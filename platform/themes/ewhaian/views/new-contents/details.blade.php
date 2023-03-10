<main id="main-content" data-view="event-details" class="home-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        <div class="sidebar-template__control">
          <!-- category menu -->
        <div class="category-menu">
            <h4 class="category-menu__title">{{__('new_contents')}}</h4>
            <ul class="category-menu__links">
                @foreach ($categories as $item)
                <li class="category-menu__item @if($idCategory == $item->id ) active @endif">
                <a href="{{route('newContentsFE.list', ['idCategory'=>$item->id]) }}"
                    title="{{$item->name}}">{{$item->name}}</a>
                </li>
                @endforeach
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
              <h3 class="single__title title-main">{{$newContents->title}}</h3>
              <div class="single__info">
                <div class="single__date">
                  <svg width="15" height="17" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                  </svg>

                  {{Carbon\Carbon::parse( $newContents->pubish)->format('Y-m-d')}}
                </div>
                <div class="single__eye">
                  <svg width="16" height="10" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                  </svg>
                  {{ $newContents->lookup}}
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
                  <span class="item__datetime__detail">{{Carbon\Carbon::parse( $newContents->start)->format('d M | h:i a')}} -  {{ Carbon\Carbon::parse( $newContents->end)->format('d M | h:i a')}}</span>
                </p>
              </div>
              <div class="col-md-6">
                <p class="single__limit">{{__('new_contents.enrollment_limit')}}: <span>{{ $newContents->enrollment_limit}}</span> {{__('new_contents.participants')}}</p>
              </div>
            </div>

            <div class="editor">
                <img src="{{  get_image_url($newContents->banner, 'featured') }}" alt="{{$newContents->title}}">
              {!!  $newContents->content !!}
            </div>
            <div>
                {!! Theme::partial('attachments',['link'=> $newContents->link,'file_upload'=>$newContents->file_upload]) !!}
            </div>
            <!-- comments -->
                {!! Theme::partial('comments',[
                    'comments'=>$comments,
                    'countCmt'=>$newContents->comments->count(),
                    'nameDetail'=>'new_contents_id',
                    'createBy'=>$newContents->member_id,
                    'idDetail'=>$newContents->id,
                    'route'=>'newContentsFE.comments',
                    'routeDelete'=>'newContentsFE.comments.delete',
                    'showEdit'=> !is_null(auth()->guard('member')->user()) && $newContents->member_id == auth()->guard('member')->user()->id ? true :false,
                    'editItem'=> 'newContentsFE.edit',
                    'deleteItem'=> 'newContentsFE.delete'
                ]) !!}
            <!-- end of comments -->
          </div>
        </div>
      </div>
    </div>
  </main>
