(function ($) {
    var drop_search = () => {
	var $drop = $("#frame-search__footer .description-dropdown");
	var $input = $('#frame-search__footer .search_panel .form_style .form-line input[type="text"]');
	var result = [];

	var key_down = () => {
		$input.on('keyup', function () {
			if (event.keyCode === 8) {
				result = [];
			}
		})
	}
	$input.on('focus', function () {
		$drop.removeClass('d-none');
		key_down();
	}).on('blur', function () {
		$drop.addClass('d-none');
	});

	// selected
	var $sel = $("#frame-search__footer select.classsearchhelper");

	$sel.on('change', function () {
		let item = $sel.find('option:selected').val();
		result.push(item);
		$input.val(result);
	});
}
var hover_line_schedule = () => {
	var $line = $("#frame-search__footer .table_panel table tbody tr");
	var isDay = [];
	var isStart = [];
	var isEnd = [];
	var valStart = [];
	var valEnd = [];
	var list_time = [];
	var a_time = null;
	var json = [];
	var $time_content = null;
	var obj = [];
	var objTime = [];

	var $subject = $line.find('a.subject');
	var $story_btn = $line.find('.story_btn');
	// var json = $line.data("json");
	$subject.on('click', function () {
		let json_cell = $(this).closest('tr').data('json');
		// console.log(json_cell);
	});
	$story_btn.on('click', function () {
		window.open('http://ewhaian.3forcom.net/campus/lecture-evaluation/3', '_blank');
	});
	$line.on('mouseenter', function (e) {
		//renew array json with new data
		list_time = [];

		var it = e.currentTarget;
		var thisDay = $(`.timetable__day`);

		//get data json for each row
		json = $(this).closest('tr').data('json');
		// console.log('json');
		//Loop json
		$.each(json, function (i, val) {
			// Loop day of week
			thisDay.each(function (iDay, vDay) {
				if ($(vDay).attr('data-day') == val.day) {
					isDay.push($(vDay));
					$time_content = $(vDay).find('.timetable__content-day');
					//Loop time of day
					$time_content.each(function (iTime, vTime) {
						// console.log($(vTime).attr('data-start'));
						if (parseInt($(vTime).attr('data-start')) == val.from) {
							isStart.push($(vTime));
							valStart.push(parseInt($(vTime).attr('data-start')));
						}
						if (parseInt($(vTime).attr('data-start')) == val.to) {
							isEnd.push($(vTime));
							valEnd.push(parseInt($(vTime).attr('data-start')));
						}
						// Initalize time aray
						list_time = [val.from];
						for (i = 1; i <= val.to - val.from; i++) {
							a_time = val.from + i;
							list_time.push(a_time);
						};
						//Check specific cell
						$.each(list_time, function (i, val) {
							$time_content.each(function (iDay, vDay) {
								if (parseInt($(vDay).attr('data-start')) === val) {
									$(vDay).css({
										"background": "rgba(51,51,51,0.6)",
										"border-bottom": "none"
									});
								}
							});
						});
					});
					//save time DOM in new array
					objTime.push($time_content);

				};
			});
			//save time array in new array
			obj.push(list_time);
		});
		// console.log(list_time);
	}).on('mouseleave', function (e) {
		$.each(objTime, function (iD, vDayArray) {
			vDayArray.each(function (iTime, vTime) {
				$.each(obj, function (index, value) {
					$.each(value, function (i, v) {
						if (parseInt($(vTime).attr('data-start')) === v) {
							$(vTime).css({
								"background": "",
								"border-bottom": "1px solid #e8e8e8"
							});
						};
					});
				})
			});
		})
	});
}
var split_pane = () => {
	$('div.split-pane').splitPane();
	$('button:first').on('click', function () {
		$('div.split-pane').splitPane('lastComponentSize', 200);
	});
	$('button:last').on('click', function () {
		$('div.split-pane').splitPane('firstComponentSize', 0);
	});
	}


    $(function () {
        split_pane();
        drop_search();
        // hover_line_schedule();
    })
})(jQuery);
