<?php

namespace Botble\Campus\Tables\StudyRoom;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campus\Repositories\Interfaces\StudyRoom\StudyRoomInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class StudyRoomTable extends TableAbstract
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
     * @param StudyRoomInterface $studyRoomRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, StudyRoomInterface $studyRoomRepository)
    {
        $this->repository = $studyRoomRepository;
        $this->setOption('id', 'table-plugins-study-room');
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
                return anchor_link(route('campus.study_room.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('comments', function ($item) {
                return view('plugins.campus::elements.tables.actions.showComments')->with(['id' =>$item->id ,'count'=> count($item->comments),'route'=>route('campus.study_room.comments.list',['id'=>$item->id])])->render();
            })
            ->editColumn('published', function ($item) {
                return date_from_database($item->published, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
//                switch ($item->status) {
//                    case 'publish':
//                        return '<span class="label-success status-label">'.trans('core/base::tables.status_publish').'</span>';
//                        break;
//                    case 'pending':
//                        return '<span class="label-warning status-label">'.trans('core/base::tables.status_pending').'</span>';
//                        break;
//                    case 'draft':
//                        return '<span class="label-info status-label">'.trans('core/base::tables.status_draft').'</span>';
//                        break;
//                    default:
//                        # code...
//                        break;
//                }
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, STUDY_ROOM_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('campus.study_room.edit', 'campus.study_room.delete', $item);
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
            'study_room.id',
            'study_room.title',
            'study_room.published',
            'study_room.status',
        ])->withCount('comments');;

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, STUDY_ROOM_MODULE_SCREEN_NAME));
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
                'name' => 'study_room.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'name' => 'study_room.title',
                'title' =>trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'comments' => [
                'name' => 'comments_count',
                'title' =>trans('core/base::tables.comment'),
                'width' => '200px',
            ],
            'published' => [
                'name' => 'study_room.published',
                'title' => trans('core/base::tables.published'),
                'width' => '100px',
            ],
            'status' => [
                'name' => 'study_room.status',
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
                'link' => route('campus.study_room.categories.list'),
                'text' => view('plugins.campus::elements.tables.actions.category')->render(),
            ],
            'create' => [
                'link' => route('campus.study_room.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, STUDY_ROOM_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('campus.study_room.delete.many'),
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
            'study_room.title' => [
                'title'    => trans('core/base::tables.title'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'study_room.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  =>  ["publish" => trans('core/base::tables.status_publish'), "pending" => trans('core/base::tables.status_pending'), 'draft' => trans('core/base::tables.status_draft'),],
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'study_room.published' => [
                'title' => trans('core/base::tables.published'),
                'type'  => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return $this->repository->pluck('study_room.title', 'study_room.id');
    }
}
