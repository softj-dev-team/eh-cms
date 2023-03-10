<?php

namespace Botble\Garden\Tables\Egarden;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Garden\Repositories\Interfaces\Egarden\EgardenInterface;
use Botble\Garden\Repositories\Interfaces\Egarden\RoomInterface;
use Botble\Garden\Repositories\Interfaces\GardenInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class EgardenTable extends TableAbstract
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
     * @param GardenInterface $egardenRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, EgardenInterface $egardenRepository, RoomInterface $roomRepository) {
        $this->repository = $egardenRepository;
        $this->roomRepository = $roomRepository;
        $this->setOption('id', 'table-plugins-egarden');
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
            ->editColumn('title', function ($item) {
                return anchor_link(route('garden.egarden.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('room_id', function ($item) {
                return $item->room ? anchor_link(route('garden.egarden.room.edit', $item->room->id), $item->room->name) :  'No Room' ;
//                return anchor_link(route('garden.egarden.room.edit', $item->room->id), $item->room->name ?? 'No Room');
            })
//            ->editColumn('comments', function ($item) {
//                return view('plugins.garden::elements.tables.actions.showComments')->with(['id' => $item->id, 'count' => count($item->comments), 'route' => route('garden.egarden.comments.list', ['id' => $item->id])])->render();
//            })
            ->editColumn('published', function ($item) {
                return date_from_database($item->published, config('core.base.general.date_format.date'));
            })
            ->editColumn('member_id', function ($item) {
                return $item->member ? $item->member->fullname : '';
            })
//            ->editColumn('status', function ($item) {
//                return $item->status->toHtml();
//            });
        ;

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, EGARDEN_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('garden.egarden.edit', 'garden.egarden.delete', $item);
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
            'egardens.id',
            'egardens.title',
            'egardens.room_id',
            'egardens.detail',
            'egardens.member_id',
            'egardens.published',
            'egardens.status',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, EGARDEN_MODULE_SCREEN_NAME));
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function columns() {
        return [
            'id' => [
                'name' => 'egardens.id',
                'title' => '이화원 번호',
                'width' => '100px',
            ],
            'title' => [
                'name' => 'egardens.title',
                'title' => '이화원이름',
                'class' => 'text-left',
            ],
            'room_id' => [
                'name' => 'egardens.room_id',
                'title' => '화원장',
                'class' => 'text-left',
            ],
            'detail' => [
                'name' => 'egardens.detail',
                'title' => '이화원 내용',
                'width' => '200px',
            ],
//            'comments' => [
//                'name' => 'egardens.comments',
//                'title' => trans('core/base::tables.comment'),
//                'width' => '200px',
//            ],
            'published' => [
                'name' => 'egardens.published',
                'title' => '날짜',
                'width' => '100px',
            ],
            'member_id' => [
                'name' => 'egardens.member_id',
                'title' => '화원 회원',
                'class' => 'text-left',
            ],
//            'status' => [
//                'name' => 'gardens.status',
//                'title' => trans('core/base::tables.status'),
//                'width' => '100px',
//            ],
        ];
    }

    /**
     * @return array
     * @throws \Throwable
     * @since 2.1
     * @author Sang Nguyen
     */
    public function buttons() {
        $buttons = [
            'room' => [
                'link' => route('garden.egarden.room.list'),
                'text' => view('plugins.garden::elements.tables.actions.category', ['title' => __('egarden.room')])->render(),
            ],
            'create' => [
                'link' => route('garden.egarden.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, EGARDEN_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href' => route('garden.egarden.delete.many'),
            'data_class' => get_class($this),
        ]);

        return $actions;
    }

    /**
     * @return mixed
     */
    public function getBulkChanges(): array {
        return [
            'egardens.title' => [
                'title' => trans('core/base::tables.title'),
                'type' => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getTitle',
            ],
            'egardens.room_id' => [
                'title' => __('egarden.room'),
                'type' => 'select',
                'validate' => 'required|max:120',
                'callback' => 'getAllRoom',
            ],
            'egardens.status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'egardens.published' => [
                'title' => trans('core/base::tables.published'),
                'type' => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getTitle() {
        return $this->repository->pluck('egardens.title', 'egardens.id');
    }

    public function getAllRoom() {
        return $this->roomRepository->pluck('room.name', 'room.id');
    }
}
