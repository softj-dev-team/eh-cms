<?php

namespace Botble\Garden\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Garden\Repositories\Interfaces\CategoriesGardenInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class CategoriesGardenTable extends TableAbstract
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
     * @param CategoriesGardenInterface $categoriesGardenRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, CategoriesGardenInterface $categoriesGardenRepository)
    {
        $this->repository = $categoriesGardenRepository;
        $this->setOption('id', 'table-plugins-categories-garden');
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
            ->editColumn('name', function ($item) {
                return anchor_link(route('garden.categories.edit', $item->id), $item->name);
            })
            ->editColumn('level_access', function ($item) {
                return  $item->level_access;
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

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, CATEGORIES_GARDEN_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('garden.categories.edit', 'garden.categories.delete', $item);
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
            'categories_gardens.id',
            'categories_gardens.name',
            'categories_gardens.level_access',
            'categories_gardens.created_at',
            'categories_gardens.status',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, CATEGORIES_GARDEN_MODULE_SCREEN_NAME));
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
                'name' => 'categories_gardens.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name' => [
                'name' => 'categories_gardens.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'level_access' => [
                'name' => 'categories_gardens.level_access',
                'title' => "액세스 레벨",
                'class' => 'text-left',
            ],
            'created_at' => [
                'name' => 'categories_gardens.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'name' => 'categories_gardens.status',
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
                'link' => route('garden.categories.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, CATEGORIES_GARDEN_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('garden.categories.delete.many'),
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
            'categories_gardens.name' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'categories_gardens.level_access' => [
                'title'    => '액세스 레벨',
                'type'     => 'number',
                'validate' => 'required|max:120',
                'callback' => 'getLevelAccess',
            ],
            'categories_gardens.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'categories_gardens.created_at' => [
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
        return $this->repository->pluck('categories_gardens.name', 'categories_gardens.id');
    }
    public function getLevelAccess()
    {
        return $this->repository->pluck('categories_gardens.level_access', 'categories_gardens.id');
    }
}
