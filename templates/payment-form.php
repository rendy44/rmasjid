<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 7:40 PM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<form name="frmPay" class="frmPay" id="frmPay_<?php echo esc_attr( $id ); ?>" method="post">
	<?php wp_nonce_field( 'validate_nonce_campaign_payment', 'nonce_field' ); ?>
	<div class="input-group input-group-lg mb-3">
		<div class="input-group-prepend">
			<span class="input-group-text">Rp</span>
		</div>
		<input name="amount" id="amount" type="number" class="form-control" pattern="\d*" min="10000" oninput="this.value = Math.abs(this.value)" required>
	</div>
	<div class="alert alert-info">
		<?php echo __( 'Minimum allowed donation is Rp10.000', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<div class="form-group input-group-lg">
		<input name="name" id="name" type="text" class="form-control" placeholder="<?php echo __( 'Your name', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" required>
	</div>
	<div class="custom-control custom-checkbox mb-3">
		<input name="hide_name" id="hide_name" type="checkbox" class="custom-control-input" value="1">
		<label class="custom-control-label" for="hide_name"><?php echo __( 'Hide my name (anonymous)', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
	</div>
	<div class="form-group input-group-lg">
		<input name="email" id="email" type="email" class="form-control" placeholder="<?php echo __( 'Your email', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" required>
	</div>
	<div class="form-group input-group-lg">
		<textarea name="message" id="message" class="form-control" placeholder="<?php echo __( 'Your message', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"></textarea>
	</div>
	<div class="clearfix"></div>
	<input type="hidden" name="payment_id" value="<?php echo esc_attr( $id ); ?>">
	<button class="btn btn-primary btn-lg btn-block text-uppercase btn-pay-continue" type="submit"><?php echo __( 'Continue Payment', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
</form>
