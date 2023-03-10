$(document).ready(() => {

    let content_id = null;
    let type = null;
    let message = null;

    $(document.body).on('click','.registerMainContentDialog', function () {
        let id = $(this).attr('data-id');
        let type = $(this).attr('data-type');
        let category = $(this).attr('data-category');
        let element = $('.register-main-content');

        if (type === 'UN_REGISTER_MAIN_CONTENT') {
            $('.modal-confirm-un-register-main-content').modal('toggle');
        } else {
            $('.modal-confirm-register-main-content').modal('toggle');
        }

        element.attr('id',  'register-main-content-' + id);
        element.attr('data-id',  id);
        element.attr('data-type',  type);
        element.attr('data-category',  category);
        content_id = id;
    });

    $(document).on('click','.register-main-content', function () {
        let id = $(this).attr('data-id');
        let type = $(this).attr('data-type');
        let category = $(this).attr('data-category');
        let $this = $(this);
        $.ajax({
            type:'POST',
            url: 'contents/register-main-content',
            data:{
                'id' : id,
                'type' : type,
                'category' : category,
            },
            dataType: 'json',
            success: function( data ) {
                if(data.error) {
                    sessionStorage.setItem('type_toastr', 'error');
                } else {
                    sessionStorage.setItem('type_toastr', 'success');
                }
                sessionStorage.setItem('message_toastr', data.message);
                sessionStorage.setItem('register_main_content_message', true);
                location.reload();
            }
        }).always(function(jqXHR){
            $this.css('pointer-events', 'auto').data('isRunAjax', false);
        });
    })


    if (sessionStorage.getItem('register_main_content_message')) {
        Botble.showNotice(sessionStorage.getItem('type_toastr'), sessionStorage.getItem('message_toastr'));
        sessionStorage.removeItem('register_main_content_message');
    }
});