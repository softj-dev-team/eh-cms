<div class="form-group" style="display: flex;">
{{--    <div class="form-group" style="width: 30%;">--}}
{{--        <label for="freshman1" class="control-label ">{{$options['label']}}</label>--}}
{{--        <div class="image-box">--}}
{{--            <input type="hidden" name="{{$name}}"--}}
{{--                value="{{$options['value']}}"--}}
{{--                class="image-data">--}}
{{--            <div class="preview-image-wrapper">--}}
{{--                <img src="{{$options['value'] ?? '/vendor/core/images/placeholder.png'}}"--}}
{{--                    alt="preview image" class="preview_image" style="height: auto;" >--}}
{{--                <a class="btn_remove_image" title="Remove image">--}}
{{--                    <i class="fa fa-times"></i>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="image-box-actions">--}}
{{--                <a href="#" class="btn_gallery" data-result="freshman1" data-action="select-image">--}}
{{--                    Choose image--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="form-group" style="width: 50%;">
        <label for="status_fresh1" class="control-label required" aria-required="true">{{$options['note_label']}}</label>
        <input class="form-control is-valid" data-counter="120"
         name="{{$options['note_name']}}" type="text" value="{{$options['note_value']}}" id="{{$options['note_name']}}" >
    </div>
    <div class="form-group" style="width: 50%;margin-left: 10px">
        <label for="status_fresh1" class="control-label required" aria-required="true">Status {{$options['label']}}</label>
        <select class="form-control select-full select2-hidden-accessible" id="status_fresh1" name="{{$options['status_name']}}"
            tabindex="-1" aria-hidden="true">
            <option value="1" {{$options['value_status'] == 1 ? 'selected' : ''}}>승인 대기 중</option>
            <option value="2" {{$options['value_status'] == 2 ? 'selected' : ''}}>승인</option>
            <option value="3" {{$options['value_status'] == 3 ? 'selected' : ''}}>승인 거절</option>
            <option value="0" {{$options['value_status'] == 0 ? 'selected' : ''}}>이미지가 없습니다</option>
        </select>
    </div>



</div>
