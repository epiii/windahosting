<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 20;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT * FROM jasa";
$pageQry = mysql_query($pageSql, $koneksidb) or die ("error paging: ".mysql_error());
$jml	 = mysql_num_rows($pageQry);
$max	 = ceil($jml/$row);
?>
<h2> DAFTAR JASA</h2>
<table class="table-list" width="592" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="40" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="54" align="center" bgcolor="#CCCCCC"><b>Kode</b></td>
    <td width="319" align="center" bgcolor="#CCCCCC"><b>Nama Jasa</b></td>
    <td width="158" align="right" bgcolor="#CCCCCC"><b>Harga   (Rp)</b></td>
  </tr>
<?php
	$jasaSql = "SELECT * FROM jasa ORDER BY kd_jasa ASC LIMIT $hal, $row";
	$jasaQry = mysql_query($jasaSql, $koneksidb)  or die ("Query jasa salah : ".mysql_error());
	$nomor  = 0; 
	while ($jasaRow = mysql_fetch_array($jasaQry)) {
	$nomor++;
	//$besarDiskon = intval($jasaRow['harga_jasa']) * (intval($jasaRow['diskon'])/100); // Mencari besarnya diskon
	//$hargaDiskon = intval($jasaRow['harga_jasa']) - $besarDiskon; // Hitung harga jual sudah dikurangi diskon
	//$labaBersih  = $hargaDiskon - intval($jasaRow['harga_jasa']); // Mendapatkan nilai Laba Bersih
?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td align="center"><?php echo $jasaRow['kd_jasa']; ?></td>
    <td><?php echo $jasaRow['nama_jasa']; ?></td>
    <td align="right"><?php echo format_angka($jasaRow['harga_jasa']); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="3"><b>Jumlah Data :</b> <?php echo $jml; ?> </td>
    <td colspan="5" align="right"><b>Halaman ke :</b>
	<?php
	for ($h = 1; $h <= $max; $h++) {
		$list[$h] = $row * $h - $row;
		echo " <a href='?page=Daftar-Jasa&hal=$list[$h]'>$h</a> ";
	}
	?></td>
  </tr>
</table>
