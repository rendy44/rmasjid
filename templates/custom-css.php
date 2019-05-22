<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 5/1/2019
 * Time: 5:20 PM
 *
 * @package Masjid/Components
 */

use Masjid\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$solid_color          = Helpers\Helper::get_solid_main_color();
$solid_color_rgb      = Helpers\Helper::hex2rgb( $solid_color );
$background_image_url = Helpers\Helper::get_default_background_image_url()
?>

<style type="text/css">

	a {
		color: <?php echo $solid_color; ?>;
	}

	a:hover {
		color: <?php echo Helpers\Helper::darken_color( $solid_color, 1.2 ); ?>;
	}

	input:active, input:focus, select:active, select:focus, textarea:active, textarea:focus {
		border-color: <?php echo $solid_color; ?> !important;
	}

	.custom-control-input:not(:disabled):active ~ .custom-control-label::before {
		border-color: rgba(<?php echo $solid_color_rgb['r'] . ', ' . $solid_color_rgb['g'] . ', ' . $solid_color_rgb['b'] . ', .7'; ?>) !important;
		background-color: rgba(<?php echo $solid_color_rgb['r'] . ', ' . $solid_color_rgb['g'] . ', ' . $solid_color_rgb['b'] . ', .7'; ?>) !important;
	}

	.custom-control-input:checked ~ .custom-control-label::before {
		border-color: <?php echo $solid_color; ?> !important;
		background-color: <?php echo $solid_color; ?> !important;
	}

	#mainNav .navbar-nav .nav-item.active .nav-link, #mainNav .navbar-nav .nav-item .nav-link.active, #mainNav .navbar-nav .nav-item .nav-link:hover {
		color: <?php echo $solid_color; ?>;
	}

	#mainNav .navbar-toggler .icon-bar {
		background-color: <?php echo $solid_color; ?>;
	}

	#mainNav .navbar-brand {
		color: <?php echo $solid_color; ?>;
	}

	#mainNav .navbar-brand:hover {
		color: <?php echo Helpers\Helper::darken_color( $solid_color, 1.2 ); ?>;
	}

	li.page-item.active .page-link {
		background-color: <?php echo $solid_color; ?> !important;
		border-color: <?php echo $solid_color; ?> !important;
	}

	li.page-item .page-link {
		color: <?php echo $solid_color; ?> !important;
	}

	ul.social-buttons li a:active, ul.social-buttons li a:focus, ul.social-buttons li a:hover {
		background-color: <?php echo $solid_color; ?>;
	}

	.details li {
		border-color: <?php echo $solid_color; ?>;
	}

	.details li i {
		color: <?php echo $solid_color; ?>;
	}

	.post_detail {
		border-color: <?php echo $solid_color; ?>;
	}

	.timeline > li .timeline-image {
		background-color: <?php echo $solid_color; ?>;
	}

	.row.countdown .row.units .col .inner {
		background-color: <?php echo $solid_color; ?>;
	}

	section.masthead {
		/*background-image: linear-gradient(to top right, rgba(*/<?php // echo $solid_color_rgb['r'] .', ' . $solid_color_rgb['g'] .', ' . $solid_color_rgb['b']; ?>/*, 0.8) 10%, rgba(150, 150, 150, 0.9) 90%), url(*/<?php // echo $background_image_url; ?>/*);*/
	}

	.carousel-item-wrapper {
		<?php echo Helpers\Helper::get_header_style_inline( '', true ); ?>
	}

	.btn-outline-primary {
		border-color: <?php echo $solid_color; ?>;
		color: <?php echo $solid_color; ?>;
	}

	.btn-outline-primary:active, .btn-outline-primary:focus, .btn-outline-primary:hover {
		border-color: <?php echo $solid_color; ?> !important;
		background-color: <?php echo $solid_color; ?> !important;
	}

	.btn-primary:disabled, .btn-primary.disabled {
		background-color: <?php echo $solid_color; ?>;
		border-color: <?php echo $solid_color; ?>;
	}

	.btn-primary {
		background-color: <?php echo $solid_color; ?>;
		border-color: <?php echo $solid_color; ?>;
	}

	.btn-primary:hover, .btn-primary:active, .btn-primary:focus, .btn-primary:not(:disabled):not(.disabled):active, .btn-primary:not(:disabled):not(.disabled):focus {
		background-color: <?php echo Helpers\Helper::darken_color( $solid_color, 1.2 ); ?>;
		border-color: <?php echo Helpers\Helper::darken_color( $solid_color, 1.2 ); ?>;
	}

	.text-primary {
		color: <?php echo $solid_color; ?> !important;
	}

	.progress .progress-bar {
		background-color: <?php echo $solid_color; ?>;
	}

	footer ul.social-buttons li a:active, footer ul.social-buttons li a:focus, footer ul.social-buttons li a:hover {
		background-color: <?php echo $solid_color; ?>;
	}

	.notfound h1 {
		color: <?php echo $solid_color; ?>;
	}

	.bg-primary {
		background-color: <?php echo Helpers\Helper::darken_color( $solid_color, 2 ); ?> !important;
	}

	footer.style2.bg-primary ul.quicklinks li ul.sub-items li a {
		background-color: <?php echo Helpers\Helper::darken_color( $solid_color, 2 ); ?> !important;
	}

	footer.bg-primary .network {
		background-color: <?php echo Helpers\Helper::darken_color( $solid_color, 1.8 ); ?> !important;
	}

    footer.style1 hr {
        border-color: <?php echo $solid_color; ?> !important;
    }
</style>
