<div class="inner">
	<div class="row">
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<a href="#" class="btn btn-success btn-block" id="mrkg_tambah"><i class="fa fa-plus"></i> &nbsp;&nbsp;&nbsp; Tambah Data</a>
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
					<h3 class="card-title">Data Merek</h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="tabel-supplier" width="100%" style="font-size:120%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama</th>
									<th>Logo</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="2" align="center">Tidak ada data</td>
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
<div class="modal fade" id="modal_merek" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title"><i class="glyphicon glyphicon-info"></i> Form Merek</h3>
			</div>
			<form role="form  col-lg-6" enctype="multipart/form-data" name="Merek" id="frm_merek">
				<div class="modal-body form">
					<div class="alert alert-danger" style="display:none; list-style-type: none;"></div>
					<div class="row">
						<input type="hidden" id="mrkg_id" name="mrkg_id" value="">
						<img id="image">
						<div class="col-lg-12">
							<div class="form-group">
								<label>Nama</label>
								<input type="text" class="form-control" name="mrkg_nama" id="mrkg_nama" placeholder="Nama" value="">
							</div>

							<div class="form-group">
								<label for="image-preview" id="label-preview" style="display: none;">Pratinjau</label>
								<img id="image-preview" class="img img-circle" style="width: 150px; height:150px; display:none" alt="">
								<label>Logo</label>
								<input type="file" class="form-control" name="mrkg_logo" onchange="previewImage();" id="mrkg_logo" placeholder="Logo" value="">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="mrkg_simpan" class="btn btn-success">Simpan</a>
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
<script src="<?= base_url("assets"); ?>/plugins/select2/js/select2.full.js"></script>

<!-- Toastr -->
<script src="<?= base_url("assets"); ?>/plugins/toastr/toastr.min.js"></script>

<!-- Custom Java Script -->
<!-- <script src="<?= base_url("assets/"); ?>assets/js/supplier.js"></script> -->
<script>
	var save_method; //for save method string
	var table;
	var baselink = $("#base_link").val();

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
				"url": "ajax_list_merek/",
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

	$("#mrkg_tambah").click(function() {
		// form_reset();
		$('#modal_merek').modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	});

	function form_reset() {
		$("#mrkg_id").val(0);
		$("#frm_merek").trigger("reset");
	}
	$("#frm_merek").submit(function(e) {
		// var dataString = $("#frm_merek").serialize();
		e.preventDefault();
		$("#mrkg_simpan").html("Menyimpan...");
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
					$("#modal_merek").modal("hide");
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
				$("#mrkg_simpan").html("Simpan");
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

	function hapus_merek(id) {
		event.preventDefault();
		$("#mrkg_id").val(id);
		$("#jdlKonfirm").html("Konfirmasi hapus data");
		$("#isiKonfirm").html("Yakin ingin menghapus data ini ?");
		$("#frmKonfirm").modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}

	function ubah_merek(id) {
		var path = baselink + "assets/images_pergudangan/merek/thumbs/";
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "cari",
			data: "mrkg_id=" + id,
			dataType: "json",
			success: function(data) {
				var obj = Object.entries(data);
				obj.map((dt) => {
					if (dt[0] != "mrkg_logo") {

						$("#" + dt[0]).val(dt[1]);
					}
					// if (dt[0] == "mrkg_logo") {
					// 	$("#image").attr("src", path + dt[1]);
					// 	$("#image").attr("alt", "Merek Logo");
					// 	// $("#div_image").css('display', 'block');
					// 	// $("#div_konten").removeClass('col-lg-12')
					// 	// $("#div_konten").addClass('col-lg-6')
					// }

				});
				// $("#mrkg_id").val(data.mrkg_id);
				// $("#mrkg_nama").val(data.mrkg_nama);
				// $("#mrkg_alamat").val(data.mrkg_alamat);
				// $("#mrkg_telp").val(data.mrkg_telp);

				$(".inputan").attr("disabled", false);
				$("#modal_merek").modal({
					show: true,
					keyboard: false,
					backdrop: 'static'
				});
				return false;
			}
		});
	}

	$("#yaKonfirm").click(function() {
		var id = $("#mrkg_id").val();

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
		oFReader.readAsDataURL(document.getElementById("mrkg_logo").files[0]);

		oFReader.onload = function(oFREvent) {
			$("#label-preview").css('display', 'block');
			$("#image-preview").css('display', 'block');
			$("#image-preview").attr('src', oFREvent.target.result);
			// document.getElementById("image-preview").src = oFREvent.target.result;
		};
	};

	$(document).ready(function() {
		drawTable();
		$("#modal_merek").on('hidden.bs.modal', function(e) {
			$(".alert-danger").hide();
			form_reset();
			$("#label-preview").css('display', 'none');
			$("#image-preview").css('display', 'none');
		});
	});
</script>