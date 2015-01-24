<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";
?>
<h2> DAFTAR TRANSAKSI PENJUALAN </h2>
<p><a href="?page=Search-Penjualan"><img src="images/btn_search1.png" width="125" height="35" /></a></p>
<table class="table-list" width="700" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="25" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="90" bgcolor="#CCCCCC"><b>Tanggal</b></td>
    <td width="115" bgcolor="#CCCCCC"><b>Nomor Jual </b></td>
    <td width="155" bgcolor="#CCCCCC"><b>Pelanggan </b></td>
    <td width="104" bgcolor="#CCCCCC"><b>Petugas</b></td>
    <td width="66" align="center" bgcolor="#CCCCCC"><b>View</b></td>
    <td width="109" align="center" bgcolor="#CCCCCC"><strong>Nota</strong></td>
  </tr>
  <?php
	# Perintah untuk menampilkan Semua Daftar Transaksi Penjualan
	$jualSql = "SELECT * FROM penjualan ORDER BY no_penjualan ASC";
	$jualQry = mysql_query($jualSql, $koneksidb)  or die ("Query penjualan salah : ".mysql_error());
	$nomor  = 0; 
	while ($jualRow = mysql_fetch_array($jualQry)) {
	$nomor++;
	
	# Membaca Kode Penjualan/ Nomor transaksi
	$Kode = $jualRow['no_penjualan'];
	
	$sPELANGGAN	= " SELECT * 
					FROM pelanggan
					WHERE kd_pelanggan='".$jualRow['kd_pelanggan']."'";
	$qPELANGGAN	= mysql_query($sPELANGGAN)or die(mysql_error());
	$rPELANGGAN	= mysql_fetch_array($qPELANGGAN);
?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td><?php echo IndonesiaTgl($jualRow['tgl_transaksi']); ?></td>
    <td><?php echo $jualRow['no_penjualan']; ?></td>
    <td><?php echo $rPELANGGAN['nm_pelanggan']; ?></td>
    <td><?php echo $jualRow['userid']; ?></td>
    <td align="center"><a href="?page=Daftar-Penjualan-List&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Daftar Penjualan"><img src="images/btn_view.png" width="20" height="20" border="0" /></a></td>
    <td align="center"><a href="nota_penjualan.php?noNota=<?php echo $jualRow['no_penjualan']; ?>" target="_self" alt="Daftar Penjualan"><img src="images/Cetak.jpg" width="25" height="25" /></td>
  </tr>
  <?php } ?>
</table>
<p><a href="?page=Laporan-Data" target="_self" alt="Laporan Data"><img src="images/back.png" width="30" height="30" /></p></a>