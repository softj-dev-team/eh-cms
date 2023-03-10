<?php

namespace Botble\Life\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Life\Http\Requests\LifeRequest;
use Botble\Life\Repositories\Interfaces\LifeInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Life\Tables\LifeTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Life\Forms\LifeForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Life\Forms\NoticesForm;
use Botble\Life\Http\Requests\NoticesRequest;
use Botble\Life\Repositories\Interfaces\NoticesInterface;
use Botble\Life\Tables\NoticesTable;

class NoticesController extends BaseController
{
    /**
     * @var NoticesInterface
     */
    protected $noticesRepository;

    /**
     * LifeController constructor.
     * @param NoticesInterface $noticesRepository
     * @author Sang Nguyen
     */
    public function __construct(NoticesInterface $noticesRepository)
    {
        $this->noticesRepository = $noticesRepository;
    }

    /**
     * Display all lives
     * @param LifeTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(NoticesTable $table)
    {
        //trans('plugins/life::life.name')
        page_title()->setTitle('목록');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle('새 설명');

        return $formBuilder->create(NoticesForm::class)->renderForm();
    }

    /**
     * Insert new Life into database
     *
     * @param LifeRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(NoticesRequest $request, BaseHttpResponse $response)
    {
        $notices = $this->noticesRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(NOTICES_MODULE_SCREEN_NAME, $request, $notices));

        return $response
            ->setPreviousUrl(route('life.notices.list'))
            ->setNextUrl(route('life.notices.edit', $notices->id))
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
        $notices = $this->noticesRepository->findOrFail($id);

        event(new BeforeEditContentEvent(NOTICES_MODULE_SCREEN_NAME, $request, $notices));

        page_title()->setTitle('수정' . ' #' . $id);

        return $formBuilder->create(NoticesForm::class, ['model' => $notices])->renderForm();
    }

    /**
     * @param $id
     * @param LifeRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, NoticesRequest $request, BaseHttpResponse $response)
    {

        $notices = $this->noticesRepository->findOrFail($id);

        $notices->fill($request->input());

        $this->noticesRepository->createOrUpdate($notices);

        event(new UpdatedContentEvent(NOTICES_MODULE_SCREEN_NAME, $request, $notices));

        return $response
            ->setPreviousUrl(route('life.notices.list'))
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
            $notices = $this->noticesRepository->findOrFail($id);

            $this->noticesRepository->delete($notices);

            event(new DeletedContentEvent(NOTICES_MODULE_SCREEN_NAME, $request, $notices));

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
            $notices = $this->noticesRepository->findOrFail($id);
            $this->noticesRepository->delete($notices);
            event(new DeletedContentEvent(NOTICES_MODULE_SCREEN_NAME, $request, $notices));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
