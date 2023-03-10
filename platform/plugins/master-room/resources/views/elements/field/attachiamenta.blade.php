<style>
#link {
    -webkit-appearance: none;
    width: 100%;
    outline: 0;
    border-width: 0 0 1px;
    border-color: blue;
    padding: 0.85714em 0;
    background: none;
    border-radius: 0 !important;
    color: #444444;
    transition: all 0.15s ease;
}
#link:focus{
    outline: none;
}
.data-origin-template .align-items-center{
    display: none!important
}

</style>
<label for="deadline" class="control-label" aria-required="true">{{ __('life.advertisements.attachiamenta') }}</label>
<div data-upload-file class="upload-file mb-3">
    <div data-template="" class="data-origin-template">
        <div class="d-flex align-items-center mb-2">
            <input type="hidden" name="#nameArr" value="#resultArr">
            <span class="upload-file__name flex-grow-1" data-file-type="#fileType">#fileName</span>
            <button class="btn btn-primary btn-icon mr-3 data-remove-file" data-remove-file type="button">
           <svg id="icon_minus" width="12.121" height="12.121" viewBox="0 0 12.121 12.121">
                <g>
                    <path data-name="Path 4606" d="M12.121,6.926H5.195V6.926H0V5.195H6.926V5.195h5.195Z" fill="currentColor"></path>
                </g>
            </svg>
            </button>
            <div class="data-clone-inputfile">
                <div class="input-group hidden">
                    <label for="uploadFile" class="form-control form-control--upload pl-2">
                        <div #hiddenform ></div>
                    <span class="form-control__label">
                            <svg id="icon_file" width="20.239" height="21.214" viewBox="0 0 20.239 21.214">
                                <g id="Group_1438" data-name="Group 1438" transform="translate(-944.451 -1021.291)">
                                    <path id="attachment" d="M21.3,7.892a.41.41,0,0,0-.581,0L9.505,19.109a4.4,4.4,0,0,1-6.216-6.216L14.505,1.676a3.014,3.014,0,0,1,4.347.17,3.014,3.014,0,0,1,.17,4.347L8.486,16.73a1.512,1.512,0,0,1-2.138-2.138l7.138-7.138a.411.411,0,0,0-.581-.581L5.767,14.01a2.334,2.334,0,0,0,3.3,3.3L19.6,6.773a3.816,3.816,0,0,0-.17-5.51,3.816,3.816,0,0,0-5.51-.17L2.708,12.311a5.218,5.218,0,0,0,7.379,7.379L21.3,8.473A.41.41,0,0,0,21.3,7.892Z" transform="translate(943.266 1021.292)" fill="#444"></path>
                                </g>
                            </svg>
                    </span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div data-uploaded-content="" class="data-clone-child ">
        @if (!empty($options['link'] ))
        @foreach ($options['link'] as $item)
            <div class="d-flex align-items-center mb-2">
                <input type="hidden" name="link[]" value="{{$item}}">
                <span class="upload-file__name flex-grow-1" data-file-type="link">{{$item}}</span>
                <button class="btn btn-primary btn-icon mr-3 data-remove-file" data-remove-file type="button">
                        <svg id="icon_minus" width="12.121" height="12.121" viewBox="0 0 12.121 12.121">
                            <g>
                                <path data-name="Path 4606" d="M12.121,6.926H5.195V6.926H0V5.195H6.926V5.195h5.195Z" fill="currentColor"></path>
                            </g>
                        </svg>
                </button>
                <div class="">
                    <div class="input-group hidden">
                        <label for="uploadFile" class="form-control form-control--upload pl-2">
                        <span class="form-control__label">
                                <svg id="icon_file" width="20.239" height="21.214" viewBox="0 0 20.239 21.214">
                                    <g id="Group_1438" data-name="Group 1438" transform="translate(-944.451 -1021.291)">
                                        <path id="attachment" d="M21.3,7.892a.41.41,0,0,0-.581,0L9.505,19.109a4.4,4.4,0,0,1-6.216-6.216L14.505,1.676a3.014,3.014,0,0,1,4.347.17,3.014,3.014,0,0,1,.17,4.347L8.486,16.73a1.512,1.512,0,0,1-2.138-2.138l7.138-7.138a.411.411,0,0,0-.581-.581L5.767,14.01a2.334,2.334,0,0,0,3.3,3.3L19.6,6.773a3.816,3.816,0,0,0-.17-5.51,3.816,3.816,0,0,0-5.51-.17L2.708,12.311a5.218,5.218,0,0,0,7.379,7.379L21.3,8.473A.41.41,0,0,0,21.3,7.892Z" transform="translate(943.266 1021.292)" fill="#444"></path>
                                    </g>
                                </svg>
                        </span>
                        </label>
                    </div>
                </div>
            </div>
        @endforeach
        @endif
        @if (!empty($options['file_upload'] ))
            @foreach ($options['file_upload'] as $key => $item)
            <div>
                <div class="d-flex align-items-center mb-2">
                        <input type="hidden" name="file_upload[]" value="{{$item}}">
                    <span class="upload-file__name flex-grow-1" data-file-type="file" >{{basename($item)}}</span>
                    <button class="btn btn-primary btn-icon mr-3 data-remove-file" data-remove-file data-file-url="{{$item}}" type="button">
                            <svg id="icon_minus" width="12.121" height="12.121" viewBox="0 0 12.121 12.121">
                                <g>
                                    <path data-name="Path 4606" d="M12.121,6.926H5.195V6.926H0V5.195H6.926V5.195h5.195Z" fill="currentColor"></path>
                                </g>
                            </svg>
                    </button>
                    <div class="">
                        <div class="input-group hidden">
                            <label for="uploadFile" class="form-control form-control--upload pl-2">
                            <span class="form-control__label">
                                    <svg id="icon_file" width="20.239" height="21.214" viewBox="0 0 20.239 21.214">
                                        <g id="Group_1438" data-name="Group 1438" transform="translate(-944.451 -1021.291)">
                                            <path id="attachment" d="M21.3,7.892a.41.41,0,0,0-.581,0L9.505,19.109a4.4,4.4,0,0,1-6.216-6.216L14.505,1.676a3.014,3.014,0,0,1,4.347.17,3.014,3.014,0,0,1,.17,4.347L8.486,16.73a1.512,1.512,0,0,1-2.138-2.138l7.138-7.138a.411.411,0,0,0-.581-.581L5.767,14.01a2.334,2.334,0,0,0,3.3,3.3L19.6,6.773a3.816,3.816,0,0,0-.17-5.51,3.816,3.816,0,0,0-5.51-.17L2.708,12.311a5.218,5.218,0,0,0,7.379,7.379L21.3,8.473A.41.41,0,0,0,21.3,7.892Z" transform="translate(943.266 1021.292)" fill="#444"></path>
                                        </g>
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
    <div class="select-media-box">
            <div class="attachment-wrapper" style="display: flex;align-items: center;">
                    <input type="hidden" value="__value__" class="attachment-url">
                    <div class="attachment-details" style="width: 100%">
                        <a href="javascript:void(0)"  class="btn_gallery" data-action="attachment"  >&nbsp;</a>
                    </div>
                    <div class="image-box-actions" style="flex-grow: 1!important;display: flex;justify-content: space-around;align-items: center;">
                            <a href="#" class="btn_gallery" data-action="attachment" style="display: block; margin: 0 14px;" title="Attachment file">
                                    <svg id="icon_file" width="20.239" height="21.214" viewBox="0 0 20.239 21.214">
                                            <g id="Group_1438" data-name="Group 1438" transform="translate(-944.451 -1021.291)">
                                              <path id="attachment" d="M21.3,7.892a.41.41,0,0,0-.581,0L9.505,19.109a4.4,4.4,0,0,1-6.216-6.216L14.505,1.676a3.014,3.014,0,0,1,4.347.17,3.014,3.014,0,0,1,.17,4.347L8.486,16.73a1.512,1.512,0,0,1-2.138-2.138l7.138-7.138a.411.411,0,0,0-.581-.581L5.767,14.01a2.334,2.334,0,0,0,3.3,3.3L19.6,6.773a3.816,3.816,0,0,0-.17-5.51,3.816,3.816,0,0,0-5.51-.17L2.708,12.311a5.218,5.218,0,0,0,7.379,7.379L21.3,8.473A.41.41,0,0,0,21.3,7.892Z" transform="translate(943.266 1021.292)" fill="#444"></path>
                                            </g>
                                          </svg>
                                </a>
                                <button class="btn btn- btn-icon mr-3" data-add-file type="button">
                                        <svg id="icon_plus_mini" width="12.121" height="12.121" viewBox="0 0 12.121 12.121">
                                                <g>primary
                                                  <path data-name="Path 4606" d="M12.121,6.926H6.926v5.195H5.195V6.926H0V5.195H5.195V0H6.926V5.195h5.195Z" fill="#fff"></path>
                                                </g>
                                        </svg>
                                </button>
                    </div>
                </div>

    </div>
    <div class="d-flex align-items-center">
    <div class="form-group form-group--1 flex-grow-1 mr-3">
        <input data-input-link type="text" id="link" placeholder="링크 (ex. http://youtube.com)">
    </div>
    <button class="btn btn-primary btn-icon mr-3" data-add-link type="button">
            <svg id="icon_plus_mini" width="12.121" height="12.121" viewBox="0 0 12.121 12.121">
                    <g>
                      <path data-name="Path 4606" d="M12.121,6.926H6.926v5.195H5.195V6.926H0V5.195H5.195V0H6.926V5.195h5.195Z" fill="#fff"></path>
                    </g>
            </svg>
    </button>
    </div>


