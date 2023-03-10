<?php

namespace Botble\NewContents\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\NewContents\Http\Requests\NewContentsRequest;
use Botble\NewContents\Repositories\Interfaces\NewContentsInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\NewContents\Tables\NewContentsTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\NewContents\Forms\NewContentsForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;

class NewContentsController extends BaseController
{
    /**
     * @var NewContentsInterface
     */
    protected $newContentsRepository;

    /**
     * NewContentsController constructor.
     * @param NewContentsInterface $newContentsRepository
     * @author Sang Nguyen
     */
    public function __construct(NewContentsInterface $newContentsRepository ,MediaFolderInterface $folderRepository, MediaFileInterface $fileRepository)
    {
        $this->newContentsRepository = $newContentsRepository;
        $this->folderRepository = $folderRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * Display all new_contents
     * @param NewContentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(NewContentsTable $table)
    {

        page_title()->setTitle('관리');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        \Assets::addScriptsDirectly(['/vendor/core/plugins/new-contents/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/new-contents/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/new-contents/js/run-datetime.js']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/new-contents/css/datetimepicker.css']);

        page_title()->setTitle(__('event.write'));

        return $formBuilder->create(NewContentsForm::class)->renderForm();
    }

    /**
     * Insert new NewContents into database
     *
     * @param NewContentsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(NewContentsRequest $request, BaseHttpResponse $response)
    {
        $start = Carbon::createFromFormat('Y/m/d h:i a', $request->input('start'))->format('Y-m-d H:i:s');
        $end = Carbon::createFromFormat('Y/m/d h:i a', $request->input('end'))->format('Y-m-d H:i:s');

        $request->merge(['start' => $start]);
        $request->merge(['end' => $end]);

        $request->merge(['member_id' => null]);

        if($request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }

        $new_contents = $this->newContentsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $new_contents));

        return $response
            ->setPreviousUrl(route('new_contents.list'))
            ->setNextUrl(route('new_contents.edit', $new_contents->id))
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
        \Assets::addScriptsDirectly(['/vendor/core/plugins/contents/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/contents/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/contents/js/run-datetime.js']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/contents/css/datetimepicker.css']);

        $new_contents = $this->newContentsRepository->findOrFail($id);
        $new_contents->start = Carbon::createFromFormat('Y-m-d H:i:s', $new_contents->start)->format('Y/m/d h:i a');
        $new_contents->end = Carbon::createFromFormat('Y-m-d H:i:s', $new_contents->end)->format('Y/m/d h:i a');

        event(new BeforeEditContentEvent(NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $new_contents));

        page_title()->setTitle(__('new_contents.edit_new_contents') . ' #' . $id);

        return $formBuilder->create(NewContentsForm::class, ['model' => $new_contents])->renderForm();
    }

    /**
     * @param $id
     * @param NewContentsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, NewContentsRequest $request, BaseHttpResponse $response)
    {
        $new_contents = $this->newContentsRepository->findOrFail($id);

        $start = Carbon::createFromFormat('Y/m/d h:i a', $request->input('start'))->format('Y-m-d H:i:s');
        $end = Carbon::createFromFormat('Y/m/d h:i a', $request->input('end'))->format('Y-m-d H:i:s');

        $request->merge(['start' => $start]);
        $request->merge(['end' => $end]);
        $request->merge(['link' => $request->input('link')]);
        $request->merge(['file_upload' => $request->input('file_upload')]);

        $new_contents->fill($request->input());
        if($new_contents->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }

        $this->newContentsRepository->createOrUpdate($new_contents);

        event(new UpdatedContentEvent(NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $new_contents));

        return $response
            ->setPreviousUrl(route('new_contents.list'))
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
        $parent = MediaFolder::where('slug', 'new-contents-fe')->first();
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
            $new_contents = $this->newContentsRepository->findOrFail($id);

            $this->newContentsRepository->delete($new_contents);

            event(new DeletedContentEvent(NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $new_contents));

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
     * @author Sang Nguyen
     * @throws Exception
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
            $parent = MediaFolder::where('slug', 'new-contents-fe')->first();
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


            $new_contents = $this->newContentsRepository->findOrFail($id);
            $this->newContentsRepository->delete($new_contents);
            event(new DeletedContentEvent(NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $new_contents));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
