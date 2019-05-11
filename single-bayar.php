<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 6:51 PM
 *
 * @package Masjid/Single
 *          Masjid/Transactions
 */

use Masjid\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $temp;
$campaign_id         = Helpers\Helper::pfield( 'campaign_id' );
$count_campaign      = Helpers\Helper::count_campaign( Helpers\Helper::pfield( 'main_detail_target', $campaign_id ), Helpers\Helper::pfield( 'main_detail_collected', $campaign_id ), Helpers\Helper::pfield( 'main_detail_due_date', $campaign_id ) );
$payment_status      = Helpers\Helper::pfield( 'status' );
$total_amount        = (int) Helpers\Helper::pfield( 'total_amount' );
$amount              = (int) Helpers\Helper::pfield( 'amount' );
$unique_amount       = Helpers\Helper::pfield( 'unique_amount' );
$expiry_confirmation = Helpers\Helper::pfield( 'expiry' );
$date_format         = get_option( 'date_format' );
$time_format         = get_option( 'time_format' );
$datetime_format     = $date_format . ' ' . $time_format;
if ( in_array( $payment_status, [ 'done', 'rejected' ], true ) ) {
	wp_safe_redirect( home_url() );
	exit;
}
get_header();
while ( have_posts() ) {
	the_post();
	?>

	<div class="row mb-3" id="single-payment" data-id="<?php the_ID(); ?>">
		<?php if ( 'waiting_payment' === $payment_status ) { ?>
			<div class="col-lg-5 mb-5">
				<p><?php echo __( 'You are going to make a donation for:', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<div class="row">
					<div class="col-3 col-md-2 col-lg-3">
						<img class="img-fluid rounded" src="<?php echo Helpers\Helper::get_thumbnail_url( $campaign_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
					</div>
					<div class="col-9 col-md-10 col-lg-9">
						<?php
						$campaign_progressbar_result = $temp->render(
							'progress-bar',
							[
								'height' => 5,
								'value'  => $count_campaign['collected_percent'],
							]
						);
						echo '<p><strong>' . get_the_title( $campaign_id ) . '</strong></p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo $campaign_progressbar_result; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo '<p class="mt-2 d-none d-sm-block">' . esc_html( Helpers\Helper::pfield( 'main_excerpt', $campaign_id ) ) . '</p>'
						?>
					</div>
				</div>
			</div>
			<div class="col-lg-7">
				<?php echo $temp->render( 'payment-form', [ 'id' => get_the_ID() ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php } elseif ( 'waiting_confirmation' === $payment_status ) { ?>
			<div class="col-lg-6 col-md-8 mx-auto text-center">
				<?php
				// instruction.
				$instruction_result = $temp->render(
					'payment-instruction',
					[
						'formatted_total_amount' => number_format( $total_amount, 0, ',', '.' ),
						'formatted_amount'       => number_format( $amount, 0, ',', '.' ),
						'unique_code'            => $unique_amount, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					]
				);
				echo $instruction_result; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				// bank accounts.
				$bank_account_result = $temp->render(
					'bank-accounts',
					[
						'expiry_beautify' => date( $datetime_format, $expiry_confirmation ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'banks'           => Helpers\Helper::get_bank_accounts(), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					]
				);
				echo $bank_account_result; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				// confirmation.
				echo $temp->render( 'payment-confirmation' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		<?php } elseif ( 'waiting_validation' === $payment_status ) { ?>
			<div class="col-lg-6 col-md-8 mx-auto text-center">
				<?php echo $temp->render( 'payment-thank-you' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php } ?>
	</div>

	<?php
}
get_footer();
