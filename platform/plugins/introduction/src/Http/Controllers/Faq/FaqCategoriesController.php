<?php

namespace Botble\Introduction\Http\Controllers\Faq;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Introduction\Forms\Faq\FaqCategoriesForm;
use Botble\Introduction\Forms\IntroductionForm;
use Botble\Introduction\Http\Requests\Faq\FaqCategoriesRequest;
use Botble\Introduction\Repositories\Interfaces\Faq\FaqCategoriesInterface;
use Botble\Introduction\Tables\Faq\FaqCategoriesTable;
use Botble\Introduction\Tables\IntroductionTable;
use Exception;
use Illuminate\Http\Request;

class FaqCategoriesController extends BaseController
{
    /**
     * @var FaqCategoriesInterface
     */
    protected $faqCategoriesRepository;

    /**
     * FaqCategoriesController constructor.
     * @param FaqCategoriesInterface $categoriesRepository
     * @author Sang Nguyen
     */
    public function __construct(FaqCategoriesInterface $faqCategoriesRepository)
    {
        $this->faqCategoriesRepository = $faqCategoriesRepository;
    }

    /**
     * Display all introductions
     * @param IntroductionTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(FaqCategoriesTable $table)
    {

        page_title()->setTitle("카테고리 FAQ 나열");

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle('소개 / FAQ / 새로운 FAQ');

        return $formBuilder->create(FaqCategoriesForm::class)->renderForm();
    }

    /**
     * Insert new Introduction into database
     *
     * @param FaqCategoriesRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(FaqCategoriesRequest $request, BaseHttpResponse $response)
    {
        $categories = $this->faqCategoriesRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FAQ_CATEGORIES_MODULE_SCREEN_NAME, $request, $categories));

        return $response
            ->setPreviousUrl(route('introduction.faq.categories.list'))
            ->setNextUrl(route('introduction.faq.categories.edit', $categories->id))
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
        $categories = $this->faqCategoriesRepository->findOrFail($id);

        event(new BeforeEditContentEvent(FAQ_CATEGORIES_MODULE_SCREEN_NAME, $request, $categories));

        page_title()->setTitle('카테고리 수정 ' . ' #' . $id);

        return $formBuilder->create(FaqCategoriesForm::class, ['model' => $categories])->renderForm();
    }

    /**
     * @param $id
     * @param FaqCategoriesRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, FaqCategoriesRequest $request, BaseHttpResponse $response)
    {
        $categories = $this->faqCategoriesRepository->findOrFail($id);

        $categories->fill($request->input());

        $this->faqCategoriesRepository->createOrUpdate($categories);

        event(new UpdatedContentEvent(FAQ_CATEGORIES_MODULE_SCREEN_NAME, $request, $categories));

        return $response
            ->setPreviousUrl(route('introduction.faq.categories.list'))
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
            $categories = $this->faqCategoriesRepository->findOrFail($id);

            $this->faqCategoriesRepository->delete($categories);

            event(new DeletedContentEvent(FAQ_CATEGORIES_MODULE_SCREEN_NAME, $request, $categories));

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
            $categories = $this->faqCategoriesRepository->findOrFail($id);
            $this->faqCategoriesRepository->delete($categories);
            event(new DeletedContentEvent(FAQ_CATEGORIES_MODULE_SCREEN_NAME, $request, $categories));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
