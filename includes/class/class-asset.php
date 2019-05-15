<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/18/2019
 * Time: 11:07 PM
 *
 * @package Masjid/Includes
 */

namespace Masjid\Includes;

use Masjid\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Asset' ) ) {

	/**
	 * Class MaAsset
	 */
	class Asset {
		/**
		 * Private instance variable
		 *
		 * @var null
		 */
		private static $instance = null;
		/**
		 * Private theme version string
		 *
		 * @var string theme version.
		 */
		private $version = '';
		/**
		 * Private front css variable
		 *
		 * @var array
		 */
		private $front_css = [];
		/**
		 * Private front js variable
		 *
		 * @var array
		 */
		private $front_js = [];
		/**
		 * Private admin css variable
		 *
		 * @var array
		 */
		private $admin_css = [];
		/**
		 * Private admin js variables
		 *
		 * @var array
		 */
		private $admin_js = [];

		/**
		 * Singleton
		 *
		 * @return Asset|null
		 */
		public static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * MaAsset constructor.
		 */
		private function __construct() {
			$theme_ovject  = wp_get_theme( 'masjid' );
			$this->version = $theme_ovject->get( 'Version' );
			$this->load_front_asset();
			$this->load_admin_asset();
		}

		/**
		 * Map front asset
		 */
		private function map_front_asset() {
			$this->front_css       = [
				'bootstrap'     => [
					'url' => TEMP_URI . '/assets/front/vendor/bootstrap/css/bootstrap.min.css',
				],
				'sweetalert'    => [
					'url' => TEMP_URI . '/assets/front/vendor/bootstrap-sweetalert/dist/sweetalert.css',
				],
				'fontawesome'   => [
					'url' => TEMP_URI . '/assets/front/vendor/fontawesome-free/css/all.min.css',
				],
				'mirza_gf'      => [
					'url' => 'https://fonts.googleapis.com/css?family=Mirza',
				],
				'montserrat_gf' => [
					'url' => 'https://fonts.googleapis.com/css?family=Montserrat',
				],
				'incosolata_gf' => [
					'url' => 'https://fonts.googleapis.com/css?family=Inconsolata',
				],
				'lity'          => [
					'url' => TEMP_URI . '/assets/front/vendor/lity/dist/lity.min.css',
				],
				'fullcalendar'  => [
					'url' => TEMP_URI . '/assets/front/vendor/fullcalendar/fullcalendar.min.css',
				],
				'masjid'        => [
					'url' => TEMP_URI . '/assets/front/css/masjid.css',
				],
			];
			$this->front_js        = [
				'bootstrap'         => [
					'url' => TEMP_URI . '/assets/front/vendor/bootstrap/js/bootstrap.bundle.min.js',
				],
				'jquery-validation' => [
					'url' => TEMP_URI . '/assets/front/vendor/jquery-validation/dist/jquery.validate.min.js',
				],
				'sweetalert'        => [
					'url' => TEMP_URI . '/assets/front/vendor/bootstrap-sweetalert/dist/sweetalert.min.js',
				],
				'jquery-easing'     => [
					'url' => TEMP_URI . '/assets/front/vendor/jquery-easing/jquery.easing.min.js',
				],
				'lity'              => [
					'url' => TEMP_URI . '/assets/front/vendor/lity/dist/lity.min.js',
				],
				'moment'            => [
					'url'  => TEMP_URI . '/assets/front/vendor/fullcalendar/moment.min.js',
					'rule' => [
						'_wp_page_template' => 'page-templates/lecture.php',
					],
				],
				'fullcalendar'      => [
					'url'  => TEMP_URI . '/assets/front/vendor/fullcalendar/fullcalendar.min.js',
					'rule' => [
						'_wp_page_template' => 'page-templates/lecture.php',
					],
				],
				'lecture'           => [
					'url'  => TEMP_URI . '/assets/front/js/lecture.js',
					'rule' => [
						'_wp_page_template' => 'page-templates/lecture.php',
					],
					'vars' => [
						'ajax_url'  => admin_url( 'admin-ajax.php' ),
						'date_noew' => date( 'Y-m-d' ),
					],
				],
				'single-lecture'    => [
					'url'  => TEMP_URI . '/assets/front/js/single-lecture.js',
					'rule' => [
						'post_type' => 'kajian',
					],
					'vars' => [
						'ajax_url' => admin_url( 'admin-ajax.php' ),
					],
				],
				'single-payment'    => [
					'url'  => TEMP_URI . '/assets/front/js/single-payment.js',
					'rule' => [
						'post_type' => 'bayar',
					],
					'vars' => [
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'message'  => [
							'sorry' => __( 'Sorry', 'masjid' ),
							'great' => __( 'Great', 'masjid' ),
						],
					],
				],
				'single-campaign'   => [
					'url'  => TEMP_URI . '/assets/front/js/single-campaign.js',
					'rule' => [
						'post_type' => 'donasi',
					],
					'vars' => [
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'message'  => [
							'sorry' => __( 'Sorry', 'masjid' ),
							'great' => __( 'Great', 'masjid' ),
						],
					],
				],
				'masjid'            => [
					'url' => TEMP_URI . '/assets/front/js/masjid.js',
				],
			];
			$fullcalendar_local_js = TEMP_PATH . '/assets/front/vendor/fullcalendar/locale/' . strtolower( get_locale() ) . '.js';
			if ( file_exists( $fullcalendar_local_js ) ) {
				$this->front_js['fullcalendar_locale'] = [
					'url'  => TEMP_URI . '/assets/front/vendor/fullcalendar/locale/' . strtolower( get_locale() ) . '.js',
					'rule' => [
						'_wp_page_template' => 'page-templates/lecture.php',
					],
				];
			}
		}

		/**
		 * Map admin asset
		 */
		private function map_admin_asset() {
			$this->admin_css = [
				'style' => [
					'url' => TEMP_URI . '/assets/admin/css/style.css',
					'dep' => [ 'cmb2-styles' ],
				],
			];
			$this->admin_js  = [
				'cmb2-conditionals' => [
					'url' => TEMP_URI . '/assets/admin/js/cmb2-conditionals.js',
					'dep' => [ 'cmb2-scripts' ],
				],
			];
		}

		/**
		 * Load front asset.
		 */
		private function load_front_asset() {
			$this->map_front_asset();
			add_action( 'wp_enqueue_scripts', [ $this, 'front_asset_callback' ] );
		}

		/**
		 * Load admin asset.
		 */
		private function load_admin_asset() {
			$this->map_admin_asset();
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_assets_callback' ] );
		}

		/**
		 * Callback loading front asset
		 */
		public function front_asset_callback() {
			foreach ( $this->front_css as $name => $obj ) {
				wp_enqueue_style( $name, $obj['url'], [], $this->version );
			}
			foreach ( $this->front_js as $name => $obj ) {
				global $post;
				$post_id      = is_object( $post ) ? $post->ID : get_the_ID();
				$filter_key   = true;
				$filter_value = true;
				if ( isset( $obj['rule'] ) ) {
					$filter_key   = isset( $obj['rule']['_wp_page_template'] ) ? Helpers\Helper::pfield( '_wp_page_template', $post_id ) : ( isset( $obj['rule']['post_type'] ) ? get_post_type( $post_id ) : false );
					$filter_value = isset( $obj['rule']['_wp_page_template'] ) ? $obj['rule']['_wp_page_template'] : ( isset( $obj['rule']['post_type'] ) ? $obj['rule']['post_type'] : false );
				}
				if ( $filter_key === $filter_value ) {
					$dependencies = ! empty( $obj['dep'] ) ? $obj['dep'] : [ 'jquery' ];
					wp_enqueue_script( $name, $obj['url'], $dependencies, $this->version, true );
					if ( isset( $obj['vars'] ) ) {
						wp_localize_script( $name, 'obj', $obj['vars'] );
					}
				}
			}
		}

		/**
		 * Callback for loading admin asset
		 */
		public function admin_assets_callback() {
			global $post;
			foreach ( $this->admin_js as $name => $obj ) {
				$filter_key   = true;
				$filter_value = true;
				if ( isset( $obj['rule'] ) ) {
					$filter_key   = isset( $obj['rule']['post_type'] ) ? ( is_object( $post ) ? $post->post_type : false ) : false;
					$filter_value = isset( $obj['rule']['post_type'] ) ? $obj['rule']['post_type'] : false;
				}
				if ( $filter_key === $filter_value ) {
					$dependencies = ! empty( $obj['dep'] ) ? $obj['dep'] : [ 'jquery' ];
					wp_enqueue_script( $name, $obj['url'], $dependencies, $this->version, true );
				}
			}
			foreach ( $this->admin_css as $name => $obj ) {
				$filter_key   = true;
				$filter_value = true;
				if ( isset( $obj['rule'] ) ) {
					$filter_key   = isset( $obj['rule']['post_type'] ) ? ( is_object( $post ) ? $post->post_type : false ) : false;
					$filter_value = isset( $obj['rule']['post_type'] ) ? $obj['rule']['post_type'] : false;
				}
				if ( $filter_key === $filter_value ) {
					$dependencies = ! empty( $obj['dep'] ) ? $obj['dep'] : [];
					wp_enqueue_style( $name, $obj['url'], $dependencies, $this->version );
				}
			}
		}
	}
}

Asset::init();
