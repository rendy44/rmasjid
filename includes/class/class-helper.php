<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 6:28 PM
 *
 * @package Masjid/Helpers
 */

namespace Masjid\Helpers;

use Masjid\Transactions;
use WP_Query;
use DateTime;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Helper' ) ) {

	/**
	 * Class MaHelper
	 */
	class Helper {

		/**
		 * Get post thumbnail url
		 *
		 * @param bool   $post_id post_id.
		 * @param string $size    thumbnail resolution.
		 *
		 * @return false|string
		 */
		public static function get_thumbnail_url( $post_id = false, $size = 'thumbnail' ) {
			if ( ! $post_id ) {
				$post_id = get_the_ID();
			}

			return has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id, $size ) : TEMP_URI . '/assets/front/img/placeholder.jpg';
		}

		/**
		 * Custom pagination
		 *
		 * @param int $numpages max numpage.
		 * @param int $paged    current page.
		 *
		 * @return string
		 */
		public static function custom_pagination( $numpages, $paged ) {

			/**
			 * This first part of our function is a fallback
			 * for custom pagination inside a regular loop that
			 * uses the global $paged and global $wp_query variables.
			 *
			 * It's good because we can now override default pagination
			 * in our theme, and use this function in default quries
			 * and custom queries.
			 */
			$paged = empty( $paged ) ? 1 : $paged;
			if ( '' === $numpages ) {
				global $wp_query;
				$numpages = $wp_query->max_num_pages;
				if ( ! $numpages ) {
					$numpages = 1;
				}
			}
			/**
			 * We construct the pagination arguments to enter into our paginate_links
			 * function.
			 */
			$pagination_args = [
				'base'         => add_query_arg( 'paged', '%#%' ),
				'total'        => $numpages,
				'current'      => $paged,
				'show_all'     => false,
				'end_size'     => 1,
				'mid_size'     => 2,
				'prev_next'    => true,
				'prev_text'    => __( '<i class="fa fa-angle-left"></i>' ),
				'next_text'    => __( '<i class="fa fa-angle-right"></i>' ),
				'type'         => 'array',
				'add_args'     => true,
				'add_fragment' => '',
			];
			$result          = '';
			$paginate_links  = paginate_links( $pagination_args );
			if ( $paginate_links ) {
				$result .= '<div class="pagination"><ul class="pagination">';
				foreach ( $paginate_links as $page ) {
					$result .= '<li class="page-item ' . ( strpos( $page, 'current' ) !== false ? 'active' : '' ) . '"> ' . str_replace( 'page-numbers', 'page-link', $page ) . '</li>';
				}
				$result .= '</ul></div>';
			}

			return $result;
		}

		/**
		 * Get artikel column size
		 *
		 * @return array
		 */
		public static function get_column_width() {
			return is_active_sidebar( 'ma_right_bar' ) ? [
				'main' => 8,
				'post' => 6,
			] : [
				'main' => 12,
				'post' => 4,
			];
		}

		/**
		 * Get latest campaigns for header
		 *
		 * @return array
		 */
		public static function count_available_campaign() {
			$result                 = [ 'items' => [] ];
			$target                 = 0;
			$collected              = 0;
			$date_now               = new DateTime();
			$query_latest_campaigns = get_transient( 'query_latest_campaigns' );
			if ( false === $query_latest_campaigns ) {
				$query_latest_campaigns = new WP_Query(
					[
						'post_type'      => 'donasi',
						'post_status'    => 'publish',
						'posts_per_page' => - 1,
						'meta_query'     => // phpcs:ignore WordPress.DB
							[
								'relation' => 'and',
								[
									'relation' => 'or',
									[
										'key'     => 'main_detail_due_date',
										'compare' => 'NOT EXISTS',
									],
									[
										'key'     => 'main_detail_due_date',
										'value'   => $date_now->getTimestamp(),
										'compare' => '>',
									],
								],
								[
									'relation' => 'or',
									[
										'key'     => 'main_detail_collected_percent',
										'compare' => 'NOT EXISTS',
									],
									[
										'type'    => 'NUMERIC',
										'key'     => 'main_detail_collected_percent',
										'value'   => 100,
										'compare' => '<',
									],
								],
							],
					]
				);
				set_transient( 'query_latest_campaigns', $query_latest_campaigns, 1 * HOUR_IN_SECONDS );
			}
			if ( $query_latest_campaigns->have_posts() ) {
				while ( $query_latest_campaigns->have_posts() ) {
					$query_latest_campaigns->the_post();
					$target    += (float) self::pfield( 'main_detail_target' );
					$collected += (float) self::pfield( 'main_detail_collected' );
					if ( count( $result['items'] ) < 5 ) {
						$short_description = self::pfield( 'main_excerpt' );
						$result['items'][] = [
							'id'                    => get_the_ID(),
							'title'                 => get_the_title(),
							'permalink'             => get_the_permalink(),
							'cover_url'             => self::get_thumbnail_url( get_the_ID(), 'medium' ),
							'short_description'     => $short_description,
							'short_description_fix' => self::limit_char( $short_description, 100 ),
						];
					}
				}
			}
			$result['sum'] = self::count_campaign( $target, $collected );

			return $result;
		}

		/**
		 * Calculate campaign detail
		 *
		 * @param int    $target    the amount of campaign target.
		 * @param int    $collected the amount of campaign collected funfs.
		 * @param string $duedate   the due date of campaign.
		 *
		 * @return array
		 */
		public static function count_campaign( $target, $collected = 0, $duedate = '' ) {
			$duedate_html        = '&infin;';
			$duedate_html_single = '&infin;';
			if ( ! empty( $duedate ) ) {
				$now                 = time(); // or your date as well.
				$your_date           = $duedate;
				$datediff            = $your_date - $now;
				$days                = round( $datediff / ( 60 * 60 * 24 ) );
				$days                = $days < 0 ? 0 : $days;
				$duedate_html        = $days . ' ' . ( $days > 1 ? __( 'days', 'masjid' ) : __( 'day', 'masjid' ) );
				$duedate_html_single = $days . ' ' . ( $days > 1 ? __( 'days left', 'masjid' ) : __( 'day left', 'masjid' ) );
			}
			$result = [
				'target'              => (float) $target,
				'target_format'       => number_format( (float) $target, 0, ',', '.' ),
				'collected'           => (float) $collected,
				'collected_format'    => number_format( (float) $collected, 0, ',', '.' ),
				'collected_percent'   => number_format( (float) $target > 0 ? (float) $collected * 100 / (float) $target : 0, 2 ),
				'duedate'             => $duedate,
				'duedate_html'        => $duedate_html,
				'duedate_html_single' => $duedate_html_single,
			];

			return $result;
		}

		/**
		 * Get post meta
		 *
		 * @param string $key     the name of post meta.
		 * @param bool   $post_id post id.
		 *
		 * @return mixed
		 */
		public static function pfield( $key, $post_id = false ) {
			$post = $post_id ? $post_id : get_the_ID();

			return get_post_meta( $post, $key, true );
		}

		/**
		 * Update post meta
		 *
		 * @param int   $post_id post id.
		 * @param array $args    post meta key and followed by its value.
		 */
		public static function upfield( $post_id, $args = [] ) {
			if ( ! empty( $args ) ) {
				foreach ( $args as $key => $value ) {
					update_post_meta( $post_id, $key, $value );
				}
			}
		}

		/**
		 * Setup custom query
		 *
		 * @param int          $page           current page.
		 * @param string|array $post_type      post type.
		 * @param array        $query_args     custom meta_query args.
		 * @param string       $posts_per_page posts_per_page.
		 *
		 * @return WP_Query
		 */
		public static function setup_query( $page = 1, $post_type = 'post', $query_args = [], $posts_per_page = '' ) {
			$posts_per_page = ! empty( $posts_per_page ) ? $posts_per_page : get_option( 'posts_per_page' );
			$setup_query    = new WP_Query(
				[
					'post_type'      => $post_type,
					'post_status'    => 'publish',
					'posts_per_page' => $posts_per_page,
					'orderby'        => 'date',
					'order'          => 'desc',
					'paged'          => $page,
					'meta_query'     => $query_args, // phpcs:ignore WordPress.DB.SlowDBQuery
				]
			);

			return $setup_query;
		}

		/**
		 * Limit string
		 *
		 * @param string $string original string.
		 * @param int    $limit  limit string index.
		 *
		 * @return string
		 */
		public static function limit_char( $string, $limit = 30 ) {
			return strlen( $string ) > $limit ? substr( $string, 0, ( $limit - 3 ) ) . '...' : $string;
		}

		/**
		 * Get lecture short description
		 *
		 * @param int  $lecture_id  lecture_id.
		 * @param bool $upper_first maybe display as capitalized.
		 *
		 * @return string
		 */
		public static function get_lecture_short_desc( $lecture_id, $upper_first = true ) {
			$lec_type     = self::pfield( 'lecture_type', $lecture_id );
			$lec_material = self::pfield( 'material_title', $lecture_id );
			$lecturer     = self::pfield( 'lecturer', $lecture_id );
			$lec_note     = self::pfield( 'lecturer_note' );
			$result       = ( 'recurring' === $lec_type ? __( 'recurring lecture', 'masjid' ) : __( 'special lecture', 'masjid' ) );
			$result      .= ' ';
			$result      .= $lec_material;
			$result      .= ' ';
			$result      .= __( 'by', 'masjid' );
			$result      .= ' ';
			$result      .= $lecturer;
			$result      .= ! empty( $lec_note ) ? ' (' . $lec_note . ')' : '';
			if ( $upper_first ) {
				$result = ucfirst( $result );
			}

			return $result;
		}

		/**
		 * Get lecture date description
		 *
		 * @param string $lecture_id .
		 * @param bool   $long       .
		 *
		 * @return string
		 */
		public static function get_lecture_desc_datetime( $lecture_id, $long = false ) {
			$lec_type       = self::pfield( 'lecture_type', $lecture_id );
			$lec_time_start = self::pfield( 'time_start', $lecture_id );
			$lec_time_end   = self::pfield( 'time_end', $lecture_id );
			$result         = ( 'recurring' === $lec_type ) ? __( 'recurring lecture', 'masjid' ) : __( 'special lecture', 'masjid' );
			$result        .= ' ';
			if ( 'recurring' === $lec_type ) {
				$lec_period = self::pfield( 'recurring_period', $lecture_id );
				switch ( $lec_period ) {
					case 'daily':
						$result .= __( 'every day', 'masjid' );
						break;
					case 'weekly':
						$lec_days = (array) self::pfield( 'recurring_period_day', $lecture_id );
						$result  .= __( 'every', 'masjid' );
						$result  .= ' ';
						$result  .= implode( ', ', $lec_days );
						break;
					case 'monthly':
						$lec_weeks = (array) self::pfield( 'recurring_period_week', $lecture_id );
						$lec_days  = (array) self::pfield( 'recurring_period_day', $lecture_id );
						$result   .= __( 'every', 'masjid' );
						$result   .= ' ';
						$result   .= implode( ', ', array_map( 'self::alt__', $lec_days ) );
						$result   .= ' ';
						$result   .= __( 'at', 'masjid' );
						$result   .= ' ';
						$result   .= implode( ', ', array_map( 'self::alt__', $lec_weeks ) );
						break;
				}
			} else {
				$date_format = get_option( 'date_format' );
				$lec_date    = self::pfield( 'once_date', $lecture_id );
				$result     .= __( 'at', 'masjid' );
				$result     .= ' ';
				$result     .= date( $date_format, $lec_date );
			}
			if ( $long ) {
				$time_end_html = ! empty( $lec_time_end ) ? $lec_time_end : __( 'finish', 'masjid' );
				$result       .= ', ' . $lec_time_start . ' - ' . $time_end_html;
			}

			// TODO: translate imploded array.
			return ucfirst( $result );
		}

		/**
		 * Beautify timestamp into readable date
		 *
		 * @param int $timestamp .
		 *
		 * @return false|string
		 */
		public static function beatify_date( $timestamp ) {
			$date_format = get_option( 'date_format' );
			$mlocal      = get_locale();
			setlocale( LC_TIME, $mlocal );

			return date_i18n( $date_format, $timestamp );
		}

		/**
		 * Manual alternate translation
		 *
		 * @param string $string .
		 *
		 * @return mixed
		 */
		public static function alt__( $string ) {
			$arr = [
				'recurring' => __( 'Recurring', 'masjid' ),
				'once'      => __( 'Special', 'masjid' ),
				'male'      => __( 'male', 'masjid' ),
				'female'    => __( 'female', 'masjid' ),
				'first'     => __( 'first week', 'masjid' ),
				'second'    => __( 'second week', 'masjid' ),
				'third'     => __( 'third week', 'masjid' ),
				'fourth'    => __( 'fourth week', 'masjid' ),
				'sunday'    => __( 'sunday', 'masjid' ),
				'monday'    => __( 'monday', 'masjid' ),
				'tuesday'   => __( 'tuesday', 'masjid' ),
				'wednesday' => __( 'wednesday', 'masjid' ),
				'thursday'  => __( 'thursday', 'masjid' ),
				'friday'    => __( 'friday', 'masjid' ),
				'saturday'  => __( 'saturday', 'masjid' ),
			];

			return isset( $arr[ $string ] ) ? $arr[ $string ] : $string;
		}

		/**
		 * Get serialized value
		 *
		 * @param array  $objs serialized object.
		 * @param string $key  key.
		 *
		 * @return array|bool|mixed
		 */
		public static function get_serialized_val( $objs, $key ) {
			$result = false;
			$temres = [];
			foreach ( $objs as $obj ) {
				if ( $obj['name'] === $key ) {
					$temres[] = $obj['value'];
				}
			}
			$countarr = count( $temres );
			if ( $countarr > 0 ) {
				$result = count( $temres ) > 1 ? $temres : $temres[0];
			}

			return $result;
		}

		/**
		 * Check campaign availability
		 *
		 * @param int $campaign_id campaign id.
		 *
		 * @return bool
		 */
		public static function is_campaign_available( $campaign_id ) {
			$check = Transactions\Payment::is_campaign_available_to_continue_payment( $campaign_id );

			return 'error' === $check['status'] ? false : true;
		}

		/**
		 * Get bank account
		 *
		 * @return array
		 */
		public static function get_bank_accounts() {
			$options = get_option( 'ma_options_bank' );

			return ! empty( $options['bank_accounts'] ) ? $options['bank_accounts'] : [];
		}

		/**
		 * Get default image url
		 *
		 * @return string
		 */
		public static function get_default_background_image_url() {
			$default_background = get_theme_mod( 'def_bg' );

			return ! empty( $default_background ) ? wp_get_attachment_image_url( $default_background, 'large' ) : TEMP_URI . '/assets/front/img/main-background.jpg';
		}

		/**
		 * Convert hex number to rgb
		 *
		 * @param string $colour hex color code.
		 *
		 * @return array|bool
		 */
		public static function hex2rgb( $colour ) {
			if ( '#' === $colour[0] ) {
				$colour = substr( $colour, 1 );
			}
			if ( 6 === strlen( $colour ) ) {
				list( $r, $g, $b ) = [ $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] ];
			} elseif ( 6 === strlen( $colour ) ) {
				list( $r, $g, $b ) = [ $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] ];
			} else {
				return false;
			}
			$r = hexdec( $r );
			$g = hexdec( $g );
			$b = hexdec( $b );

			return [
				'r' => $r,
				'g' => $g,
				'b' => $b,
			];
		}

		/**
		 * Get formatted background color
		 *
		 * @param int $opacity multiply opacity.
		 *
		 * @return string
		 */
		public static function get_background_color( $opacity = 1 ) {
			$options      = get_option( 'ma_options_identity' );
			$main_color   = ! empty( $options['bg_color'] ) ? $options['bg_color'] : '#d3a55f';
			$alt_color    = ! empty( $options['bg_color_alt'] ) ? $options['bg_color_alt'] : false;
			$rgb_main_arr = self::hex2rgb( $main_color );
			$rgb_alt_arr  = $alt_color ? self::hex2rgb( $alt_color ) : false;
			$rgb_main     = 'rgba(' . $rgb_main_arr['r'] . ', ' . $rgb_main_arr['g'] . ', ' . $rgb_main_arr['b'] . ', ' . $opacity . ')';
			$rgb_alt      = $rgb_alt_arr ? 'rgba(' . $rgb_alt_arr['r'] . ', ' . $rgb_alt_arr['g'] . ', ' . $rgb_alt_arr['b'] . ', ' . $opacity . ')' : $rgb_main;

			return 'background-image: linear-gradient(to bottom right, ' . $rgb_main . ', ' . $rgb_alt . ');';
		}

		/**
		 * Get solid main color
		 *
		 * @return string
		 */
		public static function get_solid_main_color() {
			$default_color = get_theme_mod( 'def_color' );

			return ! empty( $default_color ) ? $default_color : '#d3a55f';
		}

		/**
		 * Darken hex color
		 *
		 * @param string $hex    color code.
		 * @param int    $darker darken level.
		 *
		 * @return string
		 */
		public static function darken_color( $hex, $darker = 1 ) {

			$hash = ( strpos( $hex, '#' ) !== false ) ? '#' : '';
			$hex  = ( strlen( $hex ) === 7 ) ? str_replace( '#', '', $hex ) : ( ( strlen( $hex ) === 6 ) ? $hex : false );
			if ( strlen( $hex ) !== 6 ) {
				return $hash . '000000';
			}
			$darker                  = ( $darker > 1 ) ? $darker : 1;
			list( $r16, $g16, $b16 ) = str_split( $hex, 2 );
			$r                       = sprintf( '%02X', floor( hexdec( $r16 ) / $darker ) );
			$g                       = sprintf( '%02X', floor( hexdec( $g16 ) / $darker ) );
			$b                       = sprintf( '%02X', floor( hexdec( $b16 ) / $darker ) );

			return $hash . $r . $g . $b;
		}

		/**
		 * Get social network url
		 *
		 * @return array
		 */
		public static function get_social_network_url() {
			$keys    = [ 'facebook', 'telegram', 'instagram', 'youtube' ];
			$result  = [];
			$options = get_option( 'ma_options_socnet' );
			foreach ( $keys as $key ) {
				$result[ $key ] = ! empty( $options[ $key ] ) ? $options[ $key ] : false;
			}

			return $result;
		}

		/**
		 * Get header brand content
		 *
		 * @return string
		 */
		public static function get_header_brand_content() {
			$options = get_option( 'ma_options_identity' );

			return ! empty( $options['logo_id'] ) ? '<img class="img-fluid" src="' . wp_get_attachment_image_url( $options['logo_id'], 'medium' ) . '"/>' : get_bloginfo( 'name' );
		}
	}
}
