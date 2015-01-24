<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

$SqlPeriode = ""; $startTgl=""; $endTgl="";

# Set Tanggal skrg
$tglStart 	= isset($_POST['cmbTglStart']) ? $_POST['cmbTglStart'] : date('d-m-Y');
$tglEnd 	= isset($_POST['cmbTglEnd']) ? $_POST['cmbTglEnd'] : date('d-m-Y');

# Jika Nomor Page (halaman) diklik
if($_GET) {
	if (isset($_POST['btnShow'])) {
		$SqlPeriode = "AND ( T2.tgl_transaksi BETWEEN '".InggrisTgl($_POST['cmbTglStart'])."' AND '".InggrisTgl($_POST['cmbTglEnd'])."')";
	}
	else {
		$startTgl 	= isset($_GET['startTgl']) ? $_GET['startTgl'] : $tglStart;
		$endTgl 	= isset($_GET['endTgl']) ? $_GET['endTgl'] : $tglEnd; 
		$SqlPeriode = " AND ( T2.tgl_transaksi BETWEEN '".InggrisTgl($startTgl)."' AND '".InggrisTgl($endTgl)."')";
	}
}

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 20;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT T2.tgl_transaksi, T1.*, 
			SUM(T3.jumlah) As terjual, 
			ROUND((T1.harga_jual - (T1.harga_jual)) * T3.jumlah) AS subtotal
			 FROM barang As T1, 
				  penjualan As T2, 
				   penjualan_item As T3
			 WHERE T1.kd_barang = T3.kd_barang 
			 AND T2.no_penjualan 	 = T3.no_penjualan 	$SqlPeriode
			 GROUP BY T1.kd_barang, T2.tgl_transaksi";
$pageQry = mysql_query($pageSql, $koneksidb) or die ("error paging: ".mysql_error());
$jml	 = mysql_num_rows($pageQry);
$max	 = ceil($jml/$row);
?>
<h2>LAPORAN PENJUALAN PER PERIODE</h2>
<form action="" method="post" name="form1" target="_self" id="form1">
  <table width="500" border="0"  class="table-list">
    <tr>
      <td colspan="3" bgcolor="#CCCCCC"><strong>PERIODE TANGGAL </strong></td>
    </tr>
    <tr>
      <td width="90"><strong>Periode </strong></td>
      <td width="5"><strong>:</strong></td>
      <td width="391"><?php echo form_tanggal("cmbTglStart",$tglStart); ?> s/d <?php echo form_tanggal("cmbTglEnd",$tglEnd); ?>
      <input name="btnShow" type="submit" value=" Tampilkan " /></td>
    </tr>
  </table>
</form>
<table class="table-list" width="908" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="25" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="75" align="center" bgcolor="#CCCCCC"><strong>Tanggal</strong></td>
    <td width="74" align="center" bgcolor="#CCCCCC"><b>Kode</b></td>
    <td width="201" bgcolor="#CCCCCC"><b>Nama Barang </b></td>
    <td width="119" align="right" bgcolor="#CCCCCC"><b>Harga Beli  (Rp)</b></td>  
    <td width="124" align="right" bgcolor="#CCCCCC"><b>Harga Jual (Rp)</b></td>
    <td width="154" align="right" bgcolor="#CCCCCC"><strong>Harga Jual+Ppn (RP)</strong></td>
    <td width="95" align="center" bgcolor="#CCCCCC"><b>Qty Terjual</b></td>
    <!--<td align="center" bgcolor="#CCCCCC"><b>view</b></td>-->
    <td align="center" bgcolor="#CCCCCC"><b>nota</b></td>
  </tr>
	<?php
	# Query utama. Query ini sama dg yg dipakai Paging di atas
	$dataSql = "SELECT 
					T2.no_penjualan,T2.tgl_transaksi, T1.*, 
					SUM(T3.jumlah) As terjual, 
					ROUND((T1.harga_jual - (T1.harga_jual)) * T3.jumlah) AS subtotal
				FROM 
					barang As T1, 
					penjualan As T2, 
					penjualan_item As T3
				WHERE 
					T1.kd_barang = T3.kd_barang 
					AND T2.no_penjualan 	 = T3.no_penjualan 	$SqlPeriode
				GROUP 
					BY T1.kd_barang, 
					T2.tgl_transaksi 
				ORDER BY 
					T2.tgl_transaksi ASC 
				LIMIT $hal, $row";
 
	$dataQry = mysql_query($dataSql, $koneksidb) or die ("Error Query".mysql_error());
	$nomor = 0;
	while ($dataRow = mysql_fetch_array($dataQry)) {
		#var_dump($dataRow);exit();
		$nomor++;
	?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td align="center"><?php echo IndonesiaTgl($dataRow['tgl_transaksi']); ?></td>
    <td align="center"><?php echo $dataRow['kd_barang']; ?></td>
    <td><?php echo $dataRow['nm_barang']; ?></td>
    <td align="right"><?php echo format_angka($dataRow['harga_beli']); ?></td>
    <td align="right"><?php echo format_angka($dataRow['harga_jual']); ?></td>
    <td align="right"><?php echo format_angka($dataRow['harga_jual']+(10*($dataRow['harga_jual']/100))); ?></td>
    <td align="center"><?php echo $dataRow['terjual']; ?></td>
	<!--<td align="center"><a href="?page=Daftar-Transaksi-Jasa-List&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Daftar Transaksi Jasa"><img src="images/btn_view.png" width="20" height="20" border="0" /></a></td>-->
	<td align="center"><a href="nota_penjualan.php?noNota=<?php echo $dataRow['no_penjualan']; ?>" target="_self" alt="Daftar Penjualan"><img src="images/Cetak.jpg" width="25" height="25" /></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="3"><b>Jumlah Data :</b> <?php echo $jml; ?> </td>
    <td>&nbsp;</td>
    <td colspan="5" align="right"><b>Halaman ke :</b>
      <?php
	for ($h = 1; $h <= $max; $h++) {
		$list[$h] = $row * $h - $row;
		echo " <a href='?page=Lap-Penjualan-Perperiode&hal=$list[$h]&startTgl='>$h</a> ";
	}
	?></td>
  </tr>
</table>
<p><a href="?page=Laporan-Data" target="_self" alt="Laporan Data"><img src="images/back.png" width="30" height="30" /></p></a>
