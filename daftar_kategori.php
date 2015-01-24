<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";
?>
<h2> DAFTAR KATEGORI </h2>
<table class="table-list" width="556" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="37" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="364" bgcolor="#CCCCCC"><b>Nama Kategori </b></td>
    <td width="139" align="center" bgcolor="#CCCCCC"><b>Jumlah Barang</b> </td>  
  </tr>
  <?php
	$kategoriSql = "SELECT kategori.*, 
					(SELECT COUNT(*) FROM barang WHERE barang.kd_kategori=kategori.kd_kategori) As qty_barang,
					(SELECT COUNT(*) FROM jasa WHERE jasa.kd_kategori=kategori.kd_kategori) AS qty_jasa
					FROM kategori ORDER BY kd_kategori ASC";
	$kategoriQry = mysql_query($kategoriSql, $koneksidb)  or die ("Query kategori salah : ".mysql_error());
	$nomor  = 0; 
	while ($kategoriRow = mysql_fetch_array($kategoriQry)) {
	$nomor++;
	$Kode = $kategoriRow['kd_kategori'];
  ?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td><?php echo $kategoriRow['nm_kategori']; ?></td>
    <td align="center"><?php if( $kategoriRow['qty_barang']==0){echo $kategoriRow['qty_jasa'];}else{echo $kategoriRow['qty_barang'];} ?></td>
  </tr>
  <?php } ?>
</table>