<?php

namespace Botble\MasterRoom\Tables;

use Botble\Campus\Repositories\Interfaces\Shelter\ShelterCommentsInterface;
use Botble\Garden\Repositories\Interfaces\CommentsGardenInterface;
use Botble\Garden\Repositories\Interfaces\Egarden\CommentsEgardenInterface;
use Botble\MasterRoom\Repositories\Interfaces\CommentsMasterRoomInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class CommentsMasterRoomTable extends TableAbstract
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
     * @param CommentsMasterRoomInterface $commentsMasterRoomRepository
     */

    public function __construct(DataTables $table, UrlGenerator $urlGenerator, CommentsMasterRoomInterface $commentsMasterRoomRepository)
    {

        $this->repository = $commentsMasterRoomRepository;
        $this->setOption('id', 'table-plugins-comments-master-room');
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
        $id = $this->getOption('id');
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('contents', function ($item) {
                return $item->content;
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, COMMENTS_MASTER_ROOM_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) use($id) {
                return table_actions(null, 'master_room.comments.delete', $item);
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
        $id = $this->getOption('id');
        $model = $this->repository->getModel();
        $query = $model->where('master_rooms_id', $id)->select([
            'comments_master_rooms.id',
            'comments_master_rooms.content',
            'comments_master_rooms.created_at',
        ]);
        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, COMMENTS_EGARDEN_MODULE_SCREEN_NAME));
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
                'name' => 'comments_master_rooms.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'contents' => [
                'name' => 'comments_master_rooms.content',
                'title' => __('message.contents'),
                'width' => '200px',
            ],
            'created_at' => [
                'name' => 'comments_master_rooms.created_at',
                'title' => trans('core/base::tables.created_at'),
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
        $id = $this->getOption('id');
        $buttons = [
            'create' => [
                'link' => route('master_room.comments.create', ['id' => $id]),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, COMMENTS_MASTER_ROOM_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $id = $this->getOption('id');
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href' => route('master_room.comments.delete.many', ['id' => $id]),
            'data_class' => get_class($this),
        ]);

        return $actions;
    }

}
