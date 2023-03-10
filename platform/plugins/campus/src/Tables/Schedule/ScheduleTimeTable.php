<?php

namespace Botble\Campus\Tables\Schedule;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleTimeInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class ScheduleTimeTable extends TableAbstract
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
     * @param ScheduleTimeInterface $scheduleTimeRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ScheduleTimeInterface $scheduleTimeRepository)
    {
        $this->repository = $scheduleTimeRepository;
        $this->setOption('id', 'table-plugins-campus-schedule-time');
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
            ->editColumn('from', function ($item) {
                return anchor_link(route('campus.schedule.time.edit', $item->id), $item->from);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('to', function ($item) {
                return $item->to;
            })
            ->editColumn('unit', function ($item) {
                return $item->unit;
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, SCHEDULE_TIME_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('campus.schedule.time.edit', 'campus.schedule.time.delete', $item);
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
            'schedule_time.id',
            'schedule_time.from',
            'schedule_time.to',
            'schedule_time.unit',
            'schedule_time.status',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, SCHEDULE_TIME_MODULE_SCREEN_NAME));
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
                'name' => 'schedule_time.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'from' => [
                'name' => 'schedule_time.from',
                'title' => "From",
                'class' => 'text-left',
            ],
            'to' => [
                'name' => 'schedule_time.to',
                'title' => "To",
                'class' => 'text-left',
            ],
            'unit' => [
                'name' => 'schedule_time.unit',
                'title' => "Unit",
                'class' => 'text-left',
            ],
            'status' => [
                'name' => 'campuses.status',
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
            'create' => [
                'link' => route('campus.schedule.time.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, SCHEDULE_TIME_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('campus.schedule.time.delete.many'),
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
            'schedule_time.from' => [
                'title'    => "From",
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getFrom',
            ],
            'schedule_time.to' => [
                'title'    => "To",
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getTo',
            ],
            'schedule_time.unit' => [
                'title'    => "Unit",
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getUnit',
            ],
            'schedule_time.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
        ];
    }

    /**
     * @return array
     */
    public function getFrom()
    {
        return $this->repository->pluck('schedule_time.from', 'schedule_time.id');
    }

     /**
     * @return array
     */
    public function getTo()
    {
        return $this->repository->pluck('schedule_time.to', 'schedule_time.id');
    }

     /**
     * @return array
     */
    public function getUnit()
    {
        return $this->repository->pluck('schedule_time.unit', 'schedule_time.id');
    }
}
