<?php

use Botble\Campus\Models\Schedule\ScheduleFilter;
use Botble\Campus\Models\StudyRoom\StudyRoomCategories;
use Botble\Garden\Models\AccessGarden;
use Botble\Garden\Models\CategoriesGarden;
use Botble\Garden\Models\Egarden\Room;
use Botble\Garden\Models\Egarden\RoomMember;
use Botble\Life\Models\Ads\AdsCategories;
use Botble\Life\Models\FlareCategories;
use Botble\Life\Models\Jobs\JobsCategories;
use Botble\Life\Models\Shelter\ShelterCategories;
use Botble\Media\Models\MediaFolder;
use Botble\Member\Models\Member;
use Botble\Page\Models\Page;
use Botble\Setting\Models\Setting;
use Botble\Slug\Models\Slug;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use IlluminateAgnostic\Str\Support\Str;
use Illuminate\Support\Facades\Date;
use Botble\Member\Models\MemberBlackList;

require_once __DIR__ . '/../vendor/autoload.php';

define('EVULATION_REMARK', [
    __('campus.timetable.university'),
    __('campus.timetable.major'),
    __('campus.timetable.title'),
    __('campus.evaluation.major_status'),
    __('campus.genealogy.professor_name'),
]);

register_page_template([
    'default' => 'Default',
    'page' => 'Pages',
    'timetable' => 'TimeTable',
]);

register_sidebar([
    'id' => 'second_sidebar',
    'name' => 'Second sidebar',
    'description' => 'This is a sample sidebar for ewhaian theme',
]);

theme_option()
    ->setArgs(['debug' => config('app.debug')])
    ->setSection([
        'title' => __('general'),
        'desc' => __('General settings'),
        'id' => 'opt-text-subsection-general',
        'subsection' => true,
        'icon' => 'fa fa-home',
    ])
    ->setSection([
        'title' => __('logo'),
        'desc' => __('Change logo'),
        'id' => 'opt-text-subsection-logo',
        'subsection' => true,
        'icon' => 'fa fa-image',
        'fields' => [
            [
                'id' => 'logo',
                'type' => 'mediaImage',
                'label' => __('logo'),
                'attributes' => [
                    'name' => 'logo',
                    'value' => null,
                ],
            ],
        ],
    ])
    ->setField([
        'id' => 'copyright',
        'section_id' => 'opt-text-subsection-general',
        'type' => 'text',
        'label' => __('copyright'),
        'attributes' => [
            'name' => 'copyright',
            'value' => '© 2016 Botble Technologies. All right reserved. Designed by Nghia Minh',
            'options' => [
                'class' => 'form-control',
                'placeholder' => __('Change copyright'),
                'data-counter' => 120,
            ],
        ],
        'helper' => __('copyright_on_footer'),
    ]);

function highlightWords($text, $word)
{
    $words = explode(',', $word);

    $wordReplace = null;

    foreach ($words as $word){
        if (str_contains($text, $word)) {
            $wordReplace = $word;
            break;
        }
    }

    $text = preg_replace("|($wordReplace)|ui", "<span class=\"hlw\">$1</span>", $text);
    return $text;
}

function getToDate($type = 0)
{
    if ($type == 1) {
        return date('Y.m.d'); // get to date
    }
    return "";
}

function highlightWords2($text, $word, $num = null)
{
    $num = !is_null($num) ? $num : 30;
    $text = html_entity_decode($text);
    $text = strip_tags($text);
    $text = preg_replace('/&#?[a-z0-9]{2,8};/i', '', $text);
    if ($text == null || $word == null) {
        if (mb_strlen($text) > $num) {
            return mb_substr($text, 0, $num, 'utf-8') != '' ?
                mb_substr($text, 0, $num, 'utf-8') . '...' :
                '';
        } else {
            return mb_substr(strip_tags($text), 0, $num, 'utf-8');
        }
    }
    $text = strip_tags($text);

    $position = mb_strpos($text, $word, 0, 'utf-8');

    if ($position > $num) {
        $text = '...' . mb_substr($text, ($position - 10), $num, 'utf-8') . '...';
    } else {
        if (mb_strlen(mb_substr($text, 0, $num, 'utf-8')) == $num) {
            $text = mb_substr($text, 0, $num, 'utf-8') . '...';
        } else {
            $text = mb_substr($text, 0, $num, 'utf-8');
        }
    }

    return highlightWords($text, $word);
}

function createSlug($name, $parent_id)
{
    $slug = Str::slug($name);
    $index = 1;
    $baseSlug = $slug;
    while (checkIfExists('slug', $slug, $parent_id)) {
        $slug = $baseSlug . '-' . $index++;
    }

    return $slug;
}
function checkIfExists($key, $value, $parent_id)
{
    $count = MediaFolder::where($key, '=', $value)->where('parent_id', $parent_id)->withTrashed();

    /**
     * @var Builder $count
     */
    $count = $count->count();

    return $count > 0;
}

function simpleTitle($text, $length = 40)
{
    if ($text == null) {
        return $text;
    }

    $text = strip_tags(html_entity_decode($text));

    $text = mb_substr($text, 0, $length, "utf-8") . "...";

    return $text;
}

