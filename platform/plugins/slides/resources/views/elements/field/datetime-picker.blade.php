<div class="form-group">
        <label for="contact" class="{{$options['label_attr']['class']}}">{{$options['label'] }}</label>
        <input type='text' class="form-control" datepicker-deadline id="{{$options['id'] ?? 'noID' }}" name="{{$name}}" value="{{ $options['value'] ??  getToDate(1) }} " autocomplete="off" required/>
</div>
