<div class="form-group check_deadline" @if($options['is_show'] == 1) style="display: block"  @else  style="display: none"  @endif  >
        <label for="contact" class="{{$options['label_attr']['class']}}">{{__('life.advertisements.deadline')}}( {{__('campus.timetable.from')}} )</label>
        <input type='text' class="form-control" datepicker-deadline id="datetimepicker1" name="start" value="{{ $options['data-value'][0] ??  getToDate(1) }} " autocomplete="off"/>
</div>

<div class="form-group check_deadline"  @if($options['is_show'] == 1) style="display: block"  @else  style="display: none"  @endif >
    <label for="contact" class="{{$options['label_attr']['class']}}">{{__('life.advertisements.deadline')}} ({{__('campus.timetable.to')}})</label>
    <input type='text' class="form-control" datepicker-deadline id="datetimepicker2" name="deadline" value="{{ $options['data-value'][1] ??  getToDate(1) }} " autocomplete="off"/>
</div>

<script>
$(function(){
    $('#is_deadline').on('change',function(){
        if($(this).is(':checked')) {
            $('.check_deadline').css('display','block');
        }else {
            $('.check_deadline').css('display','none');
        }
    })
})
</script>
