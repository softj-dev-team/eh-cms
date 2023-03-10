
<div class="form-group2">

    <label for="images[]" class="control-label" aria-required="true">{{__('campus.study_room.images')}}</label>

    <div class=" multipleUpload">
        <div class="image-box  multipleUploadImage">
            <input type="hidden" name="images[1]" value="{{!empty($options['value'][1] ) ? $options['value'][1] : ''}}" class="image-data">
            <div class="preview-image-wrapper ">
                <img src="{{!empty($options['value'][1] ) ? get_image_url($options['value'][1], 'thumb') : '/vendor/core/images/placeholder.png'}}" alt="preview image"
                    class="preview_image" width="150">
                <a class="btn_remove_image" title="Remove image">
                    <i class="fa fa-times"></i>
                </a>
            </div>
            <div class="image-box-actions">
                <a href="#" class="btn_gallery" data-result="images[1]" data-action="select-image">
                  이미지 선택
                </a>
            </div>
        </div>
    </div>

</div>

<style>
    .multipleUploadImage {
        display: inline-block;
        margin-left: 30px;
        margin-bottom: 15px;
    }

</style>
