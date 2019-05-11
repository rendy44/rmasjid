<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/21/2019
 * Time: 9:12 PM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="history_item mb-3">
	<div class="row">
		<div class="col-3">
			<img src="<?php echo TEMP_URI . '/assets/front/img/placeholder.jpg'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" class="rounded-circle">
		</div>
		<div class="col-9">
			<span>
				<strong>Rp<?php echo $total_formatted_amount; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong><br/>
				<small><?php echo $beautify_datetime; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></small>
				<br/><?php echo __( 'By', 'masjid' ) . ' ' . ( $hide_name ? __( 'Anonymous', 'masjid' ) : $name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span><br/> <q><?php echo esc_html( $message ); ?></q>
		</div>
	</div>
</div>
