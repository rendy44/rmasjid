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

	/**
	 * Class Mailer
	 *
	 * @package Masjid\Helpers
	 */
	class Mailer {

		/**
		 * Fire the email
		 *
		 * @param array|string $to      email destination.
		 * @param string       $subject email subject.
		 * @param string       $message email content.
		 */
		private static function send_email( $to, $subject, $message ) {
			$content_html = self::convert_string_to_template( $subject, $message );
			$headers      = [
				'From: ' . get_bloginfo( 'name' ) . ' <' . get_bloginfo( 'admin_email' ) . '>',
			];
			wp_mail( $to, $subject, $content_html, $headers );
		}

		/**
		 * Send email to admin to notify them
		 *
		 * @param string $subject email subject.
		 * @param string $message html email content.
		 */
		private static function send_email_to_admin( $subject, $message ) {
			$email = get_bloginfo( 'admin_email' );
			self::send_email( $email, $subject, $message );
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

			return $temp->render(
				'email-template',
				[
					'title'   => $title,
					'content' => $string,
				]
			);
		}

		/**
		 * Send email after making payment
		 *
		 * @param int $payment_id payment id.
		 */
		public static function send_email_after_making_payment( $payment_id ) {
			$date_format = get_option( 'date_format' );
			$time_format = get_option( 'time_format' );
			$name        = Helper::pfield( 'name', $payment_id );
			$email       = Helper::pfield( 'email', $payment_id );
			$expiry      = Helper::pfield( 'expiry', $payment_id );
			$result      = '<p>' . __( 'Hi', 'masjid' ) . ' ' . $name . ',</p>';
			$result     .= '<p>' . __( 'In order to finish your donation, please make a transfer before', 'masjid' ) . ' ' . date_i18n( $date_format . ', ' . $time_format, $expiry ) . '</p>';
			$result     .= '<p>' . __( 'Please click', 'masjid' ) . ' <a href="' . get_permalink( $payment_id ) . '">' . __( 'here', 'masjid' ) . '</a> ' . __( 'for your payment details', 'masjid' );

			self::send_email( $email, __( 'Please complete your payment', 'masjid' ), $result );
		}

		/**
		 * Send email after making confirmation
		 *
		 * @param int $payment_id payment id.
		 */
		public static function send_email_after_making_confirmation( $payment_id ) {
			$name    = Helper::pfield( 'name', $payment_id );
			$email   = Helper::pfield( 'email', $payment_id );
			$result  = '<p>' . __( 'Hi', 'masjid' ) . ' ' . $name . ',</p>';
			$result .= '<p>' . __( 'Thank you for confirming your payment, we are validating your payment.', 'masjid' ) . '</p>';

			// Send to user.
			self::send_email( $email, __( 'Thank you for confirming', 'masjid' ), $result );

			$amount_total  = (float) Helper::pfield( 'total_amount', $payment_id );
			$content_admin = '<p>' . __( 'Someone just confirmed that he/she just transfered', 'masjid' ) . ' Rp' . number_format( $amount_total, 0, ',', '.' ) . '. ' . __( 'Please validate it as soon as possible', 'masjid' ) . '</p>';
			// Send to admin.
			self::send_email_to_admin( __( 'New Payment', 'masjid' ), $content_admin );
		}

		/**
		 * Send email after making validation
		 *
		 * @param int $payment_id payment id.
		 */
		public static function send_email_after_making_validation( $payment_id ) {
			$name        = Helper::pfield( 'name', $payment_id );
			$email       = Helper::pfield( 'email', $payment_id );
			$campaign_id = Helper::pfield( 'campaign_id', $payment_id );
			$result      = '<p>' . __( 'Hi', 'masjid' ) . ' ' . $name . ',</p>';
			$result     .= '<p>' . __( 'Your donation for', 'masjid' ) . ' <strong>' . get_the_title( $campaign_id ) . '</strong> ' . __( 'have been validated.', 'masjid' ) . '</p>';

			// Send to user.
			self::send_email( $email, __( 'Payment Validated', 'masjid' ), $result );
		}

		/**
		 * Send email after making rejection
		 *
		 * @param $payment_id
		 */
		public static function send_email_after_making_rejection( $payment_id ) {
			$name        = Helper::pfield( 'name', $payment_id );
			$email       = Helper::pfield( 'email', $payment_id );
			$campaign_id = Helper::pfield( 'campaign_id', $payment_id );
			$result      = '<p>' . __( 'Hi', 'masjid' ) . ' ' . $name . ',</p>';
			$result     .= '<p>' . __( 'Your donation for', 'masjid' ) . ' <strong>' . get_the_title( $campaign_id ) . '</strong> ' . __( 'have been rejected.', 'masjid' ) . '</p>';

			// Send to user.
			self::send_email( $email, __( 'Payment Rejected', 'masjid' ), $result );
		}
	}
}
