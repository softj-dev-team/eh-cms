<?php

namespace Botble\Report\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Member\Models\Member;
use Botble\Report\Repositories\Interfaces\ReportInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class ReportTable extends TableAbstract
{
    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * TagTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param ReportInterface $reportRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ReportInterface $reportRepository) {
        $this->repository = $reportRepository;
        $this->setOption('id', 'table-plugins-report');
        parent::__construct($table, $urlGenerator);
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     * @since 2.1
     */
    public function ajax() {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('link', function ($item) {
                switch ($item->type_post) {
                    case 1 :
                        $link = route('events.edit', ['id' => $item->id_post]);
                        break;
                    case 2 : // Event cmt
                        $link = route('events.cmt.edit', ['id' => $item->id_post]);
                        break;
                    case 3 : // Contents
                        $link = route('contents.edit', ['id' => $item->id_post]);
                        break;
                    case 4 : // OpenSpace
                        $link = route('life.open.space.edit', ['id' => $item->id_post]);
                        break;
                    case 5 : // Flea Market
                        $link = route('life.flare.edit', ['id' => $item->id_post]);
                        break;
                    case 6 : // Jobs PartTime
                        $link = route('life.jobs_part_time.edit', ['id' => $item->id_post]);
                        break;
                    case 7 : // Shelter
                        $link = route('life.shelter.edit', ['id' => $item->id_post]);
                        break;
                    case 8 : // Advertisements
                        $link = route('life.advertisements.edit', ['id' => $item->id_post]);
                        break;
                    case 9 : // Garden
                        $link = route('garden.edit', ['id' => $item->id_post]);
                        break;
                    case 10 : // StudyRoom
                        $link = route('campus.study_room.edit', ['id' => $item->id_post]);
                        break;
                    case 11 : // StudyRoom
                        $link = route('campus.old.genealogy.edit', ['id' => $item->id_post]);
                        break;
                    case 12 : // StudyRoom
                        $link = route('campus.genealogy.edit', ['id' => $item->id_post]);
                        break;
                    case 13 : // Evaluation
                        $link = route('campus.evaluation.edit', ['id' => $item->id_post]);
                        break;
                    default:
                        $link = null;
                        break;
                }
                switch ($item->type_report) {
                    case '1':
                        $link_detail =  __("link_detail");
                        return "<a  href='$link' title='$link_detail' target= '_blank'>$link_detail</a>";
                        break;
                    case '2':
                        $comment = $item->getComment($item->type_post , $item->id_post);
                        return $comment ? $comment->content : "";
                        break;
                    default:
                        return null;
                }


            })

            ->editColumn('reason_option', function ($item) {
                switch ($item->reason_option) {
                    case '1':
                        return '훌리건 의심';
                        break;
                    case '2':
                        return '회원에 대한 욕설 혹은 저격';
                        break;
                    case '3':
                        return '허위사실 유포';
                        break;
                    case '4':
                        return '게시 자료의 저작권 위반';
                        break;
                    case '5':
                        return '일반인 신상정보 게시';
                        break;
                    case '6':
                        return '지나친 홍보 또는 상거래 유도';
                        break;
                    case '7':
                        return '다른 게시판에 적절한 게시글';
                        break;
                    case '8':
                        return '기타';
                        break;

                    default:
                        return '훌리건 의심';
                        break;
                }
            })
            ->editColumn('type_report', function ($item) {
                switch ($item->type_report) {
                    case '1':
                        return __('post');
                        break;
                    case '2':
                        return __('comment');
                        break;
                    default:
                        return null;
                }
            })
            ->editColumn('type_post', function ($item) {
                switch ($item->type_post) {
                    case '1':
                        return __('event.menu__title');
                        break;
                    case '2':
                        return __('event_comment');
                        break;
                    case '3':
                        return __('home.contents');
                        break;
                    case '4':
                        return __('home.open_space');
                        break;
                    case '5':
                        return __('home.flea_market');
                        break;
                    case '6':
                        return __('header.part-time_job');
                        break;
                    case '7':
                        return __('home.shelter_info');
                        break;
                    case '8':
                        return __('home.advertisements');
                        break;
                    case '9':
                        return __('garden');
                        break;
                    case '10':
                        return __('header.study_room');
                        break;
                    case '11':
                        return __('campus.old_genealogy');
                        break;
                    case '12':
                        return __('campus.genealogy');
                        break;
                    case '13':
                        return __('campus.evaluation');
                        break;
                    default:
                        # code...
                        return __('other_report');
                        break;
                }
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, REPORT_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('report.edit', 'report.delete', $item);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Get the query object to be processed by table.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     * @author Sang Nguyen
     * @since 2.1
     */
    public function query() {
        $model = $this->repository->getModel();
        $query = $model->select([
            'reports.id',
            'reports.reason_option',
            'reports.type_report',
            'reports.type_post',
            'reports.link',
            'reports.id_post',
            'reports.person_report_id',
            'reports.reported_id',
            'reports.created_at',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, REPORT_MODULE_SCREEN_NAME));
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function columns() {
        return [
            'id' => [
                'name' => 'reports.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'reason_option' => [
                'name' => 'reports.reason_option',
                'title' => __('reason_option'),
                'width' => '60px',
            ],
            'type_report' => [
                'name' => 'reports.type_report',
                'title' => __('member.report_type'),
                'width' => '20px',
            ],
            'type_post' => [
                'name' => 'reports.type_post',
                'title' => __('type_post'),
                'width' => '20px',
            ],
            'link' => [
                'name' => 'reports.link',
                'title' => '게시글/댓글',
                'width' => '20px',
            ],
            'person_report_id' => [
                'name' => 'reports.person_report_id',
                'title' => __('person_report_id'),
                'width' => '20px',
            ],
            'reported_id' => [
                'name' => 'reports.reported_id',
                'title' => __('reported_id'),
                'width' => '20px',
            ],
            'created_at' => [
                'name' => 'reports.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href' => route('report.delete.many'),
            'data_class' => get_class($this),
        ]);

        return $actions;
    }

    /**
     * @return mixed
     */
    public function getBulkChanges(): array {
        return [
            'reports.id' => [
                'title' =>  trans('core/base::tables.id'),
                'type' => 'text',
            ],
            'reports.reason_option' => [
                'title' => '신고 사유',
                'type' => 'select',
                'validate' => 'required',
                'choices' => [
                    "1" =>  '훌리건 의심',
                    "2" => '회원에 대한 욕설 혹은 저격',
                    "3" => '허위사실 유포',
                    "4" => '게시 자료의 저작권 위반',
                    "5" =>  '일반인 신상정보 게시',
                    "6" =>  '지나친 홍보 또는 상거래 유도',
                    "7" => '다른 게시판에 적절한 게시글',
                    "8" =>'기타']
            ],
            'reports.type_report' => [
                'title' => __('member.report_type'),
                'type' => 'select',
                'validate' => 'required',
                'choices' => ["1" => __('post'), "2" => __('comment')],
            ],
            'reports.type_post' => [
                'title' => '닉네임',
                'type' => 'select',
                'choices' => [
                    "1" =>  __('event.menu__title'),
                    "2" =>__('event_comment'),
                    "3" =>__('home.contents'),
                    "4" => __('home.open_space'),
                    "5" =>  __('home.flea_market'),
                    "6" =>  __('header.part-time_job'),
                    "7" => __('home.shelter_info'),
                    "8" =>__('home.advertisements'),
                    "9" =>  __('garden'),
                    "10" =>__('header.study_room'),
                    "11" => __('campus.old_genealogy'),
                    "12" => __('campus.genealogy'),
                    "13" =>  __('campus.evaluation')
                    ]
            ],
//            'reports.link' => [
//                'title' =>  '게시글/댓글',
//                'type' => 'text',
//            ],
            'reports.person_report_id' => [
                'title' => '신고 아이디',
                'type' => 'text',
            ],
            'reports.reported_id' => [
                'title' => '훌리 아이디',
                'type' => 'text',
            ],
            'reports.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],

        ];
    }

}
