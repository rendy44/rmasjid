<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/29/2019
 * Time: 9:19 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $temp, $wp_query;
$column_size = MaHelper::get_column_width();

?>

<?php get_header(); ?>

<div class="row">
    <div class="col-md-<?php echo esc_html( $column_size['main'] ); ?>">
		<?php
		if ( have_posts() ) {
			?>
            <div class="row">
				<?php
				$post_paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				while ( have_posts() ) {
					the_post();
					$get_author_id       = get_the_author_meta( 'ID' );
					$get_author_gravatar = get_avatar_url( $get_author_id, [ 'size' => 50 ] );
					echo $temp->render( 'post-list', [
							'cover_url'     => MaHelper::get_thumbnail_url( get_the_ID(), 'medium' ),
							'title'         => get_the_title(),
							'permalink'     => get_the_permalink(),
							'excerpt'       => get_the_excerpt(),
							'width'         => $column_size['post'],
							'avatar_url'    => $get_author_gravatar,
							'post_date'     => get_the_date(),
							'comment_count' => get_comments_number(),
						] );
				}
				?>
            </div>
			<?php
			echo MaHelper::custom_pagination( $wp_query->max_num_pages, $post_paged );
		} else {
			get_template_part( 'templates/post', '404' );
		};
		?>
    </div>

	<?php
	if ( is_active_sidebar( 'ma_right_bar' ) ) {
		get_sidebar();
	};
	?>
</div>

<?php get_footer(); ?>
