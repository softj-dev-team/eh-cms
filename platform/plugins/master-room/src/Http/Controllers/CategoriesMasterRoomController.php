<?php

namespace Botble\MasterRoom\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\MasterRoom\Forms\CategoriesMasterRoomForm;
use Botble\MasterRoom\Http\Requests\CategoriesMasterRoomRequest;
use Botble\MasterRoom\Http\Requests\MasterRoomRequest;
use Botble\MasterRoom\Repositories\Interfaces\CategoriesMasterRoomInterface;
use Botble\MasterRoom\Repositories\Interfaces\MasterRoomInterface;
use Botble\MasterRoom\Tables\CategoriesMasterRoomTable;
use Botble\MasterRoom\Tables\MasterRoomTable;
use Exception;
use Illuminate\Http\Request;

class CategoriesMasterRoomController extends BaseController
{
    /**
     * @var CategoriesMasterRoomInterface
     */
    protected $categoriesMasterRoomRepository;

    /**
     * MasterRoomController constructor.
     * @param CategoriesMasterRoomInterface $categoriesMasterRoomRepository
     * @author Sang Nguyen
     */
    public function __construct(CategoriesMasterRoomInterface $categoriesMasterRoomRepository)
    {
        $this->categoriesMasterRoomRepository = $categoriesMasterRoomRepository;
    }

    /**
     * Display all master_rooms
     * @param CategoriesMasterRoomTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(CategoriesMasterRoomTable $table)
    {

        page_title()->setTitle('마스터룸 / 카테고리 목록');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle('마스터룸 / 카테고리 / 새로운 카테고리');

        return $formBuilder->create(CategoriesMasterRoomForm::class)->renderForm();
    }

    /**
     * Insert new MasterRoom into database
     *
     * @param CategoriesMasterRoomRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CategoriesMasterRoomRequest $request, BaseHttpResponse $response)
    {
        $master_room = $this->categoriesMasterRoomRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(CATEGORIES_MASTER_ROOM_MODULE_SCREEN_NAME, $request, $master_room));

        return $response
            ->setPreviousUrl(route('master_room.categories.list'))
            ->setNextUrl(route('master_room.categories.edit', $master_room->id))
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
        $master_room = $this->categoriesMasterRoomRepository->findOrFail($id);

        event(new BeforeEditContentEvent(CATEGORIES_MASTER_ROOM_MODULE_SCREEN_NAME, $request, $master_room));

        page_title()->setTitle('마스터룸 / 카테고리 / 편집 ' . ' #' . $id);

        return $formBuilder->create(CategoriesMasterRoomForm::class, ['model' => $master_room])->renderForm();
    }

    /**
     * @param $id
     * @param CategoriesMasterRoomRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, CategoriesMasterRoomRequest $request, BaseHttpResponse $response)
    {
        $master_room = $this->categoriesMasterRoomRepository->findOrFail($id);

        $master_room->fill($request->input());

        $this->categoriesMasterRoomRepository->createOrUpdate($master_room);

        event(new UpdatedContentEvent(CATEGORIES_MASTER_ROOM_MODULE_SCREEN_NAME, $request, $master_room));

        return $response
            ->setPreviousUrl(route('master_room.categories.list'))
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
        try {
            $master_room = $this->categoriesMasterRoomRepository->findOrFail($id);

            $this->categoriesMasterRoomRepository->delete($master_room);

            event(new DeletedContentEvent(CATEGORIES_MASTER_ROOM_MODULE_SCREEN_NAME, $request, $master_room));

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
            $master_room = $this->categoriesMasterRoomRepository->findOrFail($id);
            $this->categoriesMasterRoomRepository->delete($master_room);
            event(new DeletedContentEvent(CATEGORIES_MASTER_ROOM_MODULE_SCREEN_NAME, $request, $master_room));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
