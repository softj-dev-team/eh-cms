<?php

namespace Botble\MasterRoom\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\MasterRoom\Repositories\Interfaces\CategoriesMasterRoomInterface;
use Botble\MasterRoom\Repositories\Interfaces\MasterRoomInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class MasterRoomTable extends TableAbstract
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
     * @param MasterRoomInterface $masterRoomRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, MasterRoomInterface $masterRoomRepository, CategoriesMasterRoomInterface $categoriesMasterRoomRepository)
    {
        $this->repository = $masterRoomRepository;
        $this->categoriesMasterRoomRepository = $categoriesMasterRoomRepository;
        $this->setOption('id', 'table-plugins-master_room');
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
                return anchor_link(route('master_room.edit', $item->id), $item->title);
            })
            ->editColumn('categories', function ($item) {
                return $item->categories->name;
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('published', function ($item) {
                return date_from_database($item->published, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->editColumn('comments', function ($item) {
                return view('plugins.master-room::elements.tables.actions.showComments')->with(['id' => $item->id, 'count' => $item->comments->count()])->render();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, MASTER_ROOM_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('master_room.edit', 'master_room.delete', $item);
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
            'master_rooms.id',
            'master_rooms.title',
//            'master_rooms.enrollment_limit',
            'master_rooms.categories_master_rooms_id',
            'master_rooms.published',
            'master_rooms.status',
        ])->withCount('comments');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, MASTER_ROOM_MODULE_SCREEN_NAME));
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
                'name' => 'master_rooms.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'name' => 'master_rooms.title',
                'title' => trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'categories' => [
                'name' => 'master_rooms.categories_master_rooms_id',
                'title' => __('event.categories'),
                'class' => 'text-left',
            ],
//            'enrollment_limit' => [
//                'name' => 'master_rooms.enrollment_limit',
//                'title' => __('enrollment_limit'),
//                'width' => '200px',
//            ],
            'comments' => [
                'name' => 'comments_count',
                'title' => __('comment'),
                'width' => '200px',
            ],
            'published' => [
                'name' => 'master_rooms.published',
                'title' => __('published_at'),
                'width' => '100px',
            ],
            'status' => [
                'name' => 'master_rooms.status',
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
            'categories' => [
                'link' =>route('master_room.categories.list'),
                'text' => view('plugins.master-room::elements.tables.actions.category')->render(),
            ],
            'create' => [
                'link' => route('master_room.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, MASTER_ROOM_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href' => route('master_room.delete.many'),
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
            'master_rooms.name' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'master_rooms.categories_master_rooms_id' => [
                'title'    => __('event.event_comments.category'),
                'type'     => 'select',
                'validate' => 'required|max:120',
                'callback' => 'getAllCategory',
            ],
            'master_rooms.status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'master_rooms.published' => [
                'title' => __('published_at'),
                'type' => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return $this->repository->pluck('master_rooms.name', 'master_rooms.id');
    }
    public function getAllCategory()
    {
        return $this->categoriesMasterRoomRepository->pluck('categories_master_rooms.name', 'categories_master_rooms.id');
    }
}
