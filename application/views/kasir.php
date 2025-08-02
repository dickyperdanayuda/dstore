<div class="inner">

	<form id="frm_kasir">
		<input type="hidden" id="detail_jual" name="detail">
		<input type="hidden" id="pjl_total_belanja" name="pjl_total_belanja">
		<div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label>Tanggal Penjualan</label>
					<input type="text" class="form-control tgl" name="pjl_tgl" id="pjl_tgl" placeholder="Tanggal Penjualan" value="<?= date('d/m/Y'); ?>" required>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
					<label>No Faktur</label>
					<input type="text" class="form-control" name="pjl_kode" id="pjl_kode" placeholder="No Faktur" value="">
				</div>
			</div>
		</div>
		<div class="row">
			<!-- <div class="col-lg-6">
				<div class="form-group">
					<label>Kepada</label>
					<input type="text" class="form-control" name="pjl_kepada" id="pjl_kepada" placeholder="Kepada" value="">
				</div>
			</div> -->
			<div class="col-lg-6">
				<div class="form-group">
					<label>Diskon Global</label>
					<input type="text" class="form-control rp text-right" onChange="kasikoma(this.id)" onKeyUp="kasikoma(this.id)" name="pjl_diskon" id="pjl_diskon" placeholder="Diskon" value="0">
				</div>
			</div>

			<!-- </div>
		<div class="row"> -->
			<div class="col-lg-6">
				<div class="form-group">
					<label>Dibayar ( <input type="checkbox" id="hutang" style="position:relative;top:2px;"> Hutang )</label>
					<input type="text" class="form-control rp text-right" onChange="kasikoma(this.id)" onKeyUp="kasikoma(this.id)" name="pjl_dibayar" id="pjl_dibayar" placeholder="Dibayar" value="0">
				</div>
			</div>
		</div>
	</form>



	<div class="row" id="isidata">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<form id="frmCari">
						<div class="panel-heading row">
							<div class="col-lg-2">
								<input type="radio" name="mode" id="mode_id" value="1"> ID Produk
							</div>
							<div class="col-lg-2">
								<input type="radio" name="mode" checked id="mode_barcode" value="2"> Barcode
							</div>
							<div class="col-lg-4" style="padding-right:5px;padding-left:5px;">
								<input type="text" style="display: none;" id="cari_id" class="form-control hide" name="cari_id">
								<input type="text" id="cari_barcode" class="form-control" name="cari_barcode" autofocus>
							</div>
							<div class="col-lg-1" style="padding-right:5px;padding-left:5px;">
								<button type="button" name="btnCari" id="btnCari" class="btn btn-block btn-primary"><i class="fa fa-search" style="margin-right:5px;"></i>Cari</button>
							</div>
							<div class="col-lg-3" style="padding-right:5px;padding-left:5px;">
								<div class="card bg-danger" style="padding:5px 5px 5px 5px;">
									<div class="card-body" style="font-size:18px;font-weight:bold;">
										Total Bayar : <span id="total_bayar" class="float-right text-right">0</span>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="tbl-kasir" width="100%" style="font-size:120%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Produk</th>
									<th>Jumlah</th>
									<th>Harga Modal</th>
									<th>Harga Barang</th>
									<th>Diskon Barang</th>
									<th>Total Bayar</th>
									<th>Hapus</th>
								</tr>
							</thead>
							<tbody id="data_keranjang">
								<tr>
									<td colspan="7" align="center">Tidak ada data</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="panel-heading row">
						<div class="col-lg-1 pull-right" style="padding-right:5px;padding-left:5px;">
							<button type="button" id="btnBayar" class="btn btn-block btn-dark" onClick="bayar()"><i class="fa fa-usd" style="margin-right:5px;"></i>Bayar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Select2 -->
