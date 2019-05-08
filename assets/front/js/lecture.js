(function ($) {
	"use strict";

	$(document).ready(function () {
		$.ajax({
			url: obj.ajax_url,
			type: 'GET',
			data: {
				'action': 'lecture_archive'
			},
			dataType: 'json',
			success: function (data) {
				var events = [];
				$.each(data.items, function (x, y) {
					events.push(y)
				});

				$('#fcalendar').fullCalendar({
					defaultDate: obj.date_now,
					editable: false,
					eventLimit: true, // allow "more" link when too many events
					header: {
						left: 'title',
						center: '',
						right: 'prev,next'
					},
					events: events
				});
			}
		});
	});

})(jQuery); // End of use strict