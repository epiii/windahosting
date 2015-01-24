<link rel="stylesheet" type="text/css" media="screen" href="styles/smoothness/jquery-ui-1.10.1.custom.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="styles/smoothness/jquery.ui.combogrid.css"/>

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.ui.combogrid-1.6.3.js"></script>
<script >
	function cmbCatatan(event){
		var d = new Date();
		var tg	= d.getDate();
		var bl	= d.getMonth()+1;
		var th	= d.getFullYear();
		if(bl.length=1){
			bl='0'+bl;
		}
		if($(this).val()=='lunas'){
			$('#cmbTempo').val('0000-00-00');
			$('#cmbTempo').attr('disabled',true);
		}else{
			$('#cmbTempo').val(tg+'-'+bl+'-'+th);
			$('#cmbTempo').attr('disabled',false);
		}
	}
	$(document).ready(function(){
		$('#cmbCatatan').on('blur change',cmbCatatan);
	});
</script>

<script>
jQuery(document).ready(function(){
	$("#txtBarang").on('keyup', function(e){
		var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		var keyCode = $.ui.keyCode;
		if(key != keyCode.ENTER && key != keyCode.LEFT && key != keyCode.RIGHT && key != keyCode.DOWN) {
			$('#txtKode').val('');
		}
	});
	$( "#txtBarang" ).combogrid({
		debug:true,
		colModel: [{
				'columnName':'kd_barang',
				'hide':true,
				'width':'10',
				'label':'kode'
			}, {
				'columnName':'nm_barang',
				'width':'45',
				'label':'nama'
			},{
				'columnName':'harga_jual',
				'width':'45',
				'label':'harga Jual'
			}],
		url: 'pAjax.php?aksi=autoCom&menu=transJual',
		select: function( event, ui ) {
			$( "#txtBarang" ).val( ui.item.nm_barang );
			$( "#txtKode" ).val( ui.item.kd_barang);
			return false;
		}
	});
});
$(document).ready(function(){
	$('#txtKode').on('focus',function(){
		$('#txtBarang').focus();

		$('#infoKodeP').html('<span style="color:red;">kode terisi otomatis (setelah anda pilih barang)</span>').fadeIn();
		setTimeout(function(){
			$('#infoKodeP').fadeOut();
		},3000);
	});
});
</script>

