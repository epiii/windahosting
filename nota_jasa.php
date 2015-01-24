<?php
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

# Baca variabel URL
$kodeTransaksi = $_GET['noNota'];

# Perintah untuk mendapatkan data dari tabel transaksi
$jasaSql = " SELECT transaksi.*, user_login.nama 
			 FROM transaksi, user_login 
			 WHERE transaksi.userid=user_login.userid AND no_transaksi='$kodeTransaksi'";
$jasaQry = mysql_query($jasaSql, $koneksidb)  or die ("Query transaksi salah : ".mysql_error());
$jasaRow = mysql_fetch_array($jasaQry);

$sPELANGGAN = "SELECT nm_pelanggan, alamat FROM pelanggan where kd_pelanggan='$jasaRow[kd_pelanggan]'";
$qPELANGGAN = mysql_query($sPELANGGAN, $koneksidb)  or die ("Query transaksi salah : ".mysql_error());
$rPELANGGAN	= mysql_fetch_array($qPELANGGAN);
?>
<html>
<head>
<title> :: Nota Transaksi Jasa - Printshop Healthy Solution For Printer</title>
<link href="styles/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="568" border="0" cellspacing="1" cellpadding="4" class="table-print">
  <tr>
    <td width="416" height="104"><h3><a href="index.php?page=Transaksi-Jasa">PRINTSHOP</a></h3>
      <table width="100%" border="0" cellspacing="1" cellpadding="2">
        <tr>
          <th scope="col"><div align="left">
            <pre>N.P.W.P :24875.3683-618.000</pre>
          </div></th>
        </tr>
      </table>
      <h6>Healthy Solution For Printer
        <br>
      	Jl. Intan III, No 12 A, Gedangan, Sidoarjo, Jawa Timur <br>
    Telpon : 0318915346 </h6></td>
    <td width="133"><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td valign="top"><h5><strong>Sidoarjo, </strong><strong><?php echo IndonesiaTgl($jasaRow['tgl_transaksi']); ?></strong></h5></td>
  </tr>
  <tr>
    <td height="27"><h5><strong>Kepada Yth.</strong><?php echo $rPELANGGAN['nm_pelanggan']; ?><br>
    <strong>Alamat :</strong> <?php echo $rPELANGGAN['alamat']; ?></h5></td>
    <td valign="top">&nbsp;</td>
  </tr>
