<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/20/2019
 * Time: 12:30 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MaCPT' ) ) {

	/**
	 * Class MaCPT
	 */
	class MaCPT {

		/**
		 * Private instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Singleton
		 *
		 * @return MaCPT|null
		 */
		static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * MaCPT constructor.
		 */
		private function __construct() {
			add_action( 'init', [ $this, '_register_kajian' ] );
			add_action( 'init', [ $this, '_register_donasi' ] );
			add_action( 'init', [ $this, '_register_pembayaran' ] );
		}

		/**
		 * Register kajian post type
		 */
		function _register_kajian() {
			$args = [
				'labels'              => [
					'name'          => __( 'Lectures', 'masjid' ),
					'singular_name' => __( 'Lecture', 'masjid' ),
				],
				'supports'            => [
					//					'title',
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
		function _register_donasi() {
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
		function _register_pembayaran() {
			$args = [
				'labels'              => [
					'name'          => __( 'Payments', 'masjid' ),
					'singular_name' => __( 'Payment', 'masjid' ),
				],
				'supports'            => [
					'none',
					//					'title',
					//					'thumbnail',
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
				//				'capabilities'        => [
				//					'create_posts' => false,
				//				],
			];
			register_post_type( 'bayar', $args );

			//			add_action( 'cmb2_admin_init', [ $this, 'donasi_metabox_callback' ] );

			add_filter( 'manage_bayar_posts_columns', [ $this, 'manage_bayar_column_title_callback' ] );
			add_action( 'manage_bayar_posts_custom_column', [ $this, 'manage_bayar_columns_callback' ], 10, 2 );
		}

		/**
		 * Manage payment column title
		 *
		 * @return array
		 */
		function manage_bayar_column_title_callback() {
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
		 * @param $column
		 * @param $post_id
		 */
		function manage_bayar_columns_callback( $column, $post_id ) {
			switch ( $column ) {
				case 'name':
					echo MaHelper::pfield( 'name', $post_id );
					break;
				case 'total_amount':
					$total_amount = (int) MaHelper::pfield( 'total_amount', $post_id );
					echo 'Rp' . number_format( $total_amount, '0', ',', '.' );
					break;
				case 'campaign':
					$campaign_id = MaHelper::pfield( 'campaign_id', $post_id );
					echo '<a href="' . get_edit_post_link( $campaign_id ) . '"><strong>' . get_the_title( $campaign_id ) . '</strong></a>';
					break;
				case 'status':
					$status = MaHelper::pfield( 'status' );
					switch ( $status ) {
						case 'waiting_payment':
							echo '<strong>' . __( 'Waiting Payment', 'masjid' ) . '</strong>';
							break;
						case 'waiting_confirmation':
							echo '<strong>' . __( 'Waiting Confirmation', 'masjid' ) . '</strong>';
							break;
						case 'waiting_validation':
							echo '<strong style="color: #856404; background: #fff3cd">' . __( 'Waiting Validation', 'masjid' ) . '</strong>';
							break;
						case 'done':
							echo '<strong style="color: #155724; background: #d4edda">' . __( 'Done', 'masjid' ) . '</strong>';
							break;
						case 'rejected':
							echo '<strong style="color: #721c24; background: #f8d7da">' . __( 'Rejected', 'masjid' ) . '</strong>';
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
		function manage_donasi_column_title_callback() {
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
		 * @param $column
		 * @param $post_id
		 */
		function manage_donasi_columns_callback( $column, $post_id ) {
			switch ( $column ) {
				case 'target':
					$target = (int) MaHelper::pfield( 'main_detail_target', $post_id );
					echo 'Rp' . number_format( $target, 0, ',', '.' );
					break;
				case 'collected':
					$collected = (int) MaHelper::pfield( 'main_detail_collected', $post_id );
					echo 'Rp' . number_format( $collected, 0, ',', '.' );
					break;
				case 'due_date':
					$target        = (int) MaHelper::pfield( 'main_detail_target', $post_id );
					$collected     = (int) MaHelper::pfield( 'main_detail_collected', $post_id );
					$duedate       = MaHelper::pfield( 'main_detail_due_date', $post_id );
					$calculation   = MaHelper::count_campaign( $target, $collected, $duedate );
					$duedate_style = $duedate ? 'style="color: #721c24; background: #f8d7da"' : '';
					echo '<strong ' . $duedate_style . '>' . $calculation['duedate_html_single'] . '</strong>';
					break;
			}
		}
	}
}

MaCPT::init();