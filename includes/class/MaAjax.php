<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/25/2019
 * Time: 1:41 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MaAjax' ) ) {
	class MaAjax {

		/**
		 * private instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Singleton
		 *
		 * @return MaAjax|null
		 */
		static function init() {
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
		function lecture_time_detail_callback() {
			$result           = [];
			$datetime_now     = new DateTime();
			$result['now']    = $datetime_now->getTimestamp();
			$result['now_js'] = $result['now'] * 1000;
			$lecture_id       = ! empty( $_GET['lecture'] ) ? $_GET['lecture'] : false;
			if ( $lecture_id ) {
				$lecture_type = MaHelper::pfield( 'lecture_type', $lecture_id );
				if ( 'once' == $lecture_type ) {
					$result['detail'] = MaHelper::pfield( 'once_date', $lecture_id );
//				} else {
//					$period        = MaHelper::pfield( 'recurring_period', $lecture_id );
//					$time_start    = MaHelper::pfield( 'time_start', $lecture_id );
//					$time_now      = $datetime_now->format( 'HH:mm' );
//					$closest_date  = '';
//					$objdate_other = new DateTime();
//					switch ( $period ) {
//						case 'daily':
//							$closest_date = $objdate_other->getTimestamp();
//							if ( $time_start <= $time_now ) {
//								$closest_date = $objdate_other->modify( '+1 day' )->getTimestamp();
//							}
//							break;
//						case 'weekly':
//							$lecture_days = (array) MaHelper::pfield( 'recurring_period_day', $lecture_id );
//							if ( ! empty( $lecture_days ) ) {
//								foreach ( $lecture_days as $day ) {
//
//								}
//							}
//							break;
//						case 'monthly':
//							break;
//					}
				}

				$result['detail_js'] = $result['detail'] * 1000;
			}
			wp_send_json( $result );
		}

		/**
		 * Callback for lecture_archive
		 */
		function lecture_archive_callback() {
			$dttimeformat = 'm/d/Y';
			$key_format   = 'Ymd';
			$result       = [ 'status' => 'error' ];
			$qAllKajian   = MaHelper::setup_query( 1, 'kajian', [], - 1 );
			$addedKajian  = array();
			if ( $qAllKajian->have_posts() ) {
				while ( $qAllKajian->have_posts() ) {
					$qAllKajian->the_post();

					$participants    = MaHelper::pfield( 'participant' );
					$is_ikhwan       = in_array( 'male', $participants ) ? true : false;
					$is_akhwat       = in_array( 'female', $participants ) ? true : false;
					$lecture_type    = MaHelper::pfield( 'lecture_type' );
					$obj_now         = new DateTime();
					$now_date        = $obj_now->getTimestamp();
					$cover_url       = MaHelper::get_thumbnail_url( get_the_ID() );
					$is_tematik      = false;
					$actual_time     = MaHelper::pfield( 'time_start' );
					$actual_time_end = MaHelper::pfield( 'time_end' );
					if ( 'once' == $lecture_type ) {
						$event_date_db = MaHelper::pfield( 'once_date' );
						$objevent_date = new DateTime();
						$objevent_date->setTimestamp( $event_date_db );
//						$actual_date = $objevent_date->format( $dttimeformat );
						$event_date = $objevent_date->format( 'Y-m-d' );
//						$key_date    = $objevent_date->format( $key_format );
						if ( $now_date <= $event_date_db ) {
							$is_tematik    = true;
							$extra_class   = [ $is_tematik ? 'e_tematik' : 'e_rutin' ];
							$extra_class[] = $is_ikhwan ? 'e_ikhwan' : '';
							$extra_class[] = $is_akhwat ? 'e_akhwat' : '';
							$addedKajian[] = array(
								'id'              => get_the_ID(),
								'title'           => get_the_title(),
								'url'             => get_permalink(),
								'start'           => $event_date,
								'classNames'      => $extra_class,
								'backgroundColor' => $is_tematik ? '#f8d7da' : '#d1ecf1',
								'borderColor'     => $is_tematik ? '#f5c6cb' : '#bee5eb',
								'textColor'       => $is_tematik ? '#721c24' : '#0c5460'
							);
						}
					} else { //Rutin
						$lecture_period = MaHelper::pfield( 'recurring_period' );
						$onemonth_date  = new DateTime();
						$onemonth_date  = $onemonth_date->modify( '+1 month' );
						switch ( $lecture_period ):
							case 'daily':
								for ( $i = $obj_now; $i <= $onemonth_date; $i->modify( '+1 day' ) ) {
									$extra_class   = [ $is_tematik ? 'e_tematik' : 'e_rutin' ];
									$extra_class[] = $is_ikhwan ? 'e_ikhwan' : '';
									$extra_class[] = $is_akhwat ? 'e_akhwat' : '';
									$addedKajian[] = array(
										'id'              => get_the_ID(),
										'title'           => get_the_title(),
										'url'             => get_permalink(),
										'start'           => $i->format( 'Y-m-d' ),
										'classNames'      => $extra_class,
										'backgroundColor' => $is_tematik ? '#f8d7da' : '#d1ecf1',
										'borderColor'     => $is_tematik ? '#f5c6cb' : '#bee5eb',
										'textColor'       => $is_tematik ? '#721c24' : '#0c5460'
									);
								}
								break;
							case "weekly":
								$lec_days = MaHelper::pfield( 'recurring_period_day' );
								for ( $i = $obj_now; $i <= $onemonth_date; $i->modify( '+1 day' ) ) {
									foreach ( $lec_days as $day ) {
										if ( strtolower( $i->format( 'l' ) ) == $day ) {
											$extra_class   = [ $is_tematik ? 'e_tematik' : 'e_rutin' ];
											$extra_class[] = $is_ikhwan ? 'e_ikhwan' : '';
											$extra_class[] = $is_akhwat ? 'e_akhwat' : '';
											$addedKajian[] = array(
												'id'              => get_the_ID(),
												'title'           => get_the_title(),
												'url'             => get_permalink(),
												'start'           => $i->format( 'Y-m-d' ),
												'classNames'      => $extra_class,
												'backgroundColor' => $is_tematik ? '#f8d7da' : '#d1ecf1',
												'borderColor'     => $is_tematik ? '#f5c6cb' : '#bee5eb',
												'textColor'       => $is_tematik ? '#721c24' : '#0c5460'
											);
										}
									}
								}
								break;
							case "monthly":
								$lec_weeks = MaHelper::pfield( 'recurring_period_week' );
								foreach ( $lec_weeks as $nweek ) {
									$lec_days = MaHelper::pfield( 'recurring_period_day' );
									foreach ( $lec_days as $day ) {
										$montly_timestamp = strtotime( $nweek . ' ' . $day . ' of this month' );
										if ( $now_date > $montly_timestamp ) {
											$montly_timestamp = strtotime( $nweek . ' ' . $day . ' of next month' );
										}

										$event_date    = date( "Y-m-d", $montly_timestamp );
										$extra_class   = [ $is_tematik ? 'e_tematik' : 'e_rutin' ];
										$extra_class[] = $is_ikhwan ? 'e_ikhwan' : '';
										$extra_class[] = $is_akhwat ? 'e_akhwat' : '';
										$addedKajian[] = array(
											'id'              => get_the_ID(),
											'title'           => get_the_title(),
											'url'             => get_permalink(),
											'start'           => $event_date,
											'classNames'      => $extra_class,
											'backgroundColor' => $is_tematik ? '#f8d7da' : '#d1ecf1',
											'borderColor'     => $is_tematik ? '#f5c6cb' : '#bee5eb',
											'textColor'       => $is_tematik ? '#721c24' : '#0c5460'
										);
									}
								}
								break;
						endswitch;
					}
				}
			}
			wp_reset_postdata();

			$result['items']  = $addedKajian;
			$result['status'] = ! empty( $addedKajian ) ? 'success' : 'error';
			wp_send_json( $result );
		}

		/**
		 * Callback for paying campaign
		 */
		function campaign_pay_callback() {
			$result      = [ 'status' => 'error' ];
			$campaign_id = ! empty( $_POST['campaign_id'] ) ? $_POST['campaign_id'] : false;
			if ( DOING_AJAX && defined( 'DOING_AJAX' ) ) {
				$create_payment = MaPayment::create_payment( $campaign_id );
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
		function campaign_pay_continue_callback() {
			$result         = [ 'status' => 'error' ];
			$serialized_obj = ! empty( $_POST['data'] ) ? $_POST['data'] : false;
			$usObj          = maybe_unserialize( $serialized_obj );
			$nonce_field    = MaHelper::get_serialized_val( $usObj, 'nonce_field' );
			$amount         = MaHelper::get_serialized_val( $usObj, 'amount' );
			$name           = MaHelper::get_serialized_val( $usObj, 'name' );
			$hide_name      = MaHelper::get_serialized_val( $usObj, 'hide_name' );
			$email          = MaHelper::get_serialized_val( $usObj, 'email' );
			$message        = MaHelper::get_serialized_val( $usObj, 'message' );
			$payment_id     = MaHelper::get_serialized_val( $usObj, 'payment_id' );
			$campaign_id    = MaHelper::pfield( 'campaign_id', $payment_id );
			if ( wp_verify_nonce( $nonce_field, 'validate_nonce_campaign_payment' ) ) {
				if ( DOING_AJAX && defined( 'DOING_AJAX' ) ) {
					$continue_payment = MaPayment::continue_payment( $payment_id, $campaign_id, $amount, $name, $email, $hide_name, $message );
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
		function campaign_pay_confirm_callback() {
			$result      = [ 'status' => 'error' ];
			$campaign_id = ! empty( $_POST['payment_id'] ) ? $_POST['payment_id'] : false;
			if ( DOING_AJAX && defined( 'DOING_AJAX' ) ) {
				$confirm_payment = MaPayment::confirm_payment( $campaign_id );
				$result          = $confirm_payment;
			}
			wp_send_json( $result );
		}
	}
}

MaAjax::init();