function geFirsttImageInArray($arr, $size, $type = 0)
{
    if ($type == 0) {
        if ($arr == null) {
            return '/vendor/core/images/placeholder.png';
        }

        foreach ($arr as $value) {
            if ($value !== null) {
                return get_image_url($value, $size ?? 'thumb');
            }
        }

        return '/vendor/core/images/placeholder.png';
    }
    if ($type == 1 && $arr) {
        foreach ($arr as $value) {
            if ($value !== null) {
                return $value;
            }
        }
    }

}

function getDateFlareMarket($date)
{
    $start = date("Y-m-d", strtotime($date));
    if (strtotime(date("Y-m-d", strtotime('today'))) == strtotime($start)) {
        $start = Carbon::parse($date)->format("H:i");
    }
    return $start;
}

function getStatusDateByDate($date)
{
    $start = date("Y-m-d", strtotime($date));

//    if (strtotime(date("Y-m-d", strtotime('today'))) == strtotime($start)) {
//        $start = Carbon::parse($date)->format("H:i:s");
//    } else if (strtotime(date("Y-m-d", strtotime('tomorrow'))) == strtotime($start)) {
//        $start = "Tomorrow";
//    } else if (strtotime(date("Y-m-d", strtotime('yesterday'))) == strtotime($start)) {
//        $start = "Yesterday";
//    }

    return $start;
}

function getStatusDateByDate2($date)
{
    $start = date("Y-m-d", strtotime($date));
    return $start;
}

function getFlareCategories($id, $type = 1, $link = 'life.elements.showCategories')
{
    switch ($type) {
        case 1:
            $flareCategories = FlareCategories::find($id);
            break;

        case 2:
            $flareCategories = JobsCategories::find($id);
            break;
        case 3:
            $flareCategories = AdsCategories::find($id);
            break;
        case 4:
            $flareCategories = ShelterCategories::find($id);
            break;
        case 5:
            $flareCategories = StudyRoomCategories::find($id);
            break;
    }

    return Theme::partial($link, ['flareCategories' => $flareCategories, 'isParent' => false]);
}

function getDeadlineDate($date, $type = 1)
{
    if(!$date){
        return "종료";
    }
    $fdate = $date;

    $datetime1 = Carbon::createFromDate($fdate);
    $datetime2 = now()->startOfDay();
    $days = $datetime2->diffInDays($datetime1, false);
    if ($days <= 0) {
        $hours = $datetime2->diffInHours($datetime1, false);
        if ($hours > 0) {
            return "D-day";
        } else {
            return "종료";
        }
    } elseif ($days > 0 && $days < 32) {
        return "D-" . $days;
    } elseif ($days >= 32) {
        if ($type == 1) {
            return strftime('%Y-%m-%d', strtotime($date));
        } else {
            return date("d M | H:i a", strtotime($date));
        }

    } else {
        return "종료";
    }

}
function getTimeToday($date)
{

    $fdate = $date;
    $tdate = date("Y/m/d H:i");
    $datetime1 = new DateTime($fdate);
    $datetime2 = new DateTime($tdate);
    $interval = $datetime2->diff($datetime1);
    $days = $interval->format('%r%a'); //now do whatever you like with $days

    if ($days != 0) {
        return strftime('%Y-%m-%d', strtotime($date));
    } else {
        return strftime('%H:%m', strtotime($date));
    }

}

function getYoutubeVideoID($link){
    $urlParams = parse_url($link);
    //dd($urlParams);

    $idVideo = "";
    if(count($urlParams) > 2){ // if no link, array after parse just only have one param is path = ""
        if (strpos($urlParams["host"], 'youtube') > 0) {
            $idVideoArray = explode("=",$urlParams["query"]);
            $idVideo = $idVideoArray[1];
        } elseif ($urlParams["host"] == 'youtu.be') {
            $idVideo = str_replace("/","",$urlParams["path"]);
        } else {
            $idVideo = "";
        }
    }
    return $idVideo;


}

function getIDVideoYoutube($link)
{
    $url = $link;
    $temp = preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches);
    if ($temp == 1) {
        $id = $matches[1];
        return $id;
    } else {
        return 0;
    }

}

function handleKey($keyword)
{
    $key = $keyword;

    while (strpos($key, "  ")) {
        $key = str_replace("  ", " ", $key);
    }

    return mb_strtolower($key);
}

function checkImageInContent($content)
{
    if (mb_strpos($content, '<img') !== false) {
        return true;
    } else {
        return false;
    }

}

function toJsonLecture($datetime, $title,$group_color)
{

    $jsonLecture = json_decode($datetime, true);
    if(!$jsonLecture){
        return null;
    }

    $arrNewSku = array();
    $incI = 0;

    foreach ($jsonLecture as $arrKey => $arrData) {
        $arrNewSku[$incI]['title'] = $title;
        $arrNewSku[$incI]['group_color'] = $group_color;
        $arrNewSku[$incI]['day'] = $arrData['day'];
        $arrNewSku[$incI]['from'] = (float) $arrData['from'];
        $arrNewSku[$incI]['to'] = (float) $arrData['to'];
        $incI++;
    }
    //Convert array to json form...
    $encodedSku = json_encode($arrNewSku);
    return $encodedSku;

}

