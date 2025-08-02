<div class="inner">
	<div class="row">
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<a href="#" class="btn btn-success btn-block" id="pblgg_tambah"><i class="fa fa-plus"></i> &nbsp;&nbsp;&nbsp; Tambah Data</a>
			</div>
		</div>
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<a href="javascript:drawTable()" class="btn btn-success btn-block"><i class="fa fa-sync"></i> &nbsp;&nbsp;&nbsp; Refresh</a>
			</div>
		</div>

	</div>
	<div class="row" id="isidata">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Data Pembelian</h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="tabel-supplier" width="100%" style="font-size:120%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Tanggal Pembelian</th>
									<th>Produk</th>
									<th>Supplier</th>
									<!-- <th>Jumlah Pembelian</th> -->
									<th>Jumlah Harga</th>
									<th>Jumlah Bayar</th>
									<th>Status</th>
									<th>User</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="11" align="center">Tidak ada data</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_pembelian" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title"><i class="glyphicon glyphicon-info"></i> Form Pembelian</h3>
			</div>
			<form role="form col-lg-12" name="Pembelian" id="frm_pembelian">
				<div class="modal-body form">
					<div class="row">
						<input type="hidden" id="pblg_id" name="pblg_id" value="">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Supplier</label>
								<select class="form-control selectajax" name="pblg_splg_id" id="pblg_splg_id" placeholder="Supplier" required>
									<option value="">== Pilih Supplier ==</option>
									<?php
									foreach ($supplier as $spl) {
										echo "<option value='{$spl->splg_id}'>{$spl->splg_nama}</option>";
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Tanggal Pembelian</label>
								<input type="text" class="form-control tgl" name="pblg_tgl" id="pblg_tgl" placeholder="Tanggal Pembelian" value="<?= date("d/m/Y"); ?>">
							</div>
						</div>
						<hr />
						<div class="row" style="margin-left:10px;margin-right:10px;">
							<input type="hidden" id="pblg_pembeliandetail" name="pblg_pembeliandetail">
							<input type="hidden" id="jpblg" name="jpblg">
							<div class="col-lg-12">
								<div class="form-group">
									<label>Detail Pembelian</label>
									<table width="100%" class="table table-responsive table-striped">
										<thead>
											<tr>
												<th>No</th>
												<th>Produk</th>
												<th>Jumlah</th>
												<th>Harga Modal</th>
												<th>HPP</th>
												<th>Total Bayar</th>
												<th>harga Jual</th>
												<th>Catatan</th>
												<th>Aksi</th>
											</tr>
										</thead>
										<tbody id="view_pembeliandetail">
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-lg-12" style="display:none;" id="input_pembeliandetail">
								<div class="col-lg-12">
									<div class="form-group">
										<label>Produk</label>
										<select class="form-control select2" name="pblg_prd_id" id="pblg_prd_id" placeholder="Produk">
											<option value="">== Pilih Produk ==</option>
											<?php
											foreach ($produk as $prd) {
												echo "<option value='{$prd->prdg_id}'>{$prd->prdg_nama} ({$prd->prdg_isi} {$prd->prdg_kemasan})</option>";
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group">
										<label>Jumlah Barang</label>
										<input type="text" min=0 max=0 class="form-control rp" onChange="kasikoma(this.id)" onKeyUp="kasikoma(this.id)" name="pblg_jml" id="pblg_jml" placeholder="Jumlah" value="">
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group">
										<label>Harga Modal</label>
										<input type="text" min=0 max=0 class="form-control rp" onChange="kasikoma(this.id)" onKeyUp="kasikoma(this.id)" name="pblg_harga_modal" id="pblg_harga_modal" placeholder="Harga Modal" value="">
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group">
										<label>HPP</label>
										<input type="text" min=0 max=0 class="form-control rp" onChange="kasikoma(this.id)" onKeyUp="kasikoma(this.id)" name="pblg_hpp" id="pblg_hpp" placeholder="Harga Pokok Penjualan" value="">
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group">
										<label>Harga Jual</label>
										<input type="hidden" id="pblg_harga_id" name="pblg_harga_id" value="">
										<input type="text" min=0 max=0 class="form-control rp" onChange="kasikoma(this.id)" onKeyUp="kasikoma(this.id)" name="pblg_harga_jual" id="pblg_harga_jual" placeholder="Harga Jual" value="">
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group">
										<label>Profit BA</label>
										<input type="text" min=0 max=0 class="form-control rp" onChange="kasikoma(this.id)" onKeyUp="kasikoma(this.id)" name="prd_profit" id="prd_profit" placeholder="Profit" value="" readonly>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group">
										<label>Catatan</label>
										<textarea class="form-control" name="pblg_catatan" id="pblg_catatan" placeholder="Catatan"></textarea>
									</div>
								</div>
								<div class="col-lg-12" style="text-align:center;">
									<a href="#" onClick="batalPembelianDetail()" class="btn btn-danger">Batal</a>
									<a href="#" onClick="tambahPembelianDetail()" class="btn btn-success">Tambah</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="pblg_simpan" class="btn btn-success">Simpan</a>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
<script src="<?= base_url("assets"); ?>/plugins/select2/js/select2.full.min.js"></script>

<!-- Toastr -->
<script src="<?= base_url("assets"); ?>/plugins/toastr/toastr.min.js"></script>

<!-- Custom Java Script -->
<script>
	var save_method; //for save method string
	var table;

	function drawTable() {
		$('#tabel-supplier').DataTable({
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
			"sort": false,
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.
			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "ajax_list_pembelian/",
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

	$("#pblgg_tambah").click(function() {
		form_reset();
		$('#modal_pembelian').modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	});

	function form_reset() {
		$("#pblgg_id").val(0);
		$("#frm_pembelian").trigger("reset");
	}
	$("#frm_pembelian").submit(function(e) {
		// var dataString = $("#frm_pembelian").serialize();
		e.preventDefault();
		$("#pblgg_simpan").html("Menyimpan...");
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
				// alert(d+ " - " + res.status);
				if (res.status == 1) {
					msg = res.desc;
					$(".alert-danger").hide();
					// $(".alert-danger").css('display','none');	
					toastr.success(msg);
					$("#modal_pembelian").modal("hide");
					drawTable();
				} else {
					msg = res.desc;
					toastr.error(msg);
					$(".alert-danger").html("");
					$.each(res.error, function(key, value) {
						$(".alert-danger").show();
						$(".alert-danger").append("<li>" + value + "</li>");
					});
				}

				// $("#pesan_info_ok").html(msg);
				$("#pblgg_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
				// $('#info_ok').modal({
				// 	show: true,
				// 	keyboard: false,
				// 	backdrop: 'static'
				// });
			},
			error: function(jqXHR, textStatus, errorThrown) {
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

	function hapus_pembelian(id) {
		event.preventDefault();
		$("#pblgg_id").val(id);
		$("#jdlKonfirm").html("Konfirmasi hapus data");
		$("#isiKonfirm").html("Yakin ingin menghapus data ini ?");
		$("#frmKonfirm").modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}

	function ubah_pembelian(id) {
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "cari",
			data: "pblgg_id=" + id,
			dataType: "json",
			success: function(data) {
				var obj = Object.entries(data);
				obj.map((dt) => {
					// console.log(dt);
					if (dt[0] != 'pblgg_foto')
						$("#" + dt[0]).val(dt[1]);

				})
				// $("#pblgg_id").val(data.pblgg_id);
				// $("#pblgg_nama").val(data.pblgg_nama);
				// $("#pblgg_alamat").val(data.pblgg_alamat);
				// $("#pblgg_telp").val(data.pblgg_telp);

				$(".inputan").attr("disabled", false);
				$("#modal_pembelian").modal({
					show: true,
					keyboard: false,
					backdrop: 'static'
				});
				return false;
			}
		});
	}

	$("#yaKonfirm").click(function() {
		var id = $("#pblgg_id").val();

		$("#isiKonfirm").html("Sedang menghapus data...");
		$(".btn").attr("disabled", true);
		$.ajax({
			type: "GET",
			url: "hapus/" + id,
			success: function(d) {
				var res = JSON.parse(d);
				var msg = "";
				if (res.status == 1) {
					msg = res.desc;
					toastr.success(msg);
					$("#frmKonfirm").modal("hide");
					drawTable();
				} else {
					msg = res.desc + "[" + res.err + "]";
					toastr.error(msg);
				}
				// $("#isiKonfirm").html(msg);
				// $(".utama").hide();
				// $(".cadangan").show();
				$(".btn").attr("disabled", false);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	});
	$(".selectajax").select2({
		placeholder: "== Pilih ==",
		theme: "bootstrap4",
	});
	// $('#pblgg_deskripsi').summernote();
	$('.tgl').daterangepicker({
		locale: {
			format: 'DD/MM/YYYY'
		},
		showDropdowns: true,
		singleDatePicker: true,
		"autoApspl": true,
		opens: 'left'
	});
	function previewImage() {
		// document.getElementById("image-preview").style.display = "block";
		$("#image-preview").css('display', 'block');
		var oFReader = new FileReader();
		oFReader.readAsDataURL(document.getElementById("pblgg_foto").files[0]);

		oFReader.onload = function(oFREvent) {
			$("#label-preview").css('display', 'block');
			$("#image-preview").css('display', 'block');
			$("#image-preview").attr('src', oFREvent.target.result);
			// document.getElementById("image-preview").src = oFREvent.target.result;
		}
	}
	
	function no_input() {
		// toastr.success('tes');
		window.event.preventDefault();
	}

	function ribuan(val) {
		while (/(\d+)(\d{3})/.test(val.toString())) {
			val = val.toString().replace(/(\d+)(\d{3})/, '$1' + '.' + '$2');
		}
		return val;
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
	function getProduk() {
		$("#pblgg_prd_id").select2("val", "");
		var id = $("#pblgg_ktg_id").select2("val");
		$.get("get_produk/" + id, {}, function(d) {
			$("#pblgg_prd_id").html(d);
		});
	}
	function getPembelianDetail() {
		var pembeliandetail = $("#jpblg").val();
		$.ajax({
			type: "POST",
			url: "view_pembeliandetail/",
			data: 'jpblg=' + pembeliandetail,
			success: function(d) {
				$("#view_pembeliandetail").html(d);
				console.log(d);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}
	function kasikoma(id) {
		var isi = $("#" + id).val().replace(/\./g, '');
		$("#" + id).val(addCommas(isi));
	}

	function hapuskoma(id) {
		var isis = $("#" + id).val().split(",");
		var isi = isis[0].replace(/\./g, "");
		$("#" + id).val(isi);
		$("#" + id).select();
	}
	$(document).ready(function() {
		drawTable();
		$("#modal_pembelian").on('hidden.bs.modal', function(e) {
			$(".alert-danger").hide();
			form_reset();
			$("#label-preview").css('display', 'none');
			$("#image-preview").css('display', 'none');
		});
	});
</script>