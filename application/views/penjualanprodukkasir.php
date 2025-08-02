<div class="inner">
	<div class="row">
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<a href="javascript:log_tambah()" class="btn btn-dark btn-block"><i class="fa fa-plus"></i> &nbsp;&nbsp;&nbsp; Tambah</a>
			</div>
		</div>
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<a href="javascript:drawTable()" class="btn btn-dark btn-block"><i class="fa fa-sync-alt"></i> &nbsp;&nbsp;&nbsp; Refresh</a>
			</div>
		</div>
	</div>
	<div class="row" id="isidata">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					Data Penjualan Produk
				</div>
				<div class="card-body table-responsive">
					<table class="table table-striped table-bordered table-hover" id="tabel-penjualan" width="100%" style="font-size:120%;">
						<thead>
							<tr>
								<th>No</th>
								<th>Tanggal</th>
								<th>Nomor Faktur</th>
								<th>Pelanggan</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="3" align="center">Tidak ada data</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_list_detail" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title"><i class="glyphicon glyphicon-info"></i> Detail Penjualan</h3>
			</div>
			<div class="modal-body form" id="list_penjualandetail">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Bootstrap modal -->

<div class="modal fade" id="modal_penjualan" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title"><i class="glyphicon glyphicon-info"></i> Form Penjualan</h3>
			</div>
			<form role="form col-lg-6" name="Penjualan" id="frm_penjualan">
				<div class="modal-body form">
					<div class="row">
						
						<input type="hidden" id="pjlc_id" name="pjlc_id" value="">

						<div class="col-lg-6">
							<div class="form-group">
								<label>Tanggal Penjualan</label>
								<input type="text" class="form-control tgl" name="pjlc_tgl" id="pjlc_tgl" placeholder="" value="<?= date('d/m/Y'); ?>" required>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>No Faktur</label>
								<input type="text" class="form-control" name="pjlc_no_faktur" id="pjlc_no_faktur" placeholder="No faktur" required>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Pelanggan</label>
								<select class="form-control" name="dspl_plgc_id" id="dspl_plgc_id">
									<option value="">== Pilih Pelanggan ==</option>
									<option value="">Tidak Terdaftar</option>
                                    <?php foreach ($pelanggankasir as $plg) {
                                    ?>
                                        <option value=<?= $plg->plgc_id ?>><?= $plg->plgc_nama ?></option>
                                    <?php } ?>
                                </select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Jenis Pembayaran</label>
								<select class="form-control" name="dspl_plgc_id" id="dspl_plgc_id">
								<?php foreach ($jenispembayarankasir as $jns) {
                                    ?>
                                        <option value=<?= $jns->jpbc_id ?>><?= $jns->jpbc_nama ?></option>
                                    <?php } ?>
                                </select>
							</div>
						</div>
						
						<div class="col-lg-6">
							<div class="form-group">
								<label>Diskon</label>
								<input type="text" class="form-control" name="jngc_ongkos" id="jngc_ongkos" value="0" required>
							</div>
						</div>

					</div>
					<hr />
					<div class="row">
						<input type="hidden" id="pjl_penjualandetail" name="pjl_penjualandetail">
						<input type="hidden" id="jpjd" name="jpjd">
						<div class="col-lg-12">
							<div class="form-group">
								<label>Detail Penjualan</label>
								<table width="100%" class="table table-responsive table-striped">
									<thead>
										<tr>
											<th>No</th>
											<th>Produk</th>
											<th>Jumlah</th>
											<th>Harga Satuan</th>
											<th>Diskon</th>
											<th>Total Bayar</th>
											<th>Aksi</th>
										</tr>

									</thead>
									<tr>
									<td colspan="5" align="right" style="font-size:18px;font-weight:bold;">Total<input type="hidden" id="pjl_total_belanja" name="pjl_total_belanja"></td>
									<td colspan="2" style="text-align:right;font-size:18px;font-weight:bold;"></td>
									</tr>
									<tr>
										<td colspan="7" align="center"><a href="#" onClick="inputPenjualanDetail()" class="btn btn-dark"><i class="fa fa-plus"></i> &nbsp;&nbsp;&nbsp; Tambah Detail Penjualan</a></td>
									</tr>
									<tbody id="view_penjualandetail">
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-lg-12" style="display:none;" id="input_penjualandetail">
							
							<div class="col-lg-12">
								<div class="form-group">
									<label>Produk</label>
									<select class="form-control" name="pjdc_prd_id" id="pjdc_prd_id" >
										<option value="">== Pilih Produk ==</option>
										<?php foreach ($produk as $item) {
										?>
											<option value="<?= $item->prdg_id; ?>"><?= "{$item->prdg_nama}"; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label>Jumlah</label>
									<input type="number" min=0 class="form-control rp text-right" onChange="kasikoma('pjdc_jml')" onKeyUp="kasikoma('pjdc_jml')" name="pjdc_jml" id="pjdc_jml" placeholder="Jumlah Barang" value=0>
								</div>
							</div>
							
							<div class="col-lg-12">
								<div class="form-group">
									<label>Diskon</label>
									<input type="text" class="form-control rp text-right" onChange="kasikoma('pjd_diskon')" onKeyUp="kasikoma('pjd_diskon')" name="pjd_diskon" id="pjd_diskon" placeholder="Diskon" value=0>
								</div>
							</div>

							<div class="col-lg-12" style="text-align:center;">
								<a href="#" onClick="batalPenjualanDetail()" class="btn btn-danger">Batal</a>
								<a href="#" onClick="tambahPenjualanDetail()" class="btn btn-dark">Tambah</a>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="pjl_simpan" class="btn btn-dark">Simpan</a>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->

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
<script src="<?= base_url("assets"); ?>/plugins/select2/js/select2.full.js"></script>

<!-- Toastr -->
<script src="<?= base_url("assets"); ?>/plugins/toastr/toastr.min.js"></script>

<!-- Custom Java Script -->
<script>
	var save_method; //for save method string
	var table;

	function drawTable() {
		$('#tabel-penjualan').DataTable({
			"destroy": true,
			dom: 'Bfrtip',
			lengthMenu: [
				[10, 25, 50, -1],
				['10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
			],
			// "oLanguage": {
			// "sProcessing": '<center><img src="<?= base_url("assets/"); ?>assets/img/fb.gif" style="width:2%;"> Loading Data</center>',
			// },
			"responsive": true,
			"sort": true,
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.
			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "ajax_list_penjualan/",
				"type": "POST"
			},
			//Set column definition initialisation properties.
			"columnDefs": [{
				"targets": [-1], //last column
				"orderable": false, //set not orderable
			}, ],
			"initComplete": function(settings, json) {
				$("#process").html("<i class='glyphicon glyphicon-search'></i> Process")
				$(".btn").attr("disabled", false);
				$("#isidata").fadeIn();
			}
		});
	}

	function log_tambah() {
		reset_form();
		$("#jngc_id").val(0);
		$("frm_penjualan").trigger("reset");
		$('#modal_penjualan').modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}
	function inputPenjualanDetail() {
		event.preventDefault();
		$("#input_penjualandetail").slideDown(100);
	}
	
	function kasikoma(id) {
		var isi = $("#" + id).val().replace(/\./g, '');
		$("#" + id).val(addCommas(isi));
	}
	function addCommas(nStr) {
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? ',' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + '.' + '$2');
		}
		return x1 + x2;
	}
	$("#frm_penjualan").submit(function(e) {
		e.preventDefault();
		$("#log_simpan").html("Menyimpan...");
		$(".btn").attr("disabled", true);
		$.ajax({
			type: "POST",
			url: "simpan",
			data: new FormData(this),
			processData: false,
			contentType: false,
			success: function(d) {
				var res = JSON.parse(d);
				var msg = "";
				if (res.status == 1) {
					toastr.success(res.desc);
					drawTable();
					reset_form();
					$("#modal_penjualan").modal("hide");
				} else {
					toastr.error(res.desc);
				}
				$("#log_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
			},
			error: function(jqXHR, namaStatus, errorThrown) {
				$("#log_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
				alert('Error get data from ajax');
			}
		});
	});

	$("#ok_info_ok").click(function() {
		$("#info_ok").modal("hide");
		drawTable();
	});

	$("#okKonfirm").click(function() {
		$(".utama").show();;
		$(".cadangan").hide();
		drawTable();
	});

	function hapus_penjualan(id) {
		event.preventDefault();
		$("#jngc_id").val(id);
		$("#jdlKonfirm").html("Konfirmasi hapus data");
		$("#isiKonfirm").html("Yakin ingin menghapus data ini ?");
		$("#frmKonfirm").modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}

	function ubah_penjualan(id) {
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "cari",
			data: "jngc_id=" + id,
			dataType: "json",
			success: function(data) {
				var obj = Object.entries(data);
				obj.map((dt) => {
					$("#" + dt[0]).val(dt[1]);
				});
				$(".inputan").attr("disabled", false);
				$("#modal_penjualan").modal({
					show: true,
					keyboard: false,
					backdrop: 'static'
				});
				return false;
			}
		});
	}

	function reset_form() {
		$("#jngc_id").val(0);
		$("#frm_penjualan")[0].reset();
	}
	function tambahPenjualanDetail() {
		event.preventDefault();
		var penjualandetail = $("#pjl_penjualandetail").val();
		var pj = $("#jpjd").val();
		var pjd = $("#pjd_prd_id").select2("val");
		var pjdtext = $("#pjd_prd_id option:selected").text().replace(/-/g, "|");
		var pjdt = pjdtext.replace(/&/g, "inisimboldan");
		var jml = $("#pjd_jml").val().replace(/\./g, '');
		var diskon = $("#pjd_diskon").val().replace(/\./g, '');
		if (diskon == "") {
			diskon = 0;
		}
		if (jml > 0) {

			var total = 0;
			$.get("cari_harga/" + pjd + "/" + jml, {}, function(d) {
				total = (parseInt(d) * parseInt(jml)) - parseInt(diskon);
				pj += "-" + pjd + "." + pjdt + "_" + jml + "." + jml + "_" + d + "." + d + "_" + diskon + "." + diskon + "_" + total + "." + total;
				penjualandetail += ";" + pjd + "-" + jml + "-" + d + "-" + diskon + "-" + total;
				$("#jpjd").val(pj);
				$("#pjl_penjualandetail").val(penjualandetail);
				getPenjualanDetail();
				stok[pjd] = jml;
				$("#pjd_jml").val(0);
				$("#pjd_diskon").val(0);
				$("#pjd_prd_id").select2('val', '');
			});
		}
	}
	function getPenjualanDetail() {
		var penjualandetail = $("#jpjd").val();
		// $.get('view_penjualandetail/'+penjualandetail, {}, function(d) {
		// $("#view_penjualandetail").html(d);
		// });
		$.ajax({
			type: "POST",
			url: "view_penjualandetail/",
			data: 'jpjd=' + penjualandetail,
			success: function(d) {
				$("#view_penjualandetail").html(d);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}


	$("#showPass").click(function() {
		var st = $(this).attr("st");
		if (st == 0) {
			$("#log_passnya").attr("type", "text");
			$("#matanya").removeClass("fa-eye");
			$("#matanya").addClass("fa-eye-slash");
			$(this).attr("st", 1);
		} else {
			$("#log_passnya").attr("type", "password");
			$("#matanya").removeClass("fa-eye-slash");
			$("#matanya").addClass("fa-eye");
			$(this).attr("st", 0);
		}
	});

	$("#yaKonfirm").click(function() {
		var id = $("#jngc_id").val();

		$("#isiKonfirm").html("Sedang menghapus data...");
		$(".btn").attr("disabled", true);
		$.ajax({
			type: "GET",
			url: "hapus/" + id,
			success: function(d) {
				var res = JSON.parse(d);
				var msg = "";
				if (res.status == 1) {
					toastr.success(res.desc);
					$("#frmKonfirm").modal("hide");
					drawTable();
				} else {
					toastr.error(res.desc + "[" + res.err + "]");
				}
				$(".btn").attr("disabled", false);
			},
			error: function(jqXHR, namaStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	});

	$('.tgl').daterangepicker({
		locale: {
			format: 'DD/MM/YYYY'
		},
		showDropdowns: true,
		singleDatePicker: true,
		"autoAplog": true,
		opens: 'left'
	});

	$('.select2').select2({
		className: "form-control"
	});

	$(document).ready(function() {
		drawTable();
	});
</script>