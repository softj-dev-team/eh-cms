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
use Botble\Life\Http\Requests\Jobs\JobsCategoriesRequest;
use Botble\Life\Repositories\Interfaces\Jobs\JobsCategoriesInterface;
use Botble\Life\Tables\Jobs\JobsCategoriesTable;

class JobsCategoriesController extends BaseController
{
    /**
     * @var JobsCategoriesInterface
     */
    protected $jobsCategoriesRepository;

    /**
     * JobsCategoriesController constructor.
     * @param JobsCategoriesInterface $lifeRepository
     */
    public function __construct(JobsCategoriesInterface $jobsCategoriesRepository)
    {
        $this->jobsCategoriesRepository = $jobsCategoriesRepository;

    }

    /**
     * Display all lives
     * @param LifeTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(JobsCategoriesTable $table)
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

        return $formBuilder->create(JobsCategoriesForm::class)->renderForm();
    }

    /**
     * Insert new Life into database
     *
     * @param JobsCategoriesRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(JobsCategoriesRequest $request, BaseHttpResponse $response)
    {
        if($request->parent_id == 0) {
            $request->merge(['parent_id' => null ]);
        }
        $jobsCategories = $this->jobsCategoriesRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(JOBS_CATEGORIES_MODULE_SCREEN_NAME, $request, $jobsCategories));

        return $response
            ->setPreviousUrl(route('life.jobs_part_time.categories.list'))
            ->setNextUrl(route('life.jobs_part_time.categories.edit', $jobsCategories->id))
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
        $jobsCategories = $this->jobsCategoriesRepository->findOrFail($id);

        event(new BeforeEditContentEvent(JOBS_CATEGORIES_MODULE_SCREEN_NAME, $request, $jobsCategories));

        page_title()->setTitle("Categories" . ' #' . $id);

        return $formBuilder->create(JobsCategoriesForm::class, ['model' => $jobsCategories])->renderForm();
    }

    /**
     * @param $id
     * @param FlareCategoriesRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, JobsCategoriesRequest $request, BaseHttpResponse $response)
    {

        if($request->parent_id == 0) {
            $request->merge(['parent_id' => null ]);
        } 
        $jobsCategories = $this->jobsCategoriesRepository->findOrFail($id);

        $jobsCategories->fill($request->input());

        $this->jobsCategoriesRepository->createOrUpdate($jobsCategories);

        event(new UpdatedContentEvent(JOBS_CATEGORIES_MODULE_SCREEN_NAME, $request, $jobsCategories));

        return $response
            ->setPreviousUrl(route('life.jobs_part_time.categories.list'))
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
            $jobsCategories = $this->jobsCategoriesRepository->findOrFail($id);

            $this->jobsCategoriesRepository->delete($jobsCategories);

            event(new DeletedContentEvent(JOBS_CATEGORIES_MODULE_SCREEN_NAME, $request, $jobsCategories));

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
            $jobsCategories = $this->jobsCategoriesRepository->findOrFail($id);
            $this->jobsCategoriesRepository->delete($jobsCategories);
            event(new DeletedContentEvent(JOBS_CATEGORIES_MODULE_SCREEN_NAME, $request, $jobsCategories));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
