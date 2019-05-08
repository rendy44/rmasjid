<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/20/2019
 * Time: 6:24 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="donasi-slider-item darken item<?php echo $id; ?>"
     style="background-image: url('<?php echo $cover_url; ?>')">
    <div class="carousel-caption d-md-block mb-lg-2 mb-sm-0">
        <h4><?php echo $title; ?></h4>
        <p class="lead"><?php echo isset( $short_description_fix ) ? $short_description_fix : ''; ?></p>
        <a href="<?php echo $permalink; ?>" class="btn btn-primary mt-1"><?php echo __( 'See more', 'masjid' ); ?></a>
    </div>
</div>
