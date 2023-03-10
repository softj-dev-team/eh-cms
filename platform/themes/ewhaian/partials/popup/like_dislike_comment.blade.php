@if( auth()->guard('member')->check() )
<style>
  .modal-content {
    border-radius: 5px;
  }
  .modal--confirm .modal-content.pr-0{
    padding-right:0 !important;
  }
  .modal--confirm .modal-body {
    padding-bottom: 10px;
  }
  .modal--confirm .btn{
    height: 40px;
  }

  .btn-cancel {
    color: #ffffff;
    background-color: #444444;
  }

  .btn-cancel:hover {
    color: #ffffff !important;
    background-color: #444444 !important;
    text-decoration: none;
  }

  .btn-ok {
    border: 1px solid transparent;
    background-color: #dddddd;
    color: #444444;
  }

  .btn-ok:hover {
    background-color: #dddddd !important;
    color: #444444 !important;
    text-decoration: none;
  }
</style>
<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmPopupComment" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
    <div class="modal-content pr-0">
      <div class="modal-body">

        <div class="align-items-center mx-lg-2">
          <div class="d-lg-flex align-items-start flex-grow-1">

            <div class="form-group form-group--1 flex-grow-1 mr-lg-20 m-3">
                <div class="md-form">
                    <textarea id="comment-reason" class="md-textarea form-control" style="height: 1.9em!important;" rows="3" name="reason"></textarea>
                    <label for="comment-reason" style="color: black">비공감하시는 이유를 입력해 주세요.</label>
                  </div>
            </div>
          </div>
          <div class="button-group mb-2" style="display: flex; justify-content: flex-end">
            <button type="button" class="btn btn-cancel mr-lg-10" data-dismiss="modal">취소</button>
            <button type="button" class="btn btn-ok" id="comment-dislike" >확인</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmPopupComment3" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
    <div class="modal-content pr-0">
      <div class="modal-body">

        <div class="align-items-center mx-lg-2">
          <div class="d-lg-flex align-items-start flex-grow-1">

            <div class="form-group form-group--1 flex-grow-1 mr-lg-20 m-3">
                <div class="md-form">
                    <textarea id="reason_comment_like" class="md-textarea form-control" style="height: 1.9em!important;" rows="3" name="reason"></textarea>
                    <label for="reason_comment_like ">공감취소하시는 이유를 알려주세요.</label>
                  </div>
            </div>
          </div>
          <div class="button-group mb-2" style="display: flex; justify-content: flex-end">
            <button type="button" class="btn btn-cancel mr-lg-10" data-dismiss="modal">취소</button>
            <button type="button" class="btn btn-ok" id="comment-like" >확인</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmPopupComment2" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
    <div class="modal-content" style="padding:0">

      <div class="align-items-center mx-lg-2">
        <div class="d-lg-flex align-items-start flex-grow-1">

          <div class="form-group form-group--1 flex-grow-1 mr-lg-20 m-3">
            <div class="md-form" id="message_comment_dislike">
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmPopupComment4" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
    <div class="modal-content pr-0">
      <div class="modal-body">

        <div class="align-items-center mx-lg-2">
          <div class="d-lg-flex align-items-start flex-grow-1">

            <div class="form-group form-group--1 flex-grow-1 mr-lg-20 m-3">
                <div class="md-form change-text-like">
                    {{-- @if(is_null($item->likes->find(auth()->guard('member')->user()->id)))
                        이 댓글을 공감하시겠습니까?
                    @else
                        이 댓글을 공감 / 취소하시겠습니까?
                    @endif --}}
                    이 댓글을 공감 / 취소하시겠습니까?
                  </div>
            </div>
          </div>
          <div class="button-group mb-2" style="display: flex; justify-content: flex-end">
            <button type="button" class="btn btn-cancel mr-lg-10" data-dismiss="modal">취소</button>
            <button type="button" class="btn btn-ok" id="confirm_comment_like" >확인</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmPopupDislike" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
    <div class="modal-content pr-0">
      <div class="modal-body">

        <div class="align-items-center mx-lg-2">
          <div class="d-lg-flex align-items-start flex-grow-1">

            <div class="form-group form-group--1 flex-grow-1 mr-lg-20 m-3">
                <div class="md-form">
                  {{ __('comments.confirm_disklike') }}
                  </div>
            </div>
          </div>
          <div class="button-group mb-2" style="display: flex; justify-content: flex-end">
            <button type="button" class="btn btn-cancel mr-lg-10" data-dismiss="modal">취소</button>
            <button type="button" class="btn btn-ok" id="confirm_comment_unlike" >확인</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade modal--confirm" id="confirmPopupCommentReject" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
    <div class="modal-content" style="padding:0">
        <div class="align-items-center mx-lg-2">
          <div class="d-lg-flex align-items-start flex-grow-1">

            <div class="form-group form-group--1 flex-grow-1 mr-lg-20 m-3">
              <div class="md-form" id="message_comment_reject">
              </div>
            </div>
          </div>
          <div class="button-group mb-2" style="display: flex; justify-content: flex-end">
            <button type="button" class="btn btn-ok" id="confirmPopupCommentRejectClose" >확인</button>
          </div>
        </div>

    </div>
  </div>
