<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 5/1/2019
 * Time: 1:45 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MaOptionsPage' ) ) {
	class MaOptionsPage {
		private static $instance = null;

		static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		private function __construct() {
			add_action( 'cmb2_admin_init', [ $this, 'options_page_callback' ] );
		}

		function options_page_callback() {
			$main_args    = [
				'id'           => 'ma_options_identity',
				'title'        => __( 'Theme Options', 'masjid' ),
				'object_types' => [ 'options-page' ],
				'option_key'   => 'ma_options_identity',
				'tab_group'    => 'ma_options_identity',
				'tab_title'    => __( 'Site Identity', 'masjid' ),
			];
			$main_options = new_cmb2_box( $main_args );
			$main_options->add_field( [
				'name'         => __( 'Logo', 'masjid' ),
				'desc'         => __( 'Leave it empty to use simple text instead', 'masjid' ),
				'id'           => 'logo',
				'type'         => 'file',
				'options'      => [
					'url' => false,
				],
				'text'         => [
					'add_upload_file_text' => __( 'Select Image', 'masjid' ),
				],
				'query_args'   => [
					'type' => [
						'image/gif',
						'image/jpeg',
						'image/png',
					],
				],
				'preview_size' => 'thumbnail',
			] );
			$main_options->add_field( [
				'name'         => __( 'Background Image', 'masjid' ),
				'desc'         => __( 'This will be set as a default background image, leave it empty to use the stock image (image of prophet mosque)', 'masjid' ),
				'id'           => 'background_image',
				'type'         => 'file',
				'options'      => [
					'url' => false,
				],
				'text'         => [
					'add_upload_file_text' => __( 'Select Image', 'masjid' ),
				],
				'query_args'   => [
					'type' => [
						'image/gif',
						'image/jpeg',
						'image/png',
					],
				],
				'preview_size' => 'thumbnail',
			] );
			$main_options->add_field( [
				'name'    => __( 'Color Scheme', 'masjid' ),
				'id'      => 'bg_color',
				'type'    => 'colorpicker',
				'default' => '#d3a55f',
			] );

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
			$group_field_id    = $secondary_options->add_field( [
				'id'      => 'bank_accounts',
				'type'    => 'group',
				// 'repeatable'  => false, // use false if you want non-repeatable group
				'options' => [
					'group_title'   => __( 'Bank {#}', 'cmb2' ),
					'add_button'    => __( 'Add Bank', 'masjid' ),
					'remove_button' => __( 'Remove Bank', 'masjid' ),
					'sortable'      => false,
					// 'closed'         => true, // true to have the groups closed by default
					// 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
				],
			] );
			$secondary_options->add_group_field( $group_field_id, [
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
			] );
			$secondary_options->add_group_field( $group_field_id, [
				'name' => __( 'Holder Name', 'masjid' ),
				'id'   => 'bank_holder',
				'type' => 'text',
			] );
			$secondary_options->add_group_field( $group_field_id, [
				'name' => __( 'Account Number', 'masjid' ),
				'id'   => 'bank_account_number',
				'type' => 'text',
			] );
			$secondary_options->add_group_field( $group_field_id, [
				'name' => __( 'Branch', 'masjid' ),
				'id'   => 'bank_branch',
				'type' => 'text',
			] );

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
			$third_options->add_field( [
				'name' => __( 'Facebook', 'masjid' ),
				'id'   => 'facebook',
				'type' => 'text_url',
			] );
			$third_options->add_field( [
				'name' => __( 'Telegram', 'masjid' ),
				'id'   => 'telegram',
				'type' => 'text_url',
			] );
			$third_options->add_field( [
				'name' => __( 'Instagram', 'masjid' ),
				'id'   => 'instagram',
				'type' => 'text_url',
			] );
			$third_options->add_field( [
				'name' => __( 'Youtube', 'masjid' ),
				'id'   => 'youtube',
				'type' => 'text_url',
			] );

			$pages_arr   = [];
			$query_pages = new WP_Query( [ 'post_type' => 'page', 'posts_per_page' => - 1 ] );
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
			$fourth_options->add_field( [
				'name'             => __( 'Design', 'masjid' ),
				'id'               => 'footer_design',
				'type'             => 'radio',
				'classes'          => 'footer_selection',
				'show_option_none' => false,
				'default'          => 'style1',
				'options'          => [
					'style1' => __( 'Full', 'masjid' ),
					'style2' => __( 'Minimalism', 'masjid' ),
				],
			] );
			$fourth_options->add_field( [
				'name'             => __( 'Color Scheme', 'masjid' ),
				'id'               => 'footer_scheme',
				'type'             => 'radio_inline',
				'classes'          => '',
				'show_option_none' => false,
				'default'          => 'bg-dark',
				'options'          => [
					'bg-dark'    => __( 'Dark', 'masjid' ),
					'bg-light'   => __( 'Light', 'masjid' ),
					'bg-primary' => __( 'Default', 'masjid' ),
				],
			] );
			$fourth_options->add_field( [
				'name'       => __( 'First Column Title', 'masjid' ),
				'id'         => 'footer_1_title',
				'type'       => 'text',
//				'attributes' => [
//					'data-conditional-id'    => 'footer_design',
//					'data-conditional-value' => 'style1',
//				],
			] );
			$fourth_options->add_field( [
				'name'             => __( 'First Column Links', 'masjid' ),
				'id'               => 'footer_1_links',
				'desc'             => __( 'You can select multiple options', 'masjid' ),
				'type'             => 'select_multiple',
				'show_option_none' => false,
				'options'          => $pages_arr,
			] );
			$fourth_options->add_field( [
				'name' => __( 'Second Column Title', 'masjid' ),
				'id'   => 'footer_2_title',
				'type' => 'text',
			] );
			$fourth_options->add_field( [
				'name'             => __( 'Second Column Links', 'masjid' ),
				'id'               => 'footer_2_links',
				'desc'             => __( 'You can select multiple options', 'masjid' ),
				'type'             => 'select_multiple',
				'show_option_none' => false,
				'options'          => $pages_arr,
			] );
			$fourth_options->add_field( [
				'name' => __( 'Third Column Title', 'masjid' ),
				'id'   => 'footer_3_title',
				'type' => 'text',
			] );
			$fourth_options->add_field( [
				'name'             => __( 'Third Column Links', 'masjid' ),
				'id'               => 'footer_3_links',
				'desc'             => __( 'You can select multiple options', 'masjid' ),
				'type'             => 'select_multiple',
				'show_option_none' => false,
				'options'          => $pages_arr,
			] );
		}
	}
}

MaOptionsPage::init();