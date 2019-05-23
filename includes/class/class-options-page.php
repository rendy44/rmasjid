<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 5/1/2019
 * Time: 1:45 PM
 *
 * @package Masjid/Settings
 */

namespace Masjid\Settings;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Options_Page' ) ) {

	/**
	 * Class OptionsPage
	 *
	 * @package Masjid\Settings
	 */
	class Options_Page {
		/**
		 * Privat instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Singleton
		 *
		 * @return \Masjid\Settings\Options_Page|null
		 */
		public static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * OptionsPage constructor.
		 */
		private function __construct() {
			add_action( 'cmb2_admin_init', [ $this, 'options_page_callback' ] );
		}

		/**
		 * Callback for registering options page.
		 */
		public function options_page_callback() {
			$main_args    = [
				'id'           => 'ma_options_identity',
				'title'        => __( 'Theme Options', 'masjid' ),
				'object_types' => [ 'options-page' ],
				'option_key'   => 'ma_options_identity',
				'tab_group'    => 'ma_options_identity',
				'tab_title'    => __( 'Site Identity', 'masjid' ),
			];
			$main_options = new_cmb2_box( $main_args );
			$second_args       = [
				'id'           => 'ma_options_bank',
				'menu_title'   => __( 'Bank Accounts', 'masjid' ), // Use menu title, & not title to hide main h2.
				'object_types' => [ 'options-page' ],
				'option_key'   => 'ma_options_bank',
				'parent_slug'  => 'ma_options_identity',
				'tab_group'    => 'ma_options_identity',
				'tab_title'    => __( 'Bank Accounts', 'masjid' ),
			];
			$secondary_options = new_cmb2_box( $second_args );
			$group_field_id    = $secondary_options->add_field(
				[
					'id'      => 'bank_accounts',
					'type'    => 'group',
					'options' => [
						'group_title'   => __( 'Bank {#}', 'cmb2' ),
						'add_button'    => __( 'Add Bank', 'masjid' ),
						'remove_button' => __( 'Remove Bank', 'masjid' ),
						'sortable'      => false,
					],
				]
			);
			$secondary_options->add_group_field(
				$group_field_id,
				[
					'name'             => __( 'Bank Name', 'masjid' ),
					'id'               => 'bank_name',
					'type'             => 'select',
					'show_option_none' => false,
					'default'          => 'bri',
					'options'          => [
						'bri'     => __( 'Bank Rakyat Indonesia (BRI)', 'masjid' ),
						'bni'     => __( 'Bank Negara Indonesia (BNI)', 'masjid' ),
						'bca'     => __( 'Bank Central Asia (BCA)', 'masjid' ),
						'mandiri' => __( 'Bank Mandiri', 'masjid' ),
					],
				]
			);
			$secondary_options->add_group_field(
				$group_field_id,
				[
					'name' => __( 'Holder Name', 'masjid' ),
					'id'   => 'bank_holder',
					'type' => 'text',
				]
			);
			$secondary_options->add_group_field(
				$group_field_id,
				[
					'name' => __( 'Account Number', 'masjid' ),
					'id'   => 'bank_account_number',
					'type' => 'text',
				]
			);
			$secondary_options->add_group_field(
				$group_field_id,
				[
					'name' => __( 'Branch', 'masjid' ),
					'id'   => 'bank_branch',
					'type' => 'text',
				]
			);
			$third_args    = [
				'id'           => 'ma_options_socnet',
				'title'        => __( 'Social Network', 'masjid' ),
				'object_types' => [ 'options-page' ],
				'option_key'   => 'ma_options_socnet',
				'parent_slug'  => 'ma_options_identity',
				'tab_group'    => 'ma_options_identity',
				'tab_title'    => __( 'Social Network', 'masjid' ),
			];
			$third_options = new_cmb2_box( $third_args );
			$third_options->add_field(
				[
					'name' => __( 'Facebook', 'masjid' ),
					'id'   => 'facebook',
					'type' => 'text_url',
				]
			);
			$third_options->add_field(
				[
					'name' => __( 'Telegram', 'masjid' ),
					'id'   => 'telegram',
					'type' => 'text_url',
				]
			);
			$third_options->add_field(
				[
					'name' => __( 'Instagram', 'masjid' ),
					'id'   => 'instagram',
					'type' => 'text_url',
				]
			);
			$third_options->add_field(
				[
					'name' => __( 'Youtube', 'masjid' ),
					'id'   => 'youtube',
					'type' => 'text_url',
				]
			);
			$pages_arr   = [];
			$query_pages = new WP_Query(
				[
					'post_type'      => 'page',
					'posts_per_page' => - 1,
				]
			);
			if ( $query_pages->have_posts() ) {
				while ( $query_pages->have_posts() ) {
					$query_pages->the_post();
					$pages_arr[ get_the_ID() ] = get_the_title();
				}
			}
			wp_reset_postdata();
			$fourth_args    = [
				'id'           => 'ma_options_footer',
				'title'        => __( 'Footer', 'masjid' ),
				'object_types' => [ 'options-page' ],
				'option_key'   => 'ma_options_footer',
				'parent_slug'  => 'ma_options_identity',
				'tab_group'    => 'ma_options_identity',
				'tab_title'    => __( 'Footer', 'masjid' ),
			];
			$fourth_options = new_cmb2_box( $fourth_args );
			$fourth_options->add_field(
				[
					'name' => __( 'First Column Title', 'masjid' ),
					'id'   => 'footer_1_title',
					'type' => 'text',
				]
			);
			$fourth_options->add_field(
				[
					'name'             => __( 'First Column Links', 'masjid' ),
					'id'               => 'footer_1_links',
					'desc'             => __( 'You can select multiple options', 'masjid' ),
					'type'             => 'select',
					'show_option_none' => false,
					'options'          => $pages_arr,
				]
			);
			$fourth_options->add_field(
				[
					'name' => __( 'Second Column Title', 'masjid' ),
					'id'   => 'footer_2_title',
					'type' => 'text',
				]
			);
			$fourth_options->add_field(
				[
					'name'             => __( 'Second Column Links', 'masjid' ),
					'id'               => 'footer_2_links',
					'desc'             => __( 'You can select multiple options', 'masjid' ),
					'type'             => 'select2',
					'show_option_none' => false,
					'options'          => $pages_arr,
				]
			);
			$fourth_options->add_field(
				[
					'name' => __( 'Third Column Title', 'masjid' ),
					'id'   => 'footer_3_title',
					'type' => 'text',
				]
			);
			$fourth_options->add_field(
				[
					'name'             => __( 'Third Column Links', 'masjid' ),
					'id'               => 'footer_3_links',
					'desc'             => __( 'You can select multiple options', 'masjid' ),
					'type'             => 'select',
					'show_option_none' => false,
					'options'          => $pages_arr,
					'repeatable' => true
				]
			);
		}
	}
}

Options_Page::init();
