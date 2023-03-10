<?php

namespace Botble\Campus\Tables\Genealogy;

use Botble\Campus\Repositories\Interfaces\Genealogy\GenealogyCommentsInterface;
use Botble\Campus\Repositories\Interfaces\Shelter\ShelterCommentsInterface;
use Botble\Campus\Repositories\Interfaces\StudyRoom\StudyRoomCommentsInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class GenealogyCommentsTable extends TableAbstract
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

    public function __construct(DataTables $table, UrlGenerator $urlGenerator, GenealogyCommentsInterface $genealogyCommentsRepository)
    {
        
        $this->repository = $genealogyCommentsRepository;
        $this->setOption('id', 'table-plugins-genealogy-comments');
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
            ->editColumn('contents', function ($item) {
                return $item->content;
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, GENEALOGY_COMMENTS_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('', 'campus.genealogy.comments.delete', $item);
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
        $id =$this->getOption('id');
        $model = $this->repository->getModel() ;
        $query = $model->where('genealogy_id',$id)->select([
            'genealogy_comments.id',
            'genealogy_comments.content',
            'genealogy_comments.created_at',
        ]);
        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, GENEALOGY_COMMENTS_MODULE_SCREEN_NAME));
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
                'name' => 'genealogy_comments.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'contents' => [
                'name' => 'genealogy_comments.content',
                'title' => 'Contents',
                'width' => '200px',
            ],
            'created_at' => [
                'name' => 'genealogy_comments.created_at',
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
        $id =$this->getOption('id');
        $buttons = [
            'create' => [
                'link' => route('campus.genealogy.comments.create',['id'=>$id]),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, GENEALOGY_COMMENTS_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('campus.genealogy.comments.delete.many'),
            'data_class' => get_class($this),
        ]);

        return $actions;
    }

}
