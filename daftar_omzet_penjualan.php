	<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if (isset($_POST['btnShow'])){
	$tahun	= strip_tags(trim($_POST['tahun']));
?>
<script type="text/javascript">
window.open("?page=Lap-Omzet-Penjualan&tahun=" + <?php echo $tahun?>,"_self");
</script>
<?php
}

// Fungsi Membuat Rupiah
function rupiah($data){
	$rupiah = "";
	$jml = strlen($data);

	while($jml > 3)
	{
		$rupiah = "." . substr($data,-3) . $rupiah;
		$l 		= strlen($data) - 3;
		$data 	= substr($data,0,$l);
		$jml 	= strlen($data);
	}
	$rupiah = $data . $rupiah . ",-";
	return $rupiah;
} 

?>
<h2>LAPORAN OMZET PENJUALAN</h2>
<form action="" method="post" name="form1">
  <table width="500" border="0"  class="table-list">
    <tr>
      <td colspan="3" bgcolor="#CCCCCC"><strong>PILIH TAHUN TRANSAKSI</strong></td>
    </tr>
    <tr>
      <td width="90"><strong>TAHUN </strong></td>
      <td width="5"><strong>:</strong></td>
      <td width="391">
		<select name="tahun">
			<?php
				$sql = "SELECT DISTINCT year(tgl_transaksi)as tahun from pembelian";
				$exe = mysql_query($sql);
				$jum = mysql_num_rows($exe);
				if($jum=0){
					echo "<option value=''>kosong</option>";
				}else{
					while($res=mysql_fetch_assoc($exe)){
						echo "<option value='$res[tahun]'>$res[tahun]</option>";
					}
				}
			?>
		</select>
	  <input name="btnShow" type="submit" value=" Tampilkan " /></td>
    </tr>
  </table>
</form>

