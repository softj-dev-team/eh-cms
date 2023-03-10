@if ($categories)

<div class="flare_market_list" style="justify-content: center;">
  <div class="d-flex">
      @foreach ($categories as $key => $item)

      {!! getFlareCategories($item,$type ?? 1) !!}
      @if($key == 2 )  @break @endif

      @endforeach
  </div>
</div>

@else
   <span class="alert bg-white-1" title="All">{{__('life.flea_market.no_categories')}}</span>
@endif
