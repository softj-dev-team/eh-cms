<?php

namespace Botble\Events\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Events\Repositories\Interfaces\EventsInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Botble\Events\Repositories\Interfaces\CategoryEventsInterface;
use Botble\Events\Repositories\Interfaces\EventsCmtInterface;

class EventsCmtTable extends TableAbstract
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
     * @param EventsCmtInterface $eventsCmtRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, EventsCmtInterface $eventsCmtRepository,CategoryEventsInterface $categoryEventsRepository)
    {
        $this->repository = $eventsCmtRepository;
        $this->categoryEventsRepository = $categoryEventsRepository;
        $this->setOption('id', 'table-plugins-events-cmt');
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
            ->editColumn('category', function ($item) {
                return $item->category_events ?   $item->category_events->name : null;
            })
            ->editColumn('title', function ($item) {
                return anchor_link(route('events.cmt.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('comments', function ($item) {
                return view('plugins.events::elements.tables.actions.showComments')->with(['id' =>$item->id ,'count'=> $item->comments->count(),'route'=>route('events.cmt.comments.list',['id'=>$item->id]) ])->render();
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, EVENTS_CMT_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('events.cmt.edit', 'events.cmt.delete', $item);
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
            'events_cmt.id',
            'events_cmt.title',
            'events_cmt.category_events_id',
            'events_cmt.status'
        ])->withCount('comments');;

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, EVENTS_CMT_MODULE_SCREEN_NAME));
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
                'name' => 'events_cmt.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'category' => [
                'name' => 'events_cmt.category_events_id',
                'title' => trans('core/base::tables.category'),
                'class' => 'text-left',
            ],
            'title' => [
                'name' => 'events_cmt.title',
                'title' =>  trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'comments' => [
                'name' => 'comments_count',
                'title' =>  trans('core/base::tables.comment'),
                'width' => '200px',
            ],
            'status' => [
                'name' => 'events_cmt.status',
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
                'link' => route('events.cmt.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ]

        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, EVENTS_CMT_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('events.cmt.delete.many'),
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
            'events_cmt.title' => [
                'title'    => trans('core/base::tables.title'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getTitle',
            ],
            'events_cmt.category_events_id' => [
                'title'    =>  trans('core/base::tables.category'),
                'type'     => 'select',
                'validate' => 'required|max:120',
                'callback' => 'getAllCategory',
            ],
            'events_cmt.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'events_cmt.published' => [
                'title' =>  trans('core/base::tables.published'),
                'type'  => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getTitle()
    {
        return $this->repository->pluck('events_cmt.title', 'events_cmt.id');
    }

    /**
     * @return array
     */
    public function getEnrollmentLimit()
    {
        return $this->repository->pluck('events_cmt.enrollment_limit', 'events_cmt.id');
    }

    /**
     * @return array
     */
    public function getAllCategory()
    {
        return $this->categoryEventsRepository->pluck('category_events.name', 'category_events.id');
    }
}
