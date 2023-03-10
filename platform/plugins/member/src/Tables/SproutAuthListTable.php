<?php

namespace Botble\Member\Tables;

use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\Member\Models\Member;
use Botble\Member\Repositories\Interfaces\MemberInterface;
use Botble\Table\Abstracts\TableAbstract;
use Carbon\Carbon;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class SproutAuthListTable extends TableAbstract
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
            ->editColumn('freshman1', function ($item) {
                return $this->showImageInTable($item->freshman1, $item->id);
//                return show_image($item->freshman1, $item->id);
            })
            ->editColumn('update_freshman1', function ($item) {
                return Carbon::parse($item->update_freshman1)->format('Y-m-d H:i:s');
            })
            ->editColumn('status_fresh1', function ($item) {
                switch ($item->status_fresh1) {
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
                return table_actions_auth('sprout',
                    route('member.authentication.sprout.update', ['id' => $item->id, 'approval' => 2]),
                    route('member.authentication.sprout.update', ['id' => $item->id, 'approval' => 3]),
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
            'members.nickname',
            'members.freshman1',
            'members.note_freshman1',
            'members.sprouts_number',
            'members.status_fresh1',
            'members.update_freshman1',
            'members.reason_reject_1',
        ])->whereNotNull('freshman1');
        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, MEMBER_MODULE_SCREEN_NAME));
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function columns() {
        return [
            'update_freshman1' => [
                'name' => 'update_freshman1',
                'title' => trans('core/base::tables.updated_at'),
            ],
            'note_freshman1' => [
                'name' => 'note_freshman1',
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
            'sprouts_number' => [
                'name' => 'sprouts_number',
                'title' => '수험번호',
                'class' => 'text-left',
            ],
            'freshman1' => [
                'name' => 'freshman1',
                'title' => __('life.flea_market.image'), // image
                'class' => 'text-left',
            ],
            'status_fresh1' => [
                'name' => 'status_fresh1',
                'title' => trans('core/base::tables.status'),
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
            'members.note_freshman1' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
            ],
            'members.sprouts_number' => [
                'title'    => '수험번호',
                'type'     => 'text',
            ],
            'members.status_fresh1' => [
                'title' => trans('core/base::tables.status'),
                'type'  => 'select',
                'choices'  =>  ["1" => "승인 대기", "2" => "승인", '3' => '거부',]
            ],
        ];
    }

    public function showImageInTable($link, $id){
        return view('plugins.member::elements.field.show_image_no_modal', compact('link', 'id'))->render();

    }
}
