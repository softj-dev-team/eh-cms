
@if(!isset($can_active_empathy))
<div class="d-flex flex-wrap custom-checkbox mb-2 mt-5">
    <p class="text-bold mr-4">{{__('garden.comment_empathy_function')}}</p>
    <div class="custom-control mr-4">
        <input type="radio" class="custom-control-input" id="activation" name='active_empathy'
            @if(is_null($item) || $item->active_empathy >0 ) checked @endif value="1">
        <label class="custom-control-label" for="activation">{{__('garden.activation')}}</label>
    </div>
    <div class="custom-control mr-4">
        <input type="radio" class="custom-control-input" id="disabled" name='active_empathy' value="0"
            @if( !is_null($item) && $item->active_empathy ==0 ) checked @endif>
        <label class="custom-control-label" for="disabled">{{__('garden.disabled')}}</label>
    </div>
</div>
@endif
@if(!isset($can_right_click))
<div class=" d-flex flex-wrap custom-checkbox mb-2">
    <p class="text-bold mr-4">{{__('garden.do_not_right_click')}}</p>
    <div class="custom-control mr-4">
        <input type="radio" class="custom-control-input" id="use" name="right_click"
            @if(is_null($item) || $item->right_click >0 ) checked @endif value="1">
        <label class="custom-control-label" for="use">{{__('garden.use')}}</label>
    </div>
    <div class="custom-control mr-4">
        <input type="radio" class="custom-control-input" id="notUsed" name="right_click" @if(
            !is_null($item) && $item->right_click == 0 ) checked @endif value=0>
        <label class="custom-control-label" for="notUsed">{{__('garden.not_used')}}</label>
    </div>
    {{-- <span>
        <span class="required">*</span>
        {{__('garden.enabled_warming')}}
    </span> --}}
</div>
@endif
<div class=" d-flex flex-wrap custom-checkbox mb-4">
    <a href="{{getPolicyPage()}}" class="text-bold mr-4" target="_blank">{{__('life.advertisements.open_bulletin_board_policy')}}</a>
    <div class="custom-control mr-4">
        <input type="checkbox" name="policy_confirm" class="custom-control-input" id="agree"
            checked required>
        <label class="custom-control-label" for="agree">{{__('life.advertisements.agree')}}</label>
    </div>
    {{-- <span>
        <span class="required">*</span>
        {{__('life.advertisements.view_bulletin_board_policy')}}
    </span> --}}
</div>