function getMajor($major, $keyword, $checkKeyword = true)
{
    $result = [];
    foreach ($major as $key => $item) {

        if(!$checkKeyword){
            array_push($result, $item);
        }
        if (in_array($item, $keyword)) {
            array_push($result, $item);
        }
    }

    return $result;
}

function checkQuaterYear()
{
    $thisMonth = date('m');
    switch ($thisMonth) {
        case (1 <= $thisMonth && $thisMonth <= 3):
            return ['start' => date('Y-01-01'), 'end' => date('Y-03-31')];
            break;
        case (4 <= $thisMonth && $thisMonth <= 6):
            return ['start' => date('Y-04-01'), 'end' => date('Y-06-30')];
            break;
        case (7 <= $thisMonth && $thisMonth <= 9):
            return ['start' => date('Y-07-01'), 'end' => date('Y-09-30')];
            break;
        case (10 <= $thisMonth && $thisMonth <= 12):
            return ['start' => date('Y-10-01'), 'end' => date('Y-12-31')];
            break;
        default:
            return ['start' => date('Y-01-01'), 'end' => date('Y-03-31')];
            break;
    }
}


function checkHalfYear()
{
    $thisMonth = date('m');
    switch ($thisMonth) {
        case (1 <= $thisMonth && $thisMonth <= 7):
            return ['start' => date('Y-01-01'), 'end' => date('Y-06-30')];
            break;
        case (7 <= $thisMonth && $thisMonth <= 12):
            return ['start' => date('Y-07-01'), 'end' => date('Y-12-31')];
            break;
        default:
            return ['start' => date('Y-01-01'), 'end' => date('Y-06-30')];
            break;
    }
}

function isAfterDay($date, $day) {
    $start = Carbon::createFromFormat('Y-m-d H:i:s', $date)->addDays($day);
    $now = Carbon::now();
    return $now > $start;
}

function checkPasswordGarden($requestPW)
{

    if(auth()->guard('member')->user()->role_member_id == 7){
        return true;
    }
    if(empty($requestPW)){
        $requestPW = Cookie::get('password_garden_report');
        addAccessGarden();
    }
    if (mb_strlen($requestPW) < 5) {
        removeAccessGarden();
        return false;
    }

    $passwd_garden_member = mb_substr($requestPW, -4);

    $passwd_garden = mb_substr($requestPW, 0, mb_strlen($requestPW) - 4);

    //check passwd garden of member

    if ($passwd_garden_member != auth()->guard('member')->user()->passwd_garden) {
        removeAccessGarden();
        return false;
    }

    $passwd = Setting::where('key', 'password_garden')->firstOrFail();

    if (Hash::check($passwd_garden, $passwd->value)) {
        addAccessGarden();
        return true;
    } else {
        removeAccessGarden();
        return false;
    }
}

function addAccessGarden()
{
    $memberAccess = AccessGarden::where('member_id', auth()->guard('member')->user()->id)->first();

    if (isset($memberAccess)) {
        $memberAccess->time_access_from = date("Y/m/d H:i:s", strtotime("now"));
        $memberAccess->time_access_to = date("Y/m/d H:i:s", strtotime("+30 minutes"));
        $memberAccess->status = 'publish';
        $memberAccess->save();
    } else {
        $memberAccess = new AccessGarden;
        $memberAccess->member_id = auth()->guard('member')->user()->id;
        $memberAccess->time_access_from = date("Y/m/d H:i:s", strtotime("now"));
        $memberAccess->time_access_to = date("Y/m/d H:i:s", strtotime("+30 minutes"));
        $memberAccess->save();
    }
}

function removeAccessGarden()
{
    $memberAccess = AccessGarden::where('member_id', auth()->guard('member')->user()->id)->first();

    if (isset($memberAccess)) {
        $memberAccess->status = 'pending';
        $memberAccess->save();
    }
}

function countAccess()
{
    return AccessGarden::where('time_access_from', '<=', date("Y/m/d H:i:s", strtotime("now")))->where('time_access_to', '>=', date("Y/m/d H:i:s", strtotime("now")))->where('status', 'publish')->count();
}

function getPolicyPage()
{
    $page = Page::where('name', 'Policy')->first();
    if (!is_null($page)) {
        $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

        $slug = Slug::where('reference_id', $page->id)->first();

        return asset('/') . $slug->key;

    } else {
        return asset('/');
    }

}

function getTermConditions()
{
    $page = Page::where('name', 'Terms and Conditions')->first();
    if (!is_null($page)) {
        $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

        $slug = Slug::where('reference_id', $page->id)->first();

        return asset('/') . $slug->key;

    } else {
        return asset('/');
    }
}
function getStatusWriter($status)
{
    $style = "background-size:contain; width: 20px;height: 20px;margin-right: 0.563em;";
    switch ($status) {
        case 'real_name_certification':
            return ' <span class="icon-label disable">
                        <svg width="2.359" height="15" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                xlink:href="#icon_exclamation"></use>
                        </svg>
                    </span>';
            break;
        case 'sprout':
            return ' <div style="background: url(' . Theme::asset()->url('img/sprout.png') . ') no-repeat center; '. $style.'"></div>';
            break;
        case 'certification':
            if (Route::currentRouteName() != 'gardenFE.details'){
                return '<span class="icon-label pink">
                        <svg width="12" height="12" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                xlink:href="#icon_check"></use>
                        </svg>
                    </span>';
            }
            # code...
            break;

        default:
            # code...
            break;
    }
}

