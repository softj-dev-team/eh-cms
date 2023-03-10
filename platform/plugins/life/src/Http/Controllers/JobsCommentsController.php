<?php

namespace Botble\Life\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Life\Forms\Jobs\JobsCommentsForm;
use Botble\Life\Http\Requests\Jobs\JobsCommentsRequest;
use Botble\Life\Repositories\Interfaces\Jobs\JobsCommentsInterface;
use Botble\Life\Tables\Jobs\JobsCommentsTable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobsCommentsController extends BaseController
{
    /**
     * @var JobsCommentsInterface
     */
    protected $jobsCommentsRepository;

    /**
     * EventsController constructor.
     * @param JobsCommentsInterface $jobsCommentsRepository
     * @author Sang Nguyen
     */
    public function __construct(JobsCommentsInterface $jobsCommentsRepository)
    {
        $this->jobsCommentsRepository = $jobsCommentsRepository;

    }

    /**
     * Display all events
     * @param CommentsContentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(JobsCommentsTable $table, $id)
    {
        page_title()->setTitle('Life / Part Time Jobs / Comments / List');

        $data = [];
        $data['id'] = $id;
        return $table->renderTable($data);
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder, $id)
    {
        page_title()->setTitle('Life / Part Time Jobst / Comments / Create');

        return $formBuilder->create(JobsCommentsForm::class)->renderForm();
    }

    /**
     * Insert new Events into database
     *
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(JobsCommentsRequest $request, BaseHttpResponse $response, $id)
    {
        $request->merge(['jobs_part_time_id' => $id, 'parents_id' => null]);

        $jobsComments = $this->jobsCommentsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(JOBS_COMMENTS_MODULE_SCREEN_NAME, $request, $jobsComments));

        return $response
            ->setPreviousUrl(route('life.jobs_part_time.comments.list', ['id' => $id]))
            ->setMessage(trans('core/base::notices.create_success_message'));
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
            $jobsComments = $this->jobsCommentsRepository->findOrFail($id);

            $this->jobsCommentsRepository->delete($jobsComments);

            event(new DeletedContentEvent(JOBS_COMMENTS_MODULE_SCREEN_NAME, $request, $jobsComments));

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
            $jobsComments = $this->jobsCommentsRepository->findOrFail($id);
            $this->jobsCommentsRepository->delete($jobsComments);
            event(new DeletedContentEvent(JOBS_COMMENTS_MODULE_SCREEN_NAME, $request, $jobsComments));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
