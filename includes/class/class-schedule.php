<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 5/25/2019
 * Time: 3:56 PM
 *
 * @package Masjdi/Includes
 */

namespace Masjid\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Schedule' ) ) {

	/**
	 * Class Schedule
	 *
	 * @package Masjid\Includes
	 */
	class Schedule {

		/**
		 * Muslimsalat.com api key
		 *
		 * @var string
		 */
		private $muslimsalat_key = '';
		/**
		 * Salat city/zip code
		 *
		 * @var string
		 */
		private $salat_city_name = '';
		/**
		 * Instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Singleton
		 *
		 * @return \Masjid\Includes\Schedule|null
		 */
		public static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Schedule constructor.
		 */
		private function __construct() {
			$this->muslimsalat_key = '6b4c60b4172d0cb6a5836877a8be379b';
			$this->salat_city_name = get_theme_mod( 'location', 'sleman' );
			$this->check_event_schedule();
			add_action( 'masjid_fetch_salat_times', [ $this, 'fetch_salat_times_callback' ] );
		}

		/**
		 * Get current datetime
		 *
		 * @return false|string
		 */
		private function log_last_event() {
			$date_format      = get_option( 'date_format' );
			$time_format      = get_option( 'time_format' );
			$date_time_format = $date_format . ' ' . $time_format;

			return date( $date_time_format );
		}

		/**
		 * Check next scheduled event
		 */
		private function check_event_schedule() {
			if ( ! wp_next_scheduled( 'masjid_fetch_salat_times' ) ) {
				wp_schedule_event( time(), 'hourly', 'masjid_fetch_salat_times' );
			}
		}

		/**
		 * Callback for fetching salat times
		 */
		public function fetch_salat_times_callback() {
			$city_fixed = strtolower( $this->salat_city_name );
			$now_string = $this->log_last_event();
			$api_key    = $this->muslimsalat_key;
			$remote     = wp_remote_get( 'https://muslimsalat.com/london/' . $city_fixed . '/daily.json?key=' . $api_key );
			$result_obj = json_decode( $remote['body'], true );
			if ( 1 === $result_obj['status_valid'] ) {
				$result_clean = $result_obj['items'][0];
				update_option( 'salat_times_date', $result_clean['date_for'] );
				update_option( 'salat_times_city', $result_obj['city'] );
				update_option( 'salat_times_country', $result_obj['country'] );
				update_option( 'salat_times', $result_clean );
				update_option( 'salat_times_last_success', $now_string );
			}
			update_option( 'salat_times_raw_result', $result_obj );
			update_option( 'salat_times_last_fetch', $now_string );
		}
	}
}

Schedule::init();
