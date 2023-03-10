<div class="sidebar-template__control">
    <div class="nav nav-left">
        <ul class="nav__list">
            <p class="nav__title">{{__('campus')}}</p>
            @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('scheduleFE.list') )
            <li class="nav__item">
                    <a  href="{{route('scheduleFE.timeline.v2')}}"
                        title="{{__('campus.timetable')}}">{{__('campus.timetable')}}</a>
            </li>
            @endif
            <li class="nav__item">
                <a class="@if($active == "calculator" ) active @endif" href="{{route('campus.calculator_list')}}"
                    title="평점계산기">평점계산기</a>
            </li>
            <li class="nav__item">
                <a class="@if($active == "evaluation" ) active @endif" href="{{route('campus.evaluation_comments_major')}}"
                    title="{{__('campus.evaluation')}}">{{__('campus.evaluation')}}</a>
            </li>
            <li class="nav__item">
                <a class="@if($active == "genealogy" ) active @endif" href="{{route('campus.genealogy_list')}}"
                    title="{{__('campus.genealogy')}}">{{__('campus.genealogy')}}</a>
            </li>
{{--            @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('oldGenealogyFE.list') )--}}
            <li class="nav__item">
                <a class="@if($active == "oldGenealogy" ) active @endif" href="{{route('campus.old.genealogy')}}"
                    title="{{__('campus.old_genealogy')}}">{{__('campus.old_genealogy')}}</a>
            </li>
{{--            @endif--}}
            <li class="nav__item">
                <a class="@if($active == "studyRoom" ) active @endif" href="{{route('campus.study_room_list')}}"
                    title="{{__('campus.study_room')}}">{{__('campus.study_room')}}</a>
            </li>

        </ul>
    </div>
</div>
