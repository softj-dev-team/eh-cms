<?php

namespace Botble\Garden\Tables;

use Botble\Garden\Repositories\Interfaces\EwhaInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class EwhaTable extends TableAbstract
{
    /**
     * @var bool
     */
    protected $hasActions = false;

    protected $hasOperations = false;

    protected $hasCheckbox = false;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    public function __construct(DataTables $table, UrlGenerator $urlGenerator, EwhaInterface $ewhaRepository) {
        $this->repository = $ewhaRepository;
        $this->setOption('id', 'table-plugins-garden');
        parent::__construct($table, $urlGenerator);
    }

    public function ajax() {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('BP_TITLE', function ($item) {
                return anchor_link(route('ewha.detail', $item->id), $item->title);
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, EWHA_MODULE_SCREEN_NAME)
            ->escapeColumns([])
            ->make(true);
    }

    public function query() {
        $model = $this->repository->getModel();
        $query = $model->select([
            'EWHA_BOARD_POST.BP_IDX as id',
            'EWHA_BOARD_POST.BP_TITLE as title',
            'EWHA_BOARD_POST.BP_COUNT',
            'EWHA_BOARD_POST.BP_CONTENT',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, EWHA_MODULE_SCREEN_NAME));
    }

    public function columns() {
        return [
            'id' => [
                'name' => 'EWHA_BOARD_POST.BP_IDX',
                'title' => '글번호',
                'width' => '20px',
            ],
            'BP_TITLE' => [
                'name' => 'EWHA_BOARD_POST.BP_TITLE',
                'title' => '글제목',
                'class' => 'text-left',
            ],
            'BP_COUNT' => [
                'name' => 'EWHA_BOARD_POST.BP_COUNT',
                'title' => '조회수',
                'class' => 'text-left',
            ],
        ];
    }

    public function getTitle() {
        return $this->repository->pluck('EWHA_BOARD_POST.title', 'EWHA_BOARD_POST.BP_IDX');
    }
}
