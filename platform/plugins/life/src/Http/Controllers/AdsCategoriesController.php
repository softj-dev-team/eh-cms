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
use Botble\Life\Forms\Ads\AdsCategoriesForm;
use Botble\Life\Forms\Jobs\JobsCategoriesForm;
use Botble\Life\Http\Requests\Ads\AdsCategoriesRequest;
use Botble\Life\Http\Requests\Jobs\JobsCategoriesRequest;
use Botble\Life\Repositories\Interfaces\Ads\AdsCategoriesInterface;
use Botble\Life\Repositories\Interfaces\Jobs\JobsCategoriesInterface;
use Botble\Life\Tables\Ads\AdsCategoriesTable;
use Botble\Life\Tables\Jobs\JobsCategoriesTable;

class AdsCategoriesController extends BaseController
{
    /**
     * @var adsCategoriesInterface
     */
    protected $adsCategoriesRepository;

    /**
     * JobsCategoriesController constructor.
     * @param JobsCategoriesInterface $lifeRepository
     */
    public function __construct(AdsCategoriesInterface $adsCategoriesRepository)
    {
        $this->adsCategoriesRepository = $adsCategoriesRepository;
        
    }

    /**
     * Display all lives
     * @param LifeTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(AdsCategoriesTable $table)
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

        return $formBuilder->create(AdsCategoriesForm::class)->renderForm();
    }

    /**
     * Insert new Life into database
     *
     * @param AdsCategoriesRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(AdsCategoriesRequest $request, BaseHttpResponse $response)
    {
        if($request->parent_id == 0) {
            $request->merge(['parent_id' => null ]);
        } 
        $adsCategories = $this->adsCategoriesRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(JOBS_CATEGORIES_MODULE_SCREEN_NAME, $request, $adsCategories));

        return $response
            ->setPreviousUrl(route('life.advertisements.categories.list'))
            ->setNextUrl(route('life.advertisements.categories.edit', $adsCategories->id))
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
        $adsCategories = $this->adsCategoriesRepository->findOrFail($id);

        event(new BeforeEditContentEvent(ADS_CATEGORIES_MODULE_SCREEN_NAME, $request, $adsCategories));

        page_title()->setTitle("Categories" . ' #' . $id);

        return $formBuilder->create(AdsCategoriesForm::class, ['model' => $adsCategories])->renderForm();
    }

    /**
     * @param $id
     * @param FlareCategoriesRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, AdsCategoriesRequest $request, BaseHttpResponse $response)
    {
       
        $request->merge(['parent_id' => null ]);
        $adsCategories = $this->adsCategoriesRepository->findOrFail($id);

        $adsCategories->fill($request->input());

        $this->adsCategoriesRepository->createOrUpdate($adsCategories);

        event(new UpdatedContentEvent(ADS_CATEGORIES_MODULE_SCREEN_NAME, $request, $adsCategories));

        return $response
            ->setPreviousUrl(route('life.advertisements.categories.list'))
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
            $adsCategories = $this->adsCategoriesRepository->findOrFail($id);

            $this->adsCategoriesRepository->delete($adsCategories);

            event(new DeletedContentEvent(ADS_CATEGORIES_MODULE_SCREEN_NAME, $request, $adsCategories));

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
            $adsCategories = $this->adsCategoriesRepository->findOrFail($id);
            $this->adsCategoriesRepository->delete($adsCategories);
            event(new DeletedContentEvent(ADS_CATEGORIES_MODULE_SCREEN_NAME, $request, $adsCategories));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
