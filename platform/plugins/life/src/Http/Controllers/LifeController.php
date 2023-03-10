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

class LifeController extends BaseController
{
    /**
     * @var LifeInterface
     */
    protected $lifeRepository;

    /**
     * LifeController constructor.
     * @param LifeInterface $lifeRepository
     * @author Sang Nguyen
     */
    public function __construct(LifeInterface $lifeRepository)
    {
        $this->lifeRepository = $lifeRepository;
    }

    /**
     * Display all lives
     * @param LifeTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(LifeTable $table)
    {

        page_title()->setTitle(trans('plugins/life::life.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/life::life.create'));

        return $formBuilder->create(LifeForm::class)->renderForm();
    }

    /**
     * Insert new Life into database
     *
     * @param LifeRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(LifeRequest $request, BaseHttpResponse $response)
    {
        $life = $this->lifeRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(LIFE_MODULE_SCREEN_NAME, $request, $life));

        return $response
            ->setPreviousUrl(route('life.list'))
            ->setNextUrl(route('life.edit', $life->id))
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
        $life = $this->lifeRepository->findOrFail($id);

        event(new BeforeEditContentEvent(LIFE_MODULE_SCREEN_NAME, $request, $life));

        page_title()->setTitle(trans('plugins/life::life.edit') . ' #' . $id);

        return $formBuilder->create(LifeForm::class, ['model' => $life])->renderForm();
    }

    /**
     * @param $id
     * @param LifeRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, LifeRequest $request, BaseHttpResponse $response)
    {
        $life = $this->lifeRepository->findOrFail($id);

        $life->fill($request->input());

        $this->lifeRepository->createOrUpdate($life);

        event(new UpdatedContentEvent(LIFE_MODULE_SCREEN_NAME, $request, $life));

        return $response
            ->setPreviousUrl(route('life.list'))
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
            $life = $this->lifeRepository->findOrFail($id);

            $this->lifeRepository->delete($life);

            event(new DeletedContentEvent(LIFE_MODULE_SCREEN_NAME, $request, $life));

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
            $life = $this->lifeRepository->findOrFail($id);
            $this->lifeRepository->delete($life);
            event(new DeletedContentEvent(LIFE_MODULE_SCREEN_NAME, $request, $life));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
