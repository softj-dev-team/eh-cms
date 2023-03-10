<?php

namespace Botble\ACL\Http\Controllers;

use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\EmailHandler;
use Botble\Member\Forms\SendMailForm;
use Botble\Setting\Supports\SettingStore;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MailVariable;


class MailController extends Controller
{
    protected $mailer;
    protected $setting;
    protected $response;

    /**
     * TestSendMailCommand constructor.
     * @param EmailHandler $mailer
     */
    public function __construct(
        EmailHandler $mailer,
        SettingStore $setting,
        BaseHttpResponse $response
    ) {
        $this->mailer = $mailer;
        $this->setting = $setting;
        $this->response = $response;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    public function postSendMail(Request $request)
    {
        try {
            $title = $request->input('title');
            $mailTo = $request->input('mailTo');
            $member_content = $request->input('content');
            MailVariable::setModule(SEND_MAIL_TO_MEMBER)
                ->setVariableValues([
                    'member_content'  =>  $member_content,
                ]);
            $content = MailVariable::prepareData(get_setting_email_template_content('plugins', 'member', 'member'));
            $this->mailer->send($content, $title, $mailTo, "", true);
            return $this->response->setMessage(trans('plugins/contact::contact.email.success'));
        } catch (Exception $ex) {
            info($ex->getMessage());
            return $this->response
                ->setError()
                ->setMessage(trans('plugins/contact::contact.email.failed'));
        }
    }

    public  function createMail(FormBuilder $formBuilder)
    {
        // page_title()->setTitle('Send Mail / Create');
        page_title()->setTitle('이메일 보내기 / 작성하기');

        return $formBuilder
            ->create(SendMailForm::class)
            ->renderForm();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
