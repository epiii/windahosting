
<?php
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

# Baca variabel URL
$kodeTransaksi = $_GET['noNota'];

# Perintah untuk mendapatkan data dari tabel penjualan
$jualSql = " SELECT penjualan.*, user_login.nama 
			 FROM penjualan, user_login 
			 WHERE penjualan.userid=user_login.userid AND no_penjualan='$kodeTransaksi'";
$jualQry = mysql_query($jualSql, $koneksidb)  or die ("Query penjualan salah : ".mysql_error());
$jualRow = mysql_fetch_array($jualQry);

$sPELANGGAN = "SELECT nm_pelanggan, alamat FROM pelanggan where kd_pelanggan='$jualRow[kd_pelanggan]'";
$qPELANGGAN = mysql_query($sPELANGGAN, $koneksidb)  or die ("Query penjualan salah : ".mysql_error());
$rPELANGGAN	= mysql_fetch_array($qPELANGGAN);
?>
<html>
<head>
<title> :: Nota Penjualan - Printshop Healthy Solution For Printer</title>
<link href="styles/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="569" border="0" cellpadding="4" cellspacing="1" class="table-print">
  <tr>
    <td width="416" height="36"><h3><a href="index.php?page=Penjualan-Barang">PRINTSHOP</a></h3>
      <table width="100%" border="0" cellspacing="1" cellpadding="2">
        <tr>
          <th height="20" scope="col"><div align="left">
            <pre>N.P.W.P :24875.3683-618.000</pre>
          </div></th>
        </tr>
      </table>
      <h6>Healthy Solution For Printer       
        <br>
        Jl. Intan III, No 12 A, Gedangan, Sidoarjo, Jawa Timur<br>Telpon : 0318915346 
        <strong>    </strong>      </h6>
        </h3>
    </h6></td>
    <td width="133"><h5>&nbsp;</h5>
    <h5>&nbsp;</h5></td>
  </tr>
  <tr>
    <td><h5>&nbsp;</h5></td>
    <td valign="top"><h5><strong>Sidoarjo, </strong><strong><?php echo IndonesiaTgl($jualRow['tgl_transaksi']); ?></strong></h5></td>
  </tr>
  <tr>
    <td height="44"><h5><strong>Kepada Yth.</strong> <?php echo $rPELANGGAN['nm_pelanggan']; ?><br>
    <strong>Alamat : </strong><?php echo $rPELANGGAN['alamat']; ?></h5></td>
    <td valign="top"><h5>&nbsp;</h5></td>
  </tr>
</table>
<table class="table-list" width="570" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td colspan="6"><strong>No Nota : <?php echo $jualRow['no_penjualan']; ?></strong></td>
  </tr>
  <tr>
    <td width="35" align="center" bgcolor="#CCCCCC"><b>Qty</b></td>
    <td width="141" bgcolor="#CCCCCC"><b>Nama Barang </b></td>
    <td width="86" align="center" bgcolor="#CCCCCC"><b>Harga</b></td>
    <td width="41" align="right" bgcolor="#CCCCCC"><b>Ppn</b></td>
    <td width="47" align="right" bgcolor="#CCCCCC"><strong>Diskon</strong></td>  
    <td width="100" align="right" bgcolor="#CCCCCC"><strong>Harga</strong></td>  
    <td width="84" align="right" bgcolor="#CCCCCC"><b>Subtotal</b></td>
  </tr>
	<?php
		# Menampilkan List Item barang yang dibeli untuk Nomor Transaksi Terpilih
		/*$notaSql = "SELECT barang.nm_barang, barang.ppn, barang.diskon, penjualan_item.* FROM barang, penjualan_item
						  WHERE barang.kd_barang=penjualan_item.kd_barang AND penjualan_item.no_penjualan='$kodeTransaksi'
						  ORDER BY barang.kd_barang ASC";*/
		$notaSql = "SELECT
						*
					FROM
						barang b,
						penjualan_item pi
					WHERE
						b.kd_barang = pi.kd_barang
					AND pi.no_penjualan = '".$kodeTransaksi."'
					ORDER BY
						b.kd_barang";
		#var_dump($notaSql);exit();
		$notaQry = mysql_query($notaSql, $koneksidb)  or die ("Query list barang salah : ".mysql_error());
		#var_dump($notaQry);exit();
		$nomor  = 0;  $totalBelanja = 0;
		while ($notaRow = mysql_fetch_array($notaQry)) {
			#var_dump($notaRow);exit();
			$nomor++;
			$harga		= $notaRow['harga_jual'];
			#var_dump($harga);exit();
			$ppn		= $harga * ($notaRow['ppn'] / 100);
			#var_dump($ppn);exit();
			$diskon		= $harga * ($notaRow['diskon']/100);
			#var_dump($diskon);exit();
			$hrgBersih	= $harga + $ppn - $diskon;
			$subtotal 	= $hrgBersih * $notaRow['jumlah'] ;
			$total 		= $total + $subtotal;
			$qtyBrg 	= $qtyBrg + $notaRow['qty'];
			
			/*$besarPpn = ((intval($barangRow['harga_jual']) * intval($barangRow['ppn']))/100) ;
			$hargaPpn = (intval($barangRow['harga_jual']) + $besarPpn) ;
			$besarDiskon = ((intval($hargaPpn) * intval($barangRow['diskon']))/100);
			$hargaPpnDiskon = $hargaPpn - $besarDiskon;
			$subtotal  = $hargaPpnDiskon * intval($notaRow['jumlah']); 
			$totalBelanja = $totalBelanja + intval($subtotal);*/
	?>
  <tr>
    <td align="center"><?php echo $notaRow['jumlah']; ?></td>
    <td><?php echo $notaRow['kd_barang'].": ".$notaRow['nm_barang']; ?></td>
    <td align="center"><?php echo format_angka($notaRow['harga_jual']); ?></td>
    <td align="right"><?php echo $notaRow['ppn']." %"; ?></td>
    <td align="right"><?php echo $notaRow['diskon']." %"; ?></td>
    <td align="right"><?php echo format_angka($hrgBersih); ?></td>
    <td align="right"><?php echo format_angka($subtotal); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="6" align="right"><b> Total Belanja (Rp) : </b></td>
    <td align="right" bgcolor="#CCFFFF"><b><?php echo format_angka($total); ?></b></td>
  </tr>
</table>
<table width="55%" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <th scope="col">&nbsp;</th>
  </tr>
  <tr>
    <th scope="col"><div align="left">
      <pre>Nota ini adalah SAH, jika sudah dibubuhi stampel Toko</pre>
    </div></th>
  </tr>
  <tr>
    <td><pre><strong>NB : Harga Sudah Termasuk Ppn</strong> dan Diskon</pre></td>
  </tr>
</table>
<p><br/>
</p>
<table class="table-print" width="569" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="140" align="center">Tanda terima,<br /><br /><br /> 
    ( ............................ ) </td>
    <td width="204">&nbsp;</td>
    <td width="140" align="center">Hormat kami,<br /><br /><br /> 
	(  <?php echo $jualRow['nama']; ?> ) </td>
  </tr>
</table>
</body>
	