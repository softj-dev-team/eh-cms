<?php

namespace Botble\Life\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Life\Forms\Shelter\ShelterForm;
use Botble\Life\Http\Requests\Shelter\ShelterRequest;
use Botble\Life\Repositories\Interfaces\LifeInterface;
use Botble\Life\Repositories\Interfaces\Shelter\ShelterInterface;
use Botble\Life\Tables\LifeTable;
use Botble\Life\Tables\Shelter\ShelterTable;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class ShelterController extends BaseController
{
    /**
     * @var ShelterInterface
     */
    protected $shelterRepository;
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
     * @param LifeInterface $flareRepository
     * @author Sang Nguyen
     */
    public function __construct(ShelterInterface $shelterRepository, MediaFolderInterface $folderRepository, MediaFileInterface $fileRepository)
    {
        $this->shelterRepository = $shelterRepository;
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
    public function getList(ShelterTable $table)
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
        page_title()->setTitle("새 설명");

        return $formBuilder->create(ShelterForm::class)->renderForm();
    }

    /**
     * Insert new Life into database
     *
     * @param ShelterRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(ShelterRequest $request, BaseHttpResponse $response)
    {

        if($request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['images' => json_encode($request->images)]);
        $shelter = $this->shelterRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(SHELTER_MODULE_SCREEN_NAME, $request, $shelter));

        return $response
            ->setPreviousUrl(route('life.shelter.list'))
            ->setNextUrl(route('life.shelter.edit', $shelter->id))
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
        $shelter = $this->shelterRepository->findOrFail($id);

        event(new BeforeEditContentEvent(SHELTER_MODULE_SCREEN_NAME, $request, $shelter));

        page_title()->setTitle("수정" . ' #' . $id);

        return $formBuilder->create(ShelterForm::class, ['model' => $shelter])->renderForm();
    }

    /**
     * @param $id
     * @param ShelterRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, ShelterRequest $request, BaseHttpResponse $response)
    {
        $request->merge(['images' => json_encode($request->images)]);
        $shelter = $this->shelterRepository->findOrFail($id);
        if($shelter->published == null && $request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['link' => $request->input('link')]);
        $request->merge(['file_upload' => $request->input('file_upload')]);
        $shelter->fill($request->input());

        $this->shelterRepository->createOrUpdate($shelter);

        event(new UpdatedContentEvent(SHELTER_MODULE_SCREEN_NAME, $request, $shelter));

        return $response
            ->setPreviousUrl(route('life.shelter.list'))
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
        $parent = MediaFolder::where('slug', 'shelter-fe')->first();
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
            $shelter = $this->shelterRepository->findOrFail($id);

            $this->shelterRepository->delete($shelter);

            event(new DeletedContentEvent(SHELTER_MODULE_SCREEN_NAME, $request, $shelter));

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
            $parent = MediaFolder::where('slug', 'shelter-fe')->first();
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
            $shelter = $this->shelterRepository->findOrFail($id);
            $this->shelterRepository->delete($shelter);
            event(new DeletedContentEvent(SHELTER_MODULE_SCREEN_NAME, $request, $shelter));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
