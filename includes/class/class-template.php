<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/18/2019
 * Time: 10:47 PM
 *
 * @package Masjid/Helpers
 */

namespace Masjid\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Template' ) ) {

	/**
	 * Class Template
	 */
	class Template {
		/**
		 * Private instance variable
		 *
		 * @var null
		 */
		private static $instance = null;
		/**
		 * Location of expected template
		 *
		 * @var string
		 */
		private static $folder;

		/**
		 * Create singleton
		 *
		 * @return null|Template
		 */
		public static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * MaTemplate constructor.
		 */
		private function __construct() {
			$folder = TEMP_PATH . '/templates';
			if ( $folder ) {
				self::set_folder( $folder );
			}
		}

		/**
		 * Simple method for updating the base folder where templates are located.
		 *
		 * @param string $folder folder name.
		 */
		private static function set_folder( $folder ) {
			// normalize the internal folder value by removing any final slashes.
			self::$folder = rtrim( $folder, '/' );
		}

		/**
		 * Find and attempt to render a template with variables
		 *
		 * @param string $suggestions file name.
		 * @param array  $variables variables.
		 *
		 * @return string
		 */
		public function render( $suggestions, $variables = [] ) {
			$template = $this->find_template( $suggestions );
			$output   = '';
			if ( $template ) {
				$output = $this->render_template( $template, $variables );
			}

			return $output;
		}

		/**
		 * Look for the first template suggestion
		 *
		 * @param string $suggestions file name.
		 *
		 * @return bool|string
		 */
		private function find_template( $suggestions ) {
			if ( ! is_array( $suggestions ) ) {
				$suggestions = [ $suggestions ];
			}
			$suggestions = array_reverse( $suggestions );
			$found       = false;
			foreach ( $suggestions as $suggestion ) {
				$file = self::$folder . "/{$suggestion}.php";
				if ( file_exists( $file ) ) {
					$found = $file;
					break;
				}
			}

			return $found;
		}

		/**
		 * Execute the template by extracting the variables into scope, and including
		 * the template file.
		 *
		 * @param string $template file name.
		 * @param array  $variables variables.
		 *
		 * @return string
		 */
		private function render_template( $template, $variables = [] ) {
			ob_start();
			foreach ( $variables as $key => $value ) {
				${$key} = $value;
			}
			include $template;

			return ob_get_clean();
		}
	}
}
global $temp;
$temp = Template::init();
