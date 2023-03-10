<?php

namespace Botble\Garden\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Garden\Http\Requests\GardenRequest;
use Botble\Garden\Repositories\Interfaces\CategoriesGardenInterface;
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
use Botble\Garden\Forms\CategoriesGardenForm;
use Botble\Garden\Http\Requests\CategoriesGardenRequest;
use Botble\Garden\Tables\CategoriesGardenTable;

class CategoriesGardenController extends BaseController
{
    /**
     * @var CategoriesGardenInterface
     */
    protected $categoriesGardenRepository;

    /**
     * GardenController constructor.
     * @param CategoriesGardenInterface $categoriesGardenRepository
     * @author Sang Nguyen
     */
    public function __construct(CategoriesGardenInterface $categoriesGardenRepository)
    {
        $this->categoriesGardenRepository = $categoriesGardenRepository;
    }

    /**
     * Display all gardens
     * @param GardenTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(CategoriesGardenTable $table)
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

        return $formBuilder->create(CategoriesGardenForm::class)->renderForm();
    }

    /**
     * Insert new Garden into database
     *
     * @param CategoriesGardenRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CategoriesGardenRequest $request, BaseHttpResponse $response)
    {
        $categoriesGarden = $this->categoriesGardenRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(GARDEN_MODULE_SCREEN_NAME, $request, $categoriesGarden));

        return $response
            ->setPreviousUrl(route('garden.categories.list'))
            ->setNextUrl(route('garden.categories.edit', $categoriesGarden->id))
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
        $categoriesGarden = $this->categoriesGardenRepository->findOrFail($id);

        event(new BeforeEditContentEvent(CATEGORIES_GARDEN_MODULE_SCREEN_NAME, $request, $categoriesGarden));

         //(trans('plugins/garden::garden.edit')
        page_title()->setTitle('수정' . ' #' . $id);

        return $formBuilder->create(CategoriesGardenForm::class, ['model' => $categoriesGarden])->renderForm();
    }

    /**
     * @param $id
     * @param CategoriesGardenRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, CategoriesGardenRequest $request, BaseHttpResponse $response)
    {
        $categoriesGarden = $this->categoriesGardenRepository->findOrFail($id);

        $categoriesGarden->fill($request->input());

        $this->categoriesGardenRepository->createOrUpdate($categoriesGarden);

        event(new UpdatedContentEvent(CATEGORIES_GARDEN_MODULE_SCREEN_NAME, $request, $categoriesGarden));

        return $response
            ->setPreviousUrl(route('garden.categories.list'))
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
            $categoriesGarden = $this->categoriesGardenRepository->findOrFail($id);

            $this->categoriesGardenRepository->delete($categoriesGarden);

            event(new DeletedContentEvent(CATEGORIES_GARDEN_MODULE_SCREEN_NAME, $request, $categoriesGarden));

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
            $categoriesGarden = $this->categoriesGardenRepository->findOrFail($id);
            $this->categoriesGardenRepository->delete($categoriesGarden);
            event(new DeletedContentEvent(CATEGORIES_GARDEN_MODULE_SCREEN_NAME, $request, $categoriesGarden));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
