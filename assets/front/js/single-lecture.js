(function ($) {
	"use strict";

	var single = $("#single-lecture"),
		lecture_id = single.data('id'),
		row_countdown = $('.row.countdown'),
		countDownDate = 0;

	var x = setInterval(function () {
		if (countDownDate > 0) {
			row_countdown.removeClass('d-none');
			var now = new Date().getTime();
			// Find the distance between now and the count down date
			var distance = countDownDate - now;

			// Time calculations for days, hours, minutes and seconds
			var days = Math.floor(distance / (1000 * 60 * 60 * 24));
			var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
			var seconds = Math.floor((distance % (1000 * 60)) / 1000);

			// Display the result in the element with id="demo"
			$('.number.day').html(days);
			$('.number.hour').html(hours);
			$('.number.minute').html(minutes);
			$('.number.second').html(seconds);

			// If the count down is finished, write some text
			if (distance < 0) {
				clearInterval(x);
				row_countdown.remove();
				// document.getElementById("demo").innerHTML = "EXPIRED";
			}
		}
	}, 1000);

	$.ajax({
		url: obj.ajax_url,
		type: 'GET',
		data: {
			'action': 'lecture_time_detail',
			'lecture': lecture_id
		},
		dataType: 'json',
		success: function (data) {
			if (data.detail_js > 0) { // it's special not recurring
				countDownDate = data.detail_js;
			}
		}
	});

})(jQuery); // End of use strict