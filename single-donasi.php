<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/21/2019
 * Time: 11:25 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $temp, $designer;

if ( has_post_thumbnail() ) {
	// remove original header.
	remove_action( 'header_content', [ $designer, 'maybe_small_header_callback' ], 30 );

	// replace with a new header.
	add_action( 'header_content', function () use ( $temp ) {
		echo esc_html( apply_filters( 'small_header_content', get_post_thumbnail_id(), get_the_title() ) );
	}, 30 );
}

$count_campaign        = MaHelper::count_campaign( MaHelper::pfield( 'main_detail_target' ), MaHelper::pfield( 'main_detail_collected' ), MaHelper::pfield( 'main_detail_due_date' ) );
$is_campaign_available = MaHelper::is_campaign_available( get_the_ID() );

get_header();

while ( have_posts() ) {
	the_post();
	?>

    <div class="row mb-3" id="single-campaign" data-id="<?php the_ID(); ?>">
        <div class="col-md-4">
            <h3>Rp<?php echo esc_html( $count_campaign['collected_format'] ); ?></h3>
            <p class="big"><?php echo esc_html__( 'collected from total', 'masjid' ) . ' Rp' . esc_html( $count_campaign['target_format'] ); ?></p>

            <!-- Render progress bar -->
            <div class="mb-2">
				<?php
				echo $temp->render( 'progress-bar', [
						'height' => 7,
						'value'  => $count_campaign['collected_percent'],
					] );
				?>
            </div>

            <div class="row">
                <div class="col text-left">
                    <p><?php echo esc_html( $count_campaign['collected_percent'] ) . '% ' . esc_html__( 'collected', 'masjid' ); ?></p>
                </div>
                <div class="col text-right">
                    <p><?php echo esc_html( ! empty( $count_campaign['duedate'] ) ? $count_campaign['duedate_html_single'] : '' ); ?></p>
                </div>
            </div>

			<?php if ( $is_campaign_available ) { ?>
                <button type="button"
                        class="btn btn-primary btn-lg btn-block text-uppercase mb-2 btn-pay"><?php echo esc_html__( 'Donate Now', 'masjid' ); ?></button>
			<?php } else { ?>
                <button type="button"
                        class="btn btn-primary btn-lg btn-block text-uppercase mb-2"
                        disabled><?php echo esc_html__( 'Campaign is Closed', 'masjid' ); ?></button>
			<?php } ?>

            <button type="button"
                    class="btn btn-info btn-lg btn-block text-uppercase mb-4"><?php echo esc_html__( 'Share', 'masjid' ); ?></button>
            <p><?php echo __( 'Campaign was started at', 'masjid' ) . ' ' . get_the_date() . ' ' . __( 'by', 'masjid' ) . ' ' . get_the_author_posts_link() . '. ' . ( get_the_date() === get_the_modified_date() ? esc_html__( 'And since then, there is no change has been made', 'masjid' ) : esc_html__( 'And the last update was at', 'masjid' ) . ' ' . get_the_modified_date() ); ?></p>
        </div>
        <div class="col-md-8">
			<?php
			if ( has_post_thumbnail() ) {
				echo '<img class="mx-auto mb-4 d-block img-fluid featured" src="' . get_the_post_thumbnail_url() . '">';
				echo '<p class="lead text-justify">' . MaHelper::pfield( 'main_excerpt' ) . '</p>';
			};
			?>
        </div>
    </div>
    <div class="row row-eq-height">
        <div class="col-md-4 mb-4">
            <h4 class="font-weight-light"><?php echo esc_html__( 'Payment History', 'masjid' ); ?></h4>
            <hr class="mb-4"/>
            <div class="mb-2">
				<?php
				$query_payment_history = MaHelper::setup_query( 1, 'bayar', [
					'relation' => 'and',
					[
						'key'   => 'status',
						'value' => 'done',
					],
					[
						'key'   => 'campaign_id',
						'value' => get_the_ID(),
					],
				] );
				if ( $query_payment_history->have_posts() ) {
					$date_format     = get_option( 'date_format' );
					$time_format     = get_option( 'time_format' );
					$datetime_format = $date_format . ' ' . $time_format;

					while ( $query_payment_history->have_posts() ) {
						$query_payment_history->the_post();
						echo $temp->render( 'history-payment-list', [
								'total_formatted_amount' => number_format( (int) MaHelper::pfield( 'total_amount' ), 0, ',', '.' ),
								'name'                   => MaHelper::pfield( 'name' ),
								'hide_name'              => (bool) MaHelper::pfield( 'hide_name' ),
								'beautify_datetime'      => date( $datetime_format, MaHelper::pfield( 'validation_datetime' ) ),
								'message'                => MaHelper::pfield( 'message' ),
							] );
					}
				} else {
					get_template_part( '/templates/history-payment', '404' );
				}
				wp_reset_postdata();
				?>
            </div>
        </div>
        <div class="col-md-8">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab"
                       href="#detail_tab"><?php echo esc_html__( 'Detail', 'masjid' ); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab"
                       href="#gallery_tab"><?php echo esc_html__( 'Gallery', 'masjid' ); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab"
                       href="#comment_tab"><?php echo esc_html__( 'Comment', 'masjid' ); ?></a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane container active py-4" id="detail_tab">
					<?php echo MaHelper::pfield( 'main_content' ); ?>
                </div>
                <div class="tab-pane container fade py-4" id="gallery_tab">
                    <div class="row text-center text-lg-left">
						<?php
						$galleries = MaHelper::pfield( 'main_images_gallery' );
						if ( $galleries ) {
							foreach ( $galleries as $gall_id => $gallery ) {
								echo $temp->render( 'gallery-item-list', [
										'large_url'     => $gallery,
										'thumbnail_url' => wp_get_attachment_image_url( $gall_id ),
									] );
							}
						} else {
							echo $temp->render( 'gallery-item-404' );
						};
						?>
                    </div>
                </div>
                <div class="tab-pane container fade py-4" id="comment_tab"></div>
            </div>
        </div>
    </div>

	<?php
}

get_footer();
