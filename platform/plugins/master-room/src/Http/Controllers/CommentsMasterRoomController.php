<?php

namespace Botble\MasterRoom\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Garden\Forms\Egarden\CommentsEgardenForm;
use Botble\Garden\Http\Requests\CategoriesGardenRequest;
use Botble\Garden\Http\Requests\Egarden\CommentsEgardenRequest;
use Botble\Garden\Repositories\Interfaces\Egarden\CommentsEgardenInterface;
use Botble\Garden\Tables\Egarden\CommentsEgardenTable;
use Botble\Garden\Tables\GardenTable;
use Botble\MasterRoom\Forms\CommentsMasterRoomForm;
use Botble\MasterRoom\Http\Requests\CommentsMasterRoomRequest;
use Botble\MasterRoom\Repositories\Interfaces\CommentsMasterRoomInterface;
use Botble\MasterRoom\Tables\CommentsMasterRoomTable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsMasterRoomController extends BaseController
{
    /**
     * @var CommentsMasterRoomInterface
     */
    protected $commentsMasterRoomRepository;

    /**
     * GardenController constructor.
     * @param CommentsMasterRoomInterface $commentsMasterRoomRepository
     * @author Sang Nguyen
     */
    public function __construct(CommentsMasterRoomInterface $commentsMasterRoomRepository)
    {
        $this->commentsMasterRoomRepository = $commentsMasterRoomRepository;
    }

    /**
     * Display all gardens
     * @param GardenTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(CommentsMasterRoomTable $table, $id)
    {
        page_title()->setTitle('마스터룸 #' . $id . '/ 댓글 목록');

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
        page_title()->setTitle('마스터룸 #' . $id . ' / 댓글  / 작성');

        return $formBuilder->create(CommentsMasterRoomForm::class)->renderForm();
    }

    /**
     * Insert new Garden into database
     *
     * @param CategoriesGardenRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CommentsMasterRoomRequest $request, BaseHttpResponse $response, $id)
    {
        $request->merge(['master_rooms_id' => $id, 'parents_id' => null]);
        $commentsMasterRoom = $this->commentsMasterRoomRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(COMMENTS_MASTER_ROOM_MODULE_SCREEN_NAME, $request, $commentsMasterRoom));

        return $response
            ->setPreviousUrl(route('master_room.comments.list', ['id' => $id]))
            ->setNextUrl(route('master_room.comments.list', ['id' => $id]))
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
            $commentsMasterRoom = $this->commentsMasterRoomRepository->findOrFail($id);

            $this->commentsMasterRoomRepository->delete($commentsMasterRoom);

            event(new DeletedContentEvent(COMMENTS_MASTER_ROOM_MODULE_SCREEN_NAME, $request, $commentsMasterRoom));

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
            $commentsMasterRoom = $this->commentsMasterRoomRepository->findOrFail($id);
            $this->commentsMasterRoomRepository->delete($commentsMasterRoom);
            event(new DeletedContentEvent(COMMENTS_MASTER_ROOM_MODULE_SCREEN_NAME, $request, $commentsMasterRoom));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
