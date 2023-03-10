<?php

namespace Botble\Campus\Http\Controllers\Evaluation;

use App\Imports\EvaluationImport;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Campus\Forms\Evaluation\EvaluationForm;
use Botble\Campus\Forms\Evaluation\EvaluationImportForm;
use Botble\Campus\Http\Requests\CampusRequest;
use Botble\Campus\Http\Requests\Evaluation\EvaluationRequest;
use Botble\Campus\Repositories\Interfaces\Evaluation\EvaluationInterface;
use Botble\Campus\Tables\Evaluation\EvaluationTable;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EvaluationController extends BaseController
{
    /**
     * @var EvaluationInterface
     */
    protected $evaluationRepository;

    /**
     * CampusController constructor.
     * @param EvaluationInterface $evaluationRepository
     * @author Sang Nguyen
     */
    public function __construct(EvaluationInterface $evaluationRepository)
    {
        $this->evaluationRepository = $evaluationRepository;
    }

    /**
     * Display all campuses
     * @param EvaluationTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(EvaluationTable $table)
    {
        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/jquery.rateyo.css']);

        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/jquery.rateyo.js']);
        page_title()->setTitle("목록");

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {

        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/datetimepicker.css']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/bootstrap-select.min.css']);

        \Assets::addStylesDirectly(['/vendor/core/packages/bootstrap-datepicker/css/bootstrap-datepicker3.min.css']);

        \Assets::addScriptsDirectly(['/vendor/core/packages/bootstrap-datepicker/js/bootstrap-datepicker.min.js']);

        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/run-datetime.js']);

        page_title()->setTitle("신규 강의 등록");

        return $formBuilder->create(EvaluationForm::class)->renderForm();
    }

    /**
     * Insert new Campus into database
     *
     * @param EvaluationRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(EvaluationRequest $request, BaseHttpResponse $response)
    {
        $major = $request->input('major');

        $datetime = $request->input('datetime');

        unset($datetime['#template_day']);
        unset($datetime['#template_from']);
        unset($datetime['#template_to']);

        $request->merge([
            'datetime' => json_encode($datetime),
        ]);
        $evaluation = $this->evaluationRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(EVALUATION_MODULE_SCREEN_NAME, $request, $evaluation));
        $evaluation->major()->sync($major);
        return $response
            ->setPreviousUrl(route('campus.evaluation.list'))
            ->setNextUrl(route('campus.evaluation.edit', $evaluation->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author BM Phuoc
     */
    public function getImport(FormBuilder $formBuilder)
    {

        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/datetimepicker.css']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/bootstrap-select.min.css']);

        \Assets::addStylesDirectly(['/vendor/core/packages/bootstrap-datepicker/css/bootstrap-datepicker3.min.css']);

        \Assets::addScriptsDirectly(['/vendor/core/packages/bootstrap-datepicker/js/bootstrap-datepicker.min.js']);

        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/run-datetime.js']);

        // page_title()->setTitle("Import evaluation");
        page_title()->setTitle("시간표 업로드");

        return $formBuilder->create(EvaluationImportForm::class)->renderForm();
    }

    /**
     * Import data from file excel
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postImport(Request $request, BaseHttpResponse $response)
    {

        if ($request->hasFile('import_file')) {
            Excel::import(new EvaluationImport, $request->file('import_file'));

            return $response
                ->setPreviousUrl(route('campus.evaluation.list'))
                ->setMessage(trans('core/base::notices.import_success_message'));
        }

        return $response
            ->setPreviousUrl(route('campus.evaluation.import'))
            ->setMessage(trans('core/base::notices.import_no_file'));
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

        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/datetimepicker.css']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/campus/css/bootstrap-select.min.css']);

        \Assets::addStylesDirectly(['/vendor/core/packages/bootstrap-datepicker/css/bootstrap-datepicker3.min.css']);

        \Assets::addScriptsDirectly(['/vendor/core/packages/bootstrap-datepicker/js/bootstrap-datepicker.min.js']);

        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/campus/js/run-datetime.js']);

        $evaluation = $this->evaluationRepository->findOrFail($id);

        event(new BeforeEditContentEvent(EVALUATION_MODULE_SCREEN_NAME, $request, $evaluation));

        page_title()->setTitle("수정" . ' #' . $id);

        return $formBuilder->create(EvaluationForm::class, ['model' => $evaluation])->renderForm();
    }

    /**
     * @param $id
     * @param CampusRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, EvaluationRequest $request, BaseHttpResponse $response)
    {
        if ($request->input('is_change_semester') != 1) {
            $request->request->remove('semester');
        }
        if ($request->input('year') == null) {
            $request->merge([
                'year' =>  date('Y')
            ]);
        }
        $major = $request->input('major');
        $datetime = $request->input('datetime');

        unset($datetime['#template_day']);
        unset($datetime['#template_from']);
        unset($datetime['#template_to']);

        $request->merge([
            'datetime' => json_encode($datetime),
        ]);

        $evaluation = $this->evaluationRepository->findOrFail($id);

        $evaluation->fill($request->input());

        $this->evaluationRepository->createOrUpdate($evaluation);

        $evaluation->major()->sync($major);
        event(new UpdatedContentEvent(EVALUATION_MODULE_SCREEN_NAME, $request, $evaluation));

        return $response
            ->setPreviousUrl(route('campus.evaluation.list'))
            ->setMessage(trans('core/base::notices.update_success_message'));
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
            $evaluation = $this->evaluationRepository->findOrFail($id);

            $this->evaluationRepository->delete($evaluation);

            event(new DeletedContentEvent(EVALUATION_MODULE_SCREEN_NAME, $request, $evaluation));

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
            $evaluation = $this->evaluationRepository->findOrFail($id);
            $this->evaluationRepository->delete($evaluation);
            event(new DeletedContentEvent(EVALUATION_MODULE_SCREEN_NAME, $request, $evaluation));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
