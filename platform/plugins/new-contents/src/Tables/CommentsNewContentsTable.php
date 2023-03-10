<?php

namespace Botble\NewContents\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\NewContents\Repositories\Interfaces\CommentsNewContentsInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class CommentsNewContentsTable extends TableAbstract
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
     * @param CommentsNewContentsInterface $commentsNewContentsRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, CommentsNewContentsInterface $commentsNewContentsRepository)
    {
        $this->repository = $commentsNewContentsRepository;
        $this->setOption('id', 'table-plugins-comments_new_contents');
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
            ->editColumn('content', function ($item) {
               return $item->content;
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

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, COMMENTS_NEW_CONTENTS_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) use($id) {
                return table_actions('', 'new_contents.comments.delete', $item);
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
        $query = $model->where('new_contents_id', $id)->select([
            'comments_new_contents.id',
            'comments_new_contents.content',
            'comments_new_contents.created_at',
            'comments_new_contents.status',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, COMMENTS_NEW_CONTENTS_MODULE_SCREEN_NAME));
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
                'name' => 'comments_new_contents.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'content' => [
                'name' => 'comments_new_contents.content',
                'title' => 'Content',
                'class' => 'text-left',
            ],
            'created_at' => [
                'name' => 'comments_new_contents.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'name' => 'comments_new_contents.status',
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
        $id = $this->getOption('id');

        $buttons = [
            'create' => [
                'link' => route('new_contents.comments.create',['id'=>$id]),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, COMMENTS_NEW_CONTENTS_MODULE_SCREEN_NAME);
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
            'href'       => route('new_contents.comments.delete.many',['id'=>$id]),
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
            'comments_new_contents.content' => [
                'title'    => 'Contents',
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'comments_new_contents.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'comments_new_contents.created_at' => [
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
        return $this->repository->pluck('comments_new_contents.content', 'comments_new_contents.id');
    }
}
