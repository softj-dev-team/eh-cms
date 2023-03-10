<div class="sidebar-template__control">
    <div class="nav nav-left">
        <ul class="nav__list">
            <p class="nav__title">{{__('life')}}</p>
            <li class="nav__item">
                <a class="@if($active == "open_space" ) active @endif" href="{{route('life.open_space_list')}}" title="{{__('life.open_space')}}">{{__('life.open_space')}}</a>
            </li>
            <li class="nav__item">
                <a class="@if($active == "flare_market_list" ) active @endif" href="{{route('life.flare_market_list')}}" title="{{__('life.flea_market')}}">{{__('life.flea_market')}}</a>
            </li>
            <li class="nav__item">
                <a class="@if($active == "part_time_jobs_list" ) active @endif" href="{{route('life.part_time_jobs_list')}}" title="{{__('life.part-time_job')}}">{{__('life.part-time_job')}}</a>
            </li>
            <li class="nav__item">
                <a class="@if($active == "shelter" ) active @endif" href="{{route('life.shelter_list')}}" title="{{__('life.shelter_info')}}">{{__('life.shelter_info')}}</a>
            </li>
            <li class="nav__item">
                <a class="@if($active == "advertisements" ) active @endif" href="{{route('life.advertisements_list')}}" title="{{__('life.advertisements')}}">{{__('life.advertisements')}}</a>
            </li>

        </ul>
    </div>
</div>
