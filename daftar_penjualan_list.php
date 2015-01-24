<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

# Baca variabel URL
$kodeTransaksi = $_GET['Kode'];

# Perintah untuk mendapatkan data dari tabel penjualan
$jualSql = "SELECT * FROM penjualan WHERE no_penjualan='$kodeTransaksi'";
$jualQry = mysql_query($jualSql, $koneksidb)  or die ("Query penjualan salah : ".mysql_error());
$jualRow = mysql_fetch_array($jualQry);

	$sPELANGGAN	= " SELECT * 
					FROM pelanggan
					WHERE kd_pelanggan='".$jualRow['kd_pelanggan']."'";
	$qPELANGGAN	= mysql_query($sPELANGGAN)or die(mysql_error());
	$rPELANGGAN	= mysql_fetch_array($qPELANGGAN);
?>
<table width="600" border="0" class="table-list">
<tr>
  <th colspan="3"><b>TRANSAKSI PENJUALAN </b></th>
</tr>
<tr>
  <td width="155"><b>No Penjualan </b></td>
  <td width="5"><b>:</b></td>
  <td width="379"><?php echo $jualRow['no_penjualan']; ?></td>
</tr>
<tr>
  <td><b>Tanggal</b></td>
  <td><b>:</b></td>
  <td><?php echo IndonesiaTgl($jualRow['tgl_transaksi']); ?></td>
</tr>
<tr>
  <td><b>Pelanggan</b></td>
  <td><b>:</b></td>
  <td><?php echo $rPELANGGAN['nm_pelanggan']; ?></td>
</tr>
<tr>
  <td><strong>Keterangan Pembayaran</strong></td>
  <td><strong>:</strong></td>
  <td><?php echo $jualRow['catatan']; ?></td>
</tr>
<tr>
  <td><b>Petugas</b></td>
  <td><b>:</b></td>
  <td><?php echo $jualRow['userid']; ?></td>
</tr>
</table>
  
<h2> Daftar Barang</h2>
<table class="table-list" width="780" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="26" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="65" align="center" bgcolor="#CCCCCC"><b>Kode</b></td>
    <td width="268" bgcolor="#CCCCCC"><b>Nama Barang </b></td>
    <td width="125" align="center" bgcolor="#CCCCCC"><b>Harga (Rp)</b></td>
    <td width="70" align="right" bgcolor="#CCCCCC"><strong>Ppn(%)</strong></td>
    <td width="70" align="right" bgcolor="#CCCCCC"><strong>diskon(%)</strong></td>
    <td width="91" align="right" bgcolor="#CCCCCC"><b>Harga Bersih</b></td>  
    <td width="91" align="right" bgcolor="#CCCCCC"><b>Qty</b></td>  
    <td width="142" align="right" bgcolor="#CCCCCC"><b>Subtotal (Rp)</b></td>
  </tr>
<?php
	# Menampilkan List Item barang yang dibeli untuk Nomor Transaksi Terpilih
	$listBarangSql = "SELECT 
						barang.nm_barang, 
						barang.harga_jual, 
						barang.ppn, 
						barang.diskon, 
						penjualan_item.* 
					FROM 
						barang, 
						penjualan_item 
					  WHERE 
						barang.kd_barang			=penjualan_item.kd_barang AND 
						penjualan_item.no_penjualan	='$kodeTransaksi'
					  ORDER BY barang.kd_barang ASC";
	#var_dump($listBarangSql);exit();
	$listBarangQry = mysql_query($listBarangSql, $koneksidb)  or die ("Query list barang salah : ".mysql_error());
	$nomor  = 0;  $totalBelanja = 0;

	while ($listBarangRow = mysql_fetch_array($listBarangQry)) {
		#var_dump($listBarangRow );exit();
		$nomor++;
		# Hitung Ppn, dan Harga setelah diskon
		$hargakotor	= intval($listBarangRow['harga_jual']); 
		$besardiskon= $hargakotor * (intval($listBarangRow['diskon'])/100);
		$besarppn 	= $hargakotor  * (intval($listBarangRow['ppn'])/100);
		$hargappndis= $hargakotor  + $besarppn - $besardiskon;
		
		# Membuat Subtotal
		$subtotal  = $hargappndis * intval($listBarangRow['jumlah']); 
		# Menghitung Total Belanja keseluruhan
		$totalBelanja = $totalBelanja + intval($subtotal);
?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td align="center"><?php echo $listBarangRow['kd_barang']; ?></td>
    <td><?php echo $listBarangRow['nm_barang']; ?></td>
    <td align="center"><?php echo format_angka($listBarangRow['harga_jual']); ?></td>
    <td align="right"><?php echo $listBarangRow['ppn']." %"; ?></td>
    <td align="right"><?php echo $listBarangRow['diskon']." %"; ?></td>
    <td align="right"><?php echo format_angka($hargappndis); ?></td>
    <td align="right"><?php echo $listBarangRow['jumlah']; ?></td>
    <td align="right"><?php echo format_angka($subtotal); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="8" align="right"><b>Grand Total Belanja (Rp) : </b></td>
    <td align="right"><b><?php echo format_angka($totalBelanja); ?></b></td>
  </tr>
</table>
<p><a href="?page=Daftar-Penjualan&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Daftar Penjualan"><img src="images/back.png" width="30" height="30" /></p></a>
