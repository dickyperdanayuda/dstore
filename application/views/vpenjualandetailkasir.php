
<tr>
	<td colspan="5" align="right" style="font-size:18px;font-weight:bold;">Total<input type="hidden" id="pjl_total_belanja" name="pjl_total_belanja" value=<?= $total; ?>></td>
	<td colspan="2" style="text-align:right;font-size:18px;font-weight:bold;"><?= number_format($total, 0, ",", "."); ?></td>
</tr>
<tr>
	<td colspan="7" align="center"><a href="#" onClick="inputPenjualanDetail()" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;&nbsp;&nbsp; Tambah Detail Penjualan</a></td>
</tr>
<script>
	var diskon = $("#pjl_diskon").val();
	var ongkir = $("#pjl_ongkir").val();
	var total = <?= $total; ?>;
	if ($("#hutang").is(":checked")) {
		$("#pjl_dibayar").val(0);
	} else {
		$("#pjl_dibayar").val(addCommas((parseInt($("#pjl_total_belanja").val()) + parseInt(ongkir)) - parseInt(diskon)));
	}
</script>