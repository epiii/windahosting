<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if (isset($_POST['btnCari'])) { 
$search = trim($_POST['search']);

echo "<script>window.location='?page=Search-Stok&stok=".$search."'</script>";
#header("Location:?page=Search-Stok&stok=".$search);
} 

if (isset($_POST['refresh'])) { 
header("Location:?page=Search-Stok");
}
?>
<h2> PENCARIAN TRANSAKSI PENJUALAN</h2>
<form action="" method="post" name="form1">
  <table width="599" border="0" cellpadding="2" cellspacing="1" class="table-list">
    <tr>
      <th colspan="3"><b>PENCARIAN DATA</b></th>
    </tr>
    <tr>
      <td width="230"><strong>Cari Daftar Stok</strong></td>
      <td width="7"><strong>:</strong></td>
      <td width="346"><label>
        <input type="text" name="search" id="search" value="<?php echo $_GET['stok']?>">
      </label>        <input name="btnCari" type="submit" value="Cari" id="btnCari" /><input name="refresh" type="submit" value="Refresh"/></td>
    </tr>
  </table>
</form>
<table class="table-list" width="600" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="30" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="64" bgcolor="#CCCCCC"><b>Tanggal</b></td>
    <td width="100" bgcolor="#CCCCCC"><b>Nomor Jual </b></td>
    <td width="140" bgcolor="#CCCCCC"><b>Pelanggan</b></td>
    <td width="71" bgcolor="#CCCCCC"><b>Petugas</b></td>
    <td width="51" align="center" bgcolor="#CCCCCC"><strong>Nota</strong></td>
    <td width="45" align="center" bgcolor="#CCCCCC"><b>View</b></td>
  </tr>
  <?php 
		/*$stokSql = "	SELECT stok.*, supplier.nm_supplier FROM stok, supplier 
							WHERE stok.kd_supplier=supplier.kd_supplier LIKE'%".$_GET['stok']."%' 
							ORDER BY stok.no_stok ASC";*/		
		$stokSql = "	SELECT
								p.*, s.nm_pelanggan
							FROM
								stok p,
								pelanggan s
							WHERE
								p.kd_pelanggan = s.kd_pelanggan and 
								s.nm_pelanggan LIKE '%".$_GET['stok']."%'
							ORDER BY
								p.no_stok ASC";
		#var_dump($stokSql);exit();
		$stokQry = mysql_query($stokSql, $koneksidb)  or die ("Query stok salah : ".mysql_error());
		$nomor  = 0;
		$nPEMBELIAN = mysql_num_rows($stokQry);
		
		if($nPEMBELIAN== 0){?>
  <tr>
    <th scope="col" colspan="5">Maaf Data tidak ditemukan</th>
  </tr>
  <?php 	}else{
			while ($stokRow = mysql_fetch_array($stokQry)) {
			$nomor++;
			$Kode = $stokRow['no_stok']; 
		?>
  <tr>
    <td align="center"><?php echo $nomor; ?>.</td>
    <td><?php echo IndonesiaTgl($stokRow['tgl_transaksi']); ?></td>
    <td><?php echo $stokRow['no_stok']; ?></td>
    <td><?php echo $stokRow['nm_pelanggan']; ?></td>
    <td><?php echo $stokRow['userid']; ?></td>
    <td align="center"><a href="nota_stok.php?noNota=<?php echo $stokRow['no_stok']; ?>" target="_self" alt="Daftar Stok"><img src="images/Cetak.jpg" width="25" height="25" /></td>
    <td align="center"><a href="?page=Daftar-Stok-List&amp;Kode=<?php echo $Kode; ?>"><img src="images/btn_view.png" width="20" height="20" /></a></td>
  </tr>
  <?php } } ?>
</table>
<p><a href="?page=Laporan-Data" target="_self" alt="Laporan Data"></p>
