<?php

namespace Botble\Slides\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Slides\Http\Requests\SlidesRequest;
use Botble\Slides\Repositories\Interfaces\SlidesInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Slides\Tables\SlidesTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Slides\Forms\SlidesForm;
use Botble\Base\Forms\FormBuilder;
use Carbon\Carbon;

class SlidesController extends BaseController
{
    /**
     * @var SlidesInterface
     */
    protected $slidesRepository;

    /**
     * SlidesController constructor.
     * @param SlidesInterface $slidesRepository
     * @author Sang Nguyen
     */
    public function __construct(SlidesInterface $slidesRepository)
    {
        $this->slidesRepository = $slidesRepository;
    }

    /**
     * Display all slides
     * @param SlidesTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(SlidesTable $table)
    {

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
        page_title()->setTitle(__('event.write'));

        \Assets::addScriptsDirectly(['/vendor/core/plugins/slides/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/slides/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/slides/js/run-datetime.js']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/slides/css/datetimepicker.css']);

        return $formBuilder->create(SlidesForm::class)->renderForm();
    }

    /**
     * Insert new Slides into database
     *
     * @param SlidesRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(SlidesRequest $request, BaseHttpResponse $response)
    {
        $request->validate([
            'name'   => 'required|max:120',
            'code'   => 'required|unique:slides',
        ]);

        $start = Carbon::createFromFormat('Y/m/d h:i a', $request->input('start'))->format('Y-m-d H:i:s');
        $end = Carbon::createFromFormat('Y/m/d h:i a', $request->input('end'))->format('Y-m-d H:i:s');

        $request->merge(['start' => $start]);
        $request->merge(['end' => $end]);

        $request->merge(['code' => strtoupper($request->code) ]);
        $slides = $this->slidesRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(SLIDES_MODULE_SCREEN_NAME, $request, $slides));

        return $response
            ->setPreviousUrl(route('slides.list'))
            ->setNextUrl(route('slides.edit', $slides->id))
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
        $slides = $this->slidesRepository->findOrFail($id);

        event(new BeforeEditContentEvent(SLIDES_MODULE_SCREEN_NAME, $request, $slides));

        page_title()->setTitle(trans('plugins/slides::slides.edit') . ' #' . $id);

        \Assets::addScriptsDirectly(['/vendor/core/plugins/slides/js/moment.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/slides/js/datetimepicker.home.main.min.js']);
        \Assets::addScriptsDirectly(['/vendor/core/plugins/slides/js/run-datetime.js']);
        \Assets::addStylesDirectly(['/vendor/core/plugins/slides/css/datetimepicker.css']);

        return $formBuilder->create(SlidesForm::class, ['model' => $slides])->renderForm();
    }

    /**
     * @param $id
     * @param SlidesRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, SlidesRequest $request, BaseHttpResponse $response)
    {
        $request->validate([
            'name'   => 'required|max:120',
            'code'   => 'required',
        ]);

        $start = Carbon::createFromFormat('Y/m/d h:i a', $request->input('start'))->format('Y-m-d H:i:s');
        $end = Carbon::createFromFormat('Y/m/d h:i a', $request->input('end'))->format('Y-m-d H:i:s');

        $request->merge(['start' => $start]);
        $request->merge(['end' => $end]);

        if ($request->input('is_change_code') == 1) {
            $request->validate([
                'code'   => 'unique:slides',
            ]);

            $request->merge(['code' => strtoupper($request->code) ]);
        } else {
            $request->request->remove('code');
        }

        $slides = $this->slidesRepository->findOrFail($id);

        $slides->fill($request->input());

        $this->slidesRepository->createOrUpdate($slides);

        event(new UpdatedContentEvent(SLIDES_MODULE_SCREEN_NAME, $request, $slides));

        return $response
            ->setPreviousUrl(route('slides.list'))
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
            $slides = $this->slidesRepository->findOrFail($id);

            $this->slidesRepository->delete($slides);

            event(new DeletedContentEvent(SLIDES_MODULE_SCREEN_NAME, $request, $slides));

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
            $slides = $this->slidesRepository->findOrFail($id);
            $this->slidesRepository->delete($slides);
            event(new DeletedContentEvent(SLIDES_MODULE_SCREEN_NAME, $request, $slides));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
