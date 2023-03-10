<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Garden\Models\CommentsGarden;
use Botble\Garden\Models\SympathyGarden;
use Botble\Introduction\Models\CategoriesIntroduction;
use Botble\Introduction\Models\Introduction;
use Botble\Introduction\Models\Notices\CommentsNoticeIntroduction;
use Botble\Introduction\Models\Notices\NoticesIntroduction;
use Botble\Introduction\Models\Notices\SympathyNotice;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Services\UploadsManager;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Theme;

class NoticesIntroController extends Controller
{
    /**
     * @return Response
     */
    public static function index()
    {
        $intro = Introduction::where('status', 'publish')->orderby('created_at', 'DESC')->take(4)->get();
        $notices = NoticesIntroduction::where('status', 'publish')->orderby('created_at', 'DESC')->paginate(10);

        $categories = CategoriesIntroduction::where('status', 'publish')->orderby('created_at', 'DESC')->get();

        Theme::setTitle(__('eh-introduction').' | '.__('eh-introduction.notices'));
        return Theme::scope('intro.notices.index', ['intro' => $intro, 'notices' => $notices,'categories'=>$categories])->render();

    }

    public static function detail($id)
    {
        $categories = CategoriesIntroduction::where('status', 'publish')->orderby('created_at', 'DESC')->get();
        $notices = NoticesIntroduction::where('id', $id)->where('status', 'publish')->first();
        $notices->lookup = $notices->lookup + 1;
        $notices->save();

        $intro = Introduction::where('status', 'publish')->orderby('created_at', 'DESC')->take(4)->get();

        Theme::setTitle(__('eh-introduction').' | '.__('eh-introduction.notices').' | ' . $notices->name);
        Theme::breadcrumb()->add(__('eh-introduction'), route('eh_introduction.notices.list'))->add(__('eh-introduction.notices'));

        $subNotices = NoticesIntroduction::where('status', 'publish')->orderby('created_at', 'DESC')->paginate(10);

        return Theme::scope('intro.notices.details', [
            'intro' => $intro,
            'notices' => $notices,
            'categories'=>$categories,
            'subList' => [
                'intro' => $intro,
                'notices' => $subNotices,
            ]
        ])->render();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function createComment(Request $request) {
        if (auth()->guard('member')->check()) {
            $comment = CommentsNoticeIntroduction::create([
               'notice_introduction_id' => $request->notice_id,
               'anonymous' => $request->is_secret_comments ?? 0,
               'member_id' => auth()->guard('member')->user()->id,
               'content' => request('content'),
               'parents_id' =>  $request->parents_id ?? null,
               'ip_address' => $request->ip(),
            ]);

            $parent = MediaFolder::where('slug', 'comment-fe')->first();
            $folder = MediaFolder::create([
                'name' => $comment->id,
                'slug' => "comment-garden-".$comment->id,
                'user_id' => '0',
                'parent_id' => $parent->id ?? 0,
            ]);
            //file
            if ($request->hasFile('commentFile')) {
                $listFile = $request->file('commentFile');
                $listFileURL = [];
                foreach ($listFile as $file){
                    $file_link = \RvMedia::handleUpload($file, $folder->id ?? 0);
                    if ($file_link['error'] == false) {
                        $listFileURL[] = $file_link['data']->url;
                    }
                }
                $comment->file_upload = implode(',', $listFileURL);
            }
            $comment->save();
            addPointForMembers(1);
        }
        return redirect()->back();
    }

    public function deleteComment($id) {
        if (hasPermission('memberFE.isAdmin')) {
            $comments = CommentsNoticeIntroduction::findOrFail($id);
        } else {
            $comments = CommentsNoticeIntroduction::where('member_id', auth()->guard('member')->user()->id)->findOrFail($id);
        }

        if($comments->parents_id > 0){
            $parentComment = CommentsNoticeIntroduction::findOrFail($comments->parents_id);
            $sameChildComment = $parentComment->getAllCommentByParentsID($comments->parents_id);

            if($sameChildComment->count() > 1){
                $countDelete = 0;
                foreach($sameChildComment as $key=>$item){
                    if($item->id == $id){
                        $item->is_deleted = 1;
                        $item->save();
                        $countDelete++;
                    }else{
                        if($item->is_deleted == 1){
                            $countDelete++;
                        }
                    }
                }

                $flagDeleteParent = false;
                if($countDelete==$sameChildComment->count()){
                    $flagDeleteParent = true;
                    foreach($sameChildComment as $key=>$item){
                        $item->delete();
                    }

                }

                if($parentComment->is_deleted == 1){
                    if($flagDeleteParent){
                        $parentComment->delete();
                    }
                }
            } else {
                $comments->delete();
                if($parentComment->is_deleted == 1){
                    $parentComment->delete();
                }
            }

        }
        else{
            $allChildComment = $comments->getAllCommentByParentsID($id);

            if ($comments->anonymous == 0){
                $checkSympathy = SympathyNotice::where(['member_id' => auth()->guard('member')->user()->id, 'notice_introduction_id' => $comments->notice_introduction_id])->first();
                if (!empty($checkSympathy)){
                    $checkSympathy->delete();
                }
            }

            if($allChildComment->count() > 0){
                $comments->is_deleted = 1;
                $comments->save();
            }else{
                $comments->delete();
            }
        }

        $file_delete = $comments->file_upload;
        if ($file_delete) {
            # code...
            $file = MediaFile::where('url', $file_delete)->first();
            if ($file) {
                $file->forceDelete();
            }
            $uploadManager = new UploadsManager;
            $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $file_delete);
            $uploadManager->deleteFile($path , 1);

            $folder = MediaFolder::where('slug', "comment-garden-".$id)->first();
            if ($folder) {
                $folder->forceDelete();
            }
        }

        return redirect()->back()->with('success', __('controller.deletecomment'));
    }

