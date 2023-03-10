@if($options['model'] == 'show')
    <div class="form-group"> 
            <label for="contact" class="{{$options['label_attr']['class']}}">{{$options['label'] }}</label>
            <input type='text' class="form-control" id="semesterDate" multiDate name="{{$name}}" placeholder="Semester" autocomplete="off"  />
    </div>
@else 
    <div class="form-group"> 
            <label for="contact" class="{{$options['label_attr']['class']}}">{{$options['label'] }}</label>
            <input type='text' class="form-control" id="semesterValue" placeholder="Date" value="{{$options['value'] ?? null}}" readonly />
            <input type='hidden' class="form-control" id="semesterDate" multiDate name="{{$name}}" placeholder="Semester" autocomplete="off" />
    </div>

    <div class="form-group">
            <input class="hrv-checkbox" id="is_change_semester" name="is_change_semester" type="checkbox" value="1">
            <label for="is_change_semester" class="control-label">학기 변경 </label>    
    </div>
@endif

<script>
        $('#is_change_semester').change(function(){
            if(this.checked) {
                $('#semesterValue').attr('type','hidden');
                $('#semesterDate').attr('type','text');
            }else{
                $('#semesterValue').attr('type','text');
                $('#semesterDate').attr('type','hidden');
            }
        })
</script>