<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 6:51 PM
 */

global $temp;

$campaign_id         = MaHelper::pfield( 'campaign_id' );
$count_campaign      = MaHelper::count_campaign( MaHelper::pfield( 'main_detail_target', $campaign_id ), MaHelper::pfield( 'main_detail_collected', $campaign_id ), MaHelper::pfield( 'main_detail_due_date', $campaign_id ) );
$payment_status      = MaHelper::pfield( 'status' );
$total_amount        = (int) MaHelper::pfield( 'total_amount' );
$amount              = (int) MaHelper::pfield( 'amount' );
$unique_amount       = MaHelper::pfield( 'unique_amount' );
$expiry_confirmation = MaHelper::pfield( 'expiry' );
$date_format         = get_option( 'date_format' );
$time_format         = get_option( 'time_format' );
$datetime_format     = $date_format . ' ' . $time_format;

if ( in_array( $payment_status, [ 'done', 'rejected' ] ) ) {
	wp_redirect( home_url() );
}

get_header();

while ( have_posts() ) {
	the_post();
	?>

    <div class="row mb-3" id="single-payment" data-id="<?php the_ID(); ?>">
		<?php if ( 'waiting_payment' == $payment_status ) { ?>
            <div class="col-lg-5 mb-5">
                <p><?php echo __( 'You are going to make a donation for:', 'masjid' ); ?></p>
                <div class="row">
                    <div class="col-3 col-md-2 col-lg-3">
                        <img class="img-fluid rounded" src="<?php echo MaHelper::get_thumbnail_url( $campaign_id ); ?>">
                    </div>
                    <div class="col-9 col-md-10 col-lg-9">
						<?php
						echo '<p><strong>' . get_the_title( $campaign_id ) . '</strong></p>';
						echo $temp->render( 'progress-bar', [
								'height' => 5,
								'value'  => $count_campaign['collected_percent'],
							] );
						echo '<p class="mt-2 d-none d-sm-block">' . MaHelper::pfield( 'main_excerpt', $campaign_id ) . '</p>'
						?>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
				<?php echo $temp->render( 'payment-form', [ 'id' => get_the_ID() ] ); ?>
            </div>
		<?php } else if ( 'waiting_confirmation' == $payment_status ) { ?>
            <div class="col-lg-6 col-md-8 mx-auto text-center">
				<?php
				// instruction
				echo $temp->render( 'payment-instruction', [
						'formatted_total_amount' => number_format( $total_amount, 0, ',', '.' ),
						'formatted_amount'       => number_format( $amount, 0, ',', '.' ),
						'unique_code'            => $unique_amount,
					] );
				// bank accounts
				echo $temp->render( 'bank-accounts', [
						'expiry_beautify' => date( $datetime_format, $expiry_confirmation ),
						'banks'           => MaHelper::get_bank_accounts(),
					] );
				// confirmation
				echo $temp->render( 'payment-confirmation' )
				?>
            </div>
		<?php } else if ( 'waiting_validation' == $payment_status ) { ?>
            <div class="col-lg-6 col-md-8 mx-auto text-center">
				<?php echo $temp->render( 'payment-thank-you' ); ?>
            </div>
		<?php } ?>
    </div>

	<?php
}

get_footer();