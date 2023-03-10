<?php

namespace Botble\Introduction\Http\Controllers\Faq;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Introduction\Forms\Faq\FaqIntroductionForm;
use Botble\Introduction\Forms\IntroductionForm;
use Botble\Introduction\Http\Requests\Faq\FaqIntroductionRequest;
use Botble\Introduction\Http\Requests\IntroductionRequest;
use Botble\Introduction\Repositories\Interfaces\Faq\FaqIntroductionInterface;
use Botble\Introduction\Repositories\Interfaces\IntroductionInterface;
use Botble\Introduction\Tables\Faq\FaqIntroductionTable;
use Botble\Introduction\Tables\IntroductionTable;
use Exception;
use Illuminate\Http\Request;

class FaqIntroductionController extends BaseController
{
    /**
     * @var FaqIntroductionInterface
     */
    protected $faqIntroductionRepository;

    /**
     * IntroductionController constructor.
     * @param FaqIntroductionInterface $faqIntroductionRepository
     * @author Sang Nguyen
     */
    public function __construct(FaqIntroductionInterface $faqIntroductionRepository)
    {
        $this->faqIntroductionRepository = $faqIntroductionRepository;
    }

    /**
     * Display all introductions
     * @param IntroductionTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(FaqIntroductionTable $table)
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
        page_title()->setTitle('새로운 FAQ');

        return $formBuilder->create(FaqIntroductionForm::class)->renderForm();
    }

    /**
     * Insert new Introduction into database
     *
     * @param FaqIntroductionRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(FaqIntroductionRequest $request, BaseHttpResponse $response)
    {
        $faq = $this->faqIntroductionRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FAQ_INTRODUCTION_MODULE_SCREEN_NAME, $request, $faq));

        return $response
            ->setPreviousUrl(route('introduction.faq.list'))
            ->setNextUrl(route('introduction.faq.edit', $faq->id))
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
        $faq = $this->faqIntroductionRepository->findOrFail($id);

        event(new BeforeEditContentEvent(FAQ_INTRODUCTION_MODULE_SCREEN_NAME, $request, $faq));

        page_title()->setTitle('편집하다 FAQ ' . ' #' . $id);

        return $formBuilder->create(FaqIntroductionForm::class, ['model' => $faq])->renderForm();
    }

    /**
     * @param $id
     * @param FaqIntroductionRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, FaqIntroductionRequest $request, BaseHttpResponse $response)
    {
        $faq = $this->faqIntroductionRepository->findOrFail($id);

        $faq->fill($request->input());

        $this->faqIntroductionRepository->createOrUpdate($faq);

        event(new UpdatedContentEvent(FAQ_INTRODUCTION_MODULE_SCREEN_NAME, $request, $faq));

        return $response
            ->setPreviousUrl(route('introduction.faq.list'))
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
            $faq = $this->faqIntroductionRepository->findOrFail($id);

            $this->faqIntroductionRepository->delete($faq);

            event(new DeletedContentEvent(FAQ_INTRODUCTION_MODULE_SCREEN_NAME, $request, $faq));

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
            $faq = $this->faqIntroductionRepository->findOrFail($id);
            $this->faqIntroductionRepository->delete($faq);
            event(new DeletedContentEvent(FAQ_INTRODUCTION_MODULE_SCREEN_NAME, $request, $faq));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
