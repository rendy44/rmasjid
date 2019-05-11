<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/18/2019
 * Time: 10:34 PM
 *
 * @package Masjid/Main
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
session_start();
defined( 'TEMP_DIR' ) || define( 'TEMP_DIR', get_template_directory() );
defined( 'TEMP_URI' ) || define( 'TEMP_URI', get_template_directory_uri() );
defined( 'TEMP_PATH' ) || define( 'TEMP_PATH', get_theme_file_path() );
require_once TEMP_PATH . '/includes/class/class-masjid.php';
/**
 * Will be triggered after theme being selected
 */
add_action( 'after_switch_theme', 'run_activator' );
/**
 * Include MaActivator class for first time setup
 */
function run_activator() {
	require_once TEMP_PATH . '/includes/class/class-activator.php';
}
