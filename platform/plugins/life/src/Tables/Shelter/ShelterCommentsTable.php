<?php

namespace Botble\Life\Tables\Shelter;

use Botble\Life\Repositories\Interfaces\Jobs\JobsCommentsInterface;
use Botble\Life\Repositories\Interfaces\Shelter\ShelterCommentsInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class ShelterCommentsTable extends TableAbstract
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
     * @param ShelterCommentsInterface $eventsRepository
     */

    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ShelterCommentsInterface $shelterCommentsRepository)
    {
        
        $this->repository = $shelterCommentsRepository;
        $this->setOption('id', 'table-plugins-shelter-comments');
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

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, SHELTER_COMMENTS_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('', 'life.shelter.comments.delete', $item);
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
        $query = $model->where('shelter_id',$id)->select([
            'shelter_comments.id',
            'shelter_comments.content',
            'shelter_comments.created_at',
        ]);
        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, SHELTER_COMMENTS_MODULE_SCREEN_NAME));
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
                'name' => 'shelter_comments.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'contents' => [
                'name' => 'shelter_comments.content',
                'title' => 'Contents',
                'width' => '200px',
            ],
            'created_at' => [
                'name' => 'shelter_comments.created_at',
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
                'link' => route('life.shelter.comments.create',['id'=>$id]),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, SHELTER_COMMENTS_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('life.shelter.comments.delete.many'),
            'data_class' => get_class($this),
        ]);

        return $actions;
    }

}
