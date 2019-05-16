<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 11:54 PM
 *
 * @package Masjid/Single
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $temp, $designer;
if ( has_post_thumbnail() ) {
	// remove original header.
	remove_action( 'header_content', [ $designer, 'maybe_small_header_callback' ], 30 );
	// replace with a new header.
	add_action(
		'header_content',
		function () use ( $temp ) {
			echo esc_html( apply_filters( 'small_header_content', get_post_thumbnail_id(), get_the_title() ) );
		},
		30
	);
}
get_header();
while ( have_posts() ) {
	the_post();
	?>

	<div class="row">
		<div class="col-xl-9 col-lg-10 col-md-11 mx-auto text-justify">
			<p class="text-muted text-center">
				<?php echo __( 'Published by', 'masjid' ) . ' ' . get_the_author_posts_link() . ' ' . esc_html__( 'at', 'masjid' ) . ' ' . get_the_date(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</p>
			<?php
			// Featured image.
			echo has_post_thumbnail() ? '<img src="' . get_the_post_thumbnail_url() . '" class="img-fluid mx-auto d-block mb-5">' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			// Content.
			the_content();
			// Comments.
			if ( comments_open() ) {
				comments_template();
			}
			?>
			<div class="post_detail">
				<p class="text-muted">
					<?php echo '<i class="fas fa-folder"></i> : ' . get_the_category_list( ', ' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</p>
				<p class="text-muted">
					<?php echo '<i class="fas fa-tag"></i> : ' . get_the_tag_list( '', ', ' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<p class="text-muted">
					<?php echo '<i class="fas fa-comment"></i> : <a href="#" class="load-comments" data-id="' . get_the_ID() . '"> ' . ( get_comments_number() > 0 ? get_comments_number() . __( ' comment', 'masjid' ) : __( "There's no comment", 'masjid' ) ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</p>
			</div>
		</div>
	</div>

	<?php
}
get_footer();
