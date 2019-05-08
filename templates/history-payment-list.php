<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/21/2019
 * Time: 9:12 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="history_item mb-3">
    <div class="row">
        <div class="col-3">
            <img src="<?php echo TEMP_URI . '/assets/front/img/placeholder.jpg'; ?>" class="rounded-circle">
        </div>
        <div class="col-9">
            <span>
                <strong>Rp<?php echo $total_formatted_amount; ?></strong><br/>
                <small><?php echo $beautify_datetime; ?></small>
                <br/><?php echo __( 'By', 'masjid' ) . ' ' . ( $hide_name ? __( 'Anonymous', 'masjid' ) : $name ); ?>
            </span><br/> <q><?php echo $message; ?></q>
        </div>
    </div>
</div>
