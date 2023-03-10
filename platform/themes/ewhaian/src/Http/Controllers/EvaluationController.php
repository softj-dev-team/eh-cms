<?php

namespace Theme\Ewhaian\Http\Controllers;

use Theme;
use Illuminate\Http\Request;
use App\Traits\NotificationTrait;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Botble\Campus\Models\Evaluation\Major;
use Botble\Campus\Models\Genealogy\Genealogy;
use Botble\Campus\Models\Evaluation\Evaluation;
use Botble\Campus\Models\Notices\NoticesCampus;
use Botble\Campus\Models\Description\DescriptionCampus;
use Botble\Campus\Models\Evaluation\CommentsEvaluation;
use Botble\Introduction\Models\Notices\NoticesIntroduction;

class EvaluationController extends Controller
{
    use NotificationTrait;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (hasPermission('evaluationFE.list')) {
                return $next($request);
            };
            return redirect()->route('home.index')->with('permission', __('home.no_permisson'));
        });
    }

    /**
     * @return \Response
     */
    public static function index()
    {
        $notices = NoticesIntroduction::code('EVALUATION_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = DescriptionCampus::where('code', 'EVALUATION_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $evaluation = Evaluation::where('status', 'publish')->orderBy('created_at', 'DESC')->paginate(20);

        Theme::breadcrumb()->add(__('campus'), route('campus.evaluation_comments_major'))->add(__('campus.evaluation'), 'http:...');

        Theme::setTitle(__('campus') . ' | ' . __('campus.evaluation'));

        return Theme::scope('campus.evaluation.index', [
            'evaluation' => $evaluation,
            'notices' => $notices,
            'description' => $description
        ])->render();
    }

    public static function detailNotice($id)
    {
        $notices = NoticesIntroduction::where('id', $id)->where('status', 'publish')->first();
        $notices->lookup = $notices->lookup + 1;
        $notices->save();

        $evaluation = Evaluation::where('status', 'publish')->ordered()->paginate(10);

        Theme::breadcrumb()->add(__('campus.genealogy'), route('campus.genealogy_list'))
            ->add(__('campus.genealogy'), 'http:...');

        Theme::setTitle(__('campus') . ' | ' . __('campus.genealogy') );

        $description = DescriptionCampus::where('code', 'GENEALOGY_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $canCreate = hasPermission('oldGenealogyFE.create');
        return Theme::scope('campus.evaluation.notice', [
            'evaluation' => $evaluation,
            'notices' => $notices,
            'description' => $description,
            'canCreate' => $canCreate,
            'subList' => [
                'evaluation' => $evaluation,
                'canCreate' => $canCreate,
            ]
        ])->render();


    }

    public function show($id)
    {
        $evaluation = Evaluation::where('id', $id)->where('status', '!=', 'draft')->ordered()->firstOrFail();
        $evaluation->lookup = $evaluation->lookup + 1;
        $evaluation->save();

        Theme::breadcrumb()->add(__('campus.evaluation'), route('campus.evaluation_comments_major'))
           ->add($evaluation->title, 'http:...')
        ;

        Theme::setTitle(__('campus') . ' | ' . __('campus.evaluation') . ' | ' . $evaluation->title);

        $canCreateComment = hasPermission('evaluationFE.comment.create');
        $canViewComment = hasPermission('evaluationFE.comment');
        return Theme::scope('campus.evaluation.details', [
            'evaluation' => $evaluation,
            'votes' => $evaluation->comments->avg('votes'),
            'canCreateComment' => $canCreateComment,
            'canViewComment' => $canViewComment
        ])->render();
    }

    public function createComment(Request $request)
    {
        if (!hasPermission('evaluationFE.comment.create')) {
            return redirect()->route('home.index')->with('permission', __('home.no_permisson'));
        };

        $request->validate([
            'starVote' => 'required',
            'evaluation_id' => 'required',
            'comments' => 'required',

        ]);

        if (auth()->guard('member')->check()) {
            $request->merge(['textbook' => json_encode($request->input('textbook') ?? ['textbook'])]);
            $request->merge(['type' => json_encode($request->input('type') ?? ['multiple_choices'])]);

            $parents_id = $request->parents_id;

            //            if (isset($parents_id)) {
            //                $title = __('life.open_space.comment_on_comment');
            //            } else {
            //                $title = __('life.open_space.comment_on_post');
            //            }

            if (isset($parents_id)) {
                $title = __('life.open_space.comment_on_comment');
                $type_noti = "bulletin_comment_on_comment";
            } else {
                $title = __('life.open_space.comment_on_post');
                $type_noti = "bulletin_comment_on_post";
            }

            $comments = new CommentsEvaluation;
            $comments->evaluation_id = $request->input('evaluation_id');
            $comments->member_id = auth()->guard('member')->user()->id;
            $comments->comments = $request->input('comments');
            $comments->votes = $request->input('starVote');
            $comments->grade = $request->input('grade') ?? 'normal';
            $comments->assignment = $request->input('assignment') ?? 'a_lot';
            $comments->attendance = $request->input('attendance') ?? 'care_student';
            $comments->textbook = $request->input('textbook') ?? 'over_4_times';
            $comments->team_project = $request->input('team_project');
            $comments->number_times = $request->input('number_times');
            $comments->type = $request->input('type');
            $comments->save();

            addPointForMembers(1);

            // notify to owner
            $studyRoom = Evaluation::find($request->evaluation_id);

            $this->notify($title, $request->comments, [
                $studyRoom->member_id
            ], $type_noti);

            return redirect()->back()->with('success', __('controller.create_successful', ['module' => __('campus.evaluation.review')]));
        } else {
            // return to login
            return redirect()->back()->with('err', __('controller.login_again'));
        }
    }

    public function lastest($id = null)
    {
        $comments = CommentsEvaluation::orderBy('created_at', 'DESC');
        if ($id != null) {
            $comments->where('evaluation_id', $id);
        }

        $notices = NoticesCampus::where('code', 'EVALUATION_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $description = DescriptionCampus::where('code', 'EVALUATION_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();

        Theme::breadcrumb()->add(__('campus.evaluation'), route('campus.evaluation_comments_major'))->add(__('campus.evaluation.view_the_latest_course_reviews'), 'http:...');

        Theme::setTitle(__('campus') . ' | ' . __('campus.evaluation') . ' | ' . __('campus.evaluation.view_the_latest_course_reviews'));

        return Theme::scope('campus.evaluation.comments', ['comments' => $comments->paginate(10), 'notices' => $notices, 'description' => $description])->render();
    }

    public function major()
    {
        $evaluation = Evaluation::with(['major'])->withCount(['comments'])
            ->where('status', '!=', 'draft')
            ->ordered()
            ->paginate(10);
        $dataEvaluations = $evaluation->items();
        if ($evaluation->count() > 0) {
            collect($dataEvaluations)->map(function ($data) {
                $data->avg_comment = $data->getAvgVote();
            });
        }

        $notices = NoticesIntroduction::code(EVALUATION_MODULE_SCREEN_NAME)->where('status', 'publish')->orderBy('created_at', 'DESC')->limit(2)->get();
        $description = DescriptionCampus::where('code', 'EVALUATION_MODULE_SCREEN_NAME')->where('status', 'publish')->orderBy('created_at', 'DESC')->first();
        $categories = Major::where('status', 'publish')->where('parents_id', 0)->get();

        Theme::breadcrumb()->add(__('campus.evaluation'), route('campus.evaluation_comments_major'))->add(__('campus.evaluation.view_lecture_evaluations_by_subject'), 'http:...');

        Theme::setTitle(__('campus') . ' | ' . __('campus.evaluation') . ' | ' . __('campus.evaluation.view_lecture_evaluations_by_subject'));

        return Theme::scope('campus.evaluation.major', compact('evaluation', 'dataEvaluations', 'notices', 'description', 'categories'))->render();
    }
}