//Get name by Value 1 by 1
function getNameByValue($value, $textName = null)
{
    //chuyển _ thành khoảng trắng , in hoa chữ cái đầu tiên trong chuỗi
    // ['value'=>'TextName']
    if ($textName == null) {
        if (empty($value)) {
            return '';
        }
        return ucfirst(str_replace('_', ' ', $value));
    } else {

        foreach ($textName as $key => $item) {
            if ($value == $key) {
                return $item;
            }
        }

        return '';
    }

}

function addPointForMembers($point = 2)
{
    auth()->guard('member')->user()->update(['point' => auth()->guard('member')->user()->point + $point]);
}

function getLevelMember($user = null)
{
    if (!isset($user)) {
        $user = auth()->guard('member')->user();
    }

    $x = @$user->point ?: 0;
    $y = sqrt(($x * 1.2) / 15) + 1;
    $z = (int) floor($y);
    return $z;
}

function isOnBlacklist($memberId)
{
    $count = MemberBlackList::where('member_id', $memberId)->count();
    return $count > 0;
}

function getPercentLevelMember()
{
    $x = auth()->guard('member')->user()->point;
    $y = sqrt(($x * 1.2) / 15) + 1;
    $z = (int) floor($y);
    return round ( (round($y, 2) - $z) * 100,2 );
}

function getNew($date, $type = 0)
{
    $start = date("Y-m-d", strtotime($date));

    if (strtotime($start) == strtotime('today')) {
        return true;
    }
    if ($type != 0) {
        if (strtotime('today') == strtotime($start . ' +1 day')) {
            return true;
        }
        if (strtotime('today') == strtotime($start . ' +2 day')) {
            return true;
        }
    }
    return false;
}

function titleGenealogy($genealogy, $keyword)
{

    $convertNameExam =  [
        'midterm' => __('campus.genealogy.midterm'),
        'final' => __('campus.genealogy.final'),
        'quiz' => __('campus.genealogy.quiz'),
        'other' => __('campus.genealogy.other'),
    ];

    return
        highlightWords2($genealogy->semester_year, $keyword)
        . '-' . highlightWords2(SEMESTER_SESSION_GENEALOGY[$genealogy->semester_session], $keyword)
        . ' / ' . highlightWords2($genealogy->class_name, $keyword)
        . ' / ' . highlightWords2($genealogy->professor_name, $keyword)
        . ' / ' . highlightWords2($convertNameExam[$genealogy->exam_name] ?? null, $keyword) ;

}

function getName($nickname, $id_login , $canViewCommenter = false )
{
    $routeName = Route::currentRouteName();
    switch ($routeName) {
        case 'contents.details':
            // if($canViewCommenter) {
            //     return $nickname . ' / ' . $id_login;
            // }
            // else {
                return __('comments.anonymous');
            // }
            break;
        case 'campus.genealogy_details':
        case 'gardenFE.details':
        case 'egardenFE.details':
            return __('comments.anonymous');
            break;
        default:
            return $nickname . ' / ' . subIdLogin($id_login);
            break;
    }

}
function getNickName($id){
    $member = Member::find($id);
    if ($member == null) {
        return "Anonymous";
    }
    return $member->nickname;
}
function subIdLogin($id_login)
{
    $len = mb_strlen($id_login);
    switch ($len) {
        case (0 < $len && $len <= 5):
            # code...
            return mb_substr($id_login, 0, -2) . '**';
            break;
        case (5 < $len && $len < 9):
            return mb_substr($id_login, 0, -3) . '***';
            break;
        case (9 <= $len):
            return mb_substr($id_login, 0, -4) . '****';
            break;
        default:
            return mb_substr($id_login, 0, -4) . '****';
            break;
    }
}

function getLvlImage()
{
    $level = getLevelMember();
    switch (true) {
        case 0 < $level && $level < 10:
            return Theme::asset()->url('img/lvl_img/lv,1~9.png');
            break;
        case 10 <= $level && $level < 30:
            # code...
            return Theme::asset()->url('img/lvl_img/lv,10~29.png');
            break;
        case 30 <= $level && $level < 60:
            # code...
            return Theme::asset()->url('img/lvl_img/lv,30~59.png');
            break;
        case 60 <= $level && $level < 100:
            # code...
            return Theme::asset()->url('img/lvl_img/lv,60~99.png');
            break;
        case 100 <= $level:
            # code...
            return Theme::asset()->url('img/lvl_img/lv,100~.png');
            break;
        default:
            # code...
            return Theme::asset()->url('img/avatar.png');
            break;
    }
}

