<?php $minText = "Please input 10 characters at least."?>

<style>
  .like__area {
    position: absolute;
    right: 0px;
    padding-top: 6px;
  }
</style>


@if( auth()->guard('member')->check()  && isset($item)  )
<div class="row">
    {{-- <div class="single__info" style="display: flex;justify-content: flex-end;width: 100%; "> --}}
    <div class="single__info like__area">
        <div class="single__date mr-10" >
            <a href="javascript:void(0)" class="modal_like" @if( getLikeToMember($item->id,auth()->guard('member')->user()->id)) data-like="true" @else data-like="false" @endif>
                <svg
                class="icon like" width="20" height="16.56" aria-hidden="true"
                style="@if(getDiskLikeToMember($item->id,auth()->guard('member')->user()->id))  color:black  @else color:#EC1469  @endif ; "
            >
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_like"></use>
                </svg>
                <span id="like_count">{{ $item->likes_count ?? 0 }}</span>
            </a>

        </div>
        <div class="single__date mr-10">
            <a href="javascript:void(0)" class="modal-dislike" @if(getDiskLikeToMember($item->id,auth()->guard('member')->user()->id)) data-dislike="true" @else data-dislike="false" @endif>
                <svg
                class="icon" width="20" height="16.56" aria-hidden="true"
                style="  @if(getDiskLikeToMember($item->id,auth()->guard('member')->user()->id))  color:black  @else color:#EC1469  @endif ; transform: rotate(180deg); "
            >
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_like"></use>
                </svg>
                <span id="dislike_count">{{ $item->dislikes_count ?? 0 }}</span>
            </a>

        </div>
    </div>
</div>
@endif

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-maxlength/1.10.0/bootstrap-maxlength.min.js"></script> --}}
<style>
  .reasonCountText{
    font-size: 10px;
  }
</style>

@php
    $currentRoute = Route::currentRouteName();
    $lifeLikeDislikeMessage = false;
    if ($currentRoute == 'life.open_space_details') {
        $lifeLikeDislikeMessage = true;
    }
@endphp

