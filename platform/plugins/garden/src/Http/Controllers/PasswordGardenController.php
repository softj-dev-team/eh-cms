<?php

namespace Botble\Garden\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Garden\Forms\PasswordGardenForm;
use Botble\Garden\Http\Requests\PasswordGardenRequest;
use Botble\Member\Models\Member;
use Botble\Setting\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordGardenController extends BaseController
{

    /**
     * manage password gardens
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author BM Phuoc
     * @throws \Throwable
     */
    public function getManagePW()
    {

        page_title()->setTitle('비밀단어 관리');

        return view('plugins.garden::elements.tables.actions.manage_pw');
    }
    /**
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return string
     */
    public function getEdit(FormBuilder $formBuilder, Request $request) {
        page_title()->setTitle('비밀번호 변경 ');

        return $formBuilder->create(PasswordGardenForm::class)->renderForm();
    }

    /**
     * @param PasswordGardenRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postEdit(PasswordGardenRequest $request, BaseHttpResponse $response) {
        $passwd = Setting::where('key', 'password_garden')->first();

        if (is_null($passwd)) {
            DB::table('settings')->insert([
                'key' => 'password_garden',
                'value' => Hash::make('admin@123'),
                'value_1' => 'admin@123',
            ]);
            $passwd = Setting::where('key', 'password_garden')->first();
        }

        $passwd->value = Hash::make($request->input('password'));
        $passwd->value_1 = $request->input('password');
        $passwd->save();

        $members = Member::select('id', 'passwd_garden');
        foreach ($members as $key => $item) {
            $item->passwd_garden = $this->getPasswordGarden();
            $item->save();
        }

        event(new UpdatedContentEvent(PASSWORD_GARDEN_MODULE_SCREEN_NAME, $request, $passwd));

        return $response
            ->setPreviousUrl(route('garden.manage_pw.list'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getReset(BaseHttpResponse $response) {
        $passwd = Setting::where('key', 'password_garden')->first();

        if (is_null($passwd)) {
            DB::table('settings')->insert([
                'key' => 'password_garden',
                'value' => Hash::make('admin@123'),
                'value_1' => 'admin@123',
            ]);
            $passwd = Setting::where('key', 'password_garden')->first();
        }
        $passwd->value = Hash::make('admin@123');
        $passwd->value_1 = 'admin@123';
        $passwd->save();
        $members = Member::select('id', 'passwd_garden');
        foreach ($members as $key => $item) {
            $item->passwd_garden = $this->getPasswordGarden();
            $item->save();
        }

        return $response
            ->setPreviousUrl(route('garden.manage_pw.list'))
            ->setMessage("Password default : admin@123 ");
    }

    /**
     * @return string
     */
    public function getPasswordGarden() {
        return strtoupper(substr(md5(microtime()), rand(0, 26), 4));
    }
}
