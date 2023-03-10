<?php

namespace Botble\Campus\Http\Controllers\Genealogy;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Campus\Forms\Genealogy\GenealogyForm;
use Botble\Campus\Http\Requests\Genealogy\GenealogyRequest;
use Botble\Campus\Repositories\Interfaces\Genealogy\GenealogyInterface;
use Botble\Campus\Tables\Genealogy\GenealogyTable;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class GenealogyController extends BaseController
{

    /**
     * @var GenealogyInterface
     */
    protected $genealogyRepository;
    /**
     * @var MediaFileInterface
     */
    protected $fileRepository;
    /**
     * @var MediaFolderInterface
     */
    protected $folderRepository;

    /**
     * LifeController constructor.
     * @param GenealogyInterface $flareRepository
     * @author Sang Nguyen
     */
    public function __construct(GenealogyInterface $genealogyRepository, MediaFolderInterface $folderRepository, MediaFileInterface $fileRepository)
    {
        $this->genealogyRepository = $genealogyRepository;
        $this->folderRepository = $folderRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * Display all lives
     * @param LifeTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(GenealogyTable $table)
    {

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

        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/datetimepicker.css']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/bootstrap-select.min.css']);

        \Assets::addStylesDirectly(['/vendor/core/packages/bootstrap-datepicker/css/bootstrap-datepicker3.min.css']);

        \Assets::addScriptsDirectly(['/vendor/core/packages/bootstrap-datepicker/js/bootstrap-datepicker.min.js']);

        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/run-datetime.js']);

        page_title()->setTitle(__('header.eh_genealogy'));

        return $formBuilder->create(GenealogyForm::class)->renderForm();
    }

    /**
     * Insert new campus into database
     *
     * @param GenealogyRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(GenealogyRequest $request, BaseHttpResponse $response)
    {

        $link = json_encode($request->input('link'));
        $file_upload = json_encode($request->input('file_upload'));

        $major = $request->input('major');

        $request->merge(['link' => $link]);
        $request->merge(['file_upload' => $file_upload]);

        if ($request->input('semester_session') == 3) {
            $semester_other_textbox = $request->input('semester_other_textbox');
        } else {
            $semester_other_textbox = $request->input('semester_session');
        }
        $request->merge(['images' => json_encode($request->images), 'semester_session' => $semester_other_textbox]);
        if($request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $genealogy = $this->genealogyRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(GENEALOGY_MODULE_SCREEN_NAME, $request, $genealogy));
        $genealogy->major()->sync($major);

        return $response
            ->setPreviousUrl(route('campus.genealogy.list'))
            ->setNextUrl(route('campus.genealogy.edit', $genealogy->id))
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
        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/datetimepicker.css']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/bootstrap-select.min.css']);

        \Assets::addStylesDirectly(['/vendor/core/packages/bootstrap-datepicker/css/bootstrap-datepicker3.min.css']);

        \Assets::addScriptsDirectly(['/vendor/core/packages/bootstrap-datepicker/js/bootstrap-datepicker.min.js']);

        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/run-datetime.js']);

        $genealogy = $this->genealogyRepository->findOrFail($id);

        event(new BeforeEditContentEvent(GENEALOGY_MODULE_SCREEN_NAME, $request, $genealogy));

        page_title()->setTitle(__('header.eh_genealog'). ' #' . $id);

        $studyRoom = $this->genealogyRepository->findOrFail($id);
        return $formBuilder->create(GenealogyForm::class, ['model' => $genealogy])->renderForm();
    }

    /**
     * @param $id
     * @param JobsPartTimeRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, GenealogyRequest $request, BaseHttpResponse $response)
    {
        $link = json_encode($request->input('link'));
        $file_upload = json_encode($request->input('file_upload'));

        $major = $request->input('major');

        $request->merge(['link' => $link]);
        $request->merge(['file_upload' => $file_upload]);

        if ($request->input('semester_session') == 3) {
            $semester_other_textbox = $request->input('semester_other_textbox');
        } else {
            $semester_other_textbox = $request->input('semester_session');
        }
        $request->merge(['images' => json_encode($request->images), 'semester_session' => $semester_other_textbox]);

        $genealogy = $this->genealogyRepository->findOrFail($id);
        if($genealogy->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }

        $genealogy->fill($request->input());

        $this->genealogyRepository->createOrUpdate($genealogy);

        event(new UpdatedContentEvent(GENEALOGY_MODULE_SCREEN_NAME, $request, $genealogy));

        $genealogy->major()->sync($major);

        return $response
            ->setPreviousUrl(route('campus.genealogy.list'))
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
        $parent = MediaFolder::where('slug', 'genealogy-fe')->first();
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
            $genealogy = $this->genealogyRepository->findOrFail($id);

            $this->genealogyRepository->delete($genealogy);

            event(new DeletedContentEvent(GENEALOGY_MODULE_SCREEN_NAME, $request, $genealogy));

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
            $parent = MediaFolder::where('slug', 'genealogy-fe')->first();
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
            $genealogy = $this->genealogyRepository->findOrFail($id);
            $this->genealogyRepository->delete($genealogy);
            event(new DeletedContentEvent(GENEALOGY_MODULE_SCREEN_NAME, $request, $genealogy));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
