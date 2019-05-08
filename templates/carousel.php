<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/20/2019
 * Time: 12:06 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$unique_id = uniqid( 'slider' );
?>

<div id="<?php echo $unique_id; ?>" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ul class="carousel-indicators">
		<?php
		for ( $i = 0; $i < count( $items ); $i ++ ) {
			?>
            <li data-target="#<?php echo $unique_id; ?>"
                data-slide-to="<?php echo $i; ?>" <?php echo 0 === $i ? ' class="active"' : ''; ?>>
            </li>
		<?php }
		?>
    </ul>

    <!-- The slideshow -->
    <div class="carousel-inner" role="listbox">
		<?php
		for ( $i = 0; $i < count( $items ); $i ++ ) {
			?>
            <div class="carousel-item <?php echo 0 === $i ? 'active' : ''; ?>"><?php echo $items[ $i ]; ?></div>
		<?php }
		?>
    </div>

    <!-- Left and right controls -->
    <a class="carousel-control-prev" href="#<?php echo $unique_id; ?>" data-slide="prev">
        <span class="carousel-control-prev-icon"></span> </a>
    <a class="carousel-control-next" href="#<?php echo $unique_id; ?>" data-slide="next">
        <span class="carousel-control-next-icon"></span> </a>
</div>
