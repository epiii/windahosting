<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if (isset($_POST['btnCari'])) { 
$search = trim($_POST['search']);

echo "<script>window.location='?page=Search-Pembelian&pembelian=".$search."'</script>";
#header("Location:?page=Search-Pembelian&pembelian=".$search);
} 

if (isset($_POST['refresh'])) { 
header("Location:?page=Search-Pembelian");
}
?>
<h2> PENCARIAN TRANSAKSI PEMBELIAN</h2>
<form action="" method="post" name="form1">
  <table width="539" border="0" cellpadding="2" cellspacing="1" class="table-list">
    <tr>
      <th colspan="3"><b>PENCARIAN DATA</b></th>
    </tr>
    <tr>
      <td width="187"><strong>Cari Daftar Pembelian</strong></td>
      <td width="5"><strong>:</strong></td>
      <td width="331"><label>
        <input type="text" name="search" id="search" value="<?php echo $_GET['pembelian']?>">
      </label>        <input name="btnCari" type="submit" value="Cari" id="btnCari" /><input name="refresh" type="submit" value="Refresh"/></td>
    </tr>
  </table>
</form>
<table class="table-list" width="600" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="23" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="66" bgcolor="#CCCCCC"><b>Tanggal</b></td>
    <td width="84" bgcolor="#CCCCCC"><b>Nomor Beli </b> </td>  
    <td width="180" bgcolor="#CCCCCC"><b>Supplier </b></td>
    <td width="92" bgcolor="#CCCCCC"><b>Petugas</b></td>
    <td width="44" align="center" bgcolor="#CCCCCC"><b>View</b></td>
  </tr>
  <?php 
		/*$pembelianSql = "	SELECT pembelian.*, supplier.nm_supplier FROM pembelian, supplier 
							WHERE pembelian.kd_supplier=supplier.kd_supplier LIKE'%".$_GET['pembelian']."%' 
							ORDER BY pembelian.no_pembelian ASC";*/		
		$pembelianSql = "	SELECT
								p.*, s.nm_supplier
							FROM
								pembelian p,
								supplier s
							WHERE
								p.kd_supplier = s.kd_supplier and 
								s.nm_supplier LIKE '%".$_GET['pembelian']."%'
							ORDER BY
								p.no_pembelian ASC";
		#var_dump($pembelianSql);exit();
		$pembelianQry = mysql_query($pembelianSql, $koneksidb)  or die ("Query pembelian salah : ".mysql_error());
		$nomor  = 0;
		$nPEMBELIAN = mysql_num_rows($pembelianQry);
		
		if($nPEMBELIAN== 0){?>
	    <tr>
      		<th scope="col" colspan="5">Maaf Data tidak ditemukan</th>
		</tr>	
<?php 	}else{
			while ($pembelianRow = mysql_fetch_array($pembelianQry)) {
			$nomor++;
			$Kode = $pembelianRow['no_pembelian']; 
		?>
  <tr>
    <td align="center"><?php echo $nomor; ?>.</td>
    <td><?php echo IndonesiaTgl($pembelianRow['tgl_transaksi']); ?></td>
    <td><?php echo $pembelianRow['no_pembelian']; ?></td>
    <td><?php echo $pembelianRow['nm_supplier']; ?></td>
    <td><?php echo $pembelianRow['userid']; ?></td>
    <td align="center"><a href="?page=Daftar-Pembelian-List&amp;Kode=<?php echo $Kode; ?>"><img src="images/btn_view.png" width="20" height="20"></a></td>

  </tr>
  <?php } } ?>
</table>
<p><a href="?page=Laporan-Data" target="_self" alt="Laporan Data"></p>
