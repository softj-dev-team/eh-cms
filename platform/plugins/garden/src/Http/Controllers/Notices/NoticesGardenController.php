<?php

namespace Botble\Garden\Http\Controllers\Notices;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Garden\Http\Requests\GardenRequest;
use Botble\Garden\Repositories\Interfaces\GardenInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Garden\Tables\GardenTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Garden\Forms\GardenForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Garden\Forms\Notices\NoticesGardenForm;
use Botble\Garden\Http\Requests\Notices\NoticesGardenRequest;
use Botble\Garden\Repositories\Interfaces\Notices\NoticesGardenInterface;
use Botble\Garden\Tables\Notices\NoticesGardenTable;

class NoticesGardenController extends BaseController
{
    /**
     * @var GardenInterface
     */
    protected $noticesGardenRepository;

    /**
     * GardenController constructor.
     * @param NoticesGardenInterface $noticesGardenRepository
     * @author Sang Nguyen
     */
    public function __construct(NoticesGardenInterface $noticesGardenRepository)
    {
        $this->noticesGardenRepository = $noticesGardenRepository;
    }

    /**
     * Display all gardens
     * @param GardenTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(NoticesGardenTable $table)
    {

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
        page_title()->setTitle('새 공지사항');

        return $formBuilder->create(NoticesGardenForm::class)->renderForm();
    }

    /**
     * Insert new Garden into database
     *
     * @param GardenRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(NoticesGardenRequest $request, BaseHttpResponse $response)
    {

        $notices = $this->noticesGardenRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(NOTICES_GARDEN_MODULE_SCREEN_NAME, $request, $notices));

        return $response
            ->setPreviousUrl(route('garden.notices.list'))
            ->setNextUrl(route('garden.notices.edit', $notices->id))
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
        $notices = $this->noticesGardenRepository->findOrFail($id);

        event(new BeforeEditContentEvent(NOTICES_GARDEN_MODULE_SCREEN_NAME, $request, $notices));

        page_title()->setTitle('수정' . ' #' . $id);

        return $formBuilder->create(NoticesGardenForm::class, ['model' => $notices])->renderForm();
    }

    /**
     * @param $id
     * @param GardenRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, NoticesGardenRequest $request, BaseHttpResponse $response)
    {
        $notices = $this->noticesGardenRepository->findOrFail($id);

        $notices->fill($request->input());

        $this->noticesGardenRepository->createOrUpdate($notices);

        event(new UpdatedContentEvent(NOTICES_GARDEN_MODULE_SCREEN_NAME, $request, $notices));

        return $response
            ->setPreviousUrl(route('garden.notices.list'))
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
            $notices = $this->noticesGardenRepository->findOrFail($id);

            $this->noticesGardenRepository->delete($notices);

            event(new DeletedContentEvent(NOTICES_GARDEN_MODULE_SCREEN_NAME, $request, $notices));

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
            $notices = $this->noticesGardenRepository->findOrFail($id);
            $this->noticesGardenRepository->delete($notices);
            event(new DeletedContentEvent(NOTICES_GARDEN_MODULE_SCREEN_NAME, $request, $notices));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
