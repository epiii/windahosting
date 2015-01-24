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
$pageSql = "SELECT * FROM barang WHERE kd_kategori='$kdKategori'";
$pageQry = mysql_query($pageSql, $koneksidb) or die ("error paging: ".mysql_error());
$jml	 = mysql_num_rows($pageQry);
$max	 = ceil($jml/$row);

// Query ke tabel
$kategoriSQL = "SELECT nm_kategori FROM kategori WHERE kd_kategori='$kdKategori'";
$kategoriQry = mysql_query($kategoriSQL, $koneksidb) or die ("Error : ".mysql_error());
$kategoriRow = mysql_fetch_array($kategoriQry);
?>
<h2> Daftar Barang Kategori : <?php echo $kategoriRow['nm_kategori']; ?></h2>
<table class="table-list" width="800" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="23" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="49" align="center" bgcolor="#CCCCCC"><b>Kode</b></td>
    <td width="294" bgcolor="#CCCCCC"><b>Nama Barang </b></td>
    <td width="41" align="center" bgcolor="#CCCCCC"><b>Qty</b></td>
    <td width="107" align="right" bgcolor="#CCCCCC"><b>Harga Beli  (Rp)</b></td>  
    <td width="106" align="right" bgcolor="#CCCCCC"><b>Harga Jual (Rp)</b></td>
    <td width="78" align="right" bgcolor="#CCCCCC"><b>Laba (Rp)</b></td>
  </tr>
<?php
	# Query utama, menampilkan data dari tabel barang per kategori terpilih
	$barangSql = "SELECT * FROM barang WHERE kd_kategori='$kdKategori' ORDER BY kd_barang ASC LIMIT $hal, $row";
	$barangQry = mysql_query($barangSql, $koneksidb)  or die ("Query barang salah : ".mysql_error());
	$nomor  = 0; 
	while ($barangRow = mysql_fetch_array($barangQry)) {
		$nomor++;
		//$besarDiskon = intval($barangRow['harga_jual']) * (intval($barangRow['diskon'])/100); // Mencari besarnya diskon
		//$hargaDiskon = intval($barangRow['harga_jual']) - $besarDiskon; // Hitung harga jual sudah dikurangi diskon
		$labaBersih  = intval ($barangRow['harga_jual']) - intval($barangRow['harga_beli']);
?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td align="center"><?php echo $barangRow['kd_barang']; ?></td>
    <td><?php echo $barangRow['nm_barang']; ?></td>
    <td align="center"><?php echo $barangRow['qty']; ?></td>
    <td align="right"><?php echo format_angka($barangRow['harga_beli']); ?></td>
    <td align="right"><?php echo format_angka($barangRow['harga_jual']); ?></td>
    <td align="right"><?php echo format_angka($labaBersih); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="3"><b>Jumlah Data :</b> <?php echo $jml; ?> </td>
    <td colspan="5" align="right"><b>Halaman ke :</b>
	<?php
	for ($h = 1; $h <= $max; $h++) {
		$list[$h] = $row * $h - $row;
		echo " <a href='?page=Daftar-Barang-Kategori-Show&hal=$list[$h]&kdKategori=$kdKategori'>$h</a>";
	}
	?></td>
  </tr>
</table>
<p><a href="?page=Daftar-Barang-Kategori&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Daftar Barang Kategori"><img src="images/back.png" width="30" height="30" /></p></a>
