<?php

namespace Botble\Member\Tables;

use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\Member\Models\Member;
use Botble\Member\Repositories\Interfaces\MemberInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class BlackListTable extends TableAbstract
{
    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    protected $hasCheckbox = false;

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
            ->editColumn('link', function ($item) {
                return anchor_link(route('report.edit', $item->report_id ?? 0), 'Link');
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            })
            ->editColumn('report_type', function ($item) {
                switch ($item->report_type) {
                    case 2:
                        return '댓글';
                    case 1:
                    default:
                        return '게시글';
                }
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, MEMBER_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions(NULL, 'blacklist.delete', $item);
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
        $query = $model->where('is_blacklist', Member::IS_BLACKLIST)
            ->select([
                'members.id',
                'members.report_id',
                'members.reporter_id',
                'members.report_date',
                'members.report_type',
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
            'report_id' => [
                'name' => 'members.report_id',
                'title' => __('member.report_number'),
                'width' => '100px',
            ],
            'reporter_id' => [
                'name' => 'members.reporter_id',
                'title' => __('member.reporter_id'),
                'class' => 'text-left',
            ],
            'id' => [
                'name' => 'members.id',
                'title' => __('member.hooligan_id'),
                'class' => 'text-left',
            ],
            'report_date' => [
                'name' => 'members.report_date',
                'title' => __('member.report_date'),
            ],
            'report_type' => [
                'name' => 'members.report_type',
                'title' => __('member.report_type'),
            ],
            'link' => [
                'name' => 'link',
                'title' => __('eh-introduction.link'),
                'width' => '100px',
            ],
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
