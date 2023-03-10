<script>
!function(a){a.fn.datepicker.dates.ko={days:["일요일","월요일","화요일","수요일","목요일","금요일","토요일"],daysShort:["일","월","화","수","목","금","토"],daysMin:["일","월","화","수","목","금","토"],months:["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],monthsShort:["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],today:"오늘",clear:"삭제",format:"yyyy-mm-dd",titleFormat:"yyyy년mm월",weekStart:1}}(jQuery);
//     $(document).ready(function(){
//       $.datepicker.setDefaults({
//         closeText: "닫기",
//         currentText: "오늘",
//         prevText: '이전 달',
//         nextText: '다음 달',
//         monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
//         monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
//         dayNames: ['일', '월', '화', '수', '목', '금', '토'],
//         dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
//         dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
//         weekHeader: "주",
//         yearSuffix: '년'
//       });
//     });

    $('[data-datepicker-start]').datepicker({
        format: 'yyyy.mm.dd',
        language: 'ko',


    })
    $('[data-datepicker-end]').datepicker({
        format: 'yyyy.mm.dd',
        language: 'ko',
    })
    $('[data-datepicker-year]').datepicker({
        multidate: false,
        format: 'yyyy',
        startView: "years",
        viewMode: 'years',
        minViewMode: 'years'
    })

    $('.startDate').datepicker()
        .on('changeDate', function(e) {
            // `e` here contains the extra attributes
            $('.endDate').datepicker('setStartDate', $(this).val())
        });

    $('.endDate').datepicker()
        .on('changeDate', function(e) {
            // `e` here contains the extra attributes
            $('.startDate').datepicker('setEndDate', $(this).val())
        });


    $('[datepicker-deadline]').datetimepicker({
        format: 'YYYY/MM/DD hh:mm a'
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

    // $('.events_c').click(function(e){
    //     if ($('.events_comments').hasClass('opened')) {
    //         $('.events_comments').toggleClass('opened');
    //     }

    //     $('.event-details').removeClass('show');
    //     $(this).find('.events_comments').toggleClass('opened');
    //     // $('.events_comments').removeClass('opened');
    //     // $('.event-details').removeClass('show');
    // });

    $('.event-details').on('show.bs.collapse', function (e) {
        $('.events_c td').find('i').attr('class', 'fas fa-angle-down table__btn events_comments');
        const target = $(this).attr('id');
        $('i[data-target="#'+ target +'"]').attr('class', 'fas fa-angle-up table__btn events_comments');
        $('.event-details').removeClass('show');
        $(this).addClass('show');
    });
    $('.event-details').on('hide.bs.collapse', function (e) {
        const target = $(this).attr('id');
        console.log(target);
        $('i[data-target="#'+ target +'"]').attr('class', 'fas fa-angle-down table__btn events_comments');
    });


    $(function() {
        uploadImage();
        removeImage();
    });

    function uploadImage() {
        $('[data-add-image]').on('change', function() {
            var thiz = this,
            files = thiz.files[0];
            if (files) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(thiz).parent().css('background-image', 'url(' + e.target.result + ')');
                    $(thiz).parent().css('background-color', 'white');
                    $(thiz).parent().css('background-position', 'center');
                    $(thiz).parent().addClass('form-control__invisible');
                    $(thiz).parent().parent().find('a').css({"display":"block"});
                    $(thiz).parent().parent().find(".imagesValue").val(1) ;
                    $(thiz).parent().parent().find(".imagesBase64").val(e.target.result) ;

                    $('#base64Image').val(e.target.result);
                };

                reader.readAsDataURL(files);
                const nextItem = $(this).closest('.input-group-image').next();
                if (nextItem.length == 0) {
                    $(thiz).closest('.input-group-image').clone().appendTo('#box-group-image');
                }

                removeImage();
                uploadImage();
            }
        });
    }

    function removeImage() {
        $('.btn_remove_image').click(function(){
            // $(this).parent().find("label").css({ "background-image": "none",'background-color' :'#F4F4F4'});
            // $(this).parent().find("label").removeClass('form-control__invisible');
            // $(this).css({"display" : "none"});
            // $(this).parent().find(".imagesValue").val(null) ;
            // $(this).parent().find(".imagesBase64").val(null) ;
            $(this).parent().remove();
        });
    }
