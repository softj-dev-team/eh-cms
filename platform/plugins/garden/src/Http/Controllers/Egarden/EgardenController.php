<?php

namespace Botble\Garden\Http\Controllers\Egarden;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Garden\Forms\Egarden\EgardenForm;
use Botble\Garden\Forms\GardenForm;
use Botble\Garden\Http\Requests\GardenRequest;
use Botble\Garden\Repositories\Interfaces\Egarden\EgardenInterface;
use Botble\Garden\Repositories\Interfaces\GardenInterface;
use Botble\Garden\Tables\Egarden\EgardenTable;
use Botble\Garden\Tables\GardenTable;
use Exception;
use Illuminate\Http\Request;

class EgardenController extends BaseController
{
    /**
     * @var EgardenInterface
     */
    protected $egardenRepository;

    /**
     * GardenController constructor.
     * @param EgardenInterface $egardenRepository
     * @author Sang Nguyen
     */
    public function __construct(EgardenInterface $egardenRepository)
    {
        $this->egardenRepository = $egardenRepository;
    }

    /**
     * Display all gardens
     * @param EgardenTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(EgardenTable $table)
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
        page_title()->setTitle(__('egarden.write'));

        return $formBuilder->create(EgardenForm::class)->renderForm();
    }

    /**
     * Insert new Garden into database
     *
     * @param GardenRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(GardenRequest $request, BaseHttpResponse $response)
    {
        if (is_null($request->input('right_click'))) {
            $request->merge(['right_click' => 0]);
        }
        if (is_null($request->input('active_empathy'))) {
            $request->merge(['active_empathy' => 0]);
        }

        $egarden = $this->egardenRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(EGARDEN_MODULE_SCREEN_NAME, $request, $egarden));

        return $response
            ->setPreviousUrl(route('garden.egarden.list'))
            ->setNextUrl(route('garden.egarden.edit', $egarden->id))
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
        $egarden = $this->egardenRepository->findOrFail($id);

        event(new BeforeEditContentEvent(EGARDEN_MODULE_SCREEN_NAME, $request, $egarden));

        page_title()->setTitle(__('egarden.edit_egarden') . ' #' . $id);

        return $formBuilder->create(EgardenForm::class, ['model' => $egarden])->renderForm();
    }

    /**
     * @param $id
     * @param GardenRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, GardenRequest $request, BaseHttpResponse $response)
    {
        if (is_null($request->input('right_click'))) {
            $request->merge(['right_click' => 0]);
        }
        if (is_null($request->input('active_empathy'))) {
            $request->merge(['active_empathy' => 0]);
        }
        $egarden = $this->egardenRepository->findOrFail($id);

        $egarden->fill($request->input());

        $this->egardenRepository->createOrUpdate($egarden);

        event(new UpdatedContentEvent(EGARDEN_MODULE_SCREEN_NAME, $request, $egarden));

        return $response
            ->setPreviousUrl(route('garden.egarden.list'))
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
            $egarden = $this->egardenRepository->findOrFail($id);

            $this->egardenRepository->delete($egarden);

            event(new DeletedContentEvent(EGARDEN_MODULE_SCREEN_NAME, $request, $egarden));

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
            $egarden = $this->egardenRepository->findOrFail($id);
            $this->egardenRepository->delete($egarden);
            event(new DeletedContentEvent(EGARDEN_MODULE_SCREEN_NAME, $request, $egarden));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
