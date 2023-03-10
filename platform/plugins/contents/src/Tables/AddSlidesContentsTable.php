<?php

namespace Botble\Contents\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Contents\Models\Contents;
use Botble\Contents\Repositories\Interfaces\CategoriesContentsInterface;
use Botble\Contents\Repositories\Interfaces\ContentsInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class AddSlidesContentsTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    protected $filterTemplate = 'plugins.contents::elements.tables.filter';

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
        $this->setOption('id', 'table-plugins-slides-contents-create');
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
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, CONTENTS_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                // return table_actions_slides_contents($this->getBulkChanges(),get_class($this), $this->bulkChangeUrl);
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
            'contents.enrollment_limit',
            'contents.categories_contents_id',
            'contents.slide_no',
            'contents.status',
        ])->where('is_slides', Contents::IS_NOT_SLIDE)->orderBy('categories_contents_id', 'DESC');

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
            'enrollment_limit' => [
                'name' => 'contents.enrollment_limit',
                'title' => __('new_contents.enrollment_limit'),
                'width' => '200px',
            ],
            'status' => [
                'name' => 'contents.status',
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
        $buttons = [];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, CONTENTS_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        return $actions;
    }

    /**
     * @return mixed
     */
    public function getBulkChanges(): array
    {
        return [
            'slide_no' => [
                'title'    => '슬라이드 번호.',
                'type'     => 'number',
                'validate' => 'required|max:12',
                'callback' => 'getSlideNo',
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

    public function applyFilterCondition($query, string $key, string $operator, ?string $value)
    {

        switch ($key) {
            case "contents.title" :
                $query = $query->where($key, $operator, '%'.$value.'%');
                break;
            default:
                $query = $query->where($key, $operator, '%'.$value.'%');
        }

        return $query;
    }

    public function getFilters(): array
    {
        return [
            'contents.title' => [
                'title'    => trans('core/base::tables.title'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getTitle',
            ],
        ];
    }

    public function getSlideNo()
    {
        return $this->repository->pluck('contents.slide_no', 'contents.id');
    }

}
