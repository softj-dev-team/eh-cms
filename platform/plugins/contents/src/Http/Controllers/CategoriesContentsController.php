<?php

namespace Botble\Contents\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Contents\Http\Requests\ContentsRequest;
use Botble\Contents\Repositories\Interfaces\ContentsInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Contents\Tables\ContentsTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Contents\Forms\ContentsForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Contents\Http\Requests\CategoriesContentsRequest;
use Botble\Contents\Repositories\Interfaces\CategoriesContentsInterface;
use Botble\Contents\Tables\CategoriesContentsTable;
use Botble\Contents\Forms\CategoriesContentsForm;

class CategoriesContentsController extends BaseController
{
    /**
     * @var ContentsInterface
     */
    protected $categoriesContentsRepository;

    /**
     * ContentsController constructor.
     * @param ContentsInterface $contentsRepository
     * @author Sang Nguyen
     */
    public function __construct(CategoriesContentsInterface $categoriesContentsRepository)
    {
        $this->categoriesContentsRepository = $categoriesContentsRepository;
    }

    /**
     * Display all contents
     * @param ContentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(CategoriesContentsTable $table)
    {

        page_title()->setTitle(trans('plugins/contents::contents.name').' / '.__('campus.genealogy.categories'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        //trans('plugins/contents::contents.categories.create')
        page_title()->setTitle(trans('plugins/contents::contents.name').' / '.__('campus.genealogy.categories'). '/ '.__('campus.timetable.create'));

        return $formBuilder->create(CategoriesContentsForm::class)->renderForm();
    }

    /**
     * Insert new Contents into database
     *
     * @param ContentsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CategoriesContentsRequest $request, BaseHttpResponse $response)
    {
        $categoriesContents = $this->categoriesContentsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(CATEGORIES_CONTENTS_MODULE_SCREEN_NAME, $request, $categoriesContents));

        return $response
            ->setPreviousUrl(route('contents.categories.list'))
            ->setNextUrl(route('contents.categories.edit', $categoriesContents->id))
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
        $categoriesContents = $this->categoriesContentsRepository->findOrFail($id);

        event(new BeforeEditContentEvent(CATEGORIES_CONTENTS_MODULE_SCREEN_NAME, $request, $categoriesContents));

        page_title()->setTitle('콘텐츠 / 카테고리 / 카테고리 편집' . ' #' . $id);

        return $formBuilder->create(CategoriesContentsForm::class, ['model' => $categoriesContents])->renderForm();
    }

    /**
     * @param $id
     * @param CategoriesContentsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, CategoriesContentsRequest $request, BaseHttpResponse $response)
    {
        $categoriesContents = $this->categoriesContentsRepository->findOrFail($id);

        $categoriesContents->fill($request->input());

        $this->categoriesContentsRepository->createOrUpdate($categoriesContents);

        event(new UpdatedContentEvent(CATEGORIES_CONTENTS_MODULE_SCREEN_NAME, $request, $categoriesContents));

        return $response
            ->setPreviousUrl(route('contents.categories.list'))
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
            $categoriesContents = $this->categoriesContentsRepository->findOrFail($id);

            $this->categoriesContentsRepository->delete($categoriesContents);

            event(new DeletedContentEvent(CATEGORIES_CONTENTS_MODULE_SCREEN_NAME, $request, $categoriesContents));

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
            $contents = $this->contentsRepository->findOrFail($id);
            $this->contentsRepository->delete($contents);
            event(new DeletedContentEvent(CONTENTS_MODULE_SCREEN_NAME, $request, $contents));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
