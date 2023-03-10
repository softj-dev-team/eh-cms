<div class=" d-sm-flex flex-wrap custom-checkbox form-group align-items-center" style="margin-bottom: 0px;">
        <div class="d-sm-flex flex-wrap flex-grow-1 align-items-center">
            <div class="custom-control mr-4">
                <input type="checkbox" class="custom-control-input" id="directDeal" @if(isset($exchange[1]) && $exchange[1] == 1) checked @endif  disabled="disabled"  >
                <label class="custom-control-label" for="directDeal">{{__('life.flea_market.direct_deals')}}</label>
            </div>
            <div class="custom-control custom-checkbox mx-3 mr-4">
                <input type="checkbox" class="custom-control-input" id="delivery" @if(isset($exchange[2]) && $exchange[2] == 2) checked @endif disabled="disabled" >
                <label class="custom-control-label" for="delivery">{{__('life.flea_market.delivery')}}</label>
            </div>
            <div class="custom-control custom-checkbox mx-3 mr-4">
                <input type="checkbox" class="custom-control-input" id="locker" @if(isset($exchange[3]) && $exchange[3] == 3) checked @endif disabled="disabled" >
                <label class="custom-control-label" for="locker">{{__('life.flea_market.locker')}}</label>
            </div>
            <div class="custom-control custom-checkbox mx-3 mr-4">
                <input type="checkbox" class="custom-control-input" id="other" @if(isset($exchange[4]) && $exchange[4] == 4) checked @endif disabled="disabled">
                <label class="custom-control-label" for="other">{{__('life.flea_market.other')}}</label>
            </div>
            <input type="text" class="form-control form-control--auto flex-grow-1" placeholder="거래 방법을 입력하세요." value="{{$exchange[5] ?? "No Exchange"}}" @if( !isset($exchange[4])  || $exchange[4] != 4) hidden @endif readonly>
        </div>
    </div>

    <style>
    .custom-control-input:disabled~.custom-control-label {
    color: #000000;
        }
        .custom-checkbox .custom-control-input:disabled:checked~.custom-control-label::before {
            background-color: rgba(255, 255, 255, 0.5);
    }
    .form-control:disabled, .form-control[readonly] {
    background-color: transparent;
}
    </style>
