<style src="{{Theme::asset()->url('css/chart/Chart.css')}}"></style>
<style>
.chart {
    display: flex;
    border: 1px solid #EC1469;
    border-radius: 10px;
}
.chart .chart-info-1 {
    flex: 1;
}
.chart-info-item-1 {
    border-right: 1px solid #EC1469;
    border-bottom: 1px solid #EC1469;
    height: 50%;
    padding: auto;
}
.chart-info-item-2 {
    border-right: 1px solid #EC1469;
    height: 50%;

}
.chart-info-2 {
    width: 700px;
    display: flex;
    position: relative;
}
.chart-info-hiden {
    position: absolute;
    height: 100%;
    width : 500px;
    background: white;
    display: flex;
}
.chart-info-hiden-text {
    height: 100%;
    flex: 1;
}
.chart-info-hiden-input {
    float: right;
    border-right: 1px solid #EC1469;
    padding: 0px 35px 10px 0;
}
.chart-info-hiden-input-box {
    display: flex;
    height: 25%;
    margin-bottom: 10px;
}
.chart-info-hiden-input-title{
    padding: 5px;
    width: 80px;
    font-weight: bold;
    text-align:right;
    margin-top:7px
}
.chart-info-hiden-input-select {
    width: 200px;
    height: 25%;
    padding: 5px;
}
.chart-info-item-hide-1 {
    height: 50%;
}
.chart-info-item-hide-2 {

    height: 50%;
}
.hiden-text {
    outline: none;
    border: 1px solid #EC1469;
    border-radius: 5px;
    width: 100%;
    padding: 5px ;
    color: #EC1469;
}
.hiden-select {
    padding: 5px;
    border: 1px solid #EC1469;
    outline: none;
    color: #EC1469;
    background-color: white;
    border-radius: 5px
}
.chart-info-item-text div {
    padding: 10px;
    margin-left: 20px;
}
.btn-toggle-off {
    width: 2.35714em;
    height: 4.57143em;
    overflow: hidden;
    position: absolute;
    top: 50%;
    margin-top: -2.28571em;
    border: none;
    z-index: 1;
    outline: none;
    color: white;
    border-radius: 0 30% 30% 0
}
.btn-toggle-on {
    width: 2.35714em;
    height: 4.57143em;
    overflow: hidden;
    position: absolute;
    top: 50%;
    margin-top: -2.28571em;
    border: none;
    z-index: 1;
    outline: none;
    color: white;
    border-radius: 30% 0 0 30%;
    right: 200px;
}
.chart-info-hiden-toggle {
    display: none;
}
.color-ew {
    color : #EC1469;
}
.nopadding-top {
    padding-top: 0px !important
}
@media (max-width: 991px) {
    .btn-toggle-on {
        right: 0;
        z-index: 10;
    }
    .chart-info-2 {
        width: 100%;
        margin-right: 10px;
    }
    .chart-info-hiden {
      width: auto;
      z-index: 9;
    }
    .chart-info-hiden-input {
        padding: 0px 0px 10px 0;
    }
    .chart-info-hiden-input-box {
        height: auto;
        margin-bottom: 8px
    }
    .chart-info-hiden-input-select {
        width: calc(100% - 80px);
    }
    .hiden-select {
        width: 100%;
    }
    .chart-info-item-text div {
        margin-left: 10px;
        white-space: nowrap;
    }
}

