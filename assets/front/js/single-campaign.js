(function ($) {
	"use strict";

	// button donate now being clicked
	$('.btn-pay').click(function (e) {
		e.preventDefault();
		var me = $(this),
			parent = me.closest('#single-campaign'),
			campaign_id = parent.data('id'),
			me_text = me.html(),
			me_loading = '<i class="fa fa-circle-notch fa-spin"></i> Loading...';
		me.prop('disabled', true).html(me_loading);

		$.ajax({
			url: obj.ajax_url,
			type: 'POST',
			data: {
				'action': 'campaign_pay',
				'campaign_id': campaign_id
			},
			dataType: 'json',
			success: function (data) {
				if ('success' !== data.status) {
					me.prop('disabled', false).html(me_text);
					swal(obj.message.sorry, data.message, 'error');
				} else {
					location.href = data.callback;
				}
			}
		});
	});

})(jQuery); // End of use strict