</script>

<script type="text/javascript">
    window.noticeMessages = [];
    @if (session()->has('permission'))
    noticeMessages.push({'type': 'success', 'message': '{!! session('permission') !!}'});
    @endif
</script>

<script>

    $('.slick_banner').slick({
        dots: true,
        nextArrow:
            '<button type="button" class="slick-next"><svg class="icon" xmlns="http://www.w3.org/2000/svg" width="8" height="15" viewBox="0 0 8 15"><g><g><path fill="#fff" d="M.85 14.169a.483.483 0 0 1-.35.145.495.495 0 0 1-.35-.845l6.155-6.155L.15 1.159a.495.495 0 0 1 .7-.7l6.505 6.505a.495.495 0 0 1 0 .7z"/></g></g></svg></button>',
        prevArrow:
            '<button type="button" class="slick-prev"><svg class="icon" xmlns="http://www.w3.org/2000/svg" width="8" height="15" viewBox="0 0 8 15"><g><g><path fill="#fff" d="M6.65 14.169a.483.483 0 0 0 .35.145.495.495 0 0 0 .35-.845L1.195 7.314 7.35 1.159a.495.495 0 0 0-.7-.7L.145 6.964a.495.495 0 0 0 0 .7z"/></g></g></svg></button>',
        autoplay: true,
        autoplaySpeed: 3000,
    });

    $(".tab-pane li a").click(function() {
        $(".tab-pane li a ").removeClass('active');
        $(this).addClass('active');

        $("#childCategories").val( $(this).attr('data-value'))

        $("#categories_search").submit();

    });

    $(".item .popular-search .nav li a ").click(function(){
        $("#parentCategories").val( $(this).attr('data-value'))
        $(".tab-pane li a ").removeClass('active');
        $('.all').addClass('active');
        $("#childCategories").val(0);
        $("#categories_search").submit();
    })

    $(".popular__item a").click(function() {
        $(".popular__item a ").removeClass('active');
        $(this).addClass('active');
        $("#categories2").val( $(this).attr('data-value'));

    });

    if($('input[name="exchange[4]"]').attr('checked')){
        $('input[name="exchange[5]"]').prop('type','text');
        $('input[name="exchange[5]"]').focus();
    }

    //show it when the checkbox is clicked
    $('input[name="exchange[4]"]').on('click', function () {
        if ($(this).prop('checked')) {
            $('input[name="exchange[5]"]').prop('type','text')
            $('input[name="exchange[5]"]').fadeIn();
            $('input[name="exchange[5]"]').focus();
        } else {
            $('input[name="exchange[5]"]').fadeOut();
            $('input[name="exchange[5]"]').prop('type','hidden')
        }
    });

    $('#categories-flare').change(function(){
        $('#form__title_flare').text($('#categories-flare option:selected').text() );
    });


    //ads

    const template = $('[data-template]').html(),
          container = $('[data-uploaded-content]');
          var template_id = 0;

    $('[data-add-link]').on('click', function(){
      const link = $('[data-input-link ]').val();
      if(link.trim()){
        container.append(template.replace('#fileName',link).replace('#fileType', 'link').replace('#resultArr', link));
        $('[data-input-link ]').val('');
      }
    });

    $('[data-add-file]').on('change', function(){
      template_id ++;
      const fileData = $(this)[0].files;
      if(fileData){
          $html = $(template.replace('#fileName', fileData[0].name).replace('#fileType', 'file'));
          $html.attr('id','template'+template_id);

        var $this = $(this), $clone = $this.clone();
        $clone.hide().attr('id','');
        $html.append($clone);
        // $this.after($clone).appendTo('#hiddenform');
        container.append($html);
        $this.val("");

      }
    });

    $(document).on('click','.data-clone-child .data-remove-file', function(){
       if( $(this).parent().find('.upload-file__name').attr('data-file-type') == 'file'){
        $(this).parent().parent().find('input:hidden').val($(this).attr('data-file-url'));
       }

        $(this).closest('.align-items-center').remove();
    });

    $('#other1').click(function(){
        if( $(this).is(':checked') ){
            $('#recruiment').attr('type','text');
        }else{
            $('#recruiment').attr('type','number');
        }
    })

    $(function(){
        $('.popular__item').on('click',function(e){
            var $this = $(this);

            $('.keyword').val($this.children().text() );
            $('.popular__item').children().removeAttr('style');
            $this.children().attr('style','color:#EC1469');

        })
    })

    $(function(){
        let radioId, unChecked = 1;
        $('input[type="radio"]').on('click', function() {
            if ($(this).prop('id') == radioId &&
                $(this).prop('checked') == true &&
                unChecked == 1) {
                $(this).prop('checked', false);
                unChecked = 0;
            } else {
                unChecked = 1;
            }
            radioId = $(this).prop('id');
        })


    });

    // add timeable schedule campus
    const templateSchedule = $('#form-add-timeline').find('[data-template]').html()
    containerSchedule = $('#form-add-timeline').find('[data-uploaded-content]');
    var template_id = @if(!empty($options['data-selected'])) < ? php end($options['data-selected']);
    echo key($options['data-selected']) ? > @else 0 @endif;
    var datetime = @if(!empty($options['data-selected'])) < ? php echo json_encode($options['data-selected']) ? > @else[] @endif;
    $('[data-add-link-schedule]').on('click', function() {
        var day = $('[data-select-day] option:selected').html(),
            from = $('[data-select-from] option:selected').html(),
            to = $('[data-select-to] option:selected').html(),

            dayValue = $('[data-select-day] option:selected').val(),
            fromValue = parseFloat($('[data-select-from] option:selected').val()),
            toValue = parseFloat($('[data-select-to] option:selected').val());

        var item = {
            'day': dayValue,
            'from': fromValue,
            'to': toValue,
        }

        switch (checkTimeLineBeforeAdd(datetime, item)) {
            case 0:
                $('[data-template]').removeClass('d-none');
                template_id++;
                containerSchedule.append(
                  templateSchedule
                    .replace('#On', day)
                    .replace('#From', from)
                    .replace('#To', to)
                    .replace('#dayValue', dayValue)
                    .replace('#fromValue', fromValue)
                    .replace('#toValue', toValue)
                    .replace('#template_day', template_id)
                    .replace('#template_from', template_id)
                    .replace('#template_to', template_id)
                    .replace('#numberTemplate', template_id)
                );
                $('#datetime-error').css('display', 'none');
                $('.data-origin-template').remove();
                datetime[template_id] = item;
                break;
            case 1:
                $('#datetime-error').css('display', 'block');
                $('#datetime-error').html('{{ __('campus.timetable.from_less_than_to') }}');
                break;
            case 2:
                $('#datetime-error').css('display', 'block');
                $('#datetime-error').html('{{ __('campus.timetable.timeline_exists') }}');
                break;

            default:
                break;
        }
    });

    $(document).on('click','.data-clone-child .data-remove-file', function(){
        if( $(this).parent().parent().find('.upload-file__name').attr('data-file-type') == 'file'){
        $(this).parent().parent().find('input:hidden').val($(this).attr('data-file-url'));
        }

        delete datetime[$(this).parent().parent().find('.template_id').val()];
        $(this).parent().parent().find('.align-items-center').remove();
    });

    function checkTimeLineBeforeAdd(data,value){
        let datetime = [];
        if(value['from'] >= value['to']) return 1; // The From less than The To
        for (const [key, item] of Object.entries(data)) {
            if(item['day'] == value['day'] ){
                datetime.push(item);
            }
        }

        return checkTimeLineExst(datetime,value);
    }

    function checkTimeLineExst(data,value){
        for (const [key, $item] of Object.entries(data)) {
            let $from = value['from'],
                $to = value['to'];

            if ($item['from'] < $from && $from < $item['to']) {
                return 2;
            }

            if ($item['from'] < $to && $to < $item['to']) {
                return 2;
            }

            if ($from <= $item['from'] && $item['to'] <= $to) {
                return 2;
            }
        }

        return 0;
    }
</script>
