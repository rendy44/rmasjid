<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/18/2019
 * Time: 10:43 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MaDesigner' ) ) {

	/**
	 * Class MaDesigner
	 */
	class MaDesigner {

		/**
		 * Private instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Private template variable
		 *
		 * @var null
		 */
		private $temp = null;

		/**
		 * Singleton
		 *
		 * @return MaDesigner|null
		 */
		static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * MaDesigner constructor.
		 */
		private function __construct() {
			$this->_instance_temp();
			$this->_design_header();
			$this->_design_footer();
			$this->_custom_css();
		}

		/**
		 * Instance template
		 */
		private function _instance_temp() {
			global $temp;

			$this->temp = $temp;
		}

		/**
		 * Adjust content for header
		 */
		private function _design_header() {
			add_action( 'header_content', [ $this, 'header_content_callback' ], 10 );
			add_action( 'header_content', [ $this, 'top_navbar_callback' ], 20 );
			add_action( 'header_content', [ $this, 'maybe_small_header_callback' ], 30 );
			add_action( 'header_content', [ $this, 'maybe_content_wrapper_callback' ], 40 );
			add_filter( 'small_header_content', [ $this, 'small_header_content_callback' ], 1, 3 );
		}

		/**
		 * Adjust content for footer
		 */
		private function _design_footer() {
			add_action( 'footer_content', [ $this, 'maybe_content_wrapper_close_callback' ], 10 );
			add_action( 'footer_content', [ $this, 'footer_content_callback' ], 20 );
		}

		/**
		 * Add custom css
		 */
		private function _custom_css() {
			add_action( 'wp_head', [ $this, 'custom_css_callback' ] );
		}

		/**
		 * Callback for rendering header content
		 */
		function header_content_callback() {
			echo $this->temp->render( 'header' );
		}

		/**
		 * Callback for rendering top navbar
		 */
		function top_navbar_callback() {
			echo $this->temp->render( 'top-nav', [ 'brand' => MaHelper::get_header_brand_content() ] );
		}

		/**
		 * Maybe callback for rendering small header
		 */
		function maybe_small_header_callback() {
			if ( ! is_front_page() ) {
				echo $this->temp->render( 'header-small' );
			}
		}

		/**
		 * Callback for rendering custom header small
		 *
		 * @param        $image_id
		 * @param        $title
		 * @param string $subcontent
		 */
		function small_header_content_callback( $image_id, $title, $subcontent = '' ) {
			echo $this->temp->render( 'header-small', [
				'image_url'  => ( $image_id ) ? wp_get_attachment_image_url( $image_id, 'large' ) : '',
				'title'      => $title,
				'subcontent' => $subcontent,
			] );
		}

		/**
		 * Maybe callback for rendering content wrapper
		 */
		function maybe_content_wrapper_callback() {
			if ( ! is_front_page() ) {
				echo "<div class=\"container py-5 normal\">";
				echo "<div class=\"container\">";
			}
		}

		/**
		 * Maybe callback for rendering content wrapper close
		 */
		function maybe_content_wrapper_close_callback() {
			if ( ! is_front_page() ) {
				echo "</div>";
				echo "</div>";
			}
		}

		/**
		 * Callback for rendering footer content
		 */
		function footer_content_callback() {
			$social_network_urls = MaHelper::get_social_network_url();
			$footer_options      = get_option( 'ma_options_footer' );
			$style               = ! empty( $footer_options['footer_design'] ) ? $footer_options['footer_design'] : 'style1';
			echo $this->temp->render( 'footer-' . $style, [
				'title'           => get_bloginfo( 'name' ),
				'description'     => get_bloginfo( 'description' ),
				'footnote'        => '&copy; ' . get_bloginfo( 'name' ) . ' ' . date( 'Y' ),
				'social_networks' => $social_network_urls,
				'options'         => $footer_options,
			] );
		}

		/**
		 * Callback for rendering custom css
		 */
		function custom_css_callback() {
			if ( ! is_admin() ) {
				echo $this->temp->render( 'custom-css' );
			}
		}
	}
}

global $designer;

$designer = MaDesigner::init();