var save_method; //for save method string
var table;

function drawTable() {
	$('#tabel-pelanggan').DataTable({
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
		// "sProcessing": '<center><img src="<?= base_url("assets/");?>assets/img/fb.gif" style="width:2%;"> Loading Data</center>',
		// },
		"responsive": true,
		"sort": true,
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"order": [], //Initial no order.
		// Load data for the table's content from an Ajax source
		"ajax": {
			"url": "ajax_list_pelanggan/",
			"type": "POST"
		},
		//Set column definition initialisation properties.
		"columnDefs": [
			{
				"targets": [-1], //last column
				"orderable": false, //set not orderable
			},
		],
		"initComplete": function (settings, json) {
			$("#process").html("<i class='glyphicon glyphicon-search'></i> Process")
			$(".btn").attr("disabled", false);
			$("#isidata").fadeIn();
		}
	});
}

function ubah_pelanggan(id) {
	event.preventDefault();
	$.ajax({
		type: "POST",
		url: "cari",
		data: "plgc_id=" + id,
		dataType: "json",
		success: function (data) {
			$("#plgc_id").val(data.plgc_id);
			$("#plgc_nama").val(data.plgc_nama);
			$("#plgc_telp").val(data.plgc_telp);
			$("#plgc_alamat").val(data.plgc_alamat);
			$("#plgc_tgl_bergabung").val(data.plgc_tgl_bergabung);
			$(".inputan").attr("disabled", false);
			$("#modal_pelanggan").modal({
				show: true,
				keyboard: false,
				backdrop: 'static'
			});
			return false;
		}
	});
}
function reset_form() {
	$("#plgc_id").val(0);
	$("#frm_pelanggan")[0].reset();
}

