<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";
?>
<h2> DAFTAR SUPPLIER </h2>
<table class="table-list" width="600" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="29" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="173" bgcolor="#CCCCCC"><b>Nama Supplier </b></td>
    <td width="282" bgcolor="#CCCCCC"><b>Alamat Lengkap </b> </td>  
    <td width="95" bgcolor="#CCCCCC"><strong>No Telpon </strong></td>
  </tr>
<?php
	$supplierSql = "SELECT * FROM supplier ORDER BY kd_supplier ASC";
	$supplierQry = mysql_query($supplierSql, $koneksidb)  or die ("Query supplier salah : ".mysql_error());
	$nomor  = 0; 
	while ($supplierRow = mysql_fetch_array($supplierQry)) {
	$nomor++;
?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td><?php echo $supplierRow['nm_supplier']; ?></td>
    <td><?php echo $supplierRow['alamat']; ?></td>
    <td><?php echo $supplierRow['telepon']; ?></td>
  </tr>
  <?php } ?>
</table>