function getTodayFilter($schedule_id)
{
    $listFilter =  ScheduleFilter::where('status', 'publish')->get();
    foreach ($listFilter as $key => $filter) {
        foreach ($filter->getSchedule() as $item) {
            if($item->id == $schedule_id ) {
                return $key;
            }
        }
    }

    $today_filter =  ScheduleFilter::where('status', 'publish')->where('start','<=', today())->where('end','>=',today())->first();
    foreach ($listFilter as $key => $filter) {
        if( $today_filter->id == $filter->id){
            return $key;
        }
     }

    return 0;
}
function getCampusLastDay()
{

    $now = Carbon::now();
    $rp_date = Carbon::parse(date('Y-7-31 H:i:s'));
    $where_days = $rp_date->diffInDays($now);

    if ($now <= $rp_date) {
        $semester=1;
    } else {
        $semester=2;
    }

    // if($where_days < 0 ){
    //     $semester=1;
    // }else{
    //     $semester=2;
    // }

    $row =  \Botble\CampusLastday\Models\CampusLastday::where('status', 'publish')
        ->where('semester',$semester)
        ->where('year',date('Y'))
        ->first();

    $date = Carbon::parse($row->end);
    $diff = $date->diffInDays($now);

    return $diff+1;
}







function hasPermission($permission) {

    if ( auth()->guard('member')->check() && auth()->guard('member')->user()->hasPermission($permission) ) {
        return true;
    }
    return false;
}
function isAuthor($item){
    if ( auth()->guard('member')->check() && $item->member_id ==  auth()->guard('member')->user()->id ) {
        return true;
    }
    return false;
}
function checkCanViewGarden()
{
    // const PAST_GARDEN = 1;
    // const LAW_GARDEN = 2;
    // const JOB_GARDEN = 3;
    // const GRADUATION_GARDEN = 4;
    // const SECRET_GARDEN = 5;
    // const SPROUT_GARDEN = 6;

    $canView = [];
    if(!hasPermission('gardenFE.view')) {
        return $canView;
    }

    if(hasPermission('gardenFE.view.past_garden')) {
        array_push($canView,CategoriesGarden::PAST_GARDEN);
    }

    if(hasPermission('gardenFE.view.law_garden')) {
        array_push($canView,CategoriesGarden::LAW_GARDEN);
    }
    if(hasPermission('gardenFE.view.job_garden')) {
        array_push($canView,CategoriesGarden::JOB_GARDEN);
    }
    if(hasPermission('gardenFE.view.graduation_garden')) {
        array_push($canView,CategoriesGarden::GRADUATION_GARDEN);
    }
    if(hasPermission('gardenFE.view.secret_garden')) {
        array_push($canView,CategoriesGarden::SECRET_GARDEN);
    }
    if(hasPermission('gardenFE.view.sprout_garden')) {
        array_push($canView,CategoriesGarden::SPROUT_GARDEN);
    }
    return $canView;
}

function checkImportan($idRoom)
{
    $roomCreated = auth()->guard('member')->user()->roomCreated;
    $member_id = auth()->guard('member')->user()->id;

    if($roomCreated->contains('id', $idRoom)) {
        $room = Room::where('id', $idRoom)->where('member_id', auth()->guard('member')->user()->id)->wherein('status',array('publish', '화원장 신청 가능'))->firstOrFail();
        if( $room->important === 1) {
            return 'important';
        } ;
    };

    $roomMember = RoomMember::where('member_id', $member_id )->where('room_id', $idRoom)->first();

    if( !is_null($roomMember) && $roomMember->important === 1) {
        return 'important';
    };

    return;

}

function deleteLatestSympathyCommentOnPost($postType,$postID,$parentID=null,$sympathyType="dislike"){
    $currentUserId = auth()->guard('member')->user()->id;
    $symCancel = "dislike";
    if($sympathyType=="dislike"){
        $symCancel = "like";
    }
    $commentCancelID = null;
    $sympathyCommentCancelObj = \Botble\Life\Models\SympathyComment::where('member_id', $currentUserId)
    ->where('post_id',$postID)
    ->where('post_type',$postType)
    ->where('sympathy',$symCancel);



    if($parentID){
        $sympathyCommentCancelData = $sympathyCommentCancelObj->where('parent_id',$parentID)->get()->pluck('comment_id');
    }else{
        $sympathyCommentCancelData = $sympathyCommentCancelObj->whereNull('parent_id')->get()->pluck('comment_id');
    }

    $sympathyCommentCancel = $sympathyCommentCancelData->toArray();

    if($sympathyCommentCancel){
        $commentCancelID = $sympathyCommentCancel[0];
    }


    if($commentCancelID){
        if($postType=="garden"){
             \Botble\Garden\Models\CommentsGarden::where('id', $commentCancelID)->delete();
             \Botble\Garden\Models\CommentsGarden::where('parents_id', $commentCancelID)->delete();
        }
        if($postType=="flea-market"){
            \Botble\Life\Models\FlareComments::where('id', $commentCancelID)->delete();
            \Botble\Life\Models\FlareComments::where('parents_id', $commentCancelID)->delete();
        }
        if($postType=="open-space"){
            \Botble\Life\Models\OpenSpace\OpenSpaceComments::where('id', $commentCancelID)->delete();
            \Botble\Life\Models\OpenSpace\OpenSpaceComments::where('parents_id', $commentCancelID)->delete();
        }
        if($postType=="part-time-job"){
            \Botble\Life\Models\Jobs\JobsComments::where('id', $commentCancelID)->delete();
            \Botble\Life\Models\Jobs\JobsComments::where('parents_id', $commentCancelID)->delete();
        }
        if($postType=="shelter"){
            \Botble\Life\Models\Shelter\ShelterComments::where('id', $commentCancelID)->delete();
            \Botble\Life\Models\Shelter\ShelterComments::where('parents_id', $commentCancelID)->delete();
        }
        if($postType=="advertisement"){
            \Botble\Life\Models\Ads\AdsComments::where('id', $commentCancelID)->delete();
            \Botble\Life\Models\Ads\AdsComments::where('parents_id', $commentCancelID)->delete();
        }
        if($postType=="genealogy"){
            \Botble\Campus\Models\Genealogy\GenealogyComments::where('id', $commentCancelID)->delete();
            \Botble\Campus\Models\Genealogy\GenealogyComments::where('parents_id', $commentCancelID)->delete();
        }
        if($postType=="old-genealogy"){
            \Botble\Campus\Models\OldGenealogy\OldGenealogyComments::where('id', $commentCancelID)->delete();
            \Botble\Campus\Models\OldGenealogy\OldGenealogyComments::where('parents_id', $commentCancelID)->delete();
        }
        if($postType=="contents"){
            \Botble\Contents\Models\CommentsContents::where('id', $commentCancelID)->delete();
            \Botble\Contents\Models\CommentsContents::where('parents_id', $commentCancelID)->delete();
        }
        \Botble\Life\Models\SympathyComment::where('comment_id', $commentCancelID)->where('post_type', $postType)->delete();
    }
}

