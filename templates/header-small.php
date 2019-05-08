<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 5:06 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title      = ! isset( $title ) ? ( is_archive() ? get_the_archive_title() : ( is_search() ? sprintf( __( 'Search Results for "%s"', 'masjid' ), get_search_query() ) : single_post_title( '', false ) ) ) : $title;
$image_url  = isset( $image_url ) ? 'style="background-image: linear-gradient(to top right, rgba(30, 30, 30, .6), rgba(0, 0, 0, .8)), url(' . $image_url . ')"' : '';
$subcontent = isset( $subcontent ) ? '<p class="text-center lead">' . $subcontent . '</p>' : '';
?>

<section class="masthead small" <?php echo $image_url; ?>>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="intro-text">
                    <div class="intro-lead-in text-uppercase"><?php echo $title; ?></div>
					<?php echo $subcontent; ?>
                </div>
            </div>
        </div>
    </div>
</section>
