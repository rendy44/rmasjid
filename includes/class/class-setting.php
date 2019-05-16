<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 7:41 AM
 *
 * @package Masjid/Settings
 */

namespace Masjid\Settings;

use Masjid\Helpers;
use Masjid\Transactions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Setting' ) ) {

	/**
	 * Class Setting
	 */
	class Setting {

		/**
		 * Private instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Singleton
		 *
		 * @return Setting|null
		 */
		public static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * MaSetting constructor.
		 */
		private function __construct() {
			$this->add_theme_support();
			$this->wp_setting();
			$this->remove_page_editor();
			$this->admin_setting();
			$this->add_custom_action();
		}

		/**
		 * Add theme support
		 */
		private function add_theme_support() {
			add_theme_support( 'title-tag' );
			add_theme_support( 'menus' );
			add_theme_support( 'post-thumbnails' );
			register_nav_menus(
				[
					'main_nav' => __( 'Main Nav', 'masjid' ),
				]
			);
			register_sidebar(
				[
					'name'          => __( 'Sidebar' ),
					'id'            => 'ma_right_bar',
					'before_widget' => '<div class="card widget-item mb-4">',
					'before_title'  => '<h5 class="card-header">',
					'after_title'   => '</h5>',
					'after_widget'  => '</div>',
				]
			);
		}

		/**
		 * Remove page editor for every pages that have custom template
		 */
		private function remove_page_editor() {
			add_action( 'init', [ $this, 'remove_page_editor_callback' ] );
		}

		/**
		 * Override default wp setting
		 */
		private function wp_setting() {
			add_filter( 'excerpt_length', [ $this, 'number_of_excerpt_callback' ] );
			add_filter( 'show_admin_bar', '__return_false' );
			add_filter(
				'wp_mail_content_type',
				function ( $content_type ) {
					return 'text/html';
				}
			);
			add_filter( 'comment_form_default_fields', [ $this, 'comment_form_hide_cookies_consent' ] );
		}

		/**
		 * Additional hooks fot admin
		 */
		private function admin_setting() {
			add_action( 'save_post', [ $this, 'clean_campaign_transient_callback' ] );
			add_action( 'updated_post_meta', [ $this, 'clean_campaign_and_payment_transient' ], 10, 4 );
			add_filter( 'post_row_actions', [ $this, 'modify_list_row_actions_callback' ], 10, 2 );
			add_action( 'admin_notices', [ $this, 'need_mod_payments_notice_callback' ] );
		}

		/**
		 * Add custom action
		 */
		private function add_custom_action() {
			add_action( 'admin_action_validate', [ $this, 'validate_payment_callback' ] );
			add_action( 'admin_action_reject', [ $this, 'reject_payment_callback' ] );
		}

		/**
		 * Callback to display admin notices
		 */
		public function need_mod_payments_notice_callback() {
			$counts = Transactions\Payment::get_payment_need_mod( false );
			if ( $counts > 0 ) {
				$go_url = admin_url( 'edit.php?post_type=bayar' );
				?>
				<div class="notice notice-warning">
					<?php echo '<p><strong>' . __( 'Importnat!', 'masjid' ) . ' </strong>' . __( 'You have', 'masjid' ) . ' <a href="' . $go_url . '"><strong>' . $counts . '</strong></a> ' . __( 'payments(s) that need to be validated immediately.', 'masjid' ) . '</p>'; // phpcs:ignore WordPress ?>
				</div>
				<?php
			}
		}

		/**
		 * Callback to validate payment
		 */
		public function validate_payment_callback() {
			$nonce   = isset( $_GET['_nonce'] ) ? $_GET['_nonce'] : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput
			$post_id = isset( $_GET['post'] ) ? $_GET['post'] : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput
			if ( wp_verify_nonce( $nonce, 'validate_nonce_validate' ) ) {
				if ( $post_id ) {
					$post_type = get_post_type( $post_id );
					if ( 'bayar' === $post_type ) {
						$status = Helpers\Helper::pfield( 'status', $post_id );
						if ( 'waiting_validation' === $status ) {
							$validate = Transactions\Payment::validate_payment( $post_id );
							if ( 'success' === $validate['status'] ) {
								$redirect_url = admin_url( 'edit.php?post_type=bayar' );
								wp_die( '<h4>' . __( 'Congratulation', 'masjid' ) . '</h4><p>' . __( 'You have validated the payment successfully. Please', 'masjid' ) . ' <a href="' . $redirect_url . '">' . __( 'go back', 'masjid' ) . '</a></p>', __( 'Success', 'masjid' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
							} else {
								wp_die( $validate['message'] ); // phpcs:ignore WordPress.Security.EscapeOutput
							}
						} else {
							wp_die( __( 'Not a valid payment', 'masjid' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
						}
					} else {
						wp_die( __( 'Not a valid payment', 'masjid' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
					}
				} else {
					wp_die( __( 'No payment found', 'masjid' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				}
			} else {
				wp_die( __( 'Failed to pass security check', 'masjid' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
			}
		}

		/**
		 * Reject payment
		 */
		public function reject_payment_callback() {
			$nonce   = isset( $_GET['_nonce'] ) ? $_GET['_nonce'] : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput
			$post_id = isset( $_GET['post'] ) ? $_GET['post'] : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput
			if ( wp_verify_nonce( $nonce, 'validate_nonce_reject' ) ) {
				if ( $post_id ) {
					$post_type = get_post_type( $post_id );
					if ( 'bayar' === $post_type ) {
						$status = Helpers\Helper::pfield( 'status', $post_id );
						if ( 'waiting_validation' === $status ) {
							$reject = Transactions\Payment::reject_payment( $post_id );
							if ( 'success' === $reject['status'] ) {
								$redirect_url = admin_url( 'edit.php?post_type=bayar' );
								wp_die( '<h4>' . __( 'Congratulation', 'masjid' ) . '</h4><p>' . __( 'You have rejected the payment successfully. Please', 'masjid' ) . ' <a href="' . $redirect_url . '">' . __( 'go back', 'masjid' ) . '</a></p>', __( 'Success', 'masjid' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
							} else {
								wp_die( $reject['message'] ); // phpcs:ignore WordPress.Security.EscapeOutput
							}
						} else {
							wp_die( __( 'Not a valid payment', 'masjid' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
						}
					} else {
						wp_die( __( 'Not a valid payment', 'masjid' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
					}
				} else {
					wp_die( __( 'No payment found', 'masjid' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				}
			} else {
				wp_die( __( 'Failed to pass security check', 'masjid' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
			}
		}

		/**
		 * Modify post row actions
		 *
		 * @param array  $actions action.
		 * @param object $post    post object.
		 *
		 * @return mixed
		 */
		public function modify_list_row_actions_callback( $actions, $post ) {
			// Check for your post type.
			if ( 'bayar' === $post->post_type ) {
				$status = Helpers\Helper::pfield( 'status', $post->ID );
				if ( 'waiting_validation' === $status ) {
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
		 * Clear caches
		 */
		private static function clear_cache() {
			delete_transient( 'query_latest_campaigns' );
			wp_cache_delete( 'total_income', 'payment' );
			delete_transient( 'all_payments' );
			delete_transient( 'all_waiting_payments' );
			delete_transient( 'all_waiting_validations' );
			delete_transient( 'all_rejected' );
		}

		/**
		 * Clear cache upon post meta saving
		 *
		 * @param int    $meta_id    meta id.
		 * @param int    $post_id    post id.
		 * @param string $meta_key   meta key.
		 * @param string $meta_value meta value.
		 */
		public function clean_campaign_and_payment_transient( $meta_id, $post_id, $meta_key, $meta_value ) {
			$post_type = get_post_type( $post_id );
			if ( in_array( $post_type, [ 'bayar', 'donasi' ], true ) ) {
				if ( in_array( $meta_key, [ 'main_detail_target', 'status' ], true ) ) {
					self::clear_cache();
				}
			}
		}

		/**
		 * Clean transient campaign upon saving
		 *
		 * @param int $post_id post id.
		 */
		public function clean_campaign_transient_callback( $post_id ) {
			// If this is an autosave, our form has not been submitted, so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			$post_type = get_post_type( $post_id );
			if ( ! in_array( $post_type, [ 'donasi', 'bayar' ], true ) ) {
				return;
			}
			if ( 'auto-draft' === get_post_status( $post_id ) ) {
				return;
			}
			self::clear_cache();
		}

		/**
		 * Callback for removing page editor
		 */
		public function remove_page_editor_callback() {
			$post_id = ! empty( $_GET['post'] ) ? $_GET['post'] : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput
			if ( ! $post_id ) {
				return;
			}
			$template_file = get_post_meta( $post_id, '_wp_page_template', true );
			if ( $template_file ) { // edit the template name.
				remove_post_type_support( 'page', 'editor' );
			}
		}

		/**
		 * Callback for limiting excerpt length
		 *
		 * @param int $length number of excerpt words.
		 *
		 * @return int
		 */
		public function number_of_excerpt_callback( $length ) {
			return (int) $length - 40;
		}

		/**
		 * Callback for removing cookies consent checkbox
		 *
		 * @param array $fields default fields.
		 *
		 * @return mixed
		 */
		public function comment_form_hide_cookies_consent( $fields ) {
			unset( $fields['cookies'] );

			return $fields;
		}
	}
}

Setting::init();
