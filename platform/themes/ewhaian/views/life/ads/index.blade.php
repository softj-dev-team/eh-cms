<style>
.table tr.disable {
    opacity: 0.4;
}
.table--content-middle .alert {
    padding: 5px 0.33333em;
}
svg {
    margin-bottom: 3px;
}
</style>
<main id="main-content" data-view="advertisement" data-page="life" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            {!! Theme::partial('life.menu',['active'=>"advertisements"]) !!}
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    <li><a href="" title="Event">{{__('life')}}</a></li>
                    <li>
                        <svg width="4" height="6" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                    </li>
                    <li>{{__('life.advertisements')}}</li>
                </ul>
                <div class="heading">
                    <div class="heading__title">
                        {{__('life.advertisements')}}
                    </div>
                    <div class="heading__description">
                        {!!$description->description ?? ""!!}
                    </div>
                </div>
                {!! Theme::partial('life.elements.group',['ads'=>true]) !!}
                {!! Theme::partial('life.elements.searchNoParent',[
                    'categories'=> $categories,
                    'route'=>"ads.search",
                    'have_like' => 1,
                ]) !!}

              @if(count($notices) > 0 )
                @foreach ($notices as $notice)
                  <div class="notice-alert">
                    <div class="notice-alert__title" style="white-space: nowrap;">{{__('life.advertisements.notice')}}</div>
                    <div class="notice-alert__description">
                      <a href="{{route('life.advertisements.notices.detail',['id'=>$notice->id])}}">{!!$notice->name!!}</a>
                    </div>
                  </div>

                @endforeach
              @else
                <div class="notice-alert">
                  <div class="notice-alert__title" style="white-space: nowrap;">{{__('life.advertisements.notice')}}</div>
                  <div class="notice-alert__description">
                    <span> {{__('life.advertisements.no_have_notices')}}</span>
                  </div>
                </div>
              @endif

                @if (session('err'))
                    <div class="alert alert-danger" style="display: block">
                        {{ session('err') }}
                    </div>
                @endif
                @if (session('success'))
                <div class="alert alert-success"  style="display: block">
                    {{ session('success') }}
                </div>
                @endif
                <div class="content">
                    <div class=" table-responsive">
                        <table class="table table--content-middle">
                            <thead>
                                @if($style > 0)<th style="text-align: center; width:148px">{{__('life.advertisements.images')}}</th> @endif
                                <th style="text-align: center;padding-left: 0px;">{{__('life.advertisements.classification')}}</th>
                                <th style="text-align: center;padding-left: 0px;">{{__('life.advertisements.title')}}</th>
                                <th style="text-align: center;padding-left: 0px;width: 13%;">{{__('life.advertisements.writer')}}</th>
                                @if($style == 0)  <th style="text-align: center;padding-left: 0px;width: 13%;">{{__('life.advertisements.date')}}</th> @endif
                                <th style="text-align: center;padding-left: 0px;width: 13%;">{{__('life.advertisements.lookup')}}</th>
                            </thead>
                            <tbody>
                                @if(count($ads) > 0 )
                                @foreach ($ads as $item)
                                <tr @if ( $item->is_deadline >0 && getDeadlineDate( $item->deadline ) == "Expired")  class="disable" @endif>
                                    @if($style > 0)
                                    <td>
                                        <div class="item__image">
                                            @if(getNew($item->published))
                                                <div class="item__new">
                                                    <div class="item__rectangle" style="z-index: 1">

                                                    </div>
                                                    <div class="item__eye" style="z-index: 2;margin-right: -10px;margin-top: -10px">
                                                    <span class="icon-label-image">N</span>
                                                    </div>
                                                </div>
                                            @endif

                                            <a href="{{route('life.advertisements_details',['id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                            <div class="table__image @if($item->status != 'publish') table__image--overlay d-flex align-items-center @endif"
                                                style="background-image: url('{{ geFirsttImageInArray([$item->images],'thumb')}}')"
                                                width="150">
                                            </div>
                                            </a>
                                        </div>
                                    </td>
                                    @endif
                                    <td style="width: 13%">
                                        {!! Theme::partial('classification',['categories'=>[$item->categories],'type'=>3 ]) !!}
                                    </td>
                                    <td> <a href="{{route('life.advertisements_details',['id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                            @if(request('type') > 0 )
                                                {!! Theme::partial('show_title',['text'=>$item->detail ?? "No have details", 'keyword' => request('keyword') ]) !!}
                                                {{' ('.$item->comments->count().')' }}
                                                @if(getNew($item->published)) <span class="icon-label">N</span>@endif
                                            @else
                                                {!! Theme::partial('show_title',['text'=>$item->title , 'keyword' => request('keyword') ]) !!}
                                                {{' ('.$item->comments->count().')' }}
                                                @if(getNew($item->published)) <span class="icon-label">N</span>@endif

                                            @endif
                                        </a>
                                    </td>
                                    <td  >
                                        <div style="display: flex">
                                            @if($item->member_id ==null || $item->getNameMemberById($item->member_id)
                                            =="Anonymous" )
                                                {!! getStatusWriter('real_name_certification')  !!}
                                            @else
                                                {!! getStatusWriter($item->getStatusMember($item->member_id))  !!}
                                            @endif
                                            {{$item->getNameMemberById($item->member_id)}}
                                        </div>
                                    </td>
                                    @if($style == 0)
                                    <td style="text-align: center" >
                                        {{ \Carbon\Carbon::parse($item->deadline)->format('Y/m/d')}}
                                    </td>
                                    @endif
                                    <td style="text-align: center">{{$item->lookup ?? 0}}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6" style="text-align: center">{{__('life.advertisements.no_advertisements')}}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
                <div  method="GET" id="form-search-2">
                        <div class="filter filter--1 align-items-end">
                                <button  type="button" class="filter__item btn btn-secondary"   onClick="window.location.href='{{route('life.advertisements_list')}}'">
                                <svg width="18" height="20.781" aria-hidden="true">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_menu-list"></use>
                                </svg>
                                <span>{{__('life.advertisements.last_list')}}</span>
                            </button>

                            @if($canCreate )
                            <div>
                            <a href="{{route('adsFE.create')}}" title="{{__('life.advertisements.write')}}" class="filter__item btn btn-primary mx-3 btn3">
                                <span style="white-space: nowrap;">{{__('life.advertisements.write')}}</span>
                            </a>
                            </div>
                            @endif
                            <input type="hidden" name="parentCategories" id="parentCategories3" value="{{request('parentCategories') }}">
                            <input type="hidden" name="childCategories" id="childCategories3" value="{{request('childCategories')}}">
                            <input type="submit" title="Enrollment" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"
                            style="display:none">
                        </div>
                    </div>
                {!! Theme::partial('paging',['paging'=>$ads->appends(request()->input()) ]) !!}
            </div>

        </div>

    </div>
</main>
