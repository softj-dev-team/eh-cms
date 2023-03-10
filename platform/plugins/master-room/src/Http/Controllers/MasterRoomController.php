<?php

namespace Botble\MasterRoom\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\MasterRoom\Forms\MasterRoomForm;
use Botble\MasterRoom\Http\Requests\MasterRoomRequest;
use Botble\MasterRoom\Repositories\Interfaces\MasterRoomInterface;
use Botble\MasterRoom\Tables\MasterRoomTable;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class MasterRoomController extends BaseController
{
    /**
     * @var MasterRoomInterface
     */
    protected $masterRoomRepository;

    /**
     * MasterRoomController constructor.
     * @param MasterRoomInterface $masterRoomRepository
     * @author Sang Nguyen
     */
    public function __construct(MasterRoomInterface $masterRoomRepository, MediaFolderInterface $folderRepository, MediaFileInterface $fileRepository)
    {
        $this->masterRoomRepository = $masterRoomRepository;
        $this->folderRepository = $folderRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * Display all master_rooms
     * @param MasterRoomTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(MasterRoomTable $table)
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
        \Assets::addScriptsDirectly(['/vendor/core/plugins/master-room/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/master-room/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/master-room/js/run-datetime.js']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/master-room/css/datetimepicker.css']);

        page_title()->setTitle(trans('core/base::forms.create'));

        return $formBuilder->create(MasterRoomForm::class)->renderForm();
    }

    /**
     * Insert new MasterRoom into database
     *
     * @param MasterRoomRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(MasterRoomRequest $request, BaseHttpResponse $response)
    {
//        $start = Carbon::createFromFormat('Y/m/d h:i a', $request->input('start'))->format('Y-m-d H:i:s');
//        $end = Carbon::createFromFormat('Y/m/d h:i a', $request->input('end'))->format('Y-m-d H:i:s');
//
//        $request->merge(['start' => $start]);
//        $request->merge(['end' => $end]);

        $request->merge(['member_id' => null]);

        if($request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $master_room = $this->masterRoomRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(MASTER_ROOM_MODULE_SCREEN_NAME, $request, $master_room));

        return $response
            ->setPreviousUrl(route('master_room.list'))
            ->setNextUrl(route('master_room.edit', $master_room->id))
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

        $master_room = $this->masterRoomRepository->findOrFail($id);
//        $master_room->start = Carbon::createFromFormat('Y-m-d H:i:s', $master_room->start)->format('Y/m/d h:i a');
//        $master_room->end = Carbon::createFromFormat('Y-m-d H:i:s', $master_room->end)->format('Y/m/d h:i a');

        event(new BeforeEditContentEvent(MASTER_ROOM_MODULE_SCREEN_NAME, $request, $master_room));

        page_title()->setTitle('편집하다'. ' #' . $id);

        return $formBuilder->create(MasterRoomForm::class, ['model' => $master_room])->renderForm();
    }

    /**
     * @param $id
     * @param MasterRoomRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, MasterRoomRequest $request, BaseHttpResponse $response)
    {
        $master_room = $this->masterRoomRepository->findOrFail($id);
        if($master_room->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
//        $start = Carbon::createFromFormat('Y/m/d h:i a', $request->input('start'))->format('Y-m-d H:i:s');
//        $end = Carbon::createFromFormat('Y/m/d h:i a', $request->input('end'))->format('Y-m-d H:i:s');
//
//        $request->merge(['start' => $start]);
//        $request->merge(['end' => $end]);
        $request->merge(['link' => $request->input('link')]);
        $request->merge(['file_upload' => $request->input('file_upload')]);

        $master_room->fill($request->input());

        $this->masterRoomRepository->createOrUpdate($master_room);

        event(new UpdatedContentEvent(MASTER_ROOM_MODULE_SCREEN_NAME, $request, $master_room));

        return $response
            ->setPreviousUrl(route('master_room.list'))
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
        $parent = MediaFolder::where('slug', 'master-room-fe')->first();
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
            $master_room = $this->masterRoomRepository->findOrFail($id);

            $this->masterRoomRepository->delete($master_room);

            event(new DeletedContentEvent(MASTER_ROOM_MODULE_SCREEN_NAME, $request, $master_room));

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
            $parent = MediaFolder::where('slug', 'master-room-fe')->first();
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

            $master_room = $this->masterRoomRepository->findOrFail($id);
            $this->masterRoomRepository->delete($master_room);
            event(new DeletedContentEvent(MASTER_ROOM_MODULE_SCREEN_NAME, $request, $master_room));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
