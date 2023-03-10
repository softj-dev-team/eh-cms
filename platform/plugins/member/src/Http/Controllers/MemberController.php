<?php

namespace Botble\Member\Http\Controllers;

use App\Traits\NotificationTrait;
use Assets;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Garden\Models\Egarden\RoomMemberRequest;
use Botble\Garden\Repositories\Interfaces\Egarden\RoomInterface;
use Botble\Member\Forms\MemberForm;
use Botble\Member\Models\Member;
use Botble\Member\Models\MemberBlackList;
use Botble\Member\Tables\MemberTable;
use Botble\Member\Http\Requests\MemberCreateRequest;
use Botble\Member\Http\Requests\MemberEditRequest;
use Botble\Member\Repositories\Interfaces\MemberInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Member\Tables\AuthListTable;
use Botble\Member\Tables\BlackListTable;
use Botble\Member\Tables\EwhaianAuthListTable;
use Botble\Member\Tables\SproutAuthListTable;
use Illuminate\Support\Facades\DB;

class MemberController extends BaseController
{
    use NotificationTrait;

    /**
     * @var MemberInterface
     */
    protected $memberRepository;

    /**
     * @var RoomInterface
     */
    protected $roomRepository;

    /**
     * @param MemberInterface $memberRepository
     * @author Sang Nguyen
     */
    public function __construct(
        MemberInterface $memberRepository,
        RoomInterface $roomRepository
    ) {
        $this->memberRepository = $memberRepository;
        $this->roomRepository = $roomRepository;
    }

    /**
     * Display all members
     * @param MemberTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     * @author Sang Nguyen
     */
    public function getList(MemberTable $dataTable) {
        Member::where('member_id', null)->update([
            'member_id' => DB::raw('id')
            ]);
        page_title()->setTitle('목록');
        Assets::addScriptsDirectly(['/vendor/core/plugins/member/js/hide_bulk.js']);
        Assets::addStylesDirectly(['/vendor/core/plugins/member/css/hide_bulk.css']);

        return $dataTable->renderTable();
    }

    /**
     * Show create form
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder) {
        page_title()->setTitle(trans('plugins/member::member.create'));

        Assets::addScriptsDirectly(['/vendor/core/plugins/member/js/member-admin.js']);
        Assets::addScriptsDirectly(['/js/custom_admin.js']);
        Assets::addStylesDirectly(['/css/custom.css']);

        return $formBuilder
            ->create(MemberForm::class)
            ->remove('is_change_password')
            ->renderForm();
    }

    /**
     * Insert new Gallery into database
     *
     * @param MemberCreateRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(MemberCreateRequest $request, BaseHttpResponse $response) {
        if ($request->role_member_id == null) {
            $request->merge(['role_member_id' => 0]);
        }

        if($request->input('block_user') == "4"||  $request->input('block_user') == "0"){
            $request->merge(['start_block_time' => null]);
            $request->merge(['end_block_time' => null]);
        }

        $request->merge(['password' => bcrypt($request->input('password')), 'first_name' => $request->nickname ?? "No have first name"]);
        $member = $this->memberRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(MEMBER_MODULE_SCREEN_NAME, $request, $member));

        return $response
            ->setPreviousUrl(route('member.list'))
            ->setNextUrl(route('member.edit', $member->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * Show edit form
     *
     * @param $id
     * @param FormBuilder $formBuilder
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @author Sang Nguyen
     */
    public function getEdit($id, FormBuilder $formBuilder) {
        Assets::addScriptsDirectly(['/vendor/core/plugins/member/js/member-admin.js']);
        Assets::addScriptsDirectly(['/js/custom_admin.js']);
        Assets::addStylesDirectly(['/css/custom.css']);

        $member = $this->memberRepository->findOrFail($id);
        page_title()->setTitle(trans('plugins/member::member.edit'));

        $member->password = null;

        return $formBuilder
            ->create(MemberForm::class, ['model' => $member])
            ->renderForm();
    }

    public function sendMail($mails) {
        // empty
    }

