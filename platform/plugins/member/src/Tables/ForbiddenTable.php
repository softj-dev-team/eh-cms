<?php

namespace Botble\Member\Tables;

use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\Member\Repositories\Interfaces\ForbiddenKeywordsInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class ForbiddenTable extends TableAbstract
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
     * @param ForbiddenKeywordsInterface $forbiddenRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ForbiddenKeywordsInterface $forbiddenRepository, RoleInterface $roleRepository)
    {
        $this->repository = $forbiddenRepository;
        $this->roleRepository = $roleRepository;
        $this->setOption('id', 'table-members-forbidden-key');
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
                return anchor_link(route('member.forbidden.edit', $item->id), $item->title);
            })
            ->editColumn('type', function ($item) {
                return $item->type == 'forbidden' ? '금지 단어' : '욕설';
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, FORBIDDEN_KEYWORDS_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('member.forbidden.edit', 'member.forbidden.delete', $item);
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
        $model = app(ForbiddenKeywordsInterface::class)->getModel();
        $query = $model
            ->select([
                'forbidden_keywords.id',
                'forbidden_keywords.title',
                'forbidden_keywords.type',
                'forbidden_keywords.created_at',

            ]);
        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, FORBIDDEN_KEYWORDS_MODULE_SCREEN_NAME));
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
                'name' => 'forbidden_keywords.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'name' => 'forbidden_keywords.title',
                'title' => trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'type' => [
                'name' => 'forbidden_keywords.type',
                'title' => __('type'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name' => 'forbidden_keywords.created_at',
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
            'create' => [
                'link' => route('member.forbidden.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];
        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, FORBIDDEN_KEYWORDS_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href' => route('member.forbidden.delete.many'),
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
            'forbidden_keywords.title' => [
                'title' => trans('core/base::tables.title'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'forbidden_keywords.type' => [
                'title' => __('type'),
                'type' => 'select',
                'choices'  => [
                    'forbidden' => '금지 단어',
                    'swear_word' => '욕설',
                ]
            ],
            'forbidden_keywords.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],
        ];
    }
}
