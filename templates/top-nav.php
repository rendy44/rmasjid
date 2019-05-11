<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 7:30 AM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
	<div class="container">
		<a class="navbar-brand" href="<?php echo home_url(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
			<?php echo $brand; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
		<button class="navbar-toggler navbar-toggler-right collapsed" type="button" data-toggle="collapse"
				data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
				aria-label="Toggle navigation">
			<span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
		</button>
		<?php
		if ( has_nav_menu( 'main_nav' ) ) {
			wp_nav_menu(
				[
					'theme_location'  => 'main_nav',
					'depth'           => 1,
					'container'       => 'div',
					'container_class' => 'collapse navbar-collapse',
					'container_id'    => 'navbarResponsive',
					'menu_class'      => 'navbar-nav text-uppercase ml-auto',
					'walker'          => new Navwalker(),
				]
			);
		}
		?>
	</div>
</nav>