<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if($_GET) {
	# HAPUS DAFTAR barang DI TMP
	if(isset($_GET['Act'])){
		if(trim($_GET['Act'])=="Delete"){
			# Hapus Tmp jika datanya sudah dipindah
			mysql_query("DELETE FROM tmp_penjualan WHERE id='".$_GET['ID']."' AND userid='".$_SESSION['SES_LOGIN']."'", $koneksidb) 
				or die ("Gagal kosongkan tmp".mysql_error());
		}
		if(trim($_GET['Act'])=="Sucsses"){
			echo "<b>DATA BERHASIL DISIMPAN</b> <br><br>";
		}
	}
}
	// =========================================================================
	
	if($_POST) {
	# TOMBOL PILIH (KODE barang) DIKLIK
	if(isset($_POST['btnPilih'])){
		$message = array();
		if (trim($_POST['txtKode'])=="") {
			$message[] = "<b>Kode Barang belum diisi</b>, ketik secara manual atau dari barcode reader !";		
		}
		if (trim($_POST['txtJumlah'])=="" OR ! is_numeric(trim($_POST['txtJumlah']))) {
			$message[] = "Data <b>Jumlah barang (Qty) belum diisi</b>, silahkan <b>isi dengan angka</b> !";		
		}
		
		# Baca variabel
		$txtKode	= $_POST['txtKode'];
		$txtKode	= str_replace("'","&acute;",$txtKode);
		$txtJumlah	= $_POST['txtJumlah'];
		$txtJumlah	= str_replace("'","&acute;",$txtJumlah);
		
		# Jika jumlah error message tidak ada
		if(count($message)==0){			
			$barangSql ="SELECT * FROM barang WHERE kd_barang='$txtKode'";
			$barangQry = mysql_query($barangSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
			$barangRow = mysql_fetch_array($barangQry);
			$barangQty = mysql_num_rows($barangQry);
			if ($barangQty >= 1) {
				
				# Hitung Diskon,ppn dan Harga setelah diskon dan ppn
				$besarPpn = ((intval($barangRow['harga_jual']) * intval($barangRow['ppn']))/100) ;
				$hargaPpn = (intval($barangRow['harga_jual']) + $besarPpn) ;
				$besarDiskon = ((intval($hargaPpn) * intval($barangRow['diskon']))/100);
				$hargaPpnDiskon = $hargaPpn - $besarDiskon;
				
				$tmpSql = "INSERT INTO tmp_penjualan SET 
							kd_barang='$barangRow[kd_barang]', 
							/*harga_jual='$hargaPpnDiskon', */
							qty='$txtJumlah', 
							userid='".$_SESSION['SES_LOGIN']."'";
				mysql_query($tmpSql, $koneksidb) or die ("Gagal Query detail barang : ".mysql_error());
				$txtJumlah	= "";
			}
			else {
				$message[] = "Tidak ada barang dengan kode <b>$txtKode'</b>, silahkan ganti";
			}
		}
	}
	}
	// ============================================================================
	
	# JIKA TOMBOL SIMPAN DIKLIK
	if(isset($_POST['btnSave'])){
		$message = array();
		if (trim($_POST['cmbTanggal'])=="") {
			$message[] = "Tanggal transaksi belum diisi, pilih pada combo !";		
		}
		#if (trim($_POST['cmbTempo'])=="") {
			#$message[] = "Tanggal jatuh tempo belum diisi, pilih pada combo !";
		#}
		$tmpSql ="SELECT COUNT(*) As qty FROM tmp_penjualan WHERE userid='".$_SESSION['SES_LOGIN']."'";
		$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
		$tmpRow = mysql_fetch_array($tmpQry);
		if ($tmpRow['qty'] < 1) {
			$message[] = "<b>Item Barang</b> belum ada yang dimasukan, <b>minimal 1 barang</b>.";
		}
		
		# Baca variabel
		$txtKode= $_POST['txtKode'];
		$cmbPelanggan= $_POST['cmbPelanggan'];
		$cmbPelanggan= str_replace("'","&acute;",$cmbPelanggan);
		$txtCatatan	= $_POST['txtCatatan'];
		$txtCatatan = str_replace("'","&acute;",$txtCatatan);
		$cmbTanggal =$_POST['cmbTanggal'];
		$cmbTempo	= $_POST['cmbTempo'];
				
		# Jika jumlah error message tidak ada
		if(count($message)==0){			
			$kodeBaru	= buatKode("penjualan", "JL");
			$qrySave=mysql_query("	INSERT INTO penjualan 
								 	SET no_penjualan='$kodeBaru', tgl_transaksi='".InggrisTgl($_POST['cmbTanggal'])."',
									kd_pelanggan='$cmbPelanggan', catatan='$txtCatatan', 
									userid='".$_SESSION['SES_LOGIN']."'") or die ("Gagal query".mysql_error());
			if($qrySave){
				# Ambil semua data barang yang dipilih, berdasarkan kasir yg login
				$tmpSql ="SELECT * FROM tmp_penjualan WHERE userid='".$_SESSION['SES_LOGIN']."'";
				$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				while ($tmpRow = mysql_fetch_array($tmpQry)) {
					// Masukkan semua barang yang udah diisi ke tabel penjualan detail
					$itemSql = "INSERT INTO penjualan_item SET 
											no_penjualan='$kodeBaru', 
											kd_barang='$tmpRow[kd_barang]', 
											jumlah='$tmpRow[qty]'";

		  			mysql_query($itemSql, $koneksidb) or die ("Gagal Query Simpan detail barang".mysql_error());
					
					// Update qty
					$barangSql = "UPDATE barang SET qty=qty - $tmpRow[qty] WHERE kd_barang='$tmpRow[kd_barang]'";
		  			mysql_query($barangSql, $koneksidb) or die ("Gagal Query Edit Qty".mysql_error());
				}
				# Kosongkan Tmp jika datanya sudah dipindah
				mysql_query("DELETE FROM tmp_penjualan WHERE userid='".$_SESSION['SES_LOGIN']."'", $koneksidb) or die ("Gagal kosongkan tmp".mysql_error());
				
				// Refresh form
				echo "<meta http-equiv='refresh' content='0; url=nota_penjualan.php?noNota=$kodeBaru'>";
			}
			else{
				$message[] = "Gagal penyimpanan ke database";
			}
		}	
	}  
	// ============================================================================

	# JIKA ADA PESAN ERROR DARI VALIDASI
	// (Form Kosong, atau Duplikat ada), Ditampilkan lewat kode ini
	if (! count($message)==0 ){
		echo "<div class='mssgBox'>";
		echo "<img src='images/attention.png' class='imgBox'> <hr>";
			$Num=0;
			foreach ($message as $indeks=>$pesan_tampil) { 
			$Num++;
				echo "&nbsp;&nbsp;$Num. $pesan_tampil<br>";	
			} 
		echo "</div> <br>"; 
	}
	// ============================================================================



# TAMPILKAN DATA KE FORM
$nomorTransaksi = buatKode("penjualan", "JL");
$tglTransaksi 	= isset($_POST['cmbTanggal']) ? $_POST['cmbTanggal'] : date('d-m-Y');
$jatuhtempo 	= isset($_POST['cmbTempo']) ? $_POST['cmbTempo'] : date('d-m-Y');
$dataPelanggan	= isset($_POST['txtPelanggan']) ? $_POST['txtPelanggan'] : '';
$dataCatatan	= isset($_POST['txtCatatan']) ? $_POST['txtCatatan'] : '';
?>
<form action="" method="post"  name="frmadd">
  <table width="962" cellspacing="1" class="table-common" style="margin-top:0px;">
    <tr>
      <td colspan="3" align="right"><h1 align="center">TRANSAKSI PENJUALAN BARANG</h1></td>
    </tr>
    <tr>
      <td width="20%"><b>No Penjualan </b></td>
      <td width="1%"><b>:</b></td>
      <td width="79%"><input name="txtNomor" value="<?php echo $nomorTransaksi; ?>" size="9" maxlength="9" readonly="readonly"/></td>
    </tr>
    <tr>
      <td><b>Tanggal Penjualan </b></td>
      <td><b>:</b></td>
      <td><?php echo form_tanggal("cmbTanggal",$tglTransaksi); ?></td>
    </tr>
    <tr>
      <td><b>Pelanggan</b></td>
      <td><b>:</b></td>
      <td><select required id="cmbPelanggan"name="cmbPelanggan">
        <option value="">pilih pelanggan ...</option>
        <?php
	  $dataSql = "SELECT * FROM pelanggan ORDER BY kd_pelanggan";
	  $dataQry = mysql_query($dataSql, $koneksidb) or die ("Gagal Query".mysql_error());
	  while ($dataRow = mysql_fetch_array($dataQry)) {
	  	if ($dataRow['kd_pelanggan']== $_POST['cmbPelanggan']) {
			$cek = " selected";
		} else { $cek=""; }
	  	echo "<option value='$dataRow[kd_pelanggan]' $cek>$dataRow[nm_pelanggan]</option>";
	  }
	  $sqlData ="";
	  ?>
      </select></td>
    </tr>
    <tr>
      <td><b>Catatan</b></td>
      <td><b>:</b></td>
      <td>
		<select required id="txtCatatan"name="txtCatatan">
			<option value="">pilih catatan ..</option>
			<option value="lunas">Lunas</option>
			<option value="hutang">Hutang</option>
		</select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><b>Kode Barang</b></td>
      <td><b>:</b></td>
      <td><b>
         <input size="4" name="txtKode"id="txtKode" placeholder="kode" readonly/>
		 <input id="txtBarang" name="txtBarang" placeholder="cari barang berdasarkan nama" class="angkaC" size="30" maxlength="50" />
Qty :
<input class="angkaC" name="txtJumlah" size="2" maxlength="4" value="1" 
	  		 onblur="if (value == '') {value = '1'}" 
      		 onfocus="if (value == '1') {value =''}"/>
<input name="btnPilih" type="submit" style="cursor:pointer;" value=" Pilih " />
      </b></td>
    </tr>
	<tr>
      <td>&nbsp;</td>
      <td colspan="2" id="infoKodeTD"><p id="infoKodeP"></p></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><label>
       <input name="btnSave" type="submit" style="cursor:pointer;" value=" SIMPAN " />
      </label></td>
    </tr>
  </table>
	<table class="table-list" width="962" border="0" cellspacing="1" cellpadding="2">
		<tr>
			<th colspan="10">DAFTAR  ITEM BARANG</th>
		</tr>
		<tr>
			<td width="25" align="center" bgcolor="#CCCCCC"><b>No</b></td>
			<td width="66" align="center" bgcolor="#CCCCCC"><b>Kode</b></td>
			<td width="281" bgcolor="#CCCCCC"><b>Nama Barang </b></td>
			<td width="109" align="right" bgcolor="#CCCCCC"><div align="center"><b>Harga</b></div></td>
			<td width="61" align="center" bgcolor="#CCCCCC"><strong>Ppn (%)</strong> </td>
			<td width="68" align="center" bgcolor="#CCCCCC"><strong>Disc (%)</strong></td>
			<td width="123" align="center" bgcolor="#CCCCCC"><strong>Harga Ppn+Disc</strong></td>
			<td width="46" align="center" bgcolor="#CCCCCC"><b>Qty</b></td>
			<td width="71" align="right" bgcolor="#CCCCCC"><b>Subtotal</b></td>
			<td width="61" align="center" bgcolor="#FFCC00"><b>Delete</b></td>
		</tr>
  <?php
/*$tmpSql ="SELECT barang.*, tmp_penjualan.id, tmp_penjualan.harga_jual As harga_ppndis, tmp_penjualan.qty 
		FROM barang, tmp_penjualan
		WHERE barang.kd_barang=tmp_penjualan.kd_barang AND tmp_penjualan.userid='".$_SESSION['SES_LOGIN']."'
		ORDER BY barang.kd_barang ";*/
$tmpSql ="SELECT
			barang.*, tmp_penjualan.id,
			tmp_penjualan.qty
		FROM
			barang,
			tmp_penjualan
		WHERE
			barang.kd_barang = tmp_penjualan.kd_barang
		AND tmp_penjualan.userid = '".$_SESSION['SES_LOGIN']."'
		ORDER BY
			barang.kd_barang";
//var_dump($tmpSql);exit();
$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
$total = 0; $qtyBrg = 0; $nomor=0;
while($tmpRow = mysql_fetch_array($tmpQry)) {
	$ID			= $tmpRow['id'];
	$harga		= $tmpRow['harga_jual'];
	//var_dump($harga);exit();
	$ppn		= $harga * ($tmpRow['ppn'] / 100);
	#var_dump($ppn);exit();
	$diskon		= $harga * ($tmpRow['diskon']/100);
	#var_dump($diskon);exit();
	$hrgBersih	= $harga + $ppn - $diskon;
	#var_dump($hrgBersih);exit();
	$subtotal 	= $hrgBersih * $tmpRow['qty'] ;
	$total 		= $total + $subtotal;
	$qtyBrg 	= $qtyBrg + $tmpRow['qty'];
	//$subPenjar	= ($ressub[penjar]!=0)?$ressub[penjar]:0;
	$nomor++;
?>
  <tr>
    <td align="center"><b><?php echo $nomor; ?></b></td>
    <td align="center"><b><?php echo $tmpRow['kd_barang']; ?></b></td>
    <td><?php echo $tmpRow['nm_barang']; ?></td>
    <td align="right"><?php echo format_angka($harga); ?></td>
    <td align="center"><?php echo $tmpRow['ppn']; ?></td>
    <td align="center"><?php echo $tmpRow['diskon']; ?></td>
    <td align="center"><?php echo format_angka($hrgBersih); ?></td>
    <td align="center"><?php echo $tmpRow['qty']; ?></td>
    <td align="right"><?php echo format_angka($subtotal); ?></td>
    <td align="center" bgcolor="#FFFFCC"><a href="?page=Penjualan-Barang&Act=Delete&ID=<?php echo $ID; ?>" target="_self"><img src="images/hapus.gif" width="16" height="16" border="0" /></a></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="5" align="right"><b>Grand Total : </b></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center"><b><?php echo $qtyBrg; ?></b></td>
    <td align="right"><b><?php echo format_angka($total); ?></b></td>
    <td align="right">&nbsp;</td>
  </tr>
</table>
</form>