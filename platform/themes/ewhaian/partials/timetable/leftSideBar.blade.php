@if(!is_null($listFilter))
<input type="hidden" id="active_filter" value="{{request('active_filter') ?? getTodayFilter($schedule->id ?? 0)}}">
{{-- <div class="nav nav-left">
    <div id="campusContent">
    <input type="hidden" id="active_filter" value="{{request('active_filter') ?? getTodayFilter($schedule->id ?? 0)}}">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach ($listFilter as $filter)
                <div class="swiper-slide">
                    <div class="text-center">
                        <p>{{$filter->name}}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
    @foreach ($listFilter as $key => $filter)
    <div class="nav-content
        @if(!is_null(request('active_filter') ) && request('active_filter') != $key )
            d-none
        @elseif( is_null(request('active_filter')) && $key !=  getTodayFilter($schedule->id ?? 0))
            d-none
        @endif
        " data-index='{{$key+1}}'>
        @foreach ($filter->getSchedule() as $item)
        <a href="{{route('scheduleFE.timeline.v2',['schedule_id'=>$item->id,'filter_id'=>$filter->id ?? null,'active_filter' => $key ])}}"
            title=" {{$item->name}}"
            class="@if($item->id == $schedule->id) btn__ehw btn_active  @else  btn__ehw btn_line @endif ">
            {{$item->name}}
        </a>
        @endforeach
    </div>
    @endforeach
</div> --}}
@else
{{-- @foreach ($scheduleAll as $item)
<a href="{{route('scheduleFE.timeline.v2',['schedule_id'=>$item->id,'filter_id'=>$filter_id ?? null])}}"
    title=" {{$item->name}}"
    class="@if($item->id == $schedule->id) btn__ehw btn_active  @else  btn__ehw btn_line @endif ">
    {{$item->name}}
</a>
@endforeach --}}
@endif
@foreach ($scheduleAll as $item)
<a href="{{route('scheduleFE.timeline.v2',['schedule_id'=>$item->id,'active_filter'=>$activeID ?? null])}}"
    title=" {{$item->name}}"
    class="@if($item->id == $schedule->id) btn__ehw btn_active  @else  btn__ehw btn_line @endif ">
    {{$item->name}}
</a>
@endforeach
