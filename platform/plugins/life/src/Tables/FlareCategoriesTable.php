<?php

namespace Botble\Life\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Life\Repositories\Interfaces\FlareCategoriesInterface;
use Botble\Life\Repositories\Interfaces\FlareInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class FlareCategoriesTable extends TableAbstract
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
     * @param FlareInterface $flareRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, FlareCategoriesInterface $flareCategoriesRepository)
    {
        $this->repository = $flareCategoriesRepository;
        $this->flareCategoriesRepository = $flareCategoriesRepository;
        $this->setOption('id', 'table-plugins-flare-categories');
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
               return view('plugins.life::elements.categories.show',['name'=>$item->name,'background'=>$item->background,'color'=>$item->color,'id'=>$item->id ,'route'=>route('life.flare.categories.edit',['id'=>$item->id])])->render();
                //return anchor_link(route('life.flare.categories.edit', $item->id), $item->name);
            })
            ->editColumn('parent_name', function ($item) {
                return $item->parent_id == 1 ? "Type 1" : "Type 2";
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

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, LIFE_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('life.flare.categories.edit', 'life.flare.categories.delete', $item);
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
            'categories_market.id',
            'categories_market.name',
            'categories_market.created_at',
            'categories_market.status',
            'categories_market.parent_id',
            'categories_market.background',
            'categories_market.color',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, FLARE_CATEGORIES_MODULE_SCREEN_NAME));
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
                'name' => 'categories_market.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name' => [
                'name' => 'categories_market.name',
                'title' =>trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'parent_name' => [
                'name' => 'categories_market.parent_id',
                'title' =>'유형',
                'class' => 'text-left',
            ],
            'created_at' => [
                'name' => 'categories_market.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'name' => 'categories_market.status',
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
                'link' => route('life.flare.categories.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, FLARE_CATEGORIES_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('life.flare.categories.delete.many'),
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
            'categories_market.name' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'categories_market.parent_id' => [
                'title'    =>trans('core/base::tables.category'),
                'type'     => 'select',
                'choices'  => $this->getParents(),
                'validate' => 'required'
            ],
            'categories_market.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'categories_market.created_at' => [
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
        return $this->repository->pluck('categories_market.name', 'categories_market.id');
    }
    public function getParents()
    {
       return ['1'=>'유형 1','2'=>'유형 2'];
    }
}
