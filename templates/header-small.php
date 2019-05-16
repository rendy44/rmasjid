<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 5:06 PM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* translators: %s: search term */
$header_title = ! isset( $header_title ) ? ( is_archive() ? get_the_archive_title() : ( is_search() ? sprintf( __( 'Search Results for "%s"', 'masjid' ), get_search_query() ) : single_post_title( '', false ) ) ) : $header_title;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
$image_url    = isset( $image_url ) ? 'style="background-image: linear-gradient(to top right, rgba(30, 30, 30, .6), rgba(0, 0, 0, .8)), url(' . $image_url . ')"' : '';
$subcontent   = isset( $subcontent ) ? '<p class="text-center lead">' . esc_html( $subcontent ) . '</p>' : '';
?>

<section class="masthead small" <?php echo $image_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="container">
		<div class="row">
			<div class="col-lg-8 mx-auto">
				<div class="intro-text">
                    <div class="intro-lead-in text-uppercase"><?php echo wp_strip_all_tags( $header_title, true ); // phpcs:ignore ?></div>
					<?php echo $subcontent; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>
	</div>
</section>
