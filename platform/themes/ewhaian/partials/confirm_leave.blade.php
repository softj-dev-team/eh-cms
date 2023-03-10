<script>
// var formSubmitting = false;
// var setFormSubmitting = function() { formSubmitting = true; };

// window.onload = function() {
//     window.addEventListener("beforeunload", function (e) {


//       var confirmationMessage = '정말로 취소하시겠습니까 ?';
//         // if (formSubmitting) {
//         //     return confirmationMessage;
//         // }

//         // //var confirmationMessage = '정말로 취소하시겠습니까 ?';

//         (e || window.event).returnValue = confirmationMessage; //Gecko + IE
//         return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
//     });
// };


// $(document).ready(function(){
//     $(window).bind('beforeunload', function (e) {
//       var message = "Why are you leaving?";
//       e.returnValue = message;
//       return message;
//     });

// });
window.onbeforeunload = function() {
  return 'You have not yet saved your work.Do you want to continue? Doing so, may cause loss of your work' ;
}
$(document).ready(function (){
  $("#btn_publish").click(function(){
    window.onbeforeunload = null;
  });
  $(".btn-secondary").click(function(){
    window.onbeforeunload = null;
  });
});


</script>
