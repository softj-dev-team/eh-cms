<?php

namespace Botble\Campus\Http\Controllers\StudyRoom;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Campus\Http\Requests\CampusRequest;
use Botble\Campus\Repositories\Interfaces\CampusInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Campus\Tables\CampusTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Campus\Forms\CampusForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Campus\Forms\StudyRoom\StudyRoomForm;
use Botble\Campus\Http\Requests\StudyRoom\StudyRoomRequest;
use Botble\Campus\Repositories\Interfaces\StudyRoom\StudyRoomInterface;
use Botble\Campus\Tables\StudyRoom\StudyRoomTable;
use Illuminate\Filesystem\Filesystem;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Carbon\Carbon;

class StudyRoomController extends BaseController
{

    /**
     * @var ShelterInterface
     */
    protected $studyRoomRepository;
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
    public function __construct(StudyRoomInterface $studyRoomRepository, MediaFolderInterface $folderRepository, MediaFileInterface $fileRepository)
    {
        $this->studyRoomRepository = $studyRoomRepository;
        $this->folderRepository = $folderRepository;
        $this->fileRepository =  $fileRepository;
    }

    /**
     * Display all lives
     * @param LifeTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(StudyRoomTable $table)
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
        page_title()->setTitle("새로운 쉼터");

        return $formBuilder->create(StudyRoomForm::class)->renderForm();
    }

    /**
     * Insert new Life into database
     *
     * @param StudyRoomRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(StudyRoomRequest $request, BaseHttpResponse $response)
    {
        if($request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['images' => json_encode($request->images)]);
        $studyRoom = $this->studyRoomRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(STUDY_ROOM_MODULE_SCREEN_NAME, $request, $studyRoom));

        return $response
            ->setPreviousUrl(route('campus.study_room.list'))
            ->setNextUrl(route('campus.study_room.edit', $studyRoom->id))
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
        $studyRoom = $this->studyRoomRepository->findOrFail($id);

        event(new BeforeEditContentEvent(STUDY_ROOM_MODULE_SCREEN_NAME, $request, $studyRoom));

        page_title()->setTitle(__('header.study_room') . ' #' . $id);

        $studyRoom = $this->studyRoomRepository->findOrFail($id);
        return $formBuilder->create(StudyRoomForm::class, ['model' => $studyRoom])->renderForm();
    }

    /**
     * @param $id
     * @param JobsPartTimeRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, StudyRoomRequest $request, BaseHttpResponse $response)
    {

        $request->merge(['images' => json_encode($request->images)]);
        $studyRoom = $this->studyRoomRepository->findOrFail($id);
        if($studyRoom->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['link' => $request->input('link')]);
        $request->merge(['file_upload' => $request->input('file_upload')]);
        $studyRoom->fill($request->input());

        $this->studyRoomRepository->createOrUpdate($studyRoom);

        event(new UpdatedContentEvent(STUDY_ROOM_MODULE_SCREEN_NAME, $request, $studyRoom));

        return $response
            ->setPreviousUrl(route('campus.study_room.list'))
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
        $parent = MediaFolder::where('slug', 'study-room-fe')->first();
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
            $studyRoom = $this->studyRoomRepository->findOrFail($id);

            $this->studyRoomRepository->delete($studyRoom);

            event(new DeletedContentEvent(STUDY_ROOM_MODULE_SCREEN_NAME, $request, $studyRoom));

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
            $parent = MediaFolder::where('slug', 'study-room-fe')->first();
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
            $studyRoom = $this->studyRoomRepository->findOrFail($id);
            $this->studyRoomRepository->delete($studyRoom);
            event(new DeletedContentEvent(STUDY_ROOM_MODULE_SCREEN_NAME, $request, $studyRoom));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
