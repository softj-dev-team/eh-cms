<?php

namespace Botble\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use Botble\ACL\Models\Role;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\EmailHandler;
use Botble\Campus\Repositories\Interfaces\Schedule\ScheduleInterface;
use Botble\Member\Models\Member;
use Botble\Member\Repositories\Interfaces\MemberInterface;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use SeoHelper;
use URL;
use Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = null;

    /**
     * @var MemberInterface
     */
    protected $memberRepository;
    /**
     * @var ScheduleInterface
     */
    protected $scheduleRepository;

    protected $emailHandler;

    /**
     * Create a new controller instance.
     *
     * @param MemberInterface $memberRepository
     * @param ScheduleInterface $scheduleRepository
     * @author Sang Nguyen
     */
    public function __construct(MemberInterface $memberRepository, ScheduleInterface $scheduleRepository, EmailHandler $emailHandler) {
        $this->memberRepository = $memberRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->redirectTo = route('public.member.register');
        $this->emailHandler = $emailHandler;
    }

    /**
     * Show the application registration form.
     *
     * @return \Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @author Sang Nguyen
     */
    public function showRegistrationForm() {
        SeoHelper::setTitle(__('이화이언'));

        return view('plugins.member::auth.register');
    }

    /**
     * Confirm a user with a given confirmation code.
     *
     * @param $email
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param MemberInterface $memberRepository
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function confirm($email, Request $request, BaseHttpResponse $response, MemberInterface $memberRepository) {
        if (!URL::hasValidSignature($request)) {
            abort(404);
        }
        $member = $memberRepository->getFirstBy(['email' => $email]);
        if (!$member) {
            abort(404);
        }
        $member->confirmed_at = Carbon::now(config('app.timezone'));
        $this->memberRepository->createOrUpdate($member);
        $this->guard()->login($member);
        $this->scheduleRepository->createOrUpdate([
            'name' => '새 스케쥴',
            'id_login' => $member->id_login,
            'start' => Carbon::now(config('app.timezone')),
            'end' => Carbon::now(config('app.timezone')),
        ]);
        return $response
            ->setNextUrl(route('public.member.dashboard'))
            ->setMessage(trans('plugins/member::member.confirmation_successful'));
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     * @author Sang Nguyen
     */
    protected function guard() {
        return Auth::guard('member');
    }

    /**
     * Resend a confirmation code to a user.
     *
     * @param \Illuminate\Http\Request $request
     * @param MemberInterface $memberRepository
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function resendConfirmation(Request $request, MemberInterface $memberRepository, BaseHttpResponse $response) {
        $member = $memberRepository->getFirstBy(['email' => $request->input('email')]);
        if (!$member) {
            return $response
                ->setError()
                ->setMessage(__('Cannot find this account!'));
        }
        $this->sendConfirmationToUser($member);
        return $response
            ->setMessage(trans('plugins/member::member.confirmation_resent'));
    }

    /**
     * Send the confirmation code to a user.
     *
     * @param Member $member
     * @author Sang Nguyen
     */
    protected function sendConfirmationToUser($member) {
        // Notify the user
        $notification = app(config('plugins.member.general.notification'));
        $member->notify($notification);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @param BaseHttpResponse $response

     * @author Sang Nguyen
     */
    public function register(Request $request, BaseHttpResponse $response) {

        $this->validator($request->input())->validate();
        $uuid = Str::uuid();


        // if ($this->verifySMS($request) === false) {
        //     return redirect()->back()->withErrors(['Verify fail, please reload and check your code again']);
        // }
        event(new Registered($member = $this->create($request->input())));

        $member->confirmed_at = Carbon::now(config('app.timezone'));
        $member->role_member_id = Role::where('slug', 'member')->first()->id ?? 1;
        $member->verify_at = Carbon::now(config('app.timezone'));
        $member->phone = $request->phone;
        $member->email_verify_code = $uuid; // for email verify
        $this->memberRepository->createOrUpdate($member);

//        $this->guard()->login($member);

        $this->scheduleRepository->createOrUpdate([
            'name' => '새 스케쥴',
            'id_login' => $member->id_login,
            'start' => Carbon::now(config('app.timezone')),
            'end' => Carbon::now(config('app.timezone')),
        ]);

        // 인증 이메일 전송
        $this->emailHandler->send(
            view('plugins.member::auth.email_verify_template', ['uuid' => $request->id_login.'||'.$uuid]),
            trans('plugins/member::dashboard.register_verify_email'),
            $request->email
        );

        if ($this->registered($request, $member)) {
            return redirect()->route('home.index')->withErrors([
                'verifySMS' => '인증 메일이 발송되었습니다.'
            ]);
        } else {
            return $response->setNextUrl($this->redirectPath());
        }
        // return $this->registered($request, $member)
        //     ?: $response->setNextUrl($this->redirectPath());
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     * @author Sang Nguyen
     */
    protected function validator(array $data) {
        return Validator::make($data, [
            'id_login' => 'required|max:20|min:4|unique:members',
            'password' => 'required|min:6|max:16|confirmed',
            'nickname' => 'required|max:20|min:2',
            'fullname' => 'required',
            'namemail' => 'required',
            'domainmail' => 'required',
            'email' => 'required|email|unique:members',
            'phone' => [
                'required',
                // 'regex:/\(?\+[0-9]{1,3}\)? ?-?[0-9]{1,3} ?-?[0-9]{3,5} ?-?[0-9]{4}( ?-?[0-9]{3})? ?(\w{1,10}\s?\d{1,6})?/u',
//                'regex:/^(010|011|016|017|018|019)-[^0][0-9]{3,4}-[0-9]{4}/u',
            ],
            // 'verify_code' => 'required',
            // 'sessionInfo' => 'required'
        ], [
            'password.required' => '비밀번호를 입력해주세요',
            'nickname.required' => '닉네임을 입력해주세요',
            'namemail.required' => '이름을 입력해주세요.',
            'id_login.required' => '이름을 입력해주세요.',
            'fullname.required' => '이름을 입력해주세요.',
            'phone.required' => '전화번호를 입력해주세요',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return Member
     * @author Sang Nguyen
     */
    protected function create(array $data) {
        return $this->memberRepository->create([
            'id_login' => $data['id_login'],
            'nickname' => $data['nickname'],
            'first_name' => $data['nickname'],
            'fullname' => $data['fullname'],
            'namemail' => $data['namemail'],
            'domainmail' => $data['domainmail'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role_member_id' => 0,
            'passwd_garden' => strtoupper(substr(md5(microtime()), rand(0, 26), 4)),
        ]);

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getVerify() {
        if (Cookie::get('verify_phone') !== null) {
            return view('plugins.member::auth.verify');
        }
        return redirect()->route('home.index')->withErrors([
            'verifySMS' => '허가가 없다'
        ]);
    }

    public function sendSMS(Request $request) {
        $request->validate([
            'phone' => [
                'required',
                'regex:/\(?\+[0-9]{1,3}\)? ?-?[0-9]{1,3} ?-?[0-9]{3,5} ?-?[0-9]{4}( ?-?[0-9]{3})? ?(\w{1,10}\s?\d{1,6})?/u',
            ],
            'recaptcha' => [
                'required',
            ],
        ]);

        $phoneNumber = $request->phone;
        $recaptcha = $request->recaptcha;

        try {
            $verifyUrl = GOOGLE_FIREBASE_SEND_VERIFICATION_CODE . config('keyfirebase');
            $client = resolve(Client::class);
            $apiRequest = $client->request('POST', $verifyUrl, [
                "form_params" => [
                    "recaptchaToken" => $recaptcha,
                    "phoneNumber" => $phoneNumber,
                ],
            ]);
            $response = json_decode($apiRequest->getBody());

            return response()->json([
                'msg' => $response->sessionInfo
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'err' => $e->getMessage(),
            ], 200);
        }
    }

    public function verifySMS(Request $request) {
        $sessionInfo = $request->sessionInfo;
        $code = $request->verify_code;
        $phoneNumber = $request->phone;
        $phoneNumber = preg_replace('/^0/', '+82', $phoneNumber);
        try {
            $verifyUrl = GOOGLE_FIREBASE_VERIFY_PHONE_NUMBER_CODE . config('keyfirebase');
            $client = resolve(Client::class);
            $apiRequest = $client->request('POST', $verifyUrl, [
                "form_params" => [
                    "sessionInfo" => $sessionInfo,
                    "code" => $code,
                ],
            ]);
            $response = json_decode($apiRequest->getBody());
            if ($response->phoneNumber == $phoneNumber) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function postVerify(Request $request) {
        if (Cookie::get('verify_phone') !== null) {
            $request->validate([
                'phone' => [
                    'required',
                    'regex:/\(?\+[0-9]{1,3}\)? ?-?[0-9]{1,3} ?-?[0-9]{3,5} ?-?[0-9]{4}( ?-?[0-9]{3})? ?(\w{1,10}\s?\d{1,6})?/u',
                ],
                'verify_code' => 'required',
                'sessionInfo' => 'required'
            ]);

            if ($this->verifySMS($request) === false) {
                return redirect()->back()->withErrors(['Verify fail, please reload and check your code again']);
            }
            $member = resolve(Member::class)->where('id_login', Cookie::get('verify_phone'))->first();
            if (!is_null($member)) {
                $member->verify_at = Carbon::now(config('app.timezone'));
                $member->phone = $request->phone;
                $this->memberRepository->createOrUpdate($member);
                $this->guard()->login($member);
                return redirect()->route('home.index');
            }
            return redirect()->route('home.index')->withErrors([
                'verifySMS' => '계정을 찾지 못했습니다'
            ]);
        }

        return redirect()->route('home.index')->withErrors([
            'verifySMS' => '허가가 없다'
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function emailVerify(Request $request)
    {
        $code = $request->input('c', null);

        if(!$code) {
            $this->verifyEmailConfirmError('plugins/member::dashboard.not_email_verified_error');
        }

        try {
            $code_arr = explode('||', $code);

            $member = Member::where('id_login', $code_arr[0])->firstOrFail();

            if ($member->email_verify_code !== $code_arr[1]) {
                $this->verifyEmailConfirmError('plugins/member::dashboard.not_email_verified_fail');
            }

            $member->email_verified = true;
            $member->update();
        } catch(\Exception $e) {
            $this->verifyEmailConfirmError('plugins/member::dashboard.not_email_verified_error');
        }

        return redirect()->route('home.index')->withErrors([
            'verifySMS' => '인증완료 되었습니다. 다시 로그인 해주세요.'
        ]);
    }

    public function emailResend()
    {
        return view('plugins.member::auth.email_verify_resend');
    }

    public function sendVerifyMail(Request $request)
    {
        $this->verifyEmailValidator($request->input())->validate();

        $uuid = Str::uuid();
        $member = Member::where('id_login', $request->id_login)->firstOrFail();

        $member->email = $request->email;
        $member->email_verify_code = $uuid;
        $member->update();

        // 인증 이메일 전송
        $this->emailHandler->send(
            view('plugins.member::auth.email_verify_template', ['uuid' => $request->id_login.'||'.$uuid]),
            trans('plugins/member::dashboard.register_verify_email'),
            $request->email
        );

        return redirect()->route('home.index');
    }

    /**
     * @throws ValidationException
     */
    protected function verifyEmailConfirmError($message)
    {
        throw ValidationException::withMessages([
            'verifySMS' => [
                trans($message, [
                    'resend_link' => route('public.member.verify.email.resend'),
                ]),
            ],
        ]);
    }

    protected function verifyEmailValidator(array $data) {
        return Validator::make($data, [
            'id_login' => 'required|max:20|min:4|exists:members',
            'namemail' => 'required',
            'domainmail' => 'required',
            'email' => 'required|email'
        ]);
    }
}
