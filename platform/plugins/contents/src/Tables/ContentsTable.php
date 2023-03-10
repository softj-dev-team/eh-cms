<?php

namespace Botble\Contents\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Contents\Models\Contents;
use Botble\Contents\Repositories\Interfaces\CategoriesContentsInterface;
use Botble\Contents\Repositories\Interfaces\ContentsInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class ContentsTable extends TableAbstract
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
     * @param ContentsInterface $contentsRepository
     * @param CategoriesContentsInterface $categoriesContentsRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ContentsInterface $contentsRepository, CategoriesContentsInterface $categoriesContentsRepository)
    {
        $this->repository = $contentsRepository;
        $this->categoriesContentsRepository = $categoriesContentsRepository;
        $this->setOption('id', 'table-plugins-contents');
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
                return anchor_link(route('contents.edit', $item->id), $item->title);
            })
            ->editColumn('categories', function ($item) {
                return $item->categories_contents->name;
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
            ->editColumn('main_content', function ($item) {
                return view('plugins.contents::elements.tables.actions.registerMainContentLabel')->with(
                    [
                        'isMainContentText' => $item->is_main_content ? __('contents.main_content.label.registered') :__('contents.main_content.label.unregister'),
                        'backgroundColor' => $item->is_main_content == true ? 'success' : 'danger'
                    ]
                )->render();
            })
            ->editColumn('comments', function ($item) {
                return view('plugins.contents::elements.tables.actions.showComments')->with(['id' =>$item->id ,'count'=> count($item->comments) ])->render();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, CONTENTS_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {

                $extraButton = view('plugins.contents::elements.tables.actions.registerMainContentButton')->with(
                    [
                        'id' => $item->id,
                        'type' => $item->is_main_content ? Contents::UN_REGISTER_MAIN_CONTENT : Contents::REGISTER_MAIN_CONTENT,
                        'category' => $item->categories_contents_id,
                        'isMainContent' => $item->is_main_content ? __('contents.main_content.button.unregister') : __('contents.main_content.button.registered')
                    ]
                )->render();

                return table_actions('contents.edit', 'contents.delete', $item, $extraButton);
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
            'contents.id',
            'contents.title',
//            'contents.enrollment_limit',
            'contents.categories_contents_id',
            'contents.status',
            'contents.is_main_content',
        ])->withCount('comments');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, CONTENTS_MODULE_SCREEN_NAME));
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
                'name' => 'contents.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'name' => 'contents.title',
                'title' => trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'categories' => [
                'name' => 'contents.categories_contents_id',
                'title' => trans('core/base::tables.category'),
                'class' => 'text-left',
            ],
//            'enrollment_limit' => [
//                'name' => 'contents.enrollment_limit',
//                'title' => trans('core/base::tables.enrollment_limit'),
//                'width' => '200px',
//            ],
            'comments' => [
                'name' => 'comments_count',
                'title' => trans('core/base::tables.comment'),
                'width' => '80px',
            ],
            'status' => [
                'name' => 'contents.status',
                'title' => trans('core/base::tables.status'),
                'width' => '50px',
            ],
            'main_content' => [
                'name' => 'contents.main_content',
                'title' => trans('core/base::tables.is_main_content'),
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
            'categories' => [
                'link' => route('contents.categories.list'),
                'text' => view('plugins.contents::elements.tables.actions.category')->render(),
            ],
            'create' => [
                'link' => route('contents.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, CONTENTS_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('contents.delete.many'),
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
            'contents.title' => [
                'title'    => trans('core/base::tables.title'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getTitle',
            ],
            'categories_contents_id' => [
                'title'    => trans('core/base::tables.category'),
                'type'     => 'select',
                'validate' => 'required|max:120',
                'callback' => 'getAllCategories',
            ],
            'contents.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'contents.published' => [
                'title' => trans('core/base::tables.published'),
                'type'  => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getTitle()
    {
        return $this->repository->pluck('contents.title', 'contents.id');
    }

    /**
     * @return array
     */
    public function getAllCategories()
    {
        return $this->categoriesContentsRepository->pluck('categories_contents.name', 'categories_contents.id');
    }
}
