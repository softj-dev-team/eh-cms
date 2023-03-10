<style>
  body {
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none
  }

  .syn {
    width: 176px;
    margin: auto !important;
    height: 50px;
    font-size: 20px;
    display: flex !important;
    justify-content: center;
    align-items: center;
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

    .syn {
      margin: 0;
    }
  }

</style>

@if ($room->statusMember(auth()->guard('member')->user()->id) == 'publish' || $room->member_id == auth()->guard('member')->user()->id )
  <main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        {!! Theme::partial('garden.menu',['categories'=>$categories,'id'=>0,'egarden'=>1]) !!}
        <div class="sidebar-template__content">
          <div class="heading">
            <ul class="breadcrumb">
              <li>
                <a href="{{route('egardenFE.room.list')}}">{{__('egarden.room')}}</a>
                <svg width="4" height="6" aria-hidden="true" class="icon">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                </svg>
              </li>
              <li class="active">{{$room->name}}</li>
            </ul>
          </div>
        {{-- Search --}}
        <!-- filter -->
          <div>
            {!! Theme::partial('garden.elements.detailRoom',[
                'room' => $room,
                'listUserCanGetOwnership' => $listUserCanGetOwnership
            ]) !!}
          </div>
          <!-- end of filter -->
          {{-- end search --}}
          {!! Theme::partial('garden.elements.order',[
              'room' => $room,
              'canSearch' => true,
              'canOrder' => false,
              'route' => route('egarden.search',['id' => $room->id]),
              'labelOrderby1' => 'Views',
              'labelOrderby2' => 'Comments',
              'labelOrderby3' => 'Likes'
          ]) !!}
          <div class="notice-alert" style=" margin-bottom: 0.14286em;">
            <div class="notice-alert__title" style="white-space: nowrap;">{{__('egarden.notice')}}</div>
            @if($notices->count() > 0 )
              <div class="notice-alert__description">
                @foreach ($notices as $item)
                  <div>
                    <a href="{{$item->link}}">{!!$item->notices !!}</a>
                  </div>
                @endforeach
              </div>
            @else
              <div class="notice-alert__description">
                {!!__('egarden.no_have_notices')!!}
              </div>
            @endif
          </div>
          @if (session('err'))
            <div class="alert alert-danger" style="display: block">
              {{ session('err') }}
            </div>
          @endif
          @if (session('permission'))
            <div class="alert alert-danger">
              <p>{{ session('permission') }}</p>
            </div>
          @endif
          @if (session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif
          <div class="content">
            <div class=" table-responsive">
              <table class="table table--content-middle">
                <thead>
                <th style="text-align: center; width: 120px;">말머리 설정 제목</th>
                <th style="text-align: center;">@if(request('type') > 0 )
                    {{__('egarden.detail')}}
                  @else
                    {{__('egarden.title')}}
                  @endif

                </th>
                <th style="text-align: center;">{{__('egarden.date')}}</th>
                <th style="text-align: center;">{{__('egarden.lookup')}}</th>
                </thead>
                <tbody>

                @if(count( $egarden ) )

                  @foreach ($egarden as $item)
                    <tr>
                      <td style="text-align: center;">
                        {!! Theme::partial('garden.elements.showCategories',['item' => $item->categoriesRoom]) !!}
                      </td>
                      <td>
                        <a href="{{route('egardenFE.details',['id'=>$item->id])}}"
                           title="{{ strip_tags($item->title) }}{{' ('.$item->comments->count().')' }}">
                          <div class="garden-title">
                            @if(request('type') > 0 )
                              {!! Theme::partial('show_title',['text'=>$item->detail ?? "No have details", 'keyword' => request('keyword') ]) !!}
                              {{' ('.$item->comments->count().')' }}
                            @else
                              {!! Theme::partial('show_title',['text'=>$item->title , 'keyword' => request('keyword') ]) !!}
                              {{' ('.$item->comments->count().')' }}

                            @endif
                          </div>
                        </a>
                      </td>
                      <td class=" date" style="text-align: center;">
                        {{getStatusDateByDate($item->published)}}
                        @if( getStatusDateByDate($item->published) == "Today" )
                          <span class="icon-label">N</span>
                        @endif
                      </td>
                      <td style="text-align: center;">
                        {{$item->lookup ?? 0}}
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="4" class="text-center">{{__('egarden.no_item')}}</td>
                  </tr>
                @endif
                </tbody>
              </table>
            </div>

          </div>
          <div class="filter filter--1 align-items-end">
            <div>
              <button type="button" class="filter__item btn btn-secondary"
                      onClick="window.location.href = window.location.href;">
                <span>최신목록</span>
              </button>
              <button type="button" class="filter__item btn btn-secondary"
                      onClick="window.location.href='{{route('egardenFE.home')}}'">
                <span>E-화원 홈</span>
              </button>
            </div>
            <div>
              {!! Theme::partial('paging',['paging'=>$egarden->appends(request()->input()) ]) !!}
            </div>

            @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('egardenFE.create') )
              <a href="{{route('egardenFE.create',['id'=>$room->id])}}" title="{{__('egarden.create_new_egarden')}}"
                 class="filter__item btn btn-primary mx-3 btn-reset-padding">
                <span style="white-space: nowrap;">{{__('egarden.write')}}</span>
              </a>
            @endif
          </div>


        </div>

      </div>

    </div>
  </main>
