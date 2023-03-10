@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        <div {!! $options['wrapperAttrs'] !!}>
    @endif
@endif

@if ($showLabel && $options['label'] !== false && $options['label_show'])
    {!! Form::customLabel($name, $options['label'], $options['label_attr']) !!}
@endif

@if ($showField)
    <div class="input-group color-picker">
        {!! Form::text($name, $options['value'] ?? '#000', $options['attr']) !!}
        <span class="input-group-append">
        <span class="input-group-text colorpicker-input-addon"><i></i></span>
      </span>
    </div>
    @include('core.base::forms.partials.help_block')
@endif

@include('core.base::forms.partials.errors')

@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        </div>
    @endif
@endif
