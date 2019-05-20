<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/20/2019
 * Time: 12:30 PM
 *
 * @package Masjid/Settinfs
 */

namespace Masjid\Settings;

use Masjid\Helpers;
use Masjid\Transactions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Post_Type' ) ) {

	/**
	 * Class Post_Type
	 */
	class Post_Type {

		/**
		 * Private instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Singleton
		 *
		 * @return \Masjid\Settings\Post_Type|null
		 */
		public static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * MaCPT constructor.
		 */
		private function __construct() {
			add_action( 'init', [ $this, 'register_kajian' ] );
			add_action( 'init', [ $this, 'register_donasi' ] );
			add_action( 'init', [ $this, 'register_pembayaran' ] );
			add_action( 'init', [ $this, 'register_slider' ] );
		}

		/**
		 * Register kajian post type
		 */
		public function register_kajian() {
			$args = [
				'labels'              => [
					'name'          => __( 'Lectures', 'masjid' ),
					'singular_name' => __( 'Lecture', 'masjid' ),
				],
				'supports'            => [
					'title',
					'thumbnail',
				],
				'taxonomies'          => [],
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-calendar-alt',
			];
			register_post_type( 'kajian', $args );
		}

		/**
		 * Register donasi post type
		 */
		public function register_donasi() {
			$args = [
				'labels'              => [
					'name'          => __( 'Campaigns', 'masjid' ),
					'singular_name' => __( 'Campaign', 'masjid' ),
				],
				'supports'            => [
					'title',
					'thumbnail',
				],
				'taxonomies'          => [],
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-heart',
			];
			register_post_type( 'donasi', $args );
			add_filter( 'manage_donasi_posts_columns', [ $this, 'manage_donasi_column_title_callback' ] );
			add_action( 'manage_donasi_posts_custom_column', [ $this, 'manage_donasi_columns_callback' ], 10, 2 );
		}

		/**
		 * Register pembayaran post type
		 */
		public function register_pembayaran() {
			$counts = Transactions\Payment::get_payment_need_mod();
			$args   = [
				'labels'              => [
					'name'          => __( 'Payments', 'masjid' ),
					'singular_name' => __( 'Payment', 'masjid' ),
					'menu_name'     => __( 'Payments', 'masjid' ) . $counts,
				],
				'supports'            => [
					'none',
				],
				'taxonomies'          => [],
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'edit.php?post_type=donasi',
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => false,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-star-filled',
			];
			register_post_type( 'bayar', $args );
			add_filter( 'manage_bayar_posts_columns', [ $this, 'manage_bayar_column_title_callback' ] );
			add_action( 'manage_bayar_posts_custom_column', [ $this, 'manage_bayar_columns_callback' ], 10, 2 );
		}

		/**
		 * Register slider post type
		 */
		public function register_slider() {
			$args = [
				'labels'              => [
					'name'          => __( 'Sliders', 'masjid' ),
					'singular_name' => __( 'Slider', 'masjid' ),
					'menu_name'     => __( 'Sliders', 'masjid' ),
				],
				'supports'            => [
					'title',
				],
				'taxonomies'          => [],
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => false,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-images-alt',
			];
			register_post_type( 'slider', $args );
		}

		/**
		 * Manage payment column title
		 *
		 * @return array
		 */
		public function manage_bayar_column_title_callback() {
			$columns = [
				'cb'           => '<input type="checkbox" />',
				'title'        => __( 'Title', 'masjid' ),
				'name'         => __( 'Name', 'masjid' ),
				'total_amount' => __( 'Total Amount', 'masjid' ),
				'campaign'     => __( 'Campaign', 'masjid' ),
				'status'       => __( 'Status', 'masjid' ),
				'date'         => __( 'Created', 'masjid' ),
			];

			return $columns;
		}

		/**
		 * Manage payment column value
		 *
		 * @param string $column  column name.
		 * @param int    $post_id post id.
		 */
		public function manage_bayar_columns_callback( $column, $post_id ) {
			switch ( $column ) {
				case 'name':
					echo Helpers\Helper::pfield( 'name', $post_id ); // phpcs:ignore WordPress.Security.EscapeOutput
					break;
				case 'total_amount':
					$total_amount = (int) Helpers\Helper::pfield( 'total_amount', $post_id );
					echo 'Rp' . number_format( $total_amount, '0', ',', '.' );
					break;
				case 'campaign':
					$campaign_id = Helpers\Helper::pfield( 'campaign_id', $post_id );
					echo '<a href="' . get_edit_post_link( $campaign_id ) . '"><strong>' . get_the_title( $campaign_id ) . '</strong></a>'; // phpcs:ignore WordPress.Security.EscapeOutput
					break;
				case 'status':
					$status = Helpers\Helper::pfield( 'status' );
					switch ( $status ) {
						case 'waiting_payment':
							echo '<strong>' . __( 'Waiting Payment', 'masjid' ) . '</strong>'; // phpcs:ignore WordPress.Security.EscapeOutput
							break;
						case 'waiting_confirmation':
							echo '<strong>' . __( 'Waiting Confirmation', 'masjid' ) . '</strong>'; // phpcs:ignore WordPress.Security.EscapeOutput
							break;
						case 'waiting_validation':
							echo '<strong style="color: #856404; background: #fff3cd">' . __( 'Waiting Validation', 'masjid' ) . '</strong>'; // phpcs:ignore WordPress.Security.EscapeOutput
							break;
						case 'done':
							echo '<strong style="color: #155724; background: #d4edda">' . __( 'Done', 'masjid' ) . '</strong>'; // phpcs:ignore WordPress.Security.EscapeOutput
							break;
						case 'rejected':
							echo '<strong style="color: #721c24; background: #f8d7da">' . __( 'Rejected', 'masjid' ) . '</strong>'; // phpcs:ignore WordPress.Security.EscapeOutput
							break;
					}
					break;
			}
		}

		/**
		 * Manage campaign column title
		 *
		 * @return array
		 */
		public function manage_donasi_column_title_callback() {
			$columns = [
				'cb'        => '<input type="checkbox" />',
				'title'     => __( 'Title', 'masjid' ),
				'target'    => __( 'Target', 'masjid' ),
				'collected' => __( 'Collected', 'masjid' ),
				'due_date'  => __( 'Due Date', 'masjid' ),
				'date'      => __( 'Created' ),
			];

			return $columns;
		}

		/**
		 * Manage campaign column value
		 *
		 * @param string $column  column name.
		 * @param int    $post_id post id.
		 */
		public function manage_donasi_columns_callback( $column, $post_id ) {
			switch ( $column ) {
				case 'target':
					$target = (int) Helpers\Helper::pfield( 'main_detail_target', $post_id );
					echo 'Rp' . number_format( $target, 0, ',', '.' );
					break;
				case 'collected':
					$collected = (int) Helpers\Helper::pfield( 'main_detail_collected', $post_id );
					echo 'Rp' . number_format( $collected, 0, ',', '.' );
					break;
				case 'due_date':
					$target        = (int) Helpers\Helper::pfield( 'main_detail_target', $post_id );
					$collected     = (int) Helpers\Helper::pfield( 'main_detail_collected', $post_id );
					$duedate       = Helpers\Helper::pfield( 'main_detail_due_date', $post_id );
					$calculation   = Helpers\Helper::count_campaign( $target, $collected, $duedate );
					$duedate_style = $duedate ? 'style="color: #721c24; background: #f8d7da"' : '';
					echo '<strong ' . $duedate_style . '>' . $calculation['duedate_html_single'] . '</strong>'; // phpcs:ignore WordPress.Security.EscapeOutput
					break;
			}
		}
	}
}

Post_Type::init();
