<div class="form-group"> 
        <label for="title" class="{{$options['label_attr']['class']}}" aria-required="true">{{$options['label']}}</label>
{{-- Schemadule Day --}}
@if(isset($options['typeSelect'] ) && $options['typeSelect'] == '1')
<select class="form-control select-full select2-hidden-accessible" name="{{$name}}" tabindex="-1" aria-hidden="true">
        @foreach ($options['data-value'] as $item)
            <option value="{{$item->name}}" @if($options['value'] == $item->name) selected @endif >{{ucfirst( $item->name )}}</option>
        @endforeach
</select>
@else  
{{-- Schemadule Times --}}
    <select class="form-control select-full select2-hidden-accessible" name="{{$name}}" tabindex="-1" aria-hidden="true">
            @for ($i = $options['data-value']->from; $i <= $options['data-value']->to; $i++)
                <option value="{{$i}}" @if($options['value'] == $i) selected @endif>{{$i.':00'}}</option>
                <option value="{{$i+0.5}}" @if($options['value'] == ($i+0.5) ) selected @endif>{{$i.':30'}}</option>
            @endfor
    </select>
@endif

        
</div>