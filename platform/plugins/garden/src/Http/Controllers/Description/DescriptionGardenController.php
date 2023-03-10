<?php

namespace Botble\Garden\Http\Controllers\Description;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Garden\Forms\Description\DescriptionGardenForm;
use Botble\Garden\Forms\Notices\NoticesGardenForm;
use Botble\Garden\Http\Requests\Description\DescriptionGardenRequest;
use Botble\Garden\Http\Requests\GardenRequest;
use Botble\Garden\Http\Requests\Notices\NoticesGardenRequest;
use Botble\Garden\Repositories\Interfaces\Description\DescriptionGardenInterface;
use Botble\Garden\Repositories\Interfaces\GardenInterface;
use Botble\Garden\Tables\Description\DescriptionGardenTable;
use Botble\Garden\Tables\GardenTable;
use Exception;
use Illuminate\Http\Request;

class DescriptionGardenController extends BaseController
{
    /**
     * @var GardenInterface
     */
    protected $descriptionGardenRepository;

    /**
     * GardenController constructor.
     * @param DescriptionGardenInterface $descriptionGardenRepository
     * @author Sang Nguyen
     */
    public function __construct(DescriptionGardenInterface $descriptionGardenRepository)
    {
        $this->descriptionGardenRepository = $descriptionGardenRepository;
    }

    /**
     * Display all gardens
     * @param GardenTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(DescriptionGardenTable $table)
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
        page_title()->setTitle('새 설명');

        return $formBuilder->create(DescriptionGardenForm::class)->renderForm();
    }

    /**
     * Insert new Garden into database
     *
     * @param GardenRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(DescriptionGardenRequest $request, BaseHttpResponse $response)
    {
        $description = $this->descriptionGardenRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(DESCRIPTION_GARDEN_MODULE_SCREEN_NAME, $request, $description));

        return $response
            ->setPreviousUrl(route('garden.description.list'))
            ->setNextUrl(route('garden.description.edit', $description->id))
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
        $description = $this->descriptionGardenRepository->findOrFail($id);

        event(new BeforeEditContentEvent(DESCRIPTION_GARDEN_MODULE_SCREEN_NAME, $request, $description));

        page_title()->setTitle('수정'. ' #' . $id);

        return $formBuilder->create(DescriptionGardenForm::class, ['model' => $description])->renderForm();
    }

    /**
     * @param $id
     * @param GardenRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, DescriptionGardenRequest $request, BaseHttpResponse $response)
    {
        $description = $this->descriptionGardenRepository->findOrFail($id);

        $description->fill($request->input());

        $this->descriptionGardenRepository->createOrUpdate($description);

        event(new UpdatedContentEvent(DESCRIPTION_GARDEN_MODULE_SCREEN_NAME, $request, $description));

        return $response
            ->setPreviousUrl(route('garden.description.list'))
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
            $description = $this->descriptionGardenRepository->findOrFail($id);

            $this->descriptionGardenRepository->delete($description);

            event(new DeletedContentEvent(DESCRIPTION_GARDEN_MODULE_SCREEN_NAME, $request, $description));

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
            $description = $this->descriptionGardenRepository->findOrFail($id);
            $this->descriptionGardenRepository->delete($description);
            event(new DeletedContentEvent(DESCRIPTION_GARDEN_MODULE_SCREEN_NAME, $request, $description));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
