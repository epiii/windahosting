<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if (isset($_POST['btnCari'])) { 
$search = trim($_POST['search']);

echo "<script>window.location='?page=Search-Jasa&jasa=".$search."'</script>";
#header("Location:?page=Search-Jasa&jasa=".$search);
} 

if (isset($_POST['refresh'])) { 
header("Location:?page=Search-Jasa");
}
?>
<h2> PENCARIAN TRANSAKSI JASA</h2>
<form action="" method="post" name="form1">
  <table width="599" border="0" cellpadding="2" cellspacing="1" class="table-list">
    <tr>
      <th colspan="3"><b>PENCARIAN DATA</b></th>
    </tr>
    <tr>
      <td width="230"><strong>Cari Daftar Jasa</strong></td>
      <td width="7"><strong>:</strong></td>
      <td width="346"><label>
        <input type="text" name="search" id="search" value="<?php echo $_GET['jasa']?>">
      </label>        <input name="btnCari" type="submit" value="Cari" id="btnCari" /><input name="refresh" type="submit" value="Refresh"/></td>
    </tr>
  </table>
</form>
<table class="table-list" width="600" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="30" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="64" bgcolor="#CCCCCC"><b>Tanggal</b></td>
    <td width="100" bgcolor="#CCCCCC"><b>Nomor Transaksi Jasa </b></td>
    <td width="140" bgcolor="#CCCCCC"><b>Pelanggan</b></td>
    <td width="71" bgcolor="#CCCCCC"><b>Petugas</b></td>
    <td width="51" align="center" bgcolor="#CCCCCC"><strong>Nota</strong></td>
    <td width="45" align="center" bgcolor="#CCCCCC"><b>View</b></td>
  </tr>
  <?php 
		/*$jasaSql = "	SELECT jasa.*, supplier.nm_supplier FROM jasa, supplier 
							WHERE jasa.kd_supplier=supplier.kd_supplier LIKE'%".$_GET['jasa']."%' 
							ORDER BY jasa.no_jasa ASC";*/		
		$jasaSql = "	SELECT
								p.*, s.nm_pelanggan
							FROM
								transaksi p,
								pelanggan s
							WHERE
								p.kd_pelanggan = s.kd_pelanggan and 
								s.nm_pelanggan LIKE '%".$_GET['jasa']."%'
							ORDER BY
								p.no_transaksi ASC";
		#var_dump($jasaSql);exit();
		$jasaQry = mysql_query($jasaSql, $koneksidb)  or die ("Query jasa salah : ".mysql_error());
		$nomor  = 0;
		$nTRANSAKSI = mysql_num_rows($jasaQry);
		
		if($nTRANSAKSI== 0){?>
  <tr>
    <th scope="col" colspan="5">Maaf Data tidak ditemukan</th>
  </tr>
  <?php 	}else{
			while ($jasaRow = mysql_fetch_array($jasaQry)) {
			$nomor++;
			$Kode = $jasaRow['no_jasa']; 
		?>
  <tr>
    <td align="center"><?php echo $nomor; ?>.</td>
    <td><?php echo IndonesiaTgl($jasaRow['tgl_transaksi']); ?></td>
    <td><?php echo $jasaRow['no_transaksi']; ?></td>
    <td><?php echo $jasaRow['nm_pelanggan']; ?></td>
    <td><?php echo $jasaRow['userid']; ?></td>
    <td align="center"><a href="nota_jasa.php?noNota=<?php echo $jasaRow['no_transaksi']; ?>" target="_self" alt="Daftar Jasa"><img src="images/Cetak.jpg" width="25" height="25" /></td>
    <td align="center"><a href="?page=Daftar-Jasa-List&amp;Kode=<?php echo $Kode; ?>"><img src="images/btn_view.png" width="20" height="20" /></a></td>
  </tr>
  <?php } } ?>
</table>
<p><a href="?page=Laporan-Data" target="_self" alt="Laporan Data"></p>
