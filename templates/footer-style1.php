<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/18/2019
 * Time: 10:31 PM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<!-- Back to top -->
<a class="btn btn-primary btn-lg btn-back-to-top text-center text-white" style="display: none"> <i class="fa fa-arrow-up"></i> </a>
<footer class="style1 <?php echo $color_scheme; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-sm-12 desc">
				<h4><?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h4>
				<hr/>
				<p><?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<ul class="list-inline social-buttons my-2">
					<?php echo $social_networks['telegram'] ? '<li class="list-inline-item"><a target="_blank" href="' . $social_networks['telegram'] . '"><i class="fab fa-telegram-plane"></i></a></li>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo $social_networks['facebook'] ? '<li class="list-inline-item"><a target="_blank" href="' . $social_networks['facebook'] . '"><i class="fab fa-facebook-f"></i></a></li>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo $social_networks['instagram'] ? '<li class="list-inline-item"><a target="_blank" href="' . $social_networks['instagram'] . '"><i class="fab fa-instagram"></i></a></li>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo $social_networks['youtube'] ? '<li class="list-inline-item"><a target="_blank" href="' . $social_networks['youtube'] . '"><i class="fab fa-youtube"></i></a></li>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</ul>
			</div>
			<div class="col-lg-2 col-sm-4 first">
				<h4><?php echo esc_html( $footer1_title ); ?></h4>
				<hr/>
				<ul class="links">
					<?php
					if ( ! empty( $footer1_items ) ) {
						foreach ( $footer1_items as $item ) {
							echo '<li><a href="' . $item->url . '" target="' . $item->target . '">' . $item->post_title . '</a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					}
					?>
				</ul>
			</div>
			<div class="col-lg-2 col-sm-4 mid">
				<h4><?php echo esc_html( $footer2_title ); ?></h4>
				<hr/>
				<ul class="links">
					<?php
					if ( ! empty( $footer2_items ) ) {
						foreach ( $footer2_items as $item ) {
							echo '<li><a href="' . $item->url . '" target="' . $item->target . '">' . $item->post_title . '</a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					}
					?>
				</ul>
			</div>
			<div class="col-lg-2 col-sm-4 last">
				<h4><?php echo esc_html( $footer3_title ); ?></h4>
				<hr/>
				<ul class="links">
					<?php
					if ( ! empty( $footer3_items ) ) {
						foreach ( $footer3_items as $item ) {
							echo '<li><a href="' . $item->url . '" target="' . $item->target . '">' . $item->post_title . '</a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					}
					?>
				</ul>
			</div>
		</div>
	</div>
	<div class="network">
		<span class="copyright"><?php echo esc_html( $footnote ); ?></span>
	</div>
</footer>
<?php wp_footer(); ?>

</body>
</html>
