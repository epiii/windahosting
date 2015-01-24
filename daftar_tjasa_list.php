<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

# Baca variabel URL
$kodeTransaksi = $_GET['Kode'];

# Perintah untuk mendapatkan data dari tabel transaksi
$jualSql = "SELECT * FROM transaksi WHERE no_transaksi='$kodeTransaksi'";
$jualQry = mysql_query($jualSql, $koneksidb)  or die ("Query transaksi salah : ".mysql_error());
$jualRow = mysql_fetch_array($jualQry);

	$sPELANGGAN	= " SELECT * 
					FROM pelanggan
					WHERE kd_pelanggan='".$jualRow['kd_pelanggan']."'";
	$qPELANGGAN	= mysql_query($sPELANGGAN)or die(mysql_error());
	$rPELANGGAN	= mysql_fetch_array($qPELANGGAN);
?>
<table width="500" border="0" class="table-list">
<tr>
  <th colspan="3"><b>TRANSAKSI JASA</b></th>
</tr>
<tr>
  <td width="155"><b>No Transaksi</b></td>
  <td width="5"><b>:</b></td>
  <td width="326"><?php echo $jualRow['no_transaksi']; ?></td>
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
  <td><b>Petugas</b></td>
  <td><b>:</b></td>
  <td><?php echo $jualRow['userid']; ?></td>
</tr>
</table>
  
<h2> Daftar Jasa</h2>
<table class="table-list" width="760" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="24" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="60" align="center" bgcolor="#CCCCCC"><b>Kode</b></td>
    <td width="298" bgcolor="#CCCCCC"><b>Nama Jasa</b></td>
    <td width="298" bgcolor="#CCCCCC"><b>Harga Jasa</b></td>
    <td width="298" bgcolor="#CCCCCC"><b>ppn(%)</b></td>
    <td width="298" bgcolor="#CCCCCC"><b>diskon(%)</b></td>
    <td width="298" bgcolor="#CCCCCC"><b>Qty</b></td>
    <td width="127" align="center" bgcolor="#CCCCCC"><b>Harga Bahan(Rp)</b></td>
    <td width="110" align="right" bgcolor="#CCCCCC"><b>Subtotal (Rp)</b></td>
  </tr>
<?php
	# Menampilkan List Item jasa yang dibeli untuk Nomor Transaksi Terpilih
	/*$listJasaSql = "SELECT jasa.nama_jasa, jasa_item.* FROM jasa, jasa_item 
					  WHERE jasa.kd_jasa=jasa_item.kd_jasa AND jasa_item.no_transaksi='$kodeTransaksi'
					  ORDER BY jasa.kd_jasa ASC";*/
	$listJasaSql = "SELECT
						j.nama_jasa,
						ji.jml_bahan,
						b.harga_bahan,
						ji.jml_bahan * (b.harga_bahan) subbahan,
						j.harga_jasa,
						j.diskon,
						j.ppn,
						((ji.jml_bahan * b.harga_bahan) + j.harga_jasa) +(((ji.jml_bahan * b.harga_bahan) + j.harga_jasa) * j.ppn / 100) - (((ji.jml_bahan * b.harga_bahan) + j.harga_jasa) * j.diskon/ 100) as subtotal
						
					FROM
						jasa j,
						jasa_item ji,
						bahanbaku b
					WHERE
						j.kd_jasa = ji.kd_jasa
					AND b.kd_bahan = ji.kd_bahan
					AND ji.no_transaksi = '".$kodeTransaksi."'
					ORDER BY
						j.kd_jasa ASC";
	//var_dump($listJasaSql);exit();
	$listJasaQry = mysql_query($listJasaSql, $koneksidb)  or die ("Query list jasa salah : ".mysql_error());
	$nomor  = 0;  $totalBelanja = 0;
	while ($listJasaRow = mysql_fetch_array($listJasaQry)) {
		$nomor++;
		# Hitung Diskon, dan Harga setelah diskon
		//$besarDiskon = intval($listJasaRow['harga_jual']) * (intval($listJasaRow['diskon'])/100);
		//$hargaDiskon = intval($listJasaRow['harga_jual']) - $besarDiskon;
		
		# Membuat Subtotal
		$subtotal  = intval($listJasaRow['subtotal']); 
		# Menghitung Total Belanja keseluruhan
		$totalBelanja = $totalBelanja + intval($subtotal);
?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td align="center"><?php echo $listJasaRow['kd_jasa']; ?></td>
    <td><?php echo $listJasaRow['nama_jasa']; ?></td>
    <td align="center"><?php echo format_angka($listJasaRow['harga_jasa']); ?></td>
    <td align="center"><?php echo $listJasaRow['ppn']; ?></td>
    <td align="center"><?php echo $listJasaRow['diskon']; ?></td>
    <td align="center"><?php echo $listJasaRow['jml_bahan']; ?></td>
    <td align="center"><?php echo format_angka($listJasaRow['subbahan']); ?></td>
    <td align="right"><?php echo format_angka($listJasaRow['subtotal']); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="8" align="right"><b>Grand Total Belanja (Rp) : </b></td>
    <td align="right"><b><?php echo format_angka($totalBelanja); ?></b></td>
  </tr>
</table>
<p> <a href="?page=Daftar-Transaksi-Jasa&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Daftar Transaksi Jasa"><img src="images/back.gif" width="20" height="20" border="0" /></a></p>
