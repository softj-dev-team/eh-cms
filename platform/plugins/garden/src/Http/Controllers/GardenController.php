<?php

namespace Botble\Garden\Http\Controllers;

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
use Carbon\Carbon;

class GardenController extends BaseController
{
    /**
     * @var GardenInterface
     */
    protected $gardenRepository;

    /**
     * GardenController constructor.
     * @param GardenInterface $gardenRepository
     * @author Sang Nguyen
     */
    public function __construct(GardenInterface $gardenRepository)
    {
        $this->gardenRepository = $gardenRepository;
    }

    /**
     * Display all gardens
     * @param GardenTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(GardenTable $table)
    {
        page_title()->setTitle('목록');

        return $table->renderTable();
    }

    /**
     * manage password gardens
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author BM Phuoc
     * @throws \Throwable
     */
    public function getManagePW()
    {

        page_title()->setTitle('비밀단어 관리');

        return view('plugins.garden::elements.tables.actions.manage_pw');
    }


    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle('글쓰기');

        return $formBuilder->create(GardenForm::class)->renderForm();
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
        if(is_null($request->input('right_click'))){
            $request->merge(['right_click' => 0 ]);
        }
        if(is_null($request->input('active_empathy'))){
            $request->merge(['active_empathy' => 0 ]);
        }
        if($request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $garden = $this->gardenRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(GARDEN_MODULE_SCREEN_NAME, $request, $garden));

        return $response
            ->setPreviousUrl(route('garden.list'))
            ->setNextUrl(route('garden.edit', $garden->id))
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
        $garden = $this->gardenRepository->findOrFail($id);

        event(new BeforeEditContentEvent(GARDEN_MODULE_SCREEN_NAME, $request, $garden));

        page_title()->setTitle(trans('plugins/garden::garden.edit') . ' #' . $id);

        return $formBuilder->create(GardenForm::class, ['model' => $garden])->renderForm();
    }

    /**
     * @param $id
     * @param GardenRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, GardenRequest $request, BaseHttpResponse $response)
    {
        if(is_null($request->input('right_click'))){
            $request->merge(['right_click' => 0 ]);
        }
        if(is_null($request->input('active_empathy'))){
            $request->merge(['active_empathy' => 0 ]);
        }
        $garden = $this->gardenRepository->findOrFail($id);
        if($garden->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }
        $request->merge(['link' => $request->input('link')]);
        $request->merge(['file_upload' => $request->input('file_upload')]);
        $garden->fill($request->input());

        $this->gardenRepository->createOrUpdate($garden);

        event(new UpdatedContentEvent(GARDEN_MODULE_SCREEN_NAME, $request, $garden));

        return $response
            ->setPreviousUrl(route('garden.list'))
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
            $garden = $this->gardenRepository->findOrFail($id);

            $this->gardenRepository->delete($garden);

            event(new DeletedContentEvent(GARDEN_MODULE_SCREEN_NAME, $request, $garden));

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
            $garden = $this->gardenRepository->findOrFail($id);
            $this->gardenRepository->delete($garden);
            event(new DeletedContentEvent(GARDEN_MODULE_SCREEN_NAME, $request, $garden));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
