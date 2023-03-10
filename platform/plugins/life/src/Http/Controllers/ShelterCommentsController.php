<?php

namespace Botble\Life\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Life\Forms\Shelter\ShelterCommentsForm;
use Botble\Life\Http\Requests\Jobs\JobsCommentsRequest;
use Botble\Life\Http\Requests\Shelter\ShelterCommentsRequest;
use Illuminate\Support\Facades\Auth;
use Botble\Life\Repositories\Interfaces\Jobs\JobsCommentsInterface;
use Botble\Life\Repositories\Interfaces\Shelter\ShelterCommentsInterface;
use Botble\Life\Tables\Shelter\ShelterCommentsTable;

class ShelterCommentsController extends BaseController
{
    /**
     * @var ShelterCommentsInterface
     */
    protected $shelterCommentsRepository;

    /**
     * EventsController constructor.
     * @param ShelterCommentsInterface $jobsCommentsRepository
     * @author Sang Nguyen
     */
    public function __construct(ShelterCommentsInterface $shelterCommentsRepository)
    {
        $this->shelterCommentsRepository = $shelterCommentsRepository;
    }

    /**
     * Display all events
     * @param ShelterCommentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(ShelterCommentsTable $table, $id)
    {
        page_title()->setTitle('Life / Shelter / Comments / List');

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
        page_title()->setTitle('Life / Shelter / Comments / Create');


        return $formBuilder->create(ShelterCommentsForm::class)->renderForm();
    }

    /**
     * Insert new Events into database
     *
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(ShelterCommentsRequest $request, BaseHttpResponse $response, $id)
    {

        $request->merge(['shelter_id' => $id, 'parents_id' => null]);

        $shelterComments = $this->shelterCommentsRepository->createOrUpdate($request->input());


        event(new CreatedContentEvent(SHELTER_COMMENTS_MODULE_SCREEN_NAME, $request, $shelterComments));

        return $response
            ->setPreviousUrl(route('life.shelter.comments.list', ['id' => $id]))
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
            $shelterComments = $this->shelterCommentsRepository->findOrFail($id);

            $this->shelterCommentsRepository->delete($shelterComments);

            event(new DeletedContentEvent(SHELTER_COMMENTS_MODULE_SCREEN_NAME, $request, $shelterComments));

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
            $shelterComments = $this->shelterCommentsRepository->findOrFail($id);
            $this->jobsCommentsRepository->delete($shelterComments);
            event(new DeletedContentEvent(SHELTER_COMMENTS_MODULE_SCREEN_NAME, $request, $shelterComments));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
