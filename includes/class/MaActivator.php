<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 8:12 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MaActivator' ) ) {

	/**
	 * Class MaActivator
	 */
	class MaActivator {

		/**
		 * Private instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Private dependency pages variable
		 *
		 * @var array
		 */
		private $dep_pages = [];

		/**
		 * Singleton
		 *
		 * @return MaActivator|null
		 */
		static function init() {
			if ( null === self::$instance ) {
				self::$instance = new  self();
			}

			return self::$instance;
		}

		/**
		 * MaActivator constructor.
		 */
		private function __construct() {
			$this->_create_dep_pages();
			$this->_set_web_settings();
		}

		/**
		 * Set default settings
		 */
		private function _set_web_settings() {
			$options = get_option( 'ma_page_maps' );
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $options['page_home'] );
			update_option( 'page_for_posts', $options['page_article'] );
		}

		/**
		 * Create dependency pages
		 */
		private function _create_dep_pages() {
			$this->_map_dep_pages();

			// check options for created pages
			$opt_page_ids  = (array) get_option( 'ma_page_ids' );
			$opt_page_keys = (array) get_option( 'ma_page_keys' );
			$opt_page_maps = (array) get_option( 'ma_page_maps' );

			foreach ( $this->dep_pages as $name => $title ) {
				// if not available in option, then create one
				if ( ! in_array( $name, $opt_page_keys ) ) {
					$opt_page_keys[] = $name;
					$created_page    = $this->_create_post( $title, 'page' );
					update_post_meta( $created_page, '_wp_page_template', "page-templates/{$name}.php" );
					$opt_page_ids[]                   = $created_page;
					$opt_page_maps[ 'page_' . $name ] = $created_page;
				}
			}

			// update options
			update_option( 'ma_page_ids', $opt_page_ids );
			update_option( 'ma_page_keys', $opt_page_keys );
			update_option( 'ma_page_maps', $opt_page_maps );
		}

		/**
		 * Map dependecy pages
		 */
		private function _map_dep_pages() {
			$this->dep_pages = [
				'campaign' => __( 'All Campaigns', 'masjid' ),
				'history'  => __( 'Our History', 'masjid' ),
				'home'     => __( 'Home', 'masjid' ),
				'lecture'  => __( 'Lecture Schedules', 'masjid' ),
				'article'  => __( 'All Articles', 'masjid' ),
			];
		}

		/**
		 * Create a post
		 *
		 * @param        $post_title
		 * @param string $post_type
		 *
		 * @return int|WP_Error
		 */
		private function _create_post( $post_title, $post_type = 'post' ) {
			$new_post = wp_insert_post( [
				'post_type'   => $post_type,
				'post_status' => 'publish',
				'post_title'  => $post_title,
				'post_name'   => sanitize_title( $post_title ),
			] );

			return $new_post;
		}
	}
}

MaActivator::init();