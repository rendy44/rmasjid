<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 5:16 PM
 *
 * @package Masjid/Transaction
 */

namespace Masjid\Transactions;

use Masjid\Helpers;
use DateTime;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Payment' ) ) {

	/**
	 * Class Payment
	 */
	class Payment {

		/**
		 * Generate a new payment
		 *
		 * @param int $campaign_id campaign_id.
		 *
		 * @return bool|int
		 */
		public static function create_payment( $campaign_id ) {
			$result      = false;
			$title       = '#' . uniqid( 'ma', true );
			$new_payment = wp_insert_post(
				[
					'post_type'   => 'bayar',
					'post_title'  => strtoupper( $title ),
					'post_name'   => sanitize_title( $title ),
					'post_status' => 'publish',
				]
			);
			if ( $new_payment ) {
				Helpers\Helper::upfield(
					$new_payment,
					[
						'campaign_id' => $campaign_id,
						'status'      => 'waiting_payment',
					]
				);
				$result = $new_payment;
			}

			return $result;
		}

		/**
		 * Continue a payment
		 *
		 * @param int    $payment_id  payment id.
		 * @param int    $campaign_id campaign id.
		 * @param int    $amount      donantion amount.
		 * @param string $name        person name.
		 * @param string $email       person email.
		 * @param int    $hide_name   either show or hide name as anonymous.
		 * @param string $message     message.
		 *
		 * @return array
		 */
		public static function continue_payment( $payment_id, $campaign_id, $amount, $name, $email, $hide_name, $message = '' ) {
			$result                 = [ 'status' => 'error' ];
			$datetime_now_timestamp = current_time( 'timestamp' );
			$availability           = self::is_campaign_available_to_continue_payment( $campaign_id );
			if ( 'success' === $availability['status'] ) {
				$unique          = str_pad( wp_rand( 0, pow( 10, 3 ) - 1 ), 3, '0', STR_PAD_LEFT );
				$total_amount    = (int) $amount + (int) $unique;
				$datetime_expiry = new DateTime();
				$datetime_expiry->modify( '+1 day' );
				$expiry_timestamp = $datetime_expiry->getTimestamp();
				Helpers\Helper::upfield(
					$payment_id,
					[
						'amount'           => $amount,
						'total_amount'     => $total_amount,
						'unique_amount'    => $unique,
						'name'             => $name,
						'email'            => $email,
						'hide_name'        => $hide_name,
						'message'          => $message,
						'expiry'           => $expiry_timestamp,
						'status'           => 'waiting_confirmation',
						'payment_datetime' => $datetime_now_timestamp,
					]
				);
				// Send invoice email to user.
				Helpers\Mailer::send_email_after_making_payment( $payment_id );
				$result['status'] = 'success';
			} else {
				$result = $availability;
			}

			return $result;
		}

		/**
		 * Confirm the payment
		 *
		 * @param int $payment_id payment id.
		 *
		 * @return array
		 */
		public static function confirm_payment( $payment_id ) {
			$result                 = [ 'status' => 'error' ];
			$datetime_now_timestamp = current_time( 'timestamp' );
			$status                 = Helpers\Helper::pfield( 'status', $payment_id );
			if ( 'waiting_confirmation' === $status ) {
				Helpers\Helper::upfield(
					$payment_id,
					[
						'status'                => 'waiting_validation',
						'confirmation_datetime' => $datetime_now_timestamp,
					]
				);
				// Send email to user and admin.
				Helpers\Mailer::send_email_after_making_confirmation( $payment_id );
				$result['status'] = 'success';
			} else {
				$result['message'] = __( 'You are not allowed to perform this action', 'masjid' );
			}

			return $result;
		}

		/**
		 * Validate the payment
		 *
		 * @param int $payment_id payment id.
		 *
		 * @return array
		 */
		public static function validate_payment( $payment_id ) {
			$result                 = [ 'status' => 'error' ];
			$datetime_now_timestamp = current_time( 'timestamp' );
			$status                 = Helpers\Helper::pfield( 'status', $payment_id );
			if ( 'waiting_validation' === $status ) {
				$campaign_id = Helpers\Helper::pfield( 'campaign_id', $payment_id );
				// Make a charge since the payment is success.
				self::charge_payment_into_campaign( $campaign_id, $payment_id );
				// update some fields.
				Helpers\Helper::upfield(
					$payment_id,
					[
						'validated_by'        => wp_get_current_user()->ID,
						'status'              => 'done',
						'validation_datetime' => $datetime_now_timestamp,
					]
				);
				// Send email to user.
				Helpers\Mailer::send_email_after_making_validation( $payment_id );
				$result['status'] = 'success';
			} else {
				$result['message'] = __( 'You are not allowed to perform this action', 'masjid' );
			}

			return $result;
		}

		/**
		 * Reject the payment
		 *
		 * @param int $payment_id payment id.
		 *
		 * @return array
		 */
		public static function reject_payment( $payment_id ) {
			$result                 = [ 'status' => 'error' ];
			$datetime_now_timestamp = current_time( 'timestamp' );
			$status                 = Helpers\Helper::pfield( 'status', $payment_id );
			if ( 'waiting_validation' === $status ) {
				// update some fields.
				Helpers\Helper::upfield(
					$payment_id,
					[
						'rejected_by'        => wp_get_current_user()->ID,
						'status'             => 'rejected',
						'rejection_datetime' => $datetime_now_timestamp,
					]
				);
				// Send email to user.
				Helpers\Mailer::send_email_after_making_rejection( $payment_id );
				$result['status'] = 'success';
			} else {
				$result['message'] = __( 'You are not allowed to perform this action', 'masjid' );
			}

			return $result;
		}

		/**
		 * Charge payment into campaign
		 *
		 * @param int $campaign_id .
		 * @param int $payment_id  .
		 */
		private static function charge_payment_into_campaign( $campaign_id, $payment_id ) {
			$datetime_now_timestamp         = current_time( 'timestamp' );
			$campaign_target                = (int) Helpers\Helper::pfield( 'main_detail_target', $campaign_id );
			$campaign_collected             = (int) Helpers\Helper::pfield( 'main_detail_collected', $campaign_id );
			$payment_total_amount           = (int) Helpers\Helper::pfield( 'total_amount', $payment_id );
			$new_campaign_collected         = $campaign_collected + $payment_total_amount;
			$new_campaign_collected_percent = $new_campaign_collected * 100 / $campaign_target;
			Helpers\Helper::upfield(
				$campaign_id,
				[
					'main_detail_collected'         => $new_campaign_collected,
					'last_success_donation'         => $datetime_now_timestamp,
					'main_detail_collected_percent' => (int) $new_campaign_collected_percent,
				]
			);
		}

		/**
		 * Check campaign availability
		 *
		 * @param int $campaign_id .
		 *
		 * @return array
		 */
		public static function is_campaign_available_to_continue_payment( $campaign_id ) {
			$result    = [ 'status' => 'error' ];
			$target    = Helpers\Helper::pfield( 'main_detail_target', $campaign_id );
			$collected = Helpers\Helper::pfield( 'main_detail_collected', $campaign_id );
			$due_date  = Helpers\Helper::pfield( 'main_detail_due_date', $campaign_id );
			if ( $collected >= $target ) {
				$result['message'] = __( 'Campaign is closed due it already meet its target', 'masjid' );
			} else {
				if ( empty( $due_date ) ) {
					$result['status'] = 'success';
				} else {
					$datetime_now_timestamp = current_time( 'timestamp' );
					if ( $datetime_now_timestamp > $due_date ) {
						$result['message'] = __( 'Campaign is closed due it already meet its due date', 'masjid' );
					} else {
						$result['status'] = 'success';
					}
				}
			}

			return $result;
		}

		/**
		 * Get payment overview
		 *
		 * @return array
		 */
		public static function get_payment_overview() {
			global $wpdb;
			$result = [];
			// Total income.
			$total_income = wp_cache_get( 'total_income', 'payment' );
			if ( ! $total_income ) {
				$result_db = $wpdb->get_var(
					$wpdb->prepare(
						"
    SELECT SUM(pm.meta_value) FROM {$wpdb->postmeta} pm
    LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
    WHERE pm.meta_key = '%s' 
    AND p.post_status = '%s' 
    AND p.post_type = '%s'
  ",
						'main_detail_collected',
						'publish',
						'donasi'
					)
				);
				wp_cache_set( 'total_income', $result_db, 'payment', 3600 );
				$total_income = $result_db;
			}
			$result['total_income']           = (int) $total_income;
			$result['total_income_formatted'] = number_format( $result['total_income'], 0, ',', '.' );
			// Total payment.
			$query_all_payments = get_transient( 'all_payments' );
			if ( false === $query_all_payments ) {
				$query_all_payments = Helpers\Helper::setup_query( 1, 'bayar', [], - 1 );
				set_transient( 'all_payments', $query_all_payments, 3600 );
			}
			$result['total_payment']           = (int) $query_all_payments->found_posts;
			$result['total_payment_formatted'] = number_format( $result['total_payment'], 0, ',', '.' );
			wp_reset_postdata();
			// Total waiting payment.
			$query_all_waiting_payments = get_transient( 'all_waiting_payments' );
			if ( false === $query_all_waiting_payments ) {
				$query_all_waiting_payments = Helpers\Helper::setup_query(
					1,
					'bayar',
					[
						[
							'key'   => 'status',
							'value' => 'waiting_payment',
						],
					],
					- 1
				);
				set_transient( 'all_waiting_payments', $query_all_waiting_payments, 3600 );
			}
			$result['total_waiting_payment']           = (int) $query_all_waiting_payments->found_posts;
			$result['total_waiting_payment_formatted'] = number_format( $result['total_waiting_payment'], 0, ',', '.' );
			wp_reset_postdata();
			// Total waiting validation.
			$query_all_waiting_validations = get_transient( 'all_waiting_validations' );
			if ( false === $query_all_waiting_validations ) {
				$query_all_waiting_validations = Helpers\Helper::setup_query(
					1,
					'bayar',
					[
						[
							'key'   => 'status',
							'value' => 'waiting_validation',
						],
					],
					- 1
				);
				set_transient( 'all_waiting_validations', $query_all_waiting_validations, 3600 );
			}
			$result['total_waiting_validation']           = (int) $query_all_waiting_validations->found_posts;
			$result['total_waiting_validation_formatted'] = number_format( $result['total_waiting_validation'], 0, ',', '.' );
			wp_reset_postdata();
			// Total rejected.
			$query_all_rejected = get_transient( 'all_rejected' );
			if ( false === $query_all_rejected ) {
				$query_all_rejected = Helpers\Helper::setup_query(
					1,
					'bayar',
					[
						[
							'key'   => 'status',
							'value' => 'rejected',
						],
					],
					- 1
				);
				set_transient( 'all_rejected', $query_all_rejected, 3600 );
			}
			$result['total_rejected']           = (int) $query_all_rejected->found_posts;
			$result['total_rejected_formatted'] = number_format( $result['total_rejected'], 0, ',', '.' );
			wp_reset_postdata();

			return $result;
		}

		/**
		 * Count payment need mod
		 *
		 * @param bool $html wheter return as int or html string.
		 *
		 * @return int|string
		 */
		public static function get_payment_need_mod( $html = true ) {
			$count = self::get_payment_overview();

			return $html ? ( $count['total_waiting_validation'] > 0 ? sprintf( ' <span class="awaiting-mod">%d</span>', $count['total_waiting_validation'] ) : '' ) : (int) $count['total_waiting_validation'];
		}

		/**
		 * Save payment to session
		 *
		 * @param int $campaign_id    .
		 * @param int $new_payment_id .
		 */
		private static function save_payment_session( $campaign_id, $new_payment_id ) {
			$_SESSION[ 'pay_' . $campaign_id ] = $new_payment_id;
		}

		/**
		 * Find payment from session
		 *
		 * @param int $campaign_id .
		 *
		 * @return bool|string
		 */
		private static function find_payment_session( $campaign_id ) {
			return isset( $_SESSION[ 'pay_' . $campaign_id ] ) ? $_SESSION[ 'pay_' . $campaign_id ] : false;
		}

		/**
		 * Remove payment of session
		 *
		 * @param int $campaign_id .
		 */
		private static function remove_payment_session( $campaign_id ) {
			unset( $_SESSION[ 'pay_' . $campaign_id ] );
		}

		/**
		 * List success payments
		 *
		 * @param int $campaign_id campaign id.
		 *
		 * @return array
		 */
		public static function success_payments( $campaign_id ) {
			$result             = [];
			$query_all_payments = Helpers\Helper::setup_query(
				1,
				'bayar',
				[
					'relation' => 'and',
					[
						'key'   => 'status',
						'value' => 'done',
					],
					[
						'key'   => 'campaign_id',
						'value' => $campaign_id,
					],
				],
				- 1
			);
			if ( $query_all_payments->have_posts() ) {
				$date_format     = get_option( 'date_format' );
				$time_format     = get_option( 'time_format' );
				$datetime_format = $date_format . ' ' . $time_format;
				while ( $query_all_payments->have_posts() ) {
					$query_all_payments->the_post();
					$raw_total_amount = (float) Helpers\Helper::pfield( 'total_amount' );
					$result[]         = [
						'id'                     => get_the_ID(),
						'clean_total_amount'     => $raw_total_amount,
						'total_formatted_amount' => number_format( $raw_total_amount, 0, ',', '.' ),
						'name'                   => esc_html( Helpers\Helper::pfield( 'name' ) ),
						'hide_name'              => (bool) Helpers\Helper::pfield( 'hide_name' ),
						'beautify_datetime'      => date_i18n( $datetime_format, Helpers\Helper::pfield( 'validation_datetime' ) ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'message'                => esc_html( Helpers\Helper::pfield( 'message' ) ),
					];
				}
			}
			wp_reset_postdata();

			return $result;
		}
	}
}