<?php
# Query utama. Query ini sama dg yg dipakai Paging di atas
	if(isset($_GET['tahun']) and $_GET['tahun']!=""){
		$nomor = 0; 
?>
<table class="table-list" width="721" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="33" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="70" align="left" bgcolor="#CCCCCC"><strong>Bulan</strong></td>
    <td width="133" align="right" bgcolor="#CCCCCC"><b>Total Pembelian</b></td>
    <td width="175" align="right" bgcolor="#CCCCCC"><b>Total Penjualan</b></td>
    <td width="164" align="right" bgcolor="#CCCCCC"><strong>Total Transaksi Jasa</strong></td>
    <td width="115" align="right" bgcolor="#CCCCCC"><b>Omzet</b></td>  
  </tr>
<?php
	$thn	= date('Y');
	if($_GET['tahun']==$thn){$bln = date('m');}
	else {$bln 	= 12;}
		
	for($a=1;$a<=$bln;$a++){
		$nomor++;
		#pembelian --------------------------------------------------------------------------------------------------------
		$dataSql = "SELECT
						MONTH (tbbeli.tgl_transaksi) bulan,
						sum(tbbeli.subtotal) pembelian
					FROM
						(
							SELECT
								pbi.no_pembelian,
								pb.tgl_transaksi,
								sum(
									tbitem.harga * pbi.jumlah
								) AS subtotal
							FROM
								pembelian_item pbi,
								pembelian pb,
								(
									SELECT
										kd_bahan AS kode,
										nm_bahan AS nama,
										harga_bahan AS harga
									FROM
										bahanbaku
									UNION
										SELECT
											kd_barang AS kode,
											nm_barang AS nama,
											harga_beli AS harga
										FROM
											barang
								) tbitem
							WHERE
								pbi.kd_item= tbitem.kode
							AND pb.no_pembelian= pbi.no_pembelian
							AND YEAR (pb.tgl_transaksi) = '$_GET[tahun]'
							GROUP BY
								pb.no_pembelian
						) tbbeli
					WHERE
						MONTH (tbbeli.tgl_transaksi) = '$a' /*GROUP BY MONTH(tbjual.tgl_transaksi)*/";
		//var_dump($dataSql);exit();
		$dataQry = mysql_query($dataSql, $koneksidb) or die ("Error Query".mysql_error());
		$blnx	= array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");		
				
		while ($dataRow = mysql_fetch_array($dataQry)) { 
			$totalpembelian[$a]		= $totalpembelian[$a] + $dataRow['pembelian'];
		}		
		#end of pembelian -------------------------------------------------------------------------------------------------
		
		#penjualan -------------------------------------------------------------------------------------------------
		$dataSql = "SELECT
						MONTH (tbjual.tgl_transaksi) bulan,
						sum(tbjual.jual) penjualan
					FROM
						(
							SELECT
								pji.no_penjualan,
								pj.tgl_transaksi,
								sum(pji.jumlah * (b.harga_jual + (b.harga_jual * b.ppn / 100) - (b.harga_jual * b.diskon / 100)))as jual
								
							FROM
								penjualan_item pji,
								barang b,
								penjualan pj
							WHERE
								pji.kd_barang = b.kd_barang
								AND pji.no_penjualan = pj.no_penjualan
							GROUP BY pj.no_penjualan
							) tbjual
					WHERE
						MONTH (tbjual.tgl_transaksi) = '$a' and 
					year(tbjual.tgl_transaksi) ='$_GET[tahun]'";
		//var_dump($dataSql);exit();
		$dataQry = mysql_query($dataSql, $koneksidb) or die ("Error Query".mysql_error());
		$blnx	= array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");		
				
		while ($dataRow = mysql_fetch_array($dataQry)) { 
			$totalpenjualan[$a]		= $totalpenjualan[$a] + $dataRow['penjualan'];
		}
		#end of penjualan -------------------------------------------------------------------------------------------------
		
		#trans jasa -------------------------------------------------------------------------------------------------
		$dataSql = "SELECT
						MONTH (tbjasa.tgl_transaksi) bulan,
						sum(tbjasa.subtotal) jasa
					FROM
						(
					SELECT
						tj.no_transaksi,
						tj.tgl_transaksi,
						sum((
							(ji.jml_bahan * b.harga_bahan) + j.harga_jasa
						) + (
							(
								(ji.jml_bahan * b.harga_bahan) + j.harga_jasa
							) * j.ppn / 100
						) - (
							(
								(ji.jml_bahan * b.harga_bahan) + j.harga_jasa
							) * j.diskon / 100
						) )AS subtotal
					FROM
						jasa_item ji,
						jasa j,
						bahanbaku b,
						transaksi tj
					WHERE
						ji.kd_bahan = b.kd_bahan
					AND ji.no_transaksi = tj.no_transaksi
					AND j.kd_jasa = ji.kd_jasa
					GROUP BY
						tj.no_transaksi ) tbjasa
					WHERE
						MONTH (tbjasa.tgl_transaksi) = '$a'
					AND YEAR (tbjasa.tgl_transaksi) = '$_GET[tahun]'";
		//var_dump($dataSql);exit();
		$dataQry = mysql_query($dataSql, $koneksidb) or die ("Error Query".mysql_error());
		$blnx	= array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");		
				
		while ($dataRow = mysql_fetch_array($dataQry)) { 
			$totaltransaksi[$a]		= $totaltransaksi[$a] + $dataRow['jasa'];
		}
		#end of trans jasa-------------------------------------------------------------------------------------------------
		
		
		if($a==1){
			$bulan	= "Januari";
		}if($a==2){
			$bulan	= "Februari"; 
		}if($a==3){
			$bulan	= "Maret"; 
		}if($a==4){
			$bulan	= "April"; 
		}if($a==5){
			$bulan	= "Mei"; 
		}if($a==6){
			$bulan	= "Juni"; 
		}if($a==7){
			$bulan	= "Juli"; 
		}if($a==8){
			$bulan	= "Agustus"; 
		}if($a==9){
			$bulan	= "September"; 
		}if($a==10){
			$bulan	= "Oktober"; 
		}if($a==11){
			$bulan	= "November"; 
		}if($a==12){
			$bulan	= "Desember"; 
		}
	?>
  <tr>
    <td align="center"><?php echo $nomor; ?>.</td>
    <td align="left"><?php echo $bulan; ?></td>
    <td align="right"><?php if($totalpembelian[$a]!=""){echo rupiah($totalpembelian[$a]);}else{echo "0,-";}?></td>
    <td align="right"><?php if($totalpenjualan[$a]!=""){echo rupiah($totalpenjualan[$a]);}else{echo "0,-";} ?></td>
    <td align="right"><?php if($totaltransaksi[$a]!=""){echo rupiah($totaltransaksi[$a]);}else{echo "0,-";} ?></td>
   	<td width="115" align="right"><?php echo rupiah($totalpenjualan[$a]+$totaltransaksi[$a]-$totalpembelian[$a]); ?></td>
    
  </tr>
<?php } ?>
</table>
<?php }?>