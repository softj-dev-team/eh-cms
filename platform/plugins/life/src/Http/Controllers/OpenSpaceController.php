<?php

namespace Botble\Life\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Life\Forms\LifeForm;
use Botble\Life\Forms\OpenSpace\OpenSpaceForm;
use Botble\Life\Http\Requests\LifeRequest;
use Botble\Life\Http\Requests\OpenSpace\OpenSpaceRequest;
use Botble\Life\Repositories\Interfaces\LifeInterface;
use Botble\Life\Repositories\Interfaces\OpenSpace\OpenSpaceInterface;
use Botble\Life\Tables\LifeTable;
use Botble\Life\Tables\OpenSpace\OpenSpaceTable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class OpenSpaceController extends BaseController
{
    /**
     * @var LifeInterface
     */
    protected $openSpaceRepository;

    /**
     * LifeController constructor.
     * @param OpenSpaceInterface $openSpaceRepository
     * @author Sang Nguyen
     */
    public function __construct(OpenSpaceInterface $openSpaceRepository)
    {
        $this->openSpaceRepository = $openSpaceRepository;
    }

    /**
     * Display all lives
     * @param LifeTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(OpenSpaceTable $table)
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
        page_title()->setTitle("새 설명");

        return $formBuilder->create(OpenSpaceForm::class)->renderForm();
    }

    /**
     * Insert new Life into database
     *
     * @param LifeRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(OpenSpaceRequest $request, BaseHttpResponse $response)
    {
        if($request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }

        $openSpace = $this->openSpaceRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(OPEN_SPACE_MODULE_SCREEN_NAME, $request, $openSpace));

        return $response
            ->setPreviousUrl(route('life.open.space.list'))
            ->setNextUrl(route('life.open.space.edit', $openSpace->id))
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
        $openSpace = $this->openSpaceRepository->findOrFail($id);

        event(new BeforeEditContentEvent(OPEN_SPACE_MODULE_SCREEN_NAME, $request, $openSpace));

        page_title()->setTitle('수정' . ' #' . $id);

        return $formBuilder->create(OpenSpaceForm::class, ['model' => $openSpace])->renderForm();
    }

    /**
     * @param $id
     * @param OpenSpaceRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, OpenSpaceRequest $request, BaseHttpResponse $response)
    {
        $openSpace = $this->openSpaceRepository->findOrFail($id);
        if($openSpace->published == null && $request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['link' => $request->input('link')]);
        $request->merge(['file_upload' => $request->input('file_upload')]);
        $openSpace->fill($request->input());

        $this->openSpaceRepository->createOrUpdate($openSpace);

        event(new UpdatedContentEvent(OPEN_SPACE_MODULE_SCREEN_NAME, $request, $openSpace));

        return $response
            ->setPreviousUrl(route('life.open.space.list'))
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
            $openSpace = $this->openSpaceRepository->findOrFail($id);

            $this->openSpaceRepository->delete($openSpace);

            event(new DeletedContentEvent(OPEN_SPACE_MODULE_SCREEN_NAME, $request, $openSpace));

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
            $openSpace = $this->openSpaceRepository->findOrFail($id);
            $this->openSpaceRepository->delete($openSpace);
            event(new DeletedContentEvent(OPEN_SPACE_MODULE_SCREEN_NAME, $request, $openSpace));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
