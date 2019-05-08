<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 7:24 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$width_md = isset( $width_md ) ? ' col-md-' . $width_md : '';
?>

<div class="<?php echo 'col-lg-' . $width . $width_md; ?> d-flex">
    <div class="card mb-3 style1 flex-fill">
        <img class="card-img-top" src="<?php echo $cover_url; ?>" alt="<?php echo $title; ?>">
        <div class="card-body">
            <img src="<?php echo $avatar_url; ?>" class="author-avatar rounded-circle img-thumbnail">
            <a href="<?php echo $permalink; ?>" class="permalink">
                <h5 class="card-title"><?php echo $title; ?></h5>
            </a>
            <p class="card-text text-justify"><?php echo $excerpt; ?></p>
            <a href="<?php echo $permalink; ?>" class="card-link"><?php echo __( 'See more', 'masjid' ); ?></a>
        </div>
        <div class="card-footer">
            <ul>
                <li><?php echo $post_date; ?></li>
                <li><?php echo $comment_count > 0 ? $comment_count . ' ' . __( 'Comment', 'masjid' ) : __( "There's no comment", 'masjid' ); ?></li>
            </ul>
        </div>
    </div>
</div>
