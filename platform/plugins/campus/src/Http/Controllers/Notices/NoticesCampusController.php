<?php

namespace Botble\Campus\Http\Controllers\Notices;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Campus\Forms\CampusForm;
use Botble\Campus\Forms\Notices\NoticesCampusForm;
use Botble\Campus\Http\Requests\CampusRequest;
use Botble\Campus\Http\Requests\Notices\NoticesCampusRequest;
use Botble\Campus\Repositories\Interfaces\CampusInterface;
use Botble\Campus\Repositories\Interfaces\Notices\NoticesCampusInterface;
use Botble\Campus\Tables\CampusTable;
use Botble\Campus\Tables\Notices\NoticesCampusTable;
use Exception;
use Illuminate\Http\Request;

class NoticesCampusController extends BaseController
{
    /**
     * @var NoticesCampusInterface
     */
    protected $noticesCampusRepository;

    /**
     * CampusController constructor.
     * @param NoticesCampusInterface $noticesCampusRepository
     * @author Sang Nguyen
     */
    public function __construct(NoticesCampusInterface $noticesCampusRepository)
    {
        $this->noticesCampusRepository = $noticesCampusRepository;
    }

    /**
     * Display all campuses
     * @param CampusTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(NoticesCampusTable $table)
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

        return $formBuilder->create(NoticesCampusForm::class)->renderForm();
    }

    /**
     * Insert new Campus into database
     *
     * @param CampusRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(NoticesCampusRequest $request, BaseHttpResponse $response)
    {
        $notices = $this->noticesCampusRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(NOTICES_CAMPUS_MODULE_SCREEN_NAME, $request, $notices));

        return $response
            ->setPreviousUrl(route('campus.notices.list'))
            ->setNextUrl(route('campus.notices.edit', $notices->id))
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
        $notices = $this->noticesCampusRepository->findOrFail($id);

        event(new BeforeEditContentEvent(NOTICES_CAMPUS_MODULE_SCREEN_NAME, $request, $notices));

        page_title()->setTitle(__('comments.edit'). ' #' . $id);

        return $formBuilder->create(NoticesCampusForm::class, ['model' => $notices])->renderForm();
    }

    /**
     * @param $id
     * @param CampusRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, NoticesCampusRequest $request, BaseHttpResponse $response)
    {
        $notices = $this->noticesCampusRepository->findOrFail($id);

        $notices->fill($request->input());

        $this->noticesCampusRepository->createOrUpdate($notices);

        event(new UpdatedContentEvent(NOTICES_CAMPUS_MODULE_SCREEN_NAME, $request, $notices));

        return $response
            ->setPreviousUrl(route('campus.notices.list'))
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
            $notices = $this->noticesCampusRepository->findOrFail($id);

            $this->noticesCampusRepository->delete($notices);

            event(new DeletedContentEvent(NOTICES_CAMPUS_MODULE_SCREEN_NAME, $request, $notices));

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
            $notices = $this->noticesCampusRepository->findOrFail($id);
            $this->noticesCampusRepository->delete($notices);
            event(new DeletedContentEvent(NOTICES_CAMPUS_MODULE_SCREEN_NAME, $request, $notices));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
