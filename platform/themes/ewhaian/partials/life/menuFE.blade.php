{{-- menu of member --}}

<div class="nav nav-left">
    <ul class="nav__list">
        <p class="nav__title">{{__('life')}}</p>
        <li class="nav__item">
            <a class="@if(Route::currentRouteName() =='flareMarketFE.list') active @endif"
                href="{{route('flareMarketFE.list')}}" title="{{__('life.flea_market.flea_market_list')}}">{{__('life.flea_market.flea_market_list')}}</a>
        </li>
        <li class="nav__item">

            <a class=" @if(isset($parent)) active @endif"
                href="{{route('flareMarketFE.create',['categoryName'=>$categories->first()->name])}}"
                title="{{__('life.flea_market.create_flea_market')}}">{{__('life.flea_market.create_flea_market')}}</a>
            @if(isset($parent))
            <ul class="nav-sub">
                @foreach ($categories as $item)
                @if($item->parent_id == 1)
                <li class="">
                    <a class="@if($parent->id == $item->id ) active @endif"
                        href="{{route('flareMarketFE.create',['categoryName'=>$item->name])}}" title="{{$item->name}}">-
                        {{__('life.flea_market.create')}} {{$item->name}}</a>
                </li>
                @endif
                @endforeach
            </ul>
            @endif

        </li>
        @if(isset($flare))
        <li class="nav__item">
            <a class="active" href="{{route('flareMarketFE.edit',['id'=>$flare->id])}}" title="{{__('life.flea_market.edit_flea_market')}} #{{$flare->id}}">{{__('life.flea_market.edit_flea_market')}} #{{$flare->id}}</a>
        </li>
        @endif
    </ul>
</div>