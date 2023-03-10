<style>
    .radio_semester {
        display: flex;
        max-height: 38px;
        align-items: center;
        margin-bottom: 10px;
    }

    .tbx-semester {
        max-height: 38px;
    }

    .radio_semester label {
        margin-bottom: 0
    }

    .radio_semester_group {
        margin-bottom: 10px;
    }
</style>
<div class="form-group">
    <label for="status" class="{{$options['label_attr']['class']}}" aria-required="true">{{$options['label']}}</label>
    <div class="form-group">
        <input type='text' class="form-control tbx-semester" id="semesterDate" yearsPicker name="semester_year"
            placeholder="Semester Year" autocomplete="off" style="max-height: 38px;" value="{{$options['semester_year'] ?? date("Y")}}" />
        <div style="margin-top: 10px;">

            <div class="radio_semester_group">
                <div class="radio_semester" >
                    <input class="hrv-radio" id="semester_2" name="semester_session" type="radio" value="2" @if($options['semester_session'] == 2 )checked @endif >
                    <label for="semester_2" class="control-label">중간고사</label>
                </div>
              <div class="radio_semester" >
                    <input class="hrv-radio" id="semester_2" name="semester_session" type="radio" value="3" @if($options['semester_session'] == 3 )checked @endif >
                    <label for="semester_2" class="control-label">기말고사</label>
                </div>
              <div class="radio_semester" >
                    <input class="hrv-radio" id="semester_2" name="semester_session" type="radio" value="4" @if($options['semester_session'] == 3 )checked @endif >
                    <label for="semester_2" class="control-label">퀴즈</label>
                </div>
              <div class="radio_semester" >
                    <input class="hrv-radio" id="semester_2" name="semester_session" type="radio" value="5" @if($options['semester_session'] == 3 )checked @endif >
                    <label for="semester_2" class="control-label">과제</label>
                </div>
            </div>
            <div style="width: 100%; display: flex">
                <div class="radio_semester" >
                    <input class="hrv-radio" id="semester_other" name="semester_session" type="radio" value="3" @if($options['semester_session'] != 1 && $options['semester_session'] != 2 && $options['semester_session'] != null )checked @endif>
                    <label for="semester_other" class="control-label">그 외</label>
                </div>
                <div style="width:100%; padding-left: 10px">
                    <input type='text' class="form-control tbx-semester semester_other_textbox" id="semester_other_textbox"  autocomplete="off" @if($options['semester_session'] != 1 && $options['semester_session'] != 2 && $options['semester_session'] != null ) name="semester_other_textbox" value="{{$options['semester_session']}}" @else readonly="true" @endif/>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    $('.hrv-radio').on('click',function(){
            if(  $("#semester_other").prop("checked") ){
                $('#semester_other_textbox').prop("readonly", false);
                $('#semester_other_textbox').attr('name','semester_other_textbox');

            }else{
                $('#semester_other_textbox').val('');
                $('#semester_other_textbox').prop("readonly", true);
                $('#semester_other_textbox').removeAttr('name');
            }
    })
</script>
