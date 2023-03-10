<style>
.table tr.disable {
    opacity: 0.4;
}
.flare_market_list {

    flex-direction: unset;
}
.item__image {
    overflow: hidden;
    position: relative;
}
</style>
<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            {!! Theme::partial('campus.menu',['active'=>"studyRoom"]) !!}
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    <li><a href="{{route('campus.evaluation_comments_major')}}" title="{{__('campus')}}">{{__('campus')}}</a></li>
                    <li>
                        <svg width="4" height="6" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                    </li>
                    <li>{{__('campus.study_room')}}</li>
                </ul>
                <div class="heading" style="display: flex;">
                    <div class="heading__title" style="white-space: nowrap;">
                        {{__('campus.study_room')}}
                    </div>
                    <div class="heading__description" style="word-break: break-word;margin-left: 20px;">
                        {!!$description->description ?? __('campus.study_room.no_have_description')!!}
                    </div>
                </div>

              @if(count($notices) > 0 )
                @foreach ($notices as $notice)
                    <div class="notice-alert">
                      <div class="notice-alert__title" style="white-space: nowrap;">{{__('campus.study_room.notice')}}</div>
                      <div class="notice-alert__description">
                        <a href="{{route('campus.study_room.notices.detail',['id'=>$notice->id])}}">{!!$notice->name!!}</a>
                      </div>
                    </div>
                @endforeach
              @else
                <div class="notice-alert">
                  <div class="notice-alert__title" style="white-space: nowrap;">{{__('campus.study_room.notice')}}</div>
                  <div class="notice-alert__description">
                    <span> {{__('campus.study_room.no_have_notices')}}</span>
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
                    <div class="table-responsive">
                        <table class="table table--content-middle">
                            <thead>
                                @if($style > 0)<th style="text-align: center; width:148px">{{__('campus.study_room.images')}}</th> @endif
                                <th style="text-align: center;" width="150px">{{__('campus.study_room.classification')}}</th>
                                <th style="text-align: center;">@if(request('type') == 1) {{__('campus.study_room.detail')}} @else {{__('campus.study_room.title')}} @endif</th>
                                {{-- <th style="text-align: center;">{{__('campus.study_room.writer')}}</th>
                                <th style="text-align: center;">{{__('campus.study_room.date')}}</th> --}}
                                <th style="text-align: center; ">{{__('campus.study_room.lookup')}}</th>
                            </thead>
                            <tbody>
                                @if(count($studyRoom) > 0 )
                                @foreach ($studyRoom as $item)
                                <tr>
                                    @if($style > 0)
                                    <td>
                                        <div class="item__image">
                                            @if(getNew($item->published,1))
                                                <div class="item__new">
                                                    <div class="item__rectangle" style="z-index: 1">

                                                    </div>
                                                    <div class="item__eye" style="z-index: 2;margin-right: -10px;margin-top: -10px">
                                                    <span class="icon-label-image">N</span>
                                                    </div>
                                                </div>
                                            @endif

                                            <a href="{{route('campus.study_room_details',['id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                            <div class="table__image @if($item->status != 'publish') table__image--overlay d-flex align-items-center @endif"
                                                style="background-image: url('{{geFirsttImageInArray($item->images,'thumb')}}')"
                                                width="150">
                                            </div>
                                            </a>
                                        </div>
                                    </td>
                                    @endif
                                    <td >
                                        {!!
                                        Theme::partial('classification',['categories'=>[$item->categories],'type'=>5,'link'=>'campus.elements.showCategories'
                                        ]) !!}
                                    </td>
                                    <td> <a href="{{route('campus.study_room_details',['id'=>$item->id])}}" title=" {!! $item->title !!}{{' ('.$item->comments->count().')' }}">
                                            @if(request('type') > 0 )
                                                {!! Theme::partial('show_title',['text'=>$item->detail ?? "No have details", 'keyword' => request('keyword') ]) !!}
                                                {{' ('.$item->comments->count().')' }}
                                                @if(getNew($item->published,1)) <span class="icon-label">N</span>@endif
                                            @else
                                                {!! Theme::partial('show_title',['text'=>$item->title , 'keyword' => request('keyword') ]) !!}
                                                {{' ('.$item->comments->count().')' }}
                                                @if(getNew($item->published,1)) <span class="icon-label">N</span> @endif
                                            @endif
                                        </a>
                                    </td>
                                    {{-- <td >
                                        <div style="display: flex;">
                                            @if($item->member_id ==null || $item->getNameMemberById($item->member_id)
                                            =="Anonymous" )
                                                {!! getStatusWriter('real_name_certification')  !!}
                                            @else
                                                {!! getStatusWriter($item->getStatusMember($item->member_id))  !!}
                                            @endif
                                            {{$item->getNameMemberById($item->member_id)}}
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        {{getStatusDateByDate($item->published)}}

                                    </td> --}}
                                    <td style="text-align: center;">{{$item->lookup ?? 0}}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5" style="text-align: center">{{__('campus.study_room.no_study_room')}}</td>
                                </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>

                </div>
                <form action="{{route('study.search')}}" method="GET" id="form-search-2">
                    <div class="filter filter--1 align-items-end">
                        <button type="button" class="filter__item btn btn-secondary"
                            onClick="window.location.href='{{route('campus.study_room_list')}}'">
                            <svg width="18" height="20.781" aria-hidden="true">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_menu-list"></use>
                            </svg>
                            <span>{{__('campus.study_room.last_list')}}</span>
                        </button>

                        {{-- <div class="filter__item d-flex  align-items-end justify-content-md-center  mx-3">
                            <div class="d-flex align-items-center mr-2">
                                <span class="arrow">
                                    <svg width="6" height="15" aria-hidden="true" class="icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow">
                                        </use>
                                    </svg>
                                </span>
                                <input data-datepicker-start type="text"
                                    class="form-control form-control--date startDate" id="startDate1" name="startDate"
                                    value="{{request('startDate') ?: getToDate() }}" autocomplete="off">
                            </div>
                            <span class="filter__connect">-</span>
                            <div class="d-flex align-items-end ml-lg-2">
                                <input data-datepicker-end type="text" class="form-control form-control--date endDate"
                                    id="endDate1" name="endDate" value="{{request('endDate') ?: getToDate() }}" autocomplete="off">
                                <span class="arrow arrow--next">
                                    <svg width="6" height="15" aria-hidden="true" class="icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow">
                                        </use>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="filter__item d-flex  align-items-end justify-content-md-center flex-grow-1">
                            <select class="form-control form-control--select" name="type"
                                value="{{ request('type') }}">
                                <option value="0" @if(request('type')==0) selected @endif>{{__('campus.study_room.title')}}</option>
                                <option value="1" @if(request('type')==1) selected @endif>{{__('campus.study_room.detail')}}</option>
                            </select>

                            <div class="form-group form-group--search  flex-grow-1  mx-3">
                                <a href="javascript:{}" onclick="document.getElementById('form-search-2').submit();">
                                <span class="form-control__icon">
                                    <svg width="14" height="14" aria-hidden="true" class="icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search"></use>
                                    </svg>
                                </span>
                                </a>
                                <input type="text" class="form-control" placeholder="{{__('campus.study_room.enter_title')}}" name="keyword"
                                    value="{{ request('keyword') }}">
                            </div>
                        </div> --}}
                        @if($canCreate )
                            <a href="{{route('studyRoomFE.create')}}" title="{{__('campus.study_room.create_study_room')}}"
                                class="filter__item btn btn-primary mx-3 btn3">
                                <span style="white-space: nowrap;">{{__('campus.study_room.write')}}</span>
                            </a>
                        @endif
                        <input type="hidden" name="parentCategories" id="parentCategories3"
                            value="{{request('parentCategories') }}">
                        <input type="hidden" name="childCategories" id="childCategories3"
                            value="{{request('childCategories')}}">
                        <input type="submit" title="Enrollment" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"
                            style="display:none">

                    </div>
                </form>
                {!! Theme::partial('paging',['paging'=>$studyRoom->appends(request()->input()) ]) !!}
            </div>

        </div>

    </div>
</main>
