<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 5/18/2019
 * Time: 8:34 PM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="navbar2">
    <!-- Navigation -->
	<?php
	if ( has_nav_menu( 'main_nav' ) ) {
		wp_nav_menu(
			[
				'theme_location'  => 'main_nav',
				'depth'           => 1,
				'container'       => 'div',
				'container_class' => 'b-nav',
				'container_id'    => 'navbarResponsive',
				'menu_class'      => 'b-nav-list',
				'walker'          => new Navwalker(),
			]
		);
	}
	?>

    <!-- Burger-Icon -->
    <div class="b-container">
        <div class="b-menu">
            <div class="b-bun b-bun--top"></div>
            <div class="b-bun b-bun--mid"></div>
            <div class="b-bun b-bun--bottom"></div>
        </div>

        <a href="<?php echo home_url(); ?>" class="b-brand"><?php echo $brand; ?></a>
    </div>
</div>
