<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";
?>
<h2> DAFTAR PETUGAS (USER) </h2>
<table class="table-list" width="450" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="27" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="228" bgcolor="#CCCCCC"><b>Nama Petugas </b></td>
    <td width="97" bgcolor="#CCCCCC"><b>User Id </b> </td>  
    <td width="77" bgcolor="#CCCCCC"><strong>Level</strong></td>
  </tr>
	<?php
	$petugasSql = "SELECT * FROM user_login ORDER BY id ASC";
	$petugasQry = mysql_query($petugasSql, $koneksidb)  or die ("Query petugas salah : ".mysql_error());
	$nomor  = 0; 
	while ($petugasRow = mysql_fetch_array($petugasQry)) {
	$nomor++;
	?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td><?php echo $petugasRow['nama']; ?></td>
    <td><?php echo $petugasRow['userid']; ?></td>
    <td><?php echo $petugasRow['level']; ?></td>
  </tr>
  <?php } ?>
</table>
