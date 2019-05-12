<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/20/2019
 * Time: 8:46 AM
 *
 * @package Masjid/Pages
 */
/*Template name: Campaign Page */
__( 'Campaign Page', 'masjid' );

use Masjid\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $temp, $designer; ?>

<?php get_header(); ?>

<?php
while ( have_posts() ) {
	the_post();
	?>

	<?php $available_campaigns = Helpers\Helper::count_available_campaign(); ?>
	<div class="row mb-5">
		<?php
		if ( $available_campaigns['items'] ) {
			?>
			<div class="col-md-4 campaign-summary">
				<h3 class="mb-lg-4 mb-md-2 mb-sm-0"><?php echo __( 'Share your careness through our campaign programs!', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
				<p class="big"><?php echo __( 'Insha Allah we will always be honest and trusted to taking care your trust.', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<div class="sticky-info">
					<?php
					$campaign_progress_bar = $temp->render(
						'progress-bar',
						[
							'height' => 25,
							'value'  => $available_campaigns['sum']['collected_percent'],
						]
					);
					echo $campaign_progress_bar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
					<p class="text-muted text-center">
						<small><?php echo __( 'Collected', 'masjid' ) . ' ' . $available_campaigns['sum']['collected_percent'] . '% ' . __( 'from total', 'masjid' ) . ' Rp' . $available_campaigns['sum']['target_format']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></small>
					</p>
				</div>
			</div>
			<div class="col-md-8">
				<?php
				$slider_items = [];
				foreach ( $available_campaigns['items'] as $campaign ) {
					$slider_items[] = $temp->render( 'slider-item', $campaign );
				}
				echo $temp->render( 'carousel', [ 'items' => $slider_items ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
			<?php
		} else {
			?>
			<div class="col mx-auto">
				<?php get_template_part( '/templates/donasi', '404' ); ?>
			</div>
			<?php
		}
		?>
	</div>

	<?php
	if ( ! empty( $available_campaigns['items'] ) ) {
		?>
		<div class="row">
			<?php
			foreach ( $available_campaigns['items'] as $campaign ) {
				$campaign_id        = $campaign['id'];
				$calculation_detail = Helpers\Helper::count_campaign( Helpers\Helper::pfield( 'main_detail_target', $campaign_id ), Helpers\Helper::pfield( 'main_detail_collected', $campaign_id ), Helpers\Helper::pfield( 'main_detail_due_date', $campaign_id ) );
				$campaign_result    = $temp->render(
					'donasi-list',
					[
						'cover_url'         => $campaign['cover_url'],
						'title'             => $campaign['title'],
						'short_description' => $campaign['short_description_fix'],
						'permalink'         => $campaign['permalink'],
						'collected_percent' => $calculation_detail['collected_percent'],
						'collected_format'  => $calculation_detail['collected_format'],
						'duedate_html'      => $calculation_detail['duedate_html'],
					]
				);
				echo $campaign_result; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</div>
		<?php
	}
}
?>

<?php get_footer(); ?>
