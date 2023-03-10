<?php

namespace Theme\Ewhaian\Http\Controllers;

use App\Traits\NotificationTrait;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Member\Models\Member;
use Botble\Member\Models\MemberNotes;
use Illuminate\Routing\Controller;
use Theme;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    use NotificationTrait;

    public static function index() {
        Theme::breadcrumb()->add(__('message.member'), route('public.member.dashboard'))->add(__('message'), 'http:...');

        Theme::setTitle(__('message'));
        $message = MemberNotes::where('member_to_id', auth()->guard('member')->user()->id)->orderBy('created_at', 'DESC')->paginate(10);
        return Theme::scope('message.index', ['message' => $message])->render();
    }

    public static function send(Request $request) {
        $member_from_id = $request->input('member_from_id');
        $id_login_to = $request->input('id_login_to');
        $contents = $request->input('contents');

        $member_to_id = Member::where('id_login', $id_login_to)->first();

        if ($member_to_id == null) {
            return response()->json([
                'message' => 'ID does not exist.',
                'status' => false
            ]);
        }
        if ($contents == null) {
            return response()->json([
                'message' => 'Please enter your message.',
                'status' => false
            ]);
        }

        $member_note = new MemberNotes;
        $member_note->member_from_id = $member_from_id;
        $member_note->member_to_id = $member_to_id->id;
        $member_note->contents = $contents;

        $member_note->save();

        // notify to owner
        $type_noti = 'site_notice';
        self::notifyStatic(__('message.title_notification'), $contents, [
            $member_to_id->id
        ], $type_noti);

        return response()->json([
            'message' => '메시지를 발송하였습니다.',
            'status' => true
        ]);
    }

    public static function show(Request $request) {
        $id = $request->input('id');
        $note = MemberNotes::where('id', $id)->first();

        if ($note == null) {
            return response()->json([
                'message' => __('message.please_choose_your_message'),
                'status' => false
            ]);
        }
        if ($note->status == "unread") {
            $note->status = 'read';
            $note->save();
        }
        return response()->json([
            'status' => true
        ]);
    }

    public static function delete(Request $request) {
        try {

            $member_note = MemberNotes::where('id', $request->input('message_id'))->firstOrFail();
            $member_note->delete();

            event(new DeletedContentEvent(MEMBER_NOTES_MODULE_SCREEN_NAME, $request, $member_note));

            return redirect()->route('public.member.message.index')->with('success', __('controller.delete_message_successful', ['module' => __('message')]));
        } catch (Exception $exception) {
            return redirect()->route('public.member.message.index')->with('error', __('controller.delete_failed'));
        }
    }

    public static function deleteMany(Request $request) {
        $message_many_id = explode(",", $request->input('message_many_id'));
        foreach ($message_many_id as $key => $item) {
            try {

                $member_note = MemberNotes::where('id', $item)->firstOrFail();
                $member_note->delete();

                event(new DeletedContentEvent(MEMBER_NOTES_MODULE_SCREEN_NAME, $request, $member_note));

            } catch (Exception $exception) {
                return redirect()->route('public.member.message.index')->with('error', __('controller.delete_failed'));
            }
        }
        return redirect()->route('public.member.message.index')->with('success', __('controller.delete_successful', ['module' => __('message')]));
    }
}
