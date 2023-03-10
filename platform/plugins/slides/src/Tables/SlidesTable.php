<?php

namespace Botble\Slides\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Slides\Repositories\Interfaces\SlidesInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class SlidesTable extends TableAbstract
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
     * @param SlidesInterface $slidesRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, SlidesInterface $slidesRepository)
    {
        $this->repository = $slidesRepository;
        $this->setOption('id', 'table-plugins-slides');
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
                return anchor_link(route('slides.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('code', function ($item) {
                switch ($item->code) {
                    case 'HOME':
                        return "사이트 메인";
                        break;
                    case 'SLIDES':
                        return "메인 배너";
                        break;
                    case 'GARDEN':
                        return "비원 배너";
                        break;
                    case 'EGARDEN':
                        return "e화원";
                        break;
                    case 'SLIDES_MOBILE':
                        return "모바일배너";
                        break;
                    case 'ACCOUNT':
                        return "사이트 좌측 배너";
                        break;
                    case 'ACCOUNT_GARDEN':
                        return "비원 좌측 배너";
                        break;
                    default:
                        # code...
                        break;
                }
                return $item->code;
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, SLIDES_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('slides.edit', 'slides.delete', $item);
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
            'slides.id',
            'slides.name',
            'slides.code',
            'slides.created_at',
            'slides.status',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, SLIDES_MODULE_SCREEN_NAME));
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
                'name' => 'slides.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name' => [
                'name' => 'slides.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'code' => [
                'name' => 'slides.code',
                'title' => trans('plugins/language::language.code'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name' => 'slides.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'name' => 'slides.status',
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
                'link' => route('slides.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, SLIDES_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('slides.delete.many'),
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
            'slides.name' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'slides.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'slides.created_at' => [
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
        return $this->repository->pluck('slides.name', 'slides.id');
    }
}
