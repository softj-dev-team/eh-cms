<?php

namespace Botble\Introduction\Tables\Faq;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Introduction\Repositories\Interfaces\Faq\FaqIntroductionInterface;
use Botble\Introduction\Repositories\Interfaces\IntroductionInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class FaqIntroductionTable extends TableAbstract
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
     * @param IntroductionInterface $introductionRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, FaqIntroductionInterface $faqIntroductionRepository)
    {
        $this->repository = $faqIntroductionRepository;
        $this->setOption('id', 'table-plugins-faq-introduction');
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
            ->editColumn('question', function ($item) {
                return anchor_link(route('introduction.faq.edit', $item->id), $item->question);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('faq_categories_id', function ($item) {
                return $item->categories->name;
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, FAQ_INTRODUCTION_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('introduction.faq.edit', 'introduction.faq.delete', $item);
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
            'faq_introduction.id',
            'faq_introduction.faq_categories_id',
            'faq_introduction.question',
            'faq_introduction.created_at',
            'faq_introduction.status',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, FAQ_INTRODUCTION_MODULE_SCREEN_NAME));
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
                'name' => 'faq_introduction.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'question' => [
                'name' => 'faq_introduction.question',
                'title' => __('eh-introduction.faqs.question'),
                'class' => 'text-left',
            ],
            'faq_categories_id' => [
                'name' => 'faq_introduction.faq_categories_id',
                'title' => __('eh-introduction.faqs.category'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name' => 'faq_introduction.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'name' => 'faq_introduction.status',
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
            'categories' => [
                'link' => route('introduction.faq.categories.list'),
                'text' => view('plugins.introduction::elements.tables.actions.category')->render(),
            ],
            'create' => [
                'link' => route('introduction.faq.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, FAQ_INTRODUCTION_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href' => route('introduction.faq.delete.many'),
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
            'faq_introduction.status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::arrStatus(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'faq_introduction.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],
        ];
    }

}
