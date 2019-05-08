<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/21/2019
 * Time: 8:50 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="col-lg-4 col-md-6 d-flex">
    <div class="card mb-3 style2 flex-fill">
        <img class="card-img-top" src="<?php echo $cover_url; ?>" alt="<?php echo $title; ?>">
        <div class="card-body">
            <h5 class="card-title"><?php echo $title; ?></h5>
            <!--        <h6 class="card-subtitle mb-2 text-muted">Emilia-Romagna Region, Italy</h6>-->
            <p class="card-text"><?php echo $short_description; ?></p>
            <div class="action">
                <div class="py-3">
                    <div class="progress mb-3" style="height: 4px">
                        <div class="progress-bar" role="progressbar" style="width: <?php echo $collected_percent; ?>%"
                             aria-valuenow="<?php echo $collected_percent; ?>" aria-valuemin="0"
                             aria-valuemax="100"></div>
                    </div>
                    <div class="row">
                        <div class="col text-left">
                            <small class="text-muted">
								<?php echo __( 'Collected', 'masjid' ); ?><br/> Rp<?php echo $collected_format; ?>
                            </small>
                        </div>
                        <div class="col text-right">
                            <small class="text-muted">
								<?php echo __( 'Day left', 'masjid' ); ?><br/>
								<?php echo $duedate_html; ?>
                            </small>
                        </div>
                    </div>
                </div>
                <a href="<?php echo $permalink; ?>"
                   class="btn btn-primary btn-block"><?php echo __( 'Donate now', 'masjid' ); ?></a>
            </div>
        </div>
    </div>
</div>