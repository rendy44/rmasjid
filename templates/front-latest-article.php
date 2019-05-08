<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/26/2019
 * Time: 9:11 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$subtitle = isset( $subtitle ) ? '<p class="lead">' . $subtitle . '</p>' : '';

?>

<div class="row">
    <div class="col-lg-8 mx-auto text-center mb-4">
        <h2 class="section-heading text-uppercase"><?php echo $title; ?></h2>
		<?php echo $subtitle; ?>
    </div>
</div>
<div class="row items">
	<?php
	foreach ( $items as $item ) {
		echo $item;
	}
	?>
</div>
