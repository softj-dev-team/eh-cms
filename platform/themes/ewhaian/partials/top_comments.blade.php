<style>
  .single .comments .item__time.flex-c {
      display: flex;
      align-items: center;
  }
  .mr-left-4 {
      margin-left: 4px;
  }
</style>
<input type="hidden" name="parents_id" value="{{$item->id}}">
<div class="item">
  <div class="item__head d-flex flex-wrap align-items-center justify-content-between">
    <div style="display: flex; align-items: center;">
      <div class="item__time flex-c">
        <div style="display: flex">
          @if( $item->member_id == null )
            {!! getStatusWriter('real_name_certification') !!}
          @else
            {!! getStatusWriter($item->members->certification ?? 'real_name_certification') !!}
          @endif
          {{getName($item->members->nickname ??  __('comments.admin'), $item->members->id_login ??  __('comments.admin'), $canViewCommenter ?? false  ) }}
          @if( $item->anonymous == 1 && !is_null($item->ip_address) )
              <a target="_parent" onclick="ipPopClick('{{preg_replace('/([0-9]+.[0-9]+.[0-9]+).[0-9]+/', '1.xxx', $item->ip_address)}}')" >
              @php
                $randomIp = mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255);
              @endphp

              <span class="icon-label span_hidden_id"
                    style="cursor: pointer;font-size: 10px;line-height: 2em;width: 100%;height: 2em;border-radius: 5px;display: none;background-color: #f4f4f4;border: 1px solid #f4f4f4;margin-left: 0.71429em;margin-right: 0px;">
                {{ '(' . $randomIp . ')' }}
              </span>

              <span class="icon-label span_show_id"
                    style="cursor: pointer;font-size: 10px;line-height: 2em;width: 5em;height: 2em;border-radius: 5px;background-color: #f4f4f4;border: 1px solid #f4f4f4;margin-left: 0.71429em;margin-right: 0px;">
                IP
              </span>
            </a>
          @endif
        </div>
        <p style="margin-left: 0.71429em;">{{$item->created_at}}</p>
      </div>
      @if( getStatusDateByDate($item->created_at) == "Today" ) <span class="icon-label"
                                                                     style="margin-left: 10px">N</span> @endif
      @if(auth()->guard('member')->check())
        @if( $canDeleteComment && ( hasPermission('memberFE.isAdmin') || $item->member_id == auth()->guard('member')->user()->id ) )
          <div class="item__time">
            <form action="{{route($routeDelete,['id'=>$item->id])}}" method="POST">
              @csrf
              <button class="form-submit delete" type="submit">{{__('comments.delete')}}</button>
            </form>
          </div>
        @endif
      @endif

    </div>
    @if(auth()->guard('member')->check())
      <div class="item__action">
        <div class="single__date">
          <a href="javascript:void(0)" class="modal-comment-like comment-{{$item->id}}-like mr-sm-1"
             @if( getLikeCommentToMember($item->id,auth()->guard('member')->user()->id)) data-like="true"
             @else data-like="false" @endif data-comment_id="{{$item->id}}">
            <svg
              class="icon like" width="20" height="16.56" aria-hidden="true"
              style="@if(is_null($item->likes->find(auth()->guard('member')->user()->id)))  color:black  @else color:#EC1469  @endif ; "
            >
              <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_like"></use>
            </svg>
            <span id="like_count{{$item->id}}">{{ $item->likes_count ?? 0 }}</span>
          </a>
        </div>
        <div class="single__date">
          <a href="javascript:void(0)" class="modal-comment-dislike comment-{{$item->id}}-dislike mr-sm-1"
             @if(getDislikeCommentToMember($item->id,auth()->guard('member')->user()->id)) data-dislike="true"
             @else data-dislike="false" @endif data-comment_id="{{$item->id}}">
            <svg
              class="icon" width="20" height="16.56" aria-hidden="true"
              style="  @if(is_null($item->dislikes->find(auth()->guard('member')->user()->id)))  color:black  @else color:#EC1469  @endif ; transform: rotate(180deg); "
            >
              <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_like"></use>
            </svg>
            <span id="dislike_count{{$item->id}}">{{ $item->dislikes_count ?? 0 }}</span>
          </a>

        </div>


        <button data-target="#reportPost2{{$item->id}}" data-toggle="modal" class="mr-left-4">
          <img src="{{Theme::asset()->url('img/dependence.svg')}}"
               alt="{{__('comments.declaration')}}" width="18.008" height="13.506">
          {{__('comments.declaration')}}
        </button>
        @if($canCreateComment ?? false)
          <button class="item__reply">
            <svg width="18.008" height="13.506" aria-hidden=" true" class="icon">
              <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_reply"></use>
            </svg>
            {!!__('comments.reply',['count'=>count($item->getAllCommentByParentsID($item->id))]) !!}
          </button>
        @endif
      </div>
    @endif
  </div>

  <div class="item__content">
    {{-- @if( $item->anonymous < 1 || Route::currentRouteName() === 'contents.details')
      {{$item->content}}
    @else
      <div>
        <div style="display: flex;color: #EC1469;">
          {{__('comments.this_is_a_secret_comment')}}
        </div>
        @if(auth()->guard('member')->check())
          @if($item->member_id == auth()->guard('member')->user()->id ||
              auth()->guard('member')->user()->id== $createBy ||
              auth()->guard('member')->user()->hasPermission('member.show') )
            {{$item->content}}
          @endif
        @endif
      </div>
    @endif --}}
    @if( $item->anonymous == 0 ||
                    $item->member_id == auth()->guard('member')->user()->id  ||
                    $createBy == auth()->guard('member')->user()->id )
      {!! $createBy == $item->member_id ? '* ' : null !!}
      {!! html_entity_decode($item->content) !!}
    @else
      <div style="display: flex;color: #EC1469;">
        {!! $createBy == $item->member_id ? '* ' : null !!}
        {{__('comments.this_is_a_secret_comment')}}
      </div>
    @endif
  <!-- Modal -->
    <div class="modal fade modal--confirm" id="reportPost2{{$item->id}}" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
        <div class="modal-content" style="padding-right: 0px">
          <div class="modal-body">
            <form action="{{route('ewhaian.report')}}" method="POST" id="report_form_top{{$item->id}}">
              @csrf
              <div>
                <input type="hidden" name="type_report" value="2">  <!-- report post -->
                <input type="hidden" name="type_post" value="{{$type_post ?? 1 }}"> <!-- type post -->
                <input type="hidden" name="id_post" value="{{$item->id}}"> <!-- type post -->
                <div class="d-lg-flex align-items-center mx-3">
                  <div class="d-lg-flex align-items-start flex-grow-1">
                    <div class="form-group form-group--1 flex-grow-1 mb-3">
                      <div class="md-form">
                        <div class="custom-control custom-checkbox" style="margin: 10px 0;background-color: #dddddd;padding: 20px;">
                          <div class="label">
                            <!-- [훌리건 신고] -->
                            {{__('comments.comments.report_hooligan')}}
                          </div>
                          @if(auth()->guard('member')->check())
                            <div style="margin: 10px 0">
                              신고자 : {{auth()->guard('member')->user()->id_login ?? ''}}
                            </div>
                          @endif
                          <div>신고에 신중해 주시길 바랍니다.
                            <br>적절하지 않은 신고는 반영되지 않으며
                            <br>불이익이 있을 수 있습니다.
{{--                            <div>{{__('comments.comments.report_info')}}--}}
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
                    <li style="padding-bottom: 10px;">
                      <div class="custom-control custom-checkbox mx-3">
                        <input type="radio" class="custom-control-input checkbox_report_comment"
                               id="reason_option_top12{{$item->id}}" name="reason_option" value="1" checked required>
                        <label class="custom-control-label" for="reason_option_top12{{$item->id}}">
                            <!-- 훌리건 의심 -->
                                    훌리건 의심
                        </label>
                      </div>
                    </li>
                    <li style="padding-bottom: 10px;">
                      <div class="custom-control custom-checkbox mx-3">
                        <input type="radio" class="custom-control-input checkbox_report_comment"
                               id="reason_option_top22{{$item->id}}" name="reason_option" value="2">
                        <label class="custom-control-label" for="reason_option_top22{{$item->id}}">
                           <!-- 회원에 대한 욕설 혹은 저격 -->
                                    {{__('comments.report_hooligan.insult_user')}}
                          </label>
                      </div>
                    </li>
                    <li style="padding-bottom: 10px;">
                      <div class="custom-control custom-checkbox mx-3">
                        <input type="radio" class="custom-control-input checkbox_report_comment"
                               id="reason_option_top32{{$item->id}}" name="reason_option" value="3">
                        <label class="custom-control-label" for="reason_option_top32{{$item->id}}">허위사실 유포</label>
                      </div>
                    </li>
                    <li style="padding-bottom: 10px;">
                      <div class="custom-control custom-checkbox mx-3">
                        <input type="radio" class="custom-control-input checkbox_report_comment"
                               id="reason_option_top42{{$item->id}}" name="reason_option" value="4">
                        <label class="custom-control-label" for="reason_option_top42{{$item->id}}">
                          <!-- 게시 자료의 저작권 위반 -->
                                    {{__('comments.report_hooligan.copyright')}}
                        </label>
                      </div>
                    </li>
                    <li style="padding-bottom: 10px;">
                      <div class="custom-control custom-checkbox mx-3">
                        <input type="radio" class="custom-control-input checkbox_report_comment"
                               id="reason_option_top52{{$item->id}}" name="reason_option" value="5">
                        <label class="custom-control-label" for="reason_option_top52{{$item->id}}">
                        <!-- 일반인 신상정보 게시 -->
                                    {{__('comments.report_hooligan.post_personal_info')}}
                        </label>
                      </div>
                    </li>
                    <li style="padding-bottom: 10px;">
                      <div class="custom-control custom-checkbox mx-3">
                        <input type="radio" class="custom-control-input checkbox_report_comment"
                               id="reason_option_top62{{$item->id}}" name="reason_option" value="6">
                        <label class="custom-control-label" for="reason_option_top62{{$item->id}}"> <!-- 지나친 홍보 또는 상거래 유도 -->
                                    {{__('comments.report_hooligan.commerce')}}
                                    </label>
                      </div>
                    </li>
                    <li style="padding-bottom: 10px;">
                      <div class="custom-control custom-checkbox mx-3">
                        <input type="radio" class="custom-control-input checkbox_report_comment"
                               id="reason_option_top72{{$item->id}}" name="reason_option" value="7">
                        <label class="custom-control-label" for="reason_option_top72{{$item->id}}">     <!-- 다른 게시판에 적절한 게시글 -->
                                    {{__('comments.report_hooligan.not_board')}}
                                    </label>
                      </div>
                    </li>
                    <li style="padding-bottom: 10px;">
                      <div class="custom-control custom-checkbox mx-3">
                        <input type="radio" class="custom-control-input checkbox_report_comment orther_report_comment"
                               id="reason_option_top82{{$item->id}}" name="reason_option" value="8">
                        <label class="custom-control-label" for="reason_option_top82{{$item->id}}">
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
                    <div class="md-form hidden_comment_report">
                                            <textarea id="reason_top2{{$item->id}}"
                                                      class="md-textarea form-control comment_report" rows="5"
                                                      name="reason" required
                                                      style="border: none;border-bottom: 1px solid #e8e8e8;"
                                            >N/A</textarea>
                      <label for="reason2{{$item->id}}">신고 사유를 입력해주세요</label>
                    </div>
                  </div>
                </div>
                <div class="button-group mb-2">
                        <!-- <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">cancel</button> -->
                            <button type="button" class="btn btn-cancel mr-lg-10" data-dismiss="modal">{{__('comments.report_hooligan.cancel')}}</button>
                            <!-- <button type="submit" class="btn btn-primary">Send</button> -->
                            <button type="submit" class="btn btn-ok">{{__('comments.report_hooligan.send')}}</button>
                </div>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    @if(auth()->guard('member')->check())
      <form action="{{route($route)}}" method="POST">
        @csrf
        <input type="hidden" name="{{$nameDetail}}" value="{{$idDetail}}">
        <input type="hidden" name="parents_id" value="{{$item->id}}">
        <div class="item__form">
          <div class="comments__name" style="display: flex">
            {!! getStatusWriter(auth()->guard('member')->user()->certification ??
            'real_name_certification')!!}
            {{getName(auth()->guard('member')->user()->nickname,auth()->guard('member')->user()->id_login)}}

          </div>
          <div class="form-group">
            <textarea name="content" cols="30" rows="10" class="form-control form-control--textarea"
                      placeholder="{{__('comments.enter_your_comment')}}" required></textarea>
          </div>
          <div class="row">
            <div class="col-6">
              @if(
                  Route::currentRouteName() != 'gardenFE.details' &&
                  Route::currentRouteName() != 'masterRoomFE.detail' &&
                  Route::currentRouteName() != 'campus.genealogy_details'
              )

                <div class="form-check form-check--checkbox">
                  <input type="checkbox" class="form-check-input" id="secret{{$item->id}}" value="1"
                         name="is_secret_comments" checked/>
                  <label class="form-check-label" for="secret{{$item->id}}">
                    <svg width="12" height="9" aria-hidden="true" class="icon">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_check"></use>
                    </svg>
                    {{__('comments.secret_comments')}}
                  </label>
                </div>

              @endif
            </div>
            <div class="col-6 text-right">
              <button class="form-submit" type="submit">{{__('comments.write')}}</button>
            </div>
          </div>
        </div>

      </form>
    @endif
  </div>
</div>
