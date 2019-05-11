<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 9:59 PM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="input-group">
		<input type="search" id="<?php echo esc_attr( $unique_id ); ?>" name="s" class="form-control" placeholder="<?php echo esc_attr__( 'Search for...', 'masjid' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
		<span class="input-group-btn">
			<button class="btn btn-primary" type="submit"><?php echo esc_html__( 'Search', 'masjid' ); ?></button>
		</span>
	</div>
</form>
