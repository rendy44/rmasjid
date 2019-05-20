<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 5/19/2019
 * Time: 8:39 PM
 *
 * @package Masjid/Settings
 */

namespace Masjid\Settings;

use Kirki\Core\Helper;
use Masjid\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Customizer' ) && class_exists( 'Kirki' ) ) {

	/**
	 * Class Customizer
	 *
	 * @package Masjid\Settings
	 */
	class Customizer {

		/**
		 * Private instance variable
		 *
		 * @var null
		 */
		private static $instance = null;
		/**
		 * Private config_id variable
		 *
		 * @var string
		 */
		private $config = '';

		/**
		 * Singleton
		 *
		 * @return \Masjid\Settings\Customizer|null
		 */
		public static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Customizer constructor.
		 */
		private function __construct() {
			$this->config = 'ma_customizer';
			$this->add_config();
			$this->add_panels();
			$this->add_sections();
			$this->add_fields();
		}

		/**
		 * Callback to add config
		 */
		private function add_config() {
			\Kirki::add_config( $this->config, [
				'capability'  => 'edit_theme_options',
				'option_type' => 'theme_mod',
			] );
		}

		/**
		 * Callback to add panels
		 */
		private function add_panels() {
			\Kirki::add_panel( 'designer', [
				'priority' => 100,
				'title'    => __( 'Designer', 'masjid' ),
			] );
		}

		/**
		 * Callback to add sections
		 */
		private function add_sections() {
			\Kirki::add_section( 'header', [
				'title'    => __( 'Header', 'masjid' ),
				'panel'    => 'designer',
				'priority' => 160,
			] );
		}

		/**
		 * Callback to add fields
		 */
		private function add_fields() {
			// Site Identity.
			\Kirki::add_field( $this->config, [
				'type'        => 'image',
				'settings'    => 'def_bg',
				'label'       => __( 'Background Image', 'masjid' ),
				'description' => __( 'This will be set as a default background image, leave it empty to use the stock image (image of prophet mosque)', 'masjid' ),
				'section'     => 'title_tagline',
				'default'     => '',
				'choices'     => [
					'save_as' => 'id',
				],
			] );
			\Kirki::add_field( $this->config, [
				'type'     => 'color',
				'settings' => 'def_color',
				'label'    => __( 'Color Scheme', 'masjid' ),
				'section'  => 'title_tagline',
				'default'  => '#d3a55f',
			] );

			// Slider.
			\Kirki::add_field( $this->config, [
				'type'         => 'repeater',
				'label'        => __( 'Sliders', 'masjid' ),
				'section'      => 'header',
				'priority'     => 10,
				'row_label'    => [
					'type'  => 'text',
					'value' => __( 'Slider #', 'masjid' ),
				],
				'button_label' => __( 'Add Slider', 'masjid' ),
				'settings'     => 'sliders',
				'fields'       => [
					'background'  => [
						'type'  => 'image',
						'label' => __( 'Background', 'masjid' ),
					],
					'title'       => [
						'type'    => 'text',
						'label'   => __( 'Title', 'masjid' ),
						'default' => '',
					],
					'description' => [
						'type'    => 'text',
						'label'   => __( 'Description', 'masjid' ),
						'default' => '',
					],
					'link'        => [
						'type'     => 'select',
						'label'    => __( 'Link', 'masjid' ),
						'default'  => 'option-1',
						'multiple' => 0,
						'choices'  => \Kirki_Helper::get_posts( [
							'posts_per_page' => - 1,
							'post_type'      => [ 'page', 'post', 'kajian', 'donasi' ],
							'post_status'    => 'publish',
							'orderby'        => 'date',
							'order'          => 'desc',
						] ),
					],
					'caption'     => [
						'type'    => 'text',
						'label'   => __( 'Caption', 'masjid' ),
						'default' => '',
					],
				],
			] );
		}
	}
}

Customizer::init();
