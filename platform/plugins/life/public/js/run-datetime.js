$('[datepicker-deadline]').datetimepicker({
    format: 'YYYY/MM/DD hh:mm a',
    icons: {
        time: "far fa-clock",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down"
    }
});

$('#datetimepicker1').datetimepicker();
$('#datetimepicker2').datetimepicker({
    useCurrent: false //Important! See issue #1075
});
$("#datetimepicker1").on("dp.change", function (e) {
    $('#datetimepicker2').data("DateTimePicker").minDate(e.date);
});
$("#datetimepicker2").on("dp.change", function (e) {
    $('#datetimepicker1').data("DateTimePicker").maxDate(e.date);
});