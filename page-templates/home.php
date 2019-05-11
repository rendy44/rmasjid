<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/18/2019
 * Time: 10:25 PM
 *
 * @package Masjid/Pages
 */

/*Template name: Front Page */
__( 'Front Page', 'masjid' );

use Masjid\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $temp;
$page_id = get_the_ID();
get_header();
while ( have_posts() ) {
	the_post();
	?>

	<!--    Masthead-->
	<section class="masthead">
		<div class="container">
			<?php
			$head_title        = Helpers\Helper::pfield( 'head_title' );
			$head_subtitle     = Helpers\Helper::pfield( 'head_subtitle' );
			$head_link         = Helpers\Helper::pfield( 'head_link' );
			$head_link_caption = Helpers\Helper::pfield( 'head_link_caption' );
			$result            = $temp->render(
				'front-masthead',
				[
					'title'        => $head_title ? $head_title : get_bloginfo( 'name' ),
					'subtitle'     => $head_subtitle ? $head_subtitle : get_bloginfo( 'description' ),
					'link'         => $head_link ? get_permalink( $head_link ) : false,
					'link_caption' => $head_link_caption ? $head_link_caption : get_the_title( $head_link ),
				]
			);
			echo $result; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</div>
	</section>

	<!--    Campaign-->
	<section class="bg-white" id="campaign">
		<div class="container">
			<?php
			$campaign_title    = Helpers\Helper::pfield( 'campaign_title' );
			$campaign_subtitle = Helpers\Helper::pfield( 'campaign_subtitle' );
			$campaign_result   = $temp->render(
				'front-campaign',
				[
					'title'    => $campaign_title,
					'subtitle' => $campaign_subtitle,
				]
			);
			echo $campaign_result;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
			<div class="row">
				<div class="col-lg-8 mx-auto text-center">
					<?php
					$available_campaigns = Helpers\Helper::count_available_campaign();
					$date_format         = get_option( 'date_format' );
					$time_format         = get_option( 'time_format' );
					$datetime_format     = $date_format . ' ' . $time_format;
					$datetime_now        = new DateTime();
					?>
					<p class="lead"><?php echo __( 'This is the current status of our available campaign as per', 'masjid' ) . ' ' . date( $datetime_format, $datetime_now->getTimestamp() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					<?php
					$campaign_result = $temp->render(
						'progress-bar',
						[
							'height' => 25,
							'value'  => $available_campaigns['sum']['collected_percent'],
						]
					);
					echo $campaign_result;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
					<p class="text-muted text-center">
						<small><?php echo __( 'Collected', 'masjid' ) . ' ' . $available_campaigns['sum']['collected_percent'] . '% ' . __( 'from total', 'masjid' ) . ' Rp' . $available_campaigns['sum']['target_format']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></small>
					</p>
				</div>
			</div>
		</div>
	</section>

	<!--    Lecture-->
	<?php
	$query_latest_lectures = Helpers\Helper::setup_query( 1, 'kajian', [], 3 );
	$lecture_items         = [];
	if ( $query_latest_lectures->have_posts() ) {
		while ( $query_latest_lectures->have_posts() ) {
			$query_latest_lectures->the_post();
			$lecture_items[] = $temp->render(
				'lecture-list',
				[
					'image_url'      => Helpers\Helper::get_thumbnail_url(),
					'title'          => get_the_title(),
					'short_datetime' => Helpers\Helper::get_lecture_desc_datetime( get_the_ID() ),
					'permalink'      => get_the_permalink(),
					'type'           => Helpers\Helper::alt__( Helpers\Helper::pfield( 'lecture_type' ) ),
					'lecturer'       => Helpers\Helper::pfield( 'lecturer' ),
					'time'           => Helpers\Helper::pfield( 'time_start' ),
				]
			);
		}
	}
	wp_reset_postdata();
	?>
	<?php
	if ( ! empty( $lecture_items ) ) {
		?>
		<section class="bg-light" id="front_latest_lecture">
			<div class="container">
				<?php
				$lecture_title    = Helpers\Helper::pfield( 'lecture_title' );
				$lecture_subtitle = Helpers\Helper::pfield( 'lecture_subtitle' );
				$lecture_result   = $temp->render(
					'front-latest-lecture',
					[
						'title'    => $lecture_title,
						'subtitle' => $lecture_subtitle,
						'items'    => $lecture_items,
					]
				);
				echo $lecture_result; // phpcs:ignore WordPress.Security.EscapeOutput
				?>
			</div>
		</section>
		<?php
	}
	?>

	<!--    Article-->
	<?php
	$query_latest_articles = Helpers\Helper::setup_query( 1, 'post', [], 6 );
	$article_items         = [];
	if ( $query_latest_articles->have_posts() ) {
		while ( $query_latest_articles->have_posts() ) {
			$query_latest_articles->the_post();
			$get_author_id       = get_the_author_meta( 'ID' );
			$get_author_gravatar = get_avatar_url( $get_author_id, [ 'size' => 50 ] );
			$article_items[]     = $temp->render(
				'post-list',
				[
					'cover_url'     => Helpers\Helper::get_thumbnail_url( get_the_ID(), 'medium' ),
					'title'         => get_the_title(),
					'permalink'     => get_the_permalink(),
					'excerpt'       => get_the_excerpt(),
					'width'         => 4,
					'width_md'      => 6,
					'avatar_url'    => $get_author_gravatar,
					'post_date'     => get_the_date(),
					'comment_count' => get_comments_number(),
				]
			);
		}
	}
	wp_reset_postdata();
	?>
	<?php
	if ( ! empty( $article_items ) ) {
		?>
		<section class="bg-white" id="front_latest_article">
			<div class="container">
				<?php
				$article_title    = Helpers\Helper::pfield( 'article_title' );
				$article_subtitle = Helpers\Helper::pfield( 'article_subtitle' );
				$article_result   = $temp->render(
					'front-latest-article',
					[
						'title'    => $article_title,
						'subtitle' => $article_subtitle,
						'items'    => $article_items,
					]
				);
				echo $article_result;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		</section>
		<?php
	}
}
get_footer();
