<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/20/2019
 * Time: 12:06 PM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$unique_id  = uniqid( 'slider' );
$item_count = count( $items );
?>

<div id="<?php echo $unique_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" class="carousel slide" data-ride="carousel">
	<!-- Indicators -->
	<ul class="carousel-indicators">
		<?php
		for ( $i = 0; $i < $item_count; $i ++ ) {
			?>
			<li data-target="#<?php echo $unique_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"
				data-slide-to="<?php echo $i; ?>" <?php echo 0 === $i ? ' class="active"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			</li>
			<?php
		}
		?>
	</ul>

	<!-- The slideshow -->
	<div class="carousel-inner" role="listbox">
		<?php
		for ( $i = 0; $i < $item_count; $i ++ ) {
			?>
			<div class="carousel-item <?php echo 0 === $i ? 'active' : ''; ?>"><?php echo $items[ $i ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php
		}
		?>
	</div>

	<!-- Left and right controls -->
	<a class="carousel-control-prev" href="#<?php echo $unique_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" data-slide="prev">
		<span class="carousel-control-prev-icon"></span> </a>
	<a class="carousel-control-next" href="#<?php echo $unique_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" data-slide="next">
		<span class="carousel-control-next-icon"></span> </a>
</div>
