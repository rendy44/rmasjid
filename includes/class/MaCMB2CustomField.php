<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 5/7/2019
 * Time: 9:56 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MaCMB2CustomField' ) ) {
	class MaCMB2CustomField {

		/**
		 * Static instance variable
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Singleton
		 *
		 * @return MaCMB2CustomField|null
		 */
		static function init() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * MaCMB2CustomField constructor.
		 */
		private function __construct() {
			$this->register_select_multiple();
		}

		/**
		 * Register `select_multiple`
		 */
		private function register_select_multiple() {
			add_action( 'cmb2_render_select_multiple', [ $this, 'cmb2_render_select_multiple_field_type' ], 10, 5 );
			add_filter( 'cmb2_sanitize_select_multiple', [ $this, 'cmb2_sanitize_select_multiple_callback' ], 10, 2 );
		}

		/**
		 * Callback for rendering `select_multiple`
		 *
		 * @param $field
		 * @param $escaped_value
		 * @param $object_id
		 * @param $object_type
		 * @param $field_type_object
		 */
		function cmb2_render_select_multiple_field_type( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
			$select_multiple = '<select class="widefat" multiple name="' . $field->args['_name'] . '[]" id="' . $field->args['_id'] . '"';
			foreach ( $field->args['attributes'] as $attribute => $value ) {
				$select_multiple .= " $attribute=\"$value\"";
			}
			$select_multiple .= ' />';
			foreach ( $field->options() as $value => $name ) {
				$selected        = ( $escaped_value && in_array( $value, (array) $escaped_value ) ) ? 'selected="selected"' : '';
				$select_multiple .= '<option class="cmb2-option" value="' . esc_attr( $value ) . '" ' . $selected . '>' . esc_html( $name ) . '</option>';
			}
			$select_multiple .= '</select>';
			$select_multiple .= $field_type_object->_desc( true );
			echo $select_multiple; // WPCS: XSS ok.
		}

		/**
		 * Callback for sanitizing `select_multiple`
		 *
		 * @param $override_value
		 * @param $value
		 *
		 * @return array|void
		 */
		function cmb2_sanitize_select_multiple_callback( $override_value, $value ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $key => $saved_value ) {
					$value[ $key ] = sanitize_text_field( $saved_value );
				}

				return $value;
			}

			return;
		}
	}
}

MaCMB2CustomField::init();