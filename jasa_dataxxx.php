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


<table width="700" border="0" cellspacing="1" cellpadding="3">
  <tr>
    <td colspan="2"><h1 align="center"><strong>DATA JASA</strong></h1></td>
  </tr>
  <tr>
    <td colspan="2"><table width="100%" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <th scope="col"><div align="left"><a href="?page=Add-Jasa"><img src="images/btn_add_data2.png" width="100" height="25" /></a></div></th>
        <th scope="col"><a href="?page=Search-Jasa"><img src="images/btn_search1.png" width="125" height="35" align="right" /></a></th>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><table class="table-list" width="100%" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <th width="5%" align="center" scope="col">No</th>
        <th width="12%" align="center" scope="col">Kode</th>
        <th width="42%" scope="col">Nama Jasa</th>
        <th width="17%" align="right" scope="col">Harga</th>
        <th width="12%" align="center" scope="col">Edit</th>
        <th width="12%" align="center" scope="col">Delete</th>
      </tr>
      <?php
	$jasaSql = "SELECT * FROM jasa ORDER BY (SUBSTR(kd_jasa,3) + 0) ASC LIMIT $hal, $row";
	$jasaQry = mysql_query($jasaSql, $koneksidb)  or die ("Query jasa salah : ".mysql_error());
	$nomor  = 0; 
	while ($jasaRow = mysql_fetch_array($jasaQry)) {
	$nomor++;
	$Kode = $jasaRow['kd_jasa'];
	?>
      <tr>
        <td align="center"><b><?php echo $nomor; ?></b></td>
        <td align="center"><b><?php echo $jasaRow['kd_jasa']; ?></b></td>
        <td><?php echo $jasaRow['nama_jasa']; ?></td>
        <td align="right"><?php echo format_angka($jasaRow['harga_jasa']); ?></td>
        <td align="center"><a href="?page=Edit-Jasa&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Edit Data"><img src="images/btn_edit.png" width="20" height="20" border="0" /></a></td>
        <td align="center"><a href="?page=Delete-Jasa&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Delete Data" onclick="return confirm('ANDA YAKIN AKAN MENGHAPUS DATA PENTING INI ... ?')"><img src="images/btn_delete.png" width="20" height="20"  border="0"  alt="Delete Data" /></a></td>
      </tr>
      <?php } ?>
    </table></td>
  </tr>
  <tr>
    <td width="343">Jumlah Data : <?php echo $jml; ?></td>
    <td width="342"><div align="right">Halaman ke :
        <?php 
	for ($h = 1; $h <= $max; $h++) {
		$list[$h] = $row * $h - $row;
		echo "<a href='?page=Data-Jasa&hal=$list[$h]'>$h</a>";
	}
	?>
    </div></td>
  </tr>
</table>
