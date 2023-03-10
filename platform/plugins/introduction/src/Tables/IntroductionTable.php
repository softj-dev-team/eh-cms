<?php

namespace Botble\Introduction\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Introduction\Repositories\Interfaces\IntroductionInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class IntroductionTable extends TableAbstract
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
     * @param IntroductionInterface $introductionRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, IntroductionInterface $introductionRepository)
    {
        $this->repository = $introductionRepository;
        $this->setOption('id', 'table-plugins-introduction');
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
                return anchor_link(route('introduction.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('categories', function ($item) {
                return $item->categories->name ?? "No categories";
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, INTRODUCTION_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('introduction.edit', 'introduction.delete', $item);
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
            'introductions.id',
            'introductions.title',
            'introductions.categories_introductions_id',
            'introductions.created_at',
            'introductions.status',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, INTRODUCTION_MODULE_SCREEN_NAME));
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
                'name' => 'introductions.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'name' => 'introductions.title',
                'title' => __('eh-introduction.title'),
                'class' => 'text-left',
            ],
            'categories' => [
                'name' => 'introductions.categories_introductions_id',
                'title' => __('eh-introduction.faqs.category'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name' => 'introductions.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'name' => 'introductions.status',
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
                'link' => route('introduction.categories.list'),
                'text' => view('plugins.introduction::elements.tables.actions.category')->render(),
            ],
            'create' => [
                'link' => route('introduction.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, INTRODUCTION_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('introduction.delete.many'),
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
            'introductions.title' => [
                'title'    => __('eh-introduction.title'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'introductions.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'introductions.created_at' => [
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
        return $this->repository->pluck('introductions.title', 'introductions.id');
    }
}
