<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Contents\Models\CategoriesContents;
use Botble\Contents\Models\Contents;
use Botble\Garden\Models\CategoriesGarden;
use Botble\Garden\Models\CommentsGarden;
use Botble\Garden\Models\Description\DescriptionGarden;
use Botble\Garden\Models\Garden;
use Botble\Garden\Models\GardenDetail;
use Botble\Garden\Models\Notices\NoticesGarden;
use Botble\Garden\Models\SympathyGarden;
use Botble\Introduction\Models\Notices\CommentsNoticeIntroduction;
use Botble\Introduction\Models\Notices\NoticesIntroduction;
use Botble\Language\Repositories\Interfaces\LanguageInterface;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Services\UploadsManager;
use Botble\Member\Models\Member;
use Botble\Slides\Models\Slides;
use Botble\Setting\Models\Setting;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Exception;
use Theme;
use DB;
use Illuminate\Support\Facades\Validator;

class GardenController extends Controller
{
    private $numberTopComment = 3;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            if (empty(checkCanViewGarden())) {
                return redirect()->route('home.index')->with('permission', __('home.no_permisson'));
            }

            if (hasPermission('gardenFE.list')) {
                return $next($request);
            };

            return redirect()->route('home.index')->with('permission', __('home.no_permisson'));
        });
    }

    /**
     * @param Request $request
     * @param LanguageInterface $languageRepository
     * @return RedirectResponse
     * @throws Exception
     */
    public static function getPasswd(Request $request, LanguageInterface $languageRepository) {
        $passwordGarden = Setting::where('key', 'password_garden')->first();


        $language = $languageRepository->getDefaultLanguage();

        switch ($language->lang_locale) {
            case 'en':
                $date = (new Carbon($passwordGarden->updated_at))->isoFormat("Do \of MMMM");
                break;

            case 'ko':
            default:
                $date = (new Carbon($passwordGarden->updated_at))->isoFormat("MM월 DD일");
        }

        Theme::setTitle(__('garden') . ' | ' . __('garden.set_password'));

        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            return redirect()->route('gardenFE.list');
        }

        return Theme::scope('garden.passwd', ['date' => $date])->render();
    }

    /**
     * @return \Response
     */
    public static function postPasswd(Request $request) {

        // check block user
        $member = auth()->guard('member')->user();
        $dateCurrent = Carbon::now()->format('Y-m-d');
        $start_block_time = $member->start_block_time ? Carbon::parse($member->start_block_time)->format('Y-m-d') : $dateCurrent;
        $end_block_time = $member->end_block_time ? Carbon::parse($member->end_block_time)->format('Y-m-d') : $dateCurrent;
        if($member->block_user == 1 && $start_block_time <= $dateCurrent && $end_block_time >= $dateCurrent){
            $msg = '이 페이지에 접속할 권한이 없습니다.';
            if($member->end_block_time){
                $msg = Carbon::parse($member->end_block_time)->format('Y-m-d') .' 까지 이 페이지에 접속할 권한이 없습니다.';
            }
            return redirect()->route('gardenFE.passwd')->with('permission', $msg);
        }

        if (checkPasswordGarden($request->input('password'))) {
            set_cookie_response($request->input('password'));
            addAccessGarden();
            return redirect()->route('gardenFE.list');
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.please_check_your_password'));
        }
    }

    public static function ajaxPasswd(Request $request) {

        if (checkPasswordGarden($request->input('password'))) {
            set_cookie_response($request->input('password'));
            return response()->json(array('check' => true), 200);
        } else {
            $msg = "<div style='color: #EC1469;'>" . __('controller.please_check_your_password') . "</div>";
            return response()->json(array('msg' => $msg, 'check' => false), 200);
        }
    }

    public static function ajaxPasswdPost(Request $request) {
        //garden create
        $is_create = $request->is_create ?? 0;
        if ($is_create > 0) {
            return response()->json(array('check' => true), 200);
        }

        //garden update
        $id = $request->id;
        $pwd_post = $request->pwd_post;
        $garden = Garden::find($id);

        if (!is_null($garden)) {
            if (Hash::check($pwd_post, $garden->pwd_post)) {
                session(['pwd_post' => [
                    $garden->id => $pwd_post
                ]]);
                return response()->json(array('check' => true), 200);
            }

            $msg = "<div style='color: #EC1469;'>" . __('controller.please_check_your_password') . "</div>";
            return response()->json(array('msg' => $msg, 'check' => false), 200);

        } else {

            $msg = "<div style='color: #EC1469;'>" . __('controller.please_check_your_password') . "</div>";
            return response()->json(array('msg' => $msg, 'check' => false), 200);
        }
    }

    /**
     * @return \Response
     */
    public static function index() {
        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            $categories = CategoriesGarden::where('status', 'publish')->orderBy('created_at', 'DESC')->get();
            $building = CategoriesGarden::where('status', 'publish')->whereIn('special_garden', checkCanViewGarden());

            if (!hasPermission('memberFE.isAdmin')) {
                $building->where('level_access', '<=', getLevelMember());
            }

            $selectCategories = $building->orderBy('created_at', 'DESC')->first();
            if (is_null($selectCategories)) {
                return redirect()->route('egardenFE.home')->with('permission', __('garden.your_level_does_not_match'));
            }

            $startDay = date('Y-m-d').' 00:00:00';
            $garden = Garden::where('published', '>=',$startDay)
                ->where('categories_gardens_id', $selectCategories->id)
                ->withCount(['dislikes'])
                ->where('status', 'publish')
//                 ->where('status', '<>', 'notice')
                ->ordered()
                ->paginate(15);

            $popular = $selectCategories->popular->take(5);

            $todayPopular = $selectCategories->todaySearch();

            Theme::setTitle(__('garden') . ' | ' . $selectCategories->name);

            $notices = NoticesIntroduction::code(GARDEN_MODULE_SCREEN_NAME . '-' . $selectCategories->id)->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();

            $description = DescriptionGarden::where('categories_gardens_id', $selectCategories->id)->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

            Theme::breadcrumb()->add(__('garden'), route('gardenFE.list'))->add($selectCategories->name, route('gardenFE.show', ['id' => $selectCategories->id]));

            $slides = Slides::where('code', 'GARDEN')->where('status', 'publish')->first();
            $countAccess = countAccess();

            switch ($selectCategories->special_garden) {
                case CategoriesGarden::PAST_GARDEN:
                    $permission = 'gardenFE.create.past_garden';
                    break;
                case CategoriesGarden::LAW_GARDEN:
                    $permission = 'gardenFE.create.law_garden';
                    break;
                case CategoriesGarden::JOB_GARDEN:
                    $permission = 'gardenFE.create.job_garden';
                    break;
                case CategoriesGarden::GRADUATION_GARDEN:
                    $permission = 'gardenFE.create.graduation_garden';
                    break;
                case CategoriesGarden::SECRET_GARDEN:
                    $permission = 'gardenFE.create.secret_garden';
                    break;
                case CategoriesGarden::SPROUT_GARDEN:
                    $permission = 'gardenFE.create.sprout_garden';
                    break;
                default:
                    $permission = 'gardenFE.create.past_garden';
                    break;
            }

            $canCreate = hasPermission($permission);

            return Theme::scope('garden.index', [
                'categories' => $categories, 'garden' => $garden,
                'selectCategories' => $selectCategories, 'popular' => $popular,
                'idCategory' => $selectCategories->id,
                'notices' => $notices, 'description' => $description,
                'slides' => $slides, 'countAccess' => $countAccess,
                'todayPopular' => $todayPopular,
                'canCreate' => $canCreate,
            ])->render();

        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public function show($id) {
        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            $categories = CategoriesGarden::where('status', 'publish')->orderBy('id', 'DESC')->get();
            $slides = Slides::where('code', 'GARDEN')->where('status', 'publish')->first();

            $selectCategories = $categories->where('id', $id)->whereIn('special_garden', checkCanViewGarden())->first();
            if (is_null($selectCategories)) {
                return redirect()->route('egardenFE.home')->with('permission', __('home.no_permisson'));
            }

            Theme::setTitle(__('garden') . ' | ' . $selectCategories->name);
            Theme::breadcrumb()->add(__('garden'), route('gardenFE.list'))->add($selectCategories->name, route('gardenFE.show', ['id' => $selectCategories->id]));

            if (!hasPermission('memberFE.isAdmin')) {
                if ($selectCategories->level_access > getLevelMember()) {
                    return Theme::scope('garden.index', [
                        'categories' => $categories,
                        'idCategory' => $id,
                        'selectCategories' => $selectCategories,
                        'slides' => $slides,
                        'no_permission' => __('home.no_permisson_leve_10')
                    ])->render();
                }
            }

            $startDay = date('Y-m-d').' 00:00:00';
            $garden = Garden::where('published', '>=',$startDay)
                ->where('categories_gardens_id', $selectCategories->id)
                ->ordered()
                ->paginate(15);

            $popular = $selectCategories->popular->take(5);
            $todayPopular = $selectCategories->todaySearch();

            $notices = NoticesIntroduction::code(GARDEN_MODULE_SCREEN_NAME . '-' . $selectCategories->id)->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();

            $description = DescriptionGarden::where('categories_gardens_id', $selectCategories->id)->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

            $countAccess = countAccess();
            switch ($selectCategories->special_garden) {
                case CategoriesGarden::PAST_GARDEN:
                    $permission = 'gardenFE.create.past_garden';
                    break;
                case CategoriesGarden::LAW_GARDEN:
                    $permission = 'gardenFE.create.law_garden';
                    break;
                case CategoriesGarden::JOB_GARDEN:
                    $permission = 'gardenFE.create.job_garden';
                    break;
                case CategoriesGarden::GRADUATION_GARDEN:
                    $permission = 'gardenFE.create.graduation_garden';
                    break;
                case CategoriesGarden::SECRET_GARDEN:
                    $permission = 'gardenFE.create.secret_garden';
                    break;
                case CategoriesGarden::SPROUT_GARDEN:
                    $permission = 'gardenFE.create.sprout_garden';
                    break;
                default:
                    $permission = 'gardenFE.create.past_garden';
                    break;
            }
            $canCreate = hasPermission($permission);
            $member = $member = auth()->guard('member')->user();

            return Theme::scope('garden.index', [
                'categories' => $categories,
                'idCategory' => $id,
                'garden' => $garden, 'selectCategories' => $selectCategories,
                'popular' => $popular, 'notices' => $notices,
                'description' => $description, 'slides' => $slides,
                'countAccess' => $countAccess,
                'todayPopular' => $todayPopular,
                'canCreate' => $canCreate,
                'member' => $member,
            ])->render();
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public function detailNotice($idCategory, $id)
    {
        $notices = NoticesIntroduction::where('id', $id)->where('status', 'publish')->first();
        $notices->lookup = $notices->lookup + 1;
        $notices->save();

        $categories = CategoriesGarden::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

        $selectCategories = $categories->where('id', $idCategory)->whereIn('special_garden', checkCanViewGarden())->first();

        Theme::setTitle(__('garden') . ' | ' . $selectCategories->name);
        Theme::breadcrumb()->add(__('garden'), route('gardenFE.list'))->add($selectCategories->name, route('gardenFE.show', ['id' => $selectCategories->id]));

        switch ($selectCategories->special_garden) {
            case CategoriesGarden::PAST_GARDEN:
                $permission = 'gardenFE.create.past_garden';
                break;
            case CategoriesGarden::LAW_GARDEN:
                $permission = 'gardenFE.create.law_garden';
                break;
            case CategoriesGarden::JOB_GARDEN:
                $permission = 'gardenFE.create.job_garden';
                break;
            case CategoriesGarden::GRADUATION_GARDEN:
                $permission = 'gardenFE.create.graduation_garden';
                break;
            case CategoriesGarden::SECRET_GARDEN:
                $permission = 'gardenFE.create.secret_garden';
                break;
            case CategoriesGarden::SPROUT_GARDEN:
                $permission = 'gardenFE.create.sprout_garden';
                break;
            default:
                $permission = 'gardenFE.create.past_garden';
                break;
        }
        $canCreate = hasPermission($permission);
        $gardens = Garden::where('categories_gardens_id', $idCategory)
            ->ordered()->paginate(15);

        // comment notice
        $top_comments = CommentsGarden::where('gardens_id', $id)
            ->where("is_deleted",0)
            ->withCount(['dislikes'])
            ->withCount(['likes'])
            ->has('likes', '>', 0)
            ->orderBy('likes_count', 'DESC')
            ->take($this->numberTopComment)->get();

        $comments = CommentsNoticeIntroduction::where('notice_introduction_id', $id)->where('parents_id', null)
            ->withCount(['dislikes'])
            ->withCount(['likes'])
            ->orderBy('likes_count', 'ASC')
            ->paginate(10);

        switch ($selectCategories->special_garden) {
            case CategoriesGarden::PAST_GARDEN:
                $permissionEdit = 'gardenFE.edit.past_garden';
                $permissionDelete = 'gardenFE.delete.past_garden';
                $canCreateComment = 'gardenFE.comments.create.past_garden';
                $canDeleteComment = 'gardenFE.comments.delete.past_garden';
                break;
            case CategoriesGarden::LAW_GARDEN:
                $permissionEdit = 'gardenFE.edit.law_garden';
                $permissionDelete = 'gardenFE.delete.law_garden';
                $canCreateComment = 'gardenFE.comments.create.law_garden';
                $canDeleteComment = 'gardenFE.comments.delete.law_garden';
                break;
            case CategoriesGarden::JOB_GARDEN:
                $permissionEdit = 'gardenFE.edit.job_garden';
                $permissionDelete = 'gardenFE.delete.job_garden';
                $canCreateComment = 'gardenFE.comments.create.job_garden';
                $canDeleteComment = 'gardenFE.comments.delete.job_garden';
                break;
            case CategoriesGarden::GRADUATION_GARDEN:
                $permissionEdit = 'gardenFE.edit.graduation_garden';
                $permissionDelete = 'gardenFE.delete.graduation_garden';
                $canCreateComment = 'gardenFE.comments.create.graduation_garden';
                $canDeleteComment = 'gardenFE.comments.delete.graduation_garden';
                break;
            case CategoriesGarden::SECRET_GARDEN:
                $permissionEdit = 'gardenFE.edit.secret_garden';
                $permissionDelete = 'gardenFE.delete.secret_garden';
                $canCreateComment = 'gardenFE.comments.create.secret_garden';
                $canDeleteComment = 'gardenFE.comments.delete.secret_garden';
                break;
            case CategoriesGarden::SPROUT_GARDEN:
                $permissionEdit = 'gardenFE.edit.sprout_garden';
                $permissionDelete = 'gardenFE.delete.sprout_garden';
                $canCreateComment = 'gardenFE.comments.create.sprout_garden';
                $canDeleteComment = 'gardenFE.comments.delete.sprout_garden';
                break;
            default:
                $permissionEdit = 'gardenFE.edit.past_garden';
                $permissionDelete = 'gardenFE.delete.past_garden';
                $canCreateComment = 'gardenFE.comments.create.past_garden';
                $canDeleteComment = 'gardenFE.comments.delete.past_garden';
                break;
        }
        if (hasPermission('memberFE.isAdmin') || $notices->member_id == auth()->guard('member')->user()->id) {
            $canEdit = hasPermission($permissionEdit);
            $canDelete = hasPermission($permissionDelete);
        } else {
            $canEdit = false;
            $canDelete = false;
        }
        if ( $idCategory == 3 || $idCategory == 4 ){
            $canEdit = false;
            $canDelete = false;
        }

        $canDeleteComment = hasPermission($canDeleteComment);
        $canViewComment = hasPermission('gardenFE.comments');
        $canCreateComment = hasPermission($canCreateComment);


        return Theme::scope('garden.notice', [
            'id' => $id,
            'categories' => $categories,
            'garden' => $gardens,
            'comments' => $comments,
            'notices' => $notices,
            'selectCategories' => $selectCategories,
            'canEdit' => $canEdit ?? null,
            'canDelete' => $canDelete,
            'canCreateComment' => $canCreateComment,
            'canDeleteComment' => $canDeleteComment,
            'canViewComment' => $canViewComment,
            'top_comments' => $top_comments,
            'subList' => [
                'categories' => $categories,
                'selectCategories' => $selectCategories,
                'garden' => $gardens,
                'canCreate' => $canCreate,
            ]
        ])->render();
    }

    public function details($idCategories, $id) {
        if (checkPasswordGarden(Cookie::get('password_garden'))) {

            $categories = CategoriesGarden::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

            $selectCategories = $categories->where('id', $idCategories)->whereIn('special_garden', checkCanViewGarden())->first();
            if (is_null($selectCategories)) {
                return redirect()->route('gardenFE.show', ['id' => 1])
                    ->with('permission', __('home.no_permisson'));
            }

            if (!hasPermission('memberFE.isAdmin')) {
                if ($selectCategories->level_access > getLevelMember()) {
                    return redirect()->route('egardenFE.home')->with('permission', __('garden.your_level_does_not_match'));
                }
            }

            $garden = Garden::with('gardenDetail')->withCount(['dislikes'])
                ->withCount(['likes'])
                ->has('dislikes', '<', 10)
                ->where('status', 'publish')->orderBy('created_at', 'DESC')->where('id', $id)->firstOrFail();

            $member = auth()->guard('member')->user();
            $isBookmarkByMember = $garden->isBookmarkedBy($member);
            $ipAddress = $garden->gardenDetail->ip_address ?? '';
            if ($ipAddress) {
                $ipAddress = explode('.', $ipAddress);
                $ipAddress = $ipAddress[0] . '.' . $ipAddress[1] . '.' . $ipAddress[2] . '.' . 'XXX';
            }

            if ($garden->member_id != auth()->guard('member')->user()->id) {
                if (!hasPermission('gardenFE.bypass_password_requirement') && !is_null($garden->pwd_post)) {
                    if (!isset(session()->get('pwd_post')[$id]) || !Hash::check(session()->get('pwd_post')[$id], $garden->pwd_post)) {
                        return redirect()->route('gardenFE.show', ['id' => $idCategories])->with('err', 'Check your password');
                    }
                }
            }

            $garden->lookup++;
            $garden->save();

            $top_comments = CommentsGarden::where('gardens_id', $id)
                ->where("is_deleted",0)
                ->withCount(['dislikes'])
                ->withCount(['likes'])
                ->has('likes', '>', 0)
                ->orderBy('likes_count', 'DESC')
                ->take($this->numberTopComment)->get();

            $topIds = $top_comments->pluck('id')->toArray();

            $comments = CommentsGarden::where('gardens_id', $id)->where('parents_id', null)
                //->whereNotIn('id', $topIds)
                ->withCount(['dislikes'])
                ->withCount(['likes'])
                ->orderBy('likes_count', 'ASC')
//                ->orderBy('created_at', 'DESC')
                ->paginate(10);



            Theme::setTitle(__('garden') . ' | ' . $garden->title);
            Theme::breadcrumb()->add($selectCategories->name, route('gardenFE.show', ['id' => $selectCategories->id]))
//                ->add($garden->title, 'http:...')
            ;
            switch ($selectCategories->special_garden) {
                case CategoriesGarden::PAST_GARDEN:
                    $permissionEdit = 'gardenFE.edit.past_garden';
                    $permissionDelete = 'gardenFE.delete.past_garden';
                    $canCreateComment = 'gardenFE.comments.create.past_garden';
                    $canDeleteComment = 'gardenFE.comments.delete.past_garden';
                    break;
                case CategoriesGarden::LAW_GARDEN:
                    $permissionEdit = 'gardenFE.edit.law_garden';
                    $permissionDelete = 'gardenFE.delete.law_garden';
                    $canCreateComment = 'gardenFE.comments.create.law_garden';
                    $canDeleteComment = 'gardenFE.comments.delete.law_garden';
                    break;
                case CategoriesGarden::JOB_GARDEN:
                    $permissionEdit = 'gardenFE.edit.job_garden';
                    $permissionDelete = 'gardenFE.delete.job_garden';
                    $canCreateComment = 'gardenFE.comments.create.job_garden';
                    $canDeleteComment = 'gardenFE.comments.delete.job_garden';
                    break;
                case CategoriesGarden::GRADUATION_GARDEN:
                    $permissionEdit = 'gardenFE.edit.graduation_garden';
                    $permissionDelete = 'gardenFE.delete.graduation_garden';
                    $canCreateComment = 'gardenFE.comments.create.graduation_garden';
                    $canDeleteComment = 'gardenFE.comments.delete.graduation_garden';
                    break;
                case CategoriesGarden::SECRET_GARDEN:
                    $permissionEdit = 'gardenFE.edit.secret_garden';
                    $permissionDelete = 'gardenFE.delete.secret_garden';
                    $canCreateComment = 'gardenFE.comments.create.secret_garden';
                    $canDeleteComment = 'gardenFE.comments.delete.secret_garden';
                    break;
                case CategoriesGarden::SPROUT_GARDEN:
                    $permissionEdit = 'gardenFE.edit.sprout_garden';
                    $permissionDelete = 'gardenFE.delete.sprout_garden';
                    $canCreateComment = 'gardenFE.comments.create.sprout_garden';
                    $canDeleteComment = 'gardenFE.comments.delete.sprout_garden';
                    break;
                default:
                    $permissionEdit = 'gardenFE.edit.past_garden';
                    $permissionDelete = 'gardenFE.delete.past_garden';
                    $canCreateComment = 'gardenFE.comments.create.past_garden';
                    $canDeleteComment = 'gardenFE.comments.delete.past_garden';
                    break;
            }
            if (hasPermission('memberFE.isAdmin') || $garden->member_id == auth()->guard('member')->user()->id) {
                $canEdit = hasPermission($permissionEdit);
                $canDelete = hasPermission($permissionDelete);
            } else {
                $canEdit = false;
                $canDelete = false;
            }
            if($idCategories==3||$idCategories==4){
                $canEdit = false;
                $canDelete = false;
            }

            $canDeleteComment = hasPermission($canDeleteComment);
            $canViewComment = hasPermission('gardenFE.comments');
            $canCreateComment = hasPermission($canCreateComment);

            switch ($selectCategories->special_garden) {
                case CategoriesGarden::PAST_GARDEN:
                    $permission = 'gardenFE.create.past_garden';
                    break;
                case CategoriesGarden::LAW_GARDEN:
                    $permission = 'gardenFE.create.law_garden';
                    break;
                case CategoriesGarden::JOB_GARDEN:
                    $permission = 'gardenFE.create.job_garden';
                    break;
                case CategoriesGarden::GRADUATION_GARDEN:
                    $permission = 'gardenFE.create.graduation_garden';
                    break;
                case CategoriesGarden::SECRET_GARDEN:
                    $permission = 'gardenFE.create.secret_garden';
                    break;
                case CategoriesGarden::SPROUT_GARDEN:
                    $permission = 'gardenFE.create.sprout_garden';
                    break;
                default:
                    $permission = 'gardenFE.create.past_garden';
                    break;
            }
            $canCreate = hasPermission($permission);
        //    $gardens = Garden::where('published', '>=', date('Y-m-d').' 00:00:00')
            $gardens = Garden::where('categories_gardens_id', $selectCategories->id)
                ->ordered()->paginate(15);

            return Theme::scope('garden.details', [
                'id' => $id,
                'categories' => $categories,
                'member' => $member,
                'garden' => $garden,
                'comments' => $comments,
                'selectCategories' => $selectCategories,
                'canEdit' => $canEdit,
                'canDelete' => $canDelete,
                'canCreateComment' => $canCreateComment,
                'canDeleteComment' => $canDeleteComment,
                'canViewComment' => $canViewComment,
                'top_comments' => $top_comments,
                'ip_address' => $ipAddress,
                'subList' => [
                    'categories' => $categories,
                    'selectCategories' => $selectCategories,
                    'garden' => $gardens,
                    'canCreate' => $canCreate,
                ],
                'isBookmarkByMember' => $isBookmarkByMember
            ])->render();
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public function noticeDetails($idCategories, $id) {
        if (checkPasswordGarden(Cookie::get('password_garden'))) {

            $categories = CategoriesGarden::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

            $selectCategories = $categories->where('id', $idCategories)->whereIn('special_garden', checkCanViewGarden())->first();
            if (is_null($selectCategories)) {
                return redirect()->route('egardenFE.home')->with('permission', __('home.no_permisson'));
            }

            if (!hasPermission('memberFE.isAdmin')) {
                if ($selectCategories->level_access > getLevelMember()) {
                    return redirect()->route('egardenFE.home')->with('permission', __('garden.your_level_does_not_match'));
                }
            }

            $garden = Garden::where('id', $id)->withCount(['dislikes'])
                ->withCount(['likes'])
                ->has('dislikes', '<', 10)
                ->firstOrFail();

            if (!hasPermission('gardenFE.bypass_password_requirement') && !is_null($garden->pwd_post)) {
                if (!isset(session()->get('pwd_post')[$id]) || !Hash::check(session()->get('pwd_post')[$id], $garden->pwd_post)) {
                    return redirect()->route('gardenFE.show', ['id' => $idCategories])->with('err', 'Check your password');
                }
            }
            $garden->lookup++;
            $garden->save();

            $comments = CommentsGarden::where('gardens_id', $id)->where('parents_id', null)
                ->withCount(['dislikes'])
                ->withCount(['likes'])
                ->orderBy('likes_count', 'ASC')
                ->paginate(10);

            $top_comments = CommentsGarden::where('gardens_id', $id)
                ->withCount(['dislikes'])
                ->withCount(['likes'])
                ->orderBy('likes_count', 'DESC')
                ->take(3)->get();

            Theme::setTitle(__('garden') . ' | ' . $garden->title);
            Theme::breadcrumb()->add($selectCategories->name, route('gardenFE.show', ['id' => $selectCategories->id]))
            //    ->add($garden->title, 'http:...')
            ;
            switch ($selectCategories->special_garden) {
                case CategoriesGarden::PAST_GARDEN:
                    $permissionEdit = 'gardenFE.edit.past_garden';
                    $permissionDelete = 'gardenFE.delete.past_garden';
                    $canCreateComment = 'gardenFE.comments.create.past_garden';
                    $canDeleteComment = 'gardenFE.comments.delete.past_garden';
                    break;
                case CategoriesGarden::LAW_GARDEN:
                    $permissionEdit = 'gardenFE.edit.law_garden';
                    $permissionDelete = 'gardenFE.delete.law_garden';
                    $canCreateComment = 'gardenFE.comments.create.law_garden';
                    $canDeleteComment = 'gardenFE.comments.delete.law_garden';
                    break;
                case CategoriesGarden::JOB_GARDEN:
                    $permissionEdit = 'gardenFE.edit.job_garden';
                    $permissionDelete = 'gardenFE.delete.job_garden';
                    $canCreateComment = 'gardenFE.comments.create.job_garden';
                    $canDeleteComment = 'gardenFE.comments.delete.job_garden';
                    break;
                case CategoriesGarden::GRADUATION_GARDEN:
                    $permissionEdit = 'gardenFE.edit.graduation_garden';
                    $permissionDelete = 'gardenFE.delete.graduation_garden';
                    $canCreateComment = 'gardenFE.comments.create.graduation_garden';
                    $canDeleteComment = 'gardenFE.comments.delete.graduation_garden';
                    break;
                case CategoriesGarden::SECRET_GARDEN:
                    $permissionEdit = 'gardenFE.edit.secret_garden';
                    $permissionDelete = 'gardenFE.delete.secret_garden';
                    $canCreateComment = 'gardenFE.comments.create.secret_garden';
                    $canDeleteComment = 'gardenFE.comments.delete.secret_garden';
                    break;
                case CategoriesGarden::SPROUT_GARDEN:
                    $permissionEdit = 'gardenFE.edit.sprout_garden';
                    $permissionDelete = 'gardenFE.delete.sprout_garden';
                    $canCreateComment = 'gardenFE.comments.create.sprout_garden';
                    $canDeleteComment = 'gardenFE.comments.delete.sprout_garden';
                    break;
                default:
                    $permissionEdit = 'gardenFE.edit.past_garden';
                    $permissionDelete = 'gardenFE.delete.past_garden';
                    $canCreateComment = 'gardenFE.comments.create.past_garden';
                    $canDeleteComment = 'gardenFE.comments.delete.past_garden';
                    break;
            }
            if (hasPermission('memberFE.isAdmin') || $garden->member_id == auth()->guard('member')->user()->id) {
                $canEdit = hasPermission($permissionEdit);
                $canDelete = hasPermission($permissionDelete);
            } else {
                $canEdit = false;
                $canDelete = false;
            }
            if($idCategories==3||$idCategories==4){
                $canEdit = false;
                $canDelete = false;
            }

            $canDeleteComment = hasPermission($canDeleteComment);
            $canViewComment = hasPermission('gardenFE.comments');
            $canCreateComment = hasPermission($canCreateComment);

            switch ($selectCategories->special_garden) {
                case CategoriesGarden::PAST_GARDEN:
                    $permission = 'gardenFE.create.past_garden';
                    break;
                case CategoriesGarden::LAW_GARDEN:
                    $permission = 'gardenFE.create.law_garden';
                    break;
                case CategoriesGarden::JOB_GARDEN:
                    $permission = 'gardenFE.create.job_garden';
                    break;
                case CategoriesGarden::GRADUATION_GARDEN:
                    $permission = 'gardenFE.create.graduation_garden';
                    break;
                case CategoriesGarden::SECRET_GARDEN:
                    $permission = 'gardenFE.create.secret_garden';
                    break;
                case CategoriesGarden::SPROUT_GARDEN:
                    $permission = 'gardenFE.create.sprout_garden';
                    break;
                default:
                    $permission = 'gardenFE.create.past_garden';
                    break;
            }
            $canCreate = hasPermission($permission);

            return Theme::scope('garden.notice_detail', [
                'id' => $id,
                'categories' => $categories,
                'garden' => $garden,
                'comments' => $comments,
                'selectCategories' => $selectCategories,
                'canEdit' => $canEdit,
                'canDelete' => $canDelete,
                'canCreateComment' => $canCreateComment,
                'canDeleteComment' => $canDeleteComment,
                'canViewComment' => $canViewComment,
                'top_comments' => $top_comments
            ])->render();
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public static function getList() {

        $garden = Garden::orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->paginate(6);

        Theme::breadcrumb()->add(__('garden'), route('gardenFE.list'))->add(__('garden.garden_list'), 'http:...');

        Theme::setTitle(__('garden') . ' | ' . __('garden.garden_list'));

        return Theme::scope('garden.list', ['garden' => $garden])->render();
    }

    public function createComment(Request $request) {
        $file = "";
        if (auth()->guard('member')->check()) {

            $anonymous = $request->is_secret_comments ?? 0;
            $gardens_id = $request->gardens_id;
            $content = $request->content;
            $parents_id = $request->parents_id;
            $ip_address = $request->ip();

            $comments = new CommentsGarden;
            $comments->gardens_id = $gardens_id;
            $comments->anonymous = $anonymous;
            $comments->member_id = auth()->guard('member')->user()->id;
            $comments->content = $content;
            $comments->parents_id = $parents_id ?? null;
            $comments->ip_address = $ip_address;

            $comments->save();

            $parent = MediaFolder::where('slug', 'comment-fe')->first();
            $folder = MediaFolder::create([
                'name' => $comments->id,
                'slug' => "comment-garden-".$comments->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
            //file
            if ($request->hasFile('commentFile')) {
                $listFile = $request->file('commentFile');
                $listFileURL = [];
                foreach ($listFile as $file){
                    $file_link = \RvMedia::handleUpload($file, $folder->id ?? 0);
                    if ($file_link['error'] == false) {
                        $listFileURL[] = $file_link['data']->url;
                    }
                }
                $comments->file_upload = implode(',', $listFileURL);
            }
            $comments->save();
            addPointForMembers(1);
            return redirect()->back();
        } else {
            // return to login
            return redirect()->back();
        }
    }

    public function deleteComment($id) {
        if (hasPermission('memberFE.isAdmin')) {
            $comments = CommentsGarden::findOrFail($id);
        } else {
            $comments = CommentsGarden::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        if($comments->parents_id > 0){
            $parentComment = CommentsGarden::findOrFail($comments->parents_id);
            $sameChildComment = $parentComment->getAllCommentByParentsID($comments->parents_id);

            if($sameChildComment->count() > 1){
                $countDelete = 0;
                foreach($sameChildComment as $key=>$item){
                    if($item->id == $id){
                        $item->is_deleted = 1;
                        $item->save();
                        $countDelete++;
                    }else{
                        if($item->is_deleted == 1){
                            $countDelete++;
                        }
                    }
                }

                $flagDeleteParent = false;
                if($countDelete==$sameChildComment->count()){
                    $flagDeleteParent = true;
                    foreach($sameChildComment as $key=>$item){
                        $item->delete();
                    }

                }

                if($parentComment->is_deleted == 1){
                    if($flagDeleteParent){
                        $parentComment->delete();
                    }
                }
            } else {
                $comments->delete();
                if($parentComment->is_deleted == 1){
                    $parentComment->delete();
                }
            }

        }
        else{
            $allChildComment = $comments->getAllCommentByParentsID($id);

            if ($comments->anonymous == 0){
                $checkSympathy = SympathyGarden::where(['member_id' => auth()->guard('member')->user()->id, 'gardens_id' => $comments->gardens_id])->first();
                if (!empty($checkSympathy)){
                    $checkSympathy->delete();
                }
            }

            if($allChildComment->count() > 0){
                $comments->is_deleted = 1;
                $comments->save();
            }else{
                $comments->delete();
            }
        }


        $file_delete = $comments->file_upload;
        if ($file_delete) {
            # code...
            $file = MediaFile::where('url', $file_delete)->first();
            if ($file) {
                $file->forceDelete();
            }
            $uploadManager = new UploadsManager;
            $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $file_delete);
            $uploadManager->deleteFile($path , 1);

            $folder = MediaFolder::where('slug', "comment-garden-".$id)->first();
            if ($folder) {
                $folder->forceDelete();
            }
        }





        // foreach ($comments->getAllCommentByParentsID($id) as $item) {
        //     $item->delete();
        // }
        // $comments->delete();
        return redirect()->back()->with('success', __('controller.deletecomment'));
    }

    public static function getListCreateByMember() {

        $selectCategories = CategoriesGarden::where('status', 'publish')
            ->orderBy('created_at', 'DESC')->where('name', '!=', 'E-garden')
            ->whereIn('special_garden', checkCanViewGarden())
            ->where('level_access', '<=', getLevelMember())->first();
        if (is_null($selectCategories)) {
            return redirect()->route('egardenFE.home')->with('permission', __('garden.your_level_does_not_match'));
        }
        $garden = Garden::where('status', 'draft')->orderBy('created_at', 'DESC')->where('member_id', auth()->guard('member')->user()->id)->paginate(6);

        Theme::breadcrumb()->add(__('garden'), route('gardenFE.list'))->add(__('garden.garden_list'), 'http:...');

        Theme::setTitle(__('garden.garden_list') . ' | ' . __('garden.garden_list'));

        return Theme::scope('garden.listCreateByMember', ['garden' => $garden, 'selectCategories' => $selectCategories])->render();
    }

    public static function getCreate($id, Request $request) {
        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            $garden = null;
            if ($request->get('nondraft') != 1){
                $garden = Garden::where('status', 'draft')->where('categories_gardens_id', $id)->where('member_id', auth()->guard('member')->user()->id)->orderBy('created_at', 'DESC')->first();
            }

            if (is_null($garden)) {
                $categories = CategoriesGarden::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

                $selectCategories = $categories->where('id', $id)->whereIn('special_garden', checkCanViewGarden())->first();
                if (is_null($selectCategories)) {
                    return redirect()->route('egardenFE.home')->with('permission', __('home.no_permisson'));
                }

                if (!hasPermission('memberFE.isAdmin')) {
                    if ($selectCategories->level_access > getLevelMember()) {
                        return redirect()->route('egardenFE.home')->with('permission', __('garden.your_level_does_not_match'));
                    }
                }
                $description = DescriptionGarden::where('categories_gardens_id', $selectCategories->id)->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

                Theme::breadcrumb()->add(__('garden'), route('gardenFE.list'))
                    ->add($selectCategories->name, route('gardenFE.show', ['id' => $selectCategories->id]))
                    ->add(__('garden.create_garden'), 'http:...');
                Theme::setTitle(__('new_contents') . ' | ' . $selectCategories->name . ' | ' . __('garden.create_garden'));

                return Theme::scope('garden.create', ['garden' => null, 'categories' => $categories, 'selectCategories' => $selectCategories, 'description' => $description])->render();

            } else {
                return redirect()->route('gardenFE.edit', ['id' => $garden->id]);
            }
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public function postStore(Request $request) {
        $file = [];
        $link = [];

        $request->request->remove('off_autofill');

        $pwd_post = (isset($request->is_pwd_post) && $request->is_pwd_post == 'on') ? $request->pwd_post : null;
        $request->merge(['pwd_post'=> $pwd_post]);

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
        if (!is_null($pwd_post)) {
            $request->merge(['pwd_post' => Hash::make($request->pwd_post)]);
        }
        if (checkPasswordGarden(Cookie::get('password_garden'))) {

            $request->merge(['member_id' => auth()->guard('member')->user()->id]);
            $request->validate([
                'title' => 'required|max:120',
                'hint' => 'max:120',
                'detail' => 'required',
                'status' => 'required',
                'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:10000', // max 2000kb = 2Mb

            ]);

            if ($request->status != 'draft') {
                $request->merge(['published' => Carbon::now()]);
            }

            $garden = new Garden;
            $garden = $garden->create($request->input());

            // ip추가
            $gardenDetail = GardenDetail::create([
                'garden_id' => $garden->id,
                'ip_address' => $request->ip()
            ]);

            $parent = MediaFolder::where('slug', 'garden-fe')->first();
            $folder = MediaFolder::create([
                'name' => $garden->id,
                'slug' => $garden->id,
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
                $garden->file_upload = $file;
            }

            $garden->save();
            addPointForMembers();
            $this->deleteFilePreview();

            event(new CreatedContentEvent(GARDEN_MODULE_SCREEN_NAME, $request, $garden));

            return redirect()->route('gardenFE.show', ['id' => $request->input('categories_gardens_id')])->with('success', __('controller.create_successful', ['module' => __('garden')]));
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public static function getEdit($id) {

        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            if (hasPermission('memberFE.isAdmin')) {
                $garden = Garden::findOrFail($id);
            } else {
                $garden = Garden::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
            }

            if (!is_null($garden->pwd_post)) {
                if ($garden->member_id != auth()->guard('member')->user()->id) {
                    if (!isset(session()->get('pwd_post')[$id]) || !Hash::check(session()->get('pwd_post')[$id], $garden->pwd_post)) {
                        return redirect()->route('gardenFE.show', ['id' => $garden->categories->id])->with('err', 'Check your password');
                    }
                }
            }

            $categories = CategoriesGarden::where('status', 'publish')
                ->orderBy('created_at', 'DESC')
                ->get();
            $selectCategories = $garden->categories;
            $description = DescriptionGarden::where('categories_gardens_id', $selectCategories->id)->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

            Theme::breadcrumb()->add(__('garden'), route('gardenFE.list'))
                ->add($selectCategories->name, route('gardenFE.show', ['id' => $selectCategories->id]))
                ->add(__('garden.edit_garden'), 'http:...');
            Theme::setTitle(__('new_contents') . ' | ' . $selectCategories->name . ' | ' . __('garden.edit_garden'));

            return Theme::scope('garden.create', ['garden' => $garden, 'categories' => $categories, 'selectCategories' => $selectCategories, 'description' => $description])->render();
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public function postUpdate($id, Request $request) {

        $file_upload = [];
        $link = [];
        $request->request->remove('off_autofill');
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

        if (checkPasswordGarden(Cookie::get('password_garden'))) {

            $request->validate([
                'title' => 'required|max:120',
                'detail' => 'required',
                'status' => 'required',
                'hint' => 'max:120',
                'file.*' => 'mimes:jpg,jpeg,png,doc,csv,xlsx,xls,ppt,odt,ods,odp,pdf,docx,zip|max:10000', // max 2000kb = 2Mb

            ]);
            if (hasPermission('memberFE.isAdmin')) {
                $garden = Garden::findOrFail($id);
            } else {
                $garden = Garden::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
            }

            if ($garden->published == null && $request->status != 'draft') {
                $request->merge(['published' => Carbon::now()]);
            }
            $parent = MediaFolder::where('slug', 'garden-fe')->first();
            $folder = MediaFolder::where('slug', $garden->id)->where('parent_id', $parent->id ?? 0)->first();
            if (is_null($folder)) {
                $folder = MediaFolder::create([
                    'name' => $garden->id,
                    'slug' => $garden->id,
                    'user_id' => '0',
                    'parent_id' => $parent->id ?? 0,
                ]);
            }
            //delete old file
            $file_delete = $garden->file_upload;
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
            $garden->file_upload = $file_upload;

            if ($garden->status == 'draft'){
                $request->merge(['pwd_post' => Hash::make($request->pwd_post)]);
            } else {
                unset($request['pwd_post']);
            }

            $updateGarden = $request->input();

            if ($request->get('status') == 'photopod'){
                $updateGarden['detail'] = preg_replace("/<img[^>]+\>/i", "", $updateGarden['detail']);
            }

            $garden->update($updateGarden);
            $this->deleteFilePreview();
            event(new CreatedContentEvent(GARDEN_MODULE_SCREEN_NAME, $request, $garden));

            return redirect()->route('gardenFE.details', ['idCategories' => $garden->categories->id, 'id' => $id])->with('msg', __('controller.update_successful'));
        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public function delete(Request $request) {
        $id = $request->id;
        if (hasPermission('memberFE.isAdmin')) {
            $garden = Garden::where('id', $id)->firstOrFail();
        } else {
            $garden = Garden::where('id', $id)->where('member_id', auth()->guard('member')->user()->id)->firstOrFail();
        }

        try {
            $parent = MediaFolder::where('slug', 'garden-fe')->first();
            $folder = MediaFolder::where('slug', $request->input('id'))->where('parent_id', $parent->id)->first();
            if ($folder && count($folder->files) > 0) {
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
            $garden->delete();

            event(new DeletedContentEvent(GARDEN_MODULE_SCREEN_NAME, $request, $garden));

            return redirect()->route('gardenFE.show', ['id' => $garden->categories->id])->with('success', __('controller.delete_successful', ['module' => __('garden')]));
        } catch (Exception $exception) {
            return redirect()->route('gardenFE.show', ['id' => $garden->categories->id])->with('error', __('controller.delete_failed'));
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

            ]);

            $garden = new Garden;
            $garden->title = $request->title;
            $garden->detail = $request->detail;
            $garden->right_click = $request->right_click;
            $garden->active_empathy = $request->active_empathy;
            $garden->categories_gardens_id = $request->categories_gardens_id;
            $garden->categories_gardens_name = $request->categories_gardens_name;
            $garden->link = $request->link;

            $parent = MediaFolder::where('slug', 'garden-fe')->first();
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
                if (hasPermission('memberFE.isAdmin')) {
                    $item_preview = Garden::findOrFail($request->idPreview);
                } else {
                    $item_preview = Garden::where('member_id', auth()->guard('member')->user()->id)->findOrFail($request->idPreview);
                }

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

            $garden->file_upload = $file_upload;

            Theme::breadcrumb()->add(__('garden'), route('gardenFE.list'))
                ->add($garden->categories_gardens_name, route('gardenFE.show', ['id' => $garden->categories_gardens_id]))
                ->add(__('garden.preview'), 'http:...');
            Theme::setTitle(__('new_contents') . ' | ' . $garden->categories_gardens_name . ' | ' . __('garden.preview'));

            return Theme::scope('garden.preview', ['categories' => $categories, 'garden' => $garden])->render();

        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }
    }

    public function deleteFilePreview() {
        $parent = MediaFolder::where('slug', 'garden-fe')->first();
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

    public static function dislike(Request $request) {
        $id = $request->post_id;
        $reason = $request->reason;
        $sympathy = Garden::findOrFail($id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_gardens.member_id', $user->id);
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 0);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 1,
                    'reason' => $reason,
                    'updated_at' => Carbon::now()
                ]);
                $dislike = 2;
                if($reason!=""){
                    sympathyCommentDetail("garden",$id,$reason,"dislike");
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnPost("garden",$id,"dislike");
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 1,
                    'reason' => $reason,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]);
            if($reason!=""){
                sympathyCommentDetail("garden",$id,$reason,"dislike");
            }
            $dislike = 2;
        }
        $sympathy = Garden::withCount([
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
        $reason = $request->reason;
        $sympathy = Garden::findOrFail($id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_gardens.member_id', $user->id);
        $liked = 0;
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 1);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 0,
                    'reason' => $reason,
                    'updated_at' => Carbon::now()
                ]);
                $liked = 2;
                if($reason!=""){
                    sympathyCommentDetail("garden",$id,$reason,"like");
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnPost("garden",$id,"like");
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 0,
                    'reason' => $reason,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]);
            $liked = 1;
            if($reason!=""){
                sympathyCommentDetail("garden",$id,$reason,"like");
            }
        }
        $sympathy = Garden::withCount([
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
        $sympathy = CommentsGarden::findOrFail($comment_id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_garden_comments.member_id', $user->id);
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 0);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 1,
                    'post_id' => $post_id,
                    'reason' => $reason,
                    'updated_at' => Carbon::now()
                ]);
                $dislike = 2;
                if($reason!=""){
                    sympathyCommentDetail("garden", $post_id,$reason,"dislike",$comment_id);
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnComment("garden",$comment_id,"dislike");
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 1,
                    'post_id' => $post_id,
                    'reason' => $reason,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]);
            $dislike = 2;
            if($reason!=""){
                sympathyCommentDetail("garden", $post_id,$reason,"dislike",$comment_id);
            }
        }
        $sympathy = CommentsGarden::withCount([
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
        $reason = $request->reason;
        $post_id = $request->post_id;
        $comment_id = $request->comment_id;
        $sympathy = CommentsGarden::findOrFail($comment_id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_garden_comments.member_id', $user->id);
        $liked = 0;
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 1);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                    'is_dislike' => 0,
                    'post_id' => $post_id,
                    'reason' => $reason,
                    'updated_at' => Carbon::now()
                ]);
                $liked = 2;
                if($reason!=""){
                    sympathyCommentDetail("garden", $post_id,$reason,"like",$comment_id);
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnComment("garden",$comment_id,"like");
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 0,
                    'post_id' => $post_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]);
            $liked = 1;
            if($reason!=""){
                sympathyCommentDetail("garden", $post_id,$reason,"like",$comment_id);
            }
        }
        $sympathy = CommentsGarden::withCount([
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

    public function clicker(Request $request) {
        $key = $request->key;
        $slide = Slides::where('code', 'ACCOUNT_GARDEN')->where('status', 'publish')->first();
        $list_images = $slide->getImageGallery();
        $list_image_new = [];
        foreach ($list_images->images as $key_item => $item) {
            if ($key_item == $key) {
                isset($item['count']) ? $item['count']++ : $item['count'] = 1;
                # code...
            }
            array_push($list_image_new, $item);

        }
        $list_images->images = json_encode($list_image_new);
        $list_images->save();
        return response()->json([
            'status' => true,
        ]);
    }

    public function addOrRemoveBookmark(Request $request, $id)
    {
        if (!checkPasswordGarden(Cookie::get('password_garden'))) {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }

        $garden = Garden::with(['categories'])->where('status', 'publish')->where('id', $id)->firstOrFail();
        $member = auth()->guard('member')->user();
        $isBookmarkByMember = $garden->isBookmarkedBy($member);
        $msg = null;
        if ($isBookmarkByMember) {
            $member->unBookmark($garden);
            $msg = __('garden.remove_bookmark_success');
        } else {
            $bookMarkCount = $member->bookmarks()->count();
            $memberBookmarkLimit = $member->bookmark_limit;
            // check limit bookmark
            if ($bookMarkCount >= $memberBookmarkLimit) {
                return redirect()->route('gardenFE.details', [
                    'idCategories' => $garden->categories->id,
                    'id' => $id]
                )->with('bookmark_err', __('garden.limit_bookmar_err'));
            }

            $member->bookmark($garden);
            $msg = __('garden.add_bookmark_success');
        }

        return redirect()->route('gardenFE.details', [
            'idCategories' => $garden->categories->id,
            'id' => $id]
        )->with('msg', $msg);
    }

    public function addMultipleBookmark(Request $request)
    {
        if ($request->ajax()) {
            $tableName = resolve(Garden::class)->getTable();
            $input = $request->all();
            $validator = Validator::make($input, [
                'ids' => 'bail|required|array',
                'ids.*' => "bail|distinct|integer|exists:{$tableName},id",
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return response()->json(compact(['errors']), 422);
            }

            $ids = $request->ids;
            $member = auth()->guard('member')->user();
            $bookMarkCount = $member->bookmarks()->count();
            $memberBookmarkLimit = $member->bookmark_limit;
            if ($bookMarkCount + count($ids) >= $memberBookmarkLimit) {
                $message = __('garden.limit_bookmar_err');
                return response()->json(compact(['message']), 422);
            }

            $gardens = Garden::whereIn('id', $ids)->get();
            try {
                DB::beginTransaction();
                foreach ($gardens as $key => $garden) {
                    $member->bookmark($garden);
                }

                DB::commit();
                $message = __('garden.add_bookmark_success');
                $result = [
                    'message' => $message
                ];

                return response()->json($result);
            } catch (\Throwable $th) {
                DB::rollBack();
                $message = $th->getMessage();
                return response()->json(compact(['message']), 500);
            }
        }
    }

    public function bookmarks(Request $request)
    {
        if (!checkPasswordGarden(Cookie::get('password_garden'))) {
            return redirect()->route('gardenFE.passwd')->with('permission', __('controller.check_password_access_garden'));
        }

        $categories = CategoriesGarden::where('status', 'publish')->orderBy('id', 'DESC')->get();
        $slides = Slides::where('code', 'GARDEN')->where('status', 'publish')->first();
        $member = auth()->guard('member')->user();
        $bookmarks = $member->bookmarks()
            ->with([
                'bookmarkable' => function ($q) {
                    $q->withCount(['dislikes'])
                        ->withCount(['likes']);
                }
            ])
            ->latest()
            ->paginate(15);

        Theme::setTitle(__('garden') . ' | ' . __('garden.bookmarks'));
        Theme::breadcrumb()->add(__('garden'), route('gardenFE.list'))->add(__('garden.bookmarks'), route('gardenFE.bookmarks'));

        return Theme::scope('garden.bookmark', [
            'categories' => $categories,
            'slides' => $slides,
            'bookmarks' => $bookmarks,
        ])->render();
    }

    public static function checkSympathyPermissionOnPost(Request $request) {
        $id = $request->id;

        $gardenOwnerID = Garden::find($id)->member_id;
        $currentUserID = auth()->guard('member')->user()->id;
        $allow = 1;

        if($gardenOwnerID == $currentUserID){
            $allow = 0;
        }

        return response()->json(
            [
                'valid' =>$allow,
            ]
        );
    }

    public static function checkSympathyPermissionOnComment(Request $request) {
        $commentId = $request->comment_id;
        $commentOwnerID = CommentsGarden::find($commentId)->member_id;
        $currentUserID = auth()->guard('member')->user()->id;
        $allow = 1;

        if($commentOwnerID == $currentUserID){
            $allow = 0;
        }

        return response()->json(
            [
                'valid' =>$allow,
            ]
        );
    }
}
