<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 20;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT * FROM supplier";
$pageQry = mysql_query($pageSql, $koneksidb) or die ("error paging: ".mysql_error());
$jml	 = mysql_num_rows($pageQry);
$max	 = ceil($jml/$row);
?>
<table width="700" border="0" cellpadding="2" cellspacing="1" class="table-border">
  <tr>
    <td colspan="2" align="right"><h1><b>DATA SUPPLIER </b></h1></td>
  </tr>
  <tr>
    <td colspan="2"><a href="?page=Add-Supplier" target="_self"><img src="images/btn_add_data2.png" height="25" border="0" /></a></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
	<table class="table-list" width="100%" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <th width="26" align="center"><b>No</b></th>
        <th width="224"><b>Nama Supplier </b></th>
        <th width="326"><b>Alamat</b></th>
        <th width="43" align="center"><b>Edit</b></th>
        <th width="49" align="center"><b>Delete</b></th>
      </tr>
      <?php
	$dataSql = "SELECT * FROM supplier ORDER BY kd_supplier ASC LIMIT $hal, $row";
	$dataQry = mysql_query($dataSql, $koneksidb)  or die ("Query supplier salah : ".mysql_error());
	$nomor  = 0; 
	while ($dataRow = mysql_fetch_array($dataQry)) {
	$nomor++;
	$Kode = $dataRow['kd_supplier'];
	?>
      <tr>
        <td align="center"><b><?php echo $nomor; ?></b></td>
        <td><?php echo $dataRow['nm_supplier']; ?></td>
        <td><?php echo $dataRow['alamat']; ?></td>
        <td align="center"><a href="?page=Edit-Supplier&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Edit Data"><img src="images/btn_edit.png" width="20" height="20" border="0" /></a></td>
        <td align="center"><a href="?page=Delete-Supplier&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Delete Data" onclick="return confirm('ANDA YAKIN AKAN MENGHAPUS DATA PENTING INI ... ?')"><img src="images/btn_delete.png" width="20" height="20"  border="0"  alt="Delete Data" /></a></td>
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
		echo " <a href='?page=Data-Supplier&hal=$list[$h]'>$h</a> ";
	}
	?>
	</td>
  </tr>
</table>
