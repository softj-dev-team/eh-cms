<?php

namespace Botble\Introduction\Tables\Notices;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Contents\Repositories\Interfaces\CategoriesContentsInterface;
use Botble\Garden\Repositories\Interfaces\CategoriesGardenInterface;
use Botble\Introduction\Repositories\Interfaces\Notices\NoticesIntroductionInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class NoticesIntroductionTable extends TableAbstract
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
     * @param NoticesIntroductionInterface $noticesIntroductionRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, NoticesIntroductionInterface $noticesIntroductionRepository)
    {
        $this->repository = $noticesIntroductionRepository;
        $this->setOption('id', 'table-plugins-notices-introduction');
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

        $contentCategories = app(CategoriesContentsInterface::class)->getModel()->all();
        $gardenCategories = app(CategoriesGardenInterface::class)->getModel()->all();
        $dataNoticeContent = getCategoryContentKeyNameNotice($contentCategories, CONTENTS_MODULE_SCREEN_NAME);
        $dataNoticeGarden = getCategoryContentKeyNameNotice($gardenCategories, GARDEN_MODULE_SCREEN_NAME);
        $dataCode = $this->getCode();
        $categories = $dataNoticeContent + $dataNoticeGarden;
        $codeValues = array_merge($categories, $dataCode);

        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                return anchor_link(route('introduction.notices.edit', $item->id), $item->name);
            })
            ->editColumn('code', function ($item) use ($codeValues) {

                $codes = null;
                foreach ($codeValues as $key => $code){
                    if (!empty($item->code) && in_array($key, $item->code)){
                        $codes .= $code . ',';
                    }
                }

                return rtrim($codes, ",");
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

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, NOTICES_INTRODUCTION_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('introduction.notices.edit', 'introduction.notices.delete', $item);
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
            'notices_introduction.id',
            'notices_introduction.name',
            'notices_introduction.code',
            'notices_introduction.created_at',
            'notices_introduction.status',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, NOTICES_INTRODUCTION_MODULE_SCREEN_NAME));
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
                'name'  => 'notices_introduction.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name'       => [
                'name'  => 'notices_introduction.name',
                'title' => __('eh-introduction.notices.title'),
                'class' => 'text-left',
            ],
            'code'       => [
                'name'  => 'notices_introduction.code',
                'title' => '대상 게시판',
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 'notices_introduction.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 'notices_introduction.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * @return array
     * @throws \Throwable
     * @since 2.1
     * @author Sang Nguyen
     */
    public function buttons()
    {
        $buttons = [
            'create' => [
                'link' => route('introduction.notices.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, NOTICES_INTRODUCTION_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('introduction.notices.delete.many'),
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
            'notices_introduction.name'       => [
                'title'    => __('eh-introduction.title'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getNames',
            ],
            'notices_campus.code'             => [
                'title'    => "소속",
                'type'     => 'select',
                'validate' => 'required|max:120',
                'callback' => 'getCode',
            ],
            'notices_introduction.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'notices_introduction.created_at' => [
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
        return $this->repository->pluck('notices_introduction.name', 'notices_introduction.id');
    }

    public function getCode()
    {
        return [
            // campus
            'STUDY_ROOM_MODULE_SCREEN_NAME'     => 'Study Room',
            'GENEALOGY_MODULE_SCREEN_NAME'      => '이화계보',
            'OLD_GENEALOGY_MODULE_SCREEN_NAME'  => '지난계보',
            'EVALUATION_MODULE_SCREEN_NAME'     => '강의평가',

            // life
            'FLARE_MODULE_SCREEN_NAME'          => '벼룩시장',
            'JOBS_PART_TIME_MODULE_SCREEN_NAME' => '알바하자',
            'ADS_MODULE_SCREEN_NAME'            => '광고홍보',
            'SHELTER_MODULE_SCREEN_NAME'        => '주거정보',
            'OPEN_SPACE_MODULE_SCREEN_NAME'     => '열린광장',
        ];
    }
}
