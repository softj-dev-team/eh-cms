<?php

namespace Botble\CampusLastday\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\CampusLastday\Repositories\Interfaces\CampusLastdayInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class CampusLastdayTable extends TableAbstract
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
     * @param CampusLastdayInterface $campusLastdayRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, CampusLastdayInterface $campusLastdayRepository)
    {
        $this->repository = $campusLastdayRepository;
        $this->setOption('id', 'table-plugins-campus_lastday');
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
                return anchor_link(route('campus_lastday.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })

        ;

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, CAMPUS_LASTDAY_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('campus_lastday.edit', 'campus_lastday.delete', $item);
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
            'campus_lastdays.id',
            'campus_lastdays.name',
            'campus_lastdays.start',
            'campus_lastdays.end',
            'campus_lastdays.year',
            'campus_lastdays.semester',
            'campus_lastdays.created_at',
            'campus_lastdays.status',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, CAMPUS_LASTDAY_MODULE_SCREEN_NAME));
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
                'name' => 'campus_lastdays.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name' => [
                'name' => 'campus_lastdays.name',
                'title' => trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name' => 'campus_lastdays.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'start' => [
                'name' => 'campus_lastdays.status',
                'title' => trans('core/base::tables.start'),
                'width' => '100px',
            ],
            'end' => [
                'name' => 'campus_lastdays.start',
                'title' => trans('core/base::tables.end'),
                'width' => '100px',
            ],
            'year' => [
                'name' => 'campus_lastdays.year',
                'title' => trans('core/base::tables.year'),
                'width' => '100px',
            ],
            'semester' => [
                'name' => 'campus_lastdays.end',
                'title' => trans('core/base::tables.semester'),
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
                'link' => route('campus_lastday.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, CAMPUS_LASTDAY_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('campus_lastday.delete.many'),
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
            'campus_lastdays.name' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'campus_lastdays.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'campus_lastdays.created_at' => [
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
        return $this->repository->pluck('campus_lastdays.name', 'campus_lastdays.id');
    }
}
