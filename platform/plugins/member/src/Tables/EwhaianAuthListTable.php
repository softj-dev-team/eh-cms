<?php

namespace Botble\Member\Tables;

use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\Member\Models\Member;
use Botble\Member\Repositories\Interfaces\MemberInterface;
use Botble\Table\Abstracts\TableAbstract;
use Carbon\Carbon;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class EwhaianAuthListTable extends TableAbstract
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
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('freshman2', function ($item) {
                return $this->showImage($item->freshman2, $item->id);
            })
            ->editColumn('update_freshman2', function ($item) {
                return Carbon::parse($item->update_freshman2)->format('Y-m-d H:i:s');
            })
            ->editColumn('status_fresh2', function ($item) {
                switch ($item->status_fresh2) {
                    case '3':
                        return '거부';
                        break;
                    case '2':
                        return '승인';
                        break;
                    case '1':
                        return '승인 대기';
                        break;
                    default:
                        return 'No have image';
                        break;
                }
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, MEMBER_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions_auth('ewhaian',
                    route('member.authentication.ewhaian.update', ['id' => $item->id, 'approval' => 2]),
                    route('member.authentication.ewhaian.update', ['id' => $item->id, 'approval' => 3]),
                    $item
                );
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
        $query = $model->select([
            'members.id',
            'members.id_login',
            'members.freshman2',
            'members.note_freshman2',
            'members.status_fresh2',
            'members.update_freshman2',
            'members.auth_studentid',
            'members.reason_reject_2',
        ])->whereNotNull('freshman2');
        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, MEMBER_MODULE_SCREEN_NAME));
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function columns() {
        return [
            'update_freshman2' => [
                'name' => 'update_freshman2',
                'title' => trans('core/base::tables.updated_at'),
            ],
            'note_freshman2' => [
                'name' => 'members.note_freshman2',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'id_login' => [
                'name' => 'id_login',
                'title' => __('id_login'),
                'class' => 'text-left',
            ],
            'id' => [
                'name' => 'id',
                'title' => '회원번호',
                'class' => 'text-left',
            ],
            'auth_studentid' => [
                'name' => 'members.auth_studentid',
                'title' => '학번',
                'class' => 'text-left',
            ],
            'freshman2' => [
                'name' => 'members.freshman2',
                'title' => __('life.flea_market.image'),
                'class' => 'text-left',
            ],
            'status_fresh2' => [
                'name' => 'status_fresh2',
                'title' =>  trans('core/base::tables.status'),
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
        return [];
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array {
        return [];
    }

    public function showImage($link, $id){
        return view('plugins.member::elements.field.show_image_no_modal', compact('link','id'))->render();
    }

    /**
     * @return mixed
     */
    public function getBulkChanges(): array
    {
        return [
            'members.id_login' => [
                'title'    => __('id_login'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'members.id' => [
                'title'    => '회원번호',
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'members.note_freshman2' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
            ],
            'members.auth_studentid' => [
                'title'    => '학번',
                'type'     => 'text',
            ],
            'members.status_fresh2' => [
                'title' => trans('core/base::tables.status'),
                'type'  => 'select',
                'choices'  =>  ["1" => "승인 대기", "2" => "승인", '3' => '거부',]
            ],
        ];
    }
}
