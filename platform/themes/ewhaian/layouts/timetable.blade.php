<!DOCTYPE html>
<html lang="en">

<head>
        {!! Theme::partial('head') !!}
</head>

<body class="timebody">
    {!! Theme::content() !!}
</body>

<script type="text/javascript" src="/themes/ewhaian/js/popper.min.js"></script>
<script type="text/javascript" src="/themes/ewhaian/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/themes/ewhaian/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/themes/ewhaian/js/slick.min.js"></script>
<script type="text/javascript" src="/themes/ewhaian/js/datetimepicker.home.main.min.js"></script>
<script type="text/javascript" src="/themes/ewhaian/js/split-pane.js"></script>
<script type="text/javascript" src="/themes/ewhaian/js/timetable.js"></script>

<script type="text/javascript" src="/themes/ewhaian/js/timetable/html2canvas.min.js"></script>
<script type="text/javascript" src="/themes/ewhaian/js/timetable/swiper.min.js"></script>
{{-- <script type="text/javascript" src="/themes/ewhaian/js/timetable/timetable-top.js"></script> --}}
<script type="text/javascript" src="/js/timetable.js"></script>

<script type="text/javascript" src="/themes/ewhaian/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/themes/ewhaian/js/jquery.mCustomScrollbar.js"></script>
<script type="text/javascript" src="/themes/ewhaian/js/jquery.rateyo.js"></script>
<script type="text/javascript" src="/themes/ewhaian/dist/main.js"></script>

<?php echo file_get_contents("themes/ewhaian/img/symbol.svg"); ?>
{!! Theme::partial('scripts') !!}
</html>
