<div class="inner">
	<div class="row">
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<a href="#" class="btn btn-success btn-block" id="splg_tambah"><i class="fa fa-plus"></i> &nbsp;&nbsp;&nbsp; Tambah Data</a>
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
					<h3 class="card-title">Data Supplier</h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="tabel-supplier" width="100%" style="font-size:120%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama</th>
									<th>No. Telpon</th>
									<th>Alamat</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="5" align="center">Tidak ada data</td>
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
<div class="modal fade" id="modal_supplier" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title"><i class="glyphicon glyphicon-info"></i> Form Supplier</h3>
			</div>
			<form role="form  col-lg-6" name="Supplier" id="frm_supplier">
				<div class="modal-body form">
                <div class="alert alert-danger" style="display:none; list-style-type: none;"></div> 
					<div class="row">
						<input type="hidden" id="splg_id" name="splg_id" value="">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Nama</label>
								<input type="text" class="form-control" name="splg_nama" id="splg_nama" placeholder="Nama" value="">
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>No. Telpon</label>
								<input type="text" class="form-control" name="splg_telp" id="splg_telp" placeholder="No. Telpon" value="">
							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group">
								<label>Alamat</label>
								<input type="text" class="form-control" name="splg_alamat" id="splg_alamat" placeholder="Alamat" value="">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="splg_simpan" class="btn btn-success">Simpan</a>
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
				"url": "ajax_list_supplier/",
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

	$("#splg_tambah").click(function() {
		form_reset();
		$('#modal_supplier').modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	});

	function form_reset() {
		$("#splg_id").val(0);
		$("#frm_supplier").trigger("reset");
	}
	$("#frm_supplier").submit(function(e) {
		// var dataString = $("#frm_supplier").serialize();
		e.preventDefault();
		$("#splg_simpan").html("Menyimpan...");
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
					$("#modal_supplier").modal("hide");	
					drawTable();
				} else {
					msg = res.desc;
					toastr.error(msg);
                    $(".alert-danger").html("");
                    $.each(res.error, function (key, value) {
                    $(".alert-danger").show();
                    $(".alert-danger").append("<li>" + value + "</li>");
                });
				}
              	
				// $("#pesan_info_ok").html(msg);
				$("#splg_simpan").html("Simpan");
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

	function hapus_supplier(id) {
		event.preventDefault();
		$("#splg_id").val(id);
		$("#jdlKonfirm").html("Konfirmasi hapus data");
		$("#isiKonfirm").html("Yakin ingin menghapus data ini ?");
		$("#frmKonfirm").modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}

	function ubah_supplier(id) {
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "cari",
			data: "splg_id=" + id,
			dataType: "json",
			success: function(data) {
				var obj = Object.entries(data);
				obj.map((dt) => {
					$("#" + dt[0]).val(dt[1]);
				})
				// $("#splg_id").val(data.splg_id);
				// $("#splg_nama").val(data.splg_nama);
				// $("#splg_alamat").val(data.splg_alamat);
				// $("#splg_telp").val(data.splg_telp);

				$(".inputan").attr("disabled", false);
				$("#modal_supplier").modal({
					show: true,
					keyboard: false,
					backdrop: 'static'
				});
				return false;
			}
		});
	}

	$("#yaKonfirm").click(function() {
		var id = $("#splg_id").val();

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

	$(document).ready(function() {
		drawTable();
		$("#modal_supplier").on('hidden.bs.modal', function(e) {
			$(".alert-danger").hide();
		});
	});
</script>