function sympathyCommentDetail($postType="garden",$postID,$content="",$sympathyType = "like",$parentID=null){
    $currentUserId = auth()->guard('member')->user()->id;
    if($postType=="garden"){
        $comment = new \Botble\Garden\Models\CommentsGarden;
        $comment->gardens_id = $postID;
    }
    if($postType=="flea-market"){
        $comment = new \Botble\Life\Models\FlareComments;
        $comment->flare_id = $postID;
    }
    if($postType=="open-space"){
        $comment = new \Botble\Life\Models\OpenSpace\OpenSpaceComments;
        $comment->open_space_id = $postID;
    }
    if($postType=="part-time-job"){
        $comment = new \Botble\Life\Models\Jobs\JobsComments;
        $comment->jobs_part_time_id = $postID;
    }
    if($postType=="shelter"){
        $comment = new \Botble\Life\Models\Shelter\ShelterComments;
        $comment->shelter_id = $postID;
    }
    if($postType=="advertisement"){
        $comment = new \Botble\Life\Models\Ads\AdsComments;
        $comment->advertisements_id = $postID;
    }
    if($postType=="genealogy"){
        $comment = new \Botble\Campus\Models\Genealogy\GenealogyComments;
        $comment->genealogy_id = $postID;
    }
    if($postType=="old-genealogy"){
        $comment = new \Botble\Campus\Models\OldGenealogy\OldGenealogyComments;
        $comment->old_genealogy_id = $postID;
    }
    if($postType=="contents"){
        $comment = new \Botble\Contents\Models\CommentsContents;
        $comment->contents_id = $postID;
    }

    if($postType=="events"){
        $comment = new \Botble\Events\Models\Comments;
        $comment->event_id = $postID;
    }

        $comment->content = $content;
        $comment->status = "publish";
        $comment->member_id = $currentUserId;
        $comment->anonymous = 0;
        if($parentID){
            $comment->parents_id = $parentID;
        }
        $comment->save();

    deleteLatestSympathyCommentOnPost($postType,$postID,$parentID,$sympathyType);

    $commentID = $comment->id;
    $sympathyComment = new \Botble\Life\Models\SympathyComment;
    $sympathyComment->post_type = $postType;
    $sympathyComment->post_id = $postID;
    $sympathyComment->comment_id = $commentID;
    $sympathyComment->parent_id = $parentID;
    $sympathyComment->sympathy = $sympathyType;
    $sympathyComment->member_id = auth()->guard('member')->user()->id;
    $sympathyComment->save();
}

function cancelSympathyCommentOnPost($postType="garden",$postID,$sympathyType = "like"){


    $commentID = 0;
    $sympathyCommentObj = \Botble\Life\Models\SympathyComment::where('member_id', auth()->guard('member')->user()->id)->where('post_type',$postType)->where('post_id',$postID)->where('sympathy',$sympathyType)->whereNull('parent_id')->get()->pluck('comment_id');



    $sympathyComment = $sympathyCommentObj->toArray();
    if($sympathyComment){
        $commentID = $sympathyComment[0];
    }



    if($commentID > 0){
        if($postType=="garden"){
            \Botble\Garden\Models\CommentsGarden::where('id', $commentID)->delete();
            \Botble\Garden\Models\CommentsGarden::where('parents_id', $commentID)->delete();
        }
        if($postType=="flea-market"){
            \Botble\Life\Models\FlareComments::where('id', $commentID)->delete();
            \Botble\Life\Models\FlareComments::where('parents_id', $commentID)->delete();
        }
        if($postType=="open-space"){
            \Botble\Life\Models\FlareComments::where('id', $commentID)->delete();
            \Botble\Life\Models\FlareComments::where('parents_id', $commentID)->delete();
        }
        if($postType=="part-time-job"){
            \Botble\Life\Models\Jobs\JobsComments::where('id', $commentID)->delete();
            \Botble\Life\Models\Jobs\JobsComments::where('parents_id', $commentID)->delete();
        }
        if($postType=="shelter"){
            \Botble\Life\Models\Shelter\ShelterComments::where('id', $commentID)->delete();
            \Botble\Life\Models\Shelter\ShelterComments::where('parents_id', $commentID)->delete();
        }
        if($postType=="advertisement"){
            \Botble\Life\Models\Ads\AdsComments::where('id', $commentID)->delete();
            \Botble\Life\Models\Ads\AdsComments::where('parents_id', $commentID)->delete();
        }
        if($postType=="genealogy"){
            \Botble\Campus\Models\Genealogy\GenealogyComments::where('id', $commentID)->delete();
            \Botble\Campus\Models\Genealogy\GenealogyComments::where('parents_id', $commentID)->delete();
        }
        if($postType=="old-genealogy"){
            \Botble\Campus\Models\OldGenealogy\OldGenealogyComments::where('id', $commentID)->delete();
            \Botble\Campus\Models\OldGenealogy\OldGenealogyComments::where('parents_id', $commentID)->delete();
        }
        if($postType=="contents"){
            \Botble\Contents\Models\CommentsContents::where('id', $commentID)->delete();
            \Botble\Contents\Models\CommentsContents::where('parents_id', $commentID)->delete();
        }

        if($postType=="events"){
            \Botble\Contents\Models\Comments::where('id', $commentID)->delete();
            \Botble\Contents\Models\Comments::where('parents_id', $commentID)->delete();
        }

        \Botble\Life\Models\SympathyComment::where('comment_id', $commentID)->where('post_type', $postType)->delete();
    }

}


