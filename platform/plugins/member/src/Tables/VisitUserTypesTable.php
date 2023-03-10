<?php

namespace Botble\Member\Tables;

use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\Member\Models\Member;
use Botble\Member\Repositories\Interfaces\MemberInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class VisitUserTypesTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = false;

    /**
     * @var bool
     */
    protected $hasFilter = false;

    /**
     * TagTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param MemberInterface $memberRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, MemberInterface $memberRepository, RoleInterface $roleRepository)
    {
        $this->repository = $memberRepository;
        $this->roleRepository = $roleRepository;
        $this->setOption('id', 'table-cms-visit-user-types');
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
        // get
        $model = app(MemberInterface::class)->getModel();
        $sprout_count = $model->where('status_fresh1', 2)->count();
        $ewhaian_count = $model->where('status_fresh2', 2)->count();
        $blacklist_count = $model->where('is_blacklist', Member::IS_BLACKLIST)->count();
        $total_user = $model->count();
        $data = [
            'data' => [
                0 => [
                    'checkbox' => table_checkbox(1),
                    'id' => 1,
                    'title' => 'Sprout Authenticate',
                    'count' => $sprout_count,
                    'operations' => ''
                ],
                1 => [
                    'checkbox' => table_checkbox(2),
                    'id' => 2,
                    'title' => 'Ewhaian Authenticate',
                    'count' => $ewhaian_count,
                    'operations' => ''
                ],
                2 => [
                    'checkbox' => table_checkbox(3),
                    'id' => 3,
                    'title' => 'Blacklist',
                    'count' => $blacklist_count,
                    'operations' => ''
                ],
                3 => [
                    'checkbox' => table_checkbox(4),
                    'id' => 4,
                    'title' => 'Total user',
                    'count' => $total_user,
                    'operations' => ''
                ],
            ],
            'recordsFiltered' => 3,
            'recordsTotal' => 3,

        ];
        return json_encode($data,true);
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 'members.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title'  => [
                'name'  => 'members.title',
                'title' => __('event.event_comments.title'),
            ],
            'count'  => [
                'name'  => 'members.count',
                'title' => __('member.count'),
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
            // 'create' => [
            //     'link' => route('member.create'),
            //     'text' => view('core.base::elements.tables.actions.create')->render(),
            // ],
        ];
        return [];
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {

        return [];
    }
}
