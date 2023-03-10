<?php

namespace Botble\Introduction\Http\Controllers\Notices;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Introduction\Forms\Notices\NoticesIntroductionForm;
use Botble\Introduction\Http\Requests\IntroductionRequest;
use Botble\Introduction\Http\Requests\Notices\NoticesIntroductionRequest;
use Botble\Introduction\Repositories\Interfaces\IntroductionInterface;
use Botble\Introduction\Repositories\Interfaces\Notices\NoticesIntroductionInterface;
use Botble\Introduction\Tables\IntroductionTable;
use Botble\Introduction\Tables\Notices\NoticesIntroductionTable;
use Exception;
use Illuminate\Http\Request;

class NoticesIntroductionController extends BaseController
{
    /**
     * @var IntroductionInterface
     */
    protected $noticesIntroductionRepository;

    /**
     * IntroductionController constructor.
     * @param NoticesIntroductionInterface $noticesIntroductionRepository
     * @author Sang Nguyen
     */
    public function __construct(NoticesIntroductionInterface $noticesIntroductionRepository)
    {
        $this->noticesIntroductionRepository = $noticesIntroductionRepository;
    }

    /**
     * Display all introductions
     * @param IntroductionTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(NoticesIntroductionTable $table)
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

        return $formBuilder->create(NoticesIntroductionForm::class)->renderForm();
    }

    /**
     * Insert new Introduction into database
     *
     * @param NoticesIntroductionRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(NoticesIntroductionRequest $request, BaseHttpResponse $response)
    {
        $dataCreate = $request->input();
        $dataCreate['code'] = $request->code;
        $dataCreate['allow_comment'] = $request->allow_comment ? 1 : 0;

        $notices = $this->noticesIntroductionRepository->createOrUpdate($dataCreate);

        event(new CreatedContentEvent(NOTICES_INTRODUCTION_MODULE_SCREEN_NAME, $request, $notices));

        return $response
            ->setPreviousUrl(route('introduction.notices.list'))
            ->setNextUrl(route('introduction.notices.edit', $notices->id))
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
        $notices = $this->noticesIntroductionRepository->findOrFail($id);

        event(new BeforeEditContentEvent(NOTICES_INTRODUCTION_MODULE_SCREEN_NAME, $request, $notices));

        page_title()->setTitle('공지 수정' . ' #' . $id);

        return $formBuilder->create(NoticesIntroductionForm::class, ['model' => $notices])->renderForm();
    }

    /**
     * @param $id
     * @param IntroductionRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, NoticesIntroductionRequest $request, BaseHttpResponse $response)
    {
        $notices = $this->noticesIntroductionRepository->findOrFail($id);

        $dataEdit = $request->input();
        $dataEdit['code'] = $request->code;
        $dataEdit['allow_comment'] = $request->allow_comment ? 1 : 0;

        $notices->fill($dataEdit);

        $this->noticesIntroductionRepository->createOrUpdate($notices);

        event(new UpdatedContentEvent(NOTICES_INTRODUCTION_MODULE_SCREEN_NAME, $request, $notices));

        return $response
            ->setPreviousUrl(route('introduction.notices.list'))
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
            $notices = $this->noticesIntroductionRepository->findOrFail($id);

            $this->noticesIntroductionRepository->delete($notices);

            event(new DeletedContentEvent(NOTICES_INTRODUCTION_MODULE_SCREEN_NAME, $request, $notices));

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
            $notices = $this->noticesIntroductionRepository->findOrFail($id);
            $this->noticesIntroductionRepository->delete($notices);
            event(new DeletedContentEvent(NOTICES_INTRODUCTION_MODULE_SCREEN_NAME, $request, $notices));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
