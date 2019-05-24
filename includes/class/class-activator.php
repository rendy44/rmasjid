<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 8:12 AM
 *
 * @package Masjid/Settings
 */

namespace Masjid\Settings;

use Masjid\Helpers;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Activator' ) ) {

	/**
	 * Class Activator
	 */
	class Activator {

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
		 * @return Activator|null
		 */
		public static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * MaActivator constructor.
		 */
		private function __construct() {
			$this->create_dep_pages();
			$this->set_web_settings();
			$this->create_navmenu();
		}

		/**
		 * Create built in nav menu
		 */
		private function create_navmenu() {
			$header_nav     = 'Main Menu';
			$header_nav_obj = wp_get_nav_menu_object( $header_nav );
			if ( ! $header_nav_obj ) {
				$header_nav_id  = wp_create_nav_menu( $header_nav );
				$prebuilt_pages = (array) get_option( 'ma_page_maps' );
				if ( ! empty( $prebuilt_pages ) ) {
					foreach ( $prebuilt_pages as $page_key => $page_id ) {
						wp_update_nav_menu_item(
							$header_nav_id,
							0,
							[
								'menu-item-title'   => get_the_title( $page_id ),
								'menu-item-classes' => $page_key,
								'menu-item-url'     => home_url( get_post_field( 'post_name', $page_id ) ),
								'menu-item-status'  => 'publish',
							]
						);
					}
				}
				$locations             = get_theme_mod( 'nav_menu_locations' );
				$locations['main_nav'] = $header_nav_id;
				set_theme_mod( 'nav_menu_locations', $locations );
			}
		}

		/**
		 * Set default settings
		 */
		private function set_web_settings() {
			$options = get_option( 'ma_page_maps' );
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $options['page_home'] );
			update_option( 'page_for_posts', $options['page_article'] );
		}

		/**
		 * Create dependency pages
		 */
		private function create_dep_pages() {
			$this->map_dep_pages();
			// check options for created pages.
			$opt_page_ids  = (array) get_option( 'ma_page_ids' );
			$opt_page_keys = (array) get_option( 'ma_page_keys' );
			$opt_page_maps = (array) get_option( 'ma_page_maps' );
			foreach ( $this->dep_pages as $name => $obj ) {
				// if not available in option, then create one.
				if ( ! in_array( $name, $opt_page_keys, true ) ) {
					$title                            = ! empty( $obj['title'] ) ? $obj['title'] : ucwords( $name );
					$contents                         = ! empty( $obj['content'] ) ? $obj['content'] : [];
					$opt_page_keys[]                  = $name;
					$maybe_page_exist                 = get_page_by_title( $title );
					$created_page                     = null !== $maybe_page_exist ? $maybe_page_exist : $this->create_post( $title, 'page' );
					$opt_page_ids[]                   = $created_page;
					$opt_page_maps[ 'page_' . $name ] = $created_page;
					$post_metas                       = [ '_wp_page_template' => 'page-templates/' . $name . '.php' ];
					if ( $contents ) {
						$post_metas = array_merge( $post_metas, $contents );
					}
					Helpers\Helper::upfield( $created_page, $post_metas );
				}
			}
			// update options.
			update_option( 'ma_page_ids', $opt_page_ids );
			update_option( 'ma_page_keys', $opt_page_keys );
			update_option( 'ma_page_maps', $opt_page_maps );
		}

		/**
		 * Map dependecy pages
		 */
		private function map_dep_pages() {
			$this->dep_pages = [
				'campaign' => [
					'title' => __( 'All Campaigns', 'masjid' ),
				],
				'history'  => [
					'title' => __( 'Our History', 'masjid' ),
				],
				'home'     => [
					'title'   => __( 'Home', 'masjid' ),
					'content' => [
						'campaign_title'    => __( 'Give Your Best', 'masjid' ),
						'campaign_subtitle' => __( 'These are some reasons why you should give donation through our programs', 'masjid' ),
						'lecture_title'     => __( 'Latest Lecture Schedules', 'masjid' ),
						'lecture_subtitle'  => __( 'Some active lecture schedules that you can attend along with your family nor friends', 'masjid' ),
						'article_title'     => __( 'Latest Published Articles', 'masjid' ),
						'article_subtitle'  => __( 'We will keep sharing useful articles both for your dunya and hereafter', 'masjid' ),
					],
				],
				'lecture'  => [
					'title' => __( 'Lecture Schedules', 'masjid' ),
				],
				'article'  => [
					'title' => __( 'All Articles', 'masjid' ),
				],
			];
		}

		/**
		 * Create a post
		 *
		 * @param string $post_title post title.
		 * @param string $post_type  post type.
		 *
		 * @return int|WP_Error
		 */
		private function create_post( $post_title, $post_type = 'post' ) {
			$new_post = wp_insert_post(
				[
					'post_type'   => $post_type,
					'post_status' => 'publish',
					'post_title'  => $post_title,
					'post_name'   => sanitize_title( $post_title ),
				]
			);

			return $new_post;
		}
	}
}

Activator::init();
