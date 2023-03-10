<?php

namespace Botble\Life\Tables\Jobs;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Life\Repositories\Interfaces\FlareInterface;
use Botble\Life\Repositories\Interfaces\Jobs\JobsPartTimeInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class JobsPartTimeTable extends TableAbstract
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
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, JobsPartTimeInterface $jobsPartTimeRepository)
    {
        $this->repository = $jobsPartTimeRepository;
        $this->setOption('id', 'table-plugins-jobs-part-time');
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
                return anchor_link(route('life.jobs_part_time.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('comments', function ($item) {
                return view('plugins.life::elements.tables.actions.showComments')->with(['id' =>$item->id ,'count'=> count($item->comments),'route'=>route('life.jobs_part_time.comments.list',['id'=>$item->id])])->render();
            })
            ->editColumn('published', function ($item) {
                return date_from_database($item->published, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                switch ($item->status) {
                    case 'publish':
                        return '<span class="label-success status-label">'. trans('core/base::tables.publish') .'</span>';
                        break;
                    case 'pending':
                        return '<span class="label-warning status-label">'. trans('core/base::tables.pending') .'</span>';
                        break;
                    case 'draft':
                        return '<span class="label-info status-label">'. trans('core/base::tables.draft') .'</span>';
                        break;
                    case 'approve':
                        return '<span class="label-info status-label">'. trans('core/base::tables.completed') .'</span>';
                        break;
                    default:
                        # code...
                        break;
                }
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, JOBS_PART_TIME_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('life.jobs_part_time.edit', 'life.jobs_part_time.delete', $item);
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
            'jobs_part_time.id',
            'jobs_part_time.title',
            'jobs_part_time.published',
            'jobs_part_time.status',
        ])->withCount('comments');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, JOBS_PART_TIME_MODULE_SCREEN_NAME));
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
                'name' => 'jobs_part_time.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'name' => 'jobs_part_time.title',
                'title' => trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'comments' => [
                'name' => 'comments_count',
                'title' => trans('core/base::tables.comment'),
                'width' => '200px',
            ],
            'published' => [
                'name' => 'jobs_part_time.published',
                'title' => trans('core/base::tables.published'),
                'width' => '100px',
            ],
            'status' => [
                'name' => 'jobs_part_time.status',
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
                'link' => route('life.jobs_part_time.categories.list'),
                'text' => view('plugins.life::elements.tables.actions.category')->render(),
            ],
            'create' => [
                'link' => route('life.jobs_part_time.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, JOBS_PART_TIME_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('life.jobs_part_time.delete.many'),
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
            'jobs_part_time.title' => [
                'title'    => trans('core/base::tables.title'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'jobs_part_time.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  =>["publish" => trans('core/base::tables.publish'),"pending" => trans('core/base::tables.pending'), "approve" => trans('core/base::tables.completed'),'draft'=>trans('core/base::tables.draft')],
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'jobs_part_time.published' => [
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
        return $this->repository->pluck('jobs_part_time.title', 'jobs_part_time.id');
    }
}
