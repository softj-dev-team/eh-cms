<?php

namespace Botble\Life\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Life\Http\Requests\LifeRequest;
use Botble\Life\Repositories\Interfaces\LifeInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Life\Tables\LifeTable;
use Botble\Life\Tables\FlareTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Life\Forms\FlareForm;
use Botble\Life\Http\Requests\FlareRequest;
use Botble\Media\Models\MediaFolder;
use Botble\Life\Repositories\Interfaces\FlareInterface;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;

class FlareController extends BaseController
{
    /**
     * @var FlareInterface
     */
    protected $flareRepository;
    /**
     * @var MediaFileInterface
     */
    protected $fileRepository;
    /**
     * @var MediaFolderInterface
     */
    protected $folderRepository;

    /**
     * LifeController constructor.
     * @param LifeInterface $flareRepository
     * @author Sang Nguyen
     */
    public function __construct(FlareInterface $flareRepository , MediaFolderInterface $folderRepository, MediaFileInterface $fileRepository)
    {
        $this->flareRepository = $flareRepository;
        $this->folderRepository = $folderRepository;
        $this->fileRepository =  $fileRepository;
    }

    /**
     * Display all lives
     * @param LifeTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(FlareTable $table)
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
        page_title()->setTitle(__('life.flea_market.write'));

        return $formBuilder->create(FlareForm::class)->renderForm();
    }

    /**
     * Insert new Life into database
     *
     * @param FlareRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(FlareRequest $request, BaseHttpResponse $response)
    {

         $request->merge(['categories' => json_encode($request->categories)]);
         $request->merge(['exchange' => json_encode($request->exchange)]);
         $request->merge(['images' => json_encode($request->images)]);
         if( $request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }

        $flare = $this->flareRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FLARE_MODULE_SCREEN_NAME, $request, $flare));

        return $response
            ->setPreviousUrl(route('life.flare.list'))
            ->setNextUrl(route('life.flare.edit', $flare->id))
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
        $flare = $this->flareRepository->findOrFail($id);

        event(new BeforeEditContentEvent(FLARE_MODULE_SCREEN_NAME, $request, $flare));

        page_title()->setTitle("수정" . ' #' . $id);

        return $formBuilder->create(FlareForm::class, ['model' => $flare])->renderForm();
    }

    /**
     * @param $id
     * @param FlareRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, FlareRequest $request, BaseHttpResponse $response)
    {
        $request->merge(['categories' => json_encode($request->categories)]);
        $request->merge(['exchange' => json_encode($request->exchange)]);
        $request->merge(['images' => json_encode($request->images)]);
        $request->merge(['link' => $request->input('link')]);
        $request->merge(['file_upload' => $request->input('file_upload')]);

        $flare = $this->flareRepository->findOrFail($id);
        if($flare->published == null && $request->status == 'publish') {
            $request->merge(['published' => Carbon::now()]);
        }
        $flare->fill($request->input());

        $this->flareRepository->createOrUpdate($flare);

        event(new UpdatedContentEvent(FLARE_MODULE_SCREEN_NAME, $request, $flare));

        return $response
            ->setPreviousUrl(route('life.flare.list'))
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
        $parent = MediaFolder::where('slug', 'flare-market-fe')->first();
        $folder = MediaFolder::where('slug', $id)->where('parent_id',$parent->id)->first();
        if ($folder) {
            $directory = config('media.driver.' . config('filesystems.default') . '.path') . $this->folderRepository->getFullPath($folder->id);
            $file = new Filesystem;

            // Xóa file trong server
            if ($file->exists($directory)) {

                $file->cleanDirectory($directory);

                // Get all files in this directory.
                $files = $file->files($directory);

                // Check if directory is empty.
                if (empty($files)) {
                    // Yes, delete the directory.
                    $file->deleteDirectory($directory);
                } else {
                    return $response
                        ->setError()
                        ->setMessage(trans('core/base::notices.cannot_delete'));
                }
            }
            // xóa trong database media
            $this->fileRepository->deleteBy(['folder_id' => $folder->id]);
            $this->folderRepository->deleteFolder($folder->id, true);
        }

        try {
            $flare = $this->flareRepository->findOrFail($id);

            $this->flareRepository->delete($flare);

            event(new DeletedContentEvent(FLARE_MODULE_SCREEN_NAME, $request, $flare));

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
            $parent = MediaFolder::where('slug', 'flare-market-fe')->first();
            $folder = MediaFolder::where('slug', $id)->where('parent_id',$parent->id)->first();
            if ($folder) {
                $directory = config('media.driver.' . config('filesystems.default') . '.path') . $this->folderRepository->getFullPath($folder->id);
                $file = new Filesystem;

                // Xóa file trong server
                if ($file->exists($directory)) {

                    $file->cleanDirectory($directory);

                    // Get all files in this directory.
                    $files = $file->files($directory);

                    // Check if directory is empty.
                    if (empty($files)) {
                        // Yes, delete the directory.
                        $file->deleteDirectory($directory);
                    } else {
                        return $response
                            ->setError()
                            ->setMessage(trans('core/base::notices.cannot_delete'));
                    }
                }
                // xóa trong database media
                $this->fileRepository->deleteBy(['folder_id' => $folder->id]);
                $this->folderRepository->deleteFolder($folder->id, true);
            }
            $flare = $this->flareRepository->findOrFail($id);
            $this->flareRepository->delete($flare);
            event(new DeletedContentEvent(FLARE_MODULE_SCREEN_NAME, $request, $flare));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
