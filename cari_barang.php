<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php"; 

if (isset($_POST['btnCari'])) { 
$search = trim($_POST['search']);
header("Location:?page=Search-Barang&barang=".$search);
} 

if (isset($_POST['refresh'])) { 
header("Location:?page=Search-Barang");
}
?>
<form action="" method="post" name="form1">
  <table width="650" border="0" cellpadding="2" cellspacing="1" class="table-list">
    <tr>
      <th colspan="4"><b>PENCARIAN DATA</b></th>
    </tr>
    <tr>
      <td width="188"><strong>Cari Nama Barang</strong></td>
      <td width="5"><strong>:</strong></td>
      <td width="438"><label>
        <input type="text" name="search" id="search" value="<?php echo $_GET['barang']?>">
      </label>        <input name="btnCari" type="submit" value="Cari" id="btnCari" /><input name="refresh" type="submit" value="Refresh"/></td>
    </tr>
  </table>

  </form> 
  <table class="table-list" width="650" border="0" cellspacing="1" cellpadding="2">
    <tr>
       <td width="50" align="center" bgcolor="#CCCCCC"><b>No</b></td>
   	   <td width="60" align="center" bgcolor="#CCCCCC"><strong>Kode </strong></td>
       <td width="224" align="center" bgcolor="#CCCCCC"><div align="left"><b>Nama Barang</b></div></td>
       <td width="90" bgcolor="#CCCCCC"><div align="right"><b>Harga Beli</b></div></td>
       <td width="90" align="right" bgcolor="#CCCCCC"><div align="right"><strong>Harga Jual</strong></div></td>
       <td width="50" align="right" bgcolor="#CCCCCC"><div align="right"><b>Qty</b></div></td>  
    </tr>
    <?php 
		$barangSql = "		SELECT * FROM barang
							WHERE nm_barang LIKE'%".$_GET['barang']."%' 
							ORDER BY (SUBSTR(kd_barang,3) + 0) ASC";
		$barangQry = mysql_query($barangSql, $koneksidb)  or die ("Query barang salah : ".mysql_error());
		$nomor  = 0;
		$nBARANG = mysql_num_rows($barangQry);
		
		if($nBARANG== 0){?>
	    <tr>
      		<th scope="col" colspan="6">Maaf Data tidak ditemukan</th>
		</tr>	
<?php 	}else{
			while ($barangRow = mysql_fetch_array($barangQry)) {
			$nomor++;
			$Kode = $barangRow['kd_barang']; 
		?>
    <tr>
      <td height="22" align="center"><b><?php echo $nomor; ?>.</b></td>
      <td align="center"><b><?php echo $barangRow['kd_barang']; ?></b></td>
      <td><?php echo $barangRow['nm_barang']; ?></td>
      <td align="right"><?php echo format_angka($barangRow['harga_beli']); ?></td>
      <td align="right"><?php echo format_angka($barangRow['harga_jual']); ?></td>
      <td align="right"><?php echo format_angka($barangRow['qty']); ?></td>
    </tr>
	<?php } } ?>
  </table>