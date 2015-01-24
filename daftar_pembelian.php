<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";
?>
<h2> DAFTAR TRANSAKSI PEMBELIAN</h2>
<p><a href="?page=Search-Pembelian"><img src="images/btn_search1.png" width="125" height="35" /></a></p>
<table class="table-list" width="600" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="23" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="66" bgcolor="#CCCCCC"><b>Tanggal</b></td>
    <td width="84" bgcolor="#CCCCCC"><b>Nomor Beli </b> </td>  
    <td width="180" bgcolor="#CCCCCC"><b>Supplier </b></td>
    <td width="92" bgcolor="#CCCCCC"><b>Petugas</b></td>
    <td width="44" align="center" bgcolor="#CCCCCC"><b>View</b></td>
  </tr>
<?php
	# Perintah untuk menampilkan Semua Daftar Transaksi Pembelian
	$beliSql = "SELECT pembelian.*, supplier.nm_supplier FROM pembelian, supplier 
				WHERE pembelian.kd_supplier=supplier.kd_supplier 
				ORDER BY pembelian.no_pembelian ASC";
	$beliQry = mysql_query($beliSql, $koneksidb)  or die ("Query pembelian salah : ".mysql_error());
	$nomor  = 0; 
	while ($beliRow = mysql_fetch_array($beliQry)) {
	$nomor++;
	
	# Membaca Kode Pembelian/ Nomor transaksi
	$Kode = $beliRow['no_pembelian'];
?>
  <tr>
    <td align="center"><?php echo $nomor; ?>.</td>
    <td><?php echo IndonesiaTgl($beliRow['tgl_transaksi']); ?></td>
    <td><?php echo $beliRow['no_pembelian']; ?></td>
    <td><?php echo $beliRow['nm_supplier']; ?></td>
    <td><?php echo $beliRow['userid']; ?></td>
    <td align="center"><a href="?page=Daftar-Pembelian-List&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Daftar Pembelian"><img src="images/btn_view.png" width="20" height="20" border="0" /></a></td>

  </tr>
  <?php } ?>
</table>
<p><a href="?page=Laporan-Data" target="_self" alt="Laporan Data"><img src="images/back.png" width="30" height="30" /></p></a>
