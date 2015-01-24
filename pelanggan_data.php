<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$row = 20;
$hal = isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql = "SELECT * FROM pelanggan";
$pageQry = mysql_query($pageSql, $koneksidb) or die ("error paging: ".mysql_error());
$jml	 = mysql_num_rows($pageQry);
$max	 = ceil($jml/$row);
?>
<table width="800" border="0" cellpadding="2" cellspacing="1" class="table-border">
  <tr>
    <td colspan="2" align="right"><h1 align="center"><b>DATA PELANGGAN </b></h1></td>
  </tr>
  <tr>
    <td colspan="2"><a href="?page=Add-Pelanggan"><img src="images/btn_add_data2.png" width="100" height="25"></a></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
	<table class="table-list" width="100%" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <th width="24" align="center"><b>No</b></th>
        <th width="59" align="center">Kode</th>
        <th width="198"><b>Nama Pelanggan</b></th>
        <th width="159" align="right"><b> Alamat</b></th>
        <th width="107" align="right"><b>No. Telepon</b></th>
        <th width="110" align="center">Kode Pos</th>
        <th width="48" align="center"><b>Edit</b></th>
        <th width="48" align="center"><b>Delete</b></th>
      </tr>
      <?php
	$pelangganSql = "SELECT * FROM pelanggan ORDER BY (SUBSTR(kd_pelanggan,3) + 0) ASC LIMIT $hal, $row";
	$pelangganQry = mysql_query($pelangganSql, $koneksidb)  or die ("Query pelanggan salah : ".mysql_error());
	$nomor  = 0; 
	while ($pelangganRow = mysql_fetch_array($pelangganQry)) {
	$nomor++;
	$Kode = $pelangganRow['kd_pelanggan'];
	?>
      <tr>
        <td align="center"><b><?php echo $nomor; ?></b></td>
        <td align="center"><b><?php echo $pelangganRow['kd_pelanggan']; ?></b></td>
        <td><?php echo $pelangganRow['nm_pelanggan']; ?></td>
        <td align="right"><?php echo $pelangganRow['alamat']; ?></td>
        <td align="right"><?php echo $pelangganRow['telepon']; ?></td>
        <td align="center"><?php echo $pelangganRow['kode_pos']; ?></td>
        <td align="center"><a href="?page=Edit-Pelanggan&Kode=<?php echo $Kode; ?>"><img src="images/btn_edit.png" width="20" height="20"></a></td>
        <td align="center"><a href="?page=Delete-Pelanggan&Kode=<?php echo $Kode; ?>"><img src="images/btn_delete.png" width="20" height="20"></a></td>
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