    public static function dislikeComments(Request $request) {
        $post_id = $request->post_id;
        $reason = $request->reason;
        $comment_id = $request->comment_id;
        $sympathy = CommentsNoticeIntroduction::findOrFail($comment_id);

        $user = auth()->guard('member')->user();
        $dislike = 0;

        $check = $sympathy->check_sympathy()->where('sympathy_notice_introductions_comments.member_id', $user->id);

        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 0);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                   'is_dislike' => 1,
                   'notice_introduction_id' => $post_id,
                   'reason' => $reason,
                   'updated_at' => Carbon::now()
                ]);
                $dislike = 2;
                if($reason!=""){
                    sympathyCommentDetail("garden", $post_id,$reason,"dislike",$comment_id);
                }
            } else {
                $check->detach($user->id);
                cancelSympathyCommentOnComment("garden",$comment_id,"dislike");
            }
        } else {
            $check->attach([
               $user->id => [
                   'is_dislike' => 1,
                   'notice_introduction_id' => $post_id,
                   'reason' => $reason,
                   'created_at' => Carbon::now(),
                   'updated_at' => Carbon::now()
               ],
           ]);
            $dislike = 2;
            if($reason!=""){
                sympathyCommentDetail("garden", $post_id,$reason,"dislike",$comment_id);
            }
        }

        $sympathy = CommentsNoticeIntroduction::withCount(['dislikes'])->withCount(['likes'])->findOrFail($comment_id);

        return response()->json(
            [
                'dislikes_count' => $sympathy->dislikes_count ?? 0,
                'likes_count' => $sympathy->likes_count ?? 0,
                'disliked' => $dislike,
            ]
        );
    }

    public static function likeComments(Request $request) {
        $reason = $request->reason;
        $post_id = $request->post_id;
        $comment_id = $request->comment_id;
        $sympathy = CommentsNoticeIntroduction::findOrFail($comment_id);

        $user = auth()->guard('member')->user();

        $check = $sympathy->check_sympathy()->where('sympathy_notice_introductions_comments.member_id', $user->id);
        $liked = 0;
        if ($check->count() > 0) {
            $clone = (clone $check)->where('is_dislike', 1);
            if ($clone->count() > 0) {
                $check->first()->pivot->update([
                   'is_dislike' => 0,
                   'notice_introduction_id' => $post_id,
                   'reason' => $reason,
                   'updated_at' => Carbon::now()
                ]);
                $liked = 2;
                if($reason!=""){
                    sympathyCommentDetail("garden", $post_id,$reason,"like",$comment_id);
                }
            } else {

                $check->detach($user->id);
                cancelSympathyCommentOnComment("garden",$comment_id,"like");
            }
        } else {
            $check->attach([
                $user->id => [
                    'is_dislike' => 0,
                    'notice_introduction_id' => $post_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]);
            $liked = 1;
            if($reason!=""){
                sympathyCommentDetail("garden", $post_id,$reason,"like",$comment_id);
            }
        }

        $sympathy = CommentsNoticeIntroduction::withCount(['dislikes',])->withCount(['likes'])->findOrFail($comment_id);
        return response()->json(
            [
                'dislikes_count' => $sympathy->dislikes_count ?? 0,
                'likes_count' => $sympathy->likes_count ?? 0,
                'liked' => $liked,
            ]
        );
    }

    public static function checkSympathyPermissionOnComment(Request $request) {
        $commentId = $request->comment_id;
        $commentOwnerID = CommentsNoticeIntroduction::find($commentId)->member_id;
        $currentUserID = auth()->guard('member')->user()->id;
        $allow = 1;

        if($commentOwnerID == $currentUserID){
            $allow = 0;
        }

        return response()->json(
            [
                'valid' =>$allow,
            ]
        );
    }
}
