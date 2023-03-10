<?php

namespace Botble\Member\Tables;

use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\Member\Repositories\Interfaces\MemberInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class MemberTable extends TableAbstract
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
     * @param MemberInterface $memberRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, MemberInterface $memberRepository, RoleInterface $roleRepository) {
        $this->repository = $memberRepository;
        $this->roleRepository = $roleRepository;
        $this->setOption('id', 'table-members');
        parent::__construct($table, $urlGenerator);
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     * @since 2.1
     */
    public function ajax() {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('fullname', function ($item) {
                return anchor_link(route('member.edit', $item->id), $item->fullname);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('role_name', function ($item) {
                return view('plugins.member::table.roles', ['item' => $item])->render();
            })
            ->editColumn('level', function ($item) {
                return getLevelMember($item);
            })
            ->editColumn('block_user', function ($item) {
                return $item->block_user != 0 ? STATUS_BLOCK_USER[$item->block_user] : null;
            })
            ->editColumn('last_login', function ($item) {
                return date_from_database($item->last_login, 'Y-m-d H:i:s');
            })
            ->editColumn('certification', function ($item) {
                switch ($item->certification) {
                    case 'real_name_certification':
                        // return '<span class="label-warning status-label">Real Name Certification</span>';
                        return '<span class="label-warning status-label">실명 인증</span>';
                        break;
                    case 'sprout':
                        // return '<span class="label-info status-label">Sprout</span>';
                        return '<span class="label-info status-label">새싹 인증</span>';
                        break;
                    case 'certification':
                        // return '<span class="label-success status-label">Certification</span>';
                        return '<span class="label-success status-label">이화인 인증</span>';
                        break;
                    default:
                        # code...
                        break;
                }
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            })
            ->removeColumn('role_id');

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, MEMBER_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('member.edit', 'member.delete', $item);
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
    public function query() {
        $model = app(MemberInterface::class)->getModel();
        $query = $model
            ->leftJoin('roles', 'roles.id', '=', 'members.role_member_id')
            ->select([
                'members.id',
                'members.id_login',
                'members.member_id',
                'members.fullname',
                'members.email',
                'members.certification',
                'members.created_at',
                'members.student_number',
                'members.nickname',
                'members.role_member_id',
                'members.level',
                'members.point',
                'members.block_user',
                'members.last_login',
                'members.count_login',
                'roles.name as role_name',
                'roles.id as role_id',

            ]);
        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, MEMBER_MODULE_SCREEN_NAME));
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function columns() {
        return [
            'id' => [
                'name' => 'members.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-left',
            ],
            'id_login' => [
                'name' => 'id_login',
                'title' => __('id_login'),
                'class' => 'text-left',
            ],
            'student_number' => [
                'name' => 'members.student_number',
                'title' => '학번/수험번호',
                'class' => 'text-left',
            ],
            'nickname' => [
                'name' => 'members.nickname',
                'title' => '닉네임',
                'class' => 'text-left',
            ],
            'fullname' => [
                'name' => 'members.fullname',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'email' => [
                'name' => 'members.email',
                'title' => trans('core/base::tables.email'),
                'class' => 'text-left',
            ],
            'certification' => [
                'name' => 'members.certification',
                'title' => __('member.certification'),
                'class' => 'text-left',
            ],
            'role_name' => [
                'name' => 'role_name',
                'title' => trans('core/acl::users.role'),
                'class' => 'text-left',
            ],
            'level' => [
                'name' => 'level',
                'title' => __('home.level'),
                'class' => 'text-left',
            ],

            'count_login' => [
                'name' => 'members.count_login',
                'title' => __('count_login'),
                'width' => '100px',
                'class' => 'text-left',
            ],
            'last_login' => [
                'name' => 'members.created_at',
                'title' => trans('core/base::tables.last_login'),
                'width' => '100px',
                'class' => 'text-left',
            ],
            'created_at' => [
                'name' => 'members.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-left',
            ],
            'block_user' => [
                'name' => 'block_user',
                'title' => __('block_user'),
                'class' => 'text-left',
            ],
        ];
    }

    /**
     * @return array
     * @throws \Throwable
     * @since 2.1
     * @author Sang Nguyen
     */
    public function buttons() {
        $buttons = [
            'create' => [
                'link' => route('member.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];
        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, MEMBER_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href' => route('member.delete.many'),
            'data_class' => get_class($this),
        ]);

        return $actions;
    }

    /**
     * @return mixed
     */
    public function getBulkChanges(): array {
        return [
            'member.member_id' => [
                'title' =>  trans('core/base::tables.id'),
                'type' => 'text',
            ],
            'member.id_login' => [
                'title' => __('id_login'),
                'type' => 'text',
            ],
            'members.student_number' => [
                'title' => '학번/수험번호',
                'class' => 'text-left',
                'type' => 'text',
            ],
            'members.nickname' => [
                'title' => '닉네임',
                'class' => 'text-left',
                'type' => 'text',
            ],
            'members.fullname' => [
                'title' =>  trans('core/base::tables.full_name'),
                'type' => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getFullNames',
            ],
            'members.email' => [
                'title' => trans('core/base::tables.email'),
                'type' => 'text',
                'validate' => 'required|max:120|email',
                'callback' => 'getEmails',
            ],
            'members.block_user' => [
                'title' => __('block_user'),
                'type' => 'select',
                'validate' => 'required',
                'choices' => STATUS_BLOCK_USER
            ],
            'members.certification' => [
                'title' => __('member.certification'),
                'type' => 'select',
                'validate' => 'required',
                'choices' => ["real_name_certification" => "Real Name Certification", "sprout " => "Sprout", "certification" => "Certification"],
            ],
            'members.role_member_id' => [
                'title' => trans('core/acl::users.role'),
                'type' => 'select',
                'validate' => 'required|max:120',
                'callback' => 'getAllRoles',
            ],
            'members.passwd_garden' => [
                'title' => '정원 비밀번호',
                'type' => 'text',
            ],
//            'members.is_active' => [
//                'title' => trans('core/acl::users.is_active'),
//                'type' => 'select',
//                'choices' => ['1' => 'Active', '0' => 'Inactive'],
//            ],
//            'members.created_at' => [
//                'title' => trans('core/base::tables.created_at'),
//                'type' => 'date',
//            ],

        ];
    }

    /**
     * @return array
     */
    public function getFullNames() {
        return $this->repository->pluck('members.fullname', 'members.id');
    }

    /**
     * @return array
     */
    public function getFirstNames() {
        return $this->repository->pluck('members.first_name', 'members.id');
    }

    /**
     * @return array
     */
    public function getLastNames() {
        return $this->repository->pluck('members.last_name', 'members.id');
    }

    /**
     * @return array
     */
    public function getEmails() {
        return $this->repository->pluck('members.email', 'members.id');
    }

    /**
     * @return array
     */
    public function getAllRoles() {
        return $this->roleRepository->pluck('roles.name', 'roles.id');
    }
}