@else
  <main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        {!! Theme::partial('garden.menu',['categories'=>$categories,'id'=>0,'egarden'=>1,'my_room'=> ($room->member_id == auth()->guard('member')->user()->id ?  true : false )]) !!}
        <div class="sidebar-template__content">
          <div class="heading">
            <ul class="breadcrumb">
              <li>
                <a href="{{route('egardenFE.room.list')}}">{{{__('egarden.room')}}}</a>
                <svg width="4" height="6" aria-hidden="true" class="icon">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                </svg>
              </li>
              <li class="active">{{$room->name}}</li>
            </ul>
            <h3 class="title-main">{{$room->description}}</h3>
            <br>
          </div>
        {{-- Search --}}
        <!-- filter -->
          @if (session('permission'))
            <br>
            <div class="alert alert-danger" style="width: 100%">
              {{ session('permission') }}
            </div>
          @endif
          <div class="content">
            <div class="editor row justify-content-md-center ">
              <div class="col-md-7 slick_banner">
                @if ($room->images !== null)
                  <div class="block-img " style="width: 530px;height: 340px;">
                    <div class="img-bg"
                         style="width: 100%;height: 100%; background-image:url('{{  get_image_url($room->images, 'featured') }}"
                         alt="{{$room->title}}')">
                      <img src="{{  get_image_url($room->images, 'featured') }}" alt="{{$room->title}}">
                    </div>
                  </div>
                @endif
              </div>
            </div>
            <br>
            <div>
              {!! Theme::partial('attachments',['link'=> $room->link,'file_upload'=>$room->file_upload]) !!}
            </div>
            <div class=" table-responsive">
              {!!
                  Theme::partial('garden.elements.joinRoom',['status'=>$room->statusMember(auth()->guard('member')->user()->id),'id'=>$room->id])
              !!}
            </div>

          </div>
        </div>
        <!-- end of filter -->
        {{-- end search --}}

      </div>
    </div>
  </main>
@endif


<script>
  $(document).ready(function () {
    $(document.body).on('click', '.syn', function (e) {
      e.preventDefault();
      var $this = $(this);
      var idRoom = $this.attr('idRoom');

      if ($this.data('isRunAjax') == true) {
        return;
      }
      $this.css('pointer-events', 'none').data('isRunAjax', true);
      $.ajax({
        type: 'POST',
        url: '{{route('egardenFE.ajaxJoinRoom')}}',
        data: {
          _token: "{{ csrf_token() }}",
          'id': idRoom
        },
        success: function (data) {
          switch (data.status) {
            case 'pending':
              $this.replaceWith(`<a class='alert bg-green-1 syn' href='javascript:void(0)' idRoom="${idRoom}">{{__('egarden.room.pending')}}</a>`);
              break;
            case 'draft':
              $this.replaceWith(`<a class='alert bg-main syn' href='javascript:void(0)' idRoom="${idRoom}" >{{__('egarden.room.join_us')}}</a>`);
              break;
            default:
              break;
          }
        }
      }).always(function () {
        $this.css('pointer-events', 'auto').data('isRunAjax', false);
      });
    });
  });
</script>
