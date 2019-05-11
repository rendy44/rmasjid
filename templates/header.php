<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/18/2019
 * Time: 10:27 PM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="<?php bloginfo( 'description' ); ?>">
		<meta name="author" content="<?php bloginfo( 'name' ); ?>">
		<?php wp_head(); ?>
	</head>

	<?php
	$page_classes = [];
	if ( is_front_page() ) {
		$page_classes[] = 'front';
	} elseif ( is_single() ) {
		$page_classes[] = 'single';
	} elseif ( is_404() ) {
		$page_classes[] = 'not-found';
	};
	?>

	<body id="page-top" class="<?php echo implode( ' ', $page_classes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
