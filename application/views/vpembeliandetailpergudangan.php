<?php
$total = 0;
if (!empty($list_pembeliandetail)) {
	$data = explode("-", $list_pembeliandetail);
	$max = count($data);

	if ($max > 0) {
		for ($i = 1; $i < $max; $i++) { ?>
			<tr class="list_item">
				<td><?= $i; ?></td>
				<?php
				$list = explode("_", $data[$i]);
				$ml = count($list);
				for ($j = 0; $j < $ml; $j++) {
					$kanan = "";
					$list_text = explode(".", $list[$j]);
					if ($j == 4) {
						$total += $list_text[1];
					}
					if ($j > 0 and $j <= 5) {
						$list_text[1] = number_format($list_text[1], 0, ",", ".");
						$kanan = "text-right";
					}
					if ($j == 0) {
						$list_text[1] = str_replace("inisimboldan", "&", str_replace("|", "-", $list_text[1]));
					}
				?>
					<td class="<?= $kanan; ?>"><?= $list_text[1]; ?></td>
				<?php
				}
				?>
				<td><a href="javascript:void()" onClick="hapusPembelianDetail('<?= $data[$i]; ?>')" class="btn btn-danger btn-circle"><i class="fa fa-times" /></a></td>
			</tr>
<?php
		}
	}
}
?>
<tr>
	<td colspan="7" align="right" style="font-size:18px;font-weight:bold;">Total<input type="hidden" id="pbl_total_belanja" name="pbl_total_belanja" value=<?= $total; ?>></td>
	<td colspan="2" style="text-align:right;font-size:18px;font-weight:bold;"><?= number_format($total, 0, ",", "."); ?></td>
</tr>

<tr>
	<td colspan="8" align="center"><a href="#" onClick="inputPembelianDetail()" class="btn btn-success"> <i class="fa fa-plus"></i> &nbsp;&nbsp;&nbsp; Tambah Detail Pembelian</a></td>
</tr>
<script>
	var total = <?= $total; ?>;
	if ($("#hutang").is(":checked")) {
		$("#pbl_dibayar").val(0);
	} else {
		$("#pbl_dibayar").val(addCommas(<?= $total; ?>));
	}
</script>