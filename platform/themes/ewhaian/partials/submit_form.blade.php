<div class="text-center group-open-scape-create">
    <input type="hidden" name="status" value="publish" id="status">
    <input type="hidden" name="idPreview" value="{{$idPreview ?? ''}}" id="idPreview">
    <input type="hidden" id="validate_image" value="{{$is_validate_image ?? 0}}" >
    <input type="hidden" id="show_popup" value="{{$show_popup ?? 0}}" >
    <input type="submit" class="btn btn-secondary preview" data-route="{{$route_preview ?? ''}}" value="미리보기"  >
    <input type="submit" class="btn btn-secondary submit_form" value="임시저장" data-value="draft" >
    <input type="submit" class="btn btn-primary submit_form" value="{{$idPreview ? '수정하기' :'등록하기'  }}" data-value="publish" id="btn_publish"
        @if(isset($show_popup)) data-toggle="modal" data-target="#confirmPopup2" @endif
    >
  @if(strpos(\Illuminate\Support\Facades\Request::url(), 'garden/edit'))
    <input type="submit" class="btn btn-secondary submit_form" value="사진펑" id="photoPod" data-value="photopod">
  @endif
    <input type="button" class="btn btn-secondary cancel" value="취소하기" data-route="{{$route_back ?? ''}}" >
</div>
@if(isset($show_popup))
    <!-- Modal -->
    <div class="modal fade modal--confirm" id="confirmPopup2" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header align-items-center justify-content-lg-center">
                    <span class="modal__key">
                        <svg width="40" height="18" aria-hidden="true">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_key"></use>
                        </svg>
                    </span>
                </div>
                <div class="modal-body">
                    @if (in_array(\Route::current()->getName(), ['gardenFE.create', 'gardenFE.edit']))
                        <input type="hidden" name="is_create" value="1">
                    @else
                        <input type="hidden" name="is_create" value="0">
                    @endif
                    <div class="d-lg-flex align-items-center mx-3">
                        <div class="d-lg-flex align-items-start flex-grow-1">
                            <div class="form-group form-group--1 flex-grow-1 mr-lg-30 mb-3">
                                <label for="hint" class="form-control">
                                <input type=" text" id="hint" value="{{$hint ?? ''}}" placeholder="&nbsp;"
                                        maxlength="120" name="hint">
                                    <span class="form-control__label">질문 제목 입력</span>
                                </label>
                            </div>
                            <div class="form-group form-group--1 flex-grow-1 mb-3">
                                <label for="passwordPost" class="form-control form-control--hint">
                                    <input type="password" id="passwordPost" name="pwd_post" placeholder="&nbsp;"
                                        value="" maxlength="16">
                                    <span class="form-control__label">비밀번호를 입력하세요</span>
                                </label>
                                <span class="form-control__hint" id="msg">질문 정답 입력</span>
                            </div>
                        </div>
                        <div class="button-group mb-2" style="width: 168px;">
                            <button type="button" class="btn btn-outline mr-lg-10"
                                data-dismiss="modal">{{__('garden.cancel')}}</button>
                            <button type="button" class="btn btn-primary submitByPopup" >{{__('garden.save')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<script>
  $('#photoPod').click(function (){
    if (!confirm('게시물에서 모든 이미지를 삭제하시겠습니까?')){
      window.location.reload()
    }
  });
$(function(){

    $('.preview').on('click',function(e){
        $('#my_form').attr('action', $(this).data('route'));
        $('#my_form').attr('target','_blank');
    })

    $('.submitByPopup').on('click',function(e){
        $('#my_form').attr('action', '');
        $('#my_form').removeAttr('target');

        e.preventDefault();
        if( $('[name="title"]').val() == '' ) {
            alert('Title is required');
            return;
        }
        if( $('[name="pwd_post"]').val() == '' ) {
            alert('Password is required');
            $('[name="pwd_post"]').focus();
            return;
        }

            $.ajax({
            type:'POST',
            url:'{{route('gardenFE.ajaxPasswdPost')}}',
            data:{
                    _token: "{{ csrf_token() }}",
                    'pwd_post' : $('[name="pwd_post"]').val(),
                    'id' : $('#idPreview').val(),
                    'is_create' : $('[name="is_create"]').val(),
            },
            success: function( data ) {
                if(data.check == true){
                    $("#my_form").submit();
                }else{
                    $("#msg").html(data.msg);
                }

            },
        });

    });

    $(document.body).on('click', '.submit_form', function (e) {
        if($('#show_popup').val() > 0){
            $('#confirmPopup2').show();
            e.preventDefault();
        } else {
            $(this).removeAttr('data-toggle');
            $(this).removeAttr('data-target');
        }

        $('#status').val($(this).data('value'));
        $('#my_form').attr('action', '');
        $('#my_form').removeAttr('target');

    })

    $('.cancel').on('click' , function(e) {
        if (confirm("정말로 취소하시겠습니까 ?"))
        {
            window.location.replace($(this).data('route'));
        }
    })


    $('#my_form').on('submit', function (e) {
        e.preventDefault();

        let content = CKEDITOR.instances['content'];
        let is_validate_image = document.getElementById('validate_image').value;
        let value_submit = $('#status').val();
        if(content && content.getData() ==  '' && value_submit != 'draft') {
            alert('내용을 입력하세요.');
            content.focus();
            return ;
        }

        if (is_validate_image == 0) {
            e.currentTarget.submit();
            return;
        }

        let uploadImage = document.getElementById('uploadImage');
        if(uploadImage) {
            let files = uploadImage.files;
            if (uploadImage.files.length <= 0) {
                alert('Banner is required');
                return;
            };

            if (['image/gif', 'image/jpeg', 'image/png'].indexOf(files[0].type) < 0) {
                alert('The type of the uploaded file should be an image.');
                return;
            }
        }

        e.currentTarget.submit();
    });

    $('#special_status').on('change',function(){
        $('#btn_publish').attr('data-value',$(this).val());
    })


    $(document.body).on('click','#is_pwd_post', function () {
        if($(this).is(':checked')) {
            $('#show_popup').val(1);
        } else {
            $('#show_popup').val(0);
        }
    })
})
</script>
