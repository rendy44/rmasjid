<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/21/2019
 * Time: 8:49 PM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="col-lg-3 col-md-4 col-6">
	<a href="<?php echo $large_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" class="d-block mb-4 h-100" data-lity>
		<img class="img-fluid img-thumbnail" src="<?php echo $thumbnail_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
	</a>
</div>
