<?php

namespace Botble\Life\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Life\Tables\LifeTable;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Life\Forms\Jobs\JobsCategoriesForm;
use Botble\Life\Forms\Shelter\ShelterCategoriesForm;
use Botble\Life\Http\Requests\Jobs\JobsCategoriesRequest;
use Botble\Life\Http\Requests\Shelter\ShelterCategoriesRequest;
use Botble\Life\Repositories\Interfaces\Shelter\ShelterCategoriesInterface;
use Botble\Life\Tables\Shelter\ShelterCategoriesTable;

class ShelterCategoriesController extends BaseController
{
    /**
     * @var ShelterCategoriesInterface
     */
    protected $shelterCategoriesRepository;

    /**
     * JobsCategoriesController constructor.
     * @param ShelterCategoriesInterface $lifeRepository
     */
    public function __construct(ShelterCategoriesInterface $shelterCategoriesRepository)
    {
        $this->shelterCategoriesRepository = $shelterCategoriesRepository;
        
    }

    /**
     * Display all lives
     * @param LifeTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(ShelterCategoriesTable $table)
    {

        page_title()->setTitle('Categories');

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
        page_title()->setTitle("New Categories");

        return $formBuilder->create(ShelterCategoriesForm::class)->renderForm();
    }

    /**
     * Insert new Life into database
     *
     * @param JobsCategoriesRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(ShelterCategoriesRequest $request, BaseHttpResponse $response)
    {
        if($request->parent_id == 0) {
            $request->merge(['parent_id' => null ]);
        } 
        $shelterCategories = $this->shelterCategoriesRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(SHELTER_CATEGORIES_MODULE_SCREEN_NAME, $request, $shelterCategories));

        return $response
            ->setPreviousUrl(route('life.shelter.categories.list'))
            ->setNextUrl(route('life.shelter.categories.edit', $shelterCategories->id))
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
        $shelterCategories = $this->shelterCategoriesRepository->findOrFail($id);

        event(new BeforeEditContentEvent(SHELTER_CATEGORIES_MODULE_SCREEN_NAME, $request, $shelterCategories));

        page_title()->setTitle("Categories" . ' #' . $id);

        return $formBuilder->create(ShelterCategoriesForm::class, ['model' => $shelterCategories])->renderForm();
    }

    /**
     * @param $id
     * @param FlareCategoriesRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, ShelterCategoriesRequest $request, BaseHttpResponse $response)
    {
       
        $request->merge(['parent_id' => null ]);
        $shelterCategories = $this->shelterCategoriesRepository->findOrFail($id);

        $shelterCategories->fill($request->input());

        $this->shelterCategoriesRepository->createOrUpdate($shelterCategories);

        event(new UpdatedContentEvent(SHELTER_CATEGORIES_MODULE_SCREEN_NAME, $request, $shelterCategories));

        return $response
            ->setPreviousUrl(route('life.shelter.categories.list'))
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
            $shelterCategories = $this->shelterCategoriesRepository->findOrFail($id);

            $this->shelterCategoriesRepository->delete($shelterCategories);

            event(new DeletedContentEvent(SHELTER_CATEGORIES_MODULE_SCREEN_NAME, $request, $shelterCategories));

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
            $shelterCategories = $this->shelterCategoriesRepository->findOrFail($id);
            $this->shelterCategoriesRepository->delete($shelterCategories);
            event(new DeletedContentEvent(SHELTER_CATEGORIES_MODULE_SCREEN_NAME, $request, $shelterCategories));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
