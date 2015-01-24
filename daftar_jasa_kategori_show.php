<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if($_GET) {
	// Baca variabel Kategori
	$kdKategori = isset($_POST['cmbKategori']) ? $_POST['cmbKategori'] : $_GET['kdKategori'];
}

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 20;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT * FROM jasa WHERE kd_kategori='$kdKategori'";
$pageQry = mysql_query($pageSql, $koneksidb) or die ("error paging: ".mysql_error());
$jml	 = mysql_num_rows($pageQry);
$max	 = ceil($jml/$row);

// Query ke tabel
$kategoriSQL = "SELECT nm_kategori FROM kategori WHERE kd_kategori='$kdKategori'";
$kategoriQry = mysql_query($kategoriSQL, $koneksidb) or die ("Error : ".mysql_error());
$kategoriRow = mysql_fetch_array($kategoriQry);
?>
<h2> Daftar Jasa Kategori : <?php echo $kategoriRow['nm_kategori']; ?></h2>
<table class="table-list" width="637" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="36" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="102" align="center" bgcolor="#CCCCCC"><b>Kode</b></td>
    <td width="226" bgcolor="#CCCCCC"><div align="center"><b>Nama Jasa</b></div></td>
    <td width="162" align="right" bgcolor="#CCCCCC"><b>Harga Jasa (Rp)</b></td>
  </tr>
<?php
	# Query utama, menampilkan data dari tabel jasa per kategori terpilih
	$jasaSql = "SELECT * FROM jasa WHERE kd_kategori='$kdKategori' ORDER BY kd_jasa ASC LIMIT $hal, $row";
	$jasaQry = mysql_query($jasaSql, $koneksidb)  or die ("Query jasa salah : ".mysql_error());
	$nomor  = 0; 
	while ($jasaRow = mysql_fetch_array($jasaQry)) {
		$nomor++;
		//$besarDiskon = intval($jasaRow['harga_jasa']) * (intval($jasaRow['diskon'])/100); // Mencari besarnya diskon
		//$hargaDiskon = intval($jasaRow['harga_jasa']) - $besarDiskon; // Hitung harga jual sudah dikurangi diskon
		//$labaBersih  = $hargaDiskon - intval($jasaRow['harga_jasa']);
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
		echo " <a href='?page=Daftar-Jasa-Kategori-Show&hal=$list[$h]&kdKategori=$kdKategori'>$h</a>";
	}
	?></td>
  </tr>
</table>
<p><a href="?page=Daftar-Jasa-Kategori&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Daftar Jasa Kategori"><img src="images/back.png" width="30" height="30" /></p></a>
