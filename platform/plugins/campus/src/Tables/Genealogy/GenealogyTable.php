<?php

namespace Botble\Campus\Tables\Genealogy;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campus\Repositories\Interfaces\Genealogy\GenealogyInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class GenealogyTable extends TableAbstract
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
     * @param GenealogyInterface $genealogyRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, GenealogyInterface $genealogyRepository)
    {
        $this->repository = $genealogyRepository;
        $this->setOption('id', 'table-plugins-genealogy');
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
            ->editColumn('semester_year', function ($item) {
                return anchor_link(route('campus.genealogy.edit', $item->id), $item->semester_year.' / '.$item->semester_session );
            })
            ->editColumn('class_name', function ($item) {
                return  $item->class_name;
            })
            ->editColumn('professor_name', function ($item) {
                return  $item->professor_name;
            })
            ->editColumn('exam_name', function ($item) {
                return  $item->exam_name;
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('comments', function ($item) {
                return view('plugins.campus::elements.tables.actions.showComments')->with(['id' =>$item->id ,'count'=> count($item->comments),'route'=>route('campus.genealogy.comments.list',['id'=>$item->id])])->render();
            })
            ->editColumn('published', function ($item) {
                return date_from_database($item->published, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, GENEALOGY_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('campus.genealogy.edit', 'campus.genealogy.delete', $item);
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
            'genealogy.id',
            'genealogy.semester_year',
            'genealogy.semester_session',
            'genealogy.class_name',
            'genealogy.exam_name',
            'genealogy.professor_name',
            'genealogy.published',
            'genealogy.status',
        ])->withCount('comments');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, GENEALOGY_MODULE_SCREEN_NAME));
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
                'name' => 'genealogy.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'semester_year' => [
                'name' => 'semester_year',
                'title' =>'학기',
                'class' => 'text-left',
            ],
            'professor_name' => [
                'name' => 'genealogy.professor_name',
                'title' =>'교수명',
                'class' => 'text-left',
            ],
            'class_name' => [
                'name' => 'genealogy.class_name',
                'title' =>'수업',
                'class' => 'text-left',
            ],
            'exam_name' => [
                'name' => 'genealogy.exam_name',
                'title' =>'시험',
                'class' => 'text-left',
            ],
            'comments' => [
                'name' => 'comments_count',
                'title' => '댓글',
                'width' => '200px',
            ],
            'published' => [
                'name' => 'genealogy.published',
                'title' => '등록일자',
                'width' => '100px',
            ],
            'status' => [
                'name' => 'genealogy.status',
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
                'link' => route('campus.evaluation.major.list'),
                'text' => view('plugins.campus::elements.tables.actions.category')->render(),
            ],
            'create' => [
                'link' => route('campus.genealogy.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ]
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, GENEALOGY_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('campus.genealogy.delete.many'),
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
            'genealogy.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'genealogy.published' => [
                'title' => trans('core/base::tables.published'),
                'type'  => 'date',
            ],
        ];
    }
}
