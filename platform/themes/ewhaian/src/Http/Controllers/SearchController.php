<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Campus\Models\Description\DescriptionCampus;
use Botble\Campus\Models\Evaluation\Evaluation;
use Botble\Campus\Models\Evaluation\Major;
use Botble\Campus\Models\Genealogy\Genealogy;
use Botble\Campus\Models\Notices\NoticesCampus;
use Botble\Campus\Models\OldGenealogy\OldGenealogy;
use Botble\Campus\Models\StudyRoom\StudyRoom;
use Botble\Campus\Models\StudyRoom\StudyRoomCategories;
use Botble\Contents\Models\CategoriesContents;
use Botble\Contents\Models\Contents;
use Botble\Events\Models\CategoryEvents;
use Botble\Events\Models\Events;
use Botble\Events\Models\EventsCmt;
use Botble\Garden\Models\CategoriesGarden;
use Botble\Garden\Models\Description\DescriptionGarden;
use Botble\Garden\Models\Egarden\Egarden;
use Botble\Garden\Models\Egarden\Room;
use Botble\Garden\Models\Garden;
use Botble\Garden\Models\Notices\NoticesGarden;
use Botble\Garden\Models\PopularGarden;
use Botble\Introduction\Models\CategoriesIntroduction;
use Botble\Introduction\Models\Faq\FaqIntroduction;
use Botble\Introduction\Models\Introduction;
use Botble\Introduction\Models\Notices\NoticesIntroduction;
use Botble\Life\Models\Ads\Ads;
use Botble\Life\Models\Ads\AdsCategories;
use Botble\Life\Models\Description;
use Botble\Life\Models\Flare;
use Botble\Life\Models\FlareCategories;
use Botble\Life\Models\Jobs\JobsCategories;
use Botble\Life\Models\Jobs\JobsPartTime;
use Botble\Life\Models\Notices;
use Botble\Life\Models\OpenSpace\OpenSpace;
use Botble\Life\Models\Shelter\Shelter;
use Botble\Life\Models\Shelter\ShelterCategories;
use Botble\MasterRoom\Models\AddressMasterRoom;
use Botble\MasterRoom\Models\CategoriesMasterRoom;
use Botble\MasterRoom\Models\MasterRoom;
use Botble\Member\Models\ForbiddenKeywords;
use Botble\NewContents\Models\CategoriesNewContents;
use Botble\NewContents\Models\NewContents;
use Botble\Setting\Models\Setting;
use Botble\Slides\Models\Slides;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Theme;

class SearchController extends Controller
{
    protected static $forbidden;
    public function __construct()
    {
        self::$forbidden = ForbiddenKeywords::where('type', 'forbidden')->where('status', 'publish')->pluck('title')->toArray();
    }

    public static function checkKeyWord($arr1, $arr2)
    {
        foreach($arr1 as $item1){
            if (in_array($item1, $arr2) == true){
                return true;
            }
        }
        return false;
    }

