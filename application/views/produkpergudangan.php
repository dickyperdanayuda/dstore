<div class="inner">
	<div class="row">
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<a href="#" class="btn btn-success btn-block" id="prdg_tambah"><i class="fa fa-plus"></i> &nbsp;&nbsp;&nbsp; Tambah Data</a>
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
					<h3 class="card-title">Data Produk</h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="tabel-supplier" width="100%" style="font-size:120%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Gambar</th>
									<th>Kategori</th>
									<th>Merek</th>
									<th>Nama</th>
									<th>Deskripsi</th>
									<th>Kemasan</th>
									<th>Isi</th>
									<th>Satuan</th>
									<th>Status</th>
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
</div>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_produk" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title"><i class="glyphicon glyphicon-info"></i> Form Produk</h3>
			</div>
			<form role="form  col-lg-6" enctype="multipart/form-data" name="Produk" id="frm_produk">
				<div class="modal-body form">
					<div class="alert alert-danger" style="display:none; list-style-type: none;"></div>
					<div class="row">
						<input type="hidden" id="prdg_id" name="prdg_id" value="">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Kategori</label>
								<select class="selectajax form-control" style="width: 100%;" name="prdg_ktg_id" id="prdg_ktg_id">
									<?php foreach ($kategori as $kat) {
										echo "<option value='{$kat->ktg_id}'>{$kat->ktg_nama}</option>";
									} ?>
								</select>

							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Merek</label>
								<select class="selectajax form-control" style="width: 100%;" name="prdg_mrk_id" id="prdg_mrk_id">
									<?php foreach ($merek as $mrk) {
										echo "<option value='{$mrk->mrkg_id}'>{$mrk->mrkg_nama}</option>";
									} ?>
								</select>

							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Nama</label>
								<input type="text" class="form-control" name="prdg_nama" id="prdg_nama" placeholder="Nama" value="">
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Deskripsi</label>
								<textarea name="prdg_deskripsi" id="prdg_deskripsi" class="form-control"></textarea>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<label>Kemasan</label>
								<input type="text" class="form-control" name="prdg_kemasan" id="prdg_kemasan" placeholder="cth: Botol Plastik" value="">
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<label>Isi</label>
								<input type="text" class="form-control" name="prdg_isi" id="prdg_isi" placeholder="cth: 100" value="">
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<label>Satuan</label>
								<input type="text" class="form-control" name="prdg_satuan_isi" id="prdg_satuan_isi" placeholder="cth: ml" value="">
							</div>
						</div>
						<div class="col-lg-6">
							<label for="image-preview" id="label-preview" style="display: none;">Pratinjau</label>
							<img id="image-preview" class="img img-circle" style="width: 150px; height:150px; display:none" alt="">
							<div class="form-group">
								<label>Foto Produk</label>
								<input type="file" class="form-control" onchange="previewImage()" name="prdg_foto" id="prdg_foto" placeholder="Foto" value="">
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Status Produk</label>
								<select class="form-control" name="prdg_status" id="prdg_status">
									<!-- <option value="" selected disabled>== Pilih Status ==</option> -->
									<option value="1">Dijual</option>
									<option value="0">Tidak Dijual</option>
								</select>
							</div>
						</div>

					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="prdg_simpan" class="btn btn-success">Simpan</a>
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
				"url": "ajax_list_produk/",
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

	$("#prdg_tambah").click(function() {
		form_reset();
		$('#modal_produk').modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	});

	function form_reset() {
		$("#prdg_id").val(0);
		$("#frm_produk").trigger("reset");
	}
	$("#frm_produk").submit(function(e) {
		// var dataString = $("#frm_produk").serialize();
		e.preventDefault();
		$("#prdg_simpan").html("Menyimpan...");
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
					$("#modal_produk").modal("hide");
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
				$("#prdg_simpan").html("Simpan");
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

	function hapus_produk(id) {
		event.preventDefault();
		$("#prdg_id").val(id);
		$("#jdlKonfirm").html("Konfirmasi hapus data");
		$("#isiKonfirm").html("Yakin ingin menghapus data ini ?");
		$("#frmKonfirm").modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}

	function ubah_produk(id) {
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "cari",
			data: "prdg_id=" + id,
			dataType: "json",
			success: function(data) {
				var obj = Object.entries(data);
				obj.map((dt) => {
					// console.log(dt);
					if (dt[0] != 'prdg_foto')
						$("#" + dt[0]).val(dt[1]);

				})
				// $("#prdg_id").val(data.prdg_id);
				// $("#prdg_nama").val(data.prdg_nama);
				// $("#prdg_alamat").val(data.prdg_alamat);
				// $("#prdg_telp").val(data.prdg_telp);

				$(".inputan").attr("disabled", false);
				$("#modal_produk").modal({
					show: true,
					keyboard: false,
					backdrop: 'static'
				});
				return false;
			}
		});
	}

	$("#yaKonfirm").click(function() {
		var id = $("#prdg_id").val();

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
	// $('#prdg_deskripsi').summernote();
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
		oFReader.readAsDataURL(document.getElementById("prdg_foto").files[0]);

		oFReader.onload = function(oFREvent) {
			$("#label-preview").css('display', 'block');
			$("#image-preview").css('display', 'block');
			$("#image-preview").attr('src', oFREvent.target.result);
			// document.getElementById("image-preview").src = oFREvent.target.result;
		};
	};
	$(document).ready(function() {
		drawTable();
		$("#modal_produk").on('hidden.bs.modal', function(e) {
			$(".alert-danger").hide();
			form_reset();
			$("#label-preview").css('display', 'none');
			$("#image-preview").css('display', 'none');
		});
	});
</script>