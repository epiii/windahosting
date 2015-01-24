<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 20;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT * FROM bahanbaku";
$pageQry = mysql_query($pageSql, $koneksidb) or die ("error paging: ".mysql_error());
$jml	 = mysql_num_rows($pageQry);
$max	 = ceil($jml/$row);
?>
<table width="800" border="0" cellpadding="2" cellspacing="1" class="table-border">
  <tr>
    <td colspan="2" align="right"><h1 align="center"><b>DATA BAHAN BAKU</b></h1></td>
  </tr>
  <tr>
    <td colspan="2"><table width="100%" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <th width="13%" scope="col"><a href="?page=Add-Bahan"><img src="images/btn_add_data2.png" width="100" height="25" /></a></th>
        <th width="87%" scope="col"><div align="right"><a href="?page=Search-Bahan"><img src="images/btn_search1.png" width="125" height="35" /></a></div></th>
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
        <th width="333"><b>Nama Bahan Baku</b></th>
        <th width="90" align="right"><b> Harga  (Rp) </b></th>
        <th width="90" align="right"><b> Qty</b> </th>
        <th width="41" align="center"><b>Edit</b></th>
        <th width="47" align="center"><b>Delete</b></th>
      </tr>
      <?php
	$bahanbakuSql = "SELECT * FROM bahanbaku ORDER BY (SUBSTR(kd_bahan,3) + 0) ASC LIMIT $hal, $row";
	$bahanbakuQry = mysql_query($bahanbakuSql, $koneksidb)  or die ("Query bahan baku salah : ".mysql_error());
	$nomor  = 0; 
	while ($bahanbakuRow = mysql_fetch_array($bahanbakuQry)) {
	$nomor++;
	$Kode = $bahanbakuRow['kd_bahan'];
	?>
      <tr>
        <td height="24" align="center"><b><?php echo $nomor; ?></b></td>
        <td align="center"><b><?php echo $bahanbakuRow['kd_bahan']; ?></b></td>
        <td><?php echo $bahanbakuRow['nm_bahan']; ?></td>
        <td align="right"><?php echo format_angka($bahanbakuRow['harga_bahan']); ?></td>
        <td align="right"><?php echo format_angka($bahanbakuRow['jml_bahan']); ?></td>
 		<td align="center"><a href="?page=Edit-Bahan&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Edit Data"><img src="images/btn_edit.png" width="20" height="20" border="0" /></a></td>
    	 <td align="center"><a href="?page=Delete-Bahan&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Delete Data" onclick="return confirm('ANDA YAKIN AKAN MENGHAPUS DATA PENTING INI ... ?')"><img src="images/btn_delete.png" width="20" height="20"  border="0"  alt="Delete Data" /></a></td>
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
		echo " <a href='?page=Data-Bahan&hal=$list[$h]'>$h</a> ";
	}
	?>
	</td>
  </tr>
</table>
