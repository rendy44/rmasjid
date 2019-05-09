<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 7:41 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MaSetting' ) ) {

	/**
	 * Class MaSetting
	 */
	class MaSetting {

		/**
		 * Private instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Singleton
		 *
		 * @return MaSetting|null
		 */
		static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * MaSetting constructor.
		 */
		private function __construct() {
			$this->_add_theme_support();
			$this->_wp_setting();
			$this->_remove_page_editor();
			$this->_admin_setting();
			$this->_add_custom_action();
		}

		/**
		 * Add theme support
		 */
		private function _add_theme_support() {
			add_theme_support( 'title-tag' );
			add_theme_support( 'menus' );
			add_theme_support( 'post-thumbnails' );
			register_nav_menus( [
				'main_nav' => __( 'Main Nav', 'masjid' ),
//				'foot_nav' => __( 'Footer Nav', 'masjid' ),
			] );
			register_sidebar( [
				'name'          => __( 'Sidebar' ),
				'id'            => 'ma_right_bar',
				'before_widget' => '<div class="card widget-item mb-4">',
				'before_title'  => '<h5 class="card-header">',
				'after_title'   => '</h5>',
				'after_widget'  => '</div>',
			] );
		}

		/**
		 * Remove page editor for every pages that have custom template
		 */
		private function _remove_page_editor() {
			add_action( 'init', [ $this, 'remove_page_editor_callback' ] );
		}

		/**
		 * Override default wp setting
		 */
		private function _wp_setting() {
			add_filter( 'excerpt_length', [ $this, 'number_of_excerpt_callback' ] );
			add_filter( 'show_admin_bar', '__return_false' );
		}

		/**
		 * Additional hooks fot admin
		 */
		private function _admin_setting() {
			add_action( 'save_post', [ $this, 'modify_lecture_title_callback' ] );
			add_filter( 'post_row_actions', [ $this, 'modify_list_row_actions_callback' ], 10, 2 );
		}

		/**
		 * Add custom action
		 */
		private function _add_custom_action() {
			add_action( 'admin_action_validate', [ $this, 'validate_payment_callback' ] );
			add_action( 'admin_action_reject', [ $this, 'reject_payment_callback' ] );
		}

		/**
		 * Callback to validate payment
		 */
		function validate_payment_callback() {
			$nonce   = isset( $_GET['_nonce'] ) ? $_GET['_nonce'] : false;
			$post_id = isset( $_GET['post'] ) ? $_GET['post'] : false;

			if ( wp_verify_nonce( $nonce, 'validate_nonce_validate' ) ) {
				if ( $post_id ) {
					$post_type = get_post_type( $post_id );
					if ( 'bayar' == $post_type ) {
						$status = MaHelper::pfield( 'status', $post_id );
						if ( 'waiting_validation' == $status ) {
							$validate = MaPayment::validate_payment( $post_id );
							if ( 'success' == $validate['status'] ) {
								$redirect_url = admin_url( 'edit.php?post_type=bayar' );
								wp_die( '<h4>' . __( 'Congratulation', 'masjid' ) . '</h4><p>' . __( 'You have validated the payment successfully. Please', 'masjid' ) . ' <a href="' . $redirect_url . '">' . __( 'go back', 'masjid' ) . '</a></p>', __( 'Success', 'masjid' ) );
							} else {
								wp_die( $validate['message'] );
							}
						} else {
							wp_die( __( 'Not a valid payment', 'masjid' ) );
						}
					} else {
						wp_die( __( 'Not a valid payment', 'masjid' ) );
					}
				} else {
					var_dump( $_GET );
					wp_die( __( 'No payment found', 'masjid' ) );
				}
			} else {
				wp_die( __( 'Failed to pass security check', 'masjid' ) );
			}
		}

		function reject_payment_callback() {
			$nonce   = isset( $_GET['_nonce'] ) ? $_GET['_nonce'] : false;
			$post_id = isset( $_GET['post'] ) ? $_GET['post'] : false;

			if ( wp_verify_nonce( $nonce, 'validate_nonce_reject' ) ) {
				if ( $post_id ) {
					$post_type = get_post_type( $post_id );
					if ( 'bayar' == $post_type ) {
						$status = MaHelper::pfield( 'status', $post_id );
						if ( 'waiting_validation' == $status ) {
							$reject = MaPayment::reject_payment( $post_id );
							if ( 'success' == $reject['status'] ) {
								$redirect_url = admin_url( 'edit.php?post_type=bayar' );
								wp_die( '<h4>' . __( 'Congratulation', 'masjid' ) . '</h4><p>' . __( 'You have rejected the payment successfully. Please', 'masjid' ) . ' <a href="' . $redirect_url . '">' . __( 'go back', 'masjid' ) . '</a></p>', __( 'Success', 'masjid' ) );
							} else {
								wp_die( $reject['message'] );
							}
						} else {
							wp_die( __( 'Not a valid payment', 'masjid' ) );
						}
					} else {
						wp_die( __( 'Not a valid payment', 'masjid' ) );
					}
				} else {
					var_dump( $_GET );
					wp_die( __( 'No payment found', 'masjid' ) );
				}
			} else {
				wp_die( __( 'Failed to pass security check', 'masjid' ) );
			}
		}

		/**
		 * Modify post row actions
		 *
		 * @param $actions
		 * @param $post
		 *
		 * @return mixed
		 */
		function modify_list_row_actions_callback( $actions, $post ) {
			// Check for your post type.
			if ( 'bayar' == $post->post_type ) {
				$status = MaHelper::pfield( 'status', $post->ID );
				if ( 'waiting_validation' == $status ) {
					$url                 = admin_url( 'post.php?post=' . $post->ID );
					$validate_link       = add_query_arg( [ 'action' => 'validate' ], $url );
					$fix_validate_link   = wp_nonce_url( $validate_link, 'validate_nonce_validate', '_nonce' );
					$reject_link         = add_query_arg( [ 'action' => 'reject' ], $url );
					$fix_reject_link     = wp_nonce_url( $reject_link, 'validate_nonce_reject', '_nonce' );
					$actions['validate'] = '<a href="' . $fix_validate_link . '">' . __( 'Validate', 'masjid' ) . '</a>';
					$actions['reject']   = '<a href="' . $fix_reject_link . '">' . __( 'Reject', 'masjid' ) . '</a>';
				}
			}

			return $actions;
		}

		/**
		 * Modify lecture post title upon saving
		 *
		 * @param $post_id
		 */
		function modify_lecture_title_callback( $post_id ) {
			// If this is an autosave, our form has not been submitted, so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			// Check the user's permissions.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
			if ( 'kajian' != get_post_type( $post_id ) ) {
				return;
			}
			if ( 'auto-draft' == get_post_status( $post_id ) ) {
				return;
			}
			// remove the action to avoid infinity loop
			remove_action( 'save_post', [ $this, 'modify_lecture_title_callback' ] );

			// type
			//			$lec_type     = MaHelper::pfield( 'lecture_type', $post_id );
			//			$lec_material = MaHelper::pfield( 'material_title', $post_id );
			//			$post_title   = ( 'recurring' == $lec_type ? __( 'Recurring Lecture', 'masjid' ) : __( 'Special Lecture', 'masjid' ) );
			//			$post_title   .= ' ';
			//			$post_title   .= $lec_material;
			$post_title = MaHelper::pfield( 'material_title', $post_id );

			wp_update_post( [
				'ID'         => $post_id,
				'post_title' => $post_title,
				'post_name'  => sanitize_title( $post_title ),
			] );

			// reassign the action
			add_action( 'save_post', [ $this, 'modify_lecture_title_callback' ] );
		}

		/**
		 * Callback for removing page editor
		 */
		function remove_page_editor_callback() {
			$post_id = ! empty( $_GET['post'] ) ? $_GET['post'] : false;
			if ( ! $post_id ) {
				return;
			}

			$template_file = get_post_meta( $post_id, '_wp_page_template', true );
			if ( $template_file ) { // edit the template name
				remove_post_type_support( 'page', 'editor' );
			}
		}

		/**
		 * Callback for limiting excerpt length
		 *
		 * @param $length
		 *
		 * @return int
		 */
		function number_of_excerpt_callback( $length ) {
			return (int) $length - 40;
		}
	}
}

MaSetting::init();