function cancelSympathyCommentOnComment($postType="garden",$parentCommentID,$sympathyType = "like"){


    $commentID = 0;
    $sympathyCommentObj = \Botble\Life\Models\SympathyComment::where('member_id', auth()->guard('member')->user()->id)
    ->where('post_type',$postType)
    ->where('parent_id',$parentCommentID)
    ->where('sympathy',$sympathyType)
    ->get()->pluck('comment_id');



    $sympathyComment = $sympathyCommentObj->toArray();
    //dd($sympathyComment);
    if($sympathyComment){
        $commentID = $sympathyComment[0];
    }

    if($commentID > 0){
        if($postType=="garden"){
            \Botble\Garden\Models\CommentsGarden::where('id', $commentID)->delete();
        }
        if($postType=="flea-market"){
            \Botble\Life\Models\FlareComments::where('id', $commentID)->delete();
        }
        if($postType=="open-space"){
            \Botble\Life\Models\FlareComments::where('id', $commentID)->delete();
        }
        if($postType=="part-time-job"){
            \Botble\Life\Models\Jobs\JobsComments::where('id', $commentID)->delete();
        }
        if($postType=="shelter"){
            \Botble\Life\Models\Shelter\ShelterComments::where('id', $commentID)->delete();
        }
        if($postType=="advertisement"){
            \Botble\Life\Models\Ads\AdsComments::where('id', $commentID)->delete();
        }
        if($postType=="genealogy"){
            \Botble\Campus\Models\Genealogy\GenealogyComments::where('id', $commentID)->delete();
        }
        if($postType=="old-genealogy"){
            \Botble\Campus\Models\OldGenealogy\OldGenealogyComments::where('id', $commentID)->delete();
        }
        if($postType=="contents"){
            \Botble\Contents\Models\CommentsContents::where('id', $commentID)->delete();
        }
        \Botble\Life\Models\SympathyComment::where('comment_id', $commentID)->where('post_type', $postType)->delete();
    }

}


function checkCommentFromSympathy($commentID){

    $routeName = Route::currentRouteName();
    $postType="garden";
    if($routeName=="life.flare_market_details"){
        $postType="flare";
    }
    if($routeName=="life.open_space_details"){
        $postType="open-space";
    }
    if($routeName=="life.part_time_jobs_details"){
        $postType="part-time-job";
    }
    if($routeName=="life.shelter_list_details"){
        $postType="shelter";
    }
    if($routeName=="life.advertisements_details"){
        $postType="advertisement";
    }
    if($routeName=="campus.genealogy_details"){
        $postType="genealogy";
    }
    if($routeName=="campus.old.genealogy.details"){
        $postType="old-genealogy";
    }
    if($routeName=="contents.details"){
        $postType="contents";
    }



    //dd($routeName);

    $reasonPrefix = "";
    $row =  \Botble\Life\Models\SympathyComment::where('comment_id', $commentID)->where("post_type",$postType)->first();
    if($row){
        if($row->sympathy == "like"){
            $reasonPrefix = "공감: ";
        }
        else{
            $reasonPrefix = "비공감: ";
        }
    }
    return $reasonPrefix ;
}


