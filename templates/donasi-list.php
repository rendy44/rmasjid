<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/21/2019
 * Time: 8:50 AM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="col-lg-4 col-md-6 d-flex">
	<div class="card mb-3 style2 flex-fill">
		<img class="card-img-top" src="<?php echo $cover_url; ?>" alt="<?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
		<div class="card-body">
			<h5 class="card-title"><?php echo esc_html( $title ); ?></h5>
			<p class="card-text"><?php echo esc_html( $short_description ); ?></p>
			<div class="action">
				<div class="py-3">
					<div class="progress mb-3" style="height: 4px">
						<div class="progress-bar" role="progressbar" style="width: <?php echo $collected_percent; ?>%" aria-valuenow="<?php echo $collected_percent; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<div class="row">
						<div class="col text-left">
							<small class="text-muted">
								<?php echo __( 'Collected', 'masjid' ); ?><br/> Rp<?php echo $collected_format; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</small>
						</div>
						<div class="col text-right">
							<small class="text-muted">
								<?php echo __( 'Day left', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><br/>
								<?php echo $duedate_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</small>
						</div>
					</div>
				</div>
				<a href="<?php echo $permalink; ?>"  class="btn btn-primary btn-block"><?php echo __( 'Donate now', 'masjid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			</div>
		</div>
	</div>
</div>
