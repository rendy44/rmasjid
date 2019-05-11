<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 5:52 PM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( is_active_sidebar( 'ma_right_bar' ) ) {
	echo '<div class="col-md-4">';
	dynamic_sidebar( 'ma_right_bar' );
	echo '</div>';
}
