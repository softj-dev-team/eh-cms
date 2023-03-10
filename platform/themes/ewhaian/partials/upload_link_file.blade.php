<style>
.input-group-image {
    margin-right: 17px;
}

.input-group-image .form-control--upload {
    height: 150px;
    width: 150px;
    max-width: 150px;
}

.data-origin-template .align-items-center {
    display: none !important;
}
</style>

<div class="form-group form-group--1">
    <label for="link_yt" class="form-control">
    <input type="text" id="link_yt" placeholder="" name="result[]" value="{{$link ? $link[0] : ''}}">
      <span class="form-control__label">링크 (ex. http://youtube.com)</span>
    </label>
</div>
<label for="deadline" class="control-label" aria-required="true" style="font-weight: bold">{{__('layout.attachiamenta')}}</label>
<div data-upload-file class="upload-file mb-3">
    <div data-template="" class="data-origin-template">
        <div class="d-flex align-items-center mb-2">
            <input type="hidden" name="result[]" value="#resultArr">
            <span class="upload-file__name flex-grow-1" data-file-type="#fileType">#fileName</span>
            <button class="btn btn-primary btn-icon mr-3 data-remove-file" data-remove-file
                type="button">
                <svg width="12.121" height="14.121" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_minus"></use>
                </svg>
            </button>
            <div class="data-clone-inputfile">
                <div class="input-group hidden">
                    <label for="uploadFile" class="form-control form-control--upload pl-2">
                        <div #hiddenform></div>
                        <span class="form-control__label">
                            <svg width="20.239" height="21.214" aria-hidden="true" class="icon">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                    xlink:href="#icon_file"></use>
                            </svg>
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div data-uploaded-content="" class="data-clone-child ">
        {{-- @if (!empty($link ))
        @foreach ($link as $item)
        <div class="d-flex align-items-center mb-2">
            <input type="hidden" name="result[]" value="{{$item}}">
            <span class="upload-file__name flex-grow-1" data-file-type="link">{{$item}}</span>
            <button class="btn btn-primary btn-icon mr-3 data-remove-file" data-remove-file
                type="button">
                <svg width="12.121" height="14.121" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_minus"></use>
                </svg>
            </button>
            <div class="">
                <div class="input-group hidden">
                    <label for="uploadFile" class="form-control form-control--upload pl-2">
                        <span class="form-control__label">
                            <svg width="20.239" height="21.214" aria-hidden="true" class="icon">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                    xlink:href="#icon_file"></use>
                            </svg>
                        </span>
                    </label>
                </div>
            </div>
        </div>
        @endforeach
        @endif --}}
        @if (!empty($file_upload ))
        @foreach ($file_upload as $key => $item)
        <div>
            <div class="d-flex align-items-center mb-2">
                <span class="upload-file__name flex-grow-1"
                    data-file-type="file">{{basename($item)}}</span>
                <button class="btn btn-primary btn-icon mr-3 data-remove-file" data-remove-file
                    data-file-url="{{$item}}" type="button">
                    <svg width="12.121" height="14.121" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_minus">
                        </use>
                    </svg>
                </button>
                <div class="">
                    <div class="input-group hidden">
                        <label for="uploadFile" class="form-control form-control--upload pl-2">
                            <span class="form-control__label">
                                <svg width="20.239" height="21.214" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                        xlink:href="#icon_file"></use>
                                </svg>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <input type="hidden" name="delete[]" id="{{$key}}" value="">
        </div>

        @endforeach
        @endif
    </div>
    <div class="d-flex align-items-center">
        {{-- <div class="form-group form-group--1 flex-grow-1 mr-3">
            <label for="link" class="form-control">
                <input data-input-link type="text" id="link"
                    placeholder="링크 (ex. http://youtube.com)">
                <span class="form-control__label"> </span>
            </label>
        </div>
        <button class="btn btn-primary btn-icon mr-3" data-add-link type="button">
            <svg width="12.121" height="15.121" aria-hidden="true" class="icon">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_plus_mini"></use>
            </svg>
        </button> --}}
        <div class="">
            <div class="input-group">
                <label for="uploadFile" class="form-control form-control--upload pl-2">
                    <input type="file" class="" name="file[]" id="uploadFile" data-add-file
                        accept=".png,.jpg,.doc,.csv,.xlsx,.xls,.ppt,.odt,.ods,.odp,.pdf,.docx,.zip">
                    <span class="form-control__label">
                        <svg width="20.239" height="21.214" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_file">
                            </use>
                        </svg>
                    </span>
                </label>
            </div>
        </div>

    </div>

</div>
