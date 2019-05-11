<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 5/12/2019
 * Time: 12:09 AM
 *
 * @package Masjid/Helpers
 */

namespace Masjid\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Mailer' ) ) {
	class Mailer {
		/**
		 * Fire the email
		 *
		 * @param array|string $to      email destination.
		 * @param string       $subject email subject.
		 * @param string       $message email content.
		 */
		private static function send_email( $to, $subject, $message ) {
			wp_mail( $to, $subject, $message );
		}

		/**
		 * Convert plain text into beautiful html
		 *
		 * @param string $string plain email content.
		 *
		 * @return string
		 */
		private static function convert_string_to_template( $string ) {
			global $temp;

			return $temp->render( 'email-template', [ 'content' => $string ] );
		}

		/**
		 * Send email after making payment
		 *
		 * @param int $payment_id payment id.
		 */
		public static function send_email_after_making_payment( $payment_id ) {
			$result = '';
		}
	}
}
