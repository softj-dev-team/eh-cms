<?php

namespace Botble\MasterRoom\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\MasterRoom\Repositories\Interfaces\AddressMasterRoomInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class AddressMasterRoomTable extends TableAbstract
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
     * @param AddressMasterRoomInterface $addressMasterRoomRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, AddressMasterRoomInterface $addressMasterRoomRepository)
    {
        $this->repository = $addressMasterRoomRepository;
        $this->setOption('id', 'table-plugins-address_master_room');
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
            ->editColumn('address', function ($item) {
                return anchor_link(route('master_room.address.edit', $item->id), $item->address);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, ADDRESS_MASTER_ROOM_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('master_room.address.edit', 'master_room.address.delete', $item);
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
            'address_master_rooms.id',
            'address_master_rooms.address',
            'address_master_rooms.created_at',
            'address_master_rooms.status',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, ADDRESS_MASTER_ROOM_MODULE_SCREEN_NAME));
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
                'name' => 'address_master_rooms.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'address' => [
                'name' => 'address_master_rooms.address',
                'title' => '이름',
                'class' => 'text-left',
            ],
            'created_at' => [
                'name' => 'address_master_rooms.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'name' => 'address_master_rooms.status',
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
                'link' => route('master_room.address.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, ADDRESS_MASTER_ROOM_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href' => route('master_room.address.delete.many'),
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
            'address_master_rooms.name' => [
                'title' => '이름',
                'type' => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getAddress',
            ],
            'address_master_rooms.status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'address_master_rooms.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getAddess()
    {
        return $this->repository->pluck('address_master_rooms.address', 'address_master_rooms.id');
    }
}
