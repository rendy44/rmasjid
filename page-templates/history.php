<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 5/7/2019
 * Time: 4:03 AM
 *
 * @package Masjid/Pages
 */

/*Template name: History Page */
__( 'History Page', 'masjid' );

use Masjid\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) {
	the_post(); ?>

	<div class="row">
		<div class="col-lg-8 mx-auto">
			<p class="lead text-center mb-5"><?php echo esc_html( Helpers\Helper::pfield( 'content' ) ); ?></p>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<?php
			$timeline_num = 1;
			$timelines    = Helpers\Helper::pfield( 'timeline' );
			if ( ! empty( $timelines ) ) {
				?>
				<ul class="timeline">
					<?php
					foreach ( $timelines as $timeline ) {
						?>
						<li <?php echo 0 === $timeline_num % 2 ? 'class="timeline-inverted"' : ''; ?>>
							<div class="timeline-image">
								<?php echo ! empty( $timeline['image_id'] ) ? '<img class="rounded-circle img-fluid" src="' . wp_get_attachment_image_url( $timeline['image_id'] ) . '" alt="">' : '<h2>#' . $timeline_num . '</h2>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
							<div class="timeline-panel">
								<div class="timeline-heading">
									<h3><?php echo esc_html( ! empty( $timeline['period'] ) ? $timeline['period'] : '' ); ?></h3>
									<h4 class="subheading"><?php echo esc_html( ! empty( $timeline['title'] ) ? $timeline['title'] : '' ); ?></h4>
								</div>
								<div class="timeline-body">
									<p class="text-muted"><?php echo esc_html( ! empty( $timeline['description'] ) ? $timeline['description'] : '' ); ?></p>
								</div>
							</div>
						</li>
						<?php
						$timeline_num ++;
					}
					?>
					<li class="timeline-inverted">
						<div class="timeline-image">
							<h3><?php echo __( 'Now', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
						</div>
					</li>
				</ul>
				<?php
			}
			?>
		</div>
	</div>

	<?php
}

get_footer();
