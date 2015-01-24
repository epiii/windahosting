<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

# Baca variabel URL
$kodeTransaksi = $_GET['Kode'];

# Perintah untuk mendapatkan data dari tabel Pembelian
$beliSql = "SELECT pembelian.*, supplier.nm_supplier FROM pembelian, supplier 
			WHERE pembelian.kd_supplier=supplier.kd_supplier 
			AND pembelian.no_pembelian='$kodeTransaksi'";
$beliQry = mysql_query($beliSql, $koneksidb)  or die ("Query pembelian salah : ".mysql_error());
$beliRow = mysql_fetch_array($beliQry);
?>
<table width="578" border="0" cellpadding="2" cellspacing="1" class="table-list">
<tr>
  <th colspan="3"><b>TRANSAKSI PEMBELIAN </b></th>
</tr>
<tr>
  <td width="201"><b>No Pembelian </b></td>
  <td width="7"><b>:</b></td>
  <td width="354"> <?php echo $beliRow['no_pembelian']; ?> </td>
</tr>
<tr>
  <td><b>Tanggal Pembelian</b></td>
  <td><b>:</b></td>
  <td><?php echo IndonesiaTgl($beliRow['tgl_transaksi']); ?></td>
</tr>
<tr>
  <td><strong>Catatan Pembayaran</strong></td>
  <td><strong>:</strong></td>
  <td><?php echo $beliRow['catatan']; ?></td>
  </tr>
    <script type="text/javascript">
    	function disabletempo(nilai){ 
			if (nilai=="Lunas" || nilai=="lunas" || nilai=="LUNAS"){
				document.getElementById("cmbTempo").disabled= true;  
				document.getElementById("cmbTempo").value= "00-00-0000"; 
			}
		}
    </script> 
</tr>
<tr>
  <td><strong>Jatuh Tempo Pembayaran</strong></td>
  <td><b>:</b></td>
  <td><span id="jatuh"><?php echo IndonesiaTgl($beliRow['jatuh_tempo']); ?></span></td>
</tr>
<tr>
  <td><b>Supplier</b></td>
  <td><b>:</b></td>
  <td><?php echo $beliRow['nm_supplier']; ?></td>
</tr>
<tr>
  <td><b>Petugas</b></td>
  <td><b>:</b></td>
  <td><?php echo $beliRow['userid']; ?></td>
</tr>
</table>
  
<h2> Daftar Barang</h2>
<table class="table-list" width="707" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="26" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="64" align="center" bgcolor="#CCCCCC"><b>Kode</b></td>
    <td width="254" bgcolor="#CCCCCC"><b>Nama Barang </b></td>
    <td width="124" align="right" bgcolor="#CCCCCC"><b>Harga Beli  (Rp)</b></td>
    <td width="98" align="right" bgcolor="#CCCCCC"><b>Jumlah</b></td>
    <td width="110" align="right" bgcolor="#CCCCCC"><b>Subtotal (Rp)</b></td>
  </tr>
<?php
	# Menampilkan List Item barang yang dibeli untuk Nomor Transaksi Terpilih
	/*$listBarangSql = "	SELECT 	* 
						FROM	pembelian_item 
						WHERE no_pembelian='".$kodeTransaksi."'";*/
			$listBarangSql="SELECT
								pb.tgl_transaksi,
								pbi.no_pembelian,
								pbi.kd_item,
								pbi.jumlah,
								tbitem.kode,
								tbitem.harga,
								tbitem.nama 
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
								pbi.no_pembelian = pb.no_pembelian
							AND pbi.no_pembelian = '".$kodeTransaksi."'
							AND pbi.kd_item = tbitem.kode";
							
	//var_dump($listBarangSql);exit();
	$listBarangQry = mysql_query($listBarangSql, $koneksidb)  or die ("Query list barang salah : ".mysql_error());
	$nomor  = 0; $totalBelanja = 0;
	while ($listBarangRow = mysql_fetch_array($listBarangQry)) {
		//var_dump($listBarangRow );exit();
	
		$nomor++;
		# Membuat Subtotal
		$subtotal  = intval($listBarangRow['harga']) * intval($listBarangRow['jumlah']);  
		//$subtotal	= $listBarangRow['subtotal'];
		# Menghitung Total Belanja keseluruhan
		$totalBelanja = $totalBelanja + intval($subtotal);
		
?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td align="center"><?php echo $listBarangRow['kode']; ?></td>
    <td><?php echo $listBarangRow['nama']; ?></td>
    <td align="right"><?php echo format_angka($listBarangRow['harga']); ?></td>
    <td align="right"><?php echo $listBarangRow['jumlah']; ?></td>
    <td align="right"><?php echo format_angka($subtotal); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="5" align="right"><b>Grand Total Belanja (Rp) : </b></td>
    <td align="right"><b><?php echo format_angka($totalBelanja); ?></b></td>
  </tr>
</table>
<p><a href="?page=Daftar-Pembelian&amp;Kode=<?php echo $Kode; ?>" target="_self" alt="Daftar Pembelian"><img src="images/back.png" width="30" height="30" /></p></a>
