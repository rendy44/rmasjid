<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/25/2019
 * Time: 10:08 PM
 *
 * @package Masjid/Single
 */

use Masjid\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $temp, $designer;

if ( has_post_thumbnail() ) {
	// remove original header.
	remove_action( 'header_content', [ $designer, 'maybe_small_header_callback' ], 30 );

	// replace with a new header.
	add_action(
		'header_content',
		function () use ( $temp ) {
			echo esc_html( apply_filters( 'small_header_content', get_post_thumbnail_id(), get_the_title() ) );
		},
		30
	);
}

$participant = Helpers\Helper::pfield( 'participant' );

get_header();

while ( have_posts() ) {
	the_post();
	?>

	<div class="row mb-3" id="single-lecture" data-id="<?php the_ID(); ?>">
		<div class="col-xl-9 col-lg-10 col-md-11 mx-auto text-justify">
			<p class="text-muted text-center">
				<?php echo esc_html__( 'Published by', 'masjid' ) . ' ' . get_the_author_posts_link() . ' ' . esc_html__( 'at', 'masjid' ) . ' ' . get_the_date(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>				
				</p>
			<div class="row countdown mb-4 d-none">
				<div class="col-lg-8 mx-auto text-center">
					<p class="lead">
						<?php echo __( 'The lecture will be start within:', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>							
						</p>
					<div class="row text-center units">
						<div class="col">
							<div class="inner">
								<span class="number day">00</span><?php echo __( 'day(s)', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						</div>
						<div class="col">
							<div class="inner">
								<span class="number hour">00</span><?php echo __( 'hour(s)', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						</div>
						<div class="col">
							<div class="inner">
								<span class="number minute">00</span><?php echo __( 'minute(s)', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						</div>
						<div class="col">
							<div class="inner">
								<span class="number second">00</span><?php echo __( 'second(s)', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<h4 class="mb-3 arabic text-center">السلام عليكم ورحمة الله وبركاتهُ</h4>
			<p><?php echo __( 'Please come and join us on', 'masjid' ) . ' ' . esc_html( Helpers\Helper::get_lecture_short_desc( get_the_ID(), false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<h5><?php echo __( 'Lecture Detail', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h5>
			<p><?php echo __( 'These are the details of lecture, insya Allah will be held on', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>:</p>
			<ul class="details">
				<li class="detail">
					<i class="fa fa-calendar"></i>
					<?php echo ( 'once' === Helpers\Helper::pfield( 'lecture_type' ) ) ? Helpers\Helper::beatify_date( Helpers\Helper::pfield( 'once_date' ) ) : Helpers\Helper::get_lecture_desc_datetime( get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</li>
				<li class="detail">
					<i class="fa fa-clock"></i>
					<?php echo Helpers\Helper::pfield( 'time_start' ) . ' - ' . ( Helpers\Helper::pfield( 'time_end' ) ? Helpers\Helper::pfield( 'time_end' ) : __( 'finish', 'masjid' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</li>
				<li class="detail">
					<i class="fa fa-map-marker"></i>
					<?php echo esc_html( Helpers\Helper::pfield( 'location' ) ); ?>
				</li>
				<li class="detail">
					<i class="fa fa-book"></i>
					<?php echo esc_html( Helpers\Helper::pfield( 'material_title' ) . ( Helpers\Helper::pfield( 'title_note' ) ? ' (' . Helpers\Helper::pfield( 'title_note' ) . ')' : '' ) ); ?>
				</li>
				<li class="detail">
					<i class="fa fa-user"></i>
					<?php echo esc_html( Helpers\Helper::pfield( 'lecturer' ) . ( Helpers\Helper::pfield( 'lecturer_note' ) ? ' (' . Helpers\Helper::pfield( 'lecturer_note' ) . ')' : '' ) ); ?>
				</li>
				<li class="detail">
					<i class="fa fa-users"></i>
					<?php echo ucfirst( implode( ', ', $participant ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</li>
			</ul>
			<h5><?php echo __( 'The Virtue of Seeking Knowledge', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h5>
			<p><?php echo __( 'Prophet Muhammad ﷺ said:', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><br/> <span class="arabic">
				مَنْ سَلَكَ طَرِيقًا يَلْتَمِسُ فِيهِ عِلْمًا سَهَّلَ اللَّهُ لَهُ طَرِيقًا إِلَى الْجَنَّةِ
				</span> <br/>
				<i><?php echo __( '"Whoever travels a path in search of knowledge, Allah makes easy for him a path to Paradise." (Muslim)', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></i>
			</p>
			<p><?php echo __( 'In other hadiths Prophet Muhammad ﷺ also said:', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><br/>
				<span class="arabic">
				طلبُ العلمِ فريضةٌ على كلِّ مسلمٍ ، وإِنَّ طالبَ العلمِ يستغفِرُ له كلُّ شيءٍ ، حتى
				الحيتانِ في البحرِ
				</span><br/>
				<i><?php echo __( '"Seeking knowledge is obligation for every muslims, and verily everything will ask for forgiveness for them, even the fish in the sea does." (Ibn `Abd Al-Barr)', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></i>
			</p>
			<p><?php echo __( 'May Allah ﷻ give us tawfeeq and hidayah to seek knowledge in this dunya, aameen', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		</div>
	</div>

	<?php
}

get_footer();
