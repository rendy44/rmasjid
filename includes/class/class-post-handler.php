<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 5/15/2019
 * Time: 5:05 PM
 *
 * @package Masjid/Includes
 */

namespace Masjid\Includes;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Masjid\Transactions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Post_Handler' ) ) {

	/**
	 * Class Post_Handler
	 *
	 * @package Masjid\Includes
	 */
	class Post_Handler {

		/**
		 * Private instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Singleton
		 *
		 * @return \Masjid\Includes\Post_Handler|null
		 */
		public static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Post_Handler constructor.
		 */
		private function __construct() {
			add_action( 'admin_post_download_pdf', [ $this, 'download_pdf_callback' ] );
		}

		/**
		 * Callback for `download_pdf`
		 */
		public function download_pdf_callback() {
			$campaign_id = ! empty( $_GET['campaign'] ) ? $_GET['campaign'] : false; // phpcs:ignore WordPress
			global $temp;
			if ( $campaign_id ) {
				$html2pdf = new Html2Pdf();
				try {
					$histories      = Transactions\Payment::success_payments( $campaign_id );
					$campaign_title = get_the_title( $campaign_id );
					$content        = $temp->render(
						'pdf-template-report',
						[
							'title'     => get_bloginfo( 'name' ),
							'subtitle'  => __( 'Report for', 'masjid' ) . '<br/>' . $campaign_title,
							'rows'      => $histories,
							'doc_title' => __( 'Report for', 'masjid' ) . ' ' . $campaign_title,
							'site_url'  => home_url(),
							'footnote'  => '&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ),

						]
					);
					$html2pdf->writeHTML( $content );
					$html2pdf->output();
				} catch ( Html2PdfException $e ) {
					$html2pdf->clean();
					$formatter = new ExceptionFormatter( $e );
					wp_die( $formatter->getHtmlMessage() ); // phpcs:ignore WordPress
				}
			} else {
				wp_die( __( 'Please provide campaign id', 'masjid' ) ); // phpcs:ignore WordPress
			}
		}
	}
}

Post_Handler::init();
