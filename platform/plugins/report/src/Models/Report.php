<?php

namespace Botble\Report\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campus\Models\Evaluation\CommentsEvaluation;
use Botble\Campus\Models\Evaluation\Evaluation;
use Botble\Campus\Models\Genealogy\Genealogy;
use Botble\Campus\Models\OldGenealogy\OldGenealogy;
use Botble\Campus\Models\StudyRoom\StudyRoom;
use Botble\Contents\Models\CommentsContents;
use Botble\Contents\Models\Contents;
use Botble\Events\Models\Comments;
use Botble\Events\Models\CommentsEventsCmt;
use Botble\Events\Models\Events;
use Botble\Events\Models\EventsCmt;
use Botble\Garden\Models\CommentsGarden;
use Botble\Garden\Models\Garden;
use Botble\Life\Models\Ads\Ads;
use Botble\Life\Models\Ads\AdsComments;
use Botble\Life\Models\Flare;
use Botble\Life\Models\FlareComments;
use Botble\Life\Models\Jobs\JobsComments;
use Botble\Life\Models\Jobs\JobsPartTime;
use Botble\Life\Models\OpenSpace\OpenSpace;
use Botble\Life\Models\OpenSpace\OpenSpaceComments;
use Botble\Life\Models\Shelter\Shelter;
use Botble\Life\Models\Shelter\ShelterComments;
use Botble\Member\Models\Member;
use Eloquent;

/**
 * Botble\Report\Models\Report
 *
 * @mixin \Eloquent
 */
class Report extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reports';

    /**
     * @var array
     */
    protected $fillable = [
        'status',
        'reason_option',
        'reason',
        'type_report',
        'type_post',
        'link',
        'id_post',
        'member_id',
        'person_report_id',
        'reported_id',
    ];

    /**
     * @var string
     */
    protected $screen = REPORT_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function members() {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function getComment($model, $id_item) {
        switch ($model) {
            case 1 : //Event
                return Comments::where('id', $id_item)->first() ?? null;
                break;
            case 2 : // Event cmt
                return CommentsEventsCmt::where('id', $id_item)->first();
                break;
            case 3 : // Event cmt
                return CommentsContents::where('id', $id_item)->first();
                break;
            case 4 : // OpenSpace
                return OpenSpaceComments::where('id', $id_item)->first();
                break;
            case 5 : // FleaMarket
                return FlareComments::where('id', $id_item)->first();
                break;
            case 6 : // Jobs-PartTime
                return JobsComments::where('id', $id_item)->first();
                break;
            case 7 : //Shelter
                return ShelterComments::where('id', $id_item)->first();
                break;
            case 8 : //Advertisements
                return AdsComments::where('id', $id_item)->first();
                break;
            case 9 : //Comment Garden
                return CommentsGarden::where('id', $id_item)->first();
                break;
            case 10 : //Comment Evaluation
                return CommentsEvaluation::where('id', $id_item)->first();
                break;
            default:
                # code...
                break;
        }
    }

    public function getPost($model, $id_item) {
        switch ($model) {
            case 1 :
                return Events::where('id', $id_item)->where('status', '!=', 'draft')->first();
                break;
            case 2 : // Event cmt
                return EventsCmt::where('id', $id_item)->where('status', '!=', 'draft')->first();
                break;
            case 3 : // Contents
                return Contents::where('id', $id_item)->where('status', '!=', 'draft')->first();
                break;
            case 4 : // OpenSpace
                return OpenSpace::where('id', $id_item)->where('status', '!=', 'draft')->first();
                break;
            case 5 : // Flea Market
                return Flare::where('id', $id_item)->where('status', '!=', 'draft')->first();
                break;
            case 6 : // Jobs PartTime
                return JobsPartTime::where('id', $id_item)->where('status', '!=', 'draft')->first();
                break;
            case 7 : // Shelter
                return Shelter::where('id', $id_item)->where('status', '!=', 'draft')->first();
                break;
            case 8 : // Advertisements
                return Ads::where('id', $id_item)->where('status', '!=', 'draft')->first();
                break;
            case 9 : // Garden
                return Garden::where('id', $id_item)->where('status', '!=', 'draft')->first();
                break;
            case 10 : // StudyRoom
                return StudyRoom::where('id', $id_item)->where('status', '!=', 'draft')->first();
                break;
            case 11 : // StudyRoom
                return OldGenealogy::where('id', $id_item)->where('status', '!=', 'draft')->first();
                break;
            case 12 : // StudyRoom
                return Genealogy::where('id', $id_item)->where('status', '!=', 'draft')->first();
                break;
            case 13 : // Evaluation
                return Evaluation::where('id', $id_item)->where('status', '!=', 'draft')->first();
                break;


            default:
                # code...
                break;
        }
    }

    public function getSource() {
        switch ($this->type_report) {
            case 1:
                return $this->getPost($this->type_post , $this->id_post);
                break;
            case 2:
                return $this->getComment($this->type_post , $this->id_post);
                break;
        }
    }
}
