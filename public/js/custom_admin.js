
$(document).ready(function(){
    $(".add_index").parent().css({"z-index": "1000"});
    var selected_option = $('#block_user').val();
    if(selected_option == '4' || selected_option == '0'){
        $(".add_index").parent().css({"display": "none"});
    }

    $("#block_user").change(function () {
        var selected_option = $('#block_user').val();

        if (selected_option === '4' || selected_option == '0') {
            $(".add_index").parent().css({"display": "none"});
        }else{
            $(".add_index").parent().css({"display": "block"});
        }
    })
});
