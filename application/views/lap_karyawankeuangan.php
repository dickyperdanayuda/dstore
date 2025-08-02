<div class="inner">
	<div class="row">
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<a href="javascript:drawTable()" class="btn btn-dark btn-block"><i class="fa fa-sync-alt"></i> &nbsp;&nbsp;&nbsp; Refresh</a>
			</div>
		</div>
		<!-- <div class="col-md-2 col-xs-12">
			<div class="form-group">
				<select id="filter_tahun" onChange="drawTable()" class="form-control">
					<option value="">== Filter Tahun ==</option>
					<?php
					for ($thn = date('Y'); $thn > (date('Y') - 5); $thn--) {
					?>
						<option value="<?= $thn ?>"><?= "{$thn}"; ?></option>
					<?php
					} ?>
				</select>
			</div>
		</div> -->
	</div>
	<div class="row" id="isidata">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					Laporan Karyawan
				</div>
				<div class="card-body table-responsive">
					<table class="table table-striped table-hover" id="tabel-laporan_rekap" width="100%" style="font-size:120%;">
						<thead>
							<tr>
								<th>Jenis Kelamin</th>
								<th style="text-align: right;">Jumlah</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Laki-Laki</td>
								<td style="text-align: right;"><?= number_format($jml_pria, 0) ?> Orang</td>
							</tr>
							<tr>
								<td>Perempuan</td>
								<td style="text-align: right;"><?= number_format($jml_wanita, 0) ?> Orang</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- DataTables -->
<script src="<?= base_url("assets"); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/buttons.flash.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/buttons.colVis.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/pdfmake.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/vfs_fonts.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/jszip.min.js"></script>
<!-- date-range-picker -->
<script src="<?= base_url("assets"); ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= base_url("assets"); ?>/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Select 2 -->
<script src="<?= base_url("assets"); ?>/plugins/select2/select2.js"></script>

<!-- Toastr -->
<script src="<?= base_url("assets"); ?>/plugins/toastr/toastr.min.js"></script>

<!-- Custom Java Script -->
<script>
	var save_method; //for save method string
	var table;
	var thn = null;
	var total = 0;

	function get_total() {
		$.get("getTotal/" + thn, {}, function(dtotal) {
			$("#totalnya").html(dtotal);
			total = dtotal;
			$.get("getTotalSelesai/" + thn, {}, function(etotal) {
				$("#totalselesai").html(etotal);
				totalselesai = etotal;
				persentase = (totalselesai / total) * 100;
				$('#persentase').html(persentase.toFixed(2) + " %");
				if (persentase > 95) {
					$('#bg_persen').removeClass('bg-red');
					$('#bg_persen').addClass('bg-green');
				} else {
					$('#bg_persen').removeClass('bg-green');
					$('#bg_persen').addClass('bg-red');
				}
			});
		});
	}

	function drawTable() {
		var filter_tahun = $("#filter_tahun").val();
		if (!filter_tahun) filter_tahun = null;
		document.location.href = "<?= base_url('LapRekap/tampil/') ?>" + filter_tahun;
		// get_total(filter_tahun);
	}

	// $(document).ready(function() {
	// 	get_total();
	// 	drawTable();
	// });
</script>