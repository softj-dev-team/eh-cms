<?php

namespace Botble\Contents\Http\Controllers;

use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Contents\Forms\ContentsForm;
use Botble\Contents\Http\Requests\ContentsRequest;
use Botble\Contents\Repositories\Interfaces\ContentsInterface;
use Botble\Contents\Tables\AddSlidesContentsTable;
use Botble\Contents\Tables\ContentsTable;
use Botble\Contents\Tables\SlideContentsTable;
use Botble\Media\Models\MediaFolder;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SlidesContentsController extends BaseController
{
    /**
     * @var ContentsInterface
     */
    protected $contentsRepository;

    /**
     * ContentsController constructor.
     * @param ContentsInterface $contentsRepository
     * @author Sang Nguyen
     */
    public function __construct(ContentsInterface $contentsRepository)
    {
        $this->contentsRepository = $contentsRepository;
    }

    /**
     * Display all contents
     * @param ContentsTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(SlideContentsTable $table)
    {

        page_title()->setTitle('목록');

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(AddSlidesContentsTable $table)
    {
        page_title()->setTitle('슬라이드 콘텐츠');

        return $table->renderTable();
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
            $contents = $this->contentsRepository->findOrFail($id);

            $this->contentsRepository->update([
                'id' => $contents->id
            ],[
                'is_slides' => 0,
            ]);

            event(new DeletedContentEvent(CONTENTS_MODULE_SCREEN_NAME, $request, $contents));

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
            $contents = $this->contentsRepository->findOrFail($id);
            $this->contentsRepository->update([
                'id' => $contents->id
            ],[
                'is_slides' => 0,
            ]);
            event(new DeletedContentEvent(CONTENTS_MODULE_SCREEN_NAME, $request, $contents));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
