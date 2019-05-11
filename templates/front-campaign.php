<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/29/2019
 * Time: 9:10 PM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$subtitle = isset( $subtitle ) ? '<h3 class="section-subheading">' . esc_html( $subtitle ) . '</h3>' : '';

?>

<div class="row">
	<div class="col-lg-8 mx-auto text-center mb-4">
		<h2 class="section-heading text-uppercase"><?php echo esc_html( $title ); ?></h2>
		<?php echo $subtitle; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</div>
<div class="row mb-5 text-center">
	<div class="col-md-4 why-item">
		<span class="fa-stack fa-4x">
			<i class="fas fa-circle fa-stack-2x text-primary"></i>
			<i class="fas fa-chart-line fa-stack-1x fa-inverse"></i>
		</span>
		<h4 class="point-heading">Transparan</h4>
		<p class="text-muted">Kami senantiasa jujur dan transparan dalam mengemban amanat dari muhsinin, Dan prosesnya bisa diakses langsung oleh siapapun.</p>
	</div>
	<div class="col-md-4 why-item">
		<span class="fa-stack fa-4x">
			<i class="fas fa-circle fa-stack-2x text-primary"></i>
			<i class="fas fa-users fa-stack-1x fa-inverse"></i>
		</span>
		<h4 class="point-heading">Profesional</h4>
		<p class="text-muted">Kami sudah bertahun-tahun dipercaya oleh muhsinin dalam mengemban amanat mereka untuk menyalurkan bantuan agar tepat sasaran.</p>
	</div>
	<div class="col-md-4 why-item">
		<span class="fa-stack fa-4x">
			<i class="fas fa-circle fa-stack-2x text-primary"></i>
			<i class="fas fa-laptop fa-stack-1x fa-inverse"></i>
		</span>
		<h4 class="point-heading">Berbasis Online</h4>
		<p class="text-muted">Anda bisa berdonasi dimanapun dan kapanpun menggunakan perangkat komputer atau smartphone dengan koneksi internet.</p>
	</div>
</div>
