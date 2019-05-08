<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 7:34 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="intro-text">
    <div class="intro-lead-in"><?php echo $title; ?></div>
    <div class="intro-heading"><?php echo $subtitle; ?></div>
	<?php echo $link ? '<a href="' . $link . '" class="btn btn-primary btn-lg text-uppercase shine">' . $link_caption . '</a>' : ''; ?>
</div>