<!-- Modal -->
<div class="modal fade modal--confirm" id="reportPost{{$type_report}}{{$id_post}}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="z-index: 2000">
    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
        <div class="modal-content" style="padding-right: 0px">
            <div class="modal-body">
                <form action="{{route('ewhaian.report')}}" method="POST" id="report_form">
                    @csrf
                    <div style="text-align: left !important;">
                        <input type="hidden" name="type_report" value="{{$type_report}}">  <!-- report post -->
                        <input type="hidden" name="type_post" value="{{$type_post}}"> <!-- type post -->
                        <input type="hidden" name="link" value="{{Request::path()}}"> <!-- type post -->
                        <input type="hidden" name="id_post" value="{{$id_post}}"> <!-- type post -->
                        <input type="hidden" name="reported_id" value="{!! $object->member_id ?? null !!}"> <!-- reported_id -->
                        <input type="hidden" name="is_garden" value="{{ Request::is('garden/*') ? true : null }}"> <!-- reported_id -->
                       {{-- <input type="hidden" name="categories_gardens_id" value="{!! $object->categories_gardens_id ?? null !!}"> <!-- categories_gardens_id --> --}}
                        <div class="d-lg-flex align-items-center mx-3">
                            <div class="d-lg-flex align-items-start flex-grow-1">
                                    <div class="form-group form-group--1 flex-grow-1 mb-3">
                                        <div class="md-form">
                                            <div class="custom-control custom-checkbox" style="margin: 10px 0;background-color: #dddddd;padding: 20px;">
                                            <div class="label">
                                                 <!-- [훌리건 신고] -->
                                                 {{__('comments.report_hooligan')}}
                                                </div>
                                            @if(auth()->guard('member')->check())
                                                <div style="margin: 10px 0">
                                                    신고자 :  {{auth()->guard('member')->user()->id_login ?? ''}}
                                                    |  신고글번호  :  {{$id_post}}
                                                </div>
                                            @endif
                                            <!-- <div>신고에 신중해 주시길 바랍니다.
                                                <br>적절하지 않은 신고는 반영되지 않으며
                                                <br>불이익이 있을 수 있습니다. -->
                                            <div>
                                              {!! html_entity_decode(__('comments.report_info')) !!}
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="flex-line" style="display: flex">
                            <div class="label">
                            </div>
                            <ul class="listColor">
                                <li style="padding-bottom: 10px;" >
                                    <div class="custom-control custom-checkbox mx-3">
                                    <input type="radio" class="custom-control-input checkbox_report_post" id="reason_option1{{$type_report}}{{$id_post}}" name="reason_option" value="1" checked required>
                                    <label class="custom-control-label" for="reason_option1{{$type_report}}{{$id_post}}">
                                    <!-- 훌리건 의심 -->
                                    <!-- {{!!__('comments.report_hooligan.suspect_hooligan') !!}} -->
                                    훌리건 의심
                                    </label>
                                    </div>
                                </li>
                                <li style="padding-bottom: 10px;">
                                    <div class="custom-control custom-checkbox mx-3">
                                    <input type="radio" class="custom-control-input checkbox_report_post" id="reason_option2{{$type_report}}{{$id_post}}"  name="reason_option" value="2">
                                    <label class="custom-control-label" for="reason_option2{{$type_report}}{{$id_post}}">
                                    <!-- 회원에 대한 욕설 혹은 저격 -->
                                    {{__('comments.report_hooligan.insult_user')}}
                                    </label>
                                    </div>
                                </li>
                                <li style="padding-bottom: 10px;">
                                    <div class="custom-control custom-checkbox mx-3">
                                    <input type="radio" class="custom-control-input checkbox_report_post" id="reason_option3{{$type_report}}{{$id_post}}"  name="reason_option" value="3">
                                    <label class="custom-control-label" for="reason_option3{{$type_report}}{{$id_post}}">
                                    <!-- 허위사실 유포 -->
                                    {{__('comments.report_hooligan.fake_info')}}
                                    </label>
                                    </div>
                                </li>
                                <li style="padding-bottom: 10px;">
                                    <div class="custom-control custom-checkbox mx-3">
                                    <input type="radio" class="custom-control-input checkbox_report_post" id="reason_option4{{$type_report}}{{$id_post}}" name="reason_option" value="4">
                                    <label class="custom-control-label" for="reason_option4{{$type_report}}{{$id_post}}">
                                    <!-- 게시 자료의 저작권 위반 -->
                                    {{__('comments.report_hooligan.copyright')}}
                                    </label>
                                    </div>
                                </li>
                                <li style="padding-bottom: 10px;">
                                    <div class="custom-control custom-checkbox mx-3">
                                    <input type="radio" class="custom-control-input checkbox_report_post" id="reason_option5{{$type_report}}{{$id_post}}"  name="reason_option" value="5">
                                    <label class="custom-control-label" for="reason_option5{{$type_report}}{{$id_post}}">
                                    <!-- 일반인 신상정보 게시 -->
                                    {{__('comments.report_hooligan.post_personal_info')}}
                                    </label>
                                    </div>
                                </li>
                                <li style="padding-bottom: 10px;">
                                    <div class="custom-control custom-checkbox mx-3">
                                    <input type="radio" class="custom-control-input checkbox_report_post" id="reason_option6{{$type_report}}{{$id_post}}"  name="reason_option" value="6">
                                    <label class="custom-control-label" for="reason_option6{{$type_report}}{{$id_post}}">
                                    <!-- 지나친 홍보 또는 상거래 유도 -->
                                    {{__('comments.report_hooligan.commerce')}}
                                    </label>
                                    </div>
                                </li>
                                <li style="padding-bottom: 10px;">
                                    <div class="custom-control custom-checkbox mx-3">
                                    <input type="radio" class="custom-control-input checkbox_report_post" id="reason_option7{{$type_report}}{{$id_post}}"  name="reason_option" value="7">
                                    <label class="custom-control-label" for="reason_option7{{$type_report}}{{$id_post}}">
                                    <!-- 다른 게시판에 적절한 게시글 -->
                                    {{__('comments.report_hooligan.not_board')}}
                                    </label>
                                    </div>
                                </li>
                                <li style="padding-bottom: 10px;">
                                    <div class="custom-control custom-checkbox mx-3">
                                    <input type="radio" class="custom-control-input checkbox_report_post orther_report" id="reason_option8{{$type_report}}{{$id_post}}"  name="reason_option" value="8">
                                    <label class="custom-control-label" for="reason_option8{{$type_report}}{{$id_post}}">
                                    <!-- 기타 -->
                                    {{__('comments.report_hooligan.extra')}}
                                    </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-lg-flex align-items-center mx-3">
                        <div class="d-lg-flex align-items-start flex-grow-1">

                                <div class="form-group form-group--1 flex-grow-1 mb-3">
                                    <div class="md-form hidden_post_report"   style="display: none">
                                        <textarea id="reason{{$type_report}}{{$id_post}}" class="md-textarea form-control" rows="5"
                                            name="reason">N/A</textarea>
                                        <label for="reason ">신고 사유를 입력해주세요</label>
                                    </div>
                                </div>
                        </div>
                        <div class="button-group mb-2">
                            <!-- <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">cancel</button> -->
                            <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">{{__('comments.report_hooligan.cancel')}}</button>
                            <!-- <button type="submit" class="btn btn-primary">Send</button> -->
                            <button type="submit" class="btn btn-primary">{{__('comments.report_hooligan.send')}}</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
  .like_group .report_post{
    position: absolute;
    right: 0px;
    padding-top: 6px;
  }
  .custom-checkbox .custom-control-input:indeterminate~.custom-control-label::before {
          background-color: #fff;
      border: #adb5bd solid 1px;
  }
</style>

<script>
    $(document.body).on('click','.checkbox_report_post' , function (e) {
        if ( $('.orther_report').is(':checked')) {
            $('#reason{{$type_report}}{{$id_post}}').val('');
            $('.hidden_post_report').show();
        } else {
            $('.hidden_post_report').hide();
            $('#reason{{$type_report}}{{$id_post}}').val('N/A');
        }
    })
</script>
