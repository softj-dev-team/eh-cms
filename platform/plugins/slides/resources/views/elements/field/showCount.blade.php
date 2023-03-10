
@if(is_null($options['data-value'] ))
    <h3>클릭수 기능이 없음</h3>
@else
    @foreach ($options['data-value'] as $key => $item)
    <label class="control-label" style="display: block"> 배너 {{$key +1}} : {{$item['count'] ?? 0}} click</label>
    @endforeach
@endif
