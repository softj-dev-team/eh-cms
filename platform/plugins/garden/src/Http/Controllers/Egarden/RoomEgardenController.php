<?php

namespace Botble\Garden\Http\Controllers\Egarden;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Garden\Forms\Egarden\RoomForm;
use Botble\Garden\Http\Requests\Egarden\RoomRequest;
use Botble\Garden\Models\Egarden\CategoriesRoom;
use Botble\Garden\Models\Egarden\Room;
use Botble\Garden\Models\Egarden\RoomMember;
use Botble\Garden\Repositories\Interfaces\Egarden\RoomInterface;
use Botble\Garden\Tables\Egarden\RoomTable;
use Botble\Member\Models\Member;
use Exception;
use Illuminate\Http\Request;

class RoomEgardenController extends BaseController
{
    /**
     * @var RoomInterface
     */
    protected $roomRepository;

    /**
     * CampusController constructor.
     * @param RoomInterface $roomRepository
     * @author Sang Nguyen
     */
    public function __construct(RoomInterface $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    /**
     * Display all campuses
     * @param RoomTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(RoomTable $table)
    {
        page_title()->setTitle('List');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        \Assets::addStylesDirectly(['/vendor/core/plugins/garden/css/multi-select.css']);

        \Assets::addScriptsDirectly(['/vendor/core/plugins/garden/js/jquery.multi-select.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/garden/js/jquery.quicksearch.js']);

        page_title()->setTitle('Create room');

        return $formBuilder->create(RoomForm::class)->renderForm();
    }

    /**
     * Insert new Campus into database
     *
     * @param RoomRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(RoomRequest $request, BaseHttpResponse $response)
    {

        $room = $this->roomRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(ROOM_MODULE_SCREEN_NAME, $request, $room));

        return $response
            ->setPreviousUrl(route('garden.egarden.room.list'))
            ->setNextUrl(route('garden.egarden.room.edit', $room->id))
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
        \Assets::addStylesDirectly(['/vendor/core/plugins/garden/css/multi-select.css']);

        \Assets::addScriptsDirectly(['/vendor/core/plugins/garden/js/jquery.multi-select.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/garden/js/jquery.quicksearch.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/garden/js/jscolor.js']);

        $room = $this->roomRepository->findOrFail($id);

        event(new BeforeEditContentEvent(ROOM_MODULE_SCREEN_NAME, $request, $room));

        page_title()->setTitle('Edit room' . ' #' . $id);

        return $formBuilder->create(RoomForm::class, ['model' => $room])->renderForm();
    }

    /**
     * @param $id
     * @param RoomRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, RoomRequest $request, BaseHttpResponse $response)
    {
        $room = $this->roomRepository->findOrFail($id);

        $room->fill($request->input());

        $this->roomRepository->createOrUpdate($room);

        event(new UpdatedContentEvent(ROOM_MODULE_SCREEN_NAME, $request, $room));

        return $response
            ->setPreviousUrl(route('garden.egarden.room.list'))
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
            $room = $this->roomRepository->findOrFail($id);

            $this->roomRepository->delete($room);

            event(new DeletedContentEvent(ROOM_MODULE_SCREEN_NAME, $request, $room));

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
            $room = $this->roomRepository->findOrFail($id);
            $this->roomRepository->delete($room);
            event(new DeletedContentEvent(ROOM_MODULE_SCREEN_NAME, $request, $room));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getSeachMember(Request $request)
    {
        $keyword = $request->keyword;
        $idRoom = $request->idRoom;
        $memberRoom = RoomMember::where('room_id', $idRoom)->pluck('member_id')->toArray();
        $data = Member::whereNotIn('id', $memberRoom)->where(function ($q) use ($keyword) {
            $q->where('fullname', 'like', '%' . $keyword . '%')
                ->orWhere('nickname', 'like', '%' . $keyword . '%')
                ->orWhere('id_login', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');
        })->limit(10)->get(['id','id_login','nickname','email']);
        return response()->json([
            'items' => $data,
        ]);
    }
    public function getSeachAuthor(Request $request)
    {
        $keyword = $request->keyword;
        $idRoom = $request->idRoom;
        $memberRoom = RoomMember::where('room_id', $idRoom)->pluck('member_id')->toArray();
        $data = Member::whereIn('id', $memberRoom)->where(function ($q) use ($keyword) {
            $q->where('fullname', 'like', '%' . $keyword . '%')
                ->orWhere('nickname', 'like', '%' . $keyword . '%')
                ->orWhere('id_login', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');
        })->limit(10)->get(['id','id_login','nickname','email']);
        return response()->json([
            'items' => $data,
        ]);
    }

    public function postAddMember(Request $request)
    {
       $member_id = $request->id;
       $idRoom = $request->idRoom;
       RoomMember::create([
            'member_id' =>  $member_id,
            'room_id' =>  $idRoom,
            'status' =>  'publish',
       ]);
       return response()->json(array('msg'=> true), 200);
    }
    public function postAddAuthor(Request $request)
    {
        $member_id = $request->id;
        $idRoom = $request->idRoom;
        $old_member = $request->old_member;
        $item = Room::where('member_id', $old_member )->where('id', $idRoom)->first();
        $item->member_id = $member_id;
        $item->save();

        $html = view('plugins.garden::elements.field.author', [
            'author' =>   $item->author,
        ])->render();

        return response()->json([
            'member' => $html,
            'old_member' => $member_id,
            'status' => 'publish',
        ], 200);
    }

    public function postRemoveMember(Request $request)
    {
        $member_id = $request->id;
        $idRoom = $request->idRoom;
        $item = RoomMember::where('member_id', $member_id )->where('room_id', $idRoom)->first();
        $item->delete();

        return response()->json(array('msg'=> true), 200);
    }

    public function postAddCategories(Request $request)
    {
        $request->validate([
            'idRoom' => 'exists:room,id',
        ]);
        $idRoom = $request->idRoom;
        $categoriesName = $request->categoriesName;
        $categoriesBackground = $request->categoriesBackground;
        $categoriesColor = $request->categoriesColor;

        CategoriesRoom::create([
            'room_id' => $idRoom,
            'name' => $categoriesName,
            'color' => $categoriesColor,
            'background' => $categoriesBackground,
        ]);

        return response()->json(array('msg'=> true), 200);
    }
    public function postRemoveCategories(Request $request)
    {
        $request->validate([
            'idRoom' => 'exists:room,id',
            'id' => 'exists:categories_room,id',
        ]);
        $idRoom = $request->idRoom;
        $id = $request->id;
        $categories = CategoriesRoom::where('room_id', $idRoom)->findOrFail($id);
        $categories->delete();
        return response()->json(array('msg'=> true), 200);
    }
}
