<style>
.item__new {
    position: absolute;
    top: 0;
    right: 0;
    font-size: 0.85714em;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    padding: 0.83333em 1.25em;
}
.item__rectangle {
    position: absolute;
    top: -59px;
    right: -60px;
    width: 92px;
    height: 92px;
    font-size: 0.85714em;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    padding: 0.83333em 1.25em;
    background: #EC1469;
    transform: rotate(45deg);
}
.item__rectangle2 {
    position: absolute;
    top: -59px;
    right: -62px;
    width: 120px;
    height: 120px;
    font-size: 0.85714em;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    padding: 0.83333em 1.25em;
    background: #EC1469;
    transform: rotate(45deg);
}
.icon-label {
  width: 2em;
  height: 2em;
  display: inline-block;
  border: 1px solid #EC1469;
  border-radius: 50%;
  color: #EC1469;
  text-align: center;
  line-height: 2em;
  font-weight: 700;
  font-size: 0.61429em;
}

.item--custom{
    overflow: hidden;
    position: relative;
}
.icon-label-new {
    width: 2em;
    height: 2em;
    display: inline-block;
    border: 1px solid #EC1469;
    border-radius: 50%;
    color: #EC1469;
    text-align: center;
    line-height: 2em;
    font-weight: 700;
    font-size: 0.71429em;
}
.flare_market_list > .d-flex{
    align-items: center;
    justify-content: center;
}

