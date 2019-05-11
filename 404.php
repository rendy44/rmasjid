<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/26/2019
 * Time: 11:01 PM
 *
 * @package Masjid/Pages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $temp; ?>

<?php get_header(); ?>

<div class="row">
	<div class="col-lg-8 mx-auto notfound text-center">
		<h1>404</h1>
		<h2><?php echo __( 'Oops! Nothing was found', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		<p>
			<?php echo __( 'The page you are looking for might have been removed had its name changed or is temporarily unavailable', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</p>
		<a href="<?php echo home_url(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" class="btn btn-outline-primary">
			<?php echo __( 'Back to Homepage', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
	</div>
</div>

<?php get_footer(); ?>
