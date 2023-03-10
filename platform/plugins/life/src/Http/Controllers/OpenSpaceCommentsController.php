<?php

namespace Botble\Life\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Life\Forms\OpenSpace\OpenSpaceCommentsForm;
use Botble\Life\Forms\Shelter\ShelterCommentsForm;
use Botble\Life\Http\Requests\Jobs\JobsCommentsRequest;
use Botble\Life\Http\Requests\OpenSpace\OpenSpaceCommentsRequest;
use Botble\Life\Http\Requests\Shelter\ShelterCommentsRequest;
use Illuminate\Support\Facades\Auth;
use Botble\Life\Repositories\Interfaces\Jobs\JobsCommentsInterface;
use Botble\Life\Repositories\Interfaces\OpenSpace\OpenSpaceCommentsInterface;
use Botble\Life\Repositories\Interfaces\Shelter\ShelterCommentsInterface;
use Botble\Life\Tables\OpenSpace\OpenSpaceCommentsTable;
use Botble\Life\Tables\Shelter\ShelterCommentsTable;

class OpenSpaceCommentsController extends BaseController
{
    /**
     * @var OpenSpaceCommentsInterface
     */
    protected $openSpaceCommentsRepository;

    /**
     * EventsController constructor.
     * @param OpenSpaceCommentsInterface $jobsCommentsRepository
     * @author Sang Nguyen
     */
    public function __construct(OpenSpaceCommentsInterface $openSpaceCommentsRepository)
    {
        $this->openSpaceCommentsRepository = $openSpaceCommentsRepository;
    }

    /**
     * Display all events
     * @param OpenSpaceCommentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(OpenSpaceCommentsTable $table, $id)
    {
        page_title()->setTitle('Life / Open Space / Comments / List');

        $data =  [];
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
        page_title()->setTitle('Life / Open Space / Comments / Create');


        return $formBuilder->create(OpenSpaceCommentsForm::class)->renderForm();
    }

    /**
     * Insert new Events into database
     *
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(OpenSpaceCommentsRequest $request, BaseHttpResponse $response, $id)
    {

        $request->merge(['open_space_id' => $id]);

        $openSpaceComments = $this->openSpaceCommentsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(OPEN_SPACE_COMMENTS_MODULE_SCREEN_NAME, $request, $openSpaceComments));

        return $response
            ->setPreviousUrl(route('life.open.space.comments.list', ['id' => $id]))
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
            $openSpaceComments = $this->openSpaceCommentsRepository->findOrFail($id);

            $this->openSpaceCommentsRepository->delete($openSpaceComments);

            event(new DeletedContentEvent(OPEN_SPACE_COMMENTS_MODULE_SCREEN_NAME, $request, $openSpaceComments));

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
            $openSpaceComments = $this->openSpaceCommentsRepository->findOrFail($id);
            $this->jobsCommentsRepository->delete($openSpaceComments);
            event(new DeletedContentEvent(OPEN_SPACE_COMMENTS_MODULE_SCREEN_NAME, $request, $openSpaceComments));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