</div>

<script>
    function basename(path) {
    return path.split('/').reverse()[0];
    }
    const template = $('[data-template]').html(),
          container = $('[data-uploaded-content]');
          var template_id = 0;


    $('[data-add-link]').on('click', function(){
      const link = $('[data-input-link ]').val();
      if(link.trim()){
        container.append(template.replace('#fileName',link).replace('#fileType', 'link').replace('#resultArr', link).replace('#nameArr', 'link[]'));
        $('[data-input-link ]').val('');
      }
    });

    $('[data-add-file]').on('click', function(){
        let link = $(this).parent().parent().find('.attachment-url').val();
        if( link == '__value__' ) return;
        if(link.trim()){
        container.append(template.replace('#fileName',basename(link)).replace('#fileType', 'link').replace('#resultArr', link).replace('#nameArr', 'file_upload[]'));
        $(this).parent().parent().find('.attachment-details a').remove();
        $(this).parent().parent().find('.attachment-details').html('<a href="__value__" target="_blank">&nbsp;</a>');
        $(this).parent().parent().find('.attachment-url').val('');

      }
    });

    $(document).on('click','.data-clone-child .data-remove-file', function(){
       if( $(this).parent().find('.upload-file__name').attr('data-file-type') == 'file'){
        $(this).parent().parent().find('input:hidden').val($(this).attr('data-file-url'));
       }

        $(this).closest('.align-items-center').remove();
    });


</script>
