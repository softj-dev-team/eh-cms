//---Open dropdown container----//
function handle_dropdown() {

    const $dropContainer = $('.dropdownPanel .dropdown-container');

    $(document.body).on('click', '.dropdownPanel a.settingColor', function (e) {
        e.preventDefault();
        const $itContainer = $(this).parent().find('.dropdown-container');
        $('.info-schedule-popup').addClass('d-none');
        // $itContainer.addClass('isOpen');
        if (!$itContainer.hasClass('isOpen')) {
            $dropContainer.removeClass('isOpen');
            $itContainer.addClass('isOpen');
        } else {
            $itContainer.removeClass('isOpen');
        }
    });

    //--click outside
    let isMouseOutside = false;
    $(document.body).on('mouseenter', '.dropdownPanel', function (e) {
        isMouseOutside = false;
    })
        .on('mouseleave', '.dropdownPanel', function (e) {
            isMouseOutside = true;
        });

    $(document.body).on('click', function () {
        if (isMouseOutside) {
            $('.dropdownPanel .dropdown-container').removeClass('isOpen');
            $('.isSetting .selectColor').addClass('d-none');
        }
    });
}
handle_dropdown();


//--timetable
function info_schedule_showing() {
    $(document.body).on('click', '.timetable__content-task', function (e) {
        const $it = $(e.currentTarget);
        let $schedule_id = $('#schedule_id').val();
        let $id =  $it
        .closest('.timetable__content-day')
        .find('.timetable__content-task')
            .data('id');

        if ($it.parent().find('.info-schedule-popup').length < 1) {
            getDataTimeLine($id, $schedule_id);
        }

        const data = $(e.currentTarget).data();
        const $template = $(data.template);
        const $html = $($template.html());


        //--check generate once time
        if ($it.parent().find('.info-schedule-popup').length < 1) {

            $html.insertAfter($it);

            var $color = $it
                .closest('.timetable__content-day')
                .find('.timetable__content-task')
                .data('color');

            $html
                .closest('.info-schedule-popup')
                .find('.info-head')
                .css({ backgroundColor: $color });

        }
        //Open select color panel
        $html.find('.colorPicker').on('click', function (e) {
            $html.find('.selectColor').removeClass('d-none');
        });
        //pick color background
        $html.find('.selectColor .themeColorBox').on('click', function (e) {
            const $it = $(e.currentTarget);
            const color = $it.data('color');
            const _id = $it
                .closest('.timetable__content-day')
                .find('.timetable__content-task')
                .data('id');
            $('.isSetting .listBindingClass a.itemClass')
                .filter(`[data-target=${_id}]`)
                .find('span.colorClass')
                .css({ backgroundColor: color });
            $it
                .closest('.timetable__content-day')
                .find('.timetable__content-task')
                .css({ backgroundColor: color });
            $it
                .closest('.info-schedule-popup')
                .find('.info-head')
                .css({ backgroundColor: color });
            var group_color = $it
                .closest('.timetable__content-day')
                .find('.timetable__content-task')
                .data('group_color');

            if (group_color > 0) {
                $("*[data-group_color='" + group_color + "']")
                    .css({ backgroundColor: color });

                $("*[data-group_color='" + group_color + "']")
                    .attr('data-color', color);

                $("*[data-group_color='" + group_color + "']")
                    .closest('.timetable__content-day')
                    .find('.info-schedule-popup .info-head')
                    .css({ backgroundColor: color });
            }
            saveColor(_id, color);
            $it.closest('.selectColor').addClass('d-none');
        });
        //close color picker panel
        $html
            .find('.selectColor .top .closePanel')
            .on('click', () => $html.find('.selectColor').addClass('d-none'));
        $('.info-schedule-popup').addClass('d-none');
        $it.parent().find('.info-schedule-popup').removeClass('d-none');
    });
    //close info schedule panel
    $(document.body).on('click', '.info-schedule-popup .closeInfo', function (e) {
        const $it = $(e.currentTarget);
        $it.closest('.info-schedule-popup').addClass('d-none');
        $('.selectColor').addClass('d-none');
    });
}
info_schedule_showing();
function setting_color() {
    //click to show color picker
    $(document.body).on(
        'click',
        '.dropdownPanel ul.listBindingClass li a.itemClass',
        function (e) {
            e.preventDefault();
            const $it = $(e.currentTarget);
            $('.selectColor').addClass('d-none');
            $it.parent().find('.selectColor').removeClass('d-none');
        },
    );
    //click to close color picker
    $(document.body).on('click', '.selectColor .top .closePanel', function (e) {
        e.preventDefault();
        $(this).closest('.selectColor').addClass('d-none');
    });
    //set color for each class
    $(document.body).on(
        'click',
        '.isSetting .selectColor .themeColorBox',
        function (e) {
            const $it = $(e.currentTarget);
            const color = $it.data('color');
            const _id = $it.closest('li').find('a.itemClass').data('target');
            $it.closest('li').find('span.colorClass').css({ backgroundColor: color });
            $('.timetable__content-task')
                .filter(`[data-id=${_id}]`)
                .css({ backgroundColor: color });
            $('.timetable__content-task')
                .filter(`[data-id=${_id}]`)
                .closest('.timetable__content-day')
                .find('.info-schedule-popup .info-head')
                .css({ backgroundColor: color });

            var group_color = $it
                .closest('li').find('span.colorClass')
                .data('group_color');
            if (group_color > 0) {
                $("*[data-group_color='" + group_color + "']")
                    .css({ backgroundColor: color });

                $("*[data-group_color='" + group_color + "']")
                    .attr('data-color', color);

                $("*[data-group_color='" + group_color + "']")
                    .closest('.timetable__content-day')
                    .find('.info-schedule-popup .info-head')
                    .css({ backgroundColor: color });
            }
            saveColor(_id, color);
            $it.closest('.selectColor').addClass('d-none');
        },
    );
}
setting_color();
function slide_content() {
    //-slide content
    const $navContent = $('.sidebar-template .nav-content');
    const varNum = $('#campusContent #active_filter').val();

    var mySwiper = new Swiper('#campusContent .swiper-container', {
        initialSlide: varNum,
        slidesPerView: 1,
        navigation: {
            nextEl: '#campusContent .swiper-button-next',
            prevEl: '#campusContent .swiper-button-prev',
        },
        simulateTouch: false,
    });
    mySwiper.on('slideChange', function () {
        // console.log(mySwiper.activeIndex + 1);
        $navContent.addClass('d-none');
        $('.sidebar-template .nav-content')
            .filter(`[data-index=${mySwiper.activeIndex + 1}]`)
            .removeClass('d-none');
    });
}
slide_content();

