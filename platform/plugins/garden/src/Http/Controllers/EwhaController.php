<?php

namespace Botble\Garden\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Garden\Forms\EwhaForm;
use Botble\Garden\Http\Requests\GardenRequest;
use Botble\Garden\Models\Ewha;
use Botble\Garden\Repositories\Interfaces\EwhaInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Garden\Tables\EwhaTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Garden\Forms\GardenForm;
use Botble\Base\Forms\FormBuilder;
use Carbon\Carbon;

class EwhaController extends BaseController
{
    protected $ewhaRepository;

    public function __construct(EwhaInterface $ewhaRepository) {
        $this->ewhaRepository = $ewhaRepository;
    }

    public function getList(EwhaTable $table) {
        page_title()->setTitle('지난 화원');
        return $table->renderTable();
    }

    public function getDetail($id, FormBuilder $formBuilder, Request $request) {
        $garden =Ewha::where('BP_IDX', $id)->get()->first();
        dd($garden);

        event(new BeforeEditContentEvent(GARDEN_MODULE_SCREEN_NAME, $request, $garden));

        page_title()->setTitle(trans('plugins/garden::garden.edit') . ' #' . $id);
        return view('plugins.garden::elements.tables.detail_ewha_old')->with('garden', $garden );

    }

    public function postEdit($id, GardenRequest $request, BaseHttpResponse $response) {
        if (is_null($request->input('right_click'))) {
            $request->merge(['right_click' => 0]);
        }

        if (is_null($request->input('active_empathy'))) {
            $request->merge(['active_empathy' => 0]);
        }

        $garden = $this->ewhaRepository->findOrFail($id);

        if ($garden->published == null && $request->status != 'draft') {
            $request->merge(['published' => Carbon::now()]);
        }

        $request->merge(['link' => $request->input('link')]);
        $request->merge(['file_upload' => $request->input('file_upload')]);
        $garden->fill($request->input());

        $this->ewhaRepository->createOrUpdate($garden);

        event(new UpdatedContentEvent(GARDEN_MODULE_SCREEN_NAME, $request, $garden));

        return $response
            ->setPreviousUrl(route('garden.list'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}
