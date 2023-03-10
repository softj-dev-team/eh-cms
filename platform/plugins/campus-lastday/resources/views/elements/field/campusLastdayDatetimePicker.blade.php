<div class="form-group">
        <label for="contact" class="{{$options['label_attr']['class']}}">{{$options['label'] }}</label>
        <input type='datetime-local' class="form-control" id="{{$options['id'] ?? 'noID' }}" name="{{$name}}" value="{{ \Carbon\Carbon::parse($options['value'])->format('Y-m-d\TH:i') ??  \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" autocomplete="off" />
</div>
