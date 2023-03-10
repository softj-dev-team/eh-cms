<?php

namespace Botble\Member\Http\Controllers;

use App\Traits\NotificationTrait;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Member\Forms\NotifyForm;
use Botble\Member\Forms\NotifyForm2;
use Botble\Member\Forms\NotifyForm1;
use Botble\Member\Http\Requests\NotifyRequest;
use Botble\Member\Models\Member;
use Botble\Member\Models\MemberNotify;
use Botble\Member\Repositories\Interfaces\NotifyInterface;
use Botble\Member\Tables\NotifyTable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class MemberNotifyController extends BaseController
{
    use NotificationTrait;
    /**
     * @var NotifyInterface
     */
    protected $notifyRepository;

    /**
     * CampusController constructor.
     * @param NotifyInterface $notifyRepository
     * @author Sang Nguyen
     */
    public function __construct(NotifyInterface $notifyRepository)
    {
        $this->notifyRepository = $notifyRepository;
    }

    /**
     * Display all campuses
     * @param NotifyTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(NotifyTable $table)
    {

        page_title()->setTitle("목록");

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle(__('member.create_notify'));

        return $formBuilder->create(NotifyForm::class)->renderForm();
    }

    /**
     * Insert new Campus into database
     *
     * @param NotifyRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(NotifyRequest $request, BaseHttpResponse $response)
    {
        $user_id = auth()->user()->id;
        $request->merge(['user_id' => $user_id]);
        $notify = $this->notifyRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(NOTIFY_MODULE_SCREEN_NAME, $request, $notify));

        return $response
            ->setPreviousUrl(route('member.notify.list'))
            ->setNextUrl(route('member.notify.edit', $notify->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author BM Phuoc
     */
    public function getCreateNotify1(FormBuilder $formBuilder)
    {
        page_title()->setTitle('푸시알람 보내기 1');

        return $formBuilder->create(NotifyForm1::class)->renderForm();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author BM Phuoc
     */
    public function getCreateNotify2(FormBuilder $formBuilder)
    {
        page_title()->setTitle('푸시알람 보내기 2');

        return $formBuilder->create(NotifyForm2::class)->renderForm();
    }

    public function postCreateNotify1(NotifyRequest $request, BaseHttpResponse $response)
    {
        $user_id = auth()->user()->id;
        $request->merge(['user_id' => $user_id]);
        $title = $request->input('title');
        $content = $request->input('content');
        $listMemberID = $request->input('member_ids');
        if (!isset($listMemberID)){
            return $response
                ->setPreviousUrl(route('member.notify.list'))
                ->setMessage(trans('core/base::notices.create_success_message'));
        }
        $notify = $this->notifyRepository->createOrUpdate($request->input());

        // add member notify
        $memberNotifies = [];
        $memberIds = array_map('trim', explode(',', $listMemberID));
        $memberIdNotify = [];
        foreach ($memberIds as $memberId) {

            $checkMember = Member::query()->where('id', $memberId)->orWhere('id_login', $memberId)->get()->first();
            if($checkMember){
                $memberNotify = new MemberNotify([
                    'member_id' => $checkMember->id
                ]);
                $memberIdNotify[] = $checkMember->id;
                // $product->id will be set after save()
                array_push($memberNotifies, $memberNotify);
            }

        }

        $notify->memberNotify()->saveMany($memberNotifies);

        // send notify
        $typeNotification = 'site_notice';

        $this->notify($title, $content, $memberIdNotify , $typeNotification);

        event(new CreatedContentEvent(NOTIFY_MODULE_SCREEN_NAME, $request, $notify));

        return $response
            ->setPreviousUrl(route('member.notify.list'))
            ->setNextUrl(route('member.notify.edit', $notify->id))
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
        $notify = $this->notifyRepository->findOrFail($id);

        event(new BeforeEditContentEvent(NOTIFY_MODULE_SCREEN_NAME, $request, $notify));

        page_title()->setTitle('Edit Notify' . ' #' . $id);

        return $formBuilder->create(NotifyForm::class, ['model' => $notify])->renderForm();
    }

    /**
     * @param $id
     * @param NotifyRequest $request
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, NotifyRequest $request, BaseHttpResponse $response)
    {
        $notify = $this->notifyRepository->findOrFail($id);

        $notify->fill($request->input());

        $this->notifyRepository->createOrUpdate($notify);

        event(new UpdatedContentEvent(NOTIFY_MODULE_SCREEN_NAME, $request, $notify));

        return $response
            ->setPreviousUrl(route('member.notify.list'))
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
            $notify = $this->notifyRepository->findOrFail($id);

            $this->notifyRepository->delete($notify);

            event(new DeletedContentEvent(NOTIFY_MODULE_SCREEN_NAME, $request, $notify));

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
            $notify = $this->notifyRepository->findOrFail($id);
            $this->notifyRepository->delete($forbidden);
            event(new DeletedContentEvent(NOTIFY_MODULE_SCREEN_NAME, $request, $notify));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
    public function getSearchMember(Request $request)
    {
        $keyword = $request->keyword;
        $notify_id = $request->notify_id;
        $member_notify_id =  MemberNotify::where('notify_id', $notify_id)->pluck('member_id');
        $data = Member::where(function ($q) use ($keyword) {
            $q->where('fullname', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%')
                ->orWhere('nickname', 'like', '%' . $keyword . '%')
                ->orWhere('id_login', 'like', '%' . $keyword . '%');
        })->whereNotIn('id', $member_notify_id)
        ->take(10)->get([
            'id',
            'fullname',
            'email',
            'nickname',
            'id_login'
        ]);
        return response()->json([
            'items' => $data,
        ]);
    }

    public function getSearchMember1(Request $request)
    {
        $arrStudent = $request->arrStudent;
        $arrCertificate = $request->arrCertificate;
        $arrOtherFilter = $request->arrOtherFilter;

        $query = Member::select(['id', 'email', 'nickname', 'id_login']);

        if($arrStudent){
            $query->whereIn('id', $arrStudent);
        }

        if($arrCertificate){
            $query->whereIn('certification', $arrCertificate);
        }

        if($arrOtherFilter){
            if (in_array('login_in_last_one_month', $arrOtherFilter)){
                $timeLogin = Carbon::now()->subDays(30)->format('Y-m-d');
                $query->where('last_login', '>=', $timeLogin . '00:00:00');
            }

            if (in_array('not_is_blacklist', $arrOtherFilter)){
                $query->where('is_blacklist', 0);
            }
        }
        $data = $query->get();

        $memberIds = $data->pluck('id')->toArray();
        $memberIds = $memberIds ?  implode(",",$memberIds): null;

        return response()->json([
            'count_data' => count($data),
//            'items' => $data,
            'memberIds' => $memberIds
        ]);
    }
    public function postAddMemberNotify( Request $request)
    {
        $member_id = $request->member_id;
        $notify_id = $request->notify_id;
        $memberNotify = MemberNotify::updateOrCreate([
            'member_id' => $member_id,
            'notify_id' => $notify_id
        ]);
        $memberNotify = MemberNotify::where('notify_id', $notify_id)->get();
        $html = view('plugins.member::forms.fields.result-search', [
            'memberNotify' =>  $memberNotify,
        ])->render();

        return response()->json([
            'member' => $html,
            'status' => 'publish',
        ], 200);
    }

    public function postDeleteMemberNotify(Request $request, BaseHttpResponse $response)
    {
        $id = $request->id;
        $memberNotify = MemberNotify::findOrFail($id);
        $memberNotify->delete();
        return response()->json([
            'msg' => true,
            'status' => 'publish',
        ], 200);
    }
}