@media (max-width: 576px) {
  .chart-info-item-text div {
    margin-left: 0;
    font-size: 12px;
    padding: 5px;
    text-align: center
  }
  .chart-info-hiden-input-title {
    margin-top: 0;
    font-size: 12px
  }
  .chart-info-hiden-input-select {
    padding: 5px
  }
  .hiden-text, .hiden-select {
    padding: 0 5px
  }
  .chart-info-hiden-input-box {
    margin-bottom: 3px
  }
}
</style>
@if (!is_null($item))
<div  class="chart">
    <div class="chart-info-1">
        <div class="chart-info-item-1">
            <div class="chart-info-item-text">
                <div> <strong>전체 평점</strong></div>
                <div class="nopadding-top">
                    <span class="color-ew" id="point43">{{$point_average_all_detail}}</span>
                    <span>/</span>
                    <span>4.3</span>
                </div>
            </div>
        </div>
        <div class="chart-info-item-2">
            <div class="chart-info-item-text">
                <div> <strong>전체 학점</strong></div>
                <div class="nopadding-top">
                    <span class="color-ew" id="point140">{{$total_credit_all_detail}}</span>
                    <span>/</span>
                    <span >
                        <span id="factor">{{$item->factor}}</span>
                    </span>
                    <span>
                      <a href="javascript:void(0)" class="calculatorPopup" data-id="{{$item->id}}">
                          <svg width="17" height="17" aria-hidden="true" style="margin-bottom: 2px;">
                              <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_setting"></use>
                          </svg>
                      </a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="chart-info-2" >
        <div class="chart-info-hiden-toggle">
            <div class="chart-info-hiden">
                <div class="chart-info-hiden-text" >
                    <div class="chart-info-item-hide-1">
                        <div class="chart-info-item-text">
                            <div>
                                <strong>4.5 만점 환산</strong>
                            </div>
                            <div class="nopadding-top">
                                <span class="color-ew" id="point45">{{ round((4.5*$item->total_point)/4.3, 2) }}</span>
                                <span>/</span>
                                <span>4.5</span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-info-item-hide-2">
                        <div class="chart-info-item-text">
                            <div>
                                <strong>백분위 환산</strong>
                            </div>
                            <div class="nopadding-top">
                                <span class="color-ew" id="point100">{{round((100*$item->total_point)/4.3,2)}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chart-info-hiden-input">
                  <div class="chart-info-hiden-input-box">
                    <div class="chart-info-hiden-input-title">
                      <span>학년별 평점</span>
                    </div>
                    <div class="chart-info-hiden-input-select">
                      <select class="hiden-select">
                        @foreach ($calculator as $key => $subitem)
                          <option
                            value="{{$subitem->total_point}}"
                            data-credits="{{$subitem->total_credits}}"
                            data-id="{{$subitem->id}}"
                            data-factor="{{$subitem->factor}}"
                            @if($subitem->id == $item->id) selected @endif
                          >{{$subitem->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

{{--                    <div style="height: 25%;">--}}
{{--                        <div >--}}
{{--                            <select class="hiden-select">--}}
{{--                                @foreach ($calculator as $key => $subitem)--}}
{{--                                    <option--}}
{{--                                        value="{{$subitem->total_point}}"--}}
{{--                                        data-credits="{{$subitem->total_credits}}"--}}
{{--                                        data-id="{{$subitem->id}}"--}}
{{--                                        data-factor="{{$subitem->factor}}"--}}
{{--                                        @if($subitem->id == $item->id) selected @endif--}}
{{--                                    >{{$subitem->name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="chart-info-hiden-input-box">
                        <div class="chart-info-hiden-input-title">
                            <span>취득학점</span>
                        </div>
                        <div  class="chart-info-hiden-input-select">
                            <input type="text" value="{{$item->total_credits}}" id="hiden-text-1" style="cursor: auto;" class="hiden-text" readonly>
                        </div>
                    </div>
                    <div class="chart-info-hiden-input-box">
                        <div class="chart-info-hiden-input-title">
                            <span>평점</span>
                        </div>
                      <br>
                        <div class="chart-info-hiden-input-select">
                            <input type="text" value="{{$point_average_all_detail}}" id="hiden-text-2" style="width: 100%;cursor: auto;" class="hiden-text" readonly>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <input type="button" class="btn-toggle-off bg-purple " value=">" id="btn-toggle">
        <canvas id="myChart" height="100" style="padding: 10px 35px 0 35px"></canvas>
    </div>
</div>
@endif

<script src="{{Theme::asset()->url('js/chart/Chart.js')}}"></script>

<script>
var ctx = document.getElementById('myChart').getContext('2d');

var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!!json_encode($calculator->pluck('semester')->collapse()->all())!!},
        datasets: [{
            label: '학기당 평점',
            data:  {!!json_encode($calculator->pluck('semester_point')->collapse()->all())!!},
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1,
            lineTension	:false,
            fill:false,
        }],

    },
    options: {
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                ticks: {
                    stepSize : 1,
                    suggestedMax: 4,
                    suggestedMin: 2
                }
            }],
        },
    }
});

$(function() {
    $(document.body).on('click', '#btn-toggle' , function () {
        if($(this).hasClass('btn-toggle-off'))
        {
            $(this).val('<');
            $(this).addClass('btn-toggle-on');
            $(this).removeClass('btn-toggle-off');
            $('.chart-info-hiden-toggle').show();
            return;
        }

        if($(this).hasClass('btn-toggle-on'))
        {
            $(this).val('>');
            $(this).removeClass('btn-toggle-on');
            $(this).addClass('btn-toggle-off');
            $('.chart-info-hiden-toggle').hide();
            return;
        }
    });

    $(document.body).on('change','.hiden-select', function (){
        let point = $(this).val();
        let credits = $('.hiden-select option:selected').attr('data-credits');
        let id = $('option:selected', this).attr('data-id');
        let factor =  parseInt($('option:selected', this).attr('data-factor'));
         // $('#point43').text(point);
         $('#point45').text(((point*4.5) /4.3).toFixed(2));
         $('#point100').text(((point*100) /4.3).toFixed(2));
         // $('#point140').text(credits);
         $('#hiden-text-1').val(credits);
         $('#hiden-text-2').val(point);
         $('.calculatorPopup').attr('data-id', id);
         $('#factor').text(factor);
    })
})
</script>
