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
		 * @param string $title  plain email title.
		 * @param string $string plain email content.
		 *
		 * @return string
		 */
		private static function convert_string_to_template( $title, $string ) {
			global $temp;

			return $temp->render( 'email-template', [
					'title'   => $title,
					'content' => $string,
				] );
		}

		/**
		 * Send email after making payment
		 *
		 * @param int $payment_id payment id.
		 */
		public static function send_email_after_making_payment( $payment_id ) {
			$name   = Helper::pfield( 'name', $payment_id );
			$email  = Helper::pfield( 'email', $payment_id );
			$expiry = Helper::pfield( 'expiry', $payment_id );
			$result = '<p>';
		}
	}
}
