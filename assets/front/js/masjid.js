(function ($) {
	"use strict"; // Start of use strict

	$('ul.quicklinks li a ').click(function (e) {
		e.preventDefault();
	});

	var body = document.body;
	var burgerMenu = document.getElementsByClassName('b-menu')[0];
	var burgerContain = document.getElementsByClassName('b-container')[0];
	var burgerNav = document.getElementsByClassName('b-nav')[0];

	burgerMenu.addEventListener('click', function toggleClasses() {
		[body, burgerContain, burgerNav].forEach(function (el) {
			el.classList.toggle('open');
		});
	}, false);

	// Smooth scrolling using jQuery easing
	$('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function () {
		if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
			if (target.length) {
				$('html, body').animate({
					scrollTop: (target.offset().top - 54)
				}, 1000, "easeInOutExpo");
				return false;
			}
		}
	});

	// Collapse Navbar
	var navbarCollapse = function () {
		if ($(".navbar2 .b-container").offset().top > 100) {
			$(".navbar2 .b-container").addClass("shrink");
		} else {
			$(".navbar2 .b-container").removeClass("shrink");
		}
	};
	// Collapse now if page is not at top
	navbarCollapse();
	// Collapse the navbar when page is scrolled
	$(window).scroll(navbarCollapse);

	// Switch salat times
	var opt = ['fajr', 'shurooq', 'dhuhr', 'asr', 'maghrib', 'isha'],
		salat_section = $('#salat'),
		sky = salat_section.find('.bg-sky');
	var counter = 0,
		interval = setInterval(function () {
			var current_class = sky.attr('class'),
				current_class_arr = current_class.split(' '),
				val_current_class = current_class_arr[1];
			console.log(val_current_class);
			if (counter === 5) {
				counter = 0;
			} else {
				counter++;
			}
			var next_class = opt[counter];
			$(sky).removeClass(val_current_class).addClass(next_class);
		}, 5000);

	// Back to top
	var btn_back_to_top = $('.btn-back-to-top');

	$(window).scroll(function () {
		if ($(window).scrollTop() > 400) {
			btn_back_to_top.fadeIn();
		} else {
			btn_back_to_top.fadeOut();
		}
	});
	btn_back_to_top.on('click', function (e) {
		e.preventDefault();
		$('html, body').animate({scrollTop: 0}, 800);
	});
})(jQuery); // End of use strict
