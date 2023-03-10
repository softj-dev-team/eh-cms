<?php

namespace Botble\Life\Http\Controllers;



use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Life\Forms\Ads\AdsCommentsForm;
use Botble\Life\Http\Requests\Ads\AdsCommentsRequest;
use Botble\Life\Http\Requests\Jobs\JobsCommentsRequest;
use Botble\Life\Repositories\Interfaces\Ads\AdsCommentsInterface;
use Botble\Life\Tables\Ads\AdsCommentsTable;
use Illuminate\Support\Facades\Auth;

class AdsCommentsController extends BaseController
{
    /**
     * @var AdsCommentsInterface
     */
    protected $jobsCommentsRepository;

    /**
     * EventsController constructor.
     * @param AdsCommentsInterface $adsCommentsRepository
     * @author Sang Nguyen
     */
    public function __construct(AdsCommentsInterface $adsCommentsRepository)
    {
        $this->adsCommentsRepository = $adsCommentsRepository;

    }

    /**
     * Display all events
     * @param CommentsContentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(AdsCommentsTable $table,$id)
    {
        page_title()->setTitle('Life / Advertisements / Comments / List');

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
        page_title()->setTitle('Life / Advertisements / Comments / Create');


        return $formBuilder->create(AdsCommentsForm::class)->renderForm();
    }

    /**
     * Insert new Events into database
     *
     * @param EventsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(AdsCommentsRequest $request, BaseHttpResponse $response,$id)
    {
        $request->merge(['advertisements_id'=>$id,'parents_id'=>null ]);

        $adsComments = $this->adsCommentsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(ADS_COMMENTS_MODULE_SCREEN_NAME, $request, $adsComments));

        return $response
            ->setPreviousUrl(route('life.advertisements.comments.list',['id'=>$id]))
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
            $adsComments = $this->adsCommentsRepository->findOrFail($id);

            $this->adsCommentsRepository->delete($adsComments);

            event(new DeletedContentEvent(ADS_COMMENTS_MODULE_SCREEN_NAME, $request, $adsComments));

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
            $adsComments = $this->adsCommentsRepository->findOrFail($id);
            $this->adsCommentsRepository->delete($adsComments);
            event(new DeletedContentEvent(ADS_COMMENTS_MODULE_SCREEN_NAME, $request, $adsComments));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
