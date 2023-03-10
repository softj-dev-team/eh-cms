<?php

namespace Botble\Life\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Life\Repositories\Interfaces\DescriptionInterface;
use Botble\Life\Repositories\Interfaces\LifeInterface;
use Botble\Life\Repositories\Interfaces\NoticesInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class DescriptionTable extends TableAbstract
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
     * @param DescriptionInterface $descriptionRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, DescriptionInterface $descriptionRepository)
    {
        $this->repository = $descriptionRepository;
        $this->setOption('id', 'table-plugins-description');
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
                return anchor_link(route('life.description.edit', $item->id), $item->name);
            })
            ->editColumn('code', function ($item) {
                switch ($item->code) {
                    case 'FLARE_MODULE_SCREEN_NAME':
                        return 'Flea Market';
                        break;
                    case 'JOBS_PART_TIME_MODULE_SCREEN_NAME':
                        return 'Jobs Part Time';
                        break;
                    case 'ADS_MODULE_SCREEN_NAME':
                        return 'Advertisements';
                        break;
                    case 'OPEN_SPACE_MODULE_SCREEN_NAME':
                        return 'Open Space';
                        break;
                     case 'SHELTER_MODULE_SCREEN_NAME':
                        return 'Shelter';
                        break;
                };
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

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, NOTICES_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('life.description.edit', 'life.description.delete', $item);
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
            'description_life.id',
            'description_life.name',
            'description_life.code',
            'description_life.created_at',
            'description_life.status',
            ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, DESCRIPTION_MODULE_SCREEN_NAME));
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
                'name' => 'description_life.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name' => [
                'name' => 'description_life.name',
                'title' => trans('core/base::tables.title'),
                'class' => 'text-left',
                'width' => '400px',
            ],
            'code' => [
                'name' => 'description_life.code',
                'title' => trans('core/base::tables.belong_to'),
                'class' => 'text-left',
                'width' => '400px',
            ],
            'created_at' => [
                'name' => 'description_life.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'name' => 'description_life.status',
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
                'link' => route('life.description.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, NOTICES_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('life.description.delete.many'),
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
            'description_life.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'description_life.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }

}
