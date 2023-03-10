<main id="main-content" data-view="event-details" class="home-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        <div class="sidebar-template__control">
          <!-- category menu -->
        <div class="category-menu">
            <h4 class="category-menu__title">{{__('master_room')}}</h4>
            <ul class="category-menu__links">
                @foreach ($categories as $item)
                <li class="category-menu__item @if($idCategory == $item->id ) active @endif">
                <a href="{{route('masterRoomFE.list', ['idCategory'=>$item->id]) }}"
                    title="{{$item->name}}">{{$item->name}}</a>
                </li>
                @endforeach
                <li class="category-menu__item">
                    <a href="{{route('masterRoomFE.address.list')}}"
                      title="{{__('master_room.address')}}">{{__('master_room.address')}}</a>
                </li>
            </ul>
        </div>
              <!-- end of category menu -->
        </div>
        <div class="sidebar-template__content">
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

          <div class="event-details single">
            <div class="single__head">
              <h3 class="single__title title-main">{{$masterRoom->title}}</h3>
              <div class="single__info">
                <div class="single__date">
                  <svg width="15" height="17" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                  </svg>
                  {{Carbon\Carbon::parse( $masterRoom->start)->format('Y-m-d')}}
                </div>
                <div class="single__eye">
                  <svg width="16" height="10" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                  </svg>
                  {{ $masterRoom->lookup ?? 0}}
                </div>
              </div>
            </div>
            <div class="row no-gutters justify-content-between">
              <div class="col-md-6">
                <p class="single__datetime">
                  <span class="single__icon">
                    <svg width="10" height="10" aria-hidden="true" class="icon">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_datetime"></use>
                    </svg>
                  </span>
                  <span class="item__datetime__detail">{{Carbon\Carbon::parse( $masterRoom->start)->format('d M | h:i a')}} -  {{ Carbon\Carbon::parse( $masterRoom->end)->format('d M | h:i a')}}</span>
                </p>
              </div>
              <div class="col-md-6">
                <p class="single__limit">{{__('master_room.enrollment_limit')}} : <span>{{ $masterRoom->enrollment_limit}}</span> {{__('master_room.participants')}}</p>
              </div>
            </div>

            <div class="editor">
                @if(!is_null($masterRoom->banner))
                    <img src="{{  get_image_url($masterRoom->banner, 'featured') }}" alt="{{$masterRoom->title}}">
                @endif
              {!!  $masterRoom->content !!}
            </div>
            <div>
                {!! Theme::partial('attachments',['link'=> $masterRoom->link,'file_upload'=>$masterRoom->file_upload]) !!}
            </div>

            <!-- comments -->
                {!! Theme::partial('master_room_comments',[
                    'comments'=>$comments,
                    'countCmt'=>$masterRoom->comments->count(),
                    'nameDetail'=>'master_rooms_id',
                    'createBy'=>$masterRoom->member_id,
                    'idDetail'=>$masterRoom->id,
                    'route'=>'masterRoomFE.comments.create',
                    'routeDelete'=>'masterRoomFE.comments.delete',
                    'showEdit'=> !is_null(auth()->guard('member')->user()) && $masterRoom->member_id == auth()->guard('member')->user()->id ? true :false,
                    'editItem'=> 'masterRoomFE.edit',
                    'deleteItem'=> 'masterRoomFE.delete',
                    'canEdit' => $canEdit ,
                    'canDelete' => $canDelete,
                    'canCreateComment' => $canCreateComment,
                    'canDeleteComment' => $canDeleteComment,
                    'canViewComment' => $canViewComment,
                     'top_comments' => $top_comments,
                     'masterRoomTitle' => $masterRoom->title,
                     'masterRoomContent' => $masterRoom->content,
                     'idCategory' => $idCategory,
                     'isMasterRoomReply' => true,
                ]) !!}

              {!! Theme::partial('master_room.index_sub',[
                'masterRoom' => $subList['masterRoom'],
                'categories' => $subList['categories'],
                'idCategory' => $subList['idCategory']
              ]) !!}

            <!-- end of comments -->
          </div>
        </div>
      </div>
    </div>
  </main>
