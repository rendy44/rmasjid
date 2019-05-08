<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/18/2019
 * Time: 10:35 PM
 */

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
		static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Masjid constructor.
		 */
		private function __construct() {
			$this->_load_libraries();
			$this->_load_classes();
			$this->_load_languages();
		}

		private function _load_languages() {
			load_theme_textdomain( 'masjid', TEMP_DIR . '/lang' );
		}

		/**
		 * Map dependency classes
		 */
		private function _map_classes() {
			$this->classes = [
				'MaCPT',
				'MaTemplate',
				'MaDesigner',
				'MaAsset',
				'MaSetting',
				'MaNavWalker',
				'MaHelper',
				'MaCMB2Conditionals',
				'MaCMB2CustomField',
				'MaAjax',
				'MaPayment',
				'MaOptionsPage',
				'MaMetaBox',
			];
		}

		/**
		 * Map dependency libraries
		 */
		private function _map_libraries() {
			$this->libraries = [ 'cmb2/init', 'cmb2-tabs/plugin' ];
		}

		/**
		 * Load dependency classes
		 */
		private function _load_classes() {
			$this->_map_classes();

			foreach ( $this->classes as $class ) {
				require_once TEMP_PATH . "/includes/class/{$class}.php";
			}
		}

		/**
		 * Load dependency libraries
		 */
		private function _load_libraries() {
			$this->_map_libraries();

			foreach ( $this->libraries as $library ) {
				require_once TEMP_PATH . "/includes/lib/{$library}.php";
			}
		}
	}
}

Masjid::init();