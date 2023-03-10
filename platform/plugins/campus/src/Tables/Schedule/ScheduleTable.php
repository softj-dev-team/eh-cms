<?php

namespace Botble\Campus\Tables\Schedule;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campus\Repositories\Interfaces\CampusInterface;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class ScheduleTable extends TableAbstract
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
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ScheduleInterface $scheduleRepository)
    {
        $this->repository = $scheduleRepository;
        $this->setOption('id', 'table-plugins-campus-schedule');
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
            ->editColumn('name', function ($item) {
                return anchor_link(route('campus.schedule.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('timeline', function ($item) {
                return view('plugins.campus::elements.tables.actions.timeline')->with(['id' =>$item->id ,'count'=> count($item->timeline),'route'=>route('campus.schedule.timeline.list',['id'=>$item->id])])->render();
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, SCHEDULE_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('campus.schedule.edit', 'campus.schedule.delete', $item);
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
        $model = $this->repository->getModel();
        $query = $model->select([
            'schedule.id',
            'schedule.id_login',
            'schedule.name',
            'schedule.created_at',
            'schedule.status',
        ])->withCount('timeline');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, SCHEDULE_MODULE_SCREEN_NAME));
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
                'name' => 'schedule.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'id_login' => [
                'name' => 'id_login',
                'title' => __('id_login'),
                'class' => 'text-left',
            ],
            'name' => [
                'name' => 'schedule.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'timeline' => [
                'name' => 'timeline_count',
                'title' => '시간표',
                'width' => '200px',
            ],
            'created_at' => [
                'name' => 'created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
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
        $buttons = [
            'filter' => [
                'link' => route('campus.schedule.filter.list'),
                'text' => view('plugins.campus::elements.tables.actions.category',['name'=>'일정 필터'])->render(),
            ],
            'time' => [
                'link' => route('campus.schedule.time.list'),
                'text' => view('plugins.campus::elements.tables.actions.category',['name'=>'일정 시간'])->render(),
            ],
            'day' => [
                'link' => route('campus.schedule.day.list'),
                'text' => view('plugins.campus::elements.tables.actions.category',['name'=>'스케줄 요일'])->render(),
            ],
            'create' => [
                'link' => route('campus.schedule.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
            'import' => [
                'link' => route('campus.schedule.import'),
                'text' => view('core.base::elements.tables.actions.import')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, SCHEDULE_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('campus.schedule.delete.many'),
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
            'schedule.name' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'schedule.id_login' => [
                'title' => __('id_login'),
                'type' => 'text',
            ],
            'schedule.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'schedule.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return $this->repository->pluck('schedule.name', 'schedule.id');
    }
}
