<?php

namespace Botble\Introduction\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Introduction\Forms\CategoriesIntroductionForm;
use Botble\Introduction\Forms\IntroductionForm;
use Botble\Introduction\Http\Requests\CategoriesIntroductionRequest;
use Botble\Introduction\Http\Requests\IntroductionRequest;
use Botble\Introduction\Repositories\Interfaces\CategoriesIntroductionInterface;
use Botble\Introduction\Repositories\Interfaces\IntroductionInterface;
use Botble\Introduction\Tables\CategoriesIntroductionTable;
use Botble\Introduction\Tables\IntroductionTable;
use Exception;
use Illuminate\Http\Request;

class CategoriesIntroductionController extends BaseController
{
    /**
     * @var CategoriesIntroductionInterface
     */
    protected $categoriesIntroductionRepository;

    /**
     * IntroductionController constructor.
     * @param CategoriesIntroductionInterface $categoriesIntroductionRepository
     * @author Sang Nguyen
     */
    public function __construct(CategoriesIntroductionInterface $categoriesIntroductionRepository)
    {
        $this->categoriesIntroductionRepository = $categoriesIntroductionRepository;
    }

    /**
     * Display all introductions
     * @param IntroductionTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(CategoriesIntroductionTable $table)
    {

        page_title()->setTitle('소개 / 카테고리 / 목록');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle('소개 / 카테고리 / 만들기');

        return $formBuilder->create(CategoriesIntroductionForm::class)->renderForm();
    }

    /**
     * Insert new Introduction into database
     *
     * @param CategoriesIntroductionRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CategoriesIntroductionRequest $request, BaseHttpResponse $response)
    {
        $categoriesIntroduction = $this->categoriesIntroductionRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(CATEGORIES_INTRODUCTION_MODULE_SCREEN_NAME, $request, $categoriesIntroduction));

        return $response
            ->setPreviousUrl(route('introduction.categories.list'))
            ->setNextUrl(route('introduction.categories.edit', $categoriesIntroduction->id))
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
        $categoriesIntroduction = $this->categoriesIntroductionRepository->findOrFail($id);

        event(new BeforeEditContentEvent(INTRODUCTION_MODULE_SCREEN_NAME, $request, $categoriesIntroduction));

        page_title()->setTitle('소개 / 카테고리 / 편집'. ' #' . $id);

        return $formBuilder->create(CategoriesIntroductionForm::class, ['model' => $categoriesIntroduction])->renderForm();
    }

    /**
     * @param $id
     * @param IntroductionRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, CategoriesIntroductionRequest $request, BaseHttpResponse $response)
    {
        $categoriesIntroduction = $this->categoriesIntroductionRepository->findOrFail($id);

        $categoriesIntroduction->fill($request->input());

        $this->categoriesIntroductionRepository->createOrUpdate($categoriesIntroduction);

        event(new UpdatedContentEvent(CATEGORIES_INTRODUCTION_MODULE_SCREEN_NAME, $request, $categoriesIntroduction));

        return $response
            ->setPreviousUrl(route('introduction.categories.list'))
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
            $categoriesIntroduction = $this->categoriesIntroductionRepository->findOrFail($id);

            $this->categoriesIntroductionRepository->delete($categoriesIntroduction);

            event(new DeletedContentEvent(CATEGORIES_INTRODUCTION_MODULE_SCREEN_NAME, $request, $categoriesIntroduction));

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
            $categoriesIntroduction = $this->categoriesIntroductionRepository->findOrFail($id);
            $this->categoriesIntroductionRepository->delete($categoriesIntroduction);
            event(new DeletedContentEvent(CATEGORIES_INTRODUCTION_MODULE_SCREEN_NAME, $request, $categoriesIntroduction));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