$(document.body).on('click', '.screenShotEl', function (e) {
    $(this).closest('.dropdown-container').removeClass('isOpen');
    html2canvas(document.querySelector('.timetable__content')).then((canvas) => {
        document.getElementById('screenShotmodal').appendChild(canvas);
        document.getElementById('screenShotmodal').showModal();
    });
});
$(document.body).on('click', 'dialog#screenShotmodal .closePanel a', function (
    e,
) {
    e.preventDefault();
    document.getElementById('screenShotmodal').close();
    $('#screenShotmodal canvas').remove();
});


function saveColor($id, $color) {
    var $this = $(this);
    $this
        .find();
    if ($this.data('isRunAjax') == true) { return; }
    $this.css('pointer-events', 'none').data('isRunAjax', true);
    $.ajax({
        type: 'POST',
        url: $('#route_save_color').attr('content'),
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            'id': $id,
            'color': $color,
        },
        success: function (data) {
            console.log('save sussess');
        }
    }).always(function () {
        $this.css('pointer-events', 'auto').data('isRunAjax', false);
    });
}

$(document.body).on('submit', '#settingForm', function (e) {
    e.preventDefault();
    $timeFrom = parseInt($('[name="config[time][from]" ]').val());
    $timeTo = parseInt($('[name="config[time][to]" ]').val());

    $dayFrom = parseInt($('[name="config[day][from]" ]').val());
    $dayTo = parseInt($('[name="config[day][to]" ]').val());

    if ( $timeFrom >= $timeTo ) {
        alert('Time to must be greater than Time from');
        return false;
    }

    if (  $dayFrom >  $dayTo ) {
        alert('Day to must be greater than or equal to date from');
        return false;
    }

    this.submit();

});


function getDataTimeLine($id, $schedule_id) {
    var $this = $(this);
    if ($this.data('isRunAjax') == true) { return false; }
    $this.css('pointer-events', 'none').data('isRunAjax', true);
    $.ajax({
        type: 'POST',
        url: $('#route_get_data_timeline').attr('content'),
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            'id': $id,
            'schedule_id': $schedule_id,
        },
        async: false,
        success: function (data) {
            let $template = $('#template-info-html');
            $template.html(data.template);
            return true;
        }
    }).always(function () {
        $this.css('pointer-events', 'auto').data('isRunAjax', false);
    });
}
