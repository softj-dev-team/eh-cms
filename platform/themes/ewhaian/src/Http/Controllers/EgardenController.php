<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Garden\Models\CategoriesGarden;
use Botble\Garden\Models\Description\DescriptionGarden;
use Botble\Garden\Models\Egarden\CategoriesRoom;
use Botble\Garden\Models\Egarden\CommentsEgarden;
use Botble\Garden\Models\Egarden\Egarden;
use Botble\Garden\Models\Egarden\Room;
use Botble\Garden\Models\Egarden\RoomMember;
use Botble\Garden\Models\Egarden\RoomMemberRequest;
use Botble\Garden\Models\Notices\NoticesGarden;
use Botble\Garden\Models\PopularGarden;
use Botble\Garden\Repositories\Interfaces\Egarden\RoomInterface;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Botble\Media\Services\UploadsManager;
use Botble\Member\Models\Member;
use Botble\Setting\Models\Setting;
use Botble\Slides\Models\Slides;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Theme;

class EgardenController extends Controller
{
    /**
     * @var RoomInterface
     */
    protected $roomRepository;

    public function __construct(RoomInterface $roomRepository) {
        $this->roomRepository = $roomRepository;

        $this->middleware(function ($request, $next) {
            if (checkPasswordGarden(Cookie::get('password_garden'))) {
                return $next($request);
            } else {
                return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
            }
        });
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function transferToAdmin(Request $request, BaseHttpResponse $response) {
        try {
            $room = $this->roomRepository->findOrFail($request->room_id);

            $room->status = '화원장 신청 가능';

            $room->save();

            return $response->setMessage('Transfer ownership to admin successful');

        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage('Transfer ownership to admin failed');
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function transferToOtherUser(Request $request, BaseHttpResponse $response) {
        try {
            $room = $this->roomRepository->findOrFail($request->room_id);

            $room->member_id = $request->member_id;

            $room->save();

            return $response->setMessage('Transfer ownership to admin successful');

        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage('Transfer ownership to admin failed');
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function requestOwnership(Request $request, BaseHttpResponse $response) {
        try {
            $roomRequest = RoomMemberRequest::where('status', 'publish')
                ->where('member_id', $request->member_id)
                ->where('room_id', $request->room_id)
                ->first();

            if (isset($roomRequest) && $roomRequest->id) {
                throw new \Exception('You have requested ownership of this room');
            }

            RoomMemberRequest::create([
                'member_id' => $request->member_id,
                'room_id' => $request->room_id,
                'status' => 'publish'
            ]);

            return $response->setMessage('Request ownership successful');

        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage('Request ownership failed');
        }
    }

    public function details($id) {

        if (checkPasswordGarden(Cookie::get('password_garden'))) {

            $categories = CategoriesGarden::where('status', 'publish')
                ->orderBy('created_at', 'DESC')
                ->where('name', '!=', 'E-garden')
                ->get();
            $egarden = Egarden::where('id', $id)->orderBy('published', 'DESC')
                ->withCount([
                    'dislikes',
                ])->withCount([
                    'likes',
                ])
                ->firstOrFail();
            if ($egarden->room->statusMember(auth()->guard('member')->user()->id) == 'publish' || $egarden->member_id == auth()->guard('member')->user()->id) {
                $egarden->lookup++;
                $egarden->save();

                $comments = CommentsEgarden::where('egardens_id', $id)->where('parents_id', null)
                    ->withCount([
                        'dislikes',
                    ])->withCount([
                        'likes',
                    ])
                    ->paginate(10);
                $top_comments = CommentsEgarden::where('egardens_id', $id)->where('status', 'publish')
                    ->withCount([
                        'dislikes',
                    ])->withCount([
                        'likes',
                    ])
                    ->having('likes_count', '>', 0)
                    ->orderBy('likes_count', 'DESC')
                    ->take(3)->get();

                Theme::setTitle(__('egarden.room') . ' #' . $egarden->room->id . ' | ' . $egarden->title);
                Theme::breadcrumb()->add(__('egarden.room') . ' #' . $egarden->room->id, route('egardenFE.room.detail', ['id' => $egarden->room->id]))
//                    ->add($egarden->title, 'http:...')
                ;

                if (hasPermission('memberFE.isAdmin') || $egarden->member_id == auth()->guard('member')->user()->id) {
                    $canEdit = hasPermission('egardenFE.edit');
                    $canDelete = hasPermission('egardenFE.delete');
                } else {
                    $canEdit = false;
                    $canDelete = false;
                }
                $canCreateComment = hasPermission('egardenFE.comments.create');
                $canDeleteComment = hasPermission('egardenFE.comments.delete');
                $canViewComment = hasPermission('egardenFE.comments');

                $egardens = Egarden::whereIn('room_id', auth()->guard('member')->user()->roomJoined->pluck('id'))
                    ->orWhereIn('room_id', auth()->guard('member')->user()->roomCreated->pluck('id'))
                    ->orderBy('published', 'DESC')->paginate(10);

                return Theme::scope('garden.egarden.details', [
                    'id' => $id,
                    'categories' => $categories,
                    'egarden' => $egarden,
                    'comments' => $comments,
                    'canEdit' => $canEdit,
                    'canDelete' => $canDelete,
                    'canCreateComment' => $canCreateComment,
                    'canDeleteComment' => $canDeleteComment,
                    'canViewComment' => $canViewComment,
                    'top_comments' => $top_comments,
                    'subList' => [
                        'egarden' => $egardens,
                    ]
                ])->render();

            } else {
                return redirect()->route('egardenFE.room.detail', $egarden->room->id)->with('permission', __('controller.check_your_permission_in_room'));
            }
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public function noticeDetails($id) {

        if (checkPasswordGarden(Cookie::get('password_garden'))) {

            $categories = CategoriesGarden::where('status', 'publish')
                ->orderBy('created_at', 'DESC')
                ->where('name', '!=', 'E-garden')
                ->get();
            $egarden = Egarden::where('id', $id)->orderBy('published', 'DESC')
                ->withCount([
                    'dislikes',
                ])->withCount([
                    'likes',
                ])
                ->firstOrFail();
            if ($egarden->room->statusMember(auth()->guard('member')->user()->id) == 'publish' || $egarden->member_id == auth()->guard('member')->user()->id) {
                $egarden->lookup++;
                $egarden->save();

                $comments = CommentsEgarden::where('egardens_id', $id)->where('parents_id', null)
                    ->withCount([
                        'dislikes',
                    ])->withCount([
                        'likes',
                    ])
                    ->paginate(10);
                $top_comments = CommentsEgarden::where('egardens_id', $id)->where('status', 'publish')
                    ->withCount([
                        'dislikes',
                    ])->withCount([
                        'likes',
                    ])
                    ->having('likes_count', '>', 0)
                    ->orderBy('likes_count', 'DESC')
                    ->take(3)->get();

                Theme::setTitle(__('egarden.room') . ' #' . $egarden->room->id . ' | ' . $egarden->title);
                Theme::breadcrumb()->add(__('egarden.room') . ' #' . $egarden->room->id, route('egardenFE.room.detail', ['id' => $egarden->room->id]))
//                    ->add($egarden->title, 'http:...')
                ;

                if (hasPermission('memberFE.isAdmin') || $egarden->member_id == auth()->guard('member')->user()->id) {
                    $canEdit = hasPermission('egardenFE.edit');
                    $canDelete = hasPermission('egardenFE.delete');
                } else {
                    $canEdit = false;
                    $canDelete = false;
                }
                $canCreateComment = hasPermission('egardenFE.comments.create');
                $canDeleteComment = hasPermission('egardenFE.comments.delete');
                $canViewComment = hasPermission('egardenFE.comments');

                return Theme::scope('garden.egarden.notice_detail', [
                    'id' => $id,
                    'categories' => $categories,
                    'egarden' => $egarden,
                    'comments' => $comments,
                    'canEdit' => $canEdit,
                    'canDelete' => $canDelete,
                    'canCreateComment' => $canCreateComment,
                    'canDeleteComment' => $canDeleteComment,
                    'canViewComment' => $canViewComment,
                    'top_comments' => $top_comments,
                ])->render();

            } else {
                return redirect()->route('egardenFE.room.detail', $egarden->room->id)->with('permission', __('controller.check_your_permission_in_room'));
            }
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public static function getList($id) {

        $room = Room::where('id', $id)->where('status', 'publish')->firstOrFail();
        if ($room->member_id == auth()->guard('member')->user()->id) {
            $egarden = Egarden::orderBy('created_at', 'DESC')->where('room_id', $id)->where('status', 'draft')->paginate(9);
        } else {
            $egarden = Egarden::orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->where('room_id', $id)->paginate(9);
        }

        Theme::setTitle(__('egarden.room') . ' #' . $id . ' | ' . __('egarden.egarden_list'));

        return Theme::scope('garden.egarden.list', ['egarden' => $egarden, 'room' => $room])->render();
    }

    public function createComment(Request $request) {
        if (auth()->guard('member')->check()) {

            $anonymous = $request->is_secret_comments ?? 0;
            $egardens_id = $request->egardens_id;
            $content = $request->content;
            $parents_id = $request->parents_id;

            $comments = new CommentsEgarden;
            $comments->egardens_id = $egardens_id;
            $comments->member_id = auth()->guard('member')->user()->id;
            $comments->anonymous = $anonymous;
            $comments->content = $content;
            $comments->parents_id = $parents_id ?? null;
            $comments->anonymous = $anonymous;
            $comments->save();

            addPointForMembers(1);

            return redirect()->back();
        } else {
            // return to login
            return redirect()->back();
        }
    }

    public function deleteComment($id) {
        $comments = CommentsEgarden::findOrFail($id);

        foreach ($comments->getAllCommentByParentsID($id) as $item) {
            $item->delete();
        }
        $comments->delete();
        return redirect()->back()->with('success', __('controller.deletecomment'));
    }

    public static function getCreate($id) {
        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            $egarden = Egarden::where('status', 'draft')->where('room_id', $id)->where('member_id', auth()->guard('member')->user()->id)->first();
            if (is_null($egarden)) {
                Theme::breadcrumb()
                    ->add('E-화원', route('egardenFE.home'))
                    ->add(__('egarden.room') . ' #' . $id, route('egardenFE.room.detail', ['id' => $id]))
                    ->add(__('egarden.create_new_egarden'), 'http:...');

                Theme::setTitle(__('egarden.room') . ' #' . $id . ' | ' . __('egarden.create_new_egarden'));
                $categories = CategoriesGarden::where('status', 'publish')
                    ->orderBy('created_at', 'DESC')
                    ->where('name', '!=', 'E-garden')
                    ->get();
                $categoreisRoom = CategoriesRoom::where('room_id', $id)->get();
                return Theme::scope('garden.egarden.create', [
                    'egarden' => null,
                    'id' => $id,
                    'categories' => $categories,
                    'categoreisRoom' => $categoreisRoom,
                ])->render();
            }
            return redirect()->route('egardenFE.edit', ['idEgarden' => $egarden->id, 'id' => $id]);
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public function postStore($id, Request $request) {
        $file = [];
        $link = [];

        if (isOnBlacklist(auth()->guard('member')->user()->id)) {
            return redirect()->route('egardenFE.create', ['id' => $id])->with('permission', __('controller.accounts_that_have_been_suspended_more_than_once_cannot_open_e_hwawon'));
        }

        if (getLevelMember() < 5) {
            return redirect()->route('egardenFE.create', ['id' => $id])->with('permission', __('controller.you_must_be_level_5_or_higher_to_create_an_e_flower_garden'));
        }

        foreach ($request->input('result') as $item) {
            if ($item !== "#resultArr") {
                array_push($link, $item);
            }
        }

        $request->merge(['link' => $link]);
        if (is_null($request->input('right_click'))) {
            $request->merge(['right_click' => 0]);
        }

        if (is_null($request->input('active_empathy'))) {
            $request->merge(['active_empathy' => 0]);
        }

        $request->merge(['room_id' => $id]);

        if ($request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }

        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            $request->merge(['member_id' => auth()->guard('member')->user()->id]);
            $request->validate([
                'title' => 'required|max:8',
                'detail' => 'required',
                'status' => 'required',
                'hint' => 'max:120',
                'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb
                'banner' => 'mimes:jpeg,jpg,png,gif|required|max:2000', // max 2000kb = 2Mb
            ]);

            $egarden = new Egarden;
            $egarden = $egarden->create($request->input());

            $parent = MediaFolder::where('slug', 'egarden-fe')->first();
            $folder = MediaFolder::create([
                'name' => $egarden->id,
                'slug' => $egarden->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);

            //file
            if ($request->hasFile('file')) {
                foreach ($request->file as $key => $item) {

                    $file_link = \RvMedia::handleUpload($item, $folder->id ?? 0);
                    if ($file_link['error'] == false) {
                        array_push($file, $file_link['data']->url);
                    } else {
                        return redirect()->back()->with('err', __('controller.save_file_failed', ['file' => ($key + 1)]));
                    }
                }
                $egarden->file_upload = $file;
            }

            $banner_link = \RvMedia::handleUpload($request->banner, $folder->id ?? 0);

            if ($banner_link['error'] == true) {
                return redirect()->back()->with('err', 'Save Cover Fail');
            }

            $egarden->banner = $banner_link['data']->url;

            $egarden->save();

            addPointForMembers();
            $this->deleteFilePreview();

            event(new CreatedContentEvent(EGARDEN_MODULE_SCREEN_NAME, $request, $egarden));

            return redirect()->route('egardenFE.room.detail', ['id' => $id])->with('success', __('controller.create_successful', ['module' => __('egarden')]));
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public static function getEdit($idEgarden, $id) {

        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            $egarden = Egarden::where('member_id', auth()->guard('member')->user()->id)->where('room_id', $id)->where('id', $idEgarden)->firstOrFail();

            Theme::breadcrumb()->add(__('egarden.room') . ' #' . $id, route('egardenFE.room.detail', ['id' => $id]))->add(__('egarden.edit_egarden') . ' #' . $idEgarden, 'http:...');

            Theme::setTitle(__('egarden.room') . ' #' . $id . ' | ' . __('egarden.edit_egarden') . ' #' . $idEgarden);
            $categories = CategoriesGarden::where('status', 'publish')
                ->orderBy('created_at', 'DESC')
                ->where('name', '!=', 'E-garden')
                ->get();
            $categoreisRoom = CategoriesRoom::where('room_id', $id)->where('member_id', auth()->guard('member')->user()->id)->get();
            $canCreateCategoriesRoom = false;
            if (
            Room::where('id', $id)->where('member_id', auth()->guard('member')->user()->id)->exists()
            ) {
                $canCreateCategoriesRoom = true;
            }
            return Theme::scope('garden.egarden.create', [
                'egarden' => $egarden,
                'id' => $id,
                'categories' => $categories,
                'categoreisRoom' => $categoreisRoom,
                'canCreateCategoriesRoom' => $canCreateCategoriesRoom,
            ])->render();
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public function postUpdate($idEgarden, $id, Request $request) {
        $file_upload = [];
        $link = [];

        foreach ($request->input('result') as $item) {
            if ($item !== "#resultArr") {
                array_push($link, $item);
            }
        }

        $request->merge(['link' => $link]);

        if (is_null($request->input('right_click'))) {
            $request->merge(['right_click' => 0]);
        }

        if (is_null($request->input('active_empathy'))) {
            $request->merge(['active_empathy' => 0]);
        }

        $request->merge(['room_id' => $id]);

        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            $request->merge(['member_id' => auth()->guard('member')->user()->id]);
            $request->validate([
                'title' => 'required|max:8',
                'detail' => 'required',
                'status' => 'required',
                'hint' => 'max:120',
                'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb
            ]);

            $egarden = Egarden::where('member_id', auth()->guard('member')->user()->id)
                ->where('room_id', $id)
                ->where('id', $idEgarden)
                ->first();

            if ($request['title'] !== $egarden->title && !isAfterDay($egarden->created_at, 14)) {
                return redirect()->route('egardenFE.edit', ['idEgarden' => $idEgarden, 'id' => $id])->with('err', __('controller.the_name_of_e_hwawon_cannot_be_changed_for_2_weeks_after_creation'));
            }

            if ($egarden->published == null && $request->status != 'draft') {
                $request->merge(['published' => Carbon::now()]);
            }

            $parent = MediaFolder::where('slug', 'egarden-fe')->first();
            $folder = MediaFolder::where('slug', $egarden->id)->where('parent_id', $parent->id ?? 0)->first();

            if (is_null($folder)) {
                $folder = MediaFolder::create([
                    'name' => $egarden->id,
                    'slug' => $egarden->id,
                    'user_id' => '0',
                    'parent_id' => $parent->id ?? 0,
                ]);
            }

            if ($request->hasFile('banner')) {
                //lưu hình mới
                $image_link = \RvMedia::handleUpload($request->banner, $folder->id ?? 0);

                if ($image_link['error'] != false) {
                    return redirect()->back()->with('err', __('controller.save_failed'));
                }

                $request->merge(['banner' => $image_link['data']->url]);

                //---------- xóa hình cũ ------------
                $file = MediaFile::where('url', $egarden->banner)->first();
                if ($file) {
                    $file->delete();
                }

                $uploadManager = new UploadsManager;
                $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $egarden->banner);
                $uploadManager->deleteFile($path, 1);
                //---------- ------------------
            }

            //delete old file
            $file_delete = $egarden->file_upload;
            if ($request->input('delete') != null) {
                foreach ($request->delete as $key => $item) {
                    if ($item != null) {
                        # code...
                        $file = MediaFile::where('url', $item)->first();
                        if ($file) {
                            $file->forceDelete();
                        }
                        $uploadManager = new UploadsManager;
                        $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $item);
                        $uploadManager->deleteFile($path, 1);

                        unset($file_delete[$key]);
                    }
                }
            }

            //file
            if ($request->hasFile('file')) {
                foreach ($request->file as $key => $item) {

                    $file_link = \RvMedia::handleUpload($item, $folder->id ?? 0);
                    if ($file_link['error'] == false) {
                        if ($file_upload == null) {
                            $file_upload = [];
                        }
                        array_push($file_upload, $file_link['data']->url);
                    } else {
                        return redirect()->back()->with('err', __('controller.save_file_failed', ['file' => ($key + 1)]));
                    }
                }
            }

            if ($file_delete == null) {
                $file_delete = [];
            }

            $file_upload = array_merge($file_upload, $file_delete);
            $egarden->file_upload = $file_upload;
            $egarden = $egarden->update($request->input());
            $this->deleteFilePreview();
            event(new CreatedContentEvent(GARDEN_MODULE_SCREEN_NAME, $request, $egarden));

            return redirect()->route('egardenFE.room.detail', ['id' => $id])->with('success', __('controller.update_successful', ['module' => __('egarden')]));
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public static function room(Request $request) {
        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            $orderBy = $request->orderby;
            $categories = CategoriesGarden::where('status', 'publish')
                ->orderBy('created_at', 'DESC')
                ->where('name', '!=', 'E-garden')
                ->get();

            $categoriesEgarden = CategoriesGarden::where('status', 'publish')->where('name', 'E-garden')
                ->whereIn('special_garden', checkCanViewGarden())
                ->orderBy('created_at', 'DESC')->first();
            $notices = NoticesGarden::where('categories_gardens_id',
                CategoriesGarden::where('special_garden', CategoriesGarden::E_GARDEN)->first()->id
            )->where('status', 'publish')->orderBy('created_at', 'DESC')->get();
            $description = DescriptionGarden::where('categories_gardens_id', $categoriesEgarden->id ?? null)->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
            $slides = Slides::where('code', 'EGARDEN')->where('status', 'publish')->first();

            Theme::setTitle(__('egarden') . ' | ' . __('egarden.room'));
            $countAccess = countAccess();

            $roomJoined = auth()->guard('member')->user()->roomJoined;
            $roomCreated = auth()->guard('member')->user()->roomCreated;

            $roomID = $roomJoined->merge($roomCreated)->sortByDesc('created_at')->pluck('id');

            $builder = Room::whereIn('room.id', $roomID->toArray());
            $build = clone $builder;
            switch ($orderBy) {
                case 1:
                    $room = $build->orderBy('name', 'DESC')->paginate(9);
                    break;
                case 2:
                    # code...
                    $room = $build->withCount('member')->orderBy('member_count', 'DESC')->paginate(9);
                    break;
                case 3:
                    $tableEgarden = resolve(Egarden::class)->getTable();
                    $tableRoom = resolve(Room::class)->getTable();
                    $build->orderByDesc(DB::raw("(SELECT MAX({$tableEgarden}.created_at) FROM {$tableEgarden} WHERE {$tableEgarden}.room_id = {$tableRoom}.id)"));
                    $room = $build->paginate(9);

                    break;
                default:
                    $room = $builder->orderBy('id', 'DESC')->paginate(9);
                    break;
            }

            return Theme::scope('garden.egarden.room.home', [
                'categories' => $categories,
                'room' => $room,
                'notices' => $notices,
                'description' => $description,
                'slides' => $slides,
                'countAccess' => $countAccess,
            ])->render();
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public static function myRoom() {

        if (checkPasswordGarden(Cookie::get('password_garden'))) {

            $room = Room::withCount([
                'member',
            ])->where('member_id', auth()->guard('member')->user()->id)->where('status', 'publish')->orderBy('created_at', 'DESC')->paginate(9);
            $categories = CategoriesGarden::where('status', 'publish')
                ->orderBy('created_at', 'DESC')
                ->where('name', '!=', 'E-garden')
                ->get();

            Theme::setTitle(__('egarden.room') . ' | ' . __('egarden.room.my_room'));

            return Theme::scope('garden.egarden.room.myroom', ['categories' => $categories, 'room' => $room])->render();
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public static function ajaxJoinRoom(Request $request) {
        $id = $request->input('id');
        $room = Room::where('id', $id)->where('status', 'publish')->firstOrFail();
        $status = $room->statusMember(auth()->guard('member')->user()->id);
        switch ($status) {
            case 'draft':
                $room->member()->attach([
                    1 => [
                        'member_id' => auth()->guard('member')->user()->id,
                        'status' => 'publish',
                    ],
                ]);
                return response()->json(array('status' => 'publish'), 200);
                break;
            case 'pending':
                $room->member()->detach(auth()->guard('member')->user()->id);
                return response()->json(array('status' => 'draft'), 200);
                break;
            case 'publish':
                $room->member()->detach(auth()->guard('member')->user()->id);
                return redirect()->route('egardenFE.room.list');
                break;

            default:
                # code...
                break;
        }
    }

    public static function roomDetail($id) {
        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            $room = Room::where('id', $id)
                ->whereIn('status', [BaseStatusEnum::PUBLISH(), BaseStatusEnum::CAN_APPLY()])
                ->firstOrFail();

            $egarden = Egarden::where('room_id', $id)->where('status', 'publish')->ordered()->paginate(10);

            $categories = CategoriesGarden::where('status', 'publish')
                ->orderBy('created_at', 'DESC')
                ->where('name', '!=', 'E-garden')
                ->get();

            $notices = NoticesGarden::where('categories_gardens_id',
                CategoriesGarden::where('special_garden', CategoriesGarden::E_GARDEN)->first()->id
            )->where('status', 'publish')->orderBy('created_at', 'DESC')->get();

            Theme::setTitle(__('egarden') . ' | ' . __('egarden.room') . ' #' . $id);

            $listUserCanGetOwnership = [];
            $user = auth()->guard('member')->user();

            if ($user->id === $room->member_id) {
                // level 5: point >= 200
                $listUserCanGetOwnership = Member::where('id', '<>', $user->id)
                    ->where('is_blacklist', 0)
                    ->where('is_active', 1)
                    ->where('point', '>=', 200)
                    ->get();
            }

            return Theme::scope('garden.egarden.index', [
                'categories' => $categories,
                'egarden' => $egarden,
                'room' => $room,
                'notices' => $notices,
                'listUserCanGetOwnership' => $listUserCanGetOwnership
            ])->render();
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public static function createRoom() {
        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            Theme::breadcrumb()->add(__('egarden.room.my_room'), route('egardenFE.room.myroom.list'))->add(__('egarden.room.create_new_room'), 'http:...');
            Theme::setTitle(__('egarden') . ' | ' . __('egarden.room.my_room') . ' | ' . __('egarden.room.create_new_room'));

            return Theme::scope('garden.egarden.room.roomFE-create-edit', [
                'room' => null,
                'categoreisRoom' => [],
            ])->render();

        } else {
            return redirect()->route('egardenFE.room.list')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public static function storeRoom(Request $request) {
        $file = [];
        $link = [];

        foreach ($request->input('result') as $item) {
            if ($item !== "#resultArr") {
                array_push($link, $item);
            }
        }

        $request->merge(['link' => $link]);
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);

        $request->validate([
            'name' => 'required|max:120',
            'description' => 'required',
            'status' => 'required',
            'images' => 'mimes:jpeg,jpg,png,gif|required|max:2000', // max 2000kb = 2Mb
            'cover' => 'mimes:jpeg,jpg,png,gif|required|max:2000', // max 2000kb = 2Mb

        ]);

        $room = new Room;
        $room = $room->create($request->input());

        $parent = MediaFolder::where('slug', 'room-fe')->first();
        $folder = MediaFolder::create([
            'name' => $room->id,
            'slug' => $room->id,
            'user_id' => '0',
            'parent_id' => $parent->id ?? 0,
        ]);
        //file
        if ($request->hasFile('file')) {
            foreach ($request->file as $key => $item) {

                $file_link = \RvMedia::handleUpload($item, $folder->id ?? 0);
                if ($file_link['error'] == false) {
                    array_push($file, $file_link['data']->url);
                } else {
                    return redirect()->back()->with('err', __('controller.save_file_failed', ['file' => ($key + 1)]));
                }
            }
            $room->file_upload = $file;
        }
        $image_link = \RvMedia::handleUpload($request->images, $folder->id ?? 0);

        if ($image_link['error'] == true) {
            return redirect()->back()->with('err', __('controller.save_failed'));
        }
        $cover_link = \RvMedia::handleUpload($request->cover, $folder->id ?? 0);

        if ($cover_link['error'] == true) {
            return redirect()->back()->with('err', 'Save Cover Fail');
        }

        $room->images = $image_link['data']->url;
        $room->cover = $cover_link['data']->url;
        $room->save();

        event(new CreatedContentEvent(ROOM_MODULE_SCREEN_NAME, $request, $room));

        return redirect()->route('egardenFE.room.myroom.list')->with('success', __('controller.create_successful', ['module' => __('egarden.room')]));
    }

    public static function getEditRoom($id) {
        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            $room = Room::where('id', $id)->where('member_id', auth()->guard('member')->user()->id)->where('status', 'publish')->firstOrFail();

            Theme::breadcrumb()->add(__('egarden.room.my_room'), route('egardenFE.room.myroom.list'))->add(__('egarden.room.edit_room') . ' #' . $room->id, 'http:...');

            Theme::setTitle(__('egarden') . ' | ' . __('egarden.room.my_room') . ' | ' . __('egarden.room.edit_room') . ' #' . $room->id);
            $categoreisRoom = CategoriesRoom::where('room_id', $id)->get();
            return Theme::scope('garden.egarden.room.roomFE-create-edit', [
                'room' => $room,
                'categoreisRoom' => $categoreisRoom,
            ])->render();
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public static function postUpdateRoom($id, Request $request) {
        $file_upload = [];
        $link = [];

        foreach ($request->input('result') as $item) {
            if ($item !== "#resultArr") {
                array_push($link, $item);
            }
        }

        $request->merge(['link' => $link]);

        $request->validate([
            'name' => 'required|max:120',
            'description' => 'required',
            'status' => 'required',
        ]);

        if ($request->hasFile('images')) {
            $request->validate([
                'images' => 'mimes:jpeg,jpg,png,gif|required|max:2000', // max 2000kb = 2Mb

            ]);
        }

        $room = Room::where('id', $id)->where('member_id', auth()->guard('member')->user()->id)->where('status', 'publish')->firstOrFail();

        //--------------------------------------------------
        $parent = MediaFolder::where('slug', 'room-fe')->first();
        $folder = MediaFolder::where('slug', $room->id)->where('parent_id', $parent->id ?? 0)->first();
        if ($folder == null) {
            $folder = MediaFolder::create([
                'name' => $room->id,
                'slug' => $room->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
        }

        if ($request->hasFile('images')) {
            //lưu hình mới
            $image_link = \RvMedia::handleUpload($request->images, $folder->id ?? 0);

            if ($image_link['error'] != false) {
                return redirect()->back()->with('err', __('controller.save_failed'));
            }

            $request->merge(['images' => $image_link['data']->url]);

            //---------- xóa hình cũ ------------
            $file = MediaFile::where('url', $room->images)->first();
            if ($file) {
                $file->delete();
            }

            $uploadManager = new UploadsManager;
            $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $room->images);
            $uploadManager->deleteFile($path, 1);
            //---------- ------------------
        }

        if ($request->hasFile('cover')) {
            //lưu hình mới
            $cover_link = \RvMedia::handleUpload($request->cover, $folder->id ?? 0);

            if ($cover_link['error'] != false) {
                return redirect()->back()->with('err', __('controller.save_failed'));
            }

            $request->merge(['cover' => $cover_link['data']->url]);

            //---------- xóa hình cũ ------------
            $file = MediaFile::where('url', $room->cover)->first();
            if ($file) {
                $file->delete();
            }

            $uploadManager = new UploadsManager;
            $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $room->cover);
            $uploadManager->deleteFile($path, 1);
            //---------- ------------------
        }

        //delete old file
        $file_delete = $room->file_upload;
        if ($request->input('delete') != null) {
            foreach ($request->delete as $key => $item) {
                if ($item != null) {
                    # code...
                    $file = MediaFile::where('url', $item)->first();
                    if ($file) {
                        $file->forceDelete();
                    }
                    $uploadManager = new UploadsManager;
                    $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $item);
                    $uploadManager->deleteFile($path, 1);

                    unset($file_delete[$key]);
                }
            }
        }

        //file
        if ($request->hasFile('file')) {
            foreach ($request->file as $key => $item) {

                $file_link = \RvMedia::handleUpload($item, $folder->id ?? 0);
                if ($file_link['error'] == false) {
                    if ($file_upload == null) {
                        $file_upload = [];
                    }
                    array_push($file_upload, $file_link['data']->url);
                } else {
                    return redirect()->back()->with('err', __('controller.save_file_failed', ['file' => ($key + 1)]));
                }
            }
        }

        if ($file_delete == null) {
            $file_delete = [];
        }

        $file_upload = array_merge($file_upload, $file_delete);
        $room->file_upload = $file_upload;

        $room = $room->update($request->input());

        event(new CreatedContentEvent(ROOM_MODULE_SCREEN_NAME, $request, $room));

        return redirect()->route('egardenFE.room.edit', ['id' => $id])->with('success', __('controller.update_successful', ['module' => __('egarden.room')]));
    }

    public static function getApprovedRoom($id) {
        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            $room = Room::where('id', $id)->where('member_id', auth()->guard('member')->user()->id)->where('status', 'publish')->firstOrFail();

            $members = $room->approvedMember()->paginate(9);

            Theme::setTitle(__('egarden') . ' | ' . __('egarden.room.my_room') . ' | ' . __('egarden.room.approve_member_room'));

            return Theme::scope('garden.egarden.room.approved', ['members' => $members, 'room' => $room])->render();
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public static function postApprovedRoom($id, Request $request) {
        if (checkPasswordGarden(Cookie::get('password_garden'))) {

            $roomMember = RoomMember::where('room_id', $id)->where('member_id', $request->input('member_id'))->where('status', 'pending')->firstOrFail();
            $roomMember->status = "publish";
            $roomMember->save();

            $room = Room::where('id', $id)->where('member_id', auth()->guard('member')->user()->id)->where('status', 'publish')->firstOrFail();

            $members = $room->approvedMember()->paginate(9);

            return redirect()->route('egardenFE.room.approved', ['id' => $room->id])->with('success', __('controller.update_successful', ['module' => __('egarden.room')]));
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public function deleteRoom(Request $request, MediaFolderInterface $folderRepository, MediaFileInterface $fileRepository) {

        $this->folderRepository = $folderRepository;
        $this->fileRepository = $fileRepository;

        $id = $request->input('room_id');
        $parent = MediaFolder::where('slug', 'room-fe')->first();
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
                    return redirect()->route('egardenFE.room.myroom.list')->with('error', __('controller.delete_failed'));
                }
            }
            // xóa trong database media
            $this->fileRepository->deleteBy(['folder_id' => $folder->id]);
            $this->folderRepository->deleteFolder($folder->id, true);
        }

        try {
            $room = Room::findOrFail($id);
            $room->delete();

            event(new DeletedContentEvent(ROOM_MODULE_SCREEN_NAME, $request, $room));

            return redirect()->route('egardenFE.room.myroom.list')->with('success', __('controller.delete_successful', ['module' => __('egarden.room')]));
        } catch (Exception $exception) {
            return redirect()->route('egardenFE.room.myroom.list')->with('error', __('controller.delete_failed'));
        }
    }

    public function deleteEgarden(Request $request) {
        $id = $request->id;

        try {
            $egarden = Egarden::where('member_id', auth()->guard('member')->user()->id)->where('id', $id)->first();

            $parent = MediaFolder::where('slug', 'egarden-fe')->first();
            $folder = MediaFolder::where('slug', $request->input('id'))->where('parent_id', $parent->id)->first();
            if (!is_null($folder) && count($folder->files) > 0) {
                $directory = str_replace(basename($folder->files->first()->url), '', $folder->files->first()->url);
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
                        return redirect()->back()->with('err', __('controller.delete_failed'));
                    }
                }
                $files = MediaFile::where('folder_id', $folder->id)->get();
                // xóa trong database media
                foreach ($files as $key => $item) {
                    # code...
                    $item->forceDelete();
                }
                $folder->forceDelete();

            }

            $egarden->delete();

            event(new DeletedContentEvent(EGARDEN_MODULE_SCREEN_NAME, $request, $egarden));

            return redirect()->route('egardenFE.room.detail', ['id' => $egarden->room_id])->with('success', __('controller.delete_successful', ['module' => __('egarden')]));
        } catch (Exception $exception) {
            return redirect()->route('egardenFE.room.detail', ['id' => $egarden->room_id])->with('error', __('controller.delete_failed'));
        }
    }

    public function preview(Request $request) {
        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            $file_upload = [];
            $link = [];
            foreach ($request->input('result') as $item) {
                if ($item !== "#resultArr") {
                    array_push($link, $item);
                }
            }

            $request->merge(['link' => $link]);
            $categories = CategoriesGarden::where('status', 'publish')
                ->orderBy('created_at', 'DESC')
                ->where('name', '!=', 'E-garden')
                ->get();

            if (is_null($request->input('right_click'))) {
                $request->merge(['right_click' => 0]);
            }
            if (is_null($request->input('active_empathy'))) {
                $request->merge(['active_empathy' => 0]);
            }

            $request->merge(['member_id' => auth()->guard('member')->user()->id]);
            $request->validate([
                'title' => 'required|max:120',
                'detail' => 'required',
                'status' => 'required',
                'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:2000', // max 2000kb = 2Mb
                'banner' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000kb = 2Mb
            ]);

            $egarden = new Egarden;
            $egarden->title = $request->title;
            $egarden->detail = $request->detail;
            $egarden->right_click = $request->right_click;
            $egarden->active_empathy = $request->active_empathy;
            $egarden->link = $request->link;
            $egarden->banner = $request->base64Image;
            $parent = MediaFolder::where('slug', 'egarden-fe')->first();
            $folder = MediaFolder::where('slug', auth()->guard('member')->user()->id_login)->where('parent_id', $parent->id ?? 0)->first();
            if (is_null($folder)) {
                $folder = MediaFolder::create([
                    'name' => auth()->guard('member')->user()->id_login,
                    'slug' => auth()->guard('member')->user()->id_login,
                    'user_id' => '0',
                    'parent_id' => $parent->id ?? 0,
                ]);
            }
            if (!is_null($request->idPreview)) {
                $item_preview = Egarden::where('member_id', auth()->guard('member')->user()->id)->findOrFail($request->idPreview);
                $file_delete = $item_preview->file_upload;

                if ($request->input('delete') != null) {
                    foreach ($request->delete as $key => $item) {
                        if ($item != null) {
                            unset($file_delete[$key]);
                        }
                    }
                }
                if ($file_delete == null) {
                    $file_delete = [];
                }

                $file_upload = $file_delete;

            }
            //file
            if ($request->hasFile('file')) {
                foreach ($request->file as $key => $item) {

                    $file_link = \RvMedia::handleUpload($item, $folder->id ?? 0);
                    if ($file_link['error'] == false) {
                        if ($file_upload == null) {
                            $file_upload = [];
                        }
                        array_push($file_upload, $file_link['data']->url);
                    } else {
                        return redirect()->back()->with('err', __('controller.save_file_failed', ['file' => ($key + 1)]));
                    }
                }
            }

            $egarden->file_upload = $file_upload;

            $room = Room::where('id', $request->room_id)->where('status', 'publish')->firstOrFail();

            Theme::setTitle(__('egarden.room') . ' #' . $room->id . ' | ' . __('egarden.preview') . ' | ' . $egarden->title);
            Theme::breadcrumb()->add(__('egarden.room') . ' #' . $room->id, route('egardenFE.room.detail', ['id' => $room->id]))
//                ->add($egarden->title, 'http:...')
            ;

            return Theme::scope('garden.egarden.preview', ['egarden' => $egarden, 'categories' => $categories])->render();

        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public function deleteFilePreview() {
        $parent = MediaFolder::where('slug', 'egarden-fe')->first();
        $folder = MediaFolder::where('slug', auth()->guard('member')->user()->id_login)->where('parent_id', $parent->id)->first();

        if (!is_null($folder) && count($folder->files) > 0) {

            $directory = str_replace(basename($folder->files->first()->url), '', $folder->files->first()->url);

            if ($folder && $directory) {

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
                        return redirect()->back()->with('err', __('controller.delete_failed'));
                    }
                }
                $files = $folder->files;
                // xóa trong database media
                foreach ($files as $key => $item) {
                    # code...
                    $item->forceDelete();
                }
                $folder->forceDelete();

            }
        }

    }

    public function home() {

        $categories = CategoriesGarden::where('status', 'publish')
            ->orderBy('created_at', 'DESC')
            ->where('name', '!=', 'E-garden')
            ->get();

        // $notices = NoticesGarden::where('categories_gardens_id',
        //     CategoriesGarden::where('special_garden', CategoriesGarden::E_GARDEN)->first()->id
        // )->where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $notices = Egarden::where('status', 'notice')->orderBy('published', 'DESC')->limit(1)->get();

        $description = DescriptionGarden::where('categories_gardens_id', $categoriesEgarden->id ?? null)->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $slides = Slides::where('code', 'EGARDEN')->where('status', 'publish')->first();

        Theme::setTitle(__('egarden') . ' | ' . __('egarden.room'));
        $countAccess = countAccess();

        $egarden = Egarden::where('status', '<>', 'notice')
            ->whereIn('room_id', auth()->guard('member')->user()->roomJoined->pluck('id'))
            ->orWhereIn('room_id', auth()->guard('member')->user()->roomCreated->pluck('id'))
            ->orderBy('published', 'DESC')->paginate(10);

        $building = CategoriesGarden::where('id', 7)
            ->where('level_access', '<=', getLevelMember())
            ->where('status', 'publish')->where('name', '!=', 'E-garden');

        if (!hasPermission('memberFE.isAdmin')) {
            $building->where('level_access', '<=', getLevelMember());
        }

        $selectCategories = $building->orderBy('created_at', 'DESC')->first();
        if (is_null($selectCategories)) {
            return redirect()->route('egardenFE.home')->with('permission', __('garden.your_level_does_not_match'));
        }
        $popular = $selectCategories->popular->take(5);
        $todayPopular = $selectCategories->todaySearch();

        return Theme::scope('garden.egarden.home', [
            'categories' => $categories,
            'notices' => $notices,
            'description' => $description,
            'slides' => $slides,
            'countAccess' => $countAccess,
            'egarden' => $egarden,
            'roomJoined' => auth()->guard('member')->user()->roomJoined,
            'roomCreated' => auth()->guard('member')->user()->roomCreated,
            'popular' => $popular,
            'todayPopular' => $todayPopular,
        ])->render();
    }

    public function indexCategories($idRoom) {
        if (!Room::where('id', $idRoom)->where('member_id', auth()->guard('member')->user()->id)->where('status', 'publish')->exists()) {
            return redirect()->route('egardenFE.create', ['id', $idRoom])->with('err', __('home.no_permisson'));
        }
        $categories = CategoriesGarden::where('status', 'publish')
            ->orderBy('created_at', 'DESC')
            ->where('name', '!=', 'E-garden')
            ->get();

        $categoriesEgarden = CategoriesGarden::where('status', 'publish')->where('name', 'E-garden')
            ->whereIn('special_garden', checkCanViewGarden())
            ->orderBy('created_at', 'DESC')->first();
        $notices = NoticesGarden::where('categories_gardens_id',
            CategoriesGarden::where('special_garden', CategoriesGarden::E_GARDEN)->first()->id
        )->where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $description = DescriptionGarden::where('categories_gardens_id', $categoriesEgarden->id ?? null)->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $slides = Slides::where('code', 'EGARDEN')->where('status', 'publish')->first();
        $countAccess = countAccess();

        $categoriesRoom = CategoriesRoom::where('room_id', $idRoom)->where('member_id', auth()->guard('member')->user()->id)->paginate(10);
        return Theme::scope('garden.egarden.room.listCategories', [
            'categories' => $categories,
            'notices' => $notices,
            'description' => $description,
            'slides' => $slides,
            'countAccess' => $countAccess,
            'idRoom' => $idRoom,
            'categoriesRoom' => $categoriesRoom,
        ])->render();
    }

    public function createCategories($idRoom) {
        if (!Room::where('id', $idRoom)->where('member_id', auth()->guard('member')->user()->id)->where('status', 'publish')->exists()) {
            return redirect()->route('egardenFE.create', ['id', $idRoom])->with('err', __('home.no_permisson'));
        }
        $categories = CategoriesGarden::where('status', 'publish')
            ->orderBy('created_at', 'DESC')
            ->where('name', '!=', 'E-garden')
            ->get();

        $categoriesEgarden = CategoriesGarden::where('status', 'publish')->where('name', 'E-garden')
            ->whereIn('special_garden', checkCanViewGarden())
            ->orderBy('created_at', 'DESC')->first();
        $notices = NoticesGarden::where('categories_gardens_id',
            CategoriesGarden::where('special_garden', CategoriesGarden::E_GARDEN)->first()->id
        )->where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $description = DescriptionGarden::where('categories_gardens_id', $categoriesEgarden->id ?? null)->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $slides = Slides::where('code', 'EGARDEN')->where('status', 'publish')->first();
        $countAccess = countAccess();
        return Theme::scope('garden.egarden.room.categories', [
            'categories' => $categories,
            'notices' => $notices,
            'description' => $description,
            'slides' => $slides,
            'countAccess' => $countAccess,
            'idRoom' => $idRoom,
        ])->render();
    }

    public function storeCategories($idRoom, Request $request) {
        if (!Room::where('id', $idRoom)->where('member_id', auth()->guard('member')->user()->id)->where('status', 'publish')->exists()) {
            return redirect()->route('egardenFE.create', ['id', $idRoom])->with('err', __('home.no_permisson'));
        }

        CategoriesRoom::create([
            'name' => $request->name ?? 'N/A',
            'background' => $request->background,
            'color' => $request->color,
            'room_id' => $idRoom,
            'member_id' => auth()->guard('member')->user()->id,
        ]);

        return redirect()->route('egardenFE.room.edit', ['id' => $idRoom])->with('success', 'Create new categories success');
    }

    public function editCategories($idRoom, $id) {
        if (!Room::where('id', $idRoom)->where('member_id', auth()->guard('member')->user()->id)->where('status', 'publish')->exists()) {
            return redirect()->route('egardenFE.create', ['id', $idRoom])->with('err', __('home.no_permisson'));
        }
        $categoriesRoom = CategoriesRoom::where('room_id', $idRoom)->where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        $categories = CategoriesGarden::where('status', 'publish')
            ->orderBy('created_at', 'DESC')
            ->where('name', '!=', 'E-garden')
            ->get();

        $categoriesEgarden = CategoriesGarden::where('status', 'publish')->where('name', 'E-garden')
            ->whereIn('special_garden', checkCanViewGarden())
            ->orderBy('created_at', 'DESC')->first();
        $notices = NoticesGarden::where('categories_gardens_id',
            CategoriesGarden::where('special_garden', CategoriesGarden::E_GARDEN)->first()->id
        )->where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $description = DescriptionGarden::where('categories_gardens_id', $categoriesEgarden->id ?? null)->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $slides = Slides::where('code', 'EGARDEN')->where('status', 'publish')->first();
        $countAccess = countAccess();
        return Theme::scope('garden.egarden.room.categories', [
            'categories' => $categories,
            'notices' => $notices,
            'description' => $description,
            'slides' => $slides,
            'countAccess' => $countAccess,
            'idRoom' => $idRoom,
            'categoriesRoom' => $categoriesRoom,
        ])->render();
    }

    public function updateCategories($idRoom, $id, Request $request) {
        if (!Room::where('id', $idRoom)->where('member_id', auth()->guard('member')->user()->id)->where('status', 'publish')->exists()) {
            return redirect()->route('egardenFE.create', ['id', $idRoom])->with('err', __('home.no_permisson'));
        }
        $category = CategoriesRoom::where('room_id', $idRoom)->where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        $category->color = $request->color;
        $category->background = $request->background;
        $category->save();
        return redirect()->route('egardenFE.room.categories.list', ['idRoom' => $idRoom])->with('success', 'Update categories success');
    }

    public function deleteCategories(Request $request) {
        $idRoom = $request->idRoom;
        $categories_id = $request->categories_id;
        if (!Room::where('id', $idRoom)->where('member_id', auth()->guard('member')->user()->id)->where('status', 'publish')->exists()) {
            return redirect()->route('egardenFE.create', ['id', $idRoom])->with('err', __('home.no_permisson'));
        }

        $category = CategoriesRoom::where('room_id', $idRoom)->where('member_id', auth()->guard('member')->user()->id)->findOrFail($categories_id);
        $category->delete();

        return redirect()->route('egardenFE.room.edit', ['id' => $idRoom])->with('success', 'Delete categories success');
    }

    public function updateImportant(Request $request) {
        $idRoom = intval($request->id);
        $check = 0;
        $member_id = auth()->guard('member')->user()->id;
        $roomCreatedId = auth()->guard('member')->user()->roomCreated;
        if ($roomCreatedId->contains('id', $idRoom)) {
            $room = Room::where('id', $idRoom)->where('member_id', auth()->guard('member')->user()->id)->where('status', 'publish')->firstOrFail();
            if ($room->important === 0) {
                $room->important = 1;
                $check = 1;
            } else {
                $room->important = 0;
            };
            $room->save();
            return response()->json([
                'important' => $check
            ], 200);
        }

        $roomMember = RoomMember::where('member_id', $member_id)->where('room_id', $idRoom)->firstOrFail();
        if ($roomMember->important === 0) {
            $roomMember->important = 1;
            $check = 1;
        } else {
            $roomMember->important = 0;
        };
        $roomMember->save();
        return response()->json([
            'important' => $check
        ], 200);
    }

    public static function dislike(Request $request) {
        $id = $request->post_id;
        $reason = $request->reason;

        $sympathy = Egarden::findOrFail($id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_egardens.member_id', $user->id);
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 0);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 1,
                    'reason' => $reason,
                ]);
                $dislike = 2;
            } else {
                $check->detach($user->id);
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 1,
                    'reason' => $reason,
                ],
            ]);
            $dislike = 2;
        }
        $sympathy = Egarden::withCount([
            'dislikes',
        ])
            ->withCount([
                'likes',
            ])->findOrFail($id);

        return response()->json(
            [
                'dislikes_count' => $sympathy->dislikes_count ?? 0,
                'likes_count' => $sympathy->likes_count ?? 0,
                'disliked' => $dislike,
            ]
        );
    }

    public static function like(Request $request) {
        $id = $request->id;

        $sympathy = Egarden::findOrFail($id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_egardens.member_id', $user->id);
        $liked = 0;
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 1);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 0,
                ]);
                $liked = 2;
            } else {

                $check->detach($user->id);
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 0,
                ],
            ]);
            $liked = 1;
        }
        $sympathy = Egarden::withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])->findOrFail($id);
        return response()->json(
            [
                'dislikes_count' => $sympathy->dislikes_count ?? 0,
                'likes_count' => $sympathy->likes_count ?? 0,
                'liked' => $liked,
            ]
        );
    }

    public static function dislikeComments(Request $request) {
        $post_id = $request->post_id;
        $reason = $request->reason;
        $comment_id = $request->comment_id;
        $sympathy = CommentsEgarden::findOrFail($comment_id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_egardens_comments.member_id', $user->id);
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 0);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 1,
                    'post_id' => $post_id,
                    'reason' => $reason,
                ]);
                $dislike = 2;
            } else {
                $check->detach($user->id);
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 1,
                    'post_id' => $post_id,
                    'reason' => $reason,
                ],
            ]);
            $dislike = 2;
        }
        $sympathy = CommentsEgarden::withCount([
            'dislikes',
        ])
            ->withCount([
                'likes',
            ])->findOrFail($comment_id);

        return response()->json(
            [
                'dislikes_count' => $sympathy->dislikes_count ?? 0,
                'likes_count' => $sympathy->likes_count ?? 0,
                'disliked' => $dislike,
            ]
        );
    }

    public static function likeComments(Request $request) {

        $post_id = $request->post_id;
        $comment_id = $request->comment_id;
        $sympathy = CommentsEgarden::findOrFail($comment_id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_egardens_comments.member_id', $user->id);
        $liked = 0;
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 1);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 0,
                    'post_id' => $post_id,
                ]);
                $liked = 2;
            } else {

                $check->detach($user->id);
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 0,
                    'post_id' => $post_id,
                ],
            ]);
            $liked = 1;
        }
        $sympathy = CommentsEgarden::withCount([
            'dislikes',
        ])->withCount([
            'likes',
        ])->findOrFail($comment_id);
        return response()->json(
            [
                'dislikes_count' => $sympathy->dislikes_count ?? 0,
                'likes_count' => $sympathy->likes_count ?? 0,
                'liked' => $liked,
            ]
        );
    }
}
