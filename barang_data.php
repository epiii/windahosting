<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 20;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT * FROM barang";
$pageQry = mysql_query($pageSql, $koneksidb) or die ("error paging: ".mysql_error());
$jml	 = mysql_num_rows($pageQry);
$max	 = ceil($jml/$row);
?>
<table width="800" border="0" cellpadding="2" cellspacing="1" class="table-border">
  <tr>
    <td colspan="2" align="right"><h1 align="center"><b>DATA BARANG </b></h1></td>
  </tr>
  <tr>
    <td colspan="2"><table width="100%" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <th width="16%" scope="col"><a href="?page=Add-Barang"><img src="images/btn_add_data2.png" height="25" border="0" /></a></th>
        <th width="84%" scope="col"><div align="right"><a href="?page=Search-Barang"><img src="images/btn_search1.png" width="125" height="35" /></a></div></th>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
	<table class="table-list" width="100%" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <th width="23" align="center"><b>No</b></th>
        <th width="57" align="center">Kode</th>
        <th width="333"><b>Nama Barang </b></th>
        <th width="90" align="right"><div align="center">Gambar </div></th>
        <th width="90" align="right">Qty</th>
        <th width="90" align="right"><b> Beli  (Rp) </b></th>
        <th width="90" align="right"><b> Jual (Rp)</b> </th>
        <th width="41" align="center"><b>Edit</b></th>
        <th width="47" align="center"><b>Delete</b></th>
      </tr>
      <?php
	$barangSql = "SELECT * FROM barang ORDER BY (SUBSTR(kd_barang,3) + 0) ASC LIMIT $hal, $row";
	$barangQry = mysql_query($barangSql, $koneksidb)  or die ("Query barang salah : ".mysql_error());
	$nomor  = 0; 
	while ($barangRow = mysql_fetch_array($barangQry)) {
	$nomor++;
	$Kode = $barangRow['kd_barang'];
	?>
      <tr>
        <td height="24" align="center"><b><?php echo $nomor; ?>.</b></td>
        <td align="center"><b><?php echo $barangRow['kd_barang']; ?></b></td>
        <td><?php echo $barangRow['nm_barang']; ?></td>
        <td align="center"><img src="<?php echo $barangRow['link_gambar']; ?>" width="20" height="20" /></td>
        <td align="right"><?php echo format_angka($barangRow['qty']); ?></td>
        <td align="right"><?php echo format_angka($barangRow['harga_beli']); ?></td>
        <td align="right"><?php echo format_angka($barangRow['harga_jual']); ?></td>
        <td align="center"><a href="?page=Edit-Barang&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Edit Data"><img src="images/btn_edit.png" width="20" height="20" border="0" /></a></td>
        <td align="center"><a href="?page=Delete-Barang&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Delete Data" onclick="return confirm('ANDA YAKIN AKAN MENGHAPUS DATA PENTING INI ... ?')"><img src="images/btn_delete.png" width="20" height="20"  border="0"  alt="Delete Data" /></a></td>
      </tr>
      <?php } ?>
    </table></td>
  </tr>
  <tr>
    <td><b>Jumlah Data :</b> <?php echo $jml; ?> </td>
    <td align="right"><b>Halaman ke :</b> 
	<?php
	for ($h = 1; $h <= $max; $h++) {
		$list[$h] = $row * $h - $row;
		echo " <a href='?page=Data-Barang&hal=$list[$h]'>$h</a> ";
	}
	?>
	</td>
  </tr>
</table>
