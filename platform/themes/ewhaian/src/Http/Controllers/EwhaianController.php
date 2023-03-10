<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Campus\Models\Evaluation\CommentsEvaluation;
use Botble\Member\Models\Member;
use Botble\Report\Repositories\Interfaces\ReportInterface;
use Botble\Slides\Models\Slides;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Theme;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;

class EwhaianController extends Controller
{
    /**
     * @var ReportInterface
     */
    protected $gardenRepository;

    /**
     * EwhaianController constructor.
     * @param ReportInterface $reportRepository
     * @author Thanh Tran
     */
    public function __construct(ReportInterface  $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function index()
    {
        return Theme::scope('index')->render();
    }

    public function clicker(Request $request)
    {

        $key = $request->key;
        $slide = Slides::where('code', 'ACCOUNT')->where('status', 'publish')->first();

        $list_images = $slide->getImageGallery();
        $list_image_new = [];
        foreach ($list_images->images as $key_item => $item) {
            if ($key_item == $key) {
                isset($item['count']) ? $item['count']++ : $item['count'] = 1;
                # code...
            }
            array_push($list_image_new, $item);

        }
        $list_images->images = json_encode($list_image_new);
        $list_images->save();
        return response()->json([
            'status' => true,
        ]);
    }

    public function getDownload(Request $request)
    {
        $url = $request->input('url');
        $filename = basename($url);

        if (substr($url, 0, 1) == "/") {
            $url = substr($url, 1);
        }
        return response()->download($url, $filename);
    }

    public function postReport(Request $request)
    {
        $request->merge(['member_id' => auth()->guard('member')->user()->id]);
        $request->merge(['person_report_id' => auth()->guard('member')->user()->id_login]);  // person report
        $memberReported = Member::select('id_login')->where('id',$request->input('reported_id') )->first();
        $request->merge(['reported_id' => $memberReported->id_login]);  // person is reported
        $this->reportRepository->create($request->input());
        $password_garden = Cookie::get('password_garden') ? Cookie::get('password_garden') : Cookie::get('password_garden_report');
//        if($request->input('type_post') == 9 && $request->input('type_post') == 1){
//            return redirect()->route('gardenFE.show', ['id' => $request->input('categories_gardens_id') ?? 1])
//                ->with('success', '신고 처리가 완료되었습니다.')
//                ->withCookie(cookie('password_garden_report', $password_garden, 30));
//        }
        if ($request->get('is_garden') !== null){
            return redirect()->route('gardenFE.list')->with('success', '신고 처리가 완료되었습니다.')->withCookie(cookie('password_garden_report', $password_garden, 30));
        }
        return redirect()->back()->with('msg', '신고 처리가 완료되었습니다.')->withCookie(cookie('password_garden_report', $password_garden, 30));
    }

    public function deleteComment($id){
        $delete = CommentsEvaluation::where('id', $id)->delete();
        if (!$delete){
            return redirect()->back();
        }

        return redirect()->back()->with('msg', '삭제 성공.');
    }

    public function postPasswd(Request $request)
    {

        $rules = [
            'passwd_member' => [
                'required',
                'min:6',
                'regex:/^.*[^A-Z].$/'
            ],
        ];

        $message = [
            'passwd_member.min' => '비밀 번호는 최소 8 자 이상이어야합니다.',
            'passwd_member.regex' => '비밀번호는 대문자가 아니어야합니다!'
        ];

        $validator = Validator::make( $request->all(), $rules, $message );
        if ( $validator->fails() )
        {
            return response()->json([
                    'msg' =>  "<div style='color: #EC1469;'>" .$validator->errors()->first(). "</div>",
                    'check' => false
                ]
                , 200);
        }

        if (Hash::check( $request->passwd_member, auth()->guard('member')->user()->password )) {
            Cookie::queue('passwd_member', $request->passwd_member, '5');
            return response()->json(array('check' => true), 200);
        }
        $msg = "<div style='color: #EC1469;'>" . __('controller.please_check_your_password') . "</div>";
        return response()->json(array('msg' => $msg, 'check' => false), 200);
    }
}
