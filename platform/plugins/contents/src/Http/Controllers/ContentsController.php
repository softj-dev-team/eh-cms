<?php

namespace Botble\Contents\Http\Controllers;

use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Contents\Forms\ContentsForm;
use Botble\Contents\Http\Requests\ContentsRequest;
use Botble\Contents\Models\Contents;
use Botble\Contents\Repositories\Interfaces\ContentsInterface;
use Botble\Contents\Tables\ContentsTable;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Throwable;

class ContentsController extends BaseController
{
    /**
     * @var ContentsInterface
     */
    protected $contentsRepository;
    /**
     * @var MediaFileInterface
     */
    protected $fileRepository;
    /**
     * @var MediaFolderInterface
     */
    protected $folderRepository;

    /**
     * ContentsController constructor.
     * @param ContentsInterface $contentsRepository
     * @author Sang Nguyen
     */
    public function __construct(ContentsInterface $contentsRepository, MediaFolderInterface $folderRepository, MediaFileInterface $fileRepository)
    {
        $this->contentsRepository = $contentsRepository;
        $this->folderRepository = $folderRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * Display all contents
     * @param ContentsTable $dataTable
     * @return Factory|View
     * @throws Throwable
     * @author Sang Nguyen
     */
    public function getList(ContentsTable $table)
    {
        Assets::addScriptsDirectly(['/js/register-main-content.js']);
        Assets::addStylesDirectly(['/css/custom.css']);

        page_title()->setTitle('목록');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        Assets::addScriptsDirectly(['/vendor/core/plugins/contents/js/moment.min.js']);

        page_title()->setTitle(trans('plugins/contents::contents.create'));

        return $formBuilder->create(ContentsForm::class)->renderForm();
    }

    /**
     * Insert new Contents into database
     *
     * @param ContentsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(ContentsRequest $request, BaseHttpResponse $response)
    {
//        $start = Carbon::createFromFormat('Y/m/d h:i a', $request->input('start'))->format('Y-m-d H:i:s');
//        $end = Carbon::createFromFormat('Y/m/d h:i a', $request->input('end'))->format('Y-m-d H:i:s');
//
//        $request->merge(['start' => $start]);
//        $request->merge(['end' => $end]);

        $request->merge(['member_id' => 0]);
        if ($request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }

        $contents = $this->contentsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(CONTENTS_MODULE_SCREEN_NAME, $request, $contents));

        return $response
            ->setPreviousUrl(route('contents.list'))
            ->setNextUrl(route('contents.edit', $contents->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * Show edit form
     *
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getEdit($id, FormBuilder $formBuilder, Request $request)
    {
        Assets::addScriptsDirectly(['/vendor/core/plugins/contents/js/moment.min.js']);
        Assets::addScriptsDirectly(['/vendor/core/plugins/contents/js/datetimepicker.home.main.min.js']);
        Assets::addScriptsDirectly(['/vendor/core/plugins/contents/js/run-datetime.js']);
        Assets::addStylesDirectly(['/vendor/core/plugins/contents/css/datetimepicker.css']);

        $contents = $this->contentsRepository->findOrFail($id);
//        $contents->start = ($contents->start) ? Carbon::createFromFormat('Y-m-d H:i:s', $contents->start)->format('Y/m/d h:i a') : null;
//        $contents->end = ($contents->end) ?  Carbon::createFromFormat('Y-m-d H:i:s',$contents->end)->format('Y/m/d h:i a') : null;

        event(new BeforeEditContentEvent(CONTENTS_MODULE_SCREEN_NAME, $request, $contents));

        page_title()->setTitle(trans('plugins/contents::contents.edit') . ' #' . $id);

        return $formBuilder->create(ContentsForm::class, ['model' => $contents])->renderForm();
    }

    /**
     * @param $id
     * @param ContentsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, ContentsRequest $request, BaseHttpResponse $response)
    {
        $contents = $this->contentsRepository->findOrFail($id);
//
//        $start = Carbon::createFromFormat('Y/m/d h:i a', $request->input('start'))->format('Y-m-d H:i:s');
//        $end = Carbon::createFromFormat('Y/m/d h:i a', $request->input('end'))->format('Y-m-d H:i:s');
//
//        $request->merge(['start' => $start]);
//        $request->merge(['end' => $end]);
        $request->merge(['link' => $request->input('link')]);
        $request->merge(['file_upload' => $request->input('file_upload')]);
        if ($contents->published == null && $request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }

        $contents->fill($request->input());

        $this->contentsRepository->createOrUpdate($contents);

        event(new UpdatedContentEvent(CONTENTS_MODULE_SCREEN_NAME, $request, $contents));

        return $response
            ->setPreviousUrl(route('contents.list'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function getDelete(Request $request, $id, BaseHttpResponse $response)
    {
        $parent = MediaFolder::where('slug', 'contents-fe')->first();
        $folder = MediaFolder::where('slug', $id)->where('parent_id', $parent->id)->first();
        if ($folder) {
            $directory = config('media.driver.' . config('filesystems.default') . '.path') . $this->folderRepository->getFullPath($folder->id);

            $file = new Filesystem;

            // Xóa file trong server
            if ($file->exists($directory)) {

                $file->cleanDirectory($directory);

                // Get all files in this directory.
                $files = $file->files($directory);

                // Check if directory is empty.
                if (empty($files)) {
                    // Yes, delete the directory.
                    $file->deleteDirectory($directory);
                } else {
                    return $response
                        ->setError()
                        ->setMessage(trans('core/base::notices.cannot_delete'));
                }
            }
            // xóa trong database media
            $this->fileRepository->deleteBy(['folder_id' => $folder->id]);
            $this->folderRepository->deleteFolder($folder->id, true);
        }

        try {
            $contents = $this->contentsRepository->findOrFail($id);

            $this->contentsRepository->delete($contents);

            event(new DeletedContentEvent(CONTENTS_MODULE_SCREEN_NAME, $request, $contents));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.cannot_delete'));
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     * @author Sang Nguyen
     */
    public function postDeleteMany(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $parent = MediaFolder::where('slug', 'contents-fe')->first();
            $folder = MediaFolder::where('slug', $id)->where('parent_id', $parent->id)->first();
            if ($folder) {
                $directory = config('media.driver.' . config('filesystems.default') . '.path') . $this->folderRepository->getFullPath($folder->id);
                $file = new Filesystem;

                // Xóa file trong server
                if ($file->exists($directory)) {

                    $file->cleanDirectory($directory);

                    // Get all files in this directory.
                    $files = $file->files($directory);

                    // Check if directory is empty.
                    if (empty($files)) {
                        // Yes, delete the directory.
                        $file->deleteDirectory($directory);
                    } else {
                        return $response
                            ->setError()
                            ->setMessage(trans('core/base::notices.cannot_delete'));
                    }
                }
                // xóa trong database media
                $this->fileRepository->deleteBy(['folder_id' => $folder->id]);
                $this->folderRepository->deleteFolder($folder->id, true);
            }
            $contents = $this->contentsRepository->findOrFail($id);
            $this->contentsRepository->delete($contents);
            event(new DeletedContentEvent(CONTENTS_MODULE_SCREEN_NAME, $request, $contents));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postRegisterMainContent(Request $request, BaseHttpResponse $response)
    {
        $type = $request->type;
        $category = $request->category;
        $content = Contents::findOrFail($request->id);

        $countIsMainContent = Contents::select('is_main_content')->where('categories_contents_id', $category)->where('is_main_content', true)->count();

        if ($type == Contents::REGISTER_MAIN_CONTENT) {
            if ($countIsMainContent == 4) {
                return $response->setError()->setMessage((__('contents.register_main_content_max_posts')));
            }
        }

        $content->is_main_content = !$content->is_main_content;
        $content->save();

        return $response->setMessage(__('contents.update_success'));
    }
}
