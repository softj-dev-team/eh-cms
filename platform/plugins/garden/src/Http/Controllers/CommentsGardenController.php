<?php

namespace Botble\Garden\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Garden\Repositories\Interfaces\CategoriesGardenInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Garden\Tables\GardenTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Garden\Forms\CategoriesGardenForm;
use Botble\Garden\Forms\CommentsGardenForm;
use Botble\Garden\Http\Requests\CategoriesGardenRequest;
use Botble\Garden\Http\Requests\CommentsGardenRequest;
use Botble\Garden\Repositories\Interfaces\CommentsGardenInterface;
use Botble\Garden\Tables\CommentsGardenTable;
use Illuminate\Support\Facades\Auth;

class CommentsGardenController extends BaseController
{
    /**
     * @var CommentsGardenInterface
     */
    protected $commentsGardenRepository;

    /**
     * GardenController constructor.
     * @param CommentsGardenInterface $commentsGardenRepository
     * @author Sang Nguyen
     */
    public function __construct(CommentsGardenInterface $commentsGardenRepository)
    {
        $this->commentsGardenRepository = $commentsGardenRepository;
    }

    /**
     * Display all gardens
     * @param GardenTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(CommentsGardenTable $table, $id)
    {
        page_title()->setTitle('Garden / Comments List');

        $data =  [];
        $data['id'] = $id;
        return $table->renderTable($data);
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder , $id)
    {
        page_title()->setTitle('Garden / Comments #'.$id.' / Create');


        return $formBuilder->create(CommentsGardenForm::class)->renderForm();
    }

    /**
     * Insert new Garden into database
     *
     * @param CategoriesGardenRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CommentsGardenRequest $request, BaseHttpResponse $response, $id)
    {
        $request->merge(['gardens_id' => $id, 'parents_id' => null]);
        $commentsGarden = $this->commentsGardenRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(COMMENTS_GARDEN_MODULE_SCREEN_NAME, $request, $commentsGarden));

        return $response
            ->setPreviousUrl(route('garden.comments.list',['id'=>$id]))
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
            $commentsGarden = $this->commentsGardenRepository->findOrFail($id);

            $this->commentsGardenRepository->delete($commentsGarden);

            event(new DeletedContentEvent(COMMENTS_GARDEN_MODULE_SCREEN_NAME, $request, $commentsGarden));

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
            $commentsGarden = $this->commentsGardenRepository->findOrFail($id);
            $this->commentsGardenRepository->delete($commentsGarden);
            event(new DeletedContentEvent(COMMENTS_GARDEN_MODULE_SCREEN_NAME, $request, $commentsGarden));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
