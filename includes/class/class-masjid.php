<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/18/2019
 * Time: 10:35 PM
 *
 * @package Masjid
 */

namespace Masjid;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Masjid' ) ) {

	/**
	 * Class Masjid
	 */
	class Masjid {

		/**
		 * Private instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Private classes variable
		 *
		 * @var array
		 */
		private $classes = [];

		/**
		 * Private libraries variable
		 *
		 * @var array
		 */
		private $libraries = [];

		/**
		 * Singleton
		 *
		 * @return Masjid|null
		 */
		public static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Masjid constructor.
		 */
		private function __construct() {
			$this->load_libraries();
			$this->load_classes();
			$this->load_languages();
		}

		/**
		 * Load Language packs
		 */
		private function load_languages() {
			load_theme_textdomain( 'masjid', TEMP_DIR . '/lang' );
		}

		/**
		 * Map dependency classes
		 */
		private function map_classes() {
			$this->classes = [
				'cpt',
				'template',
				'designer',
				'asset',
				'setting',
				'navwalker',
				'helper',
				'cmb2-conditionals',
				'cmb2-custom-field',
				'ajax',
				'payment',
				'options-page',
				'metabox',
			];
		}

		/**
		 * Map dependency libraries
		 */
		private function map_libraries() {
			$this->libraries = [ 'cmb2/init', 'cmb2-tabs/plugin' ];
		}

		/**
		 * Load dependency classes
		 */
		private function load_classes() {
			$this->map_classes();

			foreach ( $this->classes as $class ) {
				require_once TEMP_PATH . "/includes/class/class-{$class}.php";
			}
		}

		/**
		 * Load dependency libraries
		 */
		private function load_libraries() {
			$this->map_libraries();

			foreach ( $this->libraries as $library ) {
				require_once TEMP_PATH . "/includes/lib/{$library}.php";
			}
		}
	}
}

Masjid::init();
