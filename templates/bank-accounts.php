<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/28/2019
 * Time: 10:02 AM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="mt-4">
	<p><?php echo __( 'Transfer to one of these bank accounts before', 'masjid' ) . ' <strong>' . $expiry_beautify . '</strong>. ' . __( 'Or your donation will be canceled automatically.', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	<?php
	foreach ( $banks as $bank ) {
		?>
		<div class="card mb-3 bank-item">
			<div class="card-body">
				<div class="row">
					<div class="col-5">
						<img src="<?php echo TEMP_URI . '/assets/front/img/' . $bank['bank_name'] . '.png'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"class="img-fluid">
					</div>
					<div class="col-6 text-left">
						<strong class="lead"><?php echo esc_html( $bank['bank_account_number'] ); ?></strong><br/>
						<span><?php echo esc_html( $bank['bank_branch'] ); ?></span>
					</div>
				</div>
			</div>
			<div class="card-footer"><?php echo __( 'Holder name', 'masjid' ) . ' ' . esc_html( $bank['bank_holder'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>
		<?php
	}
	?>
</div>
