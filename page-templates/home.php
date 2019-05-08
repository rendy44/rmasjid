<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/18/2019
 * Time: 10:25 PM
 */

/*Template name: Front Page */
__( 'Front Page', 'masjid' );

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
			$head_title        = MaHelper::pfield( 'head_title' );
			$head_subtitle     = MaHelper::pfield( 'head_subtitle' );
			$head_link         = MaHelper::pfield( 'head_link' );
			$head_link_caption = MaHelper::pfield( 'head_link_caption' );
			echo $temp->render( 'front-masthead', [
				'title'        => $head_title ? $head_title : get_bloginfo( 'name' ),
				'subtitle'     => $head_subtitle ? $head_subtitle : get_bloginfo( 'description' ),
				'link'         => $head_link ? get_permalink( $head_link ) : false,
				'link_caption' => $head_link_caption ? $head_link_caption : get_the_title( $head_link ),
			] ); ?>
        </div>
    </section>

    <!--    Campaign-->
    <section class="bg-white" id="campaign">
        <div class="container">
			<?php
			$campaign_title    = MaHelper::pfield( 'campaign_title' );
			$campaign_subtitle = MaHelper::pfield( 'campaign_subtitle' );
			echo $temp->render( 'front-campaign', [
				'title'    => $campaign_title,
				'subtitle' => $campaign_subtitle,
			] );

			$available_campaigns = MaHelper::count_available_campaign();
			$date_format         = get_option( 'date_format' );
			$time_format         = get_option( 'time_format' );
			$datetime_format     = $date_format . ' ' . $time_format;
			$datetime_now        = new DateTime();
			?>
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <p class="lead"><?php echo __( 'This is the current status of our available campaign as per', 'masjid' ) . ' ' . date( $datetime_format, $datetime_now->getTimestamp() ); ?></p>
					<?php
					echo $temp->render( 'progress-bar', [
						'height' => 25,
						'value'  => $available_campaigns['sum']['collected_percent'],
					] );
					?>
                    <p class="text-muted text-center">
                        <small><?php echo __( 'Collected', 'masjid' ) . ' ' . $available_campaigns['sum']['collected_percent'] . '% ' . __( 'from total', 'masjid' ) . ' Rp' . $available_campaigns['sum']['target_format']; ?></small>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!--    Lecture-->
	<?php
	$query_latest_lectures = MaHelper::setup_query( 1, 'kajian', [], 3 );
	$lecture_items         = [];
	if ( $query_latest_lectures->have_posts() ) {
		while ( $query_latest_lectures->have_posts() ) {
			$query_latest_lectures->the_post();
			$lecture_items[] = $temp->render( 'lecture-list', [
				'image_url'      => MaHelper::get_thumbnail_url(),
				'title'          => get_the_title(),
				'short_datetime' => MaHelper::get_lecture_desc_datetime( get_the_ID() ),
				'permalink'      => get_the_permalink(),
				'type'           => MaHelper::alt__( MaHelper::pfield( 'lecture_type' ) ),
				'lecturer'       => MaHelper::pfield( 'lecturer' ),
				'time'           => MaHelper::pfield( 'time_start' ),
			] );
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
				$lecture_title    = MaHelper::pfield( 'lecture_title' );
				$lecture_subtitle = MaHelper::pfield( 'lecture_subtitle' );
				echo $temp->render( 'front-latest-lecture', [
					'title'    => $lecture_title,
					'subtitle' => $lecture_subtitle,
					'items'    => $lecture_items,
				] );
				?>
            </div>
        </section>
		<?php
	} ?>

    <!--    Article-->
	<?php
	$query_latest_articles = MaHelper::setup_query( 1, 'post', [], 6 );
	$article_items         = [];
	if ( $query_latest_articles->have_posts() ) {
		while ( $query_latest_articles->have_posts() ) {
			$query_latest_articles->the_post();
			$get_author_id       = get_the_author_meta( 'ID' );
			$get_author_gravatar = get_avatar_url( $get_author_id, [ 'size' => 50 ] );
			$article_items[]     = $temp->render( 'post-list', [
				'cover_url'     => MaHelper::get_thumbnail_url( get_the_ID(), 'medium' ),
				'title'         => get_the_title(),
				'permalink'     => get_the_permalink(),
				'excerpt'       => get_the_excerpt(),
				'width'         => 4,
				'width_md'      => 6,
				'avatar_url'    => $get_author_gravatar,
				'post_date'     => get_the_date(),
				'comment_count' => get_comments_number(),
			] );
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
				$article_title    = MaHelper::pfield( 'article_title' );
				$article_subtitle = MaHelper::pfield( 'article_subtitle' );
				echo $temp->render( 'front-latest-article', [
					'title'    => $article_title,
					'subtitle' => $article_subtitle,
					'items'    => $article_items,
				] );
				?>
            </div>
        </section>
		<?php
	}
}
get_footer();
