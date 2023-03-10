<main id="main-content" data-view="event-details" class="home-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        <div class="sidebar-template__control">
          <!-- category menu -->
         <!-- category menu -->
         <div class="category-menu">
            <h4 class="category-menu__title">{{__('contents')}}</h4>
            <ul class="category-menu__links">
              @foreach ($category as $item)
              <li class="category-menu__item @if($idCategory == $item->id ) active @endif">
                <a href="{{route('contents.contents_list', ['idCategory'=>$item->id]) }}" title="$item->name"  >{{$item->name}}</a>
              </li>
              @endforeach
            </ul>
          </div>
          <!-- end of category menu -->
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
              <h3 class="single__title title-main">{{$contents->title}}</h3>
              <div class="single__info">
                <div class="single__date">
                  <svg width="15" height="17" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date"></use>
                  </svg>
                  {{Carbon\Carbon::parse( $contents->published)->format('Y-m-d')}}
                </div>
                <div class="single__eye">
                  <svg width="16" height="10" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                  </svg>
                  {{ $contents->lookup}}
                </div>
                <div class="single__eye">

                    {!! Theme::partial('report' , [
                        'type_report'=> '1',
                        'type_post'=> '3',
                        'id_post'=> $contents->id,
                        'object' => $contents
                    ]) !!}

            </div>
              </div>
            </div>


            @if (session('msg'))
            <div class="alert alert-success" role="alert">
                {{ session('msg') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
            </div>
            @endif
            <div class="clearfix" style="padding-bottom: 40px;"></div>
            <hr>
            <div class="editor">
                {{-- <img src="{{  get_image_url($contents->banner, 'featured') }}" alt="{{$contents->title}}"> --}}
                @php
                    $contentBanner = substr($contents->banner,0,1);
                    $banner = $contents->banner;
                    if ($contentBanner != '/'){
                        $banner = '/' . $banner;
                    }
                @endphp
                <img src="{{ $banner }}" alt="{{$contents->title}}" style="width: 850px">
              {!!  $contents->content !!}
            </div>
            {{-- {!! Theme::partial('life.dislike', [
              'item' => $contents,
              'route' => route('contentsFE.dislike',['id'=>$contents->id]),
              'route_like' => route('contentsFE.like',['id'=>$contents->id]),
              'route_sympathy_permission_on_post' => route('contentsFE.checkSympathyPermissionOnPost',['id'=>$contents->id]),
            ]) !!} --}}
            <div>
                {!! Theme::partial('attachments',['link'=> $contents->link,'file_upload'=>$contents->file_upload]) !!}
            </div>

            <hr>
            @if($canEdit ?? false || $canDelete ?? false)
            <div class="post_action d-flex">
              {!! Theme::partial('life.post_action', [
                'idDetail'=>$contents->id,
                'editItem'=> 'contentsFE.edit',
                'deleteItem'=> 'contentsFE.delete',
                'canEdit' => $canEdit,
                'canDelete' => $canDelete,
            ]) !!}
            </div>
            @endif

            <div class="like_group mt-5">
            {!! Theme::partial('life.dislike', [
              'item' => $contents,
              'route' => route('contentsFE.dislike',['id'=>$contents->id]),
              'route_like' => route('contentsFE.like',['id'=>$contents->id]),
              'route_sympathy_permission_on_post' => route('contentsFE.checkSympathyPermissionOnPost',['id'=>$contents->id]),
            ]) !!}
            </div>

            @if($contents->active_empathy > 0 )
            <!-- comments -->
                {!! Theme::partial('comments',[
                    'comments'=>$comments,
                    'countCmt'=>$contents->comments->count(),
                    'nameDetail'=>'contents_id',
                    'createBy'=>$contents->member_id,
                    'idDetail'=>$contents->id,
                    'route'=>'contents.comment.create',
                    'routeDelete'=>'contents.comment.delete',
                    'showEdit'=> !is_null(auth()->guard('member')->user()) && $contents->member_id == auth()->guard('member')->user()->id ? true :false,
                    'editItem'=> 'contentsFE.edit',
                    'deleteItem'=> 'contentsFE.delete',
                    'type_post'=> '3',
                    'canEdit' => $canEdit,
                    'canDelete' => $canDelete ,
                    'canCreateComment' => $canCreateComment,
                    'canDeleteComment' => $canDeleteComment,
                    'canViewComment' => $canViewComment,
                    'canViewCommenter' => $canViewCommenter,
                    'route_like' => route('contentsFE.comment.like'),
                    'route_dislike' => route('contentsFE.comment.dislike'),
                    'route_sympathy_permission_on_comment' => route('contentsFE.checkSympathyPermissionOnComment'),
                    'top_comments' => $top_comments
                ]) !!}
            <!-- end of comments -->

            @endif

            {!! Theme::partial('contents.index_sub',[
              'contents' => $subList['contents'],
              'categories' => $subList['categories'],
              'idCategory' => $subList['idCategory'],
              'selectCategories' => $subList['selectCategories'],
              'style' => $subList['style'],
              'canCreate' => $subList['canCreate']
            ]) !!}
          </div>
        </div>
      </div>
    </div>
  </main>
  {!! Theme::partial('life.modal-dislike', [
      'item' => $contents,
  ]) !!}
{!! Theme::partial('modal-report' , [
  'type_report'=> '1',
  'type_post'=> '3',
  'id_post'=> $contents->id,
  'object' => $contents
]) !!}