function getDiskLikeToMember($post_id,$member_id){
    $routeName = Route::currentRouteName();
    if($routeName=="life.flare_market_details"){
        $row =  \Botble\Life\Models\SympathyFlea::where('flare_market_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }
    if($routeName=="life.open_space_details"){
        $row =  Botble\Life\Models\OpenSpace\SympathyOpenSpace::where('open_space_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }
    if($routeName=="life.part_time_jobs_details"){
        $row =  \Botble\Life\Models\Jobs\SympathyJobsPartTime::where('jobs_part_time_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }
    if($routeName=="life.shelter_list_details"){
        $row =  \Botble\Life\Models\Shelter\SympathyShelter::where('shelter_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }
    if($routeName=="life.advertisements_details"){
        $row =  \Botble\Life\Models\Ads\SympathAds::where('ads_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }

    if($routeName=="campus.genealogy_details"){
        $row =  \Botble\Campus\Models\Genealogy\SympathGenealogy::where('genealogy_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }
    // if($routeName=="campus.old.genealogy.details"){
    //     $row =  \Botble\Campus\Models\OldGenealogy\SympathOldGenealogyComments::where('comments_id', $comment_id)
    //     ->where('member_id',$member_id)->where('is_dislike',1)->exists();
    //     return $row;
    // }
    if($routeName=="contents.details"){
        $row =  \Botble\Contents\Models\SympathyContents::where('contents_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }

    if($routeName=="event.details"){
        $row =  \Botble\Events\Models\SympathyEvents::where('events_id', $post_id)
            ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }


    $row =  \Botble\Garden\Models\SympathyGarden::where('gardens_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
}

function getLikeToMember($post_id,$member_id){
    $routeName = Route::currentRouteName();
    if($routeName=="life.flare_market_details"){
        $row =  \Botble\Life\Models\SympathyFlea::where('flare_market_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }
    if($routeName=="life.open_space_details"){
        $row =  \Botble\Life\Models\OpenSpace\SympathyOpenSpace::where('open_space_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }
    if($routeName=="life.part_time_jobs_details"){
        $row =  \Botble\Life\Models\Jobs\SympathyJobsPartTime::where('jobs_part_time_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }
    if($routeName=="life.shelter_list_details"){
        $row =  \Botble\Life\Models\Shelter\SympathyShelter::where('shelter_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }
    if($routeName=="life.advertisements_details"){
        $row =  \Botble\Life\Models\Ads\SympathAds::where('ads_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }

    if($routeName=="campus.genealogy_details"){
        $row =  \Botble\Campus\Models\Genealogy\SympathGenealogy::where('genealogy_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }
    // if($routeName=="campus.old.genealogy.details"){
    //     $row =  \Botble\Campus\Models\OldGenealogy\SympathOldGenealogyComments::where('comments_id', $comment_id)
    //     ->where('member_id',$member_id)->where('is_dislike',1)->exists();
    //     return $row;
    // }
    if($routeName=="contents.details"){
        $row =  \Botble\Contents\Models\SympathyContents::where('contents_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }

    if($routeName=="event.details"){
        $row =  \Botble\Events\Models\SympathyEvents::where('events_id', $post_id)
            ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }

    $row =  \Botble\Garden\Models\SympathyGarden::where('gardens_id', $post_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
}


function getDislikeCommentToMember($comment_id,$member_id){
    $routeName = Route::currentRouteName();
    //$postType="garden";
    if($routeName=="life.flare_market_details"){
        $row =  \Botble\Life\Models\SympathFlareComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }
    if($routeName=="life.open_space_details"){
        $row =  \Botble\Life\Models\OpenSpace\SympathOpenSpaceComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }
    if($routeName=="life.part_time_jobs_details"){
        $row =  \Botble\Life\Models\Jobs\SympathJobsComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }
    if($routeName=="life.shelter_list_details"){
        $row =  \Botble\Life\Models\Shelter\SympathShelterComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }
    if($routeName=="life.advertisements_details"){
        $row =  \Botble\Life\Models\Ads\SympathAdsComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }

    if($routeName=="campus.genealogy_details"){
        $row =  \Botble\Campus\Models\Genealogy\SympathGenealogyComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }
    if($routeName=="campus.old.genealogy.details"){
        $row =  \Botble\Campus\Models\OldGenealogy\SympathOldGenealogyComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }
    if($routeName=="contents.details"){
        $row =  \Botble\Contents\Models\SympathyCommentsContents::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
    }


    $row =  \Botble\Garden\Models\SympathGardenComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',1)->exists();
        return $row;
}

function getLikeCommentToMember($comment_id,$member_id){

    $routeName = Route::currentRouteName();
    //$postType="garden";
    if($routeName=="life.flare_market_details"){
        $row =  \Botble\Life\Models\SympathFlareComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }
    if($routeName=="life.open_space_details"){
        $row =  \Botble\Life\Models\OpenSpace\SympathOpenSpaceComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }
    if($routeName=="life.part_time_jobs_details"){
        $row =  \Botble\Life\Models\Jobs\SympathJobsComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }
    if($routeName=="life.shelter_list_details"){
        $row =  \Botble\Life\Models\Shelter\SympathShelterComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }
    if($routeName=="life.advertisements_details"){
        $row =  \Botble\Life\Models\Ads\SympathAdsComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }

    if($routeName=="campus.genealogy_details"){
        $row =  \Botble\Campus\Models\Genealogy\SympathGenealogyComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }
    if($routeName=="campus.old.genealogy.details"){
        $row =  \Botble\Campus\Models\OldGenealogy\SympathOldGenealogyComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }
    if($routeName=="contents.details"){
        $row =  \Botble\Contents\Models\SympathyCommentsContents::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
    }

    $row =  \Botble\Garden\Models\SympathGardenComments::where('comments_id', $comment_id)
        ->where('member_id',$member_id)->where('is_dislike',0)->exists();
        return $row;
}
