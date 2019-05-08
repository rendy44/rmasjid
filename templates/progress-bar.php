<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/24/2019
 * Time: 2:00 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$height  = isset( $height ) ? 'style="height: ' . $height . 'px"' : '';
$value   = isset( $value ) ? $value : 0;
$content = isset( $content ) ? $content : '';
?>

<div class="progress" <?php echo $height; ?>>
    <div class="progress-bar" role="progressbar" style="width: <?php echo $value; ?>%;" aria-valuenow="<?php echo $value; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo esc_html( $content ); ?></div>
</div>
