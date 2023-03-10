{{-- $options['value'] --}}

<div class="form-group">

    <label for="exchange" class="control-label" aria-required="true">{{__('life.flea_market.exchange')}}</label>


    <div class="form-group">
        <label class="control-label " >
            <input type="checkbox" value="1" name="exchange[1]" {{ !empty($options['value'][1] ) ? 'checked' : '' }} >
          직거래
        </label>
        <label class="control-label " style="padding-left: 5%;">
            <input type="checkbox" value="2" name="exchange[2]" {{ !empty($options['value'][2] ) ? 'checked' : '' }}>
          배달
        </label>
        <label class="control-label " style="padding-left: 5%;">
            <input type="checkbox" value="3" name="exchange[3]" {{ !empty($options['value'][3] ) ? 'checked' : '' }}>
          로커
        </label>
        <label class="control-label " style="padding-left: 5%;">
            <input type="checkbox" value="4" name="exchange[4]" {{ !empty($options['value'][4] ) ? 'checked' : '' }}>
          기타
        </label>
    </div>

    <input class="form-control" placeholder="Enter your words" data-counter="120" name="exchange[5]" type="hidden" id="showthis" value="{{!empty($options['value'][5] ) ? $options['value'][5] : '' }}">

    @if ($errors->any())
            @foreach ($errors->all() as $error)
            <span id="title-error" class="invalid-feedback" style="display:block">{{ $error }}</span>

            @endforeach
@endif





</div>

<script>
$(function () {

    if($('input[name="exchange[4]"]').attr('checked')){
        $('input[name="exchange[5]"]').prop('type','text');
        $('input[name="exchange[5]"]').focus();
    }

    //show it when the checkbox is clicked
    $('input[name="exchange[4]"]').on('click', function () {
        if ($(this).prop('checked')) {
            $('input[name="exchange[5]"]').prop('type','text')
            $('input[name="exchange[5]"]').fadeIn();
            $('input[name="exchange[5]"]').focus();
        } else {
            $('input[name="exchange[5]"]').fadeOut();
            $('input[name="exchange[5]"]').prop('type','hidden')
        }
    });
});
</script>
