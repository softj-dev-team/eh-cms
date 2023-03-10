<?php

namespace Botble\Introduction\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Introduction\Http\Requests\IntroductionRequest;
use Botble\Introduction\Repositories\Interfaces\IntroductionInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Introduction\Tables\IntroductionTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Introduction\Forms\IntroductionForm;
use Botble\Base\Forms\FormBuilder;

class IntroductionController extends BaseController
{
    /**
     * @var IntroductionInterface
     */
    protected $introductionRepository;

    /**
     * IntroductionController constructor.
     * @param IntroductionInterface $introductionRepository
     * @author Sang Nguyen
     */
    public function __construct(IntroductionInterface $introductionRepository)
    {
        $this->introductionRepository = $introductionRepository;
    }

    /**
     * Display all introductions
     * @param IntroductionTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(IntroductionTable $table)
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
        page_title()->setTitle('만들다');

        return $formBuilder->create(IntroductionForm::class)->renderForm();
    }

    /**
     * Insert new Introduction into database
     *
     * @param IntroductionRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(IntroductionRequest $request, BaseHttpResponse $response)
    {
        $introduction = $this->introductionRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(INTRODUCTION_MODULE_SCREEN_NAME, $request, $introduction));

        return $response
            ->setPreviousUrl(route('introduction.list'))
            ->setNextUrl(route('introduction.edit', $introduction->id))
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
        $introduction = $this->introductionRepository->findOrFail($id);

        event(new BeforeEditContentEvent(INTRODUCTION_MODULE_SCREEN_NAME, $request, $introduction));

        page_title()->setTitle('편집하다'. ' #' . $id);

        return $formBuilder->create(IntroductionForm::class, ['model' => $introduction])->renderForm();
    }

    /**
     * @param $id
     * @param IntroductionRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, IntroductionRequest $request, BaseHttpResponse $response)
    {
        $introduction = $this->introductionRepository->findOrFail($id);

        $introduction->fill($request->input());

        $this->introductionRepository->createOrUpdate($introduction);

        event(new UpdatedContentEvent(INTRODUCTION_MODULE_SCREEN_NAME, $request, $introduction));

        return $response
            ->setPreviousUrl(route('introduction.list'))
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
            $introduction = $this->introductionRepository->findOrFail($id);

            $this->introductionRepository->delete($introduction);

            event(new DeletedContentEvent(INTRODUCTION_MODULE_SCREEN_NAME, $request, $introduction));

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
            $introduction = $this->introductionRepository->findOrFail($id);
            $this->introductionRepository->delete($introduction);
            event(new DeletedContentEvent(INTRODUCTION_MODULE_SCREEN_NAME, $request, $introduction));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
