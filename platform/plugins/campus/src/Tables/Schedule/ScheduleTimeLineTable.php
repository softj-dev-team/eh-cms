<?php

namespace Botble\Campus\Tables\Schedule;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campus\Repositories\Interfaces\CampusInterface;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleInterface;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleTimeLineInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class ScheduleTimeLineTable extends TableAbstract
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
     * @param ScheduleInterface $scheduleRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ScheduleTimeLineInterface $scheduleTimeLineRepository)
    {
        $this->repository = $scheduleTimeLineRepository;
        $this->setOption('id', 'table-plugins-campus-schedule-timeline');
        parent::__construct($table, $urlGenerator);
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     * @since 2.1
     */
    public function ajax()
    {

        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('title', function ($item) {
                return anchor_link(route('campus.schedule.timeline.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('day', function ($item) {
                return ucfirst( $item->day );
            })
//            ->editColumn('end_day', function ($item) {
//                return ucfirst( $item->end_day );
//            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, SCHEDULE_TIMELINE_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('campus.schedule.timeline.edit', 'campus.schedule.timeline.delete', $item);
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
    public function query()
    {
        $id =$this->getOption('id');
        $model = $this->repository->getModel();
        $query = $model->where('schedule_id',$id)->select([
            'schedule_timeline.id',
            'schedule_timeline.title',
            'schedule_timeline.day',
//            'schedule_timeline.end_day',
            'schedule_timeline.status',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, SCHEDULE_TIMELINE_MODULE_SCREEN_NAME));
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id' => [
                'name' => 'schedule_timeline.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'name' => 'schedule_timeline.title',
                'title' => __('master_room.title'),
                'class' => 'text-left',
            ],
            'day' => [
                'name' => '종료일',
                'title' => '시작일',
                'width' => '100px',
            ],
//            'end_day' => [
//                'name' => 'end_day',
//                'title' => '종료일',
//                'width' => '100px',
//            ],
            'status' => [
                'name' => 'status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     * @throws \Throwable
     */
    public function buttons()
    {
        $id =$this->getOption('id');
        $buttons = [
            'create' => [
                'link' => route('campus.schedule.timeline.create',['id'=>$id]),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, SCHEDULE_TIMELINE_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $id =$this->getOption('id');
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('campus.schedule.timeline.delete.many',['id'=>$id]),
            'data_class' => get_class($this),
        ]);

        return $actions;
    }

    /**
     * @return mixed
     */
    public function getBulkChanges(): array
    {
        return [
            'schedule_timeline.title' => [
                'title'    => __('master_room.title'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getTitle',
            ],
            'schedule_timeline.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'schedule_timeline.day' => [
                'title' => 'Day',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getTitle()
    {
        return $this->repository->pluck('schedule_timeline.title', 'schedule_timeline.id');
    }
}
