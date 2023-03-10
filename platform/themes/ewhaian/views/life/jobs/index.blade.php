<style>
.table tr.disable {
    opacity: 0.4;
}

.detail_map th, .detail_map td{
        border: none !important;
        padding-top: 0px !important;
    }
.detail_map {
    margin-top: 10px;
}
.table td {
    padding: .75rem;
}
/* .table--content-middle .alert:not(:nth-of-type(1)) {
    margin-top: 0 !important;
} */
.icon-label{
    margin-right: 1.2em !important;
}
</style>
<main id="main-content" data-view="advertisement" data-page="life" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            {!! Theme::partial('life.menu',['active'=>"part_time_jobs_list"]) !!}
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    <li><a href="" title="Event">{{__('life')}}</a></li>
                    <li>
                        <svg width="4" height="6" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                    </li>
                    <li>{{__('life.part-time_job')}}</li>
                </ul>
                <div class="heading">
                    <div class="heading__title">
                        {{__('life.part-time_job')}}
                    </div>
                    <div class="heading__description">
                        {!!$description->description ?? __('life.part-time_job.no_description') !!}
                    </div>
                </div>

                {!! Theme::partial('life.elements.search',[
                    'categories'=> $categories,
                    'route' => route('jobs.search'),
                    'have_like' => 1,
                ]) !!}

              @if(count($notices) > 0 )
                @foreach ($notices as $notice)
                  <div class="notice-alert">
                    <div class="notice-alert__title" style="white-space: nowrap;">{{__('life.part-time_job.notice')}}</div>
                    <div class="notice-alert__description">
                      <a href="{{route('life.part_time_jobs.notices.detail',['id'=>$notice->id])}}">{!!$notice->name!!}</a>
                    </div>
                  </div>

                @endforeach
              @else
                <div class="notice-alert">
                  <div class="notice-alert__title" style="white-space: nowrap;">{{__('life.part-time_job.notice')}}</div>
                  <div class="notice-alert__description">
                    <span> {{__('life.part-time_job.no_notices')}}</span>
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
                <div class="content">
                    <div class=" table-responsive">
                        <table class="table table--content-middle table--event-comments">
                            <thead>
                                @if($style > 0)<th style="text-align: center; width:148px">{{__('life.part-time_job.images')}}</th> @endif
                                <th style="text-align: center;">{{__('life.part-time_job.classification')}}</th>
                                <th style="text-align: center;padding-left: 0px;">@if(request('type') == 1) {{__('life.part-time_job.detail')}} @else {{__('life.part-time_job.title')}} @endif</th>
                                <th style="text-align: center; width:16%;">{{__('life.part-time_job.writer')}}</th>
                                <th style="text-align: center; width:10%;">{{__('life.part-time_job.date')}}</th>
                                <th style="text-align: center; width:10%;">{{__('life.part-time_job.lookup')}}</th>
                                <th style="text-align: center; width:10%;">{{__('life.part-time_job.status')}}</th>
                            </thead>
                            <tbody>
                                @if(count($jobs) > 0 )
                                @foreach ($jobs as $key =>$item)
                                <tr @if($item->status == "pending") class="disable"  @endif style="opacity: {{100 - ($item->dislikes_count ?? 0) *10 }}%;">
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

                                            <a  href="{{route('life.part_time_jobs_details',['id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                            <div class="table__image @if($item->status != 'publish') table__image--overlay d-flex align-items-center @endif"
                                                style="background-image: url('{{ geFirsttImageInArray([$item->images],'thumb')}}')"
                                                width="150">
                                            </div>
                                            </a>
                                        </div>
                                    </td>
                                    @endif
                                    <td style="width: 13%">
                                            {!! Theme::partial('classification',['categories'=>$item->categories, 'type'=> 2]) !!}
                                    </td>
                                    <td style="padding-right:10px;"> <a href="{{route('life.part_time_jobs_details',['id'=>$item->id])}}" title="{!! $item->title !!}{{' ('.$item->comments->count().')' }}">
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

                                    <td >
                                        <div style="display: flex; align-items: center">
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
                                           @if( getStatusDateByDate($item->published) == "Today" )  <span class="icon-label">N</span> @else {{getStatusDateByDate($item->published)}}  @endif
                                    </td>
                                    <td style="text-align: center">{{$item->lookup ?? 0}}</td>
                                    <td style="text-align: center">{{$item->status == 'pending' ? __('life.part-time_job.in_recruitment') : __('life.part-time_job.closed')}}</td>

                                </tr>
                                   {{-- accordian-body collapse --}}
                                @endforeach
                            @else
                            <tr>
                                    <td colspan="6" style="text-align: center"> {{__('life.part-time_job.no_part-time_job')}}</td>
                            </tr>
                            @endif

                            </tbody>
                        </table>
                    </div>

                </div>
                <div id="form-search-2">
                    <div class="filter filter--1 align-items-end">
                        <button  type="button" class="filter__item btn btn-secondary"  onClick="window.location.href='{{route('life.part_time_jobs_list')}}'">
                            <svg width="18" height="20.781" aria-hidden="true">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_menu-list"></use>
                            </svg>
                            <span>{{__('life.part-time_job.last_list')}}</span>
                        </button>

                        @if($canCreate)
                            <a href="{{route('jobsPartTimeFE.create',['categoryId'=>request('parentCategories') ? request('parentCategories') : $idFirstParent ])}}"
                                title="{{__('life.part-time_job.create_part-time_job')}}" class="filter__item btn btn-primary mx-3 btn3">
                                <span style="white-space: nowrap;">{{__('life.part-time_job.write')}}</span>
                            </a>
                        @endif
                    </div>
                </div>
                {!! Theme::partial('paging',['paging'=>$jobs->appends(request()->input()) ]) !!}
            </div>

        </div>

    </div>
</main>

<script>
    function initialize() {

        let divMaps = document.getElementsByClassName('table__btn');

        Array.prototype.filter.call(divMaps, function(divMap,index){
            let latMap  =  divMap.getAttribute('data-lat')  ;
            let lngMap =  divMap.getAttribute('data-lng')  ;
            let mapProp= {
                center:new google.maps.LatLng(latMap, lngMap),
                zoom: 15,
            };
            let map = new google.maps.Map(document.getElementById(divMap.getAttribute('data-idMap')),mapProp);

            let latlng = {lat: parseFloat(latMap), lng:  parseFloat(lngMap) };
            var marker = new google.maps.Marker({position: latlng, map: map});
        });
    }

</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('google_api_key.api_google_place') }}&callback=initialize"></script>
