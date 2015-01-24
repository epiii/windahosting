<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";
?>
<h2> DAFTAR TRANSAKSI JASA</h2>
<p><a href="?page=Search-Jasa"><img src="images/btn_search1.png" width="125" height="35" /></a></p>
<table class="table-list" width="800" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="30" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="86" bgcolor="#CCCCCC"><b>Tanggal</b></td>
    <td width="166" bgcolor="#CCCCCC"><b>Nomor Transaksi Jasa</b></td>  
    <td width="234" bgcolor="#CCCCCC"><b>Pelanggan </b></td>
    <td width="100" bgcolor="#CCCCCC"><b>Petugas</b></td>
    <td width="72" align="center" bgcolor="#CCCCCC"><b>View</b></td>
    <td width="76" align="center" bgcolor="#CCCCCC"><strong>Nota</strong></td>
  </tr>
<?php
	# Perintah untuk menampilkan Semua Daftar Transaksi Penjualan
	$jualSql = "SELECT * FROM transaksi ORDER BY no_transaksi ASC";
	$jualQry = mysql_query($jualSql, $koneksidb)  or die ("Query penjualan salah : ".mysql_error());
	$nomor  = 0; 
	while ($jualRow = mysql_fetch_array($jualQry)) {
	$nomor++;
	
	# Membaca Kode Penjualan/ Nomor transaksi
	$Kode = $jualRow['no_transaksi'];
	
	$sPELANGGAN	= " SELECT * 
					FROM pelanggan
					WHERE kd_pelanggan='".$jualRow['kd_pelanggan']."'";
	$qPELANGGAN	= mysql_query($sPELANGGAN)or die(mysql_error());
	$rPELANGGAN	= mysql_fetch_array($qPELANGGAN);
?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td><?php echo IndonesiaTgl($jualRow['tgl_transaksi']); ?></td>
    <td><?php echo $jualRow['no_transaksi']; ?></td>
    <td><?php echo $rPELANGGAN['nm_pelanggan']; ?></td>
    <td><?php echo $jualRow['userid']; ?></td>
   <td align="center"><a href="?page=Daftar-Transaksi-Jasa-List&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Daftar Transaksi Jasa"><img src="images/btn_view.png" width="20" height="20" border="0" /></a></td>
   <td align="center"><a href="nota_jasa.php?noNota=<?php echo $jualRow['no_transaksi']; ?>" target="_self" alt="Daftar Transaksi Jasa"><img src="images/Cetak.jpg" width="25" height="25" /></td>
  </tr>
  <?php } ?>
</table>
<p><a href="?page=Laporan-Data" target="_self" alt="Laporan Data"><img src="images/back.png" width="30" height="30" /></p></a>
