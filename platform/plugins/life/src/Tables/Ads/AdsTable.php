<?php

namespace Botble\Life\Tables\Ads;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Life\Repositories\Interfaces\Ads\AdsInterface;
use Botble\Life\Repositories\Interfaces\FlareInterface;
use Botble\Life\Repositories\Interfaces\Jobs\JobsPartTimeInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class AdsTable extends TableAbstract
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
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, AdsInterface $adsRepository)
    {
        $this->repository = $adsRepository;
        $this->setOption('id', 'table-plugins-advertisements');
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
                return anchor_link(route('life.advertisements.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('comments', function ($item) {
                return view('plugins.life::elements.tables.actions.showComments')->with(['id' =>$item->id ,'count'=> count($item->comments),'route'=>route('life.advertisements.comments.list',['id'=>$item->id])])->render();
            })
            ->editColumn('published', function ($item) {
                return date_from_database($item->published, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, ADS_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('life.advertisements.edit', 'life.advertisements.delete', $item);
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
            'advertisements.id',
            'advertisements.title',
            'advertisements.published',
            'advertisements.status',
        ])->withCount('comments');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, ADS_MODULE_SCREEN_NAME));
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
                'name' => 'advertisements.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'name' => 'advertisements.title',
                'title' => trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'comments' => [
                'name' => 'comments_count',
                'title' =>  trans('core/base::tables.comment'),
                'width' => '200px',
            ],
            'published' => [
                'name' => 'advertisements.published',
                'title' =>  trans('core/base::tables.published'),
                'width' => '100px',
            ],
            'status' => [
                'name' => 'advertisements.status',
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
                'link' => route('life.advertisements.categories.list'),
                'text' => view('plugins.life::elements.tables.actions.category')->render(),
            ],
            'create' => [
                'link' => route('life.advertisements.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, ADS_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('life.advertisements.delete.many'),
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
            'advertisements.title' => [
                'title'    => trans('core/base::tables.title'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'advertisements.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'advertisements.published' => [
                'title' => trans('core/base::tables.published'),
                'type'  => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return $this->repository->pluck('advertisements.title', 'advertisements.id');
    }
}
