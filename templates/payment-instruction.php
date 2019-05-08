<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/28/2019
 * Time: 12:18 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<h3><?php echo __( 'Payment Instruction', 'masjid' ); ?></h3>
<p class="lead">
	<?php echo __( 'Please make a transfer with the amount below.', 'masjid' ); ?>
</p>
<h1 class="mb-3 amount">Rp<?php echo $formatted_total_amount; ?></h1>
<div class="alert alert-warning">
    <i class="fa fa-exclamation-circle"></i> <?php echo '<strong>' . __( 'Important!', 'masjid' ) . '</strong> ' . __( 'The amount has to be exactly the same as above including the last 3 digits', 'masjid' ); ?>
</div>
<div class="alert bg-light detail-amount">
    <div class="row">
        <div class="col text-left">
			<?php echo __( 'Donation Amount', 'masjid' ); ?>
        </div>
        <div class="col text-right">
            <strong>Rp<?php echo $formatted_amount; ?></strong>
        </div>
    </div>
    <div class="row">
        <div class="col text-left">
			<?php echo __( 'Unique Code (*)', 'masjid' ); ?>
        </div>
        <div class="col text-right">
            <strong><?php echo $unique_code; ?></strong>
        </div>
    </div>
</div>
<p class="text-left disclaimer"><?php echo __( 'Disclaimer:', 'masjid' ); ?><br/>
	<?php echo __( '* The last 3 digits will be included into donation', 'masjid' ); ?></p>
