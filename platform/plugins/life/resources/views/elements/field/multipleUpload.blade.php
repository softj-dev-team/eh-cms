
<div class="form-group2">

    <label for="images[]" class="control-label" aria-required="true">{{__('life.flea_market.image')}}</label>

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
                    Choose image
                </a>
            </div>
        </div>

        <div class="image-box multipleUploadImage">
            <input type="hidden" name="images[2]" value="{{!empty($options['value'][2] ) ? $options['value'][2] : ''}}" class="image-data">
            <div class="preview-image-wrapper ">
                <img src="{{!empty($options['value'][2] ) ? get_image_url($options['value'][2], 'thumb') : '/vendor/core/images/placeholder.png'}}" alt="preview image"
                    class="preview_image" width="150">
                <a class="btn_remove_image" title="Remove image">
                    <i class="fa fa-times"></i>
                </a>
            </div>
            <div class="image-box-actions">
                <a href="#" class="btn_gallery" data-result="images[2]" data-action="select-image">
                    Choose image
                </a>
            </div>
        </div>

        <div class="image-box  multipleUploadImage">
            <input type="hidden" name="images[3]" value="{{!empty($options['value'][3] ) ? $options['value'][3] : ''}}" class="image-data">
            <div class="preview-image-wrapper ">
                <img src="{{!empty($options['value'][3] ) ? get_image_url($options['value'][3], 'thumb') : '/vendor/core/images/placeholder.png'}}" alt="preview image"
                    class="preview_image" width="150">
                <a class="btn_remove_image" title="Remove image">
                    <i class="fa fa-times"></i>
                </a>
            </div>
            <div class="image-box-actions">
                <a href="#" class="btn_gallery" data-result="images[3]" data-action="select-image">
                    Choose image
                </a>
            </div>
        </div>

        <div class="image-box  multipleUploadImage">
            <input type="hidden" name="images[4]" value="{{!empty($options['value'][4] ) ? $options['value'][4] : ''}}" class="image-data">
            <div class="preview-image-wrapper ">
                <img src="{{!empty($options['value'][4] ) ? get_image_url($options['value'][4], 'thumb') : '/vendor/core/images/placeholder.png'}}" alt="preview image"
                    class="preview_image" width="150">
                <a class="btn_remove_image" title="Remove image">
                    <i class="fa fa-times"></i>
                </a>
            </div>
            <div class="image-box-actions">
                <a href="#" class="btn_gallery" data-result="images[4]" data-action="select-image">
                    Choose image
                </a>
            </div>
        </div>

        <div class="image-box  multipleUploadImage">
            <input type="hidden" name="images[5]" value="{{!empty($options['value'][5] ) ? $options['value'][5] : ''}}" class="image-data">
            <div class="preview-image-wrapper ">
                <img src="{{!empty($options['value'][5] ) ? get_image_url($options['value'][5], 'thumb') : '/vendor/core/images/placeholder.png'}}" alt="preview image"
                    class="preview_image" width="150">
                <a class="btn_remove_image" title="Remove image">
                    <i class="fa fa-times"></i>
                </a>
            </div>
            <div class="image-box-actions">
                <a href="#" class="btn_gallery" data-result="images[5]" data-action="select-image">
                    Choose image
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
