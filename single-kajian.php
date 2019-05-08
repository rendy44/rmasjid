<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/25/2019
 * Time: 10:08 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $temp, $designer;

if ( has_post_thumbnail() ) {
	// remove original header.
	remove_action( 'header_content', [ $designer, 'maybe_small_header_callback' ], 30 );

	// replace with a new header.
	add_action( 'header_content', function () use ( $temp ) {
		echo esc_html( apply_filters( 'small_header_content', get_post_thumbnail_id(), get_the_title() ) );
	}, 30 );
}

$participant = MaHelper::pfield( 'participant' );

get_header();

while ( have_posts() ) {
	the_post();
	?>

    <div class="row mb-3" id="single-lecture" data-id="<?php the_ID(); ?>">
        <div class="col-xl-9 col-lg-10 col-md-11 mx-auto text-justify">
            <p class="text-muted text-center"><?php echo esc_html__( 'Published by', 'masjid' ) . ' ' . get_the_author_posts_link() . ' ' . esc_html__( 'at', 'masjid' ) . ' ' . get_the_date(); ?></p>
            <div class="row countdown mb-4 d-none">
                <div class="col-lg-8 mx-auto text-center">
                    <p class="lead"><?php echo __( 'The lecture will be start within:', 'masjid' ); ?></p>
                    <div class="row text-center units">
                        <div class="col">
                            <div class="inner">
                                <span class="number day">00</span><?php echo __( 'day(s)', 'masjid' ); ?>
                            </div>
                        </div>
                        <div class="col">
                            <div class="inner">
                                <span class="number hour">00</span><?php echo __( 'hour(s)', 'masjid' ); ?>
                            </div>
                        </div>
                        <div class="col">
                            <div class="inner">
                                <span class="number minute">00</span><?php echo __( 'minute(s)', 'masjid' ); ?>
                            </div>
                        </div>
                        <div class="col">
                            <div class="inner">
                                <span class="number second">00</span><?php echo __( 'second(s)', 'masjid' ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--			--><?php //echo has_post_thumbnail() ? '<img src="' . get_the_post_thumbnail_url() . '" class="img-fluid mx-auto d-block mb-5">' : ''; ?>
            <h4 class="mb-3 arabic text-center">السلام عليكم ورحمة الله وبركاتهُ</h4>
            <p><?php echo __( 'Please come and join us on', 'masjid' ) . ' ' . MaHelper::get_lecture_short_desc( get_the_ID(), false ); ?></p>
            <h5><?php echo __( 'Lecture Detail', 'masjid' ); ?></h5>
            <p><?php echo __( 'These are the details of lecture, insya Allah will be held on', 'masjid' ); ?>:</p>
            <ul class="details">
                <li class="detail">
                    <i class="fa fa-calendar"></i>
					<?php echo ( 'once' == MaHelper::pfield( 'lecture_type' ) ) ? MaHelper::beatify_date( MaHelper::pfield( 'once_date' ) ) : MaHelper::get_lecture_desc_datetime( get_the_ID() ); ?>
                </li>
                <li class="detail">
                    <i class="fa fa-clock"></i>
					<?php echo MaHelper::pfield( 'time_start' ) . ' - ' . ( MaHelper::pfield( 'time_end' ) ? MaHelper::pfield( 'time_end' ) : __( 'finish', 'masjid' ) ); ?>
                </li>
                <li class="detail">
                    <i class="fa fa-map-marker"></i>
					<?php echo MaHelper::pfield( 'location' ); ?>
                </li>
                <li class="detail">
                    <i class="fa fa-book"></i>
					<?php echo MaHelper::pfield( 'material_title' ) . ( MaHelper::pfield( 'title_note' ) ? " (" . MaHelper::pfield( 'title_note' ) . ")" : "" ); ?>
                </li>
                <li class="detail">
                    <i class="fa fa-user"></i>
					<?php echo MaHelper::pfield( 'lecturer' ) . ( MaHelper::pfield( 'lecturer_note' ) ? " (" . MaHelper::pfield( 'lecturer_note' ) . ")" : "" ); ?>
                </li>
                <li class="detail">
                    <i class="fa fa-users"></i>
					<?php echo ucfirst( implode( ', ', array_map( 'MaHelper::alt__', $participant ) ) ); ?>
                </li>
            </ul>
            <h5><?php echo __( 'The Virtue of Seeking Knowledge', 'masjid' ); ?></h5>
            <p><?php echo __( 'Prophet Muhammad ﷺ said:', 'masjid' ); ?><br/> <span class="arabic">
                مَنْ سَلَكَ طَرِيقًا يَلْتَمِسُ فِيهِ عِلْمًا سَهَّلَ اللَّهُ لَهُ طَرِيقًا إِلَى الْجَنَّةِ
                </span> <br/>
                <i><?php echo __( '"Whoever travels a path in search of knowledge, Allah makes easy for him a path to Paradise." (Muslim)', 'masjid' ); ?></i>
            </p>
            <p><?php echo __( 'In other hadiths Prophet Muhammad ﷺ also said:', 'masjid' ); ?><br/>
                <span class="arabic">
                طلبُ العلمِ فريضةٌ على كلِّ مسلمٍ ، وإِنَّ طالبَ العلمِ يستغفِرُ له كلُّ شيءٍ ، حتى
                الحيتانِ في البحرِ
                </span><br/>
                <i><?php echo __( '"Seeking knowledge is obligation for every muslims, and verily everything will ask for forgiveness for them, even the fish in the sea does." (Ibn `Abd Al-Barr)', 'masjid' ); ?></i>
            </p>
            <p><?php echo __( 'May Allah ﷻ give us tawfeeq and hidayah to seek knowledge in this dunya, aameen', 'masjid' ); ?></p>
        </div>
    </div>

	<?php
}

get_footer();