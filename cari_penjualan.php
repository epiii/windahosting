<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if (isset($_POST['btnCari'])) { 
$search = trim($_POST['search']);

echo "<script>window.location='?page=Search-Penjualan&penjualan=".$search."'</script>";
#header("Location:?page=Search-Penjualan&penjualan=".$search);
} 

if (isset($_POST['refresh'])) { 
header("Location:?page=Search-Penjualan");
}
?>
<h2> PENCARIAN TRANSAKSI PENJUALAN</h2>
<form action="" method="post" name="form1">
  <table width="599" border="0" cellpadding="2" cellspacing="1" class="table-list">
    <tr>
      <th colspan="3"><b>PENCARIAN DATA</b></th>
    </tr>
    <tr>
      <td width="230"><strong>Cari Daftar Penjualan</strong></td>
      <td width="7"><strong>:</strong></td>
      <td width="346"><label>
        <input type="text" name="search" id="search" value="<?php echo $_GET['penjualan']?>">
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
		/*$penjualanSql = "	SELECT penjualan.*, supplier.nm_supplier FROM penjualan, supplier 
							WHERE penjualan.kd_supplier=supplier.kd_supplier LIKE'%".$_GET['penjualan']."%' 
							ORDER BY penjualan.no_penjualan ASC";*/		
		$penjualanSql = "	SELECT
								p.*, s.nm_pelanggan
							FROM
								penjualan p,
								pelanggan s
							WHERE
								p.kd_pelanggan = s.kd_pelanggan and 
								s.nm_pelanggan LIKE '%".$_GET['penjualan']."%'
							ORDER BY
								p.no_penjualan ASC";
		#var_dump($penjualanSql);exit();
		$penjualanQry = mysql_query($penjualanSql, $koneksidb)  or die ("Query penjualan salah : ".mysql_error());
		$nomor  = 0;
		$nPENJUALAN= mysql_num_rows($penjualanQry);
		
		if($nPENJUALAN== 0){?>
  <tr>
    <th scope="col" colspan="5">Maaf Data tidak ditemukan</th>
  </tr>
  <?php 	}else{
			while ($penjualanRow = mysql_fetch_array($penjualanQry)) {
			$nomor++;
			$Kode = $penjualanRow['no_penjualan']; 
		?>
  <tr>
    <td align="center"><?php echo $nomor; ?>.</td>
    <td><?php echo IndonesiaTgl($penjualanRow['tgl_transaksi']); ?></td>
    <td><?php echo $penjualanRow['no_penjualan']; ?></td>
    <td><?php echo $penjualanRow['nm_pelanggan']; ?></td>
    <td><?php echo $penjualanRow['userid']; ?></td>
    <td align="center"><a href="nota_penjualan.php?noNota=<?php echo $penjualanRow['no_penjualan']; ?>" target="_self" alt="Daftar Penjualan"><img src="images/Cetak.jpg" width="25" height="25" /></td>
    <td align="center"><a href="?page=Daftar-Penjualan-List&amp;Kode=<?php echo $Kode; ?>"><img src="images/btn_view.png" width="20" height="20" /></a></td>
  </tr>
  <?php } } ?>
</table>
<p><a href="?page=Laporan-Data" target="_self" alt="Laporan Data"></p>
