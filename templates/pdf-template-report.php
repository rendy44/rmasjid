<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 5/15/2019
 * Time: 7:32 PM
 *
 * @package Masjid/Components
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<style>
	table {
		width: 100%;
		/*display: table;*/
	}

	table thead {
		width: 100%;
	}

	tr.heading td {
		background-color: #b4ebe5;
		padding: 8px;
		text-align: center !important;
		font-weight: 700;
		text-transform: uppercase;
	}

	tbody tr td {
		padding: 8px;
		border-bottom: 1px solid #ddd;
	}

	img {
		max-width: 200px;
	}

	.uppercase {
		text-transform: uppercase;
	}

	.center {
		text-align: center;
	}

	.center img {
		margin: auto;
	}

	.right {
		text-align: right;
	}

	.left {
		text-align: left;
	}

	table.page_header {
		width: 100%;
		border: none;
		background-color: #DDDDFF;
		border-bottom: solid 1mm #AAAADD;
		padding: 2mm
	}

	table.page_footer {
		width: 100%;
		border: none;
		background-color: #DDDDFF;
		border-top: solid 1mm #AAAADD;
		padding: 2mm
	}

	div.note {
		border: solid 1mm #DDDDDD;
		background-color: #EEEEEE;
		padding: 2mm;
		border-radius: 2mm;
		width: 100%;
	}

	ul.main {
		width: 95%;
		list-style-type: square;
	}

	ul.main li {
		padding-bottom: 2px;
	}

	h1 {
		text-align: center;
		font-size: 20px
	}

	h3 {
		text-align: center;
		font-size: 16px;
		line-height: 1.5;
	}
</style>
<page backtop="14mm" backbottom="14mm" backleft="10mm" backright="10mm" style="font-size: 12pt">
	<page_header>
		<table class="page_header">
			<tr>
				<td style="width: 100%; text-align: left">
					<?php echo $doc_title; ?>
				</td>
			</tr>
		</table>
	</page_header>
	<page_footer>
		<table class="page_footer">
			<tr>
				<td style="width: 33%; text-align: left;">
					<?php echo $site_url; ?>
				</td>
				<td style="width: 34%; text-align: center">
					<?php echo __( 'page', 'masjid' ); ?> [[page_cu]]/[[page_nb]]
				</td>
				<td style="width: 33%; text-align: right">
					<?php echo $footnote; ?>
				</td>
			</tr>
		</table>
	</page_footer>
	<table style="width: 100%">
		<tr>
			<td style="width: 20%"></td>
			<td class="center uppercase" style="width: 60%">
				<h3><?php echo $title . '<br/>' . $subtitle; ?></h3>
			</td>
			<td style="width: 20%"></td>
		</tr>
	</table>
	<table style="width: 100%">
		<thead>
			<tr class="heading">
				<td style="width: 10%; text-align: center"><?php echo __( 'No.', 'masjid' ); ?></td>
				<td style="width: 30%; text-align: center"><?php echo __( 'Name', 'masjid' ); ?></td>
				<td style="width: 30%; text-align: center"><?php echo __( 'Total Amount', 'masjid' ); ?></td>
				<td style="width: 30%; text-align: center"><?php echo __( 'Date', 'masjid' ); ?></td>
			</tr>
		</thead>
		<tbody>
			<?php
			$rownum    = 1;
			$sub_total = 0;
			foreach ( $rows as $row ) {
				echo '<tr>';
				echo '<td>' . $rownum . '</td>';
				echo '<td>' . ( $row['hide_name'] ? __( 'Anonymous', 'masjid' ) : $row['name'] ) . '</td>';
				echo '<td class="right">Rp' . $row['total_formatted_amount'] . '</td>';
				echo '<td>' . $row['beautify_datetime'] . '</td>';
				echo '</tr>';
				$rownum ++;
				$sub_total += (float) $row['clean_total_amount'];
			};
			?>
			<tr>
				<td colspan="2" class="uppercase right"><?php echo __( 'Sub Total', 'masjid' ); ?></td>
				<td class="right">Rp<?php echo number_format( $sub_total, 0, ',', '.' ); ?></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</page>
