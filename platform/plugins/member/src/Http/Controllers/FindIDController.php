<?php

namespace Botble\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Botble\Base\Supports\EmailHandler;
use Botble\Member\Models\Member;

class FindIDController extends Controller
{
      /**
     * @var EmailHandler
     */
    protected $emailHandler;

    public function __construct(EmailHandler $emailHandler)
    {
        $this->emailHandler = $emailHandler;
    }

    protected function validateEmail(Request $request)
    {
        $request->validate(['email_findId' => 'required|email|exists:members,email']);
    }


    public function findID(Request $request)
    {
        $this->validateEmail($request);
        $email = $request->email_findId;

        $id_login = Member::where('email', $email)->get()->pluck('id_login');
        $data = "";
        if (count($id_login) > 0 ) {
            foreach ($id_login as $key => $item) {
                $data = $data . $item .'<br/>';
            }
        } else {
            $data = "No exits ID for this email";
        }

        $this->emailHandler->send(
            view('plugins.member::emails.findId', [
                'data' => $data
            ])->render(),
           '이화이언 아이디 찾기 안내 메일입니다.',
           $email,
            [],
            false
            );

        return back()->with('status','가입시 입력하신 이메일로 아이디가 전송되었습니다.');
    }
}
