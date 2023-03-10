<?php

namespace Botble\Life\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Life\Http\Requests\LifeRequest;
use Botble\Life\Repositories\Interfaces\LifeInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Life\Tables\LifeTable;
use Botble\Life\Tables\FlareTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Life\Forms\LifeForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Life\Forms\FlareCategoriesForm;
use Botble\Life\Http\Requests\FlareCategoriesRequest;
use Botble\Life\Repositories\Interfaces\FlareCategoriesInterface;
use Botble\Life\Tables\FlareCategoriesTable;

class FlareCategoriesController extends BaseController
{
    /**
     * @var FlareCategoriesInterface
     */
    protected $flareCategoriesRepository;

    /**
     * FlareCategoriesController constructor.
     * @param FlareCategoriesInterface $lifeRepository
     */
    public function __construct(FlareCategoriesInterface $flareCategoriesRepository)
    {
        $this->flareCategoriesRepository = $flareCategoriesRepository;
    }

    /**
     * Display all lives
     * @param LifeTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(FlareCategoriesTable $table)
    {

        page_title()->setTitle('카테고리');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        \Assets::addScriptsDirectly(['/vendor/core/plugins/life/js/jscolor.js']);
        page_title()->setTitle(__('life.flea_market.writer'));

        return $formBuilder->create(FlareCategoriesForm::class)->renderForm();
    }

    /**
     * Insert new Life into database
     *
     * @param FlareCategoriesRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(FlareCategoriesRequest $request, BaseHttpResponse $response)
    {
        if($request->parent_id == 0) {
            $request->merge(['parent_id' => null ]);
        }
        $flareCategories = $this->flareCategoriesRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FLARE_CATEGORIES_MODULE_SCREEN_NAME, $request, $flareCategories));

        return $response
            ->setPreviousUrl(route('life.flare.categories.list'))
            ->setNextUrl(route('life.flare.categories.edit', $flareCategories->id))
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
        \Assets::addScriptsDirectly(['/vendor/core/plugins/life/js/jscolor.js']);
        $flareCategories = $this->flareCategoriesRepository->findOrFail($id);

        event(new BeforeEditContentEvent(FLARE_CATEGORIES_MODULE_SCREEN_NAME, $request, $flareCategories));

        page_title()->setTitle(__('life.flea_market.categories') . ' #' . $id);

        return $formBuilder->create(FlareCategoriesForm::class, ['model' => $flareCategories])->renderForm();
    }

    /**
     * @param $id
     * @param FlareCategoriesRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, FlareCategoriesRequest $request, BaseHttpResponse $response)
    {
        if($request->parent_id == 0) {
            $request->merge(['parent_id' => null ]);
        }
        $flareCategories = $this->flareCategoriesRepository->findOrFail($id);

        $flareCategories->fill($request->input());

        $this->flareCategoriesRepository->createOrUpdate($flareCategories);

        event(new UpdatedContentEvent(FLARE_CATEGORIES_MODULE_SCREEN_NAME, $request, $flareCategories));

        return $response
            ->setPreviousUrl(route('life.flare.categories.list'))
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
            $flareCategories = $this->flareCategoriesRepository->findOrFail($id);

            $this->flareCategoriesRepository->delete($flareCategories);

            event(new DeletedContentEvent(FLARE_CATEGORIES_MODULE_SCREEN_NAME, $request, $flareCategories));

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
            $flareCategories = $this->flareCategoriesRepository->findOrFail($id);
            $this->flareCategoriesRepository->delete($flareCategories);
            event(new DeletedContentEvent(FLARE_CATEGORIES_MODULE_SCREEN_NAME, $request, $flareCategories));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
