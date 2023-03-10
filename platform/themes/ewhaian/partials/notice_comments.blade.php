<style>
  span.icon-label.disable, .icon-label.gray {
    color: #999999 !important;
    border-color: #999999 !important;;
  }

  span.icon-label.disable svg {
    transform: scale(0.8);
    top: -3px;
    position: relative;
  }

  span.icon-label.pink svg {
    transform: scale(0.8);
    top: -3px;
    position: relative;
  }

  .single .comments .item__time span {
    font-size: 1.2em;
    color: #EC1469;
    width: 20px;
    height: 20px;
    margin-right: 0.71429em;
  }

  .comments__name span.icon-label.disable svg {
    transform: scale(0.8);
    top: -6px;
    position: relative;
  }

  .comments__name span.icon-label.pink svg {
    transform: scale(0.8);
    top: -6px;
    position: relative;
  }

  .comments__name span {
    font-size: 1.2em;
    color: #EC1469;
    width: 20px;
    height: 20px;
    margin-right: 0.71429em;
  }

  .single .comments .delete {
    height: 30px;
    margin-left: 10px;
    border: 1px solid #EC1469;
    color: #EC1469;
    background-color: #ffffff;
    white-space: nowrap;
  }

  .icon--e {
    width: 2.25em;
    height: 2.25em;
    line-height: 1.875em;
    border: 1px solid #EC1469;
    color: #EC1469;
    text-align: center;
    border-radius: 50%;
  }

  .hidden_comment_report {
    display: none;
  }

  .hidden_sub_comment_report {
    display: none;
  }

  .item__content {
    word-break: break-all;
  }
  .single .comments .item__time.flex-c {
      display: flex;
      align-items: center;
  }

  .alert2{
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.2);
    margin: 0 !important;
    border: none;
  }
  .alert-con{
      width: 300px;
      background: #ffffff;
      border: none;
      position: fixed;
      top: 50%;
      left: 50%;
      padding: 20px;
      border-radius: 5px;
      transform: translate(-50%, -50%);
  }
  .c-alert{
      margin: 20px 0 0 auto;
      width: 100px;
      height: 40px;
      padding: 0;
      display: block;
  }
  .icon-label.disable, .icon-label.gray, .icon-label.disable use{color: #999999;}

  .mr-left-4 {
      margin-left: 4px;
  }

</style>

<!-- comments -->
<div class="comments">
  <hr>
  <div style="display: flex; position: relative;margin-bottom: 40px;z-index:1000">

  </div>
  @if($canViewComment ?? false)
    <div class="comments__title opened">
      <h3>{!! __('comments.comments_count',['count'=>$countCmt]) !!}</h3>
      <button class="comments__toggle">
        <svg width="16" height="7" aria-hidden=" true" class="icon">
          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow"></use>
        </svg>
      </button>
    </div>
    <p style="font-size: 0.85714em;">
    <!-- 욕설, 도배, 비방, 루머 등 운영정책에 어긋나는 게시물 등록 시에는 글쓰기 제한 등 불이익을 받으실 수 있습니다. -->
      {{__('comments.writing_rules')}}
    </p>
    <div class="comments__toggle-content" style="display: block;">
      @if (session('success'))
        <div class="alert alert-success alert2">
          <div class="alert-con">
              {{ session('success') }}
              <button class="btn btn-primary c-alert">확인</button>
          </div>
        </div>
      @endif
      @if (session('err'))
        <div class="alert alert-danger" style="display: block; margin-top: 5px">
          {{ session('err') }}
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        </div>
      @endif

      <div class="comments__list">
        @foreach ($top_comments as $item)
          {!! Theme::partial('top_comments', [
              'item' => $item,
              'canDeleteComment' => $canDeleteComment,
              'routeDelete' => $routeDelete,
              'route' => $route,
              'nameDetail' => $nameDetail,
              'idDetail' => $idDetail,
              'createBy' => $createBy
          ]) !!}
        @endforeach
        <hr>
        @foreach ($comments as $item)

          <input type="hidden" name="parents_id" value="{{$item->id}}">
          <div class="item">
            <div class="item__head d-flex flex-wrap align-items-center justify-content-between">
              <div style="display: flex;">
                <div class="item__time flex-c">
                  <div style="display: flex">
                    @if( $item->member_id == null )
                      {!! getStatusWriter('real_name_certification') !!}
                    @else
                      {!! getStatusWriter($item->members->certification ?? 'real_name_certification') !!}
                    @endif

                    {{getName($item->members->nickname ??  __('comments.admin'), $item->members->id_login ??  __('comments.admin'), $canViewCommenter ?? false) }}

                    @if ($item->anonymous == 1 && !is_null($item->ip_address))
                    <a target="_parent" onclick="ipPopClick('{{preg_replace('/([0-9]+\.[0-9]+\.[0-9]+)\.[0-9]+/', '\1.xxx', $item->ip_address)}}')" >
                      <span class="icon-label span_hidden_id"
                            style="cursor: pointer;font-size: 10px;line-height: 2em;width: 100%;height: 2em;border-radius: 5px;display: none;background-color: #f4f4f4;border: 1px solid #f4f4f4;margin-left: 0.71429em;margin-right: 0px;">
                        @php
                          $randomIp = mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255);
                        @endphp
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
                       @if( is_null($item->likes->find(auth()->guard('member')->user()->id)) && $item->dislikes_count > 0) data-like="true"
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
                       @if(is_null($item->dislikes->find(auth()->guard('member')->user()->id))) data-dislike="true"
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
                  <button data-target="#reportPost2{{$item->id}}" data-toggle="modal" class="mr-0 mr-left-4 mr-sm-1">
                    <img src="{{Theme::asset()->url('img/dependence.svg')}}"
                         alt="{{__('comments.declaration')}}" width="18.008" height="13.506">
                    {{__('comments.declaration')}}
                  </button>
                  @if($canCreateComment ?? false)
                    <button class="item__reply mr-0 mr-left-4 mr-sm-1">
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
{{--              @if( $item->anonymous < 1 || Route::currentRouteName() === 'contents.details')--}}
{{--                {{$item->content}}--}}
{{--              @else--}}
{{--                <div>--}}
{{--                  <div style="display: flex;color: #EC1469;">--}}
{{--                    {{__('comments.this_is_a_secret_comment')}}--}}
{{--                  </div>--}}
{{--                  @if(auth()->guard('member')->check())--}}
{{--                    @if($item->member_id == auth()->guard('member')->user()->id ||--}}
{{--                        auth()->guard('member')->user()->id== $createBy ||--}}
{{--                        auth()->guard('member')->user()->hasPermission('member.show') )--}}
{{--                      {!! nl2br(e($item->content))!!}--}}
{{--                    @endif--}}
{{--                  @endif--}}
{{--                </div>--}}
{{--              @endif--}}

              @if( $item->anonymous == 0 ||
                  $item->member_id == auth()->guard('member')->user()->id  ||
                  $createBy == auth()->guard('member')->user()->id )
                {{$item->content}}
              @else
                <div style="display: flex;color: #EC1469;">
                  {{__('comments.this_is_a_secret_comment')}}
                </div>
              @endif


            <!-- Modal -->
              <div class="modal fade modal--confirm" id="reportPost2{{$item->id}}" tabindex="-1" role="dialog"
                   aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
                  <div class="modal-content" style="padding-right: 0px">
                    <div class="modal-body">
                      <form action="{{route('ewhaian.report')}}" method="POST" id="report_form{{$item->id}}">
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
                                      {{__('comments.report_hooligan')}}
                                    </div>
                                    @if(auth()->guard('member')->check())
                                      <div style="margin: 10px 0">
                                        신고자 : {{auth()->guard('member')->user()->id_login ?? ''}}
                                        |  신고글번호  :  {{$item->id}}
                                      </div>
                                    @endif
                                    <div>신고에 신중해 주시길 바랍니다.
                                      <br>적절하지 않은 신고는 반영되지 않으며
                                      <br>불이익이 있을 수 있습니다.
{{--                                      <div>{{__('comments.report_info')}}--}}
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
                                         id="reason_option12{{$item->id}}" name="reason_option" value="1" checked
                                         required>
                                  <label class="custom-control-label" for="reason_option12{{$item->id}}">
                                    <!-- 훌리건 의심 -->
                                    {{__('comments.report_hooligan.suspect_hooligan')}}
                                  </label>
                                </div>
                              </li>
                              <li style="padding-bottom: 10px;">
                                <div class="custom-control custom-checkbox mx-3">
                                  <input type="radio" class="custom-control-input checkbox_report_comment"
                                         id="reason_option22{{$item->id}}" name="reason_option" value="2">
                                  <label class="custom-control-label" for="reason_option22{{$item->id}}">
                                     <!-- 회원에 대한 욕설 혹은 저격 -->
                                    {{__('comments.report_hooligan.insult_user')}}
                                    </label>
                                </div>
                              </li>
                              <li style="padding-bottom: 10px;">
                                <div class="custom-control custom-checkbox mx-3">
                                  <input type="radio" class="custom-control-input checkbox_report_comment"
                                         id="reason_option32{{$item->id}}" name="reason_option" value="3">
                                  <label class="custom-control-label" for="reason_option32{{$item->id}}">
                                  <!-- 허위사실 유포 -->
                                    {{__('comments.report_hooligan.fake_info')}}
                                  </label>
                                </div>
                              </li>
                              <li style="padding-bottom: 10px;">
                                <div class="custom-control custom-checkbox mx-3">
                                  <input type="radio" class="custom-control-input checkbox_report_comment"
                                         id="reason_option42{{$item->id}}" name="reason_option" value="4">
                                  <label class="custom-control-label" for="reason_option42{{$item->id}}">
                                      <!-- 게시 자료의 저작권 위반 -->
                                    {{__('comments.report_hooligan.copyright')}}
                                  </label>
                                </div>
                              </li>
                              <li style="padding-bottom: 10px;">
                                <div class="custom-control custom-checkbox mx-3">
                                  <input type="radio" class="custom-control-input checkbox_report_comment"
                                         id="reason_option52{{$item->id}}" name="reason_option" value="5">
                                  <label class="custom-control-label" for="reason_option52{{$item->id}}">
                                  <!-- 일반인 신상정보 게시 -->
                                    {{__('comments.report_hooligan.post_personal_info')}}
                                  </label>
                                </div>
                              </li>
                              <li style="padding-bottom: 10px;">
                                <div class="custom-control custom-checkbox mx-3">
                                  <input type="radio" class="custom-control-input checkbox_report_comment"
                                         id="reason_option62{{$item->id}}" name="reason_option" value="6">
                                  <label class="custom-control-label" for="reason_option62{{$item->id}}">
                                   <!-- 지나친 홍보 또는 상거래 유도 -->
                                    {{__('comments.report_hooligan.commerce')}}
                                  </label>
                                </div>
                              </li>
                              <li style="padding-bottom: 10px;">
                                <div class="custom-control custom-checkbox mx-3">
                                  <input type="radio" class="custom-control-input checkbox_report_comment"
                                         id="reason_option72{{$item->id}}" name="reason_option" value="7">
                                  <label class="custom-control-label" for="reason_option72{{$item->id}}">
                                     <!-- 다른 게시판에 적절한 게시글 -->
                                    {{__('comments.report_hooligan.not_board')}}
                                  </label>
                                </div>
                              </li>
                              <li style="padding-bottom: 10px;">
                                <div class="custom-control custom-checkbox mx-3">
                                  <input type="radio"
                                         class="custom-control-input checkbox_report_comment orther_report_comment"
                                         id="reason_option82{{$item->id}}" name="reason_option" value="8">
                                  <label class="custom-control-label" for="reason_option82{{$item->id}}">
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
                                                        <textarea id="reason2{{$item->id}}"
                                                                  class="md-textarea form-control comment_report"
                                                                  rows="5"
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
              <!-- End Modal -->
              <div class="comments__list" style="line-height: 1.71429em;letter-spacing: 0.5px;border-radius: 4px;">
                @foreach ($item->getAllCommentByParentsID($item->id) as $subitem)

                  <div class="item"
                       style="line-height: 1.71429em;letter-spacing: 0.5px;background-color: #ffffff;padding: 1.07143em 1.42857em;margin-top: 0.71429em;border-radius: 4px;">
                    <div class="item__head d-flex flex-wrap align-items-center justify-content-between">
                      <div style="display: flex">
                        <div class="item__time">
                          <div style="display: flex">
                            @if($subitem->member_id == null)
                              {!! getStatusWriter('real_name_certification') !!}
                            @else
                              {!! getStatusWriter($subitem->members->certification ??
                              'real_name_certification') !!}
                            @endif

                            {{getName(
                                $item->members->nickname ??  __('comments.admin'),
                                $item->members->id_login ??  __('comments.admin'),
                                $canViewCommenter ?? false
                            )}}

                            @if( $subitem->anonymous == 1 && !is_null($subitem->ip_address) )
                                <a target="_parent" onclick="ipPopClick('{{preg_replace('/([0-9]+.[0-9]+.[0-9]+).[0-9]+/', '1.xxx', $item->ip_address)}}')" >
                                @php
                                  $randomIp = mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255);
                                @endphp

                                <span class="icon-label span_hidden_id" style="cursor: pointer;font-size: 10px;line-height: 2em;width: 100%;height: 2em;border-radius: 5px;display: none;background-color: #f4f4f4;border: 1px solid #f4f4f4;margin-left: 0.71429em;margin-right: 0px;">
                                  {{ '(' . $randomIp . ')' }}
                                </span>

                                <span class="icon-label span_show_id" style="cursor: pointer;font-size: 10px;line-height: 2em;width: 5em;height: 2em;border-radius: 5px;background-color: #f4f4f4;border: 1px solid #f4f4f4;margin-left: 0.71429em;margin-right: 0px;">
                                  IP
                                </span>
                              </a>
                            @endif
                          </div>
                          <p style="margin-top: -6px;">{{$subitem->created_at}}</p>
                        </div>
                        @if( getStatusDateByDate($subitem->created_at) == "Today" ) <span class="icon-label"
                                                                                          style="margin-left: 10px">N</span> @endif
                        @if(auth()->guard('member')->check())
                          @if( ( hasPermission('memberFE.isAdmin') || $subitem->member_id == auth()->guard('member')->user()->id ) && $canDeleteComment  )
                            <div class="item__time">
                              <form action="{{route($routeDelete,['id'=>$subitem->id])}}" method="POST">
                                @csrf
                                <button class="form-submit delete" type="submit">{{__('comments.delete')}}</button>
                              </form>
                            </div>
                          @endif
                        @endif
                      </div>
                      <div class="item__action">
                        <div class="single__date">
                          <a href="javascript:void(0)" class="modal-comment-like comment-{{$subitem->id}}-like mr-sm-1"
                             @if( is_null($subitem->likes->find(auth()->guard('member')->user()->id)) && $subitem->dislikes_count > 0) data-like="true"
                             @else data-like="false" @endif data-comment_id="{{$subitem->id}}">
                            <svg
                              class="icon like" width="20" height="16.56" aria-hidden="true"
                              style="@if(is_null($subitem->likes->find(auth()->guard('member')->user()->id)))  color:black  @else color:#EC1469  @endif ; "
                            >
                              <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_like"></use>
                            </svg>
                            <span id="like_count{{$subitem->id}}">{{ $subitem->likes_count ?? 0 }}</span>
                          </a>
                        </div>
                        <div class="single__date">
                          <a href="javascript:void(0)" class="modal-comment-dislike comment-{{$subitem->id}}-dislike mr-sm-1"
                             @if(is_null($subitem->dislikes->find(auth()->guard('member')->user()->id))) data-dislike="true"
                             @else data-dislike="false" @endif data-comment_id="{{$subitem->id}}">
                            <svg
                              class="icon" width="20" height="16.56" aria-hidden="true"
                              style="  @if(is_null($subitem->dislikes->find(auth()->guard('member')->user()->id)))  color:black  @else color:#EC1469  @endif ; transform: rotate(180deg); "
                            >
                              <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_like"></use>
                            </svg>
                            <span id="dislike_count{{$subitem->id}}">{{ $subitem->dislikes_count ?? 0 }}</span>
                          </a>

                        </div>
                        <button data-target="#reportPost2{{$subitem->id}}" data-toggle="modal" class="mr-0 mr-left-4 mr-sm-1">
                          <img src="{{Theme::asset()->url('img/dependence.svg')}}"
                               alt="{{__('comments.declaration')}}" width="18.008" height="13.506">
                          {{__('comments.declaration')}}
                        </button>
                      </div>
                    </div>
                    <div class="item__content">
{{--                      @if( $subitem->anonymous < 1 || Route::currentRouteName() === 'contents.details')--}}
{{--                        {{$subitem->content}}--}}
{{--                      @else--}}
{{--                        <div>--}}
{{--                          <div style="display: flex;color: #EC1469;">--}}
{{--                            {{__('comments.this_is_a_secret_comment')}}--}}
{{--                          </div>--}}
{{--                          @if(auth()->guard('member')->check())--}}
{{--                            @if($subitem->member_id == auth()->guard('member')->user()->id ||--}}
{{--                            auth()->guard('member')->user()->id == $createBy ||--}}
{{--                            auth()->guard('member')->user()->hasPermission('member.show') )--}}
{{--                              {{$subitem->content}}--}}
{{--                            @endif--}}
{{--                          @endif--}}
{{--                        </div>--}}
{{--                      @endif--}}

                      @if( $subitem->anonymous == 0 ||
                        $subitem->member_id == auth()->guard('member')->user()->id  ||
                        $createBy == auth()->guard('member')->user()->id )
                        {{$subitem->content}}
                      @else
                        <div style="display: flex;color: #EC1469;">
                          {{__('comments.this_is_a_secret_comment')}}
                        </div>
                      @endif

                    </div>
                    <!-- Modal -->
                    <div class="modal fade modal--confirm" id="reportPost2{{$subitem->id}}" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
                        <div class="modal-content" style="padding-right: 0px">
                          <div class="modal-body">
                            <form action="{{route('ewhaian.report')}}" method="POST" id="report_form{{$subitem->id}}">
                              @csrf
                              <div>
                                <input type="hidden" name="type_report" value="2">  <!-- report post -->
                                <input type="hidden" name="type_post" value="{{$type_post ?? 1 }}"> <!-- type post -->
                                <input type="hidden" name="id_post" value="{{$subitem->id}}"> <!-- type post -->
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
                                              신고자 : {{auth()->guard('member')->user()->id_login ?? ''}}
                                              |  신고글번호  :  {{$subitem->id}}
                                            </div>
                                          @endif
                                          <div>신고에 신중해 주시길 바랍니다.
                                            <br>적절하지 않은 신고는 반영되지 않으며
                                            <br>불이익이 있을 수 있습니다.
{{--                                            <div>{{__('comments.report_info')}}--}}
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="flex-line" style="display: flex">
                                  <ul class="listColor">
                                    <li style="padding-bottom: 10px;">
                                      <div class="custom-control custom-checkbox mx-3">
                                        <input type="radio" class="custom-control-input checkbox_report_sub_comment"
                                               id="reason_option12{{$subitem->id}}" name="reason_option" value="1"
                                               checked required>
                                        <label class="custom-control-label" for="reason_option12{{$subitem->id}}">훌리건
                                          의심</label>
                                      </div>
                                    </li>
                                    <li style="padding-bottom: 10px;">
                                      <div class="custom-control custom-checkbox mx-3">
                                        <input type="radio" class="custom-control-input checkbox_report_sub_comment"
                                               id="reason_option22{{$subitem->id}}" name="reason_option" value="2">
                                        <label class="custom-control-label" for="reason_option22{{$subitem->id}}">회원에 대한
                                          욕설 혹은 저격</label>
                                      </div>
                                    </li>
                                    <li style="padding-bottom: 10px;">
                                      <div class="custom-control custom-checkbox mx-3">
                                        <input type="radio" class="custom-control-input checkbox_report_sub_comment"
                                               id="reason_option32{{$subitem->id}}" name="reason_option" value="3">
                                        <label class="custom-control-label" for="reason_option32{{$subitem->id}}">허위사실
                                          유포</label>
                                      </div>
                                    </li>
                                    <li style="padding-bottom: 10px;">
                                      <div class="custom-control custom-checkbox mx-3">
                                        <input type="radio" class="custom-control-input checkbox_report_sub_comment"
                                               id="reason_option42{{$subitem->id}}" name="reason_option" value="4">
                                        <label class="custom-control-label" for="reason_option42{{$subitem->id}}">일반인
                                          신상정보 게시</label>
                                      </div>
                                    </li>
                                    <li style="padding-bottom: 10px;">
                                      <div class="custom-control custom-checkbox mx-3">
                                        <input type="radio" class="custom-control-input checkbox_report_sub_comment"
                                               id="reason_option52{{$subitem->id}}" name="reason_option" value="5">
                                        <label class="custom-control-label" for="reason_option52{{$subitem->id}}">일반인
                                          신상정보 게시</label>
                                      </div>
                                    </li>
                                    <li style="padding-bottom: 10px;">
                                      <div class="custom-control custom-checkbox mx-3">
                                        <input type="radio" class="custom-control-input checkbox_report_sub_comment"
                                               id="reason_option62{{$subitem->id}}" name="reason_option" value="6">
                                        <label class="custom-control-label" for="reason_option62{{$subitem->id}}">지나친 홍보
                                          또는 상거래 유도</label>
                                      </div>
                                    </li>
                                    <li style="padding-bottom: 10px;">
                                      <div class="custom-control custom-checkbox mx-3">
                                        <input type="radio" class="custom-control-input checkbox_report_sub_comment"
                                               id="reason_option72{{$subitem->id}}" name="reason_option" value="7">
                                        <label class="custom-control-label" for="reason_option72{{$subitem->id}}">다른
                                          게시판에 적절한 게시글</label>
                                      </div>
                                    </li>
                                    <li style="padding-bottom: 10px;">
                                      <div class="custom-control custom-checkbox mx-3">
                                        <input type="radio"
                                               class="custom-control-input checkbox_report_sub_comment orther_report_sub_comment"
                                               id="reason_option82{{$subitem->id}}" name="reason_option" value="8">
                                        <label class="custom-control-label"
                                               for="reason_option82{{$subitem->id}}">기타</label>
                                      </div>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                              <div class="d-lg-flex align-items-center mx-3">
                                <div class="d-lg-flex align-items-start flex-grow-1">

                                  <div class="form-group form-group--1 flex-grow-1 mb-3">
                                    <div class="md-form hidden_sub_comment_report">
                                                    <textarea id="reason2{{$subitem->id}}"
                                                              class="md-textarea form-control sub_comment_report"
                                                              rows="5"
                                                              name="reason" required
                                                              style="border: none;border-bottom: 1px solid #e8e8e8;"
                                                    >N/A</textarea>
                                      <label for="reason2{{$subitem->id}}">신고 사유를 입력해주세요</label>
                                    </div>
                                  </div>
                                </div>
                                <div class="button-group mb-2">
                                  <button type="button" class="btn btn-cancel mr-lg-10" data-dismiss="modal">cancel
                                  </button>
                                  <button type="submit" class="btn btn-ok" id="dislike">Send</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- End Modal -->
                  </div>
                @endforeach
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
                        @if (Route::currentRouteName() === 'gardenFE.details')
                          <input type="hidden" class="form-check-input" id="secret{{$item->id}}" value="1"
                                 name="is_secret_comments" checked/>
                        @endif

                        @if (
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
        @endforeach
      </div>
        <!-- Modal -->
        <div class="modal fade" id="ipPopup" tabindex="-1" role="dialog" aria-labelledby="ipModal">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-body">
                - 임의아이디는 글마다 부여되는 랜덤키입니다.<br />
                - 임의아이디 : <span id="randomID"></span><br />
                - 아이피 : <span id="IPadress"></span>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline" style="padding:0 20px; line-height: 38px; height: 40px;" data-dismiss="modal">닫기</button>
              </div>
            </div>
          </div>
        </div>
        @endif
      @if($canCreateComment ?? false)
        <form action="{{route($route)}}" method="POST">
          @csrf
          <div class="comments__form"><br>
            <div class="comments__name" style="display: flex">
              {!! getStatusWriter(auth()->guard('member')->user()->certification ?? 'real_name_certification') !!}
              {{ getName(auth()->guard('member')->user()->nickname, auth()->guard('member')->user()->id_login) }}
            </div>
            <div class="form-group">
            <textarea name="content" id="" cols="30" rows="10" class="form-control form-control--textarea"
                      placeholder="{{__('comments.enter_your_comment')}}" required></textarea>
            </div>
            <input type="hidden" name="{{$nameDetail}}" value="{{$idDetail}}">
            <div class="row">
              <div class="col-6">
                @if (Route::currentRouteName() === 'gardenFE.details')
                  <input type="hidden" class="form-check-input" id="secret" value="1" name="is_secret_comments"
                         checked/>
                @endif

                @if(
                    Route::currentRouteName() != 'gardenFE.details' &&
                    Route::currentRouteName() != 'masterRoomFE.detail' &&
                    Route::currentRouteName() != 'campus.genealogy_details'
                )
                  <div class="form-check form-check--checkbox">
                    <input type="checkbox" class="form-check-input" id="secret" value="1" name="is_secret_comments"
                           checked/>
                    <label class="form-check-label" for="secret">
                      <svg width="12" height="9" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_check">
                        </use>
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
      @else
        @if(!auth()->guard('member')->check())
          <div class="comments__form"><br>
            <p class="comments__name"></p>
            <div class="form-group">
              <a href="{{route('public.member.login')}}" title="Login" style="color: #EC1469;">
                <svg width="40" height="18" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_key">
                  </use>
                </svg>
                <span class="">{{__('comments.please_login_to_comments')}}</span>
              </a>
            </div>
          </div>
        @endif
      @endif

    </div>
</div>
<!-- end of comments -->
{!! Theme::partial('paging',['paging'=>$comments ]) !!}

<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmDelete" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
    <div class="modal-content pr-0">
      <div class="modal-body">

        <div class="align-items-center mx-lg-2">
          <div class="d-lg-flex align-items-start flex-grow-1">
            <div class="form-group form-group--1 flex-grow-1 mr-lg-30 m-3">
              <label for="hint" class="form-control">
                <input type=" text" id="hint" value="{{__('controller.confirm_delete')}}"
                       placeholder="&nbsp;" readonly>
              </label>
            </div>
          </div>
          <form action="{{route($deleteItem)}}" method="post">
            @csrf
            <div class="button-group mb-2"  style="display: flex; justify-content: flex-end">
              <input type="hidden" value="{{$idDetail}}" id="events_id" name="id">
              <button type="button" class="btn btn-cancel mr-lg-10"
                      data-dismiss="modal">{{__('event.cancel')}}</button>
              <button type="submit" class="btn btn-ok">{{__('event.delete')}}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@if(isset($show_pwd_post))
  <!-- Modal -->
  <div class="modal fade modal--confirm" id="confirmPwdPost" tabindex="-1" role="dialog"
       aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header align-items-center justify-content-lg-center">
                    <span class="modal__key">
                        <svg width="40" height="18" aria-hidden="true">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_key"></use>
                        </svg>
                    </span>
        </div>
        <div class="modal-body">
          @if (\Route::current()->getName() === 'gardenFE.create')
            <input type="hidden" name="is_create" value="1">
          @else
            <input type="hidden" name="is_create" value="0">
          @endif
          <div class="d-lg-flex align-items-center mx-3">
            <div class="d-lg-flex align-items-start flex-grow-1">
              <div class="form-group form-group--1 flex-grow-1 mr-lg-30 mb-3">
                <label for="hint" class="form-control">
                  <input type=" text" id="hint" value="{{$hint ?? ''}}" placeholder="&nbsp;"
                         maxlength="120" readonly style="cursor: unset;">
                  <span class="form-control__label" style="cursor: unset;">질문 제목 입력</span>
                </label>
              </div>
              <div class="form-group form-group--1 flex-grow-1 mb-3">
                <label for="passwordPostEdit" class="form-control form-control--hint">
                  <input type="password" id="passwordPostEdit" name="pwd_post_edit" placeholder="&nbsp;"
                         value="" maxlength="16">
                  <span class="form-control__label">비밀번호를 입력하세요</span>
                </label>
                <span class="form-control__hint" id="msg">질문 정답 입력</span>
              </div>
            </div>
            <div class="button-group mb-2" style="width: 168px;">
              <button type="button" class="btn btn-cancel mr-lg-10"
                      data-dismiss="modal">{{__('garden.cancel')}}</button>
              <button type="button" class="btn btn-ok submitByPwdPostPopup">{{__('garden.save')}}</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endif
{!! Theme::partial('popup.like_dislike_comment', [
    'route_like' => $route_like ?? '/',
    'route_dislike' => $route_dislike ?? '/',
    'idDetail' => $idDetail
]) !!}
@if(Route::currentRouteName() === 'gardenFE.details')
  <!-- Modal -->
  <div class="modal fade modal--confirm" id="confirmDelete2" tabindex="-1" role="dialog"
       aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header align-items-center justify-content-lg-center">
               <span class="modal__key">
                   <svg width="40" height="18" aria-hidden="true">
                       <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_key"></use>
                   </svg>
               </span>
        </div>
        <div class="modal-body">
          <input type="hidden" name="is_create" value="0">
          <div class="d-lg-flex align-items-center mx-3">
            <div class="d-lg-flex align-items-start flex-grow-1">
              <div class="form-group form-group--1 flex-grow-1 mb-3">
                <label for="passwordPost" class="form-control form-control--hint">
                  <input type="password" id="passwordPost" name="pwd_post" placeholder="&nbsp;"
                         value="" maxlength="16" required>
                  <span class="form-control__label">{{__('garden.set_password')}}</span>
                </label>
                <span class="form-control__hint" id="msg">{{__('garden.up_to_16_characters')}}</span>
              </div>
            </div>
            <div class="button-group mb-2">
              <button type="button" class="btn btn-cancel mr-lg-10"
                      data-dismiss="modal">{{__('garden.cancel')}}</button>
              <button type="button" class="btn btn-ok submitByPopup">{{__('garden.save')}}</button>
            </div>
            <form action="{{route($deleteItem)}}" method="post" id="modalConfirmDelete">
              @csrf
              <input type="hidden" value="{{$idDetail}}" id="events_id" name="id">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(function () {
      $('.submitByPopup').on('click', function (e) {

        e.preventDefault();
        if ($('[name="pwd_post"]').val() == '') {
          alert('Password is required');
          $('[name="pwd_post"]').focus();
          return;
        }

        $.ajax({
          type: 'POST',
          url: '{{route('gardenFE.ajaxPasswdPost')}}',
          data: {
            _token: "{{ csrf_token() }}",
            'pwd_post': $('[name="pwd_post"]').val(),
            'id': {{$idDetail}},
            'is_create': 0,
          },
          success: function (data) {
            if (data.check == true) {
              $('#modalConfirmDelete').submit();
            } else {
              $('#msg').html(data.msg);
            }

          },
        });

      });
    })
  </script>
@else
  <script>
    $(function () {
      $('.deleteRoom').on('click', function () {
        console.log($(this).attr('data-value'));
        $('#events_id').val($(this).attr('data-value'));
      })
    })
  </script>
@endif

<script>
  $(document.body).on('click', '.checkbox_report_comment', function (e) {
    if ($('.orther_report_comment').is(':checked')) {
      $('.hidden_comment_report .comment_report').val('');
      $('.hidden_comment_report').show();
    } else {
      $('.hidden_comment_report').hide();
      $('.hidden_comment_report .comment_report').val('N/A');
    }
  })
  $(document.body).on('click', '.checkbox_report_sub_comment', function (e) {
    if ($('.orther_report_sub_comment').is(':checked')) {
      $('.hidden_sub_comment_report .sub_comment_report').val('');
      $('.hidden_sub_comment_report').show();
    } else {
      $('.hidden_sub_comment_report').hide();
      $('.hidden_sub_comment_report .sub_comment_report').val('N/A');
    }
  })
  $(document.body).on('click', '.show_ip', function (e) {

    if ($(this).attr('data-check') == 0) {
      $(this).attr('data-check', 1);
      $(this).find(' .span_hidden_id').show();
      $(this).find('.span_show_id').hide();
    } else {
      $(this).attr('data-check', 0);
      $(this).find(' .span_hidden_id').hide();
      $(this).find(' .span_show_id').show();


    }
  })
  $(document.body).on('click', '.submitByPwdPostPopup', function (e) {
    e.preventDefault();

    if ($('[name="pwd_post_edit"]').val() == '') {
      $('#msg').html('<div style="color: #EC1469;">Password is required</div>');
      $('[name="pwd_post_edit"]').focus();
      return;
    }
    $.ajax({
      type: 'POST',
      url: '{{route('gardenFE.ajaxPasswdPost')}}',
      data: {
        _token: "{{ csrf_token() }}",
        'pwd_post': $('[name="pwd_post_edit"]').val(),
        'id': {{$idDetail}},
        'is_create': 0,
      },
      success: function (data) {
        if (data.check == true) {
          @if(Route::currentRouteName() == 'egardenFE.details' || Route::currentRouteName() == 'egardenFE.notice.details')
            window.location.href = "{{route($editItem, ['id'=> $idDetail,'idEgarden'=> $idDetail])}}";
          @else
            window.location.href = "{{route($editItem, ['id'=> $idDetail])}}";
          @endif
        } else {
          $('#msg').html(data.msg);
        }
      },
    });
  });
  $('.c-alert').click(function(){
      $('.alert').css('display','none');
  });
  function ipPopClick(ip){
      $('#ipPopup').modal('toggle');
      $('#IPadress').html(ip);
  }
  $('#ipPopup').on('shown.bs.modal',function (e){
    $('#randomID').html(makeid());

    console.log(e)
  });
  function makeid()
  {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    for( var i=0; i < 5; i++ )
      text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
  }
</script>
