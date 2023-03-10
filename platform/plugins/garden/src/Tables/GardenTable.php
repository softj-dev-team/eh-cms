<?php

namespace Botble\Garden\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Garden\Repositories\Interfaces\CategoriesGardenInterface;
use Botble\Garden\Repositories\Interfaces\GardenInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class GardenTable extends TableAbstract
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
     * @param GardenInterface $gardenRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, GardenInterface $gardenRepository, CategoriesGardenInterface $categoriesGardenRepository)
    {
        $this->repository = $gardenRepository;
        $this->categoriesGardenRepository = $categoriesGardenRepository;
        $this->setOption('id', 'table-plugins-garden');
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
                return anchor_link(route('garden.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('categories', function ($item) {
                return $item->categories->name ?? 'No Categories';
            })
            ->editColumn('comments', function ($item) {
                return view('plugins.garden::elements.tables.actions.showComments')->with(['id' =>$item->id ,'count'=> count($item->comments),'route'=>route('garden.comments.list',['id'=>$item->id])])->render();
            })
            ->editColumn('published', function ($item) {
                return date_from_database($item->published, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, GARDEN_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('garden.edit', 'garden.delete', $item);
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
            'gardens.id',
            'gardens.title',
            'gardens.categories_gardens_id',
            'gardens.published',
            'gardens.status',
        ])->withCount('comments');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, GARDEN_MODULE_SCREEN_NAME));
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
                'name' => 'gardens.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'name' => 'gardens.title',
                'title' => trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'categories' => [
                'name' => 'gardens.categories_gardens_id',
                'title' => trans('core/base::tables.category'),
                'class' => 'text-left',
            ],
            'comments' => [
                'name' => 'comments_count',
                'title' => trans('core/base::tables.comment'),
                'width' => '80px',
            ],
            'published' => [
                'name' => 'gardens.published',
                'title' => trans('core/base::tables.published'),
                'width' => '80px',
            ],
            'status' => [
                'name' => 'gardens.status',
                'title' => trans('core/base::tables.status'),
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
                'link' => route('garden.categories.list'),
                'text' => view('plugins.garden::elements.tables.actions.category')->render(),
            ],
//            'reset_password' => [
//                'link' => route('garden.password.reset'),
//                'text' => view('plugins.garden::elements.tables.actions.category',['title'=>'비밀번호 재설정','icon'=>'fas fa-redo-alt'])->render(),
//            ],
//            'edit_password' => [
//                'link' => route('garden.password.edit'),
//                'text' => view('plugins.garden::elements.tables.actions.category',['title'=>'비밀번호 수정','icon'=>'fas fa-key'])->render(),
//            ],
            'create' => [
                'link' => route('garden.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, GARDEN_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('garden.delete.many'),
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
            'gardens.title' => [
                'title'    => __('core/base::tables.title'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getTitle',
            ],
            'gardens.categories_gardens_id' => [
                'title'    => trans('core/base::tables.category'),
                'type'     => 'select',
                'validate' => 'required|max:120',
                'callback' => 'getAllCategory',
            ],
            'gardens.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'gardens.published' => [
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
        return $this->repository->pluck('gardens.title', 'gardens.id');
    }

    public function getAllCategory()
    {
        $collection = $this->categoriesGardenRepository->pluck('categories_gardens.name', 'categories_gardens.id');
        if (($key = array_search('E-garden',$collection )) !== false) {
            unset($collection[$key]);
        }
        return  $collection;
    }
}
