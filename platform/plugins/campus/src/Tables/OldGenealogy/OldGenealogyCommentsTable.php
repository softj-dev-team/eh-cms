<?php

namespace Botble\Campus\Tables\OldGenealogy;

use Botble\Campus\Repositories\Interfaces\Genealogy\GenealogyCommentsInterface;
use Botble\Campus\Repositories\Interfaces\OldGenealogy\OldGenealogyCommentsInterface;
use Botble\Campus\Repositories\Interfaces\Shelter\ShelterCommentsInterface;
use Botble\Campus\Repositories\Interfaces\StudyRoom\StudyRoomCommentsInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class OldGenealogyCommentsTable extends TableAbstract
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
     * @param OldGenealogyCommentsInterface $eventsRepository
     */

    public function __construct(DataTables $table, UrlGenerator $urlGenerator, OldGenealogyCommentsInterface $oldGenealogyCommentsRepository)
    {

        $this->repository = $oldGenealogyCommentsRepository;
        $this->setOption('id', 'table-plugins-old-genealogy-comments');
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

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, OLD_GENEALOGY_COMMENTS_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('', 'campus.old.genealogy.comments.delete', $item);
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
        $query = $model->where('old_genealogy_id',$id)->select([
            'old_genealogy_comments.id',
            'old_genealogy_comments.content',
            'old_genealogy_comments.created_at',
        ]);
        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, OLD_GENEALOGY_COMMENTS_MODULE_SCREEN_NAME));
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
                'name' => 'old_genealogy_comments.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'contents' => [
                'name' => 'old_genealogy_comments.content',
                'title' => 'Contents',
                'width' => '200px',
            ],
            'created_at' => [
                'name' => 'old_genealogy_comments.created_at',
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
                'link' => route('campus.old.genealogy.comments.create',['id'=>$id]),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, OLD_GENEALOGY_COMMENTS_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('campus.old.genealogy.comments.delete.many'),
            'data_class' => get_class($this),
        ]);

        return $actions;
    }

}
