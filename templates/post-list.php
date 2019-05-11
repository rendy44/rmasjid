<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 7:24 PM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$width_md = isset( $width_md ) ? ' col-md-' . $width_md : '';
?>

<div class="<?php echo 'col-lg-' . esc_attr( $width ) . esc_attr( $width_md ); ?> d-flex">
	<div class="card mb-3 style1 flex-fill">
		<img class="card-img-top" src="<?php echo $cover_url; ?>" alt="<?php echo esc_html( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
		<div class="card-body">
			<img src="<?php echo $avatar_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" class="author-avatar rounded-circle img-thumbnail"> <a href="<?php echo $permalink; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" class="permalink">
				<h5 class="card-title"><?php echo esc_html( $title ); ?></h5>
			</a>
			<p class="card-text text-justify"><?php echo esc_html( $excerpt ); ?></p>
			<a href="<?php echo $permalink; ?>" class="card-link"><?php echo __( 'See more', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		</div>
		<div class="card-footer">
			<ul>
				<li><?php echo $post_date; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
				<li><?php echo $comment_count > 0 ? $comment_count . ' ' . __( 'Comment', 'masjid' ) : __( "There's no comment", 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
			</ul>
		</div>
	</div>
</div>
