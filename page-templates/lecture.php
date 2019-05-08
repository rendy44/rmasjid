<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/20/2019
 * Time: 8:45 AM
 */

/*Template name: Lecture Page */
__( 'Lecture Page', 'masjid' );

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
			$query_last_lectures = MaHelper::setup_query( 1, 'kajian', [], 5 );
			if ( $query_last_lectures->have_posts() ) {
				while ( $query_last_lectures->have_posts() ) {
					$query_last_lectures->the_post();
					$slider_items[] = $temp->render( 'slider-item', [
						'id'                    => get_the_ID(),
						'cover_url'             => MaHelper::get_thumbnail_url( get_the_ID() ),
						'title'                 => get_the_title(),
						'permalink'             => get_permalink(),
						'short_description_fix' => MaHelper::get_lecture_desc_datetime( get_the_ID(), true ),
					] );
				}
			} else {
				?>
                <div class="ma-auto">
					<?php get_template_part( '/templates/lecture', '404' ); ?>
                </div>
			<?php }
			wp_reset_postdata();
			echo $temp->render( 'carousel', [ 'items' => $slider_items ] );
			?>
        </div>
    </div>
    <div id="fcalendar"></div>
	<?php
}

get_footer();
