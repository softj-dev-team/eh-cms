<?php

namespace Botble\Contents\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Contents\Models\Contents;
use Botble\Contents\Repositories\Interfaces\ContentsInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class SlideContentsTable extends TableAbstract
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
     * @param ContentsInterface $contentsRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ContentsInterface $contentsRepository)
    {
        $this->repository = $contentsRepository;
        $this->setOption('id', 'table-plugins-contents-slides');
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
                return $item->title;
            })
            ->editColumn('categories', function ($item) {
                return $item->categories_contents->name;
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

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, CONTENTS_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('', 'contents.slides.delete', $item);
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
            'contents.id',
            'contents.title',
            'contents.enrollment_limit',
            'contents.categories_contents_id',
            'contents.status',
            'contents.slide_no',
        ])
            ->orderBy('slide_no', 'DESC');
//        ])->where('is_slides', '>', Contents::IS_NOT_SLIDE)->orderBy('slide_no', 'DESC');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, CONTENTS_MODULE_SCREEN_NAME));
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
                'name' => 'contents.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title' => [
                'name' => 'contents.title',
                'title' => trans('core/base::tables.title'),
                'class' => 'text-left',
            ],
            'slide_no' => [
                'name' => 'contents.slide_no',
                'title' => "슬라이드 번호.",
                'class' => 'text-left',
            ],
            'categories' => [
                'name' => 'contents.categories_contents_id',
                'title' => __('master_room.categories'),
                'class' => 'text-left',
            ],
            'enrollment_limit' => [
                'name' => 'contents.enrollment_limit',
                'title' => __('new_contents.enrollment_limit'),
                'width' => '200px',
            ],
            'status' => [
                'name' => 'contents.status',
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
//            'create' => [
//                'link' => route('contents.slides.create'),
//                'text' => view('core.base::elements.tables.actions.create')->render(),
//            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, CONTENTS_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();
        $actions['delete-many'] = view('core.table::partials.delete', [
            'href' => route('contents.slides.delete.many'),
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
            'contents.is_slides' => [
                'title'    => '슬라이드 번호',
                'type'     => 'number',
                'validate' => 'required|max:120',
//                'callback' => 'getSlideNo',
            ]
        ];
    }



    /**
     * @return array
     */
    public function getTitle()
    {
        return $this->repository->pluck('contents.title', 'contents.id');
    }
    /**
     * @return array
     */
    public function getSlideNo()
    {
        return $this->repository->pluck('contents.slide_no', 'contents.id');
    }

    /**
     * @return array
     */
    public function getAllCategories()
    {
        return $this->categoriesContentsRepository->pluck('categories_contents.name', 'categories_contents.id');
    }
}
