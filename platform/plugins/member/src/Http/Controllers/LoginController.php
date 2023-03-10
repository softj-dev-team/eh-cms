<?php

namespace Botble\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use Botble\ACL\Traits\LogoutGuardTrait;
use Botble\Garden\Models\AccessGarden;
use Botble\Member\Models\Member;
use Botble\Member\Models\MemberToken;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;
use SeoHelper;
use URL;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers, LogoutGuardTrait {
        LogoutGuardTrait::logout insteadof AuthenticatesUsers;
    }

    use AuthenticatesUsers {
        attemptLogin as baseAttemptLogin;
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     * @author Sang Nguyen
     */
    function __construct() {
        session(['url.intended' => URL::previous()]);
        if (session()->get('url.intended') === route('public.member.login')) {
            $this->redirectTo = route('public.member.dashboard');
        } else {
            $this->redirectTo = session()->get('url.intended');
        }
    }

    /**
     * Show the application's login form.
     *
     * @return \Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @author Sang Nguyen
     */
    function showLoginForm() {
        SeoHelper::setTitle(trans('plugins/member::member.login'));
        return view('plugins.member::auth.login');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     * @author Sang Nguyen
     */
    function guard() {
        return Auth::guard('member');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Validation\ValidationException
     * @author Sang Nguyen
     */
    function login(Request $request) {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    function sendLoginResponse(Request $request) {
        if ($request->filled('remember')) {
            $rememberTokenExpireMinutes = 43200; // 1 month(30 day)

            $rememberTokenName = auth()->guard('member')->getRecallerName();

            Cookie::queue($rememberTokenName, Cookie::get($rememberTokenName), $rememberTokenExpireMinutes);
            Cookie::queue('id_login', $request->id_login, $rememberTokenExpireMinutes);
            Cookie::queue('password', $request->password, $rememberTokenExpireMinutes);

            $request->session()->regenerate();

            $this->clearLoginAttempts($request);
        } else {
            if ($request->filled('keep_login')) {
                $rememberTokenExpireMinutes = 5256000; //10 year

                $rememberTokenName = auth()->guard('member')->getRecallerName();

                Cookie::queue($rememberTokenName, Cookie::get($rememberTokenName), $rememberTokenExpireMinutes);

                $request->session()->regenerate();

                $this->clearLoginAttempts($request);
            }
        }

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     * @throws ValidationException
     * @author Sang Nguyen
     */
    function attemptLogin(Request $request) {
        if ($this->guard()->validate($this->credentials($request))) {
            /** @var Member $member */
            $member = $this->guard()->getLastAttempted();

            $dateCurrent = Carbon::now()->format('Y-m-d');
            $start_block_time = $member->start_block_time ? Carbon::parse($member->start_block_time)->format('Y-m-d') : $dateCurrent;
            $end_block_time = $member->end_block_time ? Carbon::parse($member->end_block_time)->format('Y-m-d') : $dateCurrent;

            // block user  block_user  0: none;1: block_secret_garden; 2:block_all_service; 3: block_permanent; 4:force_cancel_account
            if ($member->block_user == 3 &&  $start_block_time <= $dateCurrent) {
                $end_block_time = $member->end_block_time ? Carbon::parse($member->end_block_time)->format('Y-m-d') : '2080-12-31';
                if($end_block_time >= $dateCurrent){
                    throw ValidationException::withMessages([
                        $this->username() => '이 사용자가 ' . $end_block_time  .' 까지 차단되었습니다.',
                        'password' => [$member->block_reason],
                    ]);
                }

            }
            if ($member->block_user == 4) {
                throw ValidationException::withMessages([
                    $this->username() => ['이 계정은 강제적으로 삭제되었습니다.'],
                    'password' => [$member->block_reason],
                ]);
            }

            // block_all_service
            if ($member->block_user == 2 && $start_block_time <= $dateCurrent && $end_block_time >= $dateCurrent) {
                $msg = '이 사용자가 차단되었습니다.';
                if($member->end_block_time){
                    $msg = '이 사용자는 ' .  Carbon::parse($member->end_block_time)->format('Y-m-d') .' 까지 차단되었습니다.';
                }

                throw ValidationException::withMessages([
                    $this->username() => [$msg],
                    'password' => [$member->block_reason],
                ]);
            }


            // 이메일 인증 체크
            if (!$member->email_verified) {
                throw ValidationException::withMessages([
                    'verifySMS' => [
                        trans('plugins/member::dashboard.not_email_verified', [
                            'resend_link' => route('public.member.verify.email.resend'),
                        ]),
                    ],
                ]);
            }

            $token = $this->jwt($member);

            $memberToken = MemberToken::create([
                'member_id' => $member->id,
                'token' => $token,
                'status' => MemberToken::LOGIN
            ]);

            $member->setMemberToken($memberToken);

            $countLogin = $member->count_login + 1;
            $member->update(['last_login' => date('Y-m-d H:i:s'), 'count_login' => $countLogin]);

            $request->session()->put('member_token', $token);

            if (empty($member->verify_at)) {
                $verifyTokenExpireMinutes = 300; // 5minus
                Cookie::queue('verify_phone', $request->id_login, $verifyTokenExpireMinutes);
                throw ValidationException::withMessages([
                    'verifySMS' => [
                        trans('plugins/member::member.not_confirmed', [
                            'resend_link' => route('public.member.verify'),
                        ]),
                    ],
                ]);
            }
            return $this->baseAttemptLogin($request);
        }
        return false;
    }

    function logout(Request $request) {
        $memberAccess = AccessGarden::where('member_id', auth()->guard('member')->user()->id)->first();

        if (isset($memberAccess)) {
            $memberAccess->status = 'pending';
            $memberAccess->save();
        }
        if (Cookie::get('password_garden') !== null) {
            Cookie::queue(Cookie::forget('password_garden'));
        }

        $activeGuards = 0;
        $this->guard()->logout();

        foreach (config('auth.guards') as $guard => $guardConfig) {
            if ($guardConfig['driver'] === 'session') {
                if ($this->isActiveGuard($request, $guard)) {
                    $activeGuards++;
                }
            }
        }

        if (!$activeGuards) {
            $request->session()->flush();
            $request->session()->regenerate();
        }

        if (Cookie::get(auth()->guard('member')->getRecallerName()) !== null) {
            Cookie::queue(Cookie::forget(auth()->guard('member')->getRecallerName()));
        }

        return redirect()->to($this->logoutToPath());
    }

    function username() {
        return 'id_login';
    }

    /**
     * Validate the user login request.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    function validateLogin(Request $request) {
        $id_login = $request->id_login;
        $rules = [
            'id_login' => [
                'required',
                'string',
                Rule::exists('members')->where(function ($query) use ($id_login) {
                    $query->where('id_login', $id_login)->where('is_blacklist', Member::IS_NOT_BLACKLIST);
                }),
            ],
            'password' => 'required_with:id_login',
        ];
        $customMessages = [
            'id_login.required' => '아이디를 입력해주세요.',
            'password.required_with' => '비밀번호를 입력해주세요.',
            'id_login.exists' => '계정이 존재하지 않습니다',
        ];

        $this->validate($request, $rules, $customMessages);
    }

    protected function jwt($user) {
        $payload = [
            'iss' => 'lumen-jwt', // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60 * 60 * 24 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.

        return JWT::encode($payload, env('JWT_SECRET'));
    }
}
