<?php

namespace Botble\Member\Tables;

use Analytics;
use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\Analytics\Exceptions\InvalidConfiguration;
use Botble\Analytics\Period;
use Botble\Member\Models\Member;
use Botble\Member\Repositories\Interfaces\MemberInterface;
use Botble\Table\Abstracts\TableAbstract;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class VisitWeeklyTable extends TableAbstract
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
        $this->setOption('id', 'table-cms-visit-weekly');
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
        $startDate = Carbon::today(config('app.timezone'))->startOfWeek();
        $endDate = Carbon::today(config('app.timezone'))->endOfWeek();

        try {
            $period = Period::create($startDate, $endDate);
            $visits = Analytics::performQuery($period, 'ga:users', ['dimensions' => 'ga:date']);
            $visitor = [
                'data' => [
                    0 => [
                        'checkbox' => table_checkbox(1),
                        'visitor' => $visits->totalsForAllResults['ga:users'] ." (Users)",
                        'operations' => ''
                    ],
                ],
                'recordsFiltered' => 1,
                'recordsTotal' => 1,

            ];
            return json_encode($visitor, true);

        } catch (InvalidConfiguration $ex) {
            $visitor = [
                'data' => [
                    0 => [
                        'checkbox' => table_checkbox(1),
                        'visitor' => trans('plugins/analytics::analytics.wrong_configuration', ['version' => get_cms_version()]),
                        'operations' => ''
                    ],
                ],
                'recordsFiltered' => 1,
                'recordsTotal' => 1,

            ];
            return json_encode($visitor, true);
        } catch (Exception $ex) {
            $visitor = [
                'data' => [
                    0 => [
                        'checkbox' => table_checkbox(1),
                        'id' => 1,
                        'visitor' => $ex->getMessage(),
                        'operations' => ''
                    ],
                ],
                'recordsFiltered' => 1,
                'recordsTotal' => 1,

            ];
            return json_encode($visitor, true);
        }
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function columns()
    {
        return [
            'visitor' => [
                'name' => 'members.visitor',
                'title' => __('member.visitor'),
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
