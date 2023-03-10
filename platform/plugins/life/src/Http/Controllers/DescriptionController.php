<?php

namespace Botble\Life\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Life\Forms\DescriptionForm;
use Botble\Life\Forms\NoticesForm;
use Botble\Life\Http\Requests\DescriptionRequest;
use Botble\Life\Http\Requests\LifeRequest;
use Botble\Life\Http\Requests\NoticesRequest;
use Botble\Life\Repositories\Interfaces\DescriptionInterface;
use Botble\Life\Repositories\Interfaces\NoticesInterface;
use Botble\Life\Tables\DescriptionTable;
use Botble\Life\Tables\LifeTable;
use Botble\Life\Tables\NoticesTable;
use Exception;
use Illuminate\Http\Request;

class DescriptionController extends BaseController
{
    /**
     * @var DescriptionInterface
     */
    protected $descriptionRepository;

    /**
     * DescriptionController constructor.
     * @param DescriptionInterface $descriptionRepository
     * @author Sang Nguyen
     */
    public function __construct(DescriptionInterface $descriptionRepository)
    {
        $this->descriptionRepository = $descriptionRepository;
    }

    /**
     * Display all lives
     * @param DescriptionController $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(DescriptionTable $table)
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
        page_title()->setTitle("새 설명");

        return $formBuilder->create(DescriptionForm::class)->renderForm();
    }

    /**
     * Insert new Life into database
     *
     * @param LifeRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(DescriptionRequest $request, BaseHttpResponse $response)
    {

        $description = $this->descriptionRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(DESCRIPTION_MODULE_SCREEN_NAME, $request, $description));

        return $response
            ->setPreviousUrl(route('life.description.list'))
            ->setNextUrl(route('life.description.edit', $description->id))
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
        $description = $this->descriptionRepository->findOrFail($id);

        event(new BeforeEditContentEvent(DESCRIPTION_MODULE_SCREEN_NAME, $request, $description));

        page_title()->setTitle('수정' . ' #' . $id);

        return $formBuilder->create(DescriptionForm::class, ['model' => $description])->renderForm();
    }

    /**
     * @param $id
     * @param LifeRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, DescriptionRequest $request, BaseHttpResponse $response)
    {

        $description = $this->descriptionRepository->findOrFail($id);

        $description->fill($request->input());

        $this->descriptionRepository->createOrUpdate($description);

        event(new UpdatedContentEvent(DESCRIPTION_MODULE_SCREEN_NAME, $request, $description));

        return $response
            ->setPreviousUrl(route('life.description.list'))
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
            $description = $this->descriptionRepository->findOrFail($id);

            $this->descriptionRepository->delete($description);

            event(new DeletedContentEvent(DESCRIPTION_MODULE_SCREEN_NAME, $request, $description));

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
            $description = $this->descriptionRepository->findOrFail($id);
            $this->descriptionRepository->delete($description);
            event(new DeletedContentEvent(DESCRIPTION_MODULE_SCREEN_NAME, $request, $description));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