</div>

@endif


<script>
    $(function(){
        let comment_id = 0;


        let funcCheckPermissionOnComment = function(typeSym,dataCommentLike,commentID){
            let $this = $(this);
            comment_id = commentID;
            //console.log(commentID);return;
            $.ajax({
                type:'POST',
                url: '{{ $route_sympathy_permission_on_comment }}',
                data:{
                        _token: "{{ csrf_token() }}",
                        'post_id' : '{{$idDetail}}',
                        'comment_id' : commentID,
                },
                async:false,
                success: function( data ) {
                    if(data.valid == 0){
                      $('#confirmPopupCommentReject').modal('show');
                      return false;
                    }
                    else{
                      if(typeSym == "dislike"){
                        $('#confirm_comment_unlike').attr('data-dislike',  dataCommentLike);
                        $(".warningLimitComment").hide();

                        var unlikeCommentQuestion = "{{ __('comments.confirm_disklike') }}";
                        if(dataCommentLike == "true"){
                          unlikeCommentQuestion = "비공감 을 취소하시겠습니까?";
                        }
                        if(dataCommentLike == "true-sub"){
                          unlikeCommentQuestion = "비공감 을 취소하시겠습니까?";
                        }
                        //console.log(unlikeCommentQuestion);
                        $("#confirmPopupDislike .form-group--1 .md-form").html(unlikeCommentQuestion);


                        $('#confirmPopupDislike').modal('show');
                      }
                      if(typeSym == "like"){
                        $('#confirm_comment_like').attr('data-like',  dataCommentLike)
                        $('#confirm_comment_like').attr('data-comment-id',  comment_id)
                        $(".warningLimitComment").hide();

                        var likeCommentQuestion = "{{ __('comments.confirm_like') }}";
                        if(dataCommentLike == "true"){
                          likeCommentQuestion = "{{ __('comments.confirm_disklike') }}";
                        }
                        if(dataCommentLike == "true-sub"){
                          likeCommentQuestion = "{{ __('comments.confirm_disklike') }}";
                        }
                        $("#confirmPopupComment4 .form-group--1 .md-form").html(likeCommentQuestion);
                        $('#confirmPopupComment4').modal('show');
                      }
                    }

                }
            }).always(function(){
                $this.css('pointer-events', 'auto').data('isRunAjax', false);
            });

        };

        $("#confirmPopupComment").on("hidden.bs.modal", function () {
            $("#comment-reason").val("");
        });

        $("#confirmPopupComment3").on("hidden.bs.modal", function () {
            $("#reason_comment_like").val("");
        });


        $(".modal-comment-dislike").on("click", function(e){
            $("#message_comment_reject").text('자신의 댓글은 비공감할 수 없습니다');
            var commmentID = $(this).attr("data-comment_id");
            var dataCommentDislike = $(this).attr('data-dislike');

            funcCheckPermissionOnComment("dislike",dataCommentDislike,commmentID);

            //$('#confirm_comment_unlike').attr('data-dislike',  $(this).attr('data-dislike'));
            //$('#confirmPopupDislike').modal('show');

        });

        $('#confirm_comment_unlike').on('click',function () {
            $('#confirmPopupDislike').modal('hide');
            // if(  $(this).attr('data-dislike') == 'false') {
            //     $('#confirmPopupComment').modal('show');
            //     return;
            // }
            funcDislike();
        })

        $("#comment-dislike").on("click", function(e){
            var $this = $(this);
            var $reason = $('#comment-reason');

            //if($reason.val() == '') {alert('이유를 입력해주세요.');$reason.focus(); return;}


            if($reason.val().length < 10) {
              //alert('Please input 10 characters at least.');
              $reason.focus();
              $(".warningLimitComment").show();

              return;
            }
            funcDislike();

        });

        $("#confirmPopupCommentRejectClose").on("click", function(e){
          $('#confirmPopupCommentReject').modal('hide');
        });

        let funcDislike = function(){
            let $this = $(this);
            let $reason = $('#comment-reason');
            if($this.data('isRunAjax')==true){return;}
            $this.css('pointer-events', 'none').data('isRunAjax', true);
            $.ajax({
                type:'POST',
                url: '{{ $route_dislike }}',
                async:false,
                data:{
                        _token: "{{ csrf_token() }}",
                        'post_id' : '{{$idDetail}}',
                        'comment_id' : comment_id,
                        'reason' : $reason.val(),
                },
                success: function( data ) {
                    //return false;
                    location.reload();
                    $('.item__action .single__date .comment-'+comment_id+'-dislike .mr-10').css('color', data.disliked != 0 ? '#EC1469':'black');
                    $('.item__action .single__date .comment-'+comment_id+'-like .like').css('color','black');
                    $('#dislike_count'+comment_id).html(data.dislikes_count);
                    $('#like_count'+comment_id).html(data.likes_count);
                    $reason.val('');
                    if(data.disliked != 0 ) {
                        $('#message_comment_dislike').html('비공감 하셨습니다.');
                        $('.row .single__info .single__date .dislike').attr('data-dislike', false);
                        if(data.dislikes_count > 0) {
                            $('.row .single__info .single__date .modal_like').attr('data-like', true);
                        }
                    } else {
                        $('#message_comment_dislike').html('비공감을 취소하셨습니다.');
                        $('.row .single__info .single__date .dislike').attr('data-dislike', true);
                        if(data.dislikes_count == 0) {
                            $('.row .single__info .single__date .modal_like').attr('data-like', false);
                        }
                    }

                    $('#confirmPopupComment').modal('hide');
                    $('#confirmPopupComment2').modal('show');

                }
            }).always(function(){
                $this.css('pointer-events', 'auto').data('isRunAjax', false);
            });
        };

        $('#detail').on('contextmenu',function(e){
            return false;
        })

        $(".modal-comment-like").on("click", function(e){
            $("#message_comment_reject").text('자신의 댓글은 공감할 수 없습니다.');
            var commmentID = $(this).attr("data-comment_id");
            var dataCommentLike = $(this).attr('data-like');
            funcCheckPermissionOnComment("like",dataCommentLike,commmentID);

            // $('#confirm_comment_like').attr('data-like',  $(this).attr('data-like'))
            // $('#confirmPopupComment4').modal('show');
        });

        $('#confirm_comment_like').on('click',function () {
            $('#confirmPopupComment4').modal('hide');
            var commentID = $(this).attr('data-comment_id');

            // if(  $(this).attr('data-like') == 'false' && $("#dislike_count"+commentID).html()!=0 ) {
            //     $('#confirmPopupComment3').modal('show');
            //     return;
            // }
            funcLike();
        })
        $("#comment-like").on("click", function(e){
            var $this = $(this);
            var reason = $('#reason_comment_like');

            // if($reason.val() == '') {alert('이유를 입력해주세요.');$reason.focus(); return;}


            if(reason.val().length < 10) {
              //alert('Please input 10 characters at least.');
              reason.focus();
              $(".warningLimitComment").show();

              return;
            }


            funcLike();
        });
        let funcLike = function() {

            var $this = $(this);
            var reason = $('#reason_comment_like');
            if($this.data('isRunAjax')==true){return;}
            $this.css('pointer-events', 'none').data('isRunAjax', true);
            $.ajax({
                type:'POST',
                url: '{{ $route_like }}',
                async:false,
                data:{
                        _token: "{{ csrf_token() }}",
                        'post_id' : '{{$idDetail}}',
                        'comment_id' : comment_id,
                        'reason' : reason.val()
                },
                success: function( data ) {
                    location.reload();
                    $('.item__action .single__date .comment-'+comment_id+'-dislike .mr-10').css('color', 'black');
                    $('.item__action .single__date .comment-'+comment_id+'-like .like').css('color', data.liked != 0 ? '#EC1469':'black');

                    $('#like_count'+comment_id).html(data.likes_count);
                    $('#dislike_count'+comment_id).html(data.dislikes_count);
                    reason.val('');
                    if(data.liked != 0 ) {
                        $('#message_comment_dislike').html('공감하셨습니다.');
                        $('.row .single__info .single__date .modal_like').attr('data-like', false);
                        $('.change-text-like').text('이 댓글을 공감 / 취소하시겠습니까?');
                    } else {
                        $('.change-text-like').text('이 댓글을 공감하시겠습니까?');
                        $('#message_comment_dislike').html('공감을 취소하셨습니다.');

                        if(data.dislikes_count > 0) {
                            $('.row .single__info .single__date .modal_like').attr('data-like', true);
                        }
                    }

                    $('#confirmPopupComment3').modal('hide');
                    $('#confirmPopupComment2').modal('show');

                }
            }).always(function(){
                $this.css('pointer-events', 'auto').data('isRunAjax', false);
            });
            };

    });
</script>
