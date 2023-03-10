<?php

namespace Botble\Campus\Tables\Evaluation;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campus\Repositories\Interfaces\Evaluation\EvaluationInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class EvaluationTable extends TableAbstract
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
     * @param EvaluationInterface $campusRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, EvaluationInterface $evaluationRepository)
    {
        $this->repository = $evaluationRepository;
        $this->setOption('id', 'table-plugins-campus');
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
                return anchor_link(route('campus.evaluation.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('comments', function ($item) {
                return view('plugins.campus::elements.tables.actions.showComments')->with(['id' =>$item->id ,'count'=> count($item->comments),'route'=>route('campus.evaluation.comments.list',['id'=>$item->id])])->render();
            })
            ->editColumn('status', function ($item) {
                if ($item->status == 'pending') {
                    return '<span class="label-warning status-label">'. trans('core/base::tables.end').'</span>';
                }
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, EVALUATION_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('campus.evaluation.edit', 'campus.evaluation.delete', $item);
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
            'evaluation.id',
            'evaluation.title',
            'evaluation.created_at',
            'evaluation.status',
        ])->withCount('comments');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, EVALUATION_MODULE_SCREEN_NAME));
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
                'name' => 'evaluation.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'name' => 'evaluation.title',
                'title' => trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'comments' => [
                'name' => 'comments_count',
                'title' => trans('core/base::tables.comment'),
                'width' => '200px',
            ],
            'status' => [
                'name' => 'evaluation.status',
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
            'major' => [
                'link' => route('campus.evaluation.major.list'),
                'text' => view('plugins.campus::elements.tables.actions.category')->render(),
            ],
            'create' => [
                'link' => route('campus.evaluation.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
            'import' => [
                'link' => route('campus.evaluation.import'),
                'text' => view('core.base::elements.tables.actions.import')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, EVALUATION_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href' => route('campus.evaluation.delete.many'),
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
            'evaluation.title' => [
                'title' => trans('core/base::tables.title'),
                'type' => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'evaluation.status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => ['publish' => trans('core/base::tables.publish'), 'draft' =>  trans('core/base::tables.draft'), 'pending' =>  trans('core/base::tables.end')],
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'evaluation.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return $this->repository->pluck('evaluation.title', 'evaluation.id');
    }
}
