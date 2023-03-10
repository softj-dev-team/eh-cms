<?php

namespace Botble\Campus\Tables\Evaluation;

use Botble\Campus\Repositories\Interfaces\Evaluation\CommentsEvaluationInterface;
use Botble\Campus\Repositories\Interfaces\Genealogy\GenealogyCommentsInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class CommentsEvaluationTable extends TableAbstract
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
     * @param GenealogyCommentsInterface $eventsRepository
     */

    public function __construct(DataTables $table, UrlGenerator $urlGenerator, CommentsEvaluationInterface $commentsEvaluationRepository)
    {

        $this->repository = $commentsEvaluationRepository;
        $this->setOption('id', 'table-plugins-evaluation-comments');
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
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('comments', function ($item) {
                return anchor_link(route('campus.evaluation.comments.edit', $item->id), $item->comments);
            })
            ->editColumn('votes', function ($item) {
                return view('plugins.campus::elements.tables.actions.vote')->with(['id' => $item->id, 'votes' => $item->votes, 'route' =>'javascript:void(0)'])->render();
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, COMMENTS_EVALUATION_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('campus.evaluation.comments.edit', 'campus.evaluation.comments.delete', $item);
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
        $id = $this->getOption('id');
        $model = $this->repository->getModel();
        $query = $model->where('evaluation_id', $id)->select([
            'comments_evaluation.id',
            'comments_evaluation.comments',
            'comments_evaluation.votes',
            'comments_evaluation.created_at',
        ]);
        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, COMMENTS_EVALUATION_MODULE_SCREEN_NAME));
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
                'name' => 'comments_evaluation.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'comments' => [
                'name' => 'comments_evaluation.comments',
                'title' => 'Comments',
                'width' => '200px',
            ],
            'votes' => [
                'name' => 'comments_evaluation.votes',
                'title' => 'Votes',
                'width' => '200px',
            ],
            'created_at' => [
                'name' => 'comments_evaluation.created_at',
                'title' => trans('core/base::tables.created_at'),
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
        $id = $this->getOption('id');
        $buttons = [
            'create' => [
                'link' => route('campus.evaluation.comments.create', ['id' => $id]),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, COMMENTS_EVALUATION_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href' => route('campus.evaluation.comments.delete.many'),
            'data_class' => get_class($this),
        ]);

        return $actions;
    }

}
