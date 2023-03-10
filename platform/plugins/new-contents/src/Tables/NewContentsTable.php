<?php

namespace Botble\NewContents\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\NewContents\Repositories\Interfaces\CategoriesNewContentsInterface;
use Botble\NewContents\Repositories\Interfaces\NewContentsInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class NewContentsTable extends TableAbstract
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
     * @param NewContentsInterface $newContentsRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, NewContentsInterface $newContentsRepository, CategoriesNewContentsInterface $categoriesNewContentsRepository)
    {
        $this->repository = $newContentsRepository;
        $this->categoriesNewContentsRepository = $categoriesNewContentsRepository;
        $this->setOption('id', 'table-plugins-new_contents');
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
                return anchor_link(route('new_contents.edit', $item->id), $item->title);
            })
            ->editColumn('categories', function ($item) {
                return $item->categories->name;
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('comments', function ($item) {
                return view('plugins.new-contents::elements.tables.actions.showComments')->with(['id' => $item->id, 'count' => $item->comments_count])->render();
            })
            ->editColumn('published', function ($item) {
                return date_from_database($item->published, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, NEW_CONTENTS_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('new_contents.edit', 'new_contents.delete', $item);
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
            'new_contents.id',
            'new_contents.title',
            'new_contents.categories_new_contents_id',
            'new_contents.published',
            'new_contents.status',
        ])->withCount('comments');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, NEW_CONTENTS_MODULE_SCREEN_NAME));
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
                'name' => 'new_contents.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'name' => 'new_contents.title',
                'title' => trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'categories' => [
                'name' => 'new_contents.categories_new_contents_id',
                'title' => trans('core/base::tables.category'),
                'class' => 'text-left',
            ],
            'comments' => [
                'name' => 'comments_count',
                'title' => trans('core/base::tables.comment'),
                'width' => '200px',
            ],
            'published' => [
                'name' => 'new_contents.published',
                'title' => trans('core/base::tables.published'),
                'width' => '200px',
            ],
            'status' => [
                'name' => 'new_contents.status',
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
                'link' =>route('new_contents.categories.list'),
                'text' => view('plugins.new-contents::elements.tables.actions.category')->render(),
            ],
            'create' => [
                'link' => route('new_contents.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, NEW_CONTENTS_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('new_contents.delete.many'),
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
            'new_contents.name' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'new_contents.categories_new_contents_id' => [
                'title'    =>  trans('core/base::tables.category'),
                'type'     => 'select',
                'validate' => 'required|max:120',
                'callback' => 'getAllCategory',
            ],
            'new_contents.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'new_contents.published' => [
                'title' =>  trans('core/base::tables.published'),
                'type'  => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return $this->repository->pluck('new_contents.name', 'new_contents.id');
    }
    public function getAllCategory()
    {
        return $this->categoriesNewContentsRepository->pluck('categories_new_contents.name', 'categories_new_contents.id');
    }
}
