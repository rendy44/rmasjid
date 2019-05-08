<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/19/2019
 * Time: 1:23 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) {
	the_post();
	?>

    <div class="row">
        <div class="col-xl-9 col-lg-10 col-md-11 mx-auto text-justify">
			<?php
			the_content();
			?>
        </div>
    </div>

	<?php
}

get_footer();
