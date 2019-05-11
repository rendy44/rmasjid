<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/20/2019
 * Time: 8:45 AM
 *
 * @package Masjid/Pages
 */

/*Template name: Lecture Page */
__( 'Lecture Page', 'masjid' );

use Masjid\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $temp;
get_header();
while ( have_posts() ) {
	the_post();
	?>

	<div class="row mb-5">
		<div class="col">
			<?php
			$slider_items        = [];
			$query_last_lectures = Helpers\Helper::setup_query( 1, 'kajian', [], 5 );
			if ( $query_last_lectures->have_posts() ) {
				while ( $query_last_lectures->have_posts() ) {
					$query_last_lectures->the_post();
					$slider_items[] = $temp->render(
						'slider-item',
						[
							'id'                    => get_the_ID(),
							'cover_url'             => Helpers\Helper::get_thumbnail_url( get_the_ID() ),
							'title'                 => get_the_title(),
							'permalink'             => get_permalink(),
							'short_description_fix' => Helpers\Helper::get_lecture_desc_datetime( get_the_ID(), true ),
						]
					);
				}
			} else {
				?>
				<div class="ma-auto">
					<?php get_template_part( '/templates/lecture', '404' ); ?>
				</div>
				<?php
			}
			wp_reset_postdata();
			echo $temp->render( 'carousel', [ 'items' => $slider_items ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</div>
	</div>
	<div id="fcalendar"></div>
	<?php
}
get_footer();
