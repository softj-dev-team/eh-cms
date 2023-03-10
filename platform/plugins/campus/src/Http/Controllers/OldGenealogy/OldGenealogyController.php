<?php

namespace Botble\Campus\Http\Controllers\OldGenealogy;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Campus\Forms\Genealogy\GenealogyForm;
use Botble\Campus\Forms\OldGenealogy\OldGenealogyForm;
use Botble\Campus\Http\Requests\Genealogy\GenealogyRequest;
use Botble\Campus\Http\Requests\OldGenealogy\OldGenealogyRequest;
use Botble\Campus\Repositories\Interfaces\Genealogy\GenealogyInterface;
use Botble\Campus\Repositories\Interfaces\OldGenealogy\OldGenealogyInterface;
use Botble\Campus\Tables\OldGenealogy\OldGenealogyTable;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class OldGenealogyController extends BaseController
{

    /**
     * @var OldGenealogyInterface
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
     * @param OldGenealogyInterface $oldGenealogyRepository
     * @author Sang Nguyen
     */
    public function __construct(OldGenealogyInterface $oldGenealogyRepository, MediaFolderInterface $folderRepository, MediaFileInterface $fileRepository)
    {
        $this->oldGenealogyRepository = $oldGenealogyRepository;
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
    public function getList(OldGenealogyTable $table)
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
        page_title()->setTitle("새 구 족보");

        return $formBuilder->create(OldGenealogyForm::class)->renderForm();
    }

    /**
     * Insert new campus into database
     *
     * @param OldGenealogyRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(OldGenealogyRequest $request, BaseHttpResponse $response)
    {
        if( $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['images' => json_encode($request->images)]);
        $oldGenealogy = $this->oldGenealogyRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(OLD_GENEALOGY_MODULE_SCREEN_NAME, $request, $oldGenealogy));

        return $response
            ->setPreviousUrl(route('campus.old.genealogy.list'))
            ->setNextUrl(route('campus.old.genealogy.edit', $oldGenealogy->id))
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
        $oldGenealogy = $this->oldGenealogyRepository->findOrFail($id);

        event(new BeforeEditContentEvent(OLD_GENEALOGY_MODULE_SCREEN_NAME, $request, $oldGenealogy));

        page_title()->setTitle("새 구 족보" . ' #' . $id);

        $oldGenealogy = $this->oldGenealogyRepository->findOrFail($id);
        return $formBuilder->create(OldGenealogyForm::class, ['model' => $oldGenealogy])->renderForm();
    }

    /**
     * @param $id
     * @param JobsPartTimeRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, OldGenealogyRequest $request, BaseHttpResponse $response)
    {
        $request->merge(['images' => json_encode($request->images)]);
        $oldGenealogy = $this->oldGenealogyRepository->findOrFail($id);
        if($oldGenealogy->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['link' => $request->input('link')]);
        $request->merge(['file_upload' => $request->input('file_upload')]);
        $oldGenealogy->fill($request->input());

        $this->oldGenealogyRepository->createOrUpdate($oldGenealogy);

        event(new UpdatedContentEvent(OLD_GENEALOGY_MODULE_SCREEN_NAME, $request, $oldGenealogy));

        return $response
            ->setPreviousUrl(route('campus.old.genealogy.list'))
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
        $parent = MediaFolder::where('slug', 'old-genealogy-fe')->first();
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
            $oldGenealogy = $this->oldGenealogyRepository->findOrFail($id);

            $this->oldGenealogyRepository->delete($oldGenealogy);

            event(new DeletedContentEvent(OLD_GENEALOGY_MODULE_SCREEN_NAME, $request, $oldGenealogy));

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
            $parent = MediaFolder::where('slug', 'old-genealogy-fe')->first();
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
            $oldGenealogy = $this->oldGenealogyRepository->findOrFail($id);
            $this->oldGenealogyRepository->delete($oldGenealogy);
            event(new DeletedContentEvent(OLD_GENEALOGY_MODULE_SCREEN_NAME, $request, $oldGenealogy));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
