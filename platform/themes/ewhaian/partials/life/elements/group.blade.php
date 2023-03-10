

<style>
    .tab_parent_1 {
        background-color: #4CECE7;
        color: #FFFFFF;
    }
    .tab_parent_2 {
      background-color: #C4F5FF;
      color: black;
    }
    .parent__item {
    display: inline-block;
    margin-right: 5px;
    letter-spacing: initial;
}
</style>

@if(isset($ads))
<div class="item">
    {{-- --------------------parent -------------------}}
    <div class="popular-search parent_item ">
        <ul class="popular__list">

                <li class="parent__item">
                  <a class="alert parent {{request('parents') == null || request('parents') == 0 ? 'active' : ''}} " data-toggle="tab" href="javascript:{}" title="전체" parent-value="0">전체</a>
                </li>
                <li class="parent__item ">
                    <a class="alert parent tab_parent_1 {{request('parents') != null && request('parents') == 1 ? 'active' : ''}}" data-toggle="tab" href="javascript:{}" title="{{__('life.advertisements.doing_ads')}}" parent-value="1" >{{__('life.advertisements.doing_ads')}}</a>
                </li>
                <li class="parent__item">
                    <a class="alert parent tab_parent_2 {{request('parents') != null && request('parents') == 2 ? 'active' : ''}}" data-toggle="tab"href="javascript:{}" title="{{__('life.advertisements.ads_done')}}" parent-value="2">{{__('life.advertisements.ads_done')}}</a>
                </li>
        </ul>
        <form action="{{route('ads.search')}}" method="get" id="parent_search">
                <input type="hidden" name="parents" id="parents_value" value="0">
                <input type="hidden" name="categories" value="0">
                <input type="hidden" name="style" value="{{request('style') ?? 0}}">
        </form>
    </div>
</div>
@endif

<script>
$(function(){
    $('.parent_item .popular__list .parent__item .parent').click(function(){
        $('#parents_value').val($(this).attr('parent-value'));
        $('#parent_search').submit();
    })
})
</script>

