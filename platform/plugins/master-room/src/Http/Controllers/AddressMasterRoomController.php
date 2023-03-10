<?php

namespace Botble\MasterRoom\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\MasterRoom\Forms\AddressMasterRoomForm;
use Botble\MasterRoom\Http\Requests\AddressMasterRoomRequest;
use Botble\MasterRoom\Http\Requests\MasterRoomRequest;
use Botble\MasterRoom\Repositories\Interfaces\AddressMasterRoomInterface;
use Botble\MasterRoom\Repositories\Interfaces\MasterRoomInterface;
use Botble\MasterRoom\Tables\AddressMasterRoomTable;
use Botble\MasterRoom\Tables\MasterRoomTable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class AddressMasterRoomController extends BaseController
{
    /**
     * @var AddressMasterRoomInterface
     */
    protected $addressMasterRoomRepository;

    /**
     * MasterRoomController constructor.
     * @param AddressMasterRoomInterface $addressMasterRoomRepository
     * @author Sang Nguyen
     */
    public function __construct(AddressMasterRoomInterface $addressMasterRoomRepository)
    {
        $this->addressMasterRoomRepository = $addressMasterRoomRepository;
    }

    /**
     * Display all master_rooms
     * @param AddressMasterRoomTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(AddressMasterRoomTable $table)
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
        page_title()->setTitle('새 주소');

        return $formBuilder->create(AddressMasterRoomForm::class)->renderForm();
    }

    /**
     * Insert new MasterRoom into database
     *
     * @param AddressMasterRoomRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(AddressMasterRoomRequest $request, BaseHttpResponse $response)
    {
        if($request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $address = $this->addressMasterRoomRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(ADDRESS_MASTER_ROOM_MODULE_SCREEN_NAME, $request, $address));

        return $response
            ->setPreviousUrl(route('master_room.address.list'))
            ->setNextUrl(route('master_room.address.edit', $address->id))
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
        $address = $this->addressMasterRoomRepository->findOrFail($id);

        event(new BeforeEditContentEvent(ADDRESS_MASTER_ROOM_MODULE_SCREEN_NAME, $request, $address));

        page_title()->setTitle('마스터룸 / 주소 / 수정 ' . ' #' . $id);

        return $formBuilder->create(AddressMasterRoomForm::class, ['model' => $address])->renderForm();
    }

    /**
     * @param $id
     * @param AddressMasterRoomRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, AddressMasterRoomRequest $request, BaseHttpResponse $response)
    {
        $address = $this->addressMasterRoomRepository->findOrFail($id);
        if($address->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $address->fill($request->input());

        $this->addressMasterRoomRepository->createOrUpdate($address);

        event(new UpdatedContentEvent(ADDRESS_MASTER_ROOM_MODULE_SCREEN_NAME, $request, $address));

        return $response
            ->setPreviousUrl(route('master_room.address.list'))
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
            $address = $this->addressMasterRoomRepository->findOrFail($id);

            $this->addressMasterRoomRepository->delete($address);

            event(new DeletedContentEvent(ADDRESS_MASTER_ROOM_MODULE_SCREEN_NAME, $request, $address));

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
            $address = $this->addressMasterRoomRepository->findOrFail($id);
            $this->addressMasterRoomRepository->delete($address);
            event(new DeletedContentEvent(ADDRESS_MASTER_ROOM_MODULE_SCREEN_NAME, $request, $address));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
