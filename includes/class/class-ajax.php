<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/25/2019
 * Time: 1:41 PM
 *
 * @package Masjid/Includes
 */

namespace Masjid\Includes;

use Masjid\Helpers;
use Masjid\Transactions;
use DateTime;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Ajax' ) ) {

	/**
	 * Class Ajax
	 */
	class Ajax {
		/**
		 * Private instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Singleton
		 *
		 * @return Ajax|null
		 */
		public static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * MaAjax constructor.
		 */
		private function __construct() {
			add_action( 'wp_ajax_lecture_archive', [ $this, 'lecture_archive_callback' ] );
			add_action( 'wp_ajax_nopriv_lecture_archive', [ $this, 'lecture_archive_callback' ] );
			add_action( 'wp_ajax_campaign_pay', [ $this, 'campaign_pay_callback' ] );
			add_action( 'wp_ajax_nopriv_campaign_pay', [ $this, 'campaign_pay_callback' ] );
			add_action( 'wp_ajax_campaign_pay_continue', [ $this, 'campaign_pay_continue_callback' ] );
			add_action( 'wp_ajax_nopriv_campaign_pay_continue', [ $this, 'campaign_pay_continue_callback' ] );
			add_action( 'wp_ajax_campaign_pay_confirm', [ $this, 'campaign_pay_confirm_callback' ] );
			add_action( 'wp_ajax_nopriv_campaign_pay_confirm', [ $this, 'campaign_pay_confirm_callback' ] );
			add_action( 'wp_ajax_lecture_time_detail', [ $this, 'lecture_time_detail_callback' ] );
			add_action( 'wp_ajax_nopriv_lecture_time_detail', [ $this, 'lecture_time_detail_callback' ] );
		}

		/**
		 * Get closest timestamp of lecture
		 */
		public function lecture_time_detail_callback() {
			$result           = [];
			$datetime_now     = new DateTime();
			$result['now']    = $datetime_now->getTimestamp();
			$result['now_js'] = $result['now'] * 1000;
			$lecture_id       = ! empty( $_GET['lecture'] ) ? sanitize_text_field( wp_unslash( $_GET['lecture'] ) ) : false; // phpcs:ignore Standard.Category.SniffName.ErrorCode, WordPress.Security.NonceVerification.Recommended
			if ( $lecture_id ) {
				$lecture_type = Helpers\Helper::pfield( 'lecture_type', $lecture_id );
				if ( 'once' === $lecture_type ) {
					$result['detail'] = Helpers\Helper::pfield( 'once_date', $lecture_id );
				}
				$result['detail_js'] = $result['detail'] * 1000;
			}
			wp_send_json( $result );
		}

		/**
		 * Callback for lecture_archive
		 */
		public function lecture_archive_callback() {
			$dttimeformat = 'm/d/Y';
			$key_format   = 'Ymd';
			$result       = [ 'status' => 'error' ];
			$q_all_kajian = Helpers\Helper::setup_query( 1, 'kajian', [], - 1 );
			$added_kajian = [];
			if ( $q_all_kajian->have_posts() ) {
				while ( $q_all_kajian->have_posts() ) {
					$q_all_kajian->the_post();
					$participants    = Helpers\Helper::pfield( 'participant' );
					$is_ikhwan       = in_array( 'male', $participants, true ) ? true : false;
					$is_akhwat       = in_array( 'female', $participants, true ) ? true : false;
					$lecture_type    = Helpers\Helper::pfield( 'lecture_type' );
					$obj_now         = new DateTime();
					$now_date        = $obj_now->getTimestamp();
					$cover_url       = Helpers\Helper::get_thumbnail_url( get_the_ID() );
					$is_tematik      = false;
					$actual_time     = Helpers\Helper::pfield( 'time_start' );
					$actual_time_end = Helpers\Helper::pfield( 'time_end' );
					if ( 'once' === $lecture_type ) {
						$event_date_db = Helpers\Helper::pfield( 'once_date' );
						$objevent_date = new DateTime();
						$objevent_date->setTimestamp( $event_date_db );
						$event_date = $objevent_date->format( 'Y-m-d' );
						if ( $now_date <= $event_date_db ) {
							$is_tematik     = true;
							$extra_class    = [ $is_tematik ? 'e_tematik' : 'e_rutin' ];
							$extra_class[]  = $is_ikhwan ? 'e_ikhwan' : '';
							$extra_class[]  = $is_akhwat ? 'e_akhwat' : '';
							$added_kajian[] = [
								'id'              => get_the_ID(),
								'title'           => get_the_title(),
								'url'             => get_permalink(),
								'start'           => $event_date,
								'classNames'      => $extra_class,
								'backgroundColor' => $is_tematik ? '#f8d7da' : '#d1ecf1',
								'borderColor'     => $is_tematik ? '#f5c6cb' : '#bee5eb',
								'textColor'       => $is_tematik ? '#721c24' : '#0c5460',
							];
						}
					} else { // Rutin.
						$lecture_period = Helpers\Helper::pfield( 'recurring_period' );
						$onemonth_date  = new DateTime();
						$onemonth_date  = $onemonth_date->modify( '+1 month' );
						switch ( $lecture_period ) {
							case 'daily':
								for ( $i = $obj_now; $i <= $onemonth_date; $i->modify( '+1 day' ) ) {
									$extra_class    = [ $is_tematik ? 'e_tematik' : 'e_rutin' ];
									$extra_class[]  = $is_ikhwan ? 'e_ikhwan' : '';
									$extra_class[]  = $is_akhwat ? 'e_akhwat' : '';
									$added_kajian[] = [
										'id'              => get_the_ID(),
										'title'           => get_the_title(),
										'url'             => get_permalink(),
										'start'           => $i->format( 'Y-m-d' ),
										'classNames'      => $extra_class,
										'backgroundColor' => $is_tematik ? '#f8d7da' : '#d1ecf1',
										'borderColor'     => $is_tematik ? '#f5c6cb' : '#bee5eb',
										'textColor'       => $is_tematik ? '#721c24' : '#0c5460',
									];
								}
								break;
							case 'weekly':
								$lec_days = Helpers\Helper::pfield( 'recurring_period_day' );
								for ( $i = $obj_now; $i <= $onemonth_date; $i->modify( '+1 day' ) ) {
									foreach ( $lec_days as $day ) {
										$day_name = strtolower( $i->format( 'l' ) );
										if ( $day_name === $day ) {
											$extra_class    = [ $is_tematik ? 'e_tematik' : 'e_rutin' ];
											$extra_class[]  = $is_ikhwan ? 'e_ikhwan' : '';
											$extra_class[]  = $is_akhwat ? 'e_akhwat' : '';
											$added_kajian[] = [
												'id'    => get_the_ID(),
												'title' => get_the_title(),
												'url'   => get_permalink(),
												'start' => $i->format( 'Y-m-d' ),
												'classNames' => $extra_class,
												'borderColor' => $is_tematik ? '#f5c6cb' : '#bee5eb',
												'textColor' => $is_tematik ? '#721c24' : '#0c5460',
												'backgroundColor' => $is_tematik ? '#f8d7da' : '#d1ecf1',
											];
										}
									}
								}
								break;
							case 'monthly':
								$lec_weeks = Helpers\Helper::pfield( 'recurring_period_week' );
								foreach ( $lec_weeks as $nweek ) {
									$lec_days = Helpers\Helper::pfield( 'recurring_period_day' );
									foreach ( $lec_days as $day ) {
										$montly_timestamp = strtotime( $nweek . ' ' . $day . ' of this month' );
										if ( $now_date > $montly_timestamp ) {
											$montly_timestamp = strtotime( $nweek . ' ' . $day . ' of next month' );
										}
										$event_date     = date( 'Y-m-d', $montly_timestamp );
										$extra_class    = [ $is_tematik ? 'e_tematik' : 'e_rutin' ];
										$extra_class[]  = $is_ikhwan ? 'e_ikhwan' : '';
										$extra_class[]  = $is_akhwat ? 'e_akhwat' : '';
										$added_kajian[] = [
											'id'          => get_the_ID(),
											'title'       => get_the_title(),
											'url'         => get_permalink(),
											'start'       => $event_date,
											'classNames'  => $extra_class,
											'borderColor' => $is_tematik ? '#f5c6cb' : '#bee5eb',
											'textColor'   => $is_tematik ? '#721c24' : '#0c5460',
											'backgroundColor' => $is_tematik ? '#f8d7da' : '#d1ecf1',
										];
									}
								}
								break;
						}
					}
				}
			}
			wp_reset_postdata();
			$result['items']  = $added_kajian;
			$result['status'] = ! empty( $added_kajian ) ? 'success' : 'error';
			wp_send_json( $result );
		}

		/**
		 * Callback for paying campaign
		 */
		public function campaign_pay_callback() {
			$result      = [ 'status' => 'error' ];
			$campaign_id = ! empty( $_POST['campaign_id'] ) ? sanitize_text_field( wp_unslash( $_POST['campaign_id'] ) ) : false; // phpcs:ignore Standard.Category.SniffName.ErrorCode, WordPress.Security.NonceVerification.Missing
			if ( DOING_AJAX && defined( 'DOING_AJAX' ) ) {
				$create_payment = Transactions\Payment::create_payment( $campaign_id );
				if ( $create_payment ) {
					$result['status']   = 'success';
					$result['callback'] = get_permalink( $create_payment );
				}
			}
			wp_send_json( $result );
		}

		/**
		 * Callback for continue payment
		 */
		public function campaign_pay_continue_callback() {
			$result         = [ 'status' => 'error' ];
			$serialized_obj = ! empty( $_POST['data'] ) ? $_POST['data'] : false; // phpcs:ignore Standard.Category.SniffName.ErrorCode, WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput
			$us_obj         = maybe_unserialize( $serialized_obj );
			$nonce_field    = Helpers\Helper::get_serialized_val( $us_obj, 'nonce_field' );
			$amount         = Helpers\Helper::get_serialized_val( $us_obj, 'amount' );
			$name           = Helpers\Helper::get_serialized_val( $us_obj, 'name' );
			$hide_name      = Helpers\Helper::get_serialized_val( $us_obj, 'hide_name' );
			$email          = Helpers\Helper::get_serialized_val( $us_obj, 'email' );
			$message        = Helpers\Helper::get_serialized_val( $us_obj, 'message' );
			$payment_id     = Helpers\Helper::get_serialized_val( $us_obj, 'payment_id' );
			$campaign_id    = Helpers\Helper::pfield( 'campaign_id', $payment_id );
			if ( wp_verify_nonce( $nonce_field, 'validate_nonce_campaign_payment' ) ) {
				if ( DOING_AJAX && defined( 'DOING_AJAX' ) ) {
					$continue_payment = Transactions\Payment::continue_payment( $payment_id, $campaign_id, $amount, $name, $email, $hide_name, $message );
					$result           = $continue_payment;
				} else {
					$result['message'] = __( 'Something went wrong, please try again later', 'masjid' );
				}
			} else {
				$result['message'] = __( 'Failed to verify security check', 'masjid' );
			}
			wp_send_json( $result );
		}

		/**
		 * Callback for confirming payment
		 */
		public function campaign_pay_confirm_callback() {
			$result      = [ 'status' => 'error' ];
			$campaign_id = ! empty( $_POST['payment_id'] ) ? sanitize_text_field( wp_unslash( $_POST['payment_id'] ) ) : false; // phpcs:ignore Standard.Category.SniffName.ErrorCode, WordPress.Security.NonceVerification.Missing
			if ( DOING_AJAX && defined( 'DOING_AJAX' ) ) {
				$confirm_payment = Transactions\Payment::confirm_payment( $campaign_id );
				$result          = $confirm_payment;
			}
			wp_send_json( $result );
		}
	}
}
Ajax::init();
