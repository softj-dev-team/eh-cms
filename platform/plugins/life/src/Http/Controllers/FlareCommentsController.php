<?php

namespace Botble\Life\Http\Controllers;



use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Life\Forms\FlareCommentsForm;
use Botble\Life\Http\Requests\FlareCommentsRequest;
use Botble\Life\Models\FlareComments;
use Illuminate\Support\Facades\Auth;

use Botble\Life\Repositories\Interfaces\FlareCommentsInterface;
use Botble\Life\Tables\FlareCommentsTable;

class FlareCommentsController extends BaseController
{
    /**
     * @var FlareCommentsInterface
     */
    protected $flareCommentsRepository;

    /**
     * EventsController constructor.
     * @param EventsInterface $eventsRepository
     * @author Sang Nguyen
     */
    public function __construct(FlareCommentsInterface $flareCommentsRepository)
    {
        $this->flareCommentsRepository = $flareCommentsRepository;

    }

    /**
     * Display all events
     * @param CommentsContentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(FlareCommentsTable $table,$id)
    {
        page_title()->setTitle('Life / Flea Market / Comments / List');

        $data =  [];
        $data['id'] = $id;
        return $table->renderTable($data );
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder,$id)
    {
        page_title()->setTitle('Life / Flea Market / Comments / Create');


        return $formBuilder->create(FlareCommentsForm::class)->renderForm();
    }

    /**
     * Insert new Events into database
     *
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(FlareCommentsRequest $request, BaseHttpResponse $response,$id)
    {
        $request->merge(['flare_id' => $id, 'parents_id' => null]);

        $flareComments = $this->flareCommentsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FLARE_COMMENTS_MODULE_SCREEN_NAME, $request, $flareComments));

        return $response
            ->setPreviousUrl(route('life.flare.comments.list',['id'=>$id]))
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
            $flareComments = $this->flareCommentsRepository->findOrFail($id);

            $this->flareCommentsRepository->delete($flareComments);

            event(new DeletedContentEvent(FLARE_COMMENTS_MODULE_SCREEN_NAME, $request, $flareComments));

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
            $flareComments = $this->flareCommentsRepository->findOrFail($id);
            $this->flareCommentsRepository->delete($flareComments);
            event(new DeletedContentEvent(FLARE_COMMENTS_MODULE_SCREEN_NAME, $request, $flareComments));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
