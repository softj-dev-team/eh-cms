<?php

namespace Botble\Setting\Http\Controllers;

use Assets;
use Botble\Base\Supports\EmailHandler;
use Botble\Media\RvMedia;
use Botble\Setting\Http\Requests\EmailTemplateRequest;
use Botble\Setting\Http\Requests\MediaSettingRequest;
use Botble\Setting\Http\Requests\SendTestEmailRequest;
use Botble\Setting\Repositories\Interfaces\SettingInterface;
use Botble\Setting\Supports\SettingStore;
use Exception;
use Illuminate\Support\Facades\File;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends BaseController
{
    /**
     * @var SettingInterface
     */
    protected $settingRepository;

    /**
     * SettingController constructor.
     * @param SettingInterface $settingRepository
     */
    public function __construct(SettingInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getOptions()
    {
        page_title()->setTitle(trans('core/setting::setting.title'));

        return view('core.setting::index');
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param SettingStore $setting
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit(Request $request, BaseHttpResponse $response, SettingStore $setting)
    {
        foreach ($request->except(['_token']) as $setting_key => $setting_value) {
            $setting->set($setting_key, $setting_value);
        }

        $setting->save();

        return $response
            ->setPreviousUrl(route('settings.options'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function getWordConfig()
    {
        page_title()->setTitle('욕설');
        $word_rejects = get_word_rejects();
        $word_rejects = implode(",",$word_rejects);
        return view('core.setting::word', compact('word_rejects'));
    }

    public function postEditWordConfig(Request $request, BaseHttpResponse $response, SettingStore $setting)
    {
        if ($request->has('word_rejects')) {
//            $arrayStr = array_map('trim', explode(',', $request->input('word_rejects')));
            $arrayStr = explode(',', $request->input('word_rejects'));
            $setting->set('word_rejects', json_encode($arrayStr));
            $setting->save();
        }

        return $response
            ->setPreviousUrl(route('settings.word'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEmailConfig()
    {
        page_title()->setTitle(trans('core/base::layouts.setting_email'));
        Assets::addAppModule(['setting']);

        return view('core.setting::email');
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param SettingStore $setting
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEditEmailConfig(Request $request, BaseHttpResponse $response, SettingStore $setting)
    {
        foreach ($request->except(['_token']) as $setting_key => $setting_value) {
            $setting->set($setting_key, $setting_value);
        }

        $setting->save();

        return $response
            ->setPreviousUrl(route('settings.email'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $type
     * @param $name
     * @param $template_file
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getEditEmailTemplate($type, $name, $template_file)
    {
        page_title()->setTitle(trans(config($type . '.' . $name . '.email.templates.' . $template_file . '.title', '')));

        Assets::addAppModule(['setting'])
            ->addStylesDirectly([
                'vendor/core/packages/codemirror/lib/codemirror.css',
                'vendor/core/packages/codemirror/addon/hint/show-hint.css',
                'vendor/core/css/setting.css',
            ])
            ->addScriptsDirectly([
                'vendor/core/packages/codemirror/lib/codemirror.js',
                'vendor/core/packages/codemirror/lib/css.js',
                'vendor/core/packages/codemirror/addon/hint/show-hint.js',
                'vendor/core/packages/codemirror/addon/hint/anyword-hint.js',
                'vendor/core/packages/codemirror/addon/hint/css-hint.js',
            ]);


        $email_content = get_setting_email_template_content($type, $name, $template_file);
        $email_subject = get_setting_email_subject($type, $name, $template_file);
        $plugin_data = [
            'type'          => $type,
            'name'          => $name,
            'template_file' => $template_file,
        ];

        return view('core.setting::email-template-edit', compact('email_subject', 'email_content', 'plugin_data'));
    }

    /**
     * @param EmailTemplateRequest $request
     * @param BaseHttpResponse $response
     * @param SettingStore $setting
     * @return BaseHttpResponse
     */
    public function postStoreEmailTemplate(EmailTemplateRequest $request, BaseHttpResponse $response, SettingStore $setting)
    {
        if ($request->has('email_subject_key')) {
            $setting->set($request->input('email_subject_key'), $request->input('email_subject'));
            $setting->save();
        }

        save_file_data($request->input('template_path'), $request->input('email_content'), false);

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postResetToDefault(Request $request, BaseHttpResponse $response)
    {
        $this->settingRepository->deleteBy(['key' => $request->input('email_subject_key')]);
        File::delete($request->input('template_path'));

        return $response->setMessage(trans('core/setting::setting.email.reset_success'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param SettingStore $setting
     * @return BaseHttpResponse
     */
    public function postChangeEmailStatus(Request $request, BaseHttpResponse $response, SettingStore $setting)
    {
        $setting
            ->set($request->input('key'), $request->input('value'))
            ->save();

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param BaseHttpResponse $response
     * @param SendTestEmailRequest $request
     * @param EmailHandler $emailHandler
     * @return BaseHttpResponse
     * @throws \Throwable
     * @author Sang Nguyen
     */
    public function postSendTestEmail(
        BaseHttpResponse $response,
        SendTestEmailRequest $request,
        EmailHandler $emailHandler
    )
    {
        try {
            $emailHandler->send(
                file_get_contents(core_path('setting/resources/email-templates/test.tpl')),
                __('Test title'),
                $request->input('email'),
                [],
                true
            );
            return $response->setMessage(__('Send email successfully!'));
        } catch (Exception $exception) {
            return $response->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getMediaSetting()
    {
        page_title()->setTitle(trans('core/setting::setting.media.title'));
        return view('core.setting::media');
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param SettingStore $setting
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEditMediaSetting(
        MediaSettingRequest $request,
        BaseHttpResponse $response,
        SettingStore $setting
    )
    {
        foreach ($request->except(['_token']) as $setting_key => $setting_value) {
            $setting->set($setting_key, $setting_value);
        }

        $setting->save();

        return $response
            ->setPreviousUrl(route('settings.media'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function getLogo(){
        page_title()->setTitle('Logo');
        return view('core.setting::logo');
    }

    public function updateLogo(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->hasFile('image') && $request->get('type') == 'logo'){
            $file = $request->file('image');
            Storage::disk('local')->delete('/public/uploads/back-end/logo/eh-logo.png');
            $path = $file->storeAs('/uploads/back-end/logo/', 'eh-logo.png', 'public');
        }
        if ($request->hasFile('favicon') && $request->get('type') == 'favicon'){
            $file = $request->file('favicon');
            Storage::disk('local')->delete('/public/uploads/back-end/logo/favicon.png');
            $path = $file->storeAs('/uploads/back-end/logo/', 'favicon.png', 'public');
        }

        if ($request->hasFile('logo') && $request->get('type') == 'logo-bottom'){
            $file = $request->file('logo');
            Storage::disk('local')->delete('/public/uploads/back-end/logo/ewha-logo-grey.png');
            $path = $file->storeAs('/uploads/back-end/logo/', 'ewha-logo-grey.png', 'public');
        }

        if ($request->hasFile('logo') && $request->get('type') == 'logo-loading'){
            $file = $request->file('logo');
            Storage::disk('local')->delete('/public/uploads/back-end/logo/logo-spinner.png');
            $path = $file->storeAs('/uploads/back-end/logo/', 'logo-spinner.png', 'public');
        }

        if ($request->hasFile('logo') && $request->get('type') == 'contact-page'){
            $file = $request->file('logo');
            Storage::disk('local')->delete('/public/uploads/contact.png');
            $path = $file->storeAs('/uploads/', 'contact.png', 'public');
        }
        return response()->json('Image uploaded successfully');
    }
}
