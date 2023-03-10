<?php

namespace Botble\Campus\Http\Controllers\StudyRoom;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Campus\Http\Requests\CampusRequest;
use Botble\Campus\Repositories\Interfaces\CampusInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Campus\Tables\CampusTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Campus\Forms\CampusForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Campus\Forms\StudyRoom\StudyRoomCommentsForm;
use Botble\Campus\Http\Requests\StudyRoom\StudyRoomCommentsRequest;
use Botble\Campus\Repositories\Interfaces\StudyRoom\StudyRoomCommentsInterface;
use Botble\Campus\Tables\StudyRoom\StudyRoomCommentsTable;
use Illuminate\Support\Facades\Auth;

class StudyRoomCommentsController extends BaseController
{
     /**
     * @var ShelterCommentsInterface
     */
    protected $studyRoomCommentsRepository;

    /**
     * EventsController constructor.
     * @param StudyRoomCommentsInterface $jobsCommentsRepository
     * @author Sang Nguyen
     */
    public function __construct(StudyRoomCommentsInterface $studyRoomCommentsRepository)
    {
        $this->studyRoomCommentsRepository = $studyRoomCommentsRepository;
    }

    /**
     * Display all events
     * @param ShelterCommentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(StudyRoomCommentsTable $table, $id)
    {
        page_title()->setTitle('교정/공부방/코멘트 #'.$id);

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
        page_title()->setTitle('교정/공부방/코멘트 #'.$id.'/만들다');


        return $formBuilder->create(StudyRoomCommentsForm::class)->renderForm();
    }

    /**
     * Insert new Events into database
     *
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(StudyRoomCommentsRequest $request, BaseHttpResponse $response, $id)
    {
        $request->merge(['study_room_id' => $id, 'parents_id' => null]);

        $studyRoomComments = $this->studyRoomCommentsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(STUDY_ROOM_COMMENTS_MODULE_SCREEN_NAME, $request, $studyRoomComments));

        return $response
            ->setPreviousUrl(route('campus.study_room.comments.list', ['id' => $id]))
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
            $studyRoomComments = $this->studyRoomCommentsRepository->findOrFail($id);

            $this->studyRoomCommentsRepository->delete($studyRoomComments);

            event(new DeletedContentEvent(STUDY_ROOM_COMMENTS_MODULE_SCREEN_NAME, $request, $studyRoomComments));

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
            $studyRoomComments = $this->studyRoomCommentsRepository->findOrFail($id);
            $this->studyRoomCommentsRepository->delete($studyRoomComments);
            event(new DeletedContentEvent(STUDY_ROOM_COMMENTS_MODULE_SCREEN_NAME, $request, $studyRoomComments));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