<script src="<?= base_url("assets/"); ?>assets/plugins/select2/js/select2.full.min.js"></script>
<!-- daterangepicker -->
<script src="<?= base_url("assets/"); ?>assets/plugins/daterangepicker/moment.js"></script>
<script src="<?= base_url("assets/"); ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Custom Java Script -->
<!-- <script src="<?= base_url("assets/"); ?>assets/js/kasir.js"></script> -->
<script>
	var save_method; //for save method string
	var table;
	var baselink = $("#baselink").val();

	function drawTable() {
		$('#tabel-kasir').DataTable({
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
				"url": "ajax_list_kasir/",
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

	$("#pjl_tambah").click(function() {
		$("#pjl_id").val(0);
		$("#frm_kasir").trigger("reset");
		$('#modal_kasir').modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	});

	$("#pjl_nokitas").change(function() {
		var isi = $(this).val();
		if (isi == "Prodi") {
			$("#inputProdi").show();
			$("#inputFakultas").hide();
		} else {
			$("#inputProdi").hide();
			$("#inputFakultas").show();
		}
		// alert(isi);
	});

	$("#frm_kasir").submit(function(e) {
		// var dataString = $("#frm_kasir").serialize();
		e.preventDefault();
		$("#pjl_simpan").html("Menyimpan...");
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
					localStorage.removeItem("keranjang");
					tampilKeranjang();
					$(this).trigger("reset");
					toastr.success("Pembayaran berhasil disimpan");
					setTimeout(function() {
						location.reload();
					}, 2000);
				} else {
					toastr.error("Pembayaran gagal disimpan \n" + res.error);
				}
				$("#pjl_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$("#pjl_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
				alert('Error get data from ajax');
				console.log(d);
			}
		});

	});

	function bayar() {
		var penjualandetail = "";
		var detail = JSON.parse(localStorage.getItem("keranjang"));
		// console.log(detail);
		detail.map((dt) => {
			var total = (dt.jumlahpesan * dt.harga) - dt.diskon;
			penjualandetail += ";" + dt.id + "-" + dt.jumlahpesan + "-" + dt.harga + "-" + dt.diskon + "-" + total;
		});
		$("#detail_jual").val(penjualandetail);
		$("#frm_kasir").trigger("submit");
	}

	// $("#ok_info_ok").click(function() {
	// 	document.location.href = "";
	// });

	$("input[name=mode]", "#frmCari").change(function() {
		var mode = $('input[name=mode]:checked', '#frmCari').val();
		if (mode == 1) {
			// $("#cari_barcode").addClass("hide");
			$("#cari_barcode").css("display", "none");
			// $("#cari_id").removeClass("hide");
			$("#cari_id").css("display", "block");
			$("#cari_id").focus();
		} else {
			// $("#cari_barcode").removeClass("hide");
			$("#cari_barcode").css("display", "block");
			// $("#cari_id").addClass("hide");
			$("#cari_id").css("display", "none");
			$("#cari_barcode").focus();
		}
	});

	// $("#frmCari").submit(function(e) {
	// 	// e.preventDefault();
	// 	var mode = $('input[name=mode]:checked', '#frmCari').val();
	// 	var val;
	// 	if (mode == 1) {
	// 		val = $("#cari_id").val();
	// 		$("#cari_id").val("");
	// 	} else {
	// 		val = $("#cari_barcode").val();
	// 		$("#cari_barcode").val("");
	// 	}
	// 	tambahKeranjang(mode, val);
	// });

	$("#btnCari").on('click', function(e) {
		e.preventDefault();
		var mode = $('input[name=mode]:checked', '#frmCari').val();
		var val;
		if (mode == 1) {
			val = $("#cari_id").val();
			$("#cari_id").val("");
		} else {
			val = $("#cari_barcode").val();
			$("#cari_barcode").val("");
		}
		tambahKeranjang(mode, val);
	});

	function cekKeranjang(id, keranjang) {
		for (var i = 0; i < keranjang.length; i++) {
			if (keranjang[i].id == id) {
				return i;
			}
		}
	}

	function cek_produk(mode, val) {
		var list_produk = JSON.parse(localStorage.getItem("produk"));
		for (var i = 0; i < list_produk.length; i++) {
			if (mode == 1) {
				if (list_produk[i].id == val) {
					return list_produk[i];
				}
			} else {
				if (list_produk[i].barcode == val) {
					return list_produk[i];
				}
			}
		}
	}

	function tampilKeranjang() {
		var keranjang = JSON.parse(localStorage.getItem("keranjang"));
		console.log(keranjang);
		if (keranjang) {
			$("#btnBayar").attr("disabled", false);
			totalbelanja = 0;
			ongkir = 0;
			totalbayar = 0;
			var data = "";
			keranjang.map((dt, i) => {
				var nama = dt.nama ? dt.nama.toLowerCase() : "";
				var diskon = dt.diskon ? dt.diskon : 0;
				var totalharga = (dt.harga * dt.jumlahpesan) - diskon;
				var plus = "<button type='button' class='btn btn-xs btn-success' onClick='tambahJumlahKeranjang(" + i + ",1)' style='margin-left:15px;'><i class='fa fa-plus'></i></button>";
				if (dt.jumlahpesan >= dt.stok) plus = "";
				var minus = "<button type='button' class='btn btn-xs btn-danger' onClick='tambahJumlahKeranjang(" + i + ",-1)' style='margin-right:15px;'><i class='fa fa-minus'></i></button>";
				if (dt.jumlahpesan == 1) minus = "";
				totalbelanja += totalharga;
				data += "<tr><td>" + parseInt(i + 1) + "</td><td><img src='" + dt.gbr + "' style='width:70px;height:70;' alt=''></td><td><h5 style='text-transform:capitalize;'>" + nama + " " + dt.ukuran + " " + dt.satuan + " (" + dt.isi + " " + dt.kemasan + ")</h5></td><td class='shoping__cart__price text-center' width='150'>" + minus + "<input type='text' style='width:50px;text-align:right;' onFocus='this.select()' value='" + dt.jumlahpesan + "' onFocusOut='ubahJumlahKeranjang(" + i + ",this.value)'>" + plus + "</td><td class='text-right'>Rp. " + addCommas(dt.modal) + "</td><td class='text-right'>Rp. <input type='text' style='width:100px;text-align:right;' onFocus='this.select()' value='" + addCommas(dt.harga) + "' id='" + i + "harga' onInput='kasikoma(this.id)' onFocusOut='ubahHargaKeranjang(" + i + ",this.value)'></td><td class='text-right'>Rp. <input type='text' style='width:100px;text-align:right;' onFocus='this.select()' value='" + addCommas(dt.diskon) + "' id='" + i + "diskon' onInput='kasikoma(this.id)' onFocusOut='ubahDiskonKeranjang(" + i + ",this.value)'></td><td class='text-right'>Rp. " + addCommas(totalharga) + "</td><td class='text-center'><a class='text-danger' href='javascript:hapusKeranjang(" + i + ")'><i class='fas fa-times	'></i></a></td></tr>";
			});
			if (totalbelanja >= 100000) {
				ongkir = 0;
			}
			totalbayar = totalbelanja + ongkir;
			$("#data_keranjang").html(data);
			$("#totalbelanja").html("Rp. " + addCommas(totalbelanja));
			// $("#ongkir").html("Rp. "+addCommas(ongkir));
			// $("#ongkir").html("<b>Free</b>");
			$("#total_bayar").html("Rp. " + addCommas(totalbayar));
			$("#pjl_dibayar").val(addCommas(totalbayar));
			$("#pjl_total_belanja").val(addCommas(totalbelanja));
		} else {
			$("#btnBayar").attr("disabled", true);
			$("#data_keranjang").html("");
		}
	}

	// function tambahKeranjang(mode,val)
	// {
	// event.preventDefault();
	// $.post(baselink+"Kasir/cari_produk",{mode:mode,val:val},function(prd){
	// var produk = JSON.parse(prd);
	// console.log(produk);
	// if (produk.id)
	// {
	// var jml = 1;
	// produk.jumlahpesan = jml;
	// var data = JSON.parse(localStorage.getItem("keranjang"));
	// if (data && data.length > 0)
	// {
	// var cek = cekKeranjang(produk.id,data);
	// alert(cek);
	// if (cek !== undefined)
	// {
	// data[cek].jumlahpesan += jml;
	// }
	// else
	// {
	// data.push(produk);				
	// }
	// }
	// else 
	// {
	// data = [
	// produk
	// ]		
	// }
	// localStorage.setItem("keranjang", JSON.stringify(data));
	// toastr.success("Berhasil menambahkan "+produk.nama+" "+produk.ukuran+" "+produk.satuan+" ("+produk.isi+" "+produk.kemasan+") ke dalam keranjang");
	// tampilKeranjang();
	// }
	// else 
	// {
	// toastr.error("Produk tidak ditemukan!");
	// }
	// });
	// }

	function tambahKeranjang(mode, val) {
		// event.preventDefault();
		var prd = cek_produk(mode, val);
		console.log(prd);
		if (prd) {
			var produk = prd;
			// console.log(produk);
			if (produk.id) {
				var jml = 1;
				produk.jumlahpesan = jml;
				var data = JSON.parse(localStorage.getItem("keranjang"));
				if (data && data.length > 0) {
					var cek = cekKeranjang(produk.id, data);
					// alert(cek);
					if (cek !== undefined) {
						if (data[cek].jumlahpesan < data[cek].stok) {
							data[cek].jumlahpesan += jml;
						} else {
							data[cek].jumlahpesan = data[cek].stok;
							toastr.error("Stok untuk " + produk.nama + " " + produk.ukuran + " " + produk.satuan + " (" + produk.isi + " " + produk.kemasan + " sudah mencapai batas pembelian");
						}
					} else {
						data.push(produk);
					}
				} else {
					data = [
						produk
					]
				}
				localStorage.setItem("keranjang", JSON.stringify(data));
				// toastr.success("Berhasil menambahkan " + produk.nama + " " + produk.ukuran + " " + produk.satuan + " (" + produk.isi + " " + produk.kemasan + ") ke dalam keranjang");
				tampilKeranjang();
			} else {
				toastr.error("Produk tidak ditemukan!");
			}
		} else {
			toastr.error("Produk tidak ditemukan!");
		}
	}

	function hapusKeranjang(i) {
		var keranjang = JSON.parse(localStorage.getItem("keranjang"));
		if (keranjang) {
			keranjang.splice(i, 1);
			localStorage.setItem("keranjang", JSON.stringify(keranjang));
			toastr.success('Berhasil menghapus item');
			tampilKeranjang();
		}
	}

	function tambahJumlahKeranjang(i, jml) {
		var keranjang = JSON.parse(localStorage.getItem("keranjang"));
		if (keranjang) {
			var jumlahpesan = parseInt(keranjang[i].jumlahpesan);
			jumlahpesan += parseInt(jml)
			keranjang[i].jumlahpesan = jumlahpesan
			localStorage.setItem("keranjang", JSON.stringify(keranjang));
			tampilKeranjang();
		}
	}

	function ubahJumlahKeranjang(i, jml) {
		var keranjang = JSON.parse(localStorage.getItem("keranjang"));
		if (keranjang) {
			if (parseInt(jml) > parseInt(keranjang[i].stok)) jml = keranjang[i].stok;
			keranjang[i].jumlahpesan = jml
			localStorage.setItem("keranjang", JSON.stringify(keranjang));
			tampilKeranjang();
		}
	}

	function ubahHargaKeranjang(i, jml) {
		var keranjang = JSON.parse(localStorage.getItem("keranjang"));
		if (keranjang) {
			var harga = jml.replace(/\./g, '');
			if (parseInt(harga) < parseInt(keranjang[i].modal)) harga = keranjang[i].modal;
			keranjang[i].harga = harga;
			localStorage.setItem("keranjang", JSON.stringify(keranjang));
			tampilKeranjang();
		}
	}

	function ubahDiskonKeranjang(i, jml) {
		var keranjang = JSON.parse(localStorage.getItem("keranjang"));
		if (keranjang) {
			keranjang[i].diskon = jml.replace(/\./g, '');
			localStorage.setItem("keranjang", JSON.stringify(keranjang));
			tampilKeranjang();
		}
	}

	function hapus_kasir(id) {
		// event.preventDefault();
		$("#idSts").val(id);
		$("#jdlKonfirm").html("Konfirmasi hapus data");
		$("#isiKonfirm").html("Yakin ingin menghapus data ini ?");
		$("#frmKonfirm").modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}

	function ubah_kasir(id) {
		// event.preventDefault();
		$.ajax({
			type: "POST",
			url: "cari",
			data: "pjl_id=" + id,
			dataType: "json",
			success: function(data) {
				$("#pjl_id").val(data.pjl_id);
				$("#pjl_nama").val(data.pjl_nama);
				$("#pjl_kode").val(data.pjl_kode);

				$(".inputan").attr("disabled", false);
				$("#modal_kasir").modal({
					show: true,
					keyboard: false,
					backdrop: 'static'
				});
				return false;
			}
		});
	}

	function load_produk() {
		// event.preventDefault();
		// localStorage.setItem('produk','redvelvet');
		$.get("load_produk", {}, function(prd) {
			var produk = JSON.parse(prd);
			// console.log(produk);
			localStorage.setItem("produk", JSON.stringify(produk));

		});
	}

	$("#yaKonfirm").click(function() {
		var id = $("#idSts").val();

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
				} else {
					msg = res.desc + "[" + res.err + "]";
				}
				$("#isiKonfirm").html(msg);
				$(".utama").hide();
				$(".cadangan").show();
				$(".btn").attr("disabled", false);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	});

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

	function kasikoma(id) {
		var isi = $("#" + id).val().replace(/\./g, '');
		$("#" + id).val(addCommas(isi));
	}

	// $('.tgl').daterangepicker({
	// 	locale: {
	// 		format: 'DD/MM/YYYY'
	// 	},
	// 	showDropdowns: true,
	// 	singleDatePicker: true,
	// 	"autoAppjl": true,
	// 	opens: 'left'
	// });

	$(document).ready(function() {
		tampilKeranjang();
		load_produk();
	});
</script>