    /**
     * @return \Response
     */
    public static function contents(Request $request, $idCategory)
    {
        $keyword = array_map('trim', explode(',', $request->keyword));
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;
        $idCategory = $request->idCategory;
        $style = $request->style ?? 1;
        $orderby = $request->orderby;

        $categories = CategoriesContents::where('status', 'publish')->orderBy('created_at', 'DESC');
        $selectCategories = CategoriesContents::where('status', 'publish')->where('id', $idCategory)->firstOrFail();
        $contents = Contents::where('status', '!=', 'draft')->where('categories_contents_id', $selectCategories->id);
        $notices = NoticesIntroduction::code(CONTENTS_MODULE_SCREEN_NAME . '-' . $idCategory)->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();


        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }
        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $contents->where('published', '>=', $startDate)->where('published', '<=', $endDate);

        }

        if ($keyword) {
            if(self::checkKeyWord($keyword, self::$forbidden) == false ){
                switch ($type) {
                    case 0:
                        $contents->where(function ($query) use ($keyword) {
                            foreach ($keyword as $title) {
                                $query->orWhere('title', 'like', '%' . $title . '%');
                            }
                        });
                        break;
                    case 1:
                        $contents->where(function ($query) use ($keyword) {
                            foreach ($keyword as $detail) {
                                $query->orWhere('content', 'like', '%' . $detail . '%');
                            }
                        });
                        break;
                    default:
                        $contents->whereRaw('1 = 0');
                }
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }
        switch ($orderby) {
            case 2:
                $contents->orderBy('lookup', 'DESC');
                break;
            case 3:
                $contents->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 4:
                $contents->orderBy('published', 'DESC');
                break;
        }
        $contents->orderBy('published', 'DESC');

        Theme::setTitle(__('contents') . '| ' . $selectCategories->name . ' | ' . __('contents.search'));

        Theme::breadcrumb()->add(__('contents'), route('contents.contents_list', ['idCategory' => $idCategory]))->add($selectCategories->name, 'http:...');

        switch ($selectCategories->permisions) {
            case CategoriesContents::MULTICULTURE:
                $canCreate = 'contentsFE.create.multiculture';
                break;
            case CategoriesContents::CULTURAL_SYMPATHY:
                $canCreate = 'contentsFE.create.cultural_sympathy';
                break;
            case CategoriesContents::FINE_NOTEBOOK:
                $canCreate = 'contentsFE.create.fine_notebook';
                break;
            case CategoriesContents::WRITTEN_NOTE:
                $canCreate = 'contentsFE.create.written_note';
                break;
            case CategoriesContents::CONTRIBUTION:
                $canCreate = 'contentsFE.create.contribution';
                break;
            default:
                $canCreate = 'contentsFE.create.multiculture';
                break;
        }
        $canCreate = hasPermission($canCreate);

        return Theme::scope('contents.index', [
            'contents' => $contents->paginate(10),
            'categories' => $categories->get(),
            'notices' => $notices,
            'idCategory' => $idCategory,
            'selectCategories' => $selectCategories,
            'style' => $style,
            'canCreate' => $canCreate,
        ])->render();

    }

    public static function events(Request $request)
    {
        $keyword = array_map('trim', explode(',', $request->keyword));
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;
        $orderby = $request->orderby;

        Theme::setTitle(__('event.event_comments') . ' | ' . __('event.event_comments.search'));

        $events = EventsCmt::where('status', 'publish');

        //--------------------------------------------------------------------------
        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }
        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $events->where('published', '>=', $startDate)->where('published', '<=', $endDate);

        }

        if ($keyword) {
            if(self::checkKeyWord($keyword, self::$forbidden) == false ){
                switch ($type) {
                    case 0:
                        $events->where(function ($query) use ($keyword) {
                            foreach ($keyword as $title) {
                                $query->orWhere('title', 'like', '%' . $title . '%');
                            }
                        });
                        break;
                    case 1:
                        $events->where(function ($query) use ($keyword) {
                            foreach ($keyword as $detail) {
                                $query->orWhere('detail', 'like', '%' . $detail . '%');
                            }
                        });
                        break;
                    default:
                        $events->whereRaw('1 = 0');
                }
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }
        switch ($orderby) {
            case 2:
                $events->orderBy('views', 'DESC');
                break;
            case 3:
                $events->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 4:
                $events->orderBy('published', 'DESC');
                break;
        }
        $events->orderBy('published', 'DESC');
        $category = CategoryEvents::orderBy('created_at', 'DESC')->get();

        Theme::breadcrumb()->add(__('event.menu__title'), route('event.event_list', ['idCategory' => $category->first->id]))->add(__('event.event_comments'), 'http:...');

        return Theme::scope('event.eventscmt.index', ['events' => $events->paginate(10), 'category' => $category])->render();
    }

    public static function flare(Request $request)
    {
        $childCategories = $request->childCategories ?? 0;
        $parentCategories = $request->parentCategories ?? 0;
        $keyword = array_map('trim', explode(',', $request->keyword));
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;
        $style = $request->style;
        $orderby = $request->orderby;

        Theme::setTitle(__('life') . ' | ' . __('life.flea_market') . ' | ' . __('life.flea_market.search'));
        $flare = Flare::where('status', '!=', 'draft');
        if ($parentCategories > 0) {
            $flare->where('categories', 'LIKE', '%"1":"' . $parentCategories . '"%');
        }
        if ($childCategories > 0) {
            $flare->where('categories', 'LIKE', '%"2":"' . $childCategories . '"%');
        }

        $notices = Notices::where('code', 'FLARE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = Description::where('code', 'FLARE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $categories = FlareCategories::where('status', 'publish')->get();

        //---------------------------------------------------------------->orderBy('created_at', 'DESC')->paginate(10);

        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }

        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $flare->where('published', '>=', $startDate)->where('published', '<=', $endDate);

        }

        if ($keyword) {
            if(self::checkKeyWord($keyword, self::$forbidden) == false ){
                switch ($type) {
                    case 0:
                        $flare->where(function ($query) use ($keyword) {
                            foreach ($keyword as $title) {
                                $query->orWhere('title', 'like', '%' . $title . '%');
                            }
                        });
                        break;
                    case 1:
                        $flare->where(function ($query) use ($keyword) {
                            foreach ($keyword as $detail) {
                                $query->orWhere('detail', 'like', '%' . $detail . '%');
                            }
                        });
                        break;
                    default:
                        $flare->whereRaw('1 = 0');
                }
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }

        $flare->withCount(['dislikes'])->has('dislikes', '<', 10);
        switch ($orderby) {
            case 2:
                $flare->orderBy('lookup', 'DESC');
                break;
            case 3:
                $flare->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 4:
                $flare->withCount(['likes'])->orderBy('likes_count', 'DESC');
                break;
        }
        $flare->orderBy('published', 'DESC');
        $canCreate = hasPermission('flareMarketFE.create');

        return Theme::scope('life.flare_market.index', [
            'flare' => $flare->orderBy('published', 'DESC')->paginate(10),
            'notices' => $notices, 'categories' => $categories,
            'description' => $description, 'style' => $style,
            'canCreate' => $canCreate,
        ])->render();
    }

    public static function jobs(Request $request)
    {

        $keyword = array_map('trim', explode(',', $request->keyword));
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;
        $style = $request->style ?? 0;
        $orderby = $request->orderby;
        $firstParent = JobsCategories::withcondition()->where('parent_id', 1)->where('status', 'publish')->first();

        $childCategories = $request->childCategories ?? 0;
        $parentCategories = $request->parentCategories ?? 0;

        Theme::setTitle(__('life') . ' | ' . __('life.part-time_job') . ' | ' . __('life.flea_market.search'));

        $jobs = JobsPartTime::where('status', '!=', 'draft');
        if ($parentCategories > 0) {
            $jobs->where('categories', 'LIKE', '%"1":"' . $parentCategories . '"%');
        }
        if ($childCategories > 0) {
            $jobs->where('categories', 'LIKE', '%"2": "' . $childCategories . '"%');
        }

        //---------------------------------------------------------------->orderBy('created_at', 'DESC')->paginate(10);
        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }

        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $jobs->where('published', '>=', $startDate)->where('published', '<=', $endDate);

        }

        if ($keyword) {
            if(self::checkKeyWord($keyword, self::$forbidden) == false ){
                switch ($type) {
                    case 0:
                        $jobs->where(function ($query) use ($keyword) {
                            foreach ($keyword as $title) {
                                $query->orWhere('title', 'like', '%' . $title . '%');
                            }
                        });
                        break;
                    case 1:
                        $jobs->where(function ($query) use ($keyword) {
                            foreach ($keyword as $detail) {
                                $query->orWhere('detail', 'like', '%' . $detail . '%');
                            }
                        });
                        break;
                    default:
                        $jobs->whereRaw('1 = 0');
                }
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }

        $jobs->withCount(['dislikes'])->has('dislikes', '<', 10);
        switch ($orderby) {
            case 2:
                $jobs->orderBy('lookup', 'DESC');
                break;
            case 3:
                $jobs->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 4:
                $jobs->withCount(['likes'])->orderBy('likes_count', 'DESC');
                break;
        }
        $jobs->orderBy('published', 'DESC');
        $jobs->rejectcategories();

        $categories = JobsCategories::withcondition()->where('status', 'publish')->get();
        $notices = Notices::where('code', 'JOBS_PART_TIME_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = Description::where('code', 'JOBS_PART_TIME_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        $canCreate = hasPermission('jobsPartTimeFE.create');
        return Theme::scope('life.jobs.index', [
            'jobs' => $jobs->paginate(10),
            'categories' => $categories,
            'notices' => $notices,
            'description' => $description,
            'style' => $style,
            'idFirstParent' => $firstParent->id,
            'canCreate' => $canCreate,

        ])->render();
    }

    public static function ads(Request $request)
    {
        $adsCategories = $request->categories;
        $keyword = array_map('trim', explode(',', $request->keyword));
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;
        $parents = $request->parents;
        $style = $request->style ?? 0;
        $orderby = $request->orderby;

        Theme::setTitle(__('life') . ' | ' . __('life.advertisements') . ' | ' . __('life.flea_market.search'));

        $ads = Ads::where('status', 'publish');

        //---------------------------------------------------------------->orderBy('created_at', 'DESC')->paginate(10);
        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }

        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $ads->where('published', '>=', $startDate)->where('published', '<=', $endDate);

        }

        if ($keyword) {
            if(self::checkKeyWord($keyword, self::$forbidden) == false ){
                switch ($type) {
                    case 0:
                        $ads->where(function ($query) use ($keyword) {
                            foreach ($keyword as $title) {
                                $query->orWhere('title', 'like', '%' . $title . '%');
                            }
                        });
                        break;
                    case 1:
                        $ads->where(function ($query) use ($keyword) {
                            foreach ($keyword as $detail) {
                                $query->orWhere('details', 'like', '%' . $detail . '%');
                            }
                        });
                        break;
                    default:
                        $ads->whereRaw('1 = 0');
                }
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }

        if ($adsCategories > 0) {
            $ads = $ads->where('categories', $adsCategories);
        }

        $today = date("Y-m-d 00:00:00");
        if ($parents == 2) {
            $ads->where('deadline', '<', $today)->where('is_deadline', 1);
        } else {
            $ads->where(function ($query) use ($today) {
                $query->where('deadline', '>', $today)->orWhere('is_deadline', 0);
            });
        }

        switch ($orderby) {
            case 2:
                $ads->orderBy('lookup', 'DESC');
                break;
            case 3:
                $ads->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 4:
                $ads->orderBy('published', 'DESC');
                break;
        }
        $ads->orderBy('published', 'DESC');

        $categories = AdsCategories::where('status', 'publish')->get();
        $notices = Notices::where('code', 'ADS_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = Description::where('code', 'ADS_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('adsFE.create');
        return Theme::scope('life.ads.index', [
            'ads' => $ads->paginate(10),
            'categories' => $categories,
            'notices' => $notices,
            'description' => $description,
            'style' => $style,
            'canCreate' => $canCreate,
        ])->render();
    }

    public static function shelter(Request $request)
    {
        $shelterCategories = $request->categories;
        $keyword = array_map('trim', explode(',', $request->keyword));
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;
        $style = $request->style ?? 0;
        $orderby = $request->orderby;

        Theme::setTitle(__('life') . ' | ' . __('life.shelter_info') . ' | ' . __('life.flea_market.search'));

        $shelter = Shelter::where('status', 'publish');
        if ($shelterCategories > 0) {
            $shelter = $shelter->where('categories', $shelterCategories);
        }

        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }

        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $shelter->where('published', '>=', $startDate)->where('published', '<=', $endDate);

        }

        if ($keyword) {
            if(self::checkKeyWord($keyword, self::$forbidden) == false ){
                switch ($type) {
                    case 0:
                        $shelter->where(function ($query) use ($keyword) {
                            foreach ($keyword as $title) {
                                $query->orWhere('title', 'like', '%' . $title . '%');
                            }
                        });
                        break;
                    case 1:
                        $shelter->where(function ($query) use ($keyword) {
                            foreach ($keyword as $detail) {
                                $query->orWhere('detail', 'like', '%' . $detail . '%');
                            }
                        });
                        break;
                    default:
                        $shelter->whereRaw('1 = 0');
                }
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }

        $shelter->withCount(['dislikes'])->has('dislikes', '<', 10);
        switch ($orderby) {
            case 2:
                $shelter->orderBy('lookup', 'DESC');
                break;
            case 3:
                $shelter->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 4:
                $shelter->withCount(['likes'])->orderBy('likes_count', 'DESC');
                break;
        }
        $shelter->orderBy('published', 'DESC');

        $categories = ShelterCategories::where('status', 'publish')->get();
        $notices = Notices::where('code', 'SHELTER_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = Description::where('code', 'SHELTER_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('shelterFE.create');
        return Theme::scope('life.shelter.index', [
            'shelter' => $shelter->paginate(9),
            'categories' => $categories,
            'notices' => $notices,
            'description' => $description,
            'style' => $style,
            'canCreate' => $canCreate,
        ])->render();
    }

    public static function study(Request $request)
    {
        $studyRoomCategories = $request->categories;
        $keyword = $request->keyword;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;
        $style = $request->style ?? 0;
        $orderby = $request->orderby;
        Theme::setTitle(__('campus') . ' | ' . __('campus.study_room') . ' | ' . __('campus.search'));

        $studyRoom = StudyRoom::where('status', 'publish');
        if ($studyRoomCategories > 0) {
            $studyRoom = $studyRoom->where('categories', $studyRoomCategories);
        }

        //---------------------------------------------------------------->orderBy('created_at', 'DESC')->paginate(10);
        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }

        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $studyRoom->where('published', '>=', $startDate)->where('published', '<=', $endDate);

        }
        if ($keyword) {
            if (in_array($keyword, self::$forbidden) === false) {
                switch ($type) {
                    case 0:
                        $studyRoom->where('title', 'like', '%' . $keyword . '%');
                        break;
                    case 1:
                        $studyRoom->where('detail', 'like', '%' . $keyword . '%');
                        break;
                    default:
                        $studyRoom->whereRaw('1 = 0');
                }
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }
        switch ($orderby) {
            case 2:
                $studyRoom->orderBy('lookup', 'DESC');
                break;
            case 3:
                $studyRoom->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 4:
                $studyRoom->withCount(['likes'])->orderBy('likes_count', 'DESC');
                break;
        }
        $studyRoom->orderBy('published', 'DESC');
        $categories = StudyRoomCategories::where('status', 'publish')->get();
        $notices = NoticesCampus::where('code', 'STUDY_ROOM_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = DescriptionCampus::where('code', 'STUDY_ROOM_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('studyRoomFE.create');
        return Theme::scope('campus.study_room.index', [
            'studyRoom' => $studyRoom->paginate(9),
            'categories' => $categories, 'notices' => $notices,
            'description' => $description, 'style' => $style,
            'canCreate' => $canCreate,
        ])->render();
    }

    public static function genealogy(Request $request)
    {

        $keyword = array_map('trim', explode(',', $request->keyword));
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;
        $orderby = $request->orderby;

        Theme::setTitle(__('campus') . ' | ' . __('campus.genealogy') . ' | ' . __('campus.search'));

        $genealogy = Genealogy::where('status', 'publish');

        //----------------------------------------------------------------
        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }

        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $genealogy->where('published', '>=', $startDate)->where('published', '<=', $endDate);

        }
        if ($keyword) {
            $keyword = $keyword[0]; // update after
            if (in_array($keyword, self::$forbidden) === false) {
                switch ($type) {
                    case 0:
                        $genealogy->where(function ($query) use ($keyword) {
                            $query->where(function ($q) use ($keyword) {
                                $builder = $q->where('semester_year', 'like', '%' . $keyword . '%');
                                return $builder;
                            })
                                ->orWhere(function ($q) use ($keyword) {
                                    $builder = $q->where('semester_session', 'like', '%' . $keyword . '%');
                                    return $builder;
                                })
                                ->orWhere(function ($q) use ($keyword) {
                                    $builder = $q->where('class_name', 'like', '%' . $keyword . '%');
                                    return $builder;
                                })
                                ->orWhere(function ($q) use ($keyword) {
                                    $builder = $q->where('professor_name', 'like', '%' . $keyword . '%');
                                    return $builder;
                                })
                                ->orWhere(function ($q) use ($keyword) {
                                    $builder = $q->where('exam_name', 'like', '%' . $keyword . '%');
                                    return $builder;
                                });
                        });
                        break;
                    case 1:
                        $genealogy->where('detail', 'like', '%' . $keyword . '%');
                        break;
                    default:
                        $genealogy->whereRaw('1 = 0');
                }
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }

        switch ($orderby) {
            case 2:
                $genealogy->orderBy('lookup', 'DESC');
                break;
            case 3:
                $genealogy->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 4:
                $genealogy->withCount(['likes'])->orderBy('likes_count', 'DESC');
                break;
        }
        $genealogy->orderBy('published', 'DESC');
        $notices = NoticesCampus::where('code', 'GENEALOGY_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = DescriptionCampus::where('code', 'GENEALOGY_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('genealogyFE.create');
        return Theme::scope('campus.genealogy.index', [
            'genealogy' => $genealogy->paginate(10),
            'notices' => $notices, 'description' => $description,
            'canCreate' => $canCreate,
        ])->render();
    }
    public static function oldGenealogy(Request $request)
    {

        $keyword = array_map('trim', explode(',', $request->keyword));
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;
        $orderby = $request->orderby;

        Theme::setTitle(__('campus') . ' | ' . __('campus.old_genealogy') . ' | ' . __('campus.search'));

        $oldGenealogy = OldGenealogy::where('status', 'publish');

        //----------------------------------------------------------------
        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }

        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $oldGenealogy->where('published', '>=', $startDate)->where('published', '<=', $endDate);

        }
        if ($keyword) {
            if(self::checkKeyWord($keyword, self::$forbidden) == false ){
                switch ($type) {
                    case 0:
                        $oldGenealogy->where(function ($query) use ($keyword) {
                            foreach ($keyword as $title) {
                                $query->orWhere('title', 'like', '%' . $title . '%');
                            }
                        });
                        break;
                    case 1:
                        $oldGenealogy->where(function ($query) use ($keyword) {
                            foreach ($keyword as $detail) {
                                $query->orWhere('detail', 'like', '%' . $detail . '%');
                            }
                        });
                        break;
                    default:
                        $oldGenealogy->whereRaw('1 = 0');
                }
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }
        switch ($orderby) {
            case 2:
                $oldGenealogy->orderBy('lookup', 'DESC');
                break;
            case 3:
                $oldGenealogy->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 4:
                $oldGenealogy->withCount(['likes'])->orderBy('likes_count', 'DESC');
                break;
        }
        $oldGenealogy->orderBy('published', 'DESC');
        $notices = NoticesCampus::where('code', 'OLD_GENEALOGY_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = DescriptionCampus::where('code', 'OLD_GENEALOGY_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('oldGenealogyFE.create');
        return Theme::scope('campus.old-genealogy.index', [
            'oldGenealogy' => $oldGenealogy->paginate(10),
            'notices' => $notices, 'description' => $description,
            'canCreate' => $canCreate,
        ])->render();
    }
    public static function evaluation(Request $request)
    {

        $keyword = $request->keyword;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $orderby = $request->orderby;

        Theme::setTitle(__('campus') . ' | ' . __('campus.evaluation') . ' | ' . __('campus.search'));

        $evaluation = Evaluation::where('status', 'publish');

        //---------------------------------------------------------------->orderBy('created_at', 'DESC')->paginate(10);
        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }

        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $evaluation->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
        }
        if ($keyword) {
            if (in_array($keyword, self::$forbidden) === false) {
                $keywords = array_map('trim', explode(',', $keyword));
                $title = $keywords[0];
                $professorName = $keywords[1] ?? null;
                if($title){
                    $evaluation->where('title', 'like', '%' . $title . '%')
                        ->whereNotIn('title', self::$forbidden);
                }
                if($professorName){
                    $evaluation->where('professor_name', 'like', '%' . $professorName . '%')
                        ->whereNotIn('professor_name', self::$forbidden);
                }
//                $evaluation->where(function ($query) use ($keyword) {
//                    $builder2 = $query->where(function ($q) use ($keyword) {
//                        $builder = $q->where('title', 'like', '%' . $keyword . '%')
//                            ->whereNotIn('title', self::$forbidden);
//                        return $builder;
//                    })
//                        ->orWhere(function ($q) use ($keyword) {
//                            $builder = $q->where('professor_name', 'like', '%' . $keyword . '%')
//                                ->whereNotIn('professor_name', self::$forbidden);
//                            return $builder;
//                        });
//                    return $builder2;
//                });
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }

        switch ($orderby) {
            case 2:
                $evaluation->orderBy('lookup', 'DESC');
                break;
            case 3:
                $evaluation->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 4:
                $evaluation->withCount(['likes'])->orderBy('likes_count', 'DESC');
                break;
        }
        $evaluation->orderBy('created_at', 'DESC');
        $notices = NoticesCampus::where('code', 'EVALUATION_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = DescriptionCampus::where('code', 'EVALUATION_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        return Theme::scope('campus.evaluation.index', ['evaluation' => $evaluation->paginate(10), 'notices' => $notices, 'description' => $description])->render();
    }

    public static function garden(Request $request, $idCategory)
    {
        $startDate = $request->startDate ? $request->startDate : ($request->startDate1 ?  $request->startDate1 : null );
        $endDate = $request->endDate ? $request->endDate : ($request->endDate1 ?  $request->endDate1 : null );

        $startDate = $startDate ? Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s') : date('Y-m-d').' 00:00:00';
        $endDate = $endDate ?  Carbon::createFromFormat('Y.m.d', $endDate)->startOfDay()->format('Y-m-d') .' 23:59:59': date('Y-m-d').' 23:59:59';
        $keywordFilter = $request->input('filter');


        $keyword = array_map('trim', explode(',', $request->keyword));

        $keywordFilter = $keywordFilter ? array_map('trim', explode(',', $keywordFilter)) : [];

        $type = $request->type;
        $orderby = $request->orderby ?? 1;
        $categories = CategoriesGarden::where('status', 'publish')
            ->orderBy('created_at', 'DESC')
            ->where('name', '!=', 'E-garden')
            ->get();

        $garden = Garden::where('categories_gardens_id', $idCategory)
                ->where('published', '>=', $startDate)
                ->where('published', '<=', $endDate);

        if ($keyword) {
            if(self::checkKeyWord($keyword, self::$forbidden) == false ){

                switch ($type) {
                    case 0:
                        $garden->where(function ($query) use ($keyword) {
                            foreach ($keyword as $title) {
                                $query->orWhere('title', 'like', '%' . $title . '%');
                            }
                        });
                        break;
                    case 1:
                        $garden->where(function ($query) use ($keyword) {
                            foreach ($keyword as $detail) {
                                $query->orWhere('detail', 'like', '%' . $detail . '%');
                            }
                        });
                        break;
                    default:
                        $garden->whereRaw('1 = 0');
                }
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }

        foreach ($keywordFilter as $item){
            $garden->where('title', 'not like', '%' . $item . '%');
        }

        $limit = 15;
        $page = $request->has('page') ? $request->get('page') : 1;
        $offset = ($page - 1) * $limit;

        switch ($orderby) {
            case 1:
                $garden->orderBy('published', 'DESC');
                $arrayGarden = array('categories' => $categories, 'garden' => $garden->paginate($limit));
                break;
            case 2:
                $garden->orderBy('lookup', 'DESC')
                    ->orderBy('published', 'DESC');
                $arrayGarden = array('categories' => $categories, 'garden' => $garden->paginate($limit));
                break;
            case 3:
                $garden->withCount('comments')->orderBy('comments_count', 'desc')
                    ->orderBy('published', 'DESC');
                $arrayGarden = array('categories' => $categories, 'garden' => $garden->paginate($limit));
                break;
            case 4:
                $garden->withCount(['likes']);
                $garden->orderBy('likes_count', 'DESC')
                ->orderBy('published', 'DESC');
                $gardenData = $garden->skip($offset)->take($limit)->get();
                $gardenPaging =  $garden->paginate($limit);
                $arrayGarden = array('categories' => $categories, 'garden' => $gardenData,
                'gardenPaging' => $gardenPaging);
                break;
            case 5:
                $garden->withCount(['dislikes']);
                $garden->orderBy('dislikes_count', 'DESC')->orderBy('published', 'DESC');
                $gardenData = $garden->skip($offset)->take($limit)->get();
                $gardenPaging =  $garden->paginate($limit);
                $arrayGarden = array('categories' => $categories, 'garden' => $gardenData, 'gardenPaging' => $gardenPaging);
                break;
            case 6:
                $gardenPaging =  $garden->paginate($limit);
                $gardenData = $garden->get();

                foreach ($gardenData as &$data){
                    $like = count($data->likes);
                    $dislike = count($data->dislikes);
                    $comments = count($data->comments);
                    $data->sortData = $data->lookup + $comments - $dislike + $like;
                }

                $gardenData = $gardenData->sortByDesc(function ($garden){
                    return [$garden->sortData, $garden->published];
                });

                $arrayGarden = array('categories' => $categories, 'garden' => $gardenData, 'gardenPaging' => $gardenPaging);
                break;
        }

        $selectCategories = CategoriesGarden::where('id', $idCategory)->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        $slides = Slides::where('code', 'GARDEN')->where('status', 'publish')->first();

        if (!hasPermission('memberFE.isAdmin')) {
            if ($selectCategories->level_access > getLevelMember()) {
                return Theme::scope('garden.index', [
                    'categories' => $categories,
                    'idCategory' => $idCategory,
                    'selectCategories' => $selectCategories,
                    'slides' => $slides,
                    'no_permission' => __('home.no_permisson_leve_10')
                ])->render();
            }
        }
        Theme::setTitle(__('garden') . ' | ' . $selectCategories->name . ' | ' . __('garden.search'));

        $popular = $selectCategories->popular();
        if (!is_null($keyword)) {

            $newKeywords = [];
            foreach ($keyword as $key => $value) {
                $newKeywords[] = handleKey($value);
            }

            if ($popular->count() > 0) {

                $check = 0;
                foreach ($popular->get() as $key => $item) {
                    if (in_array($item->keyword, $newKeywords)) {
                        $item->lookup++;
                        if (getStatusDateByDate($item->updated_at) == "Today") {
                            $item->today_lookup++;
                        } else {
                            $item->today_lookup = 1;
                        }
                        $item->save();
                        $check++;
                        unset($newKeywords[array_search($item->keyword, $keyword)]);
                    }

                }

                if ($check == 0) {
                    foreach ($newKeywords as $key){
                        PopularGarden::updateOrCreate(['keyword' => handleKey($key), 'lookup' => 1, 'today_lookup' => 1, 'categories_id' => $selectCategories->id]);
                    }
                };

            } else {
                foreach ($newKeywords as $key){
                    PopularGarden::updateOrCreate(['keyword' => handleKey($key), 'lookup' => 1, 'today_lookup' => 1, 'categories_id' => $selectCategories->id]);
                }
            }
        }
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



        return Theme::scope('garden.index', $arrayGarden + [
            //'categories' => $categories, 'garden' => $garden->paginate(10),
            'selectCategories' => $selectCategories, 'popular' => $popular,
            'notices' => $notices, 'description' => $description,
            'slides' => $slides, 'countAccess' => $countAccess,
            'todayPopular' => $todayPopular,
            'canCreate' => $canCreate,

        ])->render();

    }

    public static function filterGarden(Request $request, $idCategory)
    {
        $startDate = $request->startDate ? $request->startDate : ($request->startDate1 ?  $request->startDate1 : null);
        $endDate = $request->endDate ? $request->endDate : ($request->endDate1 ?  $request->endDate1 : null);

        $startDate = $startDate ? Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s') : date('Y-m-d').' 00:00:00';
        $endDate = $endDate ? Carbon::createFromFormat('Y.m.d', $endDate)->startOfDay()->format('Y-m-d') .' 23:59:59' : date('Y-m-d').' 23:59:59';
        $keyword = $request->input('filter');
        $keywordFilter = $keyword ? array_map('trim', explode(',', $keyword)) : [];

        $garden = Garden::where('categories_gardens_id', $idCategory)
            ->where('published', '>=', $startDate)
            ->where('published', '<=', $endDate)
            ->ordered();
        foreach ($keywordFilter as $item){
            $garden->where('title', 'not like', '%' . $item . '%');
        }

        $categories = CategoriesGarden::where('status', 'publish')->orderBy('created_at', 'DESC')->get();
        $selectCategories = CategoriesGarden::where('id', $idCategory)->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        $slides = Slides::where('code', 'GARDEN')->where('status', 'publish')->first();
        if (!hasPermission('memberFE.isAdmin')) {
            if ($selectCategories->level_access > getLevelMember()) {
                return Theme::scope('garden.index', [
                    'categories' => $categories,
                    'idCategory' => $idCategory,
                    'selectCategories' => $selectCategories,
                    'slides' => $slides,
                    'no_permission' => __('home.no_permisson_leve_10')
                ])->render();
            }
        }
        Theme::setTitle(__('garden') . ' | ' . $selectCategories->name . ' | ' . __('garden.search'));

        $popular = $selectCategories->popular->take(5);

        $countAccess = countAccess();
        $notices = NoticesIntroduction::code(GARDEN_MODULE_SCREEN_NAME . '-' . $selectCategories->id)->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $todayPopular = $selectCategories->todaySearch();
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
            'categories' => $categories, 'garden' => $garden->paginate(10),
            'selectCategories' => $selectCategories, 'popular' => $popular,
            'slides' => $slides, 'countAccess' => $countAccess,
            'notices' => $notices, 'todayPopular' => $todayPopular,
            'canCreate' => $canCreate,
        ])->render();
    }

    public static function noticesIntro(Request $request)
    {

        $keyword = array_map('trim', explode(',', $request->keyword));
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;

        Theme::setTitle(__('eh-introduction') . ' | ' . __('eh-introduction.notices') . ' | ' . __('eh-introduction.notices.search'));

        $notices = NoticesIntroduction::where('status', 'publish')->orderBy('created_at', 'DESC');

        //--------------------------------------------------------------------------
        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }

        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $notices->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);

        }
        if ($keyword) {
            if(self::checkKeyWord($keyword, self::$forbidden) == false ){
                switch ($type) {
                    case 0:
                        $notices->where(function ($query) use ($keyword) {
                            foreach ($keyword as $title) {
                                $query->orWhere('name', 'like', '%' . $title . '%');
                            }
                        });
                        break;
                    case 1:
                        $notices->where(function ($query) use ($keyword) {
                            foreach ($keyword as $detail) {
                                $query->orWhere('notices', 'like', '%' . $detail . '%');
                            }
                        });
                        break;
                    default:
                        $notices->whereRaw('1 = 0');
                }
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }
        $intro = Introduction::where('status', 'publish')->orderby('created_at', 'DESC')->take(4)->get();
        $categories = CategoriesIntroduction::where('status', 'publish')->orderby('created_at', 'DESC')->get();
        return Theme::scope('intro.notices.index', ['intro' => $intro, 'notices' => $notices->paginate(10), 'categories' => $categories])->render();
    }

    public static function faq(Request $request)
    {

        $keyword = array_map('trim', explode(',', $request->keyword));
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;
        $categoryId = $request->categories_id;

        Theme::setTitle(__('eh-introduction') . ' | ' . __('eh-introduction.faqs') . ' | ' . __('eh-introduction.faqs.search'));

        $faq = FaqIntroduction::where('status', 'publish')
            ->where(function($query) use ($categoryId){
                if ($categoryId != '') {
                    $query->where('faq_categories_id', $categoryId);
                }
            })
            ->orderby('id', 'DESC');

        //--------------------------------------------------------------------------
        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }

        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $faq->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);

        }

        if ($keyword) {
            if(self::checkKeyWord($keyword, self::$forbidden) == false ){
                switch ($type) {
                    case 0:
                        $faq->where(function ($query) use ($keyword) {
                            foreach ($keyword as $title) {
                                $query->orWhere('question', 'like', '%' . $title . '%');
                            }
                        });
                        break;
                    case 1:
                        $faq->where(function ($query) use ($keyword) {
                            foreach ($keyword as $detail) {
                                $query->orWhere('answer', 'like', '%' . $detail . '%');
                            }
                        });
                        break;
                    default:
                        $faq->whereRaw('1 = 0');
                }
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }
        $categories = CategoriesIntroduction::where('status', 'publish')->orderby('created_at', 'DESC')->get();
        return Theme::scope('intro.faq.index', ['faq' => $faq->paginate(10), 'categories' => $categories])->render();
    }

    public static function room(Request $request)
    {
        $passwd = Setting::where('key', 'password_garden')->firstOrFail();
        if (checkPasswordGarden(Cookie::get('password_garden'))) {
            $keyword = $request->keyword;
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $type = $request->type;
            $orderby = $request->orderby;

            Theme::setTitle(__('egarden') . ' | ' . __('egarden.room') . ' | ' . __('egarden.room.search'));

            $room = Room::withCount([
                'member',
            ])->where('status', 'publish');

            //--------------------------------------------------------------------------
            try {
                $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
                $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
            } catch (\Exception $ex) {
                $startDate = null;
                $endDate = null;
            }

            if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

                $room->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);

            }

            if ($keyword) {
                if (in_array($keyword, self::$forbidden) === false) {
                    switch ($type) {
                        case 0:
                            $room->where('name', 'like', '%' . $keyword . '%');
                            break;
                        case 1:
                            $room->where('description', 'like', '%' . $keyword . '%');
                            break;
                        default:
                            $room->whereRaw('1 = 0');
                    }
                } else {
                    return redirect()->back()->with('err', '금지된 키워드');
                }
            }
            switch ($orderby) {
                case 1:
                    $room->orderBy('created_at', 'DESC');
                    break;
                case 2:
                    $room->withCount('member')->orderBy('member_count', 'desc');
                    break;
                    // case 3:
                    //     $egarden->withCount(['likes'])->orderBy('likes_count', 'DESC');
                    //     break;
            }
            $categories = CategoriesGarden::where('status', 'publish')->where('name', '!=', 'E-garden')
                ->whereIn('special_garden', checkCanViewGarden())
                ->orderBy('created_at', 'DESC')->where('level_access', '<=', getLevelMember())->get();

            $categoriesEgarden = CategoriesGarden::where('status', 'publish')->where('name', 'E-garden')
                ->whereIn('special_garden', checkCanViewGarden())
                ->orderBy('created_at', 'DESC')->first();
            $notices = NoticesGarden::where('categories_gardens_id',
                CategoriesGarden::where('special_garden', CategoriesGarden::E_GARDEN)->first()->id
            )->where('status', 'publish')->orderBy('created_at', 'DESC')->get();
            $slides = Slides::where('code', 'EGARDEN')->where('status', 'publish')->first();
            $countAccess = countAccess();

            return Theme::scope('garden.egarden.room.home', ['categories' => $categories, 'room' => $room->paginate(9), 'notices' => $notices, 'slides' => $slides, 'countAccess' => $countAccess])->render();

        } else {
            return redirect()->route('gardenFE.passwd')->with('permission', 'Check password access garden');
        }

    }

    public static function egarden(Request $request, $idCategory)
    {
        $keyword = $request->keyword;
        $type = $request->type;
        $id = $request->id;
        $orderby = $request->orderby;
        $categories_room_id = $request->categories_room_id;

        $startDate = $request->input('startDate', null);
        $endDate = $request->input('endDate', null);

        $room = Room::where('id', $id)->where('status', 'publish')->firstOrFail();
        // $egarden = Egarden::where('room_id', $id)->where('status', 'publish');

        // 금지어 차단
        if (in_array($keyword, self::$forbidden)) {
            return redirect()->back()->with('err', '금지된 키워드');
        }

        $createdRoomIds = auth()->guard('member')->user()->roomCreated->pluck('id');
        $joinedRoomIds = auth()->guard('member')->user()->roomJoined->pluck('id');
        // $egarden = Egarden::whereIn('room_id', $joinedRoomIds)->orWhereIn('room_id', $createdRoomIds)
        $egarden = Egarden::where(function($query) use ($startDate, $endDate, $keyword, $type, $categories_room_id, $orderby){
                if ($keyword) {
                    switch ($type) {
                        case 0:
                            $query->where('title', 'like', '%' . $keyword . '%');
                            break;
                        case 1:
                            $query->where('detail', 'like', '%' . $keyword . '%');
                            break;
                        default:
                            $query->whereRaw('1 = 0');
                    }
                }

                if ($categories_room_id) {
                    $query->where('categories_room_id', $categories_room_id);
                }

                if ($startDate && $endDate) {
                    $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
                    $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');

                    $query->whereBetween('published', [$startDate, $endDate]);
                }
            });

        if ($orderby) {
            switch ($orderby) {
                case 2:
                    $egarden->orderBy('lookup', 'DESC');
                    break;
                case 3:
                    $egarden->withCount('comments')->orderBy('comments_count', 'desc');
                    break;
                case 4:
                    $egarden->withCount(['likes'])->orderBy('likes_count', 'DESC');
                    break;
                default:
                    $egarden->orderBy('published', 'DESC');
                // case 3:
                //     $egarden->withCount(['likes'])->orderBy('likes_count', 'DESC');
                //     break;
            }
        }

        $egarden = $egarden->paginate(10);

        $categories = CategoriesGarden::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

        $notices = NoticesGarden::where('categories_gardens_id',
            CategoriesGarden::where('special_garden', CategoriesGarden::E_GARDEN)->first()->id
        )->where('status', 'publish')->orderBy('created_at', 'DESC')->get();

        $description = DescriptionGarden::where('categories_gardens_id', $categoriesEgarden->id ?? null)->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $slides = Slides::where('code', 'EGARDEN')->where('status', 'publish')->first();
        Theme::setTitle(__('egarden.room') . ' #' . $id . ' | ' . __('egarden') . ' | ' . __('egarden.search'));
        $countAccess = countAccess();

        $selectCategories = CategoriesGarden::where('id', $idCategory)->where('level_access', '<=', getLevelMember())->where('status', 'publish')->where('name', '!=', 'E-garden')->orderBy('created_at', 'DESC')->first();
        $popular = $selectCategories->popular();
        if (!is_null($keyword)) {
            if ($popular->count() > 0) {

                $check = 0;
                foreach ($popular->get() as $key => $item) {

                    if ($item->keyword == handleKey($keyword)) {
                        $item->lookup++;
                        if (getStatusDateByDate($item->updated_at) == "Today") {
                            $item->today_lookup++;
                        } else {
                            $item->today_lookup = 1;
                        }
                        $item->save();
                        $check++;
                    }

                }

                if ($check == 0) {
                    PopularGarden::updateOrCreate(['keyword' => handleKey($keyword), 'lookup' => 1, 'today_lookup' => 1, 'categories_id' => $selectCategories->id]);
                };

            } else {
                PopularGarden::updateOrCreate(['keyword' => handleKey($keyword), 'lookup' => 1, 'today_lookup' => 1, 'categories_id' => $selectCategories->id]);
            }
        }
        $popular = $selectCategories->popular->take(5);
        $todayPopular = $selectCategories->todaySearch();

        // return Theme::scope('garden.egarden.index', [
        //     'categories' => $categories,
        //     'egarden' => $egarden->paginate(10),
        //     'room' => $room,
        //     'notices' => $notices,
        // ])->render();

        return Theme::scope('garden.egarden.home', [
            'categories' => $categories,
            'notices' => $notices,
            'description' => $description,
            'slides' => $slides,
            'countAccess' => $countAccess,
            'room' => $room,
            'egarden' => $egarden,
            'roomJoined' => auth()->guard('member')->user()->roomJoined,
            'roomCreated' => auth()->guard('member')->user()->roomCreated,
            'popular' => $popular,
            'todayPopular' => $todayPopular,
        ])->render();
    }

    public static function masterRoom($idCategory, Request $request)
    {
        $keyword = array_map('trim', explode(',', $request->keyword));
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;

        $selectCategories = CategoriesMasterRoom::where('status', 'publish')->where('id', $idCategory)->firstOrFail();
        $masterRoom = MasterRoom::where('categories_master_rooms_id', $selectCategories->id)->ordered();

        //---------------------------------------------------------------->orderBy('created_at', 'DESC')->paginate(10);
        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }

        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $masterRoom->where('published', '>=', $startDate)->where('published', '<=', $endDate);

        }
        if ($keyword) {
            if(self::checkKeyWord($keyword, self::$forbidden) == false ){
                switch ($type) {
                    case 0:
                        $masterRoom->where(function ($query) use ($keyword) {
                            foreach ($keyword as $title) {
                                $query->orWhere('title', 'like', '%' . $title . '%');
                            }
                        });
                        break;
                    case 1:
                        $masterRoom->where(function ($query) use ($keyword) {
                            foreach ($keyword as $detail) {
                                $query->orWhere('content', 'like', '%' . $detail . '%');
                            }
                        });
                        break;
                    default:
                        $masterRoom->whereRaw('1 = 0');
                }
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }

        Theme::setTitle(__('master_room') . ' | ' . $selectCategories->name . ' | ' . __('master_room.search'));
        Theme::breadcrumb()->add(__('master_room'), route('masterRoomFE.list'))->add($selectCategories->name, 'http:...');

        $categories = CategoriesMasterRoom::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

        return Theme::scope('master-room.index', ['masterRoom' => $masterRoom->paginate(10), 'categories' => $categories, 'idCategory' => $selectCategories->id])->render();

    }
    public static function newContents($idCategory, Request $request)
    {
        $keyword = array_map('trim', explode(',', $request->keyword));
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;

        $selectCategories = CategoriesNewContents::where('status', 'publish')->where('id', $idCategory)->firstOrFail();
        $newContents = NewContents::where('categories_new_contents_id', $selectCategories->id)->ordered();

        //---------------------------------------------------------------->orderBy('created_at', 'DESC')->paginate(10);
        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }

        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $newContents->where('published', '>=', $startDate)->where('published', '<=', $endDate);

        }
        if ($keyword) {
            if(self::checkKeyWord($keyword, self::$forbidden) == false ){
                switch ($type) {
                    case 0:
                        $newContents->where(function ($query) use ($keyword) {
                            foreach ($keyword as $title) {
                                $query->orWhere('title', 'like', '%' . $title . '%');
                            }
                        });
                        break;
                    case 1:
                        $newContents->where(function ($query) use ($keyword) {
                            foreach ($keyword as $detail) {
                                $query->orWhere('content', 'like', '%' . $detail . '%');
                            }
                        });
                        break;
                    default:
                        $newContents->whereRaw('1 = 0');
                }
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }

        Theme::setTitle(__('new_contents') . ' | ' . $selectCategories->name . ' | ' . __('new_contents.search'));
        Theme::breadcrumb()->add(__('new_contents'), route('newContentsFE.list'))->add($selectCategories->name, 'http:...');

        $categories = CategoriesNewContents::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

        return Theme::scope('new-contents.index', ['newContents' => $newContents->paginate(10), 'categories' => $categories, 'idCategory' => $selectCategories->id])->render();

    }

    public static function openSpace(Request $request)
    {

        $keyword = array_map('trim', explode(',', $request->keyword));
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;
        $style = $request->style ?? 0;
        $categories_id = $request->categories_id;
        $orderby = $request->orderby;

        Theme::setTitle(__('life') . ' | ' . __('life.open_space') . ' | ' . __('life.open_space.search'));

        $openSpace = OpenSpace::where('status', 'publish');

        //--------------------------------------------------------------------------
        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }

        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $openSpace->where('published', '>=', $startDate)->where('published', '<=', $endDate);

        }

        if ($keyword) {
            if(self::checkKeyWord($keyword, self::$forbidden) == false ){
                switch ($type) {
                    case 0:
                        $openSpace->where(function ($query) use ($keyword) {
                            foreach ($keyword as $title) {
                                $query->orWhere('title', 'like', '%' . $title . '%');
                            }
                        });
                        break;
                    case 1:
                        $openSpace->where(function ($query) use ($keyword) {
                            foreach ($keyword as $detail) {
                                $query->orWhere('detail', 'like', '%' . $detail . '%');
                            }
                        });
                        break;
                    default:
                        $openSpace->whereRaw('1 = 0');
                }
            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }
        if (!is_null($categories_id)) {
            $openSpace->where('categories_id', $categories_id);

        }

        $openSpace->withCount(['dislikes'])->has('dislikes', '<', 10);
        switch ($orderby) {
            case 2:
                $openSpace->orderBy('views', 'DESC');
                break;
            case 3:
                $openSpace->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 4:
                $openSpace->withCount(['likes'])->orderBy('likes_count', 'DESC');
                break;
        }
        $openSpace->orderBy('published', 'DESC');
        $notices = Notices::where('code', 'OPEN_SPACE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = Description::where('code', 'OPEN_SPACE_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('openSpaceFE.create');
        return Theme::scope('life.open_space.index', ['openSpace' => $openSpace->paginate(10), 'style' => $style, 'notices' => $notices, 'description' => $description, 'canCreate' => $canCreate])->render();
    }

    public function major(Request $request)
    {
        $major_id = $request->major;
        $title = $request->title;
        $professor_name = $request->professor_name;
        $query = Evaluation::with(['major'])->withCount(['comments'])->orderBy('created_at', 'DESC');
        if (!is_null($major_id)) {
            $query->whereIn('id', function ($query) use ($major_id) {
                $query->from('major_evaluation')->where('major_id', $major_id)->select('evaluation_id');
            }
            );
        }

        if (!is_null($title)) {
            if (in_array($title, self::$forbidden) === false) {
                $query->where('title', 'like', '%' . $title . '%');
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if (!is_null($professor_name)) {
            if (in_array($professor_name, self::$forbidden) === false) {
                $query->where('professor_name', 'like', '%' . $professor_name . '%');
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $evaluation = $query->paginate(10);
        $dataEvaluations = $evaluation->items();
        if ($evaluation->count() > 0) {
            collect($dataEvaluations)->map(function ($data) {
                $data->avg_comment = $data->getAvgVote();
            });
        }

        Theme::breadcrumb()->add('Evaluation', route('campus.evaluation_comments_major'))->add('Lastest comments', 'http:...');
        Theme::setTitle('Campus | evaluation | Lastest comments');
        $notices = NoticesCampus::where('code', 'EVALUATION_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = DescriptionCampus::where('code', 'EVALUATION_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $categories = Major::where('status', 'publish')->where('parents_id', 0)->get();

        return Theme::scope('campus.evaluation.major', [
            'evaluation' => $evaluation,
            'notices' => $notices, 'description' => $description,
            'categories' => $categories,
            'dataEvaluations' => $dataEvaluations
        ])->render();
    }

    public static function address(Request $request)
    {
        $keyword = $request->keyword;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;

        $address = AddressMasterRoom::orderBy('published', 'DESC');

        //---------------------------------------------------------------->orderBy('created_at', 'DESC')->paginate(10);
        try {
            $startDate = Carbon::createFromFormat('Y.m.d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('Y.m.d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            $startDate = null;
            $endDate = null;
        }

        if (!is_null($startDate) && !is_null($endDate) && $endDate >= $startDate) {

            $address->where('published', '>=', $startDate)->where('published', '<=', $endDate);

        }

        if ($keyword) {
            if (in_array($keyword, self::$forbidden) === false) {
                switch ($type) {
                    case 1:
                        $address->where('address', 'like', '%' . $keyword . '%');
                        break;
                    case 2:
                        $address->where('classification', 'like', '%' . $keyword . '%');
                        break;
                    case 3:
                        $address->where('email', 'like', '%' . $keyword . '%');
                        break;
                    case 4:
                        $address->where('home_page', 'like', '%' . $keyword . '%');
                        break;
                    case 5:
                        $address->where('name', 'like', '%' . $keyword . '%');
                        break;
                    case 6:
                        $address->where('home_phone', 'like', '%' . $keyword . '%');
                        break;
                    case 7:
                        $address->where('mobile_phone', 'like', '%' . $keyword . '%');
                        break;
                    case 8:
                        $address->where('company_phone', 'like', '%' . $keyword . '%');
                        break;
                    case 9:
                        $address->where('memo', 'like', '%' . $keyword . '%');
                        break;
                }

            } else {
                return redirect()->back()->with('err', '금지된 키워드');
            }
        }

        Theme::setTitle(__('master_room') . ' | ' . __('master_room.address.list') . ' | ' . __('master_room.search'));
        Theme::breadcrumb()->add(__('master_room'), route('masterRoomFE.list'))->add(__('master_room.address.list'), 'http:...');

        $categories = CategoriesMasterRoom::where('status', 'publish')->orderBy('created_at', 'DESC')->get();

        return Theme::scope('master-room.address.index', ['address' => $address->paginate(10), 'categories' => $categories])->render();

    }
    public function roomAjax(Request $request)
    {
        $keyword = $request->keyword;
        $orderby = $request->orderby;
        $builder = Room::where('name', 'like', '%' . $keyword . '%');
        $build = clone $builder;
        // case 1:
        //     $room = $build->orderBy('name', 'DESC')->paginate(9);
        //     break;
        // case 2:
        //     # code...
        //     $room = $build->withCount('member')->orderBy('member_count','DESC')->paginate(9);
        //     break;
        // case 3:
        //     $tableEgarden = resolve(Egarden::class)->getTable();
        //     $tableRoom = resolve(Room::class)->getTable();
        //     $build->orderByDesc(DB::raw("(SELECT MAX({$tableEgarden}.created_at) FROM {$tableEgarden} WHERE {$tableEgarden}.room_id = {$tableRoom}.id)"));
        //     $room = $build->paginate(9);

        //     break;
        // default:
        //     $room = $builder->orderBy('id', 'DESC')->paginate(9);
        //     break;
        switch ($orderby) {
            case 1:
                $room = $build->orderBy('name', 'DESC')->take(10)->get();
                break;
            case 2:
                $room = $build->withCount('member')->orderBy('member_count', 'DESC')->take(10)->get();
                break;
            case 3:
                $tableEgarden = resolve(Egarden::class)->getTable();
                $tableRoom = resolve(Room::class)->getTable();
                $build->orderByDesc(DB::raw("(SELECT MAX({$tableEgarden}.created_at) FROM {$tableEgarden} WHERE {$tableEgarden}.room_id = {$tableRoom}.id)"));
                $room = $build->take(10)->get();
                break;

            default:
                $room = $build->orderBy('name', 'DESC')->take(10)->get();
                break;
        };
        $data = $room;
        return response()->json([
            'items' => $data,
        ]);
    }
}
