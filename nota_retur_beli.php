<?php
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

# Baca variabel URL
$kodeTransaksi = $_GET['noNota'];

# Perintah untuk mendapatkan data dari tabel transaksi
$jualSql = "SELECT returbeli.*, user_login.nama FROM returbeli, user_login 
			WHERE returbeli.userid=user_login.userid AND no_transaksi='$kodeTransaksi'";
$jualQry = mysql_query($jualSql, $koneksidb)  or die ("Query returbeli salah : ".mysql_error());
$jualRow = mysql_fetch_array($jualQry);
?>
<html>
<head>
<title> :: Nota Penjualan - Printshop Healthy Solution For Printer</title>
<link href="styles/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="529" border="0" cellspacing="1" cellpadding="4" class="table-print">
  <tr>
    <td width="208"><strong>
      <h3> PRINTSHOP</h3>
    </strong></td>
    <td width="217"><strong>Healthy Solution For Printer,</strong> <?php echo IndonesiaTgl($jualRow['tgl_transaksi']); ?></td>
  </tr>
  <tr>
    <td>Jl. Intan III, No 12 A, Gedangan, Sidoarjo, Jawa Timur <br>
Telpon : 0318915346 </td>
    <td valign="top"><strong>Kepada Yth.</strong> <?php echo $jualRow['supplier']; ?> .. ..... ... .. ... ... .... . .... ... ... .. .... ..... ....... ....... .... ... ... ... ... .... .... ....</td>
  </tr>
</table>
<table class="table-list" width="529" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td colspan="6"><strong>No. Retur Beli: <?php echo $jualRow['no_transaksi']; ?></strong></td>
  </tr>
  <tr>
    <td width="35" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="94" bgcolor="#CCCCCC"><div align="left"><strong>Kode Barang</strong></div></td>
    <td width="217" bgcolor="#CCCCCC"><div align="left"><b>Nama Barang</b></div></td>
    <td width="80" align="center" bgcolor="#CCCCCC"><b>Catatan</b></td> 
    <td width="77" align="right" bgcolor="#CCCCCC"><b>Qty</b></td>
  </tr>
	<?php
		# Menampilkan List Item barang yang dibeli untuk Nomor Transaksi Terpilih
		$notaSql = "SELECT barang.nm_barang, returbeli_item.* FROM barang, returbeli_item
						  WHERE barang.kd_barang=returbeli_item.kd_barang AND returbeli_item.no_transaksi='$kodeTransaksi'
						  ORDER BY barang.kd_jasa ASC";
		$notaQry = mysql_query($notaSql, $koneksidb)  or die ("Query list jasa salah : ".mysql_error());
		//$nomor  = 0;  $totalBelanja = 0;
		while ($notaRow = mysql_fetch_array($notaQry)) {
		$nomor++;
		# Hitung Diskon, dan Harga setelah diskon
		//$besarDiskon = intval($notaRow['harga_jual']) * (intval($notaRow['diskon'])/100);
		//$hargaDiskon = intval($notaRow['harga_jual']) - $besarDiskon;
		
		# Membuat Subtotal
		//$subtotal  = $notaRow['harga_jasa']; 
		# Menghitung Total Belanja keseluruhan
		//$totalBelanja = $subtotal;
	?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td><?php echo $notaRow['kd_barang']; ?></td>
    <td><?php echo $notaRow['nm_barang']; ?></td>
    <td align="center"><?php echo format_angka($notaRow['catatan']); ?></td>
    <td align="right"><?php echo format_angka($notaRow)['qty']; ?></td>
  </tr>
  <?php } ?>
</table>
<br/>
<table class="table-print" width="529" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="140" align="center">Tanda terima,<br /><br /><br /> 
    ( ............................ ) </td>
    <td width="204">&nbsp;</td>
    <td width="140" align="center">Hormat kami,<br /><br /><br /> 
	(  <?php echo $jualRow['nama']; ?> ) </td>
  </tr>
</table>
</body>
