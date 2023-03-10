<?php

namespace Botble\Member\Tables;

use Botble\Member\Repositories\Interfaces\NotifyInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class NotifyTable extends TableAbstract
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
     * @param NotifyInterface $notifyRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, NotifyInterface $forbiddenRepository)
    {
        $this->repository = $forbiddenRepository;
        $this->setOption('id', 'table-members-notify-key');
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
//                return anchor_link(route('member.notify.edit', $item->id), $item->title);
                return $item->title;
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, NOTIFY_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions(null, 'member.notify.delete', $item);
//                return table_actions('member.notify.edit', 'member.notify.delete', $item);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Get the query object to be processed by the table.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     * @author Sang Nguyen
     * @since 2.1
     */
    public function query()
    {
        $model = app(NotifyInterface::class)->getModel();
        $query = $model
            ->select([
                'notify.id',
                'notify.title',
                'notify.created_at',

            ]);
        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, NOTIFY_MODULE_SCREEN_NAME));
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
                'name' => 'notify.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'name' => 'notify.title',
                'title' => trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name' => 'notify.created_at',
                'title' => trans('core/base::tables.created_at'),
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
//            'create' => [
//                'link' => route('member.notify.create'),
//                'text' => view('core.base::elements.tables.actions.create')->render(),
//            ],

            'notify_1' => [
                'link' => route('member.notify1.create'),
                'text' => '푸시알람 보내기 1',
            ],
            'notify_2' => [
                'link' => route('member.notify2.create'),
                'text' => '푸시알람 보내기 2',
            ],
        ];
        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, NOTIFY_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href' => route('member.notify.delete.many'),
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
            'members.title' => [
                'title' => trans('core/base::tables.title'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'members.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],
        ];
    }
}
