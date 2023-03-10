<?php

namespace Botble\Events\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Events\Repositories\Interfaces\EventsInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Botble\Events\Repositories\Interfaces\CategoryEventsInterface;

class EventsTable extends TableAbstract
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
     * @param EventsInterface $eventsRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, EventsInterface $eventsRepository,CategoryEventsInterface $categoryEventsRepository)
    {
        $this->repository = $eventsRepository;
        $this->categoryEventsRepository = $categoryEventsRepository;
        $this->setOption('id', 'table-plugins-events');
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
                return anchor_link(route('events.edit', $item->id), $item->title);
            })
            ->editColumn('category', function ($item) {
                return $item->category_events ? $item->category_events->name : null;
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('comments', function ($item) {
                return view('plugins.events::elements.tables.actions.showComments')->with(['id' =>$item->id ,'count'=> count($item->comments) ])->render();
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, EVENTS_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('events.edit', 'events.delete', $item);
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
            'events.id',
            'events.title',
            'events.enrollment_limit',
            'events.category_events_id',
            'events.status'
        ])->withCount('comments');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, EVENTS_MODULE_SCREEN_NAME));
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
                'name' => 'events.id',
                'title' => 'ID',
                'width' => '20px',
            ],
            'title' => [
                'name' => 'events.title',
                'title' => trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'category' => [
                'name' => 'events.category_events_id',
                'title' => trans('core/base::tables.category'),
                'class' => 'text-left',
                'width' => '100px',
            ],
            'enrollment_limit' => [
                'name' => 'events.enrollment_limit',
                'title' => __('event.enrollment_limit'),
                'width' => '50px',
            ],
            'comments' => [
                'name' => 'comments_count',
                'title' => trans('core/base::tables.comment'),
                'width' => '80px',
            ],
            'status' => [
                'name' => 'events.status',
                'title' => trans('core/base::tables.status'),
                'width' => '50px',
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
            'comments' => [
                'link' => route('events.category.list'),
                'text' => view('plugins.events::elements.tables.actions.category')->render(),
            ],
            'create' => [
                'link' => route('events.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ]

        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, EVENTS_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('events.delete.many'),
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
            'events.title' => [
                'title'    => trans('core/base::tables.title'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getTitle',
            ],
            'events.category_events_id' => [
                'title'    => trans('core/base::tables.category'),
                'type'     => 'select',
                'validate' => 'required|max:120',
                'callback' => 'getAllCategory',
            ],
            'events.enrollment_limit' => [
                'title'    => __('event.enrollment_limit'),
                'type'     => 'number',
                'validate' => 'required|max:120',
                'callback' => 'getEnrollmentLimit',
            ],
            'events.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            // 'events.created_at' => [
            //     'title' => trans('core/base::tables.created_at'),
            //     'type'  => 'date',
            // ],
        ];
    }

    /**
     * @return array
     */
    public function getTitle()
    {
        return $this->repository->pluck('events.title', 'events.id');
    }

    /**
     * @return array
     */
    public function getEnrollmentLimit()
    {
        return $this->repository->pluck('events.enrollment_limit', 'events.id');
    }

    /**
     * @return array
     */
    public function getAllCategory()
    {
        return $this->categoryEventsRepository->pluck('category_events.name', 'category_events.id');
    }
}
