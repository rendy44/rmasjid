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

<div class="intro-text align-self-center">
	<div class="intro-lead-in"><?php echo esc_html( $title ); ?></div>
	<div class="intro-heading"><?php echo esc_html( $subtitle ); ?></div>
	<?php echo $link ? '<a href="' . $link . '" class="btn btn-primary btn-lg text-uppercase shine">' . esc_html( $link_caption ) . '</a>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