</style>
<main id="main-content" data-view="advertisement" data-page="life" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            {!! Theme::partial('life.menu',['active'=>"shelter"]) !!}
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    <li><a href="" title="Event">{{__('life')}}</a></li>
                    <li>
                        <svg width="4" height="6" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                    </li>
                    <li>{{__('life.shelter_info')}}</li>
                </ul>
                <div class="heading">
                    <div class="heading__title">
                        {{__('life.shelter_info')}}
                    </div>
                    <div class="heading__description">
                        {!!$description->description ?? ""!!}
                    </div>
                </div>
                {!! Theme::partial('switch_menu',['style' => $style, 'hidden' => 3]) !!}
                {!! Theme::partial('life.elements.searchNoParent',[
                    'categories'=> $categories,
                    'route'=>"shelter.search",
                    'have_like' => 1,
                ]) !!}

              @if(count($notices) > 0 )
                @foreach ($notices as $notice)
                  <div class="notice-alert">
                    <div class="notice-alert__title" style="white-space: nowrap;">{{__('life.advertisements.notice')}}</div>
                    <div class="notice-alert__description">
                      <a href="{{route('life.shelter.notice.detail',['id'=>$notice->id])}}">{!!$notice->name!!}</a>
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
                    <div class="alert alert-success" style="display: block">
                        {{ session('success') }}
                    </div>
                @endif
                @switch($style)
                    @case(0)
                    <div class="content">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <th style="">{{__('life.shelter_info.classification')}}</th>
                                    <th width="45%" style="text-align: center;padding-left: 0px;">@if(request('type') == 1) {{__('life.shelter_info.detail')}} @else {{__('life.shelter_info.title')}} @endif</th>
                                    <th style="text-align: center;">{{__('life.shelter_info.writer')}}</th>
                                    <th style="text-align: center;">{{__('life.shelter_info.date')}}</th>
                                    <th style="text-align: center;">{{__('life.shelter_info.lookup')}}</th>
                                </thead>
                                <tbody>
                                    @if(count($shelter) > 0 )
                                    @foreach ($shelter as $item)
                                    <tr @if($item->status == "pending") class="disable"  @endif style="opacity: {{100 - ($item->dislikes_count ?? 0) *10 }}%;">
                                        <td style="width: 13%; padding: 0; padding-top: 12px">
                                            <div > {!! Theme::partial('classification',['categories'=>[$item->categories],'type'=>4 ]) !!} </div>
                                        </td>
                                        <td style="text-align: center">
                                            <a href="{{route('life.shelter_list_details',['id'=>$item->id])}}" title="{!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                                @if(request('type') > 0 )
                                                    {!! highlightWords2($item->detail ?? "No have details",request('keyword'), 50) !!}{{' ('.$item->comments->count().')' }}
                                                    @if(getNew($item->published)) <span class="icon-label-new">N</span>@endif
                                                @else
                                                <div>

                                                    @if ( $item->getNameCategories($item->categories)->name !== '용달' )
                                                        <div>
                                                           <small> {!! highlightWords2($item->location,request('keyword'), 50) !!}</small>
                                                        </div>
                                                        <div>
                                                          {{ highlightWords2($item->title,request('title'), 50) }}
                                                          {!! highlightWords2($item->deposit,request('keyword')) !!}
                                                            {!! highlightWords2($item->monthly_rent,request('keyword')) !!}
                                                            {!! highlightWords2($item->size,request('keyword')) !!}
                                                            {{' ('.$item->comments->count().')' }}
                                                            @if(getNew($item->published)) <span class="icon-label-new">N</span>@endif
                                                        </div>
                                                    @else
                                                        {{ highlightWords2($item->title,request('title'), 50) }}
                                                        @if(getNew($item->published)) <span class="icon-label-new">N</span>@endif
                                                    @endif
                                                </div>
                                                @endif
                                            </a>
                                        </td>

                                        <td style="text-align: center; display: flex; justify-content: left; align-items: center">
                                            <div>
                                                @if($item->member_id ==null || $item->getNameMemberById($item->member_id)
                                                =="Anonymous" )
                                                    {!! getStatusWriter('real_name_certification')  !!}
                                                @else
                                                    {!! getStatusWriter($item->getStatusMember($item->member_id))  !!}
                                                @endif
                                                {{$item->getNameMemberById($item->member_id)}}
                                            </div>
                                        </td>
                                        <td  style="text-align: center">
                                               @if( getStatusDateByDate($item->published) == "Today" )  <span class="icon-label-new">N</span> @else {{getStatusDateByDate($item->published)}}  @endif
                                        </td>
                                        <td style="text-align: center">{{$item->lookup ?? 0}}</td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                        <td colspan="5" style="text-align: center"> {{__('life.shelter_info.no_have_shelter')}}</td>
                                </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>

                    </div>
                    @break
                    @case(1)
                    <div class="content">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <th style="text-align: center;" width="110px">{{__('life.shelter_info.images')}}</th>
                                    <th style="">{{__('life.shelter_info.classification')}}</th>
                                    <th  style="text-align: center;">@if(request('type') == 1) {{__('life.shelter_info.detail')}} @else {{__('life.shelter_info.title')}} @endif</th>
                                    <th style="text-align: center;width: 15%;">{{__('life.shelter_info.writer')}}</th>
                                    <th style="text-align: center;width: 10%;">{{__('life.shelter_info.date')}}</th>
                                    <th style="text-align: center;width: 7%;">{{__('life.shelter_info.lookup')}}</th>
                                </thead>
                                <tbody>
                                    @if(count($shelter) > 0 )
                                    @foreach ($shelter as $item)
                                    <tr @if($item->status == "pending") class="disable"  @endif style="opacity: {{100 - ($item->dislikes_count ?? 0) *10 }}%;">
                                        <td style="text-align: center;">
                                            <div class="item__image">
                                                @if(getNew($item->published))
                                                    <div class="item__new">
                                                        <div class="item__rectangle" style="z-index: 1">

                                                        </div>
                                                        <div class="item__eye" style="z-index: 2;margin-right: -17px;margin-top: -7px">
                                                        <span class="icon-label" style="color: white;; border: 1px solid white;">N</span>
                                                        </div>
                                                    </div>
                                                @endif
                                                <a href="{{route('life.shelter_list_details',['id'=>$item->id])}}" title="{!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                                    <div class="table__image @if($item->status != 'publish') table__image--overlay d-flex align-items-center @endif"
                                                        style="background-image: url('{{geFirsttImageInArray($item->images,'featured')}}')"
                                                        width="150">
                                                    </div>
                                                </a>
                                            </div>
                                        </td>
                                        <td style="width: 13%;vertical-align: middle; padding: 0; padding-top: 12px; text-align: center;">
                                            <div > {!! Theme::partial('classification',['categories'=>[$item->categories],'type'=>4 ]) !!} </div>
                                        </td>
                                        <td style="vertical-align: middle; text-align: center;">
                                            <a style="display: block" href="{{route('life.shelter_list_details',['id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                                <div>

                                                    @if ( $item->getNameCategories($item->categories)->name !== '용달' )
                                                        <div>
                                                            <small> {!! highlightWords2($item->location,request('keyword'), 50) !!}</small>
                                                        </div>
                                                        <div>
                                                            {{ highlightWords2($item->title,request('title'), 50) }}
                                                            {!! highlightWords2($item->deposit,request('keyword')) !!}
                                                            {!! highlightWords2($item->monthly_rent,request('keyword')) !!}
                                                            {!! highlightWords2($item->size,request('keyword')) !!}
                                                            {{' ('.$item->comments->count().')' }}
                                                            @if(getNew($item->published)) <span class="icon-label-new">N</span>@endif
                                                        </div>
                                                    @else
                                                        {{ highlightWords2($item->title,request('title'), 50) }}
                                                        @if(getNew($item->published)) <span class="icon-label-new">N</span>@endif
                                                    @endif
                                                </div>
                                            </a>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle">
                                            <div>
                                                @if($item->member_id ==null || $item->getNameMemberById($item->member_id)
                                                =="Anonymous" )
                                                    {!! getStatusWriter('real_name_certification')  !!}
                                                @else
                                                    {!! getStatusWriter($item->getStatusMember($item->member_id))  !!}
                                                @endif
                                                {{$item->getNameMemberById($item->member_id)}}
                                            </div>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle">{{getStatusDateByDate($item->published)}}</td>
                                        <td style="text-align: center; vertical-align: middle">{{ $item->lookup ?? 0}}</td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                        <td colspan="5" style="text-align: center"> {{__('life.shelter_info.no_have_shelter')}}</td>
                                </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>

                    </div>
                    @break
                    @default
                    <div class="content event-list">
                        <div class="row mb-10" @if(count($shelter) <= 0 ) style="justify-content: center;"  @endif>
                            @if(count($shelter) > 0 )
                                @foreach ($shelter as $item)
                                <div class="col-lg-4 col-md-6" style="opacity: {{100 - ($item->dislikes_count ?? 0) *10 }}%;">
                                        <div class="item item--custom">
                                            <a  href="{{route('life.shelter_list_details',['id'=>$item->id])}}" title="{{$item->title}}" style="display:block">
                                            @if(getNew($item->published))
                                                <div class="item__new">
                                                    <div class="item__rectangle2" style="z-index: 1">

                                                    </div>
                                                    <div class="item__eye" style="z-index: 2;margin-right: -9px;">
                                                    <span class="icon-label" style="border: 1px solid white; color: white">N</span>
                                                        <div style="margin-left: -4px;">New</div>
                                                    </div>
                                                </div>
                                            @endif
                                                <div class="item__image mb-10" style="background: url('{{ geFirsttImageInArray($item->images, 'event-thumb')  }}') no-repeat center; background-size:contain; height: 180px;">
                                                </div>
                                            </a>
                                            <div class="item__tag d-flex align-items-center justify-content-between mb-10 pr-1">
                                            <div style=""> {!! Theme::partial('classification',['categories'=>[$item->categories],'type'=>4 ]) !!} </div>
                                                <div class="text-right">
                                                    <div style="display: flex">
                                                        @if($item->member_id ==null || $item->getNameMemberById($item->member_id)
                                                        =="Anonymous" )
                                                            {!! getStatusWriter('real_name_certification')  !!}
                                                        @else
                                                            {!! getStatusWriter($item->getStatusMember($item->member_id))  !!}
                                                        @endif
                                                        {{$item->getNameMemberById($item->member_id)}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item__caption mb-10 pr-1">
                                                <h4 class="item__title">
                                                    <a href="{{route('life.shelter_list_details',['id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                                        <div style="white-space: nowrap; display: flex;">
                                                            <div  style=" overflow: hidden; text-overflow:ellipsis;">
                                                                @if(request('type') > 0 )
                                                                    {!! highlightWords2($item->detail ?? "No have details",request('keyword')) !!}
                                                                @else
                                                                <div>
                                                                    <div>
                                                                       <small> {!! highlightWords2($item->location,request('keyword')) !!}</small>
                                                                    </div>
                                                                    <div>
                                                                        {{ highlightWords2($item->title,request('title'), 20) }}
                                                                        {!! highlightWords2($item->deposit,request('keyword')) !!}
                                                                        {!! highlightWords2($item->monthly_rent,request('keyword')) !!}
                                                                        {!! highlightWords2($item->size,request('keyword')) !!}
                                                                        {{' ('.$item->comments->count().')' }}
                                                                        @if(getNew($item->published)) <span class="icon-label-new">N</span>@endif
                                                                    </div>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div class="item__addon d-flex justify-content-between mb-10 pr-1">
                                                <div class="pr-2">
                                                    <span class="item__text-gray">{{__('life.shelter_info.date')}} |</span>
                                                    @if( getStatusDateByDate($item->published) == "Today" )  <span class="icon-label-new">N</span> @endif {{getStatusDateByDate($item->published)}}
                                                </div>
                                                <div class="pl-2">
                                                    <span class="item__text-gray">{{__('life.shelter_info.lookup')}} |</span>
                                                    {{$item->lookup ?? 0}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                            <div > {{__('life.shelter_info.no_have_shelter')}}</div>
                            @endif
                        </div>
                    </div>
                @break

                @endswitch
                <div id="form-search-2">
                <div class="filter filter--1 align-items-end">
                    <button class="filter__item btn btn-secondary"  onClick="window.location.href='{{route('life.shelter_list')}}'">
                        <svg width="18" height="20.781" aria-hidden="true">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_menu-list"></use>
                        </svg>
                        <span>{{__('life.shelter_info.last_list')}}</span>
                    </button>

                    @if($canCreate)
                    <a href="{{route('shelterFE.create')}}" title="{{__('life.shelter_info.enrollment')}}" class="filter__item btn btn-primary btn3">
                        <span style="white-space: nowrap;">{{__('life.shelter_info.write')}}</span>
                    </a>
                    @endif
                </div>
                </div>
                {!! Theme::partial('paging',['paging'=>$shelter->appends(request()->input()) ]) !!}
            </div>

        </div>

    </div>
</main>
