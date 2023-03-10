@php
  $user = auth()->guard('member')->user();
  $bypassPermission = isset($user) ? $user->hasPermission('gardenFE.bypass_password_requirement') : 0;
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
  }

  #background {
    position: absolute;
    z-index: -1;
    background: white;
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
    opacity: 4%;
  }

  .sidebar-template__content {
    position: relative;
  }

  .form-control::placeholder {
    color: #999999;
  }

  .avoid-click {
    pointer-events: none;
  }

  .form-control--select{
    background-position: bottom 12px right;
  }
</style>

<div class="loading-section hide">
  <img class="loading-image" src="/storage/uploads/back-end/logo/logo-spinner.png"/>
</div>

<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      {!! Theme::partial('garden.menu',['categories'=>$categories, 'id'=>null, 'isGardenBookmark' => true]) !!}
      <div class="sidebar-template__content" style="z-index: 1">
        {{-- {!! Theme::partial('slides',['slides'=> $composer_SLIDES_HOME ?? null ]) !!}
        <br> --}}
        <ul class="breadcrumb">
          @foreach (Theme::breadcrumb()->getCrumbs() as $i => $crumb)
            @if ($i != (count(Theme::breadcrumb()->getCrumbs()) - 1))
              <li>
                <a href="{{ $crumb['url'] }}">{!! $crumb['label'] !!}</a>
                <svg width="4" height="6" aria-hidden="true" class="icon">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                </svg>
              </li>
            @else
              <li class="active">{!! $crumb['label'] !!}</li>
            @endif
          @endforeach
        </ul>

        <div class="heading" style="display: flex;">
          <div class="heading__title" style="white-space: nowrap;">
            {{ __('garden.bookmarks') }}
          </div>
        </div>
      <!-- end of filter -->

      @if (session('err'))
        <div class="alert alert-danger" style="display: block">
          {{ session('err') }}
        </div>
      @endif
      @if (session('success'))
        <div class="alert alert-success" style="display: block">
          {{ session('success') }}
        </div>
      @endif
      {{-- end search --}}
      <div class="content">
        <div class=" table-responsive">
          <table class="table table--content-middle">
            <thead>
            <th style="text-align: center;" class="mobile-hide">{{__('garden.id')}}</th>
            @if(request('type') > 0 )
              <th style="text-align: center;">{{__('garden.detail')}}</th>
            @else
              <th style="text-align: center;">{{__('garden.title')}}</th>
            @endif
            <th style="text-center: center;" class="mobile-hide">{{__('garden.date')}}</th>
            <th style="text-align: center;min-width: 80px">{{__('garden.lookup')}}</th>
            </thead>
            <tbody>

              @foreach ($bookmarks as $key => $bookmark)
                @php
                  $item = $bookmark->bookmarkable;
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

                  $showFormPassword = 1;
                  $isAuthor = $item->member_id == $user->id;

                  if (!is_null($item->pwd_post)){
                      if ($bypassPermission || $isAuthor){
                          $showFormPassword = 0;
                      }
                  } else {
                      $showFormPassword = 0;
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
                      data-url="{{route('gardenFE.details',['id'=>$item->id, 'idCategories' => $item->categories_gardens_id])}}"
                      title="{{ strip_tags($item->title) }}{{' ('.$item->comments->count().')' }}"
                      href="javascript:void(0)"
                      class="garden-show-popup"
                      data-show="{{ $showFormPassword }}"
                      data-id="{{!is_null($item->id) ? $item->id : 0}}"
                      data-hint="{{!is_null($item->hint) ? $item->hint : ''}}"
                    >
                      <div class="garden-title">
                        @if(request('type') > 0 )
                          {!! Theme::partial('show_title',['text'=>$item->detail ?? "No have details", 'keyword' => request('keyword') ]) !!}
                          {{' ('.$item->comments->count().')' }}
                          @if($item->hot_garden == '2') <span class="icon-label">H</span>@endif
                        @else
                          {!! Theme::partial('show_title',['text'=>$item->title , 'keyword' => request('keyword') ]) !!}
                          {{' ('.$item->comments->count().')' }}
                          @if($item->hot_garden == '2') <span class="icon-label">H</span>@endif

                        @endif
                      </div>
                    </a>
                  </td>
                  <td class="date mobile-hide" style="text-align: center;">
                    {{getStatusDateByDate2($item->published)}}
                  </td>
                  <td style="text-align: center;">
                    <div style="white-space: nowrap;">
                      <div style="display: flex;justify-content: center;">
                        <div style="margin-right: 10px">
                          <div class="d-flex align-items-center">
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
    </div>
    {!! Theme::partial('paging',['paging' => $bookmarks->appends(request()->input()) ]) !!}
  </div>
</main>
{!! Theme::partial('popup.showImage',['slides'=> $slides]) !!}

<script type="text/javascript" src="{{ asset('js/loading.js') }}"></script>
<script>
    $(document.body).on('click', '.garden-show-popup', function (e) {
      e.preventDefault();
      e.stopPropagation();
      let $this = $(this);
      let $id = $this.attr('data-id');
      let $url = $this.attr('data-url');
      let $is_show = $this.attr('data-show');
      let $hint = $this.attr('data-hint');

      if ($is_show == 1) {
        $('#hint').val($hint.substring(0, 5));
        $('#passwordPostEdit').val('');
        $('#msg').html('질문 정답 입력');
        $('#url-detail').val($url);
        $('#id-detail').val($id);
        $('#confirmPwdPost').modal('show');
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
      if ($('[name="pwd_post_edit"]').val() == '') {
        $('#msg').html('<div style="color: #EC1469;">Password is required</div>');
        $('[name="pwd_post_edit"]').focus();
        return;
      }
      let $url = $('#url-detail').val();
      $.ajax({
        type: 'POST',
        url: '{{route('gardenFE.ajaxPasswdPost')}}',
        data: {
          _token: "{{ csrf_token() }}",
          'pwd_post': $('[name="pwd_post_edit"]').val(),
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
</script>
