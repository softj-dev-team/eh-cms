<?php

namespace Botble\Member\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Campus\Forms\CampusForm;
use Botble\Member\Forms\ForbiddenKeywordsForm;
use Botble\Member\Http\Requests\ForbiddenKeywordsRequest;
use Botble\Member\Repositories\Interfaces\ForbiddenKeywordsInterface;
use Botble\Member\Tables\ForbiddenTable;
use Exception;
use Illuminate\Http\Request;

class ForbiddenController extends BaseController
{
    /**
     * @var ForbiddenKeywordsInterface
     */
    protected $forbiddenRepository;

    /**
     * CampusController constructor.
     * @param ForbiddenKeywordsInterface $forbiddenRepository
     * @author Sang Nguyen
     */
    public function __construct(ForbiddenKeywordsInterface $forbiddenRepository)
    {
        $this->forbiddenRepository = $forbiddenRepository;
    }

    /**
     * Display all campuses
     * @param ForbiddenTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(ForbiddenTable $table)
    {

        page_title()->setTitle("목록");

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle(__('member.create_forbidden_number'));

        return $formBuilder->create(ForbiddenKeywordsForm::class)->renderForm();
    }

    /**
     * Insert new Campus into database
     *
     * @param ForbiddenKeywordsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(ForbiddenKeywordsRequest $request, BaseHttpResponse $response)
    {
        $forbidden = $this->forbiddenRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FORBIDDEN_KEYWORDS_MODULE_SCREEN_NAME, $request, $forbidden));

        return $response
            ->setPreviousUrl(route('member.forbidden.list'))
            ->setNextUrl(route('member.forbidden.edit', $forbidden->id))
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
        $forbidden = $this->forbiddenRepository->findOrFail($id);

        event(new BeforeEditContentEvent(FORBIDDEN_KEYWORDS_MODULE_SCREEN_NAME, $request, $forbidden));

        page_title()->setTitle('수정' . ' #' . $id);

        return $formBuilder->create(ForbiddenKeywordsForm::class, ['model' => $forbidden])->renderForm();
    }

    /**
     * @param $id
     * @param ForbiddenKeywordsRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, ForbiddenKeywordsRequest $request, BaseHttpResponse $response)
    {
        $forbidden = $this->forbiddenRepository->findOrFail($id);

        $forbidden->fill($request->input());

        $this->forbiddenRepository->createOrUpdate($forbidden);

        event(new UpdatedContentEvent(FORBIDDEN_KEYWORDS_MODULE_SCREEN_NAME, $request, $forbidden));

        return $response
            ->setPreviousUrl(route('member.forbidden.list'))
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
            $forbidden = $this->forbiddenRepository->findOrFail($id);

            $this->forbiddenRepository->delete($forbidden);

            event(new DeletedContentEvent(FORBIDDEN_KEYWORDS_MODULE_SCREEN_NAME, $request, $forbidden));

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
            $forbidden = $this->forbiddenRepository->findOrFail($id);
            $this->forbiddenRepository->delete($forbidden);
            event(new DeletedContentEvent(FORBIDDEN_KEYWORDS_MODULE_SCREEN_NAME, $request, $forbidden));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
