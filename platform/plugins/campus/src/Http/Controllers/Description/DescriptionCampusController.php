<?php

namespace Botble\Campus\Http\Controllers\Description;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Campus\Forms\Description\DescriptionCampusForm;
use Botble\Campus\Http\Requests\CampusRequest;
use Botble\Campus\Http\Requests\Description\DescriptionCampusRequest;
use Botble\Campus\Repositories\Interfaces\CampusInterface;
use Botble\Campus\Repositories\Interfaces\Description\DescriptionCampusInterface;
use Botble\Campus\Tables\CampusTable;
use Botble\Campus\Tables\Description\DescriptionCampusTable;
use Exception;
use Illuminate\Http\Request;

class DescriptionCampusController extends BaseController
{
    /**
     * @var DescriptionCampusInterface
     */
    protected $descriptionCampusRepository;

    /**
     * CampusController constructor.
     * @param CampusInterface $descriptionCampusRepository
     * @author Sang Nguyen
     */
    public function __construct(DescriptionCampusInterface $descriptionCampusRepository)
    {
        $this->descriptionCampusRepository = $descriptionCampusRepository;
    }

    /**
     * Display all campuses
     * @param DescriptionCampusTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(DescriptionCampusTable $table)
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

        return $formBuilder->create(DescriptionCampusForm::class)->renderForm();
    }

    /**
     * Insert new Campus into database
     *
     * @param DescriptionCampusRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(DescriptionCampusRequest $request, BaseHttpResponse $response)
    {
        $description = $this->descriptionCampusRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(DESCRIPTION_CAMPUS_MODULE_SCREEN_NAME, $request, $description));

        return $response
            ->setPreviousUrl(route('campus.description.list'))
            ->setNextUrl(route('campus.description.edit', $description->id))
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
        $description = $this->descriptionCampusRepository->findOrFail($id);

        event(new BeforeEditContentEvent(DESCRIPTION_CAMPUS_MODULE_SCREEN_NAME, $request, $description));

        page_title()->setTitle(trans('plugins/campus::campus.edit') . ' #' . $id);

        return $formBuilder->create(DescriptionCampusForm::class, ['model' => $description])->renderForm();
    }

    /**
     * @param $id
     * @param DescriptionCampusRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, DescriptionCampusRequest $request, BaseHttpResponse $response)
    {
        $description = $this->descriptionCampusRepository->findOrFail($id);

        $description->fill($request->input());

        $this->descriptionCampusRepository->createOrUpdate($description);

        event(new UpdatedContentEvent(DESCRIPTION_CAMPUS_MODULE_SCREEN_NAME, $request, $description));

        return $response
            ->setPreviousUrl(route('campus.description.list'))
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
            $description = $this->descriptionCampusRepository->findOrFail($id);

            $this->descriptionCampusRepository->delete($description);

            event(new DeletedContentEvent(DESCRIPTION_CAMPUS_MODULE_SCREEN_NAME, $request, $description));

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
            $description = $this->descriptionCampusRepository->findOrFail($id);
            $this->descriptionCampusRepository->delete($description);
            event(new DeletedContentEvent(DESCRIPTION_CAMPUS_MODULE_SCREEN_NAME, $request, $description));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
