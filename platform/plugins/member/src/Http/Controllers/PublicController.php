<?php

namespace Botble\Member\Http\Controllers;

use Assets;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Media\Http\Requests\MediaFileRequest;
use Botble\Media\Models\MediaFolder;
use Botble\Member\Http\Requests\AvatarRequest;
use Botble\Member\Http\Requests\FreshmenRequest;
use Botble\Member\Http\Requests\PasswordConfirmationRequest;
use Botble\Member\Http\Requests\SettingRequest;
use Botble\Member\Http\Requests\UpdatePasswordRequest;
use Botble\Member\Models\MemberSetting;
use Botble\Member\Models\MemberToken;
use Botble\Member\Repositories\Interfaces\MemberActivityLogInterface;
use Botble\Member\Repositories\Interfaces\MemberInterface;
use Botble\Member\Services\CropAvatar;
use Botble\Member\Models\Member;
use Exception;
use File;
use Hash;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use RvMedia;
use SeoHelper;
use Storage;
use Illuminate\Support\Facades\Validator;

class PublicController extends Controller
{
    /**
     * @var MemberInterface
     */
    protected $memberRepository;

    /**
     * @var MemberActivityLogIntecancelRequestrface
     */
    protected $activityLogRepository;

    /**
     * PublicController constructor.
     * @param Repository $config
     * @param MemberInterface $memberRepository
     * @param MemberActivityLogInterface $memberActivityLogRepository
     */
    public function __construct(
        Repository $config,
        MemberInterface $memberRepository,
        MemberActivityLogInterface $memberActivityLogRepository
    ) {
        $this->middleware(function ($request, $next) {
            $cookiePassword = Cookie::get('passwd_member');
            $password = auth()->guard('member')->user()->password;

            if (Hash::check($cookiePassword, $password) || Route::currentRouteName() == 'public.member.dashboard') {
                return $next($request);
            }

            return redirect()->route('home.index')->with('permission', 'Session timeout!');
        });

        $this->memberRepository = $memberRepository;
        $this->activityLogRepository = $memberActivityLogRepository;

        Assets::setConfig($config->get('plugins.member.assets'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getDashboard() {
        $user = auth()->guard('member')->user();

        SeoHelper::setTitle(auth()->guard('member')->user()->getFullName());

        // return view('plugins.member::dashboard.index', compact('user'));
        return view('plugins.member::dashboard.dashboard', compact('user'));
    }

    /**
     * @return \Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @author Sang Nguyen
     */
    public function getSettings() {
        SeoHelper::setTitle(__('계정 설정'));
        $user = auth()->guard('member')->user();
        return view('plugins.member::settings.index', compact('user'));
    }

    /**
     * @param SettingRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang NguyenPublicC
     */
    public function postSettings(SettingRequest $request, BaseHttpResponse $response) {
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');

        if ($year && $month && $day) {
            $request->merge(['dob' => implode('-', [$year, $month, $day])]);

            $validator = Validator::make($request->input(), [
                'dob' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return redirect()->route('public.member.settings');
            }
        }

        $this->memberRepository->createOrUpdate($request->input(), ['id' => auth()->guard('member')->user()->getKey()]);

        $member = auth()->guard('member')->user();


        $this->activityLogRepository->createOrUpdate(['action' => 'update_setting']);
        return $response
            ->setNextUrl(route('public.member.settings'))
            ->setMessage(__('Update profile successfully!'));
    }

    /**
     * @return \Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @author Sang Nguyen
     */
    public function getSecurity() {
        SeoHelper::setTitle(__('Security'));
        return view('plugins.member::settings.security');
    }

    /**
     * @param UpdatePasswordRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postSecurity(UpdatePasswordRequest $request, BaseHttpResponse $response) {
        if (!Hash::check($request->input('current_password'), auth()->guard('member')->user()->getAuthPassword())) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/member::dashboard.current_password_not_valid'));
        }

        $this->memberRepository->update(['id' => auth()->guard('member')->user()->getKey()], [
            'password' => bcrypt($request->input('password')),
        ]);

        $this->activityLogRepository->createOrUpdate(['action' => 'update_security']);

        return $response->setMessage(trans('plugins/member::dashboard.password_update_success'));
    }

    public function getNotification() {
        try {
            SeoHelper::setTitle(__('Notification'));

            $member_token = session()->get('member_token', null);

            if ($member_token != null) {
                $member = MemberToken::where('token', $member_token)->first()->member;
                if ($member != null) {
                    $setting = MemberSetting::where('member_id', $member->id)->first();
                    return view('plugins.member::settings.notification', compact('setting'));
                }
            }
            return view('plugins.member::settings.notification');
        } catch (Exception $ex) {
            return view('plugins.member::settings.notification');
        }
    }

    public function postNotification(Request $request) {
        try {
            $member_token = $request->session()->get('member_token', null);
            if ($member_token != null) {
                $member = MemberToken::where('token', $member_token)
                    ->first()
                    ->member;
                if ($member != null) {
                    $setting = [
                        'member_id' => $member->id,
                        'site_notice' => $request->get('siteNotice') == null ? 0 : 1,
                        'eh_content' => $request->get('ehContent') == null ? 0 : 1,
                        'bulletin_comment_on_post' => $request->get('bulletinCommentOnPost') == null ? 0 : 1,
                        'bulletin_comment_on_comment' => $request->get('bulletinCommentOnComment') == null ? 0 : 1,
                        'secret_garden_comment_on_post' => $request->get('secretGardenCommentOnPost') == null ? 0 : 1,
                        'secret_garden_comment_on_comment' => $request->get('secretGardenCommentOnComment') == null ? 0 : 1,
                        'garden_notice' => $request->get('gardenNotice') == null ? 0 : 1,
                        'garden_new_post' => $request->get('gardenNewPost') == null ? 0 : 1,
                        'garden_comment_on_post' => $request->get('gardenCommentOnPost') == null ? 0 : 1,
                        'garden_comment_on_comment' => $request->get('gardenCommentOnComment') == null ? 0 : 1,
                        'message_notification' => $request->get('messageNotification') == null ? 0 : 1
                    ];
                    MemberSetting::updateOrCreate(
                        ['member_id' => $member->id],
                        $setting
                    );
                    return redirect()->route('public.member.notification')->with('message', 'Updated!!');
                }
            }
            $this->getSettings();
        } catch (Exception $ex) {
            $this->getSettings();
        }
    }

    /**
     * @param AvatarRequest $request
     * @return array
     * @author Sang Nguyen <sangnguyenplus@gmail.com>s
     */
    public function postAvatar(AvatarRequest $request) {
        try {
            $member = auth()->guard('member')->user();

            $file = $request->file('avatar_file');
            $fileName = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();

            $avatar = [
                'path' => config('plugins.member.general.avatar.folder.container_dir') . DIRECTORY_SEPARATOR . md5($member->email) . '/full-' . Str::slug(basename(
                        $fileName,
                        $fileExtension
                    )) . '-' . time() . '.' . $fileExtension,
                'realPath' => config('plugins.member.general.avatar.folder.container_dir') . DIRECTORY_SEPARATOR . md5($member->email) . '/thumb-' . Str::slug(basename(
                        $fileName,
                        $fileExtension
                    )) . '-' . time() . '.' . $fileExtension,
                'ext' => $fileExtension,
                'mime' => $file->getMimeType(),
                'name' => $fileName,
                'user' => $member->id,
                'size' => $file->getSize(),

            ];
            File::deleteDirectory(config('plugins.member.general.avatar.folder.upload') . DIRECTORY_SEPARATOR . config('plugins.member.general.avatar.folder.container_dir') . DIRECTORY_SEPARATOR . md5($member->email));

            config()->set('filesystems.disks.local.root', config('plugins.member.general.avatar.folder.upload'));

            Storage::put($avatar['path'], file_get_contents($file->getRealPath()), 'public');
            $crop = new CropAvatar($request->input('avatar_src'), $request->input('avatar_data'), $avatar);
            $member->avatar = str_replace(
                    public_path(),
                    '',
                    config('plugins.member.general.avatar.folder.upload')
                ) . '/' . $crop->getResult();

            $this->memberRepository->createOrUpdate($member);

            $this->activityLogRepository->createOrUpdate([
                'action' => 'changed_avatar',
            ]);

            return [
                'error' => false,
                'message' => __('plugins/member::dashboard.update_avatar_success'),
                'result' => $member->avatar,
            ];
        } catch (Exception $ex) {
            return [
                'error' => true,
                'message' => $ex->getMessage(),
            ];
        }
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getActivityLogs(BaseHttpResponse $response) {
        $activities = $this->activityLogRepository->getAllLogs(auth()->guard('member')->user()->getKey());

        foreach ($activities->items() as &$activity) {
            $activity->description = $activity->getDescription();
        }

        return $response->setData($activities);
    }

    /**
     * @param MediaFileRequest $request
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function postUpload(MediaFileRequest $request) {
        $result = RvMedia::handleUpload($request->file('upload'), 0, 'members');

        if ($result['error'] == false) {
            $file = $result['data'];
            return response('<script>parent.setImageValue("' . url($file->url) . '"); </script>')->header(
                'Content-Type',
                'text/html'
            );
        }

        return response('<script>alert("' . Arr::get($result, 'message') . '")</script>')->header(
            'Content-Type',
            'text/html'
        );
    }

    /**
     * @param PasswordConfirmationRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postAccountDelete(PasswordConfirmationRequest $request, BaseHttpResponse $response) {
        try {
            $member = auth()->guard('member')->user();
            $this->memberRepository->delete($member);

            return $response->setNextUrl(route(url('/')))->setMessage(__('Delete Account successfully!'));
        } catch (Exception $exception) {
            return $response->setError()->setMessage(trans('core/base::notices.cannot_delete'));
        }
    }

    public function getSproutAuthentication() {
        SeoHelper::setTitle('새내기 인증');

        $user = auth()->guard('member')->user();

        return view('plugins.member::settings.freshman', compact('user'));
    }

    public function getEwhainAuthentication() {
        SeoHelper::setTitle('Ewhain Authentication');

        $user = auth()->guard('member')->user();

        return view('plugins.member::settings.freshman', compact('user'));
    }

    public function postFreshman(FreshmenRequest $request, BaseHttpResponse $response) {
        $member = auth()->guard('member')->user();

        if ($request->freshman1) {
            $file = $request->file('freshman1');
            $fileName = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();

            $freshman1 = [
                'path' => config('plugins.member.general.freshman1.folder.container_dir') . DIRECTORY_SEPARATOR . md5($member->email) . '/full-' . Str::slug(basename(
                        $fileName,
                        $fileExtension
                    )) . '-' . time() . '.' . $fileExtension,
                'realPath' => config('plugins.member.general.freshman1.folder.container_dir') . DIRECTORY_SEPARATOR . md5($member->email) . '/thumb-' . Str::slug(basename(
                        $fileName,
                        $fileExtension
                    )) . '-' . time() . '.' . $fileExtension,
                'ext' => $fileExtension,
                'mime' => $file->getMimeType(),
                'name' => $fileName,
                'user' => $member->id,
                'size' => $file->getSize(),

            ];
            File::deleteDirectory(config('plugins.member.general.freshman1.folder.upload') . DIRECTORY_SEPARATOR . config('plugins.member.general.freshman1.folder.container_dir') . DIRECTORY_SEPARATOR . md5($member->email));

            config()->set('filesystems.disks.local.root', config('plugins.member.general.freshman1.folder.upload'));
            Storage::put($freshman1['path'], file_get_contents($file->getRealPath()), 'public');
            $member->freshman1 = str_replace(
                    public_path(),
                    '',
                    config('plugins.member.general.freshman1.folder.upload')
                ) . '/' . $freshman1['path'];

            $member->status_fresh1 = 1;
            $member->sprouts_number = $request->sprouts_number ?? '';
            $member->note_freshman1 = $request->note_freshman1 ?? '';
            $member->update_freshman1 = today();

            $this->memberRepository->createOrUpdate($member, ['id' => auth()->guard('member')->user()->getKey()]);
            return $response
                ->setNextUrl(route('public.member.get.freshman.sprout'))
                ->setMessage('Update freshman successfully!');
        }

        if ($request->freshman2) {
            //
            $file = $request->file('freshman2');
            $fileName = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();

            $freshman2 = [
                'path' => config('plugins.member.general.freshman2.folder.container_dir') . DIRECTORY_SEPARATOR . md5($member->email) . '/full-' . Str::slug(basename(
                        $fileName,
                        $fileExtension
                    )) . '-' . time() . '.' . $fileExtension,
                'realPath' => config('plugins.member.general.freshman2.folder.container_dir') . DIRECTORY_SEPARATOR . md5($member->email) . '/thumb-' . Str::slug(basename(
                        $fileName,
                        $fileExtension
                    )) . '-' . time() . '.' . $fileExtension,
                'ext' => $fileExtension,
                'mime' => $file->getMimeType(),
                'name' => $fileName,
                'user' => $member->id,
                'size' => $file->getSize(),

            ];
            File::deleteDirectory(config('plugins.member.general.freshman2.folder.upload') . DIRECTORY_SEPARATOR . config('plugins.member.general.freshman2.folder.container_dir') . DIRECTORY_SEPARATOR . md5($member->email));

            config()->set('filesystems.disks.local.root', config('plugins.member.general.freshman2.folder.upload'));
            Storage::put($freshman2['path'], file_get_contents($file->getRealPath()), 'public');
            $member->freshman2 = str_replace(
                    public_path(),
                    '',
                    config('plugins.member.general.freshman2.folder.upload')
                ) . '/' . $freshman2['path'];

            $member->status_fresh2 = 1;
            $member->sprouts_number = $request->sprouts_number ?? '';
            $member->note_freshman2 = $request->note_freshman2 ?? '';
            $member->auth_studentid = $request->auth_studentid ?? '';
            $member->update_freshman2 = today();

            $this->memberRepository->createOrUpdate($member, ['id' => auth()->guard('member')->user()->getKey()]);
            return $response
                ->setNextUrl(route('public.member.get.freshman.ewhain'))
                ->setMessage('Update freshman successfully!');
        }

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function cancelRequest(Request $request, BaseHttpResponse $response) {
        /** @var Member $currentUser */
        $currentUser = auth()->guard('member')->user();

        $type = $request->get('type');

        switch ($type) {
            case 'fresh1':
                $this->memberRepository->update(['id' => $currentUser->getKey()], [
                    'status_fresh1' => 0,
                    'note_freshman1' => NULL,
                    'freshman1' => NULL,
                    'update_freshman1' => NULL
                ]);
                break;

            case 'fresh2':
                $this->memberRepository->update(['id' => $currentUser->getKey()], [
                    'status_fresh2' => 0,
                    'note_freshman2' => NULL,
                    'freshman2' => NULL,
                    'auth_studentid' => NULL,
                    'update_freshman2' => NULL
                ]);
                break;
        }

        return $response->setMessage(trans('plugins/member::dashboard.cancel_request_success'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function deleteImage(Request $request, BaseHttpResponse $response) {
        /** @var Member $currentUser */
        $currentUser = auth()->guard('member')->user();

        $type = $request->get('type');

        switch ($type) {
            case 'fresh1':
                $this->memberRepository->update(['id' => $currentUser->getKey()], [
                    'freshman1' => NULL,
                ]);
                break;

            case 'fresh2':
                $this->memberRepository->update(['id' => $currentUser->getKey()], [
                    'freshman2' => NULL,
                ]);
                break;
        }

        return $response->setMessage('Delete image successfully');
    }
}
