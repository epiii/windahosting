<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php"; 

if (isset($_POST['btnCari'])) { 
$search = trim($_POST['search']);
header("Location:?page=Search-Bahan&bahan=".$search);
} 

if (isset($_POST['refresh'])) { 
header("Location:?page=Search-Bahan");
}
?>
<form action="" method="post" name="form1">
  <table width="539" border="0" cellpadding="2" cellspacing="1" class="table-list">
    <tr>
      <th colspan="3"><b>PENCARIAN DATA</b></th>
    </tr>
    <tr>
      <td width="187"><strong>Cari Nama Bahan Baku</strong></td>
      <td width="5"><strong>:</strong></td>
      <td width="331"><label>
        <input type="text" name="search" id="search" value="<?php echo $_GET['bahan']?>">
      </label>        <input name="btnCari" type="submit" value="Cari" id="btnCari" /><input name="refresh" type="submit" value="Refresh"/></td>
    </tr>
  </table>
</form>
<table class="table-list" width="538" border="0" cellspacing="1" cellpadding="2">
    <tr>
       <td width="39" align="center" bgcolor="#CCCCCC"><b>No</b></td>
   	   <td width="113" align="center" bgcolor="#CCCCCC"><strong>Kode </strong></td>
       <td width="340" align="center" bgcolor="#CCCCCC"><b>Nama Bahan Baku</b></td>
       <td width="172" bgcolor="#CCCCCC"><div align="right"><b>Harga (Rp)</b></div></td>
       <td width="68" align="right" bgcolor="#CCCCCC"><div align="right"><b>Qty</b></div></td>  
  </tr>
    <?php 
		$bahanbakuSql = "	SELECT * FROM bahanbaku 
							WHERE nm_bahan LIKE'%".$_GET['bahan']."%' 
							ORDER BY (SUBSTR(kd_bahan,3) + 0) ASC";
		$bahanbakuQry = mysql_query($bahanbakuSql, $koneksidb)  or die ("Query bahan baku salah : ".mysql_error());
		$nomor  = 0;
		$nBAHANBAKU = mysql_num_rows($bahanbakuQry);
		
		if($nBAHANBAKU== 0){?>
	    <tr>
      		<th scope="col" colspan="5">Maaf Data tidak ditemukan</th>
		</tr>	
<?php 	}else{
			while ($bahanbakuRow = mysql_fetch_array($bahanbakuQry)) {
			$nomor++;
			$Kode = $bahanbakuRow['kd_bahan']; 
		?>
    <tr>
      <td height="24" align="center"><b><?php echo $nomor; ?>.</b></td>
      <td align="center"><b><?php echo $bahanbakuRow['kd_bahan']; ?></b></td>
      <td><?php echo $bahanbakuRow['nm_bahan']; ?></td>
      <td align="right"><?php echo format_angka($bahanbakuRow['harga_bahan']); ?></td>
      <td align="right"><?php echo format_angka($bahanbakuRow['jml_bahan']); ?></td>
    </tr>
	<?php } } ?>
</table>