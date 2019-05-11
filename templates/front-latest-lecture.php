<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/26/2019
 * Time: 9:11 PM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$subtitle = isset( $subtitle ) ? '<p class="lead">' . esc_html( $subtitle ) . '</p>' : '';
?>

<div class="row">
	<div class="col-lg-8 mx-auto text-center mb-4">
		<h2 class="section-heading text-uppercase"><?php echo esc_html( $title ); ?></h2>
		<?php echo $subtitle; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</div>
<div class="row items">
	<?php
	foreach ( $items as $item ) {
		echo $item; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>
</div>
