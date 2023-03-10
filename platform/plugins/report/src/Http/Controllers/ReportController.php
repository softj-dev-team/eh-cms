<?php

namespace Botble\Report\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Report\Http\Requests\ReportRequest;
use Botble\Report\Repositories\Interfaces\ReportInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Report\Tables\ReportTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Report\Forms\ReportForm;
use Botble\Base\Forms\FormBuilder;
use Assets;

class ReportController extends BaseController
{
    /**
     * @var ReportInterface
     */
    protected $reportRepository;

    /**
     * ReportController constructor.
     * @param ReportInterface $reportRepository
     * @author Sang Nguyen
     */
    public function __construct(ReportInterface $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    /**
     * Display all reports
     * @param ReportTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(ReportTable $table)
    {

        Assets::addScriptsDirectly(['/vendor/core/plugins/member/js/hide_bulk.js']);
        Assets::addStylesDirectly(['/vendor/core/plugins/member/css/hide_bulk.css']);
        page_title()->setTitle('목록');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle(__('life.flea_market.create'));

        return $formBuilder->create(ReportForm::class)->renderForm();
    }

    /**
     * Insert new Report into database
     *
     * @param ReportRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(ReportRequest $request, BaseHttpResponse $response)
    {
        $report = $this->reportRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(REPORT_MODULE_SCREEN_NAME, $request, $report));

        return $response
            ->setPreviousUrl(route('report.list'))
            ->setNextUrl(route('report.edit', $report->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * Show edit form
     *
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getEdit($id, FormBuilder $formBuilder, Request $request)
    {
        $report = $this->reportRepository->findOrFail($id);

        event(new BeforeEditContentEvent(REPORT_MODULE_SCREEN_NAME, $request, $report));

        page_title()->setTitle(__('comments.edit') . ' #' . $id);

        return $formBuilder->create(ReportForm::class, ['model' => $report])->renderForm();
    }


    public function postEdit($id, Request $request, BaseHttpResponse $response)
    {
        $report = $this->reportRepository->findOrFail($id);

        $comment = $report->getComment($report->type_post , $report->id_post);

        $comment->status= 'pending';

        $comment->save();

        $this->reportRepository->createOrUpdate($report);

        event(new UpdatedContentEvent(REPORT_MODULE_SCREEN_NAME, $request, $report));

        return $response
            ->setPreviousUrl(route('report.edit', $report->id))
            ->setMessage('Pending comment success');
    }

    /**
     * @param $id
     * @param Request $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function getDelete(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $report = $this->reportRepository->findOrFail($id);

            $this->reportRepository->delete($report);

            event(new DeletedContentEvent(REPORT_MODULE_SCREEN_NAME, $request, $report));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.cannot_delete'));
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     * @throws Exception
     */
    public function postDeleteMany(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $report = $this->reportRepository->findOrFail($id);
            $this->reportRepository->delete($report);
            event(new DeletedContentEvent(REPORT_MODULE_SCREEN_NAME, $request, $report));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
