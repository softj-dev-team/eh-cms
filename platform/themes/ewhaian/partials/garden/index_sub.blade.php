@php
  $bypassPermission = auth()->guard('member')->user()->hasPermission('gardenFE.bypass_password_requirement');
@endphp

<style>
  body {
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none
  }

  @media (max-width: 768px) {
    .date {
      padding: 0 18px;
      white-space: nowrap;
    }

    .garden-title {
      padding: 0 20px;
      white-space: nowrap;
    }

    .mobile-hide {
      display: none;
    }

    .garden-title {
      overflow: hidden;
      max-width: 43%;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
  }

  /* #background {
    position: absolute;
    z-index: -1;
    background:transparent !important;
    display: flex;
    top: 150px;
    flex-wrap: wrap;
  }

  .bg-text {
    color: lightgrey;
    font-size: 50px;
    transform: rotate(320deg);
    -webkit-transform: rotate(320deg);
    margin-top: 50px;
    opacity: 1%;
  } */

  .sidebar-template__content {
    position: relative;
  }

  .form-control::placeholder {
    color: #999999;
  }

  .avoid-click {
    pointer-events: none;
  }

  .submitByPwdPostPopup {
    height: 2.5em !important;
  }
</style>

<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      <div class="content" style="width:100%">
        <div class=" table-responsive">
          <table class="table table--content-middle">
            <thead>
            <th style="text-align: center;" class="mobile-hide">{{__('garden.id')}}</th>
            @if(request('type') > 0 )
              <th style="text-align: center;">{{__('garden.detail')}}</th>
            @else
              <th style="text-align: center;">{{__('garden.title')}}</th>
            @endif
            <th style="text-align: center;" class="mobile-hide">{{__('garden.date')}}</th>
            <th style="text-align: center;">{{__('garden.lookup')}}</th>
            </thead>
            <tbody>

            @foreach ($garden as $key=>$item)
              @php
                $diff = $item->dislikes_count - $item->likes_count;
                $className = '';

                if ($diff < 5) {
                    $opacity = 100;
                } elseif ($diff < 10) {
                    $opacity = 80;
                } elseif ($diff < 20) {
                    $opacity = 60;
                } elseif ($diff < 60) {
                    $opacity = 40;
                } else {
                    $opacity = 20;
                    $className = 'avoid-click';
                }
              @endphp
              @if($key >= 0)
                @if($item->status == 'publish')
                  <tr style="opacity: {{ $opacity }}%;">
                    <td style="text-align: center;" class="mobile-hide">
                      {{substr((string)$item->id, -5, 5)}}
                    </td>
                    <td class="{{ $className }}">
                      <a
                        data-url="{{route('gardenFE.details',['id'=>$item->id, 'idCategories' => $selectCategories->id])}}"
                        title="{{ strip_tags($item->title) }}"
                        href="javascript:void(0)"
                        class="garden-show-popup"
                        data-show="{{!is_null($item->pwd_post) && !$bypassPermission ? 1 : 0}}"
                        data-id="{{!is_null($item->id) ? $item->id : 0}}"
                        data-hint="{{!is_null($item->hint) ? $item->hint : ''}}"
                      >
                        <div class="garden-title" style="text-align:left">
                          @if(request('type') > 0 )
                            {!! highlightWords2($item->detail ?? "No have details",request('keyword'),40)!!}{{' ('.$item->comments->count().')' }}
{{--                            @if(getNew($item->published,1)) <span class="icon-label">N</span>@endif--}}
{{--                            @if($item->hot_garden == '2') <span class="icon-label">H</span>@endif--}}
                          @else
                            {!! highlightWords2($item->title,request('keyword'),40) !!}{{' ('.$item->comments->count().')' }}
{{--                            @if(getNew($item->published,1)) <span class="icon-label">N</span>@endif--}}
{{--                            @if($item->hot_garden == '2') <span class="icon-label">H</span>@endif--}}

                          @endif
                        </div>
                      </a>
                    </td>
                    <td class="date mobile-hide" style="text-align: center;">
                        {{getStatusDateByDate2($item->published)}}
{{--                        {{getStatusDateByDate($item->published)}}--}}

                      {{--                    @php--}}
                      {{--                      $randomIp = mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255);--}}
                      {{--                    @endphp--}}
                      {{--                    <div>IP: {{ $randomIp }}</div>--}}
                    </td>
                    <td style="text-align: center;">
                      <div style="white-space: nowrap;">
                        <div style="display: flex;justify-content: center;">
                          <div style="margin-right: 10px">
                            <div class="d-flex align-items-center">
                              {{--                            <svg class="mr-2" width="20" height="11" aria-hidden="true"--}}
                              {{--                                 class="icon">--}}
                              {{--                              <use xmlns:xlink="http://www.w3.org/1999/xlink"--}}
                              {{--                                   xlink:href="#icon_lookup"></use>--}}
                              {{--                            </svg>--}}
                              {{--                            {{__('garden.lookup')}}--}}
                            </div>
                          </div>
                          <div>{{$item->lookup ?? 0}}</div>
                        </div>

                      </div>
                    </td>
                  </tr>
                @endif
              @else
                <tr>
                  <td colspan="4" class="text-center">{{__('garden.no_item')}}</td>
                </tr>
              @endif
            @endforeach

            </tbody>
          </table>
        </div>

      </div>
      <div id="form-search-2">
        <div class="filter filter--1 align-items-end">
          <button type="button" class="filter__item btn btn-secondary"
                  onClick="window.location.href='{{route('gardenFE.list')}}'">
            <svg width="18" height="20.781" aria-hidden="true">
              <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_menu-list"></use>
            </svg>
            <span>{{__('garden.last_list')}}</span>
          </button>
          @if($canCreate )
            <a href="{{route('gardenFE.create',['id'=>$selectCategories->id])}}" title="{{__('garden.create_garden')}}"
              class="filter__item btn btn-primary btn-reset-padding">
              <span style="white-space: nowrap;">{{__('garden.write')}}</span>
            </a>
          @endif
        </div>
      </div>
    <div  style="width:100%">
    {!! Theme::partial('paging',['paging'=>$garden->appends(request()->input()) ]) !!}
    </div>
  </div>

  </div>

  </div>
