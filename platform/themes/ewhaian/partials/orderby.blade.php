<div class="filter__item d-flex align-items-center justify-content-center flex-grow-1 mo-content-L" style="{{$style ?? 'width: 200px;margin-top: 20px;'}}">
  <div class="filter__item filter__title" style="width:50px">정렬</div>
  <select class="form-control form-control--select mx-3" name="orderby" id='orderby' onchange="location = this.value;"@if (!empty($fullwidth)) style="width: 100%"@endif>
        <option value="none"  @if(is_null(request('orderby') ))  selected="true" @endif disabled="disabled" hidden>정렬 방법</option>
        <option value="{{$route.'?'.http_build_query(array_merge(request()->all(),['orderby' => 1])) }}" @if(request('orderby') == 1) selected="true" @endif >번호순</option>
        <option value="{{$route.'?'.http_build_query(array_merge(request()->all(),['orderby' => 2])) }}" @if(request('orderby') == 2) selected="true" @endif >조회순</option>
        <option value="{{$route.'?'.http_build_query(array_merge(request()->all(),['orderby' => 3])) }}" @if(request('orderby') == 3) selected="true" @endif >리플수순</option>
        @if(isset($have_like) && $have_like !== 0 )
        <option value="{{$route.'?'.http_build_query(array_merge(request()->all(),['orderby' => 4])) }}" @if(request('orderby') == 4) selected="true" @endif >공감순</option>
        @endif
        <option value="{{$route.'?'.http_build_query(array_merge(request()->all(),['orderby' => 5])) }}" @if(request('orderby') == 5) selected="true" @endif >이슈순</option>
        <option value="{{$route.'?'.http_build_query(array_merge(request()->all(),['orderby' => 6])) }}" @if(request('orderby') == 6) selected="true" @endif >비공감순</option>
    </select>

</div>
