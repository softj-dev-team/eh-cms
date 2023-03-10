<div class="container">
  <div class="row" style="margin:0px">

    <div class="col-sm ">
      <table class="table detail_map">
        @if(!is_null(@$item->exact_location['detail_address']))
          <tr>
            <th style="width: 1px;white-space: nowrap;">상세위치</th>
            <td>{{@$item->exact_location['detail_address']}}</td>
          </tr>
        @endif

        @if(!is_null($item->day) && !is_null($item->working_period))
          <tr>
            <th style="width: 1px;white-space: nowrap;">근무요일</th>
            <td>
              @if( !empty($item->day[1] )) 월 @endif
              @if( !empty($item->day[2] )) , 화 @endif
              @if( !empty($item->day[3] )) , 수 @endif
              @if( !empty($item->day[4] )) , 목 @endif
              @if( !empty($item->day[5] )) , 금 @endif
              @if( !empty($item->day[6] )) , 토 @endif
              @if( !empty($item->day[7] )) , 일 @endif
              @if( !empty($item->day[8] )) , 무관 @endif
              @if( !empty($item->day[9] )) , 협의 가능 @endif

            </td>
          </tr>
        @endif
        @if(!is_null($item->pay))
          <tr>
            <th style="width: 1px;white-space: nowrap;">급여</th>
            <td>
              @switch($item->pay['option'] )
                @case(0)
                시급
                @break
                @case(1)
                일급
                @break
                @case(2)
                주급
                @break
                @case(3)
                월급
                @break
                @case(4)
                기타
                @break
                @default
                시급
              @endswitch
              {{ $item->pay['price']}}
            </td>
          </tr>
        @endif
        @if(!is_null($item->time))
          <tr>
            <th style="width: 1px;white-space: nowrap;">근무시간</th>
            <td>{{$item->time}}</td>
          </tr>
        @endif
        @if(!is_null($item->open_position))
          <tr>
            <th style="width: 1px;white-space: nowrap;">모집인원</th>
            <td>{{$item->open_position}}</td>
          </tr>
        @endif
        @if(!is_null($item->contact))
          <tr>
            <th style="width: 1px;white-space: nowrap;">연락방법</th>
            <td>{{$item->contact}}</td>
          </tr>
        @endif
      </table>
    </div>
    <div class="col-sm ">
      @if(!is_null(@$item->exact_location['map_location']))
        <div class="googleMap" id="googleMap{{$item->id}}" style="width: 100%;height: 200px;">

        </div>
      @endif
    </div>
  </div>
</div>



