<?php

namespace Botble\Garden\Http\Controllers\Egarden;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Garden\Forms\CommentsGardenForm;
use Botble\Garden\Forms\Egarden\CommentsEgardenForm;
use Botble\Garden\Http\Requests\CategoriesGardenRequest;
use Botble\Garden\Http\Requests\CommentsGardenRequest;
use Botble\Garden\Http\Requests\Egarden\CommentsEgardenRequest;
use Botble\Garden\Repositories\Interfaces\Egarden\CommentsEgardenInterface;
use Botble\Garden\Tables\CommentsGardenTable;
use Botble\Garden\Tables\Egarden\CommentsEgardenTable;
use Botble\Garden\Tables\GardenTable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsEgardenController extends BaseController
{
    /**
     * @var CommentsEgardenInterface
     */
    protected $commentsEgardenRepository;

    /**
     * GardenController constructor.
     * @param CommentsEgardenInterface $commentsEgardenRepository
     * @author Sang Nguyen
     */
    public function __construct(CommentsEgardenInterface $commentsEgardenRepository)
    {
        $this->commentsEgardenRepository = $commentsEgardenRepository;
    }

    /**
     * Display all gardens
     * @param GardenTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(CommentsEgardenTable $table, $id)
    {
        page_title()->setTitle('Egarden / Comments List');

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
        page_title()->setTitle('Egarden / Comments #' . $id . ' / Create');

        return $formBuilder->create(CommentsEgardenForm::class)->renderForm();
    }

    /**
     * Insert new Garden into database
     *
     * @param CategoriesGardenRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CommentsEgardenRequest $request, BaseHttpResponse $response, $id)
    {
        $request->merge(['egardens_id' => $id, 'parents_id' => null]);
        $commentsEgarden = $this->commentsEgardenRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(COMMENTS_EGARDEN_MODULE_SCREEN_NAME, $request, $commentsEgarden));

        return $response
            ->setPreviousUrl(route('garden.egarden.comments.list', ['id' => $id]))
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
            $commentsEgarden = $this->commentsEgardenRepository->findOrFail($id);

            $this->commentsEgardenRepository->delete($commentsEgarden);

            event(new DeletedContentEvent(COMMENTS_EGARDEN_MODULE_SCREEN_NAME, $request, $commentsEgarden));

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
            $commentsEgarden = $this->commentsEgardenRepository->findOrFail($id);
            $this->commentsEgardenRepository->delete($commentsEgarden);
            event(new DeletedContentEvent(COMMENTS_EGARDEN_MODULE_SCREEN_NAME, $request, $commentsEgarden));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
