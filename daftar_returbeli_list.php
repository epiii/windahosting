<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

# Baca variabel URL
$kodeTransaksi = $_GET['Kode'];

# Perintah untuk mendapatkan data dari tabel Pembelian
$beliSql = "SELECT returbeli.*, supplier.nm_supplier FROM returbeli, supplier 
			WHERE returbeli.kd_supplier=supplier.kd_supplier 
			AND returbeli.no_returbeli='$kodeTransaksi'";
$beliQry = mysql_query($beliSql, $koneksidb)  or die ("Query returbeli salah : ".mysql_error());
$beliRow = mysql_fetch_array($beliQry);
?>
<table width="500" border="0" cellpadding="2" cellspacing="1" class="table-list">
<tr>
  <th colspan="3"><b>TRANSAKSI RETUR PEMBELIAN </b></th>
</tr>
<tr>
  <td width="155"><b>No Retur Beli</b></td>
  <td width="5"><b>:</b></td>
  <td width="326"> <?php echo $beliRow['no_returbeli']; ?> </td>
</tr>
<tr>
  <td><b>Tanggal</b></td>
  <td><b>:</b></td>
  <td><?php echo IndonesiaTgl($beliRow['tgl_transaksi']); ?></td>
</tr>
<tr>
  <td><b>Supplier</b></td>
  <td><b>:</b></td>
  <td><?php echo $beliRow['nm_supplier']; ?></td>
</tr>
<tr>
  <td><b>Petugas</b></td>
  <td><b>:</b></td>
  <td><?php echo $beliRow['userid']; ?></td>
</tr>
</table>
  
<h2> Daftar Retur Pembelian</h2>
<table class="table-list" width="575" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="27" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="95" align="center" bgcolor="#CCCCCC"><b>Kode</b></td>
    <td width="240" bgcolor="#CCCCCC"><b>Nama Barang </b></td>
    <td width="123" align="center" bgcolor="#CCCCCC"><b>Catatan</b></td>
    <td width="64" align="right" bgcolor="#CCCCCC"><b>Qty</b></td>
  </tr>
  <?php
	# Menampilkan List Item barang yang dibeli untuk Nomor Transaksi Terpilih
	$listBarangSql = "SELECT barang.nm_barang, returbeli_item.* FROM barang, returbeli_item 
					  WHERE barang.kd_barang=returbeli_item.kd_barang AND returbeli_item.no_returbeli='$kodeTransaksi'
					  ORDER BY barang.kd_barang ASC";
	$listBarangQry = mysql_query($listBarangSql, $koneksidb)  or die ("Query list barang salah : ".mysql_error());
	$nomor  = 0; $totalBelanja = 0;
	while ($listBarangRow = mysql_fetch_array($listBarangQry)) {
	$nomor++;
	# Membuat Subtotal
	#$subtotal  = intval($listBarangRow['catatan']) * intval($listBarangRow['qty']);  
	
	# Menghitung Total Belanja keseluruhan
	#$totalBelanja = $totalBelanja + intval($subtotal);
?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td align="center"><?php echo $listBarangRow['kd_barang']; ?></td>
    <td><?php echo $listBarangRow['nm_barang']; ?></td>
    <td align="center"><?php echo $listBarangRow['catatan']; ?></td>
    <td align="right"><?php echo $listBarangRow['jumlah']; ?></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="5" align="right">&nbsp;</td>
  </tr>
</table>
<p><a href="?page=Daftar-Retur-Pembelian&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Daftar Retur Pembelian"><img src="images/back.png" width="30" height="30" /></p></a>
</form>

