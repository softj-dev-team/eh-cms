<?php

namespace Botble\NewContents\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\NewContents\Forms\CategoriesNewContentsForm;
use Botble\NewContents\Forms\NewContentsForm;
use Botble\NewContents\Http\Requests\CategoriesNewContentsRequest;
use Botble\NewContents\Http\Requests\NewContentsRequest;
use Botble\NewContents\Repositories\Interfaces\CategoriesNewContentsInterface;
use Botble\NewContents\Repositories\Interfaces\NewContentsInterface;
use Botble\NewContents\Tables\CategoriesNewContentsTable;
use Botble\NewContents\Tables\NewContentsTable;
use Exception;
use Illuminate\Http\Request;

class CategoriesNewContentsController extends BaseController
{
    /**
     * @var CategoriesNewContentsInterface
     */
    protected $categoriesNewContentsRepository;

    /**
     * NewContentsController constructor.
     * @param CategoriesNewContentsInterface $categoriesNewContentsRepository
     * @author Sang Nguyen
     */
    public function __construct(CategoriesNewContentsInterface $categoriesNewContentsRepository)
    {
        $this->categoriesNewContentsRepository = $categoriesNewContentsRepository;
    }

    /**
     * Display all new_contents
     * @param CategoriesNewContentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(CategoriesNewContentsTable $table)
    {

        page_title()->setTitle('New Contents / Categories / List');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle('New Contents / Categories / Create');

        return $formBuilder->create(CategoriesNewContentsForm::class)->renderForm();
    }

    /**
     * Insert new NewContents into database
     *
     * @param NewContentsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CategoriesNewContentsRequest $request, BaseHttpResponse $response)
    {
        $categoriesNewContents = $this->categoriesNewContentsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(CATEGORIES_NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $categoriesNewContents));

        return $response
            ->setPreviousUrl(route('new_contents.categories.list'))
            ->setNextUrl(route('new_contents.categories.edit', $categoriesNewContents->id))
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
        $categoriesNewContents = $this->categoriesNewContentsRepository->findOrFail($id);

        event(new BeforeEditContentEvent(CATEGORIES_NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $categoriesNewContents));

        page_title()->setTitle('New Contents / Categories / Edit' . ' #' . $id);

        return $formBuilder->create(CategoriesNewContentsForm::class, ['model' => $categoriesNewContents])->renderForm();
    }

    /**
     * @param $id
     * @param CategoriesNewContentsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, CategoriesNewContentsRequest $request, BaseHttpResponse $response)
    {
        $categoriesNewContents = $this->categoriesNewContentsRepository->findOrFail($id);

        $categoriesNewContents->fill($request->input());

        $this->categoriesNewContentsRepository->createOrUpdate($categoriesNewContents);

        event(new UpdatedContentEvent(CATEGORIES_NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $categoriesNewContents));

        return $response
            ->setPreviousUrl(route('new_contents.categories.list'))
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
            $categoriesNewContents = $this->categoriesNewContentsRepository->findOrFail($id);

            $this->categoriesNewContentsRepository->delete($categoriesNewContents);

            event(new DeletedContentEvent(CATEGORIES_NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $categoriesNewContents));

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
            $categoriesNewContents = $this->categoriesNewContentsRepository->findOrFail($id);
            $this->categoriesNewContentsRepository->delete($categoriesNewContents);
            event(new DeletedContentEvent(CATEGORIES_NEW_CONTENTS_MODULE_SCREEN_NAME, $request, $categoriesNewContents));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
