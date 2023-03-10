<?php

namespace Botble\Campus\Tables\OldGenealogy;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campus\Repositories\Interfaces\Genealogy\GenealogyInterface;
use Botble\Campus\Repositories\Interfaces\OldGenealogy\OldGenealogyInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class OldGenealogyTable extends TableAbstract
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
     * @param OldGenealogyInterface $genealogyRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, OldGenealogyInterface $oldGenealogyRepository)
    {
        $this->repository = $oldGenealogyRepository;
        $this->setOption('id', 'table-plugins-old-genealogy');
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
                return anchor_link(route('campus.old.genealogy.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('comments', function ($item) {
                return view('plugins.campus::elements.tables.actions.showComments')->with(['id' => $item->id, 'count' => count($item->comments), 'route' => route('campus.old.genealogy.comments.list', ['id' => $item->id])])->render();
            })
            ->editColumn('published', function ($item) {
                return date_from_database($item->published, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, OLD_GENEALOGY_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('campus.old.genealogy.edit', 'campus.old.genealogy.delete', $item);
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
            'old_genealogy.id',
            'old_genealogy.title',
            'old_genealogy.published',
            'old_genealogy.status',
        ])->withCount('comments');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, OLD_GENEALOGY_MODULE_SCREEN_NAME));
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
                'name' => 'old_genealogy.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'name' => 'old_genealogy.title',
                'title' => trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'comments' => [
                'name' => 'comments_count',
                'title' =>trans('core/base::tables.comment'),
                'width' => '200px',
            ],
            'published' => [
                'name' => 'old_genealogy.published',
                'title' => trans('core/base::tables.published'),
                'width' => '100px',
            ],
            'status' => [
                'name' => 'old_genealogy.status',
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
                'link' => route('campus.old.genealogy.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, OLD_GENEALOGY_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href' => route('campus.old.genealogy.delete.many'),
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
            'old_genealogy.title' => [
                'title' => trans('core/base::tables.title'),
                'type' => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'old_genealogy.status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'old_genealogy.published' => [
                'title' => trans('core/base::tables.published'),
                'type' => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return $this->repository->pluck('old_genealogy.title', 'old_genealogy.id');
    }
}
