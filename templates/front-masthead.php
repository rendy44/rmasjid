<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 7:34 AM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="intro-text">
	<div class="intro-lead-in"><?php echo $title ? esc_html( $title ) : ''; ?></div>
	<div class="intro-subheading"><?php echo $description ? esc_html( $description ) : ''; ?></div>
	<?php echo $link ? '<a href="' . $link . '" class="btn btn-outline-default btn-lg text-uppercase shine">' . esc_html( $caption ) . '</a>' : ''; // phpcs:ignore ?>
</div>
