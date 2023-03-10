
$(document).ready(function(){
    $(window).bind('beforeunload', function () {
        $('.loading-section').removeClass('hide');
    });

});
