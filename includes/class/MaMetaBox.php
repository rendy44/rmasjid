<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 5/3/2019
 * Time: 9:45 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MaMetaBox' ) ) {
	class MaMetaBox {

		/**
		 * Private instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Singleton
		 *
		 * @return MaMetaBox|null
		 */
		static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * MaMetaBox constructor.
		 */
		private function __construct() {
			add_action( 'cmb2_admin_init', [ $this, 'kajian_metabox_callback' ] );
			add_action( 'cmb2_admin_init', [ $this, 'donasi_metabox_callback' ] );
			add_action( 'cmb2_admin_init', [ $this, 'front_page_metabox_callback' ] );
			add_action( 'cmb2_admin_init', [ $this, 'history_page_metabox_callback' ] );
		}

		/**
		 * Callback for registering kajian metabox
		 */
		function kajian_metabox_callback() {
			$main_box_options  = [
				'id'           => 'cmb_kajian_main',
				'title'        => __( 'Lecture', 'masjid' ),
				'object_types' => [ 'kajian' ], // Post type
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true, // Show field names on the left
			];
			$cmb_main          = new_cmb2_box( $main_box_options );
			$main_tabs_setting = [
				'config' => $main_box_options,
				'layout' => 'vertical', // Default : horizontal
				'tabs'   => [],
			];

			// Time Detail
			$main_tabs_setting['tabs'][] = [
				'id'     => 'tab_main_time',
				'title'  => __( 'Time Information', 'masjid' ),
				'fields' => [
					[
						'name'             => __( 'Lecture Type', 'masjid' ),
						'id'               => 'lecture_type',
						'type'             => 'select',
						'show_option_none' => false,
						'default'          => '',
						'options'          => [
							'recurring' => __( 'Recurring', 'masjid' ),
							'once'      => __( 'Once', 'masjid' ),
						],
					],
					[
						'name'             => __( 'Period', 'masjid' ),
						'id'               => 'recurring_period',
						'type'             => 'select',
						'show_option_none' => false,
						'default'          => 'daily',
						'options'          => [
							'daily'   => __( 'Daily', 'masjid' ),
							'weekly'  => __( 'Weekly', 'masjid' ),
							'monthly' => __( 'Monthly', 'masjid' ),
						],
						'attributes'       => [
							'data-conditional-id'    => 'lecture_type',
							'data-conditional-value' => 'recurring',
						],
					],
					[
						'name'              => __( 'Month', 'masjid' ),
						'id'                => 'recurring_period_month',
						'type'              => 'multicheck',
						'show_option_none'  => false,
						'default'           => 'january',
						'select_all_button' => false,
						'options'           => [
							'january'   => __( 'January', 'masjid' ),
							'february'  => __( 'February', 'masjid' ),
							'march'     => __( 'March', 'masjid' ),
							'april'     => __( 'April', 'masjid' ),
							'may'       => __( 'May', 'masjid' ),
							'june'      => __( 'June', 'masjid' ),
							'july'      => __( 'July', 'masjid' ),
							'august'    => __( 'August', 'masjid' ),
							'september' => __( 'September', 'masjid' ),
							'october'   => __( 'October', 'masjid' ),
							'november'  => __( 'November', 'masjid' ),
							'december'  => __( 'December', 'masjid' ),
						],
						'attributes'        => [
							'select_all_button'      => false,
							'data-conditional-id'    => 'recurring_period',
							'data-conditional-value' => 'annually',
						],
					],
					[
						'name'              => __( 'Week', 'masjid' ),
						'id'                => 'recurring_period_week',
						'type'              => 'multicheck',
						'show_option_none'  => false,
						'default'           => 'first',
						'select_all_button' => false,
						'options'           => [
							'first'  => __( 'First Week', 'masjid' ),
							'second' => __( 'Second Week', 'masjid' ),
							'third'  => __( 'Third Week', 'masjid' ),
							'fourth' => __( 'Fourth Week', 'masjid' ),
						],
						'attributes'        => [
							'select_all_button'      => false,
							'data-conditional-id'    => 'recurring_period',
							'data-conditional-value' => json_encode( [ 'monthly', 'annually' ] ),
						],
					],
					[
						'name'              => __( 'Day', 'masjid' ),
						'id'                => 'recurring_period_day',
						'type'              => 'multicheck',
						'show_option_none'  => false,
						'default'           => 'sunday',
						'select_all_button' => false,
						'options'           => [
							'sunday'    => __( 'Sunday', 'masjid' ),
							'monday'    => __( 'Monday', 'masjid' ),
							'tuesday'   => __( 'Tuesday', 'masjid' ),
							'wednesday' => __( 'Wednesday', 'masjid' ),
							'thursday'  => __( 'Thursday', 'masjid' ),
							'friday'    => __( 'Friday', 'masjid' ),
							'saturday'  => __( 'Saturday', 'masjid' ),
						],
						'attributes'        => [
							'select_all_button'      => false,
							'data-conditional-id'    => 'recurring_period',
							'data-conditional-value' => json_encode( [ 'weekly', 'monthly', 'annually' ] ),
						],
					],
					[
						'name'       => __( 'Date', 'masjid' ),
						'id'         => 'once_date',
						'type'       => 'text_date_timestamp',
						'attributes' => [
							'data-conditional-id'    => 'lecture_type',
							'data-conditional-value' => 'once',
						],
					],
					[
						'name'        => __( 'Time Start', 'masjid' ),
						'id'          => 'time_start',
						'type'        => 'text_time',
						'time_format' => 'H:i',
						'attributes'  => [
							'required' => true,
						],
					],
					[
						'name'        => __( 'Time End', 'masjid' ),
						'id'          => 'time_end',
						'desc'        => __( 'Optional, you can leave it empty', 'masjid' ),
						'type'        => 'text_time',
						'time_format' => 'H:i',
					],
					[
						'name'       => __( 'Note', 'masjid' ),
						'desc'       => __( 'Optional, you can leave it empty', 'masjid' ),
						'id'         => 'time_note',
						'type'       => 'text',
						'attributes' => [
							'placeholder' => __( 'ex: Right after Shalat Al-Maghrib until Shalat al-`isha', 'masjid' ),
						],
					],
				],
			];

			// Detail
			$main_tabs_setting['tabs'][] = [
				'id'     => 'tab_main_detail',
				'title'  => __( 'Detail', 'masjid' ),
				'fields' => [
					[
						'name'       => __( 'Title of Material', 'masjid' ),
						'id'         => 'material_title',
						'type'       => 'text',
						'attributes' => [
							'required' => true,
						],
					],
					[
						'name'    => __( 'Note', 'masjid' ),
						'id'      => 'title_note',
						'desc'    => __( 'Optional, you can leave it empty' ),
						'type'    => 'text',
						'classes' => 'finfo',
					],
					[
						'name'       => __( 'Lecturer', 'masjid' ),
						'id'         => 'lecturer',
						'type'       => 'text',
						'attributes' => [
							'required' => true,
						],
					],
					[
						'name'    => __( 'Note', 'masjid' ),
						'id'      => 'lecturer_note',
						'desc'    => __( 'Optional, you can leave it empty' ),
						'type'    => 'text',
						'classes' => 'finfo',
					],
					[
						'name'              => __( 'Participant', 'masjid' ),
						'id'                => 'participant',
						'type'              => 'multicheck',
						'show_option_none'  => false,
						'default'           => [ 'male', 'female' ],
						'select_all_button' => false,
						'options'           => [
							'male'   => __( 'Male', 'masjid' ),
							'female' => __( 'Female', 'masjid' ),
						],
					],
					[
						'name'    => __( 'Note', 'masjid' ),
						'id'      => 'participant_note',
						'desc'    => __( 'Optional, you can leave it empty' ),
						'type'    => 'text',
						'classes' => 'finfo',
					],
					[
						'name'       => __( 'Location', 'masjid' ),
						'id'         => 'location',
						'type'       => 'text',
						'attributes' => [
							'required' => true,
						],
					],
					[
						'name' => __( 'Additional Notes', 'masjid' ),
						'desc' => __( 'Any information you may want to share', 'masjid' ),
						'id'   => 'additional_note',
						'type' => 'textarea_small',
					],
				],
			];

			$cmb_main->add_field( [
				'id'   => '__tabs',
				'type' => 'tabs',
				'tabs' => $main_tabs_setting,
			] );
		}

		/**
		 * Callback for registering donasi metabox
		 */
		function donasi_metabox_callback() {
			$main_box_options  = [
				'id'           => 'cmb_donasi_main',
				'title'        => __( 'Campaign', 'masjid' ),
				'object_types' => [ 'donasi' ], // Post type
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true, // Show field names on the left
			];
			$cmb_main          = new_cmb2_box( $main_box_options );
			$main_tabs_setting = [
				'config' => $main_box_options,
				'layout' => 'vertical', // Default : horizontal
				'tabs'   => [],
			];

			$main_tabs_setting['tabs'][] = [
				'id'     => 'tab_main_info',
				'title'  => __( 'Information', 'masjid' ),
				'fields' => [
					[
						'name' => __( 'Short Description', 'masjid' ),
						'id'   => 'main_excerpt',
						'desc' => __( 'Short description of this campaign', 'masjid' ),
						'type' => 'textarea_small',
					],
					[
						'name'    => __( 'Full Content' ),
						'id'      => 'main_content',
						'desc'    => __( 'Write all details about this campaign', 'masjid' ),
						'type'    => 'wysiwyg',
						'options' => [
							'wpautop'       => false,
							'media_buttons' => true,
							'textarea_rows' => get_option( 'default_post_edit_rows', 10 ),
							'teeny'         => false,
							'dfw'           => false,
							'tinymce'       => true,
							'quicktags'     => true,
						],
					],
				],
			];
			$main_tabs_setting['tabs'][] = [
				'id'     => 'tab_main_images',
				'title'  => __( 'Gallery', 'masjid' ),
				'fields' => [
					[
						'name'         => __( 'Gallery', 'masjid' ),
						'id'           => 'main_images_gallery',
						'type'         => 'file_list',
						'preview_size' => [ 100, 100 ], // Default: array( 50, 50 )
						'query_args'   => [ 'type' => 'image' ], // Only images attachment
						'text'         => [
							'add_upload_files_text' => __( 'Select Image', 'masjid' ),
							'remove_image_text'     => __( 'Remove', 'masjid' ),
							'file_download_text'    => __( 'Download', 'masjid' ),
							'remove_text'           => __( 'Remove', 'masjid' ),
						],
					],
				],
			];
			$main_tabs_setting['tabs'][] = [
				'id'     => 'tab_main_detail',
				'title'  => __( 'Detail' ),
				'fields' => [
					[
						'name'            => __( 'Target', 'masjid' ),
						'id'              => 'main_detail_target',
						'type'            => 'text_money',
						'before_field'    => 'Rp',
						'attributes'      => [
							'required' => 'required',
							'type'     => 'number',
							'pattern'  => '\d*',
							'min'      => '20000',
							'oninput'  => 'this.value = Math.abs(this.value)',
						],
						'sanitization_cb' => 'absint',
						'escape_cb'       => 'absint',
					],
					[
						'name'            => __( 'Collected', 'masjid' ),
						'desc'            => __( 'Leave it empty, field updated automatically' ),
						'id'              => 'main_detail_collected',
						'type'            => 'text_money',
						'before_field'    => 'Rp',
						'attributes'      => [
							'readonly' => 'readonly',
							'type'     => 'number',
							'pattern'  => '\d*',
							'min'      => '0',
							'oninput'  => 'this.value = Math.abs(this.value)',
						],
						'sanitization_cb' => 'absint',
						'escape_cb'       => 'absint',
					],
					[
						'name' => __( 'Due Date', 'masjid' ),
						'desc' => __( 'Leave it empty, for unlimited due date', 'masjid' ),
						'id'   => 'main_detail_due_date',
						'type' => 'text_date_timestamp',
						// 'timezone_meta_key' => 'wiki_test_timezone',
						// 'date_format' => 'l jS \of F Y',
					],
				],
			];

			$cmb_main->add_field( [
				'id'   => '__tabs',
				'type' => 'tabs',
				'tabs' => $main_tabs_setting,
			] );
		}

		/**
		 * Callback for registering front page metabox
		 */
		function front_page_metabox_callback() {
			$main_box_options  = [
				'id'           => 'cmb_front_page',
				'title'        => __( 'Front Page', 'masjid' ),
				'object_types' => [ 'page' ], // Post type
				'show_on'      => [ 'key' => 'page-template', 'value' => 'page-templates/home.php' ],
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true, // Show field names on the left
			];
			$cmb_main          = new_cmb2_box( $main_box_options );
			$main_tabs_setting = [
				'config' => $main_box_options,
				'layout' => 'vertical', // Default : horizontal
				'tabs'   => [],
			];

			$pages_arr   = [];
			$query_pages = new WP_Query( [ 'post_type' => 'page', 'posts_per_page' => - 1 ] );
			if ( $query_pages->have_posts() ) {
				while ( $query_pages->have_posts() ) {
					$query_pages->the_post();
					$pages_arr[ get_the_ID() ] = get_the_title();
				}
			}
			wp_reset_postdata();
			$main_tabs_setting['tabs'][] = [
				'id'     => 'tab_masthead',
				'title'  => __( 'Masthead', 'masjid' ),
				'fields' => [
					[
						'name' => __( 'Title', 'masjid' ),
						'id'   => 'head_title',
						'type' => 'text',
					],
					[
						'name' => __( 'Subtitle', 'masjid' ),
						'id'   => 'head_subtitle',
						'type' => 'text',
					],
					[
						'name'             => __( 'Link', 'masjid' ),
						'id'               => 'head_link',
						'type'             => 'select',
						'show_option_none' => true,
						'options'          => $pages_arr,
					],
					[
						'name'       => __( 'Link Caption', 'masjid' ),
						'id'         => 'head_link_caption',
						'type'       => 'text',
						'attributes' => [
							'data-conditional-id' => 'head_link',
						],
					],
				],
			];
			$main_tabs_setting['tabs'][] = [
				'id'     => 'tab_campaign',
				'title'  => __( 'Campaign', 'masjid' ),
				'fields' => [
					[
						'name' => __( 'Title', 'masjid' ),
						'id'   => 'campaign_title',
						'type' => 'text',
					],
					[
						'name' => __( 'Subtitle', 'masjid' ),
						'id'   => 'campaign_subtitle',
						'type' => 'text',
					],
				],
			];
			$main_tabs_setting['tabs'][] = [
				'id'     => 'tab_lecture',
				'title'  => __( 'Lecture', 'masjid' ),
				'fields' => [
					[
						'name' => __( 'Title', 'masjid' ),
						'id'   => 'lecture_title',
						'type' => 'text',
					],
					[
						'name' => __( 'Subtitle', 'masjid' ),
						'id'   => 'lecture_subtitle',
						'type' => 'text',
					],
				],
			];
			$main_tabs_setting['tabs'][] = [
				'id'     => 'tab_article',
				'title'  => __( 'Article', 'masjid' ),
				'fields' => [
					[
						'name' => __( 'Title', 'masjid' ),
						'id'   => 'article_title',
						'type' => 'text',
					],
					[
						'name' => __( 'Subtitle', 'masjid' ),
						'id'   => 'article_subtitle',
						'type' => 'text',
					],
				],
			];
			$cmb_main->add_field( [
				'id'   => '__tabs',
				'type' => 'tabs',
				'tabs' => $main_tabs_setting,
			] );
		}

		/**
		 * Callback for registering history page metabox
		 */
		function history_page_metabox_callback() {
			$main_box_options = [
				'id'           => 'cmb_history_page',
				'title'        => __( 'History Page', 'masjid' ),
				'object_types' => [ 'page' ], // Post type
				'show_on'      => [ 'key' => 'page-template', 'value' => 'page-templates/history.php' ],
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true, // Show field names on the left
			];
			$cmb_main         = new_cmb2_box( $main_box_options );
			$cmb_main->add_field( [
				'name' => __( 'Content', 'masjid' ),
				'id'   => 'content',
				'type' => 'textarea_small',
				// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
			] );
			$group_field_id = $cmb_main->add_field( [
				'id'      => 'timeline',
				'type'    => 'group',
				//				'description' => __( 'Generates reusable form entries', 'cmb2' ),
				// 'repeatable'  => false, // use false if you want non-repeatable group
				'options' => [
					'group_title'   => __( 'Timeline {#}', 'masjid' ),
					// since version 1.1.4, {#} gets replaced by row number
					'add_button'    => __( 'Add Timeline', 'masjid' ),
					'remove_button' => __( 'Remove Timeline', 'masjid' ),
					'sortable'      => true,
					// 'closed'         => true, // true to have the groups closed by default
					// 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
				],
			] );
			$cmb_main->add_group_field( $group_field_id, [
				'name' => __( 'Period', 'masjid' ),
				'desc' => __( 'Estimation time period when the event occur', 'masjid' ),
				'id'   => 'period',
				'type' => 'text',
				// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
			] );
			$cmb_main->add_group_field( $group_field_id, [
				'name' => __( 'Title', 'masjid' ),
				'id'   => 'title',
				'type' => 'text',
				// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
			] );
			$cmb_main->add_group_field( $group_field_id, [
				'name'         => __( 'Image', 'masjid' ),
				'id'           => 'image',
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
			$cmb_main->add_group_field( $group_field_id, [
				'name' => __( 'Description', 'masjid' ),
				'id'   => 'description',
				'type' => 'textarea_small',
				// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
			] );
		}
	}
}

MaMetaBox::init();