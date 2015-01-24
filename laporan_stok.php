<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";
?>
<h2> DAFTAR STOK BARANG / BAHAN BAKU</h2>
<table class="table-list" width="538" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="47" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="354" bgcolor="#CCCCCC"><b>Nama Barang / Bahan Baku</b></td>
    <td width="117" bgcolor="#CCCCCC"><b>Jumlah Stok</b></td>
  </tr>
  <?php	
//$tmpSql = "	SELECT * FROM pembelian_item ORDER BY no_pembelian ASC ";
$tmpSql = "	SELECT
				*
			FROM
				(
					SELECT
						kd_bahan AS kode,
						nm_bahan AS nama,
						jml_bahan AS stok
					FROM
						bahanbaku
					UNION
						SELECT
							kd_barang AS kode,
							nm_barang AS nama,
							qty AS stok
						FROM
							barang
				) tbitem
			/*LEFT JOIN (
				SELECT
					kd_item
				FROM
					pembelian_item
			) tbitem2 ON tbitem2.kd_item = tbitem.kode*/
			ORDER BY
				tbitem.kode";
//var_dump($tmpSql);exit();
$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
$qtyBrg = 0; $nomor=0;
while($tmpRow = mysql_fetch_array($tmpQry)) {
	
	/*$sTempItem= " SELECT * FROM
					(
					SELECT
						kd_bahan AS kode,
						nm_bahan AS nama,
						jml_bahan AS stok
					FROM
						bahanbaku
					UNION
						SELECT
							kd_barang AS kode,
							nm_barang AS nama,
							qty AS stok
						FROM
							barang
					)tbitem
					where tbitem.kode = '".$tmpRow['kd_item']."'";*/
	#$qTempItem	= mysql_query($sTempItem, $koneksidb) or die ("Gagal Query Tmp Barang".mysql_error());
	#$rTempItem	= mysql_fetch_array($qTempItem);

	/*$sTempbarang	= " SELECT * FROM barang WHERE kd_barang ='".$tmpRow['kd_barang']."'";
	$qTempbarang	= mysql_query($sTempbarang, $koneksidb) or die ("Gagal Query Tmp Barang".mysql_error());
	$rTempbarang	= mysql_fetch_array($qTempbarang);

	$sTempbahan	= " SELECT * FROM bahanbaku WHERE kd_bahan ='".$tmpRow['kd_bahan']."'";
	$qTempbahan	= mysql_query($sTempbahan, $koneksidb) or die ("Gagal Query Tmp Bahan".mysql_error());
	$rTempbahan	= mysql_fetch_array($qTempbahan);
	*/
	#$ID		= $tmpRow['id'];
	#$subSotal = $tmpRow['qty'] * intval($tmpRow['harga_beli']);
	#$total 	= $total + $subSotal;
	$qtyBrg = $tmpRow['qty'];
	
	$nomor++;
?>
  <tr>
    <td align="center"><?php echo $nomor; ?>.</td>
    <td><?php echo $tmpRow['nama']; ?></td>
    <td><?php echo $tmpRow['stok'];?></td>
  </tr>
  <?php } ?>
</table>
<p>&nbsp;</p>