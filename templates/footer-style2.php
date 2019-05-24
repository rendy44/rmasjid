<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 5/8/2019
 * Time: 5:00 PM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$options = [];
$title_1 = ! empty( $options['footer_1_title'] ) ? $options['footer_1_title'] : '';
$links_1 = ! empty( $options['footer_1_links'] ) ? $options['footer_1_links'] : '';
$title_2 = ! empty( $options['footer_2_title'] ) ? $options['footer_2_title'] : '';
$links_2 = ! empty( $options['footer_2_links'] ) ? $options['footer_2_links'] : '';
$title_3 = ! empty( $options['footer_3_title'] ) ? $options['footer_3_title'] : '';
$links_3 = ! empty( $options['footer_3_links'] ) ? $options['footer_3_links'] : '';
?>

<!-- Back to top -->
<a class="btn btn-primary btn-lg btn-back-to-top text-center text-white" style="display: none"> <i class="fa fa-arrow-up"></i> </a>
<footer class="style2 <?php echo $color_scheme; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<span class="copyright"><?php echo esc_html( $footnote ); ?></span>
			</div>
			<div class="col-md-4 socnet">
				<ul class="list-inline social-buttons">
					<?php echo $social_networks['telegram'] ? '<li class="list-inline-item"><a target="_blank" href="' . $social_networks['telegram'] . '"><i class="fab fa-telegram-plane"></i></a></li>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo $social_networks['facebook'] ? '<li class="list-inline-item"><a target="_blank" href="' . $social_networks['facebook'] . '"><i class="fab fa-facebook-f"></i></a></li>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo $social_networks['instagram'] ? '<li class="list-inline-item"><a target="_blank" href="' . $social_networks['instagram'] . '"><i class="fab fa-instagram"></i></a></li>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo $social_networks['youtube'] ? '<li class="list-inline-item"><a target="_blank" href="' . $social_networks['youtube'] . '"><i class="fab fa-youtube"></i></a></li>' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</ul>
			</div>
			<div class="col-md-4">
				<ul class="list-inline quicklinks">
					<li class="list-inline-item">
						<a href="#"><?php echo $title_1; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
						<ul class="sub-items">
							<?php
							if ( $links_1 ) {
								foreach ( $links_1 as $item ) {
									echo '<li><a href="' . get_permalink( $item ) . '">' . get_the_title( $item ) . '</a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
							}
							?>
						</ul>
					</li>
					<li class="list-inline-item">
						<a href="#"><?php echo $title_2; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
						<ul class="sub-items">
							<?php
							if ( $links_2 ) {
								foreach ( $links_2 as $item ) {
									echo '<li><a href="' . get_permalink( $item ) . '">' . get_the_title( $item ) . '</a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
							}
							?>
						</ul>
					</li>
					<li class="list-inline-item">
						<a href="#"><?php echo $title_3; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
						<ul class="sub-items">
							<?php
							if ( $links_3 ) {
								foreach ( $links_3 as $item ) {
									echo '<li><a href="' . get_permalink( $item ) . '">' . get_the_title( $item ) . '</a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
							}
							?>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>

</body>
</html>
