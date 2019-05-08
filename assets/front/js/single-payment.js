(function ($) {
	"use strict";

	// form continue payment submitted
	$('.frmPay').validate({
		focusInvalid: true,
		errorPlacement: function (error, element) {
			if (element.parent('.form-group').length) {
				error.insertBefore(element.parent());
			} else if (element.parent('.input-group').length) {
				error.insertBefore(element.parent());
			}
		},
		submitHandler: function (form, e) {
			e.preventDefault();
			var data = $(form).serializeArray(),
				btn = $(form).find('.btn-pay-continue'),
				btn_text = btn.html(),
				btn_loading = '<i class="fa fa-circle-notch fa-spin"></i> Loading...';

			btn.prop('disabled', true).html(btn_loading);

			$.ajax({
				url: obj.ajax_url,
				type: 'POST',
				data: {
					'action': 'campaign_pay_continue',
					'data': data
				},
				dataType: 'json',
				success: function (data) {
					if ('success' !== data.status) {
						btn.prop('disabled', false).html(btn_text);
						swal(obj.message.sorry, data.message, 'error');
					} else {
						location.reload();
					}
				}
			});
		}
	});

	// styling amount
	$('h1.amount').each(function () {
		$(this).html(
			$(this).html().substr(0, $(this).html().length - 3)
			+ "<span style='color: #856404; background: #ffeeba'>"
			+ $(this).html().substr(-3)
			+ "</span>");
	});

	// button confirming payment being clicked
	$('.btn-go-conf').click(function (e) {
		e.preventDefault();
		var me = $(this),
			parent = me.closest('#single-payment'),
			payment_id = parent.data('id'),
			me_text = me.html(),
			me_loading = '<i class="fa fa-circle-notch fa-spin"></i> Loading...';
		me.prop('disabled', true).html(me_loading);

		$.ajax({
			url: obj.ajax_url,
			type: 'POST',
			data: {
				'action': 'campaign_pay_confirm',
				'payment_id': payment_id
			},
			dataType: 'json',
			success: function (data) {
				if ('success' !== data.status) {
					me.prop('disabled', false).html(me_text);
					swal(obj.message.sorry, data.message, 'error');
				} else {
					location.reload();
				}
			}
		});
	});

})(jQuery); // End of use strict