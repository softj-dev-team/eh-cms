<?php

namespace Botble\Campus\Http\Controllers\StudyRoom;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Campus\Http\Requests\CampusRequest;
use Botble\Campus\Repositories\Interfaces\CampusInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Campus\Tables\CampusTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Campus\Forms\CampusForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Campus\Forms\StudyRoom\StudyRoomCategoriesForm;
use Botble\Campus\Http\Requests\StudyRoom\StudyRoomCategoriesRequest;
use Botble\Campus\Repositories\Interfaces\StudyRoom\StudyRoomCategoriesInterface;
use Botble\Campus\Tables\StudyRoom\StudyRoomCategoriesTable;

class StudyRoomCategoriesController extends BaseController
{
     /**
     * @var StudyRoomCategoriesInterface
     */
    protected $studyRoomCategoriesRepository;

    /**
     * JobsCategoriesController constructor.
     * @param StudyRoomCategoriesInterface $studyRoomCategoriesRepository
     */
    public function __construct(StudyRoomCategoriesInterface  $studyRoomCategoriesRepository)
    {
        $this->studyRoomCategoriesRepository = $studyRoomCategoriesRepository;

    }

    /**
     * Display all lives
     * @param StudyRoomCategoriesTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(StudyRoomCategoriesTable $table)
    {

        page_title()->setTitle('교정/공부방/카테고리/목록');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        \Assets::addScriptsDirectly(['/vendor/core/plugins/life/js/jscolor.js']);
        page_title()->setTitle("교정/공부방/카테고리/새로운 카테고리");

        return $formBuilder->create(StudyRoomCategoriesForm::class)->renderForm();
    }

    /**
     * Insert new Life into database
     *
     * @param JobsCategoriesRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(StudyRoomCategoriesRequest $request, BaseHttpResponse $response)
    {
        if($request->parent_id == 0) {
            $request->merge(['parent_id' => null ]);
        }
        $studyRoomCategories = $this->studyRoomCategoriesRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(STUDY_ROOM_CATEGORIES_MODULE_SCREEN_NAME, $request, $studyRoomCategories));

        return $response
            ->setPreviousUrl(route('campus.study_room.categories.list'))
            ->setNextUrl(route('campus.study_room.categories.edit', $studyRoomCategories->id))
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
        \Assets::addScriptsDirectly(['/vendor/core/plugins/life/js/jscolor.js']);
        $studyRoomCategories = $this->studyRoomCategoriesRepository->findOrFail($id);

        event(new BeforeEditContentEvent(STUDY_ROOM_CATEGORIES_MODULE_SCREEN_NAME, $request, $studyRoomCategories));

        page_title()->setTitle("교정/공부방/카테고리 " . ' #' . $id);

        return $formBuilder->create(StudyRoomCategoriesForm::class, ['model' => $studyRoomCategories])->renderForm();
    }

    /**
     * @param $id
     * @param FlareCategoriesRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, StudyRoomCategoriesRequest $request, BaseHttpResponse $response)
    {
        $request->merge(['parent_id' => null ]);
        $studyRoomCategories = $this->studyRoomCategoriesRepository->findOrFail($id);

        $studyRoomCategories->fill($request->input());

        $this->studyRoomCategoriesRepository->createOrUpdate($studyRoomCategories);

        event(new UpdatedContentEvent(STUDY_ROOM_CATEGORIES_MODULE_SCREEN_NAME, $request, $studyRoomCategories));

        return $response
            ->setPreviousUrl(route('campus.study_room.categories.list'))
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
            $studyRoomCategories = $this->studyRoomCategoriesRepository->findOrFail($id);

            $this->studyRoomCategoriesRepository->delete($studyRoomCategories);

            event(new DeletedContentEvent(STUDY_ROOM_CATEGORIES_MODULE_SCREEN_NAME, $request, $studyRoomCategories));

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
            $studyRoomCategories = $this->studyRoomCategoriesRepository->findOrFail($id);
            $this->studyRoomCategoriesRepository->delete($studyRoomCategories);
            event(new DeletedContentEvent(STUDY_ROOM_CATEGORIES_MODULE_SCREEN_NAME, $request, $studyRoomCategories));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