    /**
     * @param $id
     * @param MemberEditRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, MemberEditRequest $request, BaseHttpResponse $response) {
        $request->merge(['id_login' => $request->input('id_login')]);

        if ($request->input('is_change_password') == 1) {
            $request->merge(['password' => bcrypt($request->input('password'))]);
            $data = $request->input();
        } else {
            $data = $request->except('password');
        }

        if($data['block_user'] == "4" || $data['block_user'] == "0"){
            $data['start_block_time'] = null;
            $data['end_block_time'] = null;
        }

        $member = $this->memberRepository->createOrUpdate($data, ['id' => $id]);

        if ( isset($data['is_blacklist']) && $data['is_blacklist'] == '1') {
            MemberBlackList::create(['member_id' => $member->id]);
        }

        event(new UpdatedContentEvent(MEMBER_MODULE_SCREEN_NAME, $request, $member));

        return $response
            ->setPreviousUrl(route('member.list'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function getDelete(Request $request, $id, BaseHttpResponse $response) {
        try {
            $member = $this->memberRepository->findOrFail($id);
            $this->memberRepository->delete($member);
            event(new DeletedContentEvent(MEMBER_MODULE_SCREEN_NAME, $request, $member));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.cannot_delete'));
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function deleteBlacklist(Request $request, $id, BaseHttpResponse $response) {
        try {
            $member = $this->memberRepository->findOrFail($id);

            $member->is_blacklist = 0;
            $member->report_id = NULL;
            $member->reporter_id = NULL;
            $member->report_date = NULL;
            $member->report_type = NULL;

            $member->save();

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
     */
    public function setHooligan(Request $request, BaseHttpResponse $response) {
        try {
            $member = $this->memberRepository->findOrFail($request->member_id);

            $member->is_blacklist = 1;
            $member->report_id = $request->report_id;
            $member->reporter_id = $request->reporter_id;
            $member->report_date = $request->report_date;
            $member->report_type = $request->report_type;

            $member->save();

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
     */
    public function approveOwnership(Request $request, BaseHttpResponse $response) {
        try {
            $roomRequest = RoomMemberRequest::findOrFail($request->application_id);

            $roomRequest->status = 'approve';

            $room = $this->roomRepository->findOrFail($roomRequest->room_id);

            $room->member_id = $roomRequest->member_id;

            $roomRequest->save();
            $room->save();

            return $response->setMessage('Approve ownership to admin successful');

        } catch (Exception $exception) {
            var_dump($exception->getMessage());
            return $response
                ->setError()
                ->setMessage('Approve ownership to admin failed');
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     * @author Sang Nguyen
     */
    public function postDeleteMany(Request $request, BaseHttpResponse $response) {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $member = $this->memberRepository->findOrFail($id);
            $this->memberRepository->delete($member);
            event(new DeletedContentEvent(MEMBER_MODULE_SCREEN_NAME, $request, $member));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getBlackList(BlackListTable $dataTable) {
        page_title()->setTitle('목록');
        return $dataTable->renderTable();
    }

    public function getSproutAuthList(SproutAuthListTable $dataTable) {
        page_title()->setTitle('새내기 인증');
        return $dataTable->renderTable();
    }

    public function getUpdateSproutAuthList($id, $approval, Request $request, BaseHttpResponse $response) {
        $member = $this->memberRepository->findOrFail($id);

        $dataUpdate =  [
            'status_fresh1' => $approval
        ];

        if($approval == 3){
            $keyReject = 'reason_reject_' . $id;
            $reason = $request->input($keyReject);
            if(empty($reason)){
                return $response
                    ->setError()
                    ->setMessage('거절 이유를 입력해주세요.');
            }
            $dataUpdate = [
                'status_fresh1' => $approval,
                'reason_reject_1' => $reason
            ];
        }

        $dataUpdate['update_freshman1']  = Carbon::now();

        $this->memberRepository->createOrUpdate($dataUpdate, ['id' => $member->id]);

        // send notify
        $typeNotification = 'site_notice';

        $this->notify(__('new_contents.title'), __('new_contents.content'), [
            $member->id
        ], $typeNotification);

        return redirect()->back();
    }

    public function getEwhaianAuthList(EwhaianAuthListTable $dataTable) {
        page_title()->setTitle('이화이안 인증 리스트');

        return $dataTable->renderTable();
    }

    public function getUpdateEwhaianAuthList($id, $approval, Request $request, BaseHttpResponse $response) {
        $member = $this->memberRepository->findOrFail($id);

        $dataUpdate =  [
            'status_fresh2' => $approval
        ];

        // reason_reject
        if($approval == 3){
            $keyReject = 'reason_reject_' . $id;
            $reason = $request->input($keyReject);
            if(empty($reason)){
                return $response
                    ->setError()
                    ->setMessage('거절 이유를 입력해주세요.');
            }
            $dataUpdate = [
                'status_fresh2' => $approval,
                'reason_reject_2' => $reason
            ];
        }

        $dataUpdate['update_freshman2']  = Carbon::now();

        $this->memberRepository->createOrUpdate($dataUpdate, ['id' => $member->id]);

        // send notify
        $typeNotification = 'site_notice';

        $this->notify(__('new_contents.title'), __('new_contents.content'), [
            $member->id
        ], $typeNotification);

        return redirect()->back();
    }
}