<script>
    $(function(){

      // $('textarea#reason').maxlength({
      //   alwaysShow: true,
      //   //warningClass: "form-text text-muted mt-1",
      //   //limitReachedClass: "form-text text-muted mt-1",
      //   warningClass: "label-success label label-rounded label-inline reasonCountText",
      //   limitReachedClass: "label-success label label-rounded label-inline reasonCountText",
      //   placement: 'bottom-right-inside',
      // });


      let funcCheckPermissionOnPost = function(typeSym,dataLike){
            let $this = $(this);

            if($this.data('isRunAjax')==true){return;}
            $this.css('pointer-events', 'none').data('isRunAjax', true);
            $.ajax({
                type:'POST',
                url: '{{ $route_sympathy_permission_on_post }}',
                async:false,
                success: function( data ) {
                    if(data.valid == 0){
                      $('#confirmPopupReject').modal('show');
                      return false;
                    }
                    else{
                      if(typeSym == "dislike"){
                        $('#confirm_unlike').attr('data-dislike',  dataLike);
                        $('.warningLimit').hide();
                        var unlikeQuestion = "{{ __('comments.confirm_disklike') }}";
                        if ("{{$lifeLikeDislikeMessage}}" === true){
                            unlikeQuestion = "{{ __('life.like.dislike') }}";
                        }

                        if(dataLike == "true"){
                            unlikeQuestion = "비공감 을 취소하시겠습니까?";
                        }
                        // if(dataLike){
                        //   unlikeQuestion = "비공감 을 취소하시겠습니까?";
                        // }
                        $("#confirmPopup5 .form-group--1 .md-form").html(unlikeQuestion);
                        $('#confirmPopup5').modal('show');
                      }
                      if(typeSym == "like"){
                        $('#confirm_like').attr('data-like',  dataLike);
                        $('.warningLimit').hide();

                        var likeQuestion = "이 글을 공감취소하시겠습니까?";
                        if(dataLike){
                          likeQuestion = "{{ __('comments.confirm_like') }}";
                          if ("{{$lifeLikeDislikeMessage}}" === true){
                              likeQuestion = "{{ __('life.like.dislike') }}";
                          }
                        }

                        $("#confirmPopup4 .form-group--1 .md-form").html(likeQuestion);
                        $('#confirmPopup4').modal('show');
                      }
                    }

                }
            }).always(function(){
                $this.css('pointer-events', 'auto').data('isRunAjax', false);
            });

        };

        $(".modal-dislike").on("click", function(e){
            var dataDislike = $(this).attr('data-dislike');
            $("#message_reject").text('자신이 쓴 글에 비공감 할 수 없습니다');
            funcCheckPermissionOnPost("dislike",dataDislike);
            // $('#confirm_unlike').attr('data-dislike',  $(this).attr('data-dislike'))
            // $('#confirmPopup5').modal('show');

        });

        $('#confirm_unlike').on('click',function () {
            $('#confirmPopup5').modal('hide');
            // if(  $(this).attr('data-dislike') == 'false') {
            //     $('#confirmPopup').modal('show');
            //      return;
            // }
            funcDislike();



        })

        $("#confirmPopup").on("hidden.bs.modal", function () {
            $("#reason").val("");
        });

        $("#dislike").on("click", function(e){

            var $this = $(this);
            var $reason = $('#reason');

            if($reason.val().length < 10) {
              //alert('Please input 10 characters at least.');
              $reason.focus();
              $(".warningLimit").show();

              return;
            }
            funcDislike();

        });

        $("#confirmPopoup2Close").on("click", function(e){
          $('#confirmPopup2').modal('hide');
        });

        let funcDislike = function(){
            let $this = $(this);
            let $reason = $('#reason');
            if($this.data('isRunAjax')==true){return;}
            $this.css('pointer-events', 'none').data('isRunAjax', true);
            $.ajax({
                type:'POST',
                url: '{{ $route }}',
                async:false,
                data:{
                        _token: "{{ csrf_token() }}",
                        'post_id' : '{{$item->id}}',
                        'reason' : $reason.val(),
                },
                success: function( data ) {
                    //return false;
                    location.reload();


                    $('.row .single__info .single__date .mr-10').css('color', data.disliked != 0 ? '#EC1469':'black');
                    $('.row .single__info .single__date .like').css('color','black');
                    $('#dislike_count').html(data.dislikes_count);
                    $('#like_count').html(data.likes_count);
                    $reason.val('');
                    if(data.disliked != 0 ) {
                        $('#message_dislike').html('비공감 하셨습니다.');
                        $('.row .single__info .single__date .dislike').attr('data-dislike', false);
                        if(data.dislikes_count > 0) {
                            $('.row .single__info .single__date .modal_like').attr('data-like', true);
                        }
                    } else {
                        $('#message_dislike').html('비공감을 취소하셨습니다.');
                        $('.row .single__info .single__date .dislike').attr('data-dislike', true);
                        if(data.dislikes_count == 0) {
                            $('.row .single__info .single__date .modal_like').attr('data-like', false);
                        }
                    }

                    $('#confirmPopup').modal('hide');
                    $('#confirmPopup2').modal('show');

                }
            }).always(function(){
                $this.css('pointer-events', 'auto').data('isRunAjax', false);
            });
        };

        $('#detail').on('contextmenu',function(e){
            return false;
        })


        $("#confirmPopupRejectClose").on("click", function(e){
          $('#confirmPopupReject').modal('hide');
        });

        $(".modal_like").on("click", function(e){
            var dataLike = $(this).attr('data-like');
            $("#message_reject").text('자신이 쓴 글에 공감 할 수 없습니다');
            funcCheckPermissionOnPost("like",dataLike);
            // $('#confirm_like').attr('data-like',  $(this).attr('data-like'))
            // $('#confirmPopup4').modal('show');
        });

        $('#confirm_like').on('click',function () {
            $('#confirmPopup4').modal('hide');
            if(  $(this).attr('data-like') == 'false' && $("#dislike_count").html() != 0  ) {
                return;
            }

            funcLike();
        })

        $("#like").on("click", function(e){
            var $this = $(this);
            var $reason = $('#reason_like');

            if($reason.val().length < 10) {
              //alert('이유를 입력해주세요.');
              $('.warningLimitLike').show();
              $reason.focus();
              return;
            }
            funcLike();
        });

        let funcLike = function() {

            var $this = $(this);
            let $reason = $('#reason_like');
            if($this.data('isRunAjax')==true){return;}
            $this.css('pointer-events', 'none').data('isRunAjax', true);
            $.ajax({
                type:'POST',
                url: '{{ $route_like }}',
                async:false,
                data:{
                        _token: "{{ csrf_token() }}",
                        'post_id' : '{{$item->id}}',
                        'reason' : $reason.val(),
                },
                success: function( data ) {
                    //return false;
                    location.reload();
                    $('.row .single__info .single__date .mr-10').css('color', 'black');
                    $('.row .single__info .single__date .like').css('color', data.liked != 0 ? '#EC1469':'black');

                    $('#like_count').html(data.likes_count);
                    $('#dislike_count').html(data.dislikes_count);
                    $reason.val('');
                    if(data.liked != 0 ) {
                        $('#message_dislike').html('{{ __("garden.like_success") }}');
                        $('.row .single__info .single__date .modal_like').attr('data-like', false);
                        $('.change-text-like').text('이 글을 공감취소하시겠습니까?');
                    } else {
                        $('.change-text-like').text('이 글을 공감하시겠습니까?');
                        $('#message_dislike').html('{{ __("garden.dislike_success") }}');

                        if(data.dislikes_count > 0) {
                            $('.row .single__info .single__date .modal_like').attr('data-like', true);
                        }
                    }

                    $('#confirmPopup2').modal('show');

                }
            }).always(function(){
                $this.css('pointer-events', 'auto').data('isRunAjax', false);
            });
            };
    });
</script>