</main>

<div class="modal fade modal--confirm" id="confirmPwdPostGarden" tabindex="-1" role="dialog"
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
        <div class="d-lg-flex mx-lg-2">
          정답을 입력해야 보실 수 있습니다.
        </div>
        <div class="d-lg-flex align-items-center mx-3">
          <div class="d-lg-flex align-items-start flex-grow-1">
            <div class="form-group form-group--1 flex-grow-1 mr-lg-30 mb-3">
              <label for="hint" class="form-control">
                <input type="text" id="hint" value="" placeholder="&nbsp;"
                       maxlength="120" readonly style="cursor: unset;">
                <span class="form-control__label" style="cursor: unset;">{{__('garden.question_password')}}</span>
              </label>
            </div>
            <div class="form-group form-group--1 flex-grow-1 mb-3">
              <label for="passwordPostEdit" class="form-control form-control--hint">
                <input type="password" id="passwordPostEdit" name="pwd_post_garden" placeholder="&nbsp;"
                       value="" maxlength="16">
                <span class="form-control__label">
                <!-- 비밀번호를 입력하세요 -->
                {{__('garden.type_password')}}
                </span>
              </label>
              <span class="form-control__hint" id="msg">
              <!-- 질문 정답 입력 -->
              {{__('garden.answer_password')}}
              </span>
            </div>
          </div>
          <input type="hidden" id="url-detail" value="">
          <input type="hidden" id="id-detail" value="1">
          <div class="button-group mb-2" style="width: 168px;">
            <button type="button" class="btn btn-primary submitByPwdPostPopup">확인</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="{{ asset('js/loading.js') }}"></script>
<script>
  //document.addEventListener('contextmenu', event => event.preventDefault());
  $(function () {
    $('.banner').slick({
      dots: false,
      nextArrow:
        '<button type="button" class="slick-next"><svg class="icon" xmlns="http://www.w3.org/2000/svg" width="8" height="15" viewBox="0 0 8 15"><g><g><path fill="#fff" d="M.85 14.169a.483.483 0 0 1-.35.145.495.495 0 0 1-.35-.845l6.155-6.155L.15 1.159a.495.495 0 0 1 .7-.7l6.505 6.505a.495.495 0 0 1 0 .7z"/></g></g></svg></button>',
      prevArrow:
        '<button type="button" class="slick-prev"><svg class="icon" xmlns="http://www.w3.org/2000/svg" width="8" height="15" viewBox="0 0 8 15"><g><g><path fill="#fff" d="M6.65 14.169a.483.483 0 0 0 .35.145.495.495 0 0 0 .35-.845L1.195 7.314 7.35 1.159a.495.495 0 0 0-.7-.7L.145 6.964a.495.495 0 0 0 0 .7z"/></g></g></svg></button>',
      autoplay: true,
      autoplaySpeed: 3000,
      variableWidth: true,
    });

    $(document.body).on('click', '.garden-show-popup', function (e) {
      e.preventDefault();
      e.stopPropagation();
      let $this = $(this);
      let $id = $this.attr('data-id');
      let $url = $this.attr('data-url');
      let $is_show = $this.attr('data-show');
      let $hint = $this.attr('data-hint');

      if ($is_show == 1) {
        $('#confirmPwdPostGarden #hint').val($hint);
        $('#passwordPostEdit').val('');
        $('#msg').html('질문 정답 입력');
        $('#url-detail').val($url);
        $('#id-detail').val($id);
        $('#confirmPwdPostGarden').modal('show');
      } else {
        console.log($url);
        window.location.href = $url;
      }
    });

    $(document.body).on('click', '.submitByPwdPostPopup', function (e) {
      e.preventDefault();
      sendPassword();
    });

    $("#passwordPostEdit").keypress(function(e){
      let keycode = e.keyCode ? e.keyCode : e.which;
      if (keycode == '13'){
        sendPassword();
      }
    })
    function sendPassword(){
      if ($('[name="pwd_post_garden"]').val() == '') {
        $('#msg').html('<div style="color: #EC1469;">Password is required</div>');
        $('[name="pwd_post_garden"]').focus();
        return;
      }
      let $url = $('#url-detail').val();
      $.ajax({
        type: 'POST',
        url: '{{route('gardenFE.ajaxPasswdPost')}}',
        data: {
          _token: "{{ csrf_token() }}",
          'pwd_post': $('[name="pwd_post_garden"]').val(),
          'id': $('#id-detail').val(),
          'is_create': 0,
        },
        success: function (data) {
          if (data.check == true) {
            window.location.href = $url;
          } else {
            $('#msg').html(data.msg);
          }
        },
      });
    }
  })
</script>
