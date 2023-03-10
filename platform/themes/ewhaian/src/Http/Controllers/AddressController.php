<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\MasterRoom\Models\AddressMasterRoom;
use Botble\MasterRoom\Models\CategoriesMasterRoom;
use Botble\MasterRoom\Models\CommentsMasterRoom;
use Botble\MasterRoom\Models\MasterRoom;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Services\UploadsManager;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Theme;

class AddressController extends Controller
{

    /**
     * @return \Response
     */
    public static function index()
    {
        $categories = CategoriesMasterRoom::where('status','publish')->orderBy('position')->get();
        $address = AddressMasterRoom::where('status', 'publish')->orderBy('published', 'DESC')->paginate(5);
        Theme::breadcrumb()->add(__('master_room'), route('masterRoomFE.list'))->add(__('master_room.address.list'), 'http:...');
        Theme::setTitle(__('master_room') . ' | ' . __('master_room.address.list'));
        return Theme::scope('master-room.address.index', ['address' => $address, 'categories' => $categories])->render();
    }

    public function show($id, Request $request)
    {
        $idCategory = $request->input('idCategory');

        $address = MasterRoom::where('status', 'publish')->findOrFail($id);

        $address->lookup = $address->lookup + 1;
        $address->save();

        $comments = CommentsMasterRoom::where('master_rooms_id', $id)->where('parents_id', null)->paginate(10);

        $selectCategories = CategoriesMasterRoom::where('status', 'publish')->findOrFail($idCategory);
        Theme::breadcrumb()->add(__('master_room'), route('masterRoomFE.list'))->add($selectCategories->name, route('masterRoomFE.list', ['idCategory' => $idCategory]));

        $categories = CategoriesMasterRoom::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

        Theme::setTitle(__('master_room') . ' | ' . $selectCategories->name . ' | ' . $address->title);

        return Theme::scope('master-room.details', ['address' => $address, 'comments' => $comments, 'categories' => $categories, 'idCategory' => $idCategory])->render();
    }

    public static function getCreate()
    {
        $address = AddressMasterRoom::where('status','draft')->orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->first();

        if( is_null($address)){
            $categories = CategoriesMasterRoom::where('status', 'publish')->orderBy('created_at', 'DESC')->get();
            Theme::breadcrumb()->add(__('master_room'), route('masterRoomFE.list'))
                ->add(__('master_room.address.create'), 'http:...');
            Theme::setTitle(__('master_room') . ' | '. __('master_room.address.create'));

            return Theme::scope('master-room.address.create', compact('categories', 'address'))->render();
        } else {
            return redirect()->route('masterRoomFE.address.edit',['id'=>$address->id]);
        }
    }

    public static function postStore(Request $request)
    {
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);
        if($request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $address = new AddressMasterRoom;
        $address = $address->create($request->input());
        return redirect()->route('masterRoomFE.address.list')->with('success', __('controller.create_successful',['module'=>__('master_room.address')]));

    }

    public static function getEdit($id)
    {
        $address = AddressMasterRoom::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        $categories = CategoriesMasterRoom::where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        Theme::breadcrumb()->add(__('master_room'), route('masterRoomFE.list'))
            ->add(__('master_room.address.edit') . '  #' . $address->id, 'http:...');
        Theme::setTitle(__('master_room') . ' | '. __('master_room.') . '  #' . $address->id);
        return Theme::scope('master-room.address.create', compact('categories', 'address'))->render();
    }

    public static function postUpdate($id, Request $request)
    {
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);
        $address = AddressMasterRoom::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        if ($address->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $address->update($request->input());
        event(new CreatedContentEvent( ADDRESS_MASTER_ROOM_MODULE_SCREEN_NAME, $request, $address));

        return redirect()->route('masterRoomFE.address.list')->with('success', __('controller.update_successful', ['module' => __('master_room.address')]));
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $address = AddressMasterRoom::where('id', $id)->where('member_id', auth()->guard('member')->user()->id)->firstOrFail();

        try {
            $address->delete();

            event(new DeletedContentEvent(ADDRESS_MASTER_ROOM_MODULE_SCREEN_NAME, $request, $address));

            return redirect()->route('masterRoomFE.address.list')->with('success', __('controller.delete_successful', ['module' => __('master_room.address')]));
        } catch (Exception $exception) {
            return redirect()->route('masterRoomFE.address.list')->with('error', __('controller.delete_failed'));
        }
    }
}
