<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/20/2019
 * Time: 8:46 AM
 */

/*Template name: Campaign Page */
__( 'Campaign Page', 'masjid' );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//delete_transient('query_latest_campaigns');
global $temp, $designer; ?>

<?php get_header(); ?>

<?php
while ( have_posts() ) {
	the_post();
	?>

	<?php $available_campaigns = MaHelper::count_available_campaign(); ?>
    <div class="row mb-5">
		<?php
		if ( $available_campaigns['items'] ) {
			?>
            <div class="col-md-4 campaign-summary">
                <h3 class="mb-lg-4 mb-md-2 mb-sm-0">Mari Salurkan Bantuan Anda Melalui Program Kami</h3>
                <p class="big">Insya Allah selalu jujur dan terpercaya dalam menjaga amanah</p>
                <div class="sticky-info">
					<?php
					echo $temp->render( 'progress-bar', [
							'height' => 25,
							'value'  => $available_campaigns['sum']['collected_percent'],
						] );
					?>
                    <p class="text-muted text-center">
                        <small><?php echo __( 'Collected', 'masjid' ) . ' ' . $available_campaigns['sum']['collected_percent'] . '% ' . __( 'from total', 'masjid' ) . ' Rp' . $available_campaigns['sum']['target_format']; ?></small>
                    </p>
                </div>
            </div>
            <div class="col-md-8">
				<?php $slider_items = [];
				foreach ( $available_campaigns['items'] as $campaign ) {
					$slider_items[] = $temp->render( 'slider-item', $campaign );
				}
				echo $temp->render( 'carousel', [ 'items' => $slider_items ] );
				?>
            </div>
			<?php
		} else { ?>
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
				$calculation_detail = MaHelper::count_campaign( MaHelper::pfield( 'main_detail_target', $campaign_id ), MaHelper::pfield( 'main_detail_collected', $campaign_id ), MaHelper::pfield( 'main_detail_due_date', $campaign_id ) );
				echo $temp->render( 'donasi-list', [
						'cover_url'         => $campaign['cover_url'],
						'title'             => $campaign['title'],
						'short_description' => $campaign['short_description_fix'],
						'permalink'         => $campaign['permalink'],
						'collected_percent' => $calculation_detail['collected_percent'],
						'collected_format'  => $calculation_detail['collected_format'],
						'duedate_html'      => $calculation_detail['duedate_html'],
					] );
			}
			?>
        </div>
		<?php
	}
}
?>

<?php get_footer(); ?>
