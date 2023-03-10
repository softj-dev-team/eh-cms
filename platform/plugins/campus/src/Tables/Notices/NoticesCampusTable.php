<?php

namespace Botble\Campus\Tables\Notices;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campus\Repositories\Interfaces\CampusInterface;
use Botble\Campus\Repositories\Interfaces\Notices\NoticesCampusInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class NoticesCampusTable extends TableAbstract
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
     * @param NoticesCampusInterface $noticesCampusRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, NoticesCampusInterface $noticesCampusRepository)
    {
        $this->repository = $noticesCampusRepository;
        $this->setOption('id', 'table-plugins-notices-campus');
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
                return anchor_link(route('campus.notices.edit', $item->id), $item->name);
            })
            ->editColumn('code', function ($item) {
                switch ($item->code) {
                    case 'STUDY_ROOM_MODULE_SCREEN_NAME':
                        return 'Study Room';
                        break;
                    case 'GENEALOGY_MODULE_SCREEN_NAME':
                        return 'Genealogy';
                        break;
                    case 'OLD_GENEALOGY_MODULE_SCREEN_NAME':
                        return 'Old Genealogy';
                        break;
                     case 'EVALUATION_MODULE_SCREEN_NAME':
                        return 'Evaluation';
                        break;
                };
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, NOTICES_CAMPUS_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('campus.notices.edit', 'campus.notices.delete', $item);
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
            'notices_campus.id',
            'notices_campus.name',
            'notices_campus.code',
            'notices_campus.created_at',
            'notices_campus.status',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, NOTICES_CAMPUS_MODULE_SCREEN_NAME));
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
                'name' => 'notices_campus.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name' => [
                'name' => 'notices_campus.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'code' => [
                'name' => 'notices_campus.code',
                'title' => '대상 게시판',
                'class' => 'text-left',
            ],
            'created_at' => [
                'name' => 'notices_campus.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'name' => 'notices_campus.status',
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
                'link' => route('campus.notices.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, NOTICES_CAMPUS_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('campus.notices.delete.many'),
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
            'notices_campus.name' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'notices_campus.status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'notices_campus.code' => [
                'title'    => trans('core/base::tables.belong_to'),  // belong to
                'type'     => 'select',
                'validate' => 'required|max:120',
                'callback' => 'getCode',
            ],
            'notices_campus.created_at' => [
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
        return $this->repository->pluck('notices_campus.name', 'notices_campus.id');
    }
    public function getCode()
    {
        return [
                'STUDY_ROOM_MODULE_SCREEN_NAME'=>'Study Room',
                'GENEALOGY_MODULE_SCREEN_NAME'=>'Genealogy',
                'OLD_GENEALOGY_MODULE_SCREEN_NAME'=>'Old Genealogy',
                'EVALUATION_MODULE_SCREEN_NAME'=>'Evaluation'
        ];
    }
}