</table>
<table class="table-list" width="570" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td colspan="6"><strong>No Nota : <?php echo $jasaRow['no_transaksi']; ?></strong></td>
  </tr>
  <tr>
    <td width="38" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="274" bgcolor="#CCCCCC"><b>Kode Jasa/Bahan</b></td>
    <td width="274" bgcolor="#CCCCCC"><b>Nama Jasa/Bahan</b></td>
    <td width="108" align="right" bgcolor="#CCCCCC"><strong>Qty</strong></td>  
    <!--<td width="77" align="center" bgcolor="#CCCCCC"><b>@Harga Bahan</b></td>-->
    <td width="77" align="center" bgcolor="#CCCCCC"><b>Harga Bahan</b></td>
    <td width="77" align="center" bgcolor="#CCCCCC"><b>Harga Jasa</b></td>
    <td width="77" align="center" bgcolor="#CCCCCC"><b>ppn %</b></td>
    <td width="77" align="center" bgcolor="#CCCCCC"><b>diskon %</b></td>
    <td width="108" align="right" bgcolor="#CCCCCC"><b>Subtotal</b></td>
  </tr>
	<?php
		# Menampilkan List Item jasa yang dibeli untuk Nomor Transaksi Terpilih
		/*$notaSql = "SELECT jasa.nama_jasa,jasa.harga_jasa as harga, jasa_item.* FROM jasa, jasa_item
						  WHERE jasa.kd_jasa=jasa_item.kd_jasa AND jasa_item.no_transaksi='$kodeTransaksi'
						  ORDER BY jasa.kd_jasa ASC";*/
		$notaSql = "SELECT
						j.ppn,
						j.diskon,
						j.kd_jasa,
						j.nama_jasa,
						j.harga_jasa,
						b.kd_bahan,
						b.harga_bahan,
						b.nm_bahan,
						ji.jml_bahan
					FROM
						jasa j,
						jasa_item ji,
						bahanbaku b
					WHERE
						j.kd_jasa = ji.kd_jasa
					AND ji.no_transaksi = '".$kodeTransaksi."'
					AND b.kd_bahan = j.kd_bahan
					ORDER BY
						j.kd_jasa ASC";
		#var_dump($notaSql);exit();
		$notaQry = mysql_query($notaSql, $koneksidb)  or die ("Query list jasa salah : ".mysql_error());
		$nomor  = 0;  $totalBelanja = 0;
		while ($notaRow = mysql_fetch_array($notaQry)) {
			$nomor++;
			//var_dump($notaRow);exit();
			# Hitung ppn, dan Harga setelah ppn
			#$besarppn = intval($notaRow['harga_beli']) * (intval($notaRow['ppn'])/100);
			#$hargappn = intval($notaRow['harga_jasa']) + $besarppn;
			
			#var_dump($notaRow['harga_jasa']);exit();
			#var_dump($notaRow['jml_bahan']);exit();
			# Membuat Subtotal
			//$harga_kotor = $notaRow[''];
			$harga_kotor	= ($notaRow['jml_bahan'] * $notaRow['harga_bahan'])+$notaRow['harga_jasa']; 
			#var_dump($harga_kotor);exit();
			$ppn_angka		= $notaRow['ppn'] * $harga_kotor /100 ;
			#var_dump($ppn_angka);exit();
			$diskon_angka	= $notaRow['diskon'] * $harga_kotor / 100;
			#var_dump($diskon_angka);exit();
			$sub_total		= $harga_kotor + $ppn_angka - $diskon_angka;
			//var_dump($harga_kotor);exit();
			# Menghitung Total Belanja keseluruhan
			$totalBelanja = $totalBelanja + $sub_total;
	?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td><?php echo $notaRow['kd_jasa']."/".$notaRow['kd_bahan']; ?></td>
    <td><?php echo $notaRow['nama_jasa']."/".$notaRow['nm_bahan']; ?></td>
    <td align="center"><?php echo $notaRow['jml_bahan']; ?></td>
    <!--<td align="center"><?php //echo format_angka($notaRow['harga_bahan']); ?></td>-->
    <td align="center"><?php echo format_angka($notaRow['jml_bahan'] * $notaRow['harga_bahan']); ?></td>
    <td align="center"><?php echo format_angka($notaRow['harga_jasa']); ?></td>
    <td align="right"><?php echo $notaRow['ppn']; ?></td>
    <td align="right"><?php echo $notaRow['diskon']; ?></td>
    <td align="right"><?php echo format_angka($sub_total); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="8" align="right"><b> Total Belanja (Rp) : </b></td>
    <td align="right" bgcolor="#CCFFFF"><b><?php echo format_angka($totalBelanja); ?></b></td>
  </tr>
</table>
<table width="55%" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <th scope="col">&nbsp;</th>
  </tr>
  <tr>
    <td><pre>Nota ini adalah SAH, jika sudah dibubuhi stampel Toko</pre></td>
  </tr>
  <tr>
    <td><pre><strong>NB : Harga Sudah Termasuk Ppn</strong> dan Diskon</pre></td>
  </tr>
</table>
<p>&nbsp;</p>
<table class="table-print" width="570" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="140" align="center">Tanda terima,<br /><br /><br /> 
    ( ............................ ) </td>
    <td width="204">&nbsp;</td>
    <td width="140" align="center">Hormat kami,<br /><br /><br /> 
	(  <?php echo $jasaRow['nama']; ?> ) </td>
  </tr>
</table>
</body>
