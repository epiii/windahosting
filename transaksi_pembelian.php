<script src="js/jquery.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="styles/smoothness/jquery-ui-1.10.1.custom.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="styles/smoothness/jquery.ui.combogrid.css"/>

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.ui.combogrid-1.6.3.js"></script>

<script>
jQuery(document).ready(function(){
	$("#txtBarang").on('keyup', function(e){
		var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		var keyCode = $.ui.keyCode;
		if(key != keyCode.ENTER && key != keyCode.LEFT && key != keyCode.RIGHT && key != keyCode.DOWN) {
			$('#txtHargaBeli').val('');
			$('#txtKode').val('');
		}
	});
	$( "#txtBarang" ).combogrid({
		debug:true,
		colModel: [{
				'columnName':'kode',
				'hide':true,
				'width':'10',
				'label':'kode'
			}, {
				'columnName':'nama',
				'width':'45',
				'label':'barang / bahan'
			},{
				'columnName':'harga',
				'width':'45',
				'label':'harga'
			}],
		url: 'pAjax.php?aksi=autoCom&menu=transBeli',
		select: function( event, ui ) {
			$( "#txtBarang" ).val( ui.item.nama);
			$( "#txtKode" ).val( ui.item.kode);
			$( "#txtHargaBeli" ).val( ui.item.harga);
			return false;
		}
	});
});
	function angkaValid(event) {
		if(this.value != this.value.replace(/[^0-9]/g, '')){
			this.value = this.value.replace(/[^0-9]/g, '');
			
			$('#infoKodeP').html('<span style="color:red;">hanya angka</span>').fadeIn();
			setTimeout(function(){
				$('#infoKodeP').fadeOut();
			},1000);
		}
	}
	

$(document).ready(function(){
	$('#txtJumlah').on('input paste',angkaValid);
	
	$('#txtKode').on('focus',function(){
		$('#txtBarang').focus();

		$('#infoKodeP').html('<span style="color:red;">kode terisi otomatis (setelah anda pilih barang)</span>').fadeIn();
		setTimeout(function(){
			$('#infoKodeP').fadeOut();
		},3000);
	});
	$('#txtHargaBeli').on('focus',function(){
		$('#txtBarang').focus();

		$('#infoKodeP').html('<span style="color:red;">harga terisi otomatis (setelah anda pilih barang)</span>').fadeIn();
		setTimeout(function(){
			$('#infoKodeP').fadeOut();
		},3000);
	});
});
</script>


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
			$('#cmbTempo').val('00-00-0000');
			//$('#cmbTempo').attr('disabled',true);
		}else{
			$('#cmbTempo').val(tg+'-'+bl+'-'+th);
			//$('#cmbTempo').attr('disabled',false);
		}
	}
	$(document).ready(function(){
		$('#cmbCatatan').on('blur change',cmbCatatan);
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
			mysql_query("DELETE FROM tmp_pembelian WHERE id='".$_GET['ID']."' AND userid='".$_SESSION['SES_LOGIN']."'", $koneksidb) 
				or die ("Gagal kosongkan tmp".mysql_error());
		}
		if(trim($_GET['Act'])=="Sucsses"){
			echo "<b>DATA BERHASIL DISIMPAN</b> <br><br>";
		}
	}
	
	if($_POST) {
	# TOMBOL PILIH (KODE barang) DIKLIK
	if(isset($_POST['btnPilih'])){
		$message = array();
		if (trim($_POST['txtKode'])=="") {
			$message[] = "<b>Kode Barang belum diisi</b>, ketik secara manual!";		
		}
		if (trim($_POST['txtHargaBeli'])=="" OR ! is_numeric(trim($_POST['txtHargaBeli']))) {
			$message[] = "Data <b>Harga Beli belum diisi</b>, silahkan <b>isi dengan nominal uang</b> !";		
		}
		if (trim($_POST['txtJumlah'])=="" OR ! is_numeric(trim($_POST['txtJumlah']))) {
			$message[] = "Data <b>Jumlah barang (Qty) belum diisi</b>, silahkan <b>isi dengan angka</b> !";		
		}
		
		# Baca variabel
		$txtKode	= $_POST['txtKode'];
		$txtKode	= str_replace("'","&acute;",$txtKode);
		$txtHargaBeli = $_POST['txtHargaBeli'];
		$txtHargaBeli = str_replace("'","&acute;",$txtHargaBeli);
		$txtHargaBeli = str_replace(".","",$txtHargaBeli);
		$txtJumlah	= $_POST['txtJumlah'];
		$txtJumlah	= str_replace("'","&acute;",$txtJumlah);
		
		# Jika jumlah error message tidak ada
		if(count($message)==0){			
			$tmpSql = "INSERT INTO tmp_pembelian SET 	kd_item		='$txtKode', 	
														qty			='$txtJumlah', 
														userid		='".$_SESSION['SES_LOGIN']."'";
			$exe	= mysql_query($tmpSql, $koneksidb) or die ("Gagal Query detail barang : ".mysql_error());
					
			// Membedakan antara bahan baku dengan Barang dengan bahan baku
			/*if(substr($txtKode,0,2)!="BB"){
				$barangSql ="SELECT * FROM barang WHERE kd_barang='$txtKode'";
				$barangQry = mysql_query($barangSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				$barangRow = mysql_fetch_array($barangQry);
				$barangQty = mysql_num_rows($barangQry);
				if ($barangQty >= 1) {
					
					$tmpSql = "INSERT INTO tmp_pembelian SET kd_barang='$barangRow[kd_barang]', harga_beli='$txtHargaBeli', 
							   qty='$txtJumlah', userid='".$_SESSION['SES_LOGIN']."'";
					mysql_query($tmpSql, $koneksidb) or die ("Gagal Query detail barang : ".mysql_error());
					$txtKode	= "";
					$txtJumlah	= "";
					$txtHargaBeli = "";
				}
				else {
					$message[] = "Tidak ada barang dengan kode <b>$txtKode'</b>, silahkan ganti";
				}
			}else{
				//Ini Buat Bahan Baku
				$bahanSql ="SELECT * FROM bahanbaku WHERE kd_bahan='$txtKode'";
				$bahanQry = mysql_query($bahanSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				$bahanRow = mysql_fetch_array($bahanQry);
				$bahanQty = mysql_num_rows($bahanQry);
				if ($bahanQty >= 1) {
					
					$tmpSql = "INSERT INTO tmp_pembelian SET kd_bahan='$bahanRow[kd_bahan]', harga_beli='$txtHargaBeli', 
							   qty='$txtJumlah', userid='".$_SESSION['SES_LOGIN']."'";
					mysql_query($tmpSql, $koneksidb) or die ("Gagal Query detail bahan : ".mysql_error());
					$txtKode	= "";
					$txtJumlah	= "";
					$txtHargaBeli = "";
				}
				else {
					$message[] = "Tidak ada bahan dengan kode <b>$txtKode'</b> , silahkan ganti";
				}
			
			}*/
		}

	}
	
	# JIKA TOMBOL SIMPAN DIKLIK
	if(isset($_POST['btnSave'])){
		//var_dump($_POST['cmbTempo']);exit();
		$message = array();
		if (trim($_POST['cmbSupplier'])=="BLANK") {
			$message[] = "<b>Nama Supplier belum dipilih</b>, silahkan pilih lagi !";		
		}
		if (trim($_POST['cmbTanggal'])=="") {
			$message[] = "Tanggal transaksi belum diisi, pilih pada combo !";		
		}
		if (trim($_POST['cmbTempo'])=="") {
			$message[] = "Tanggal jatuh tempo belum diisi, pilih pada combo !";
		}
		$tmpSql ="SELECT COUNT(*) As qty FROM tmp_pembelian WHERE userid='".$_SESSION['SES_LOGIN']."'";
		$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
		$tmpRow = mysql_fetch_array($tmpQry);
		if ($tmpRow['qty'] < 1) {
			$message[] = "<b>Item Barang</b> belum ada yang dimasukan, <b>minimal 1 barang</b>.";
		}
		
		# Baca variabel
		$cmbSupplier= $_POST['cmbSupplier'];
		$cmbSupplier= str_replace("'","&acute;",$cmbSupplier);
		$txtCatatan	= $_POST['txtCatatan'];
		$txtCatatan = str_replace("'","&acute;",$txtCatatan);
		$cmbTanggal =$_POST['cmbTanggal'];
		$cmbTempo	= $_POST['cmbTempo'];
		
		# Jika jumlah error message tidak ada
		if(count($message)==0){			
			$kodeBaru	= buatKode("pembelian", "BL");
			$sql		= "INSERT INTO pembelian SET no_pembelian	='$kodeBaru', 
													tgl_transaksi	='".InggrisTgl($_POST['cmbTanggal'])."', 
													jatuh_tempo		='".InggrisTgl($_POST['cmbTempo'])."',
													kd_supplier		='$cmbSupplier', 
													catatan			='$_POST[cmbCatatan]', 
													userid			='".$_SESSION['SES_LOGIN']."'";
			//var_dump($sql);exit();
			//var_dump($cmbCatatan);exit();
			$qrySave	= mysql_query($sql) or die ("Gagal query".mysql_error());
			if($qrySave){
				$tmpSql ="SELECT * FROM tmp_pembelian WHERE userid='".$_SESSION['SES_LOGIN']."'";
				$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				while ($tmpRow = mysql_fetch_array($tmpQry)) {
					$barangSql = "INSERT INTO pembelian_item SET 	no_pembelian	='$kodeBaru', 
																	kd_item			='$tmpRow[kd_item]', 
																	jumlah			='$tmpRow[qty]'";
						mysql_query($barangSql, $koneksidb) or die ("Gagal Query Simpan detail barang".mysql_error());
				}
				# Ambil semua data barang yang dipilih, berdasarkan kasir yg login
				/*$tmpSql ="SELECT * FROM tmp_pembelian WHERE userid='".$_SESSION['SES_LOGIN']."' and kd_barang <>''";
				$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				while ($tmpRow = mysql_fetch_array($tmpQry)) {

					// Masukkan semua barang yang udah diisi ke tabel pembelian detail
						$barangSql = "INSERT INTO pembelian_item SET no_pembelian='$kodeBaru', kd_barang='$tmpRow[kd_barang]', kd_bahan='$tmpRow[kd_bahan]', harga_beli='$tmpRow[harga_beli]',
									  jumlah='$tmpRow[qty]'";
						mysql_query($barangSql, $koneksidb) or die ("Gagal Query Simpan detail barang".mysql_error());
	
						// Update stok
						$barangSql = "	UPDATE barang SET qty=qty + $tmpRow[qty], harga_beli='$tmpRow[harga_beli]'
										WHERE kd_barang='$tmpRow[kd_barang]'";
						mysql_query($barangSql, $koneksidb) or die ("Gagal Query Edit Qty".mysql_error());
  
	 
				}*//*
				//Ambil Data Bahan Baku
				$tmpSql ="SELECT * FROM tmp_pembelian WHERE userid='".$_SESSION['SES_LOGIN']."' and kd_bahan <>''";
				$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				while ($tmpRow = mysql_fetch_array($tmpQry)) {
 
 
						$bahanSql = " INSERT INTO pembelian_item SET no_pembelian='$kodeBaru', 
									  kd_bahan='$tmpRow[kd_bahan]', harga_beli='$tmpRow[harga_beli]',
									  jumlah='$tmpRow[qty]'";
						mysql_query($bahanSql, $koneksidb) or die ("Gagal Query Simpan detail Bahan Baku".mysql_error());
	
						// Update stok
						$bahanSql = "	UPDATE bahanbaku SET jml_bahan=jml_bahan + $tmpRow[qty], harga_bahan='$tmpRow[harga_beli]'
										WHERE kd_bahan='$tmpRow[kd_bahan]'";
						mysql_query($bahanSql, $koneksidb) or die ("Gagal Query Edit Jumlah Bahan".mysql_error());
	 
				}*/
				
				# Kosongkan Tmp jika datanya sudah dipindah
				mysql_query("DELETE FROM tmp_pembelian WHERE userid='".$_SESSION['SES_LOGIN']."'", $koneksidb) 
						or die ("Gagal kosongkan tmp".mysql_error());
				
				// Refresh form
				echo "<meta http-equiv='refresh' content='0; url=?page=Pembelian-Barang&Act=Sucsses'>";
			}
			else{
				$message[] = "Gagal penyimpanan ke database";
			}
		}	
	} 
	// =======================================
	
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
	// ======================================
	
	} // Penutup POST
} // Penutup GET

# TAMPILKAN DATA KE FORM
$nomorTransaksi = buatKode("pembelian", "BL");
$tglTransaksi 	= isset($_POST['cmbTanggal']) ? $_POST['cmbTanggal'] : date('d-m-Y');
$jatuhtempo 	= isset($_POST['cmbTempo']) ? $_POST['cmbTempo'] : date('d-m-Y');
$dataCatatan	= isset($_POST['txtCatatan']) ? $_POST['txtCatatan'] : '';
?>
<form action="" method="post"  name="frmadd">
<table width="750" cellspacing="1" class="table-common" style="margin-top:0px;">
	<tr>
	  <td colspan="3" align="right"><h1>TRANSAKSI PEMBELIAN BARANG/ BAHAN BAKU</h1> </td>
	</tr>
	<tr>
	  <td width="28%"><b>No. Pembelian </b></td>
	  <td width="1%"><b>:</b></td>
	  <td width="71%"><input name="txtNomor" value="<?php echo $nomorTransaksi; ?>" size="9" maxlength="9" readonly="readonly"/></td></tr>
	<tr>
	  <td><strong>No. Pembelian Supplier</strong></td>
	  <td><strong>:</strong></td>
	  <td><label>
	    <input name="textfield" type="text" id="textfield" value="<?php echo $nomorSupplier; ?>" size="9" maxlength="9" />
	  </label></td>
    </tr>
	<tr>
      <td><b>Tanggal Pembelian </b></td>
	  <td><b>:</b></td>
	  <td><?php echo form_tanggal("cmbTanggal",$tglTransaksi); ?></td>
    </tr>
	<tr>
      <td><b>Supplier Barang </b></td>
	  <td><b>:</b></td>
	  <td><select required name="cmbSupplier">
        <option value="">pilih supplier ..</option>
        <?php
	  $dataSql = "SELECT * FROM supplier ORDER BY kd_supplier";
	  $dataQry = mysql_query($dataSql, $koneksidb) or die ("Gagal Query".mysql_error());
	  while ($dataRow = mysql_fetch_array($dataQry)) {
	  	if ($dataRow['kd_supplier']== $_POST['cmbSupplier']) {
			$cek = " selected";
		} else { $cek=""; }
	  	echo "<option value='$dataRow[kd_supplier]' $cek>$dataRow[nm_supplier]</option>";
	  }
	  $sqlData ="";
	  ?>
      </select></td>
    </tr>
	<tr>
      <td><b>Catatan</b></td>
	  <td><b>:</b></td>
	  <td><select required name="cmbCatatan" value="<?php echo $dataCatatan; ?>"  id="cmbCatatan">
	    <option value="">pilih catatan .. </option>
	    <option value="lunas">Lunas</option>
	    <option value="hutang">Hutang</option>
		<?php
			/*$dataSql = "SELECT * FROM pembelian ORDER BY catatan";
			$dataQry = mysql_query($dataSql, $koneksidb) or die ("Gagal Query".mysql_error());
			while ($dataRow = mysql_fetch_array($dataQry)) {
			if ($dataRow['catatan']== $_POST['cmbCatatan']) {
				$cek = " selected";
			} else { $cek=""; }
				echo "<option value='$dataRow[catatan]' $cek>$dataRow[catatan]</option>";
			}
			$sqlData ="";*/
		?>
      </select></td>
    </tr>
    <script type="text/javascript">
    	/*function disabletempo(nilai){ 
			if (nilai=="Lunas" || nilai=="lunas" || nilai=="LUNAS"){
				document.getElementById("cmbTempo").disabled= true;  
				document.getElementById("cmbTempo").value= "00-00-0000"; 
			}
		}*/
    </script> 
	<tr>
	  <td><strong>Jatuh Tempo</strong></td>
	  <td><b>:</b></td>
	  <td><span id="jatuh"><?php echo form_tanggal("cmbTempo",$jatuhtempo); ?></span></td>
    </tr> 
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
    </tr>
	<tr>
	  <td><b>Kode Barang/ Bahan Baku</b></td>
	  <td><b>:</b></td>
	  <td><b>
			<input size="4" name="txtKode"id="txtKode" placeholder="kode" readonly/>
			<input name="txtBarang" id="txtBarang" placeholder="cari barang / bahan berdasarkan nama "class="angkaC" size="40" maxlength="20" />
	  </b></td>
    </tr>
	<tr>
	  <td><b>Harga Beli</b></td>
	  <td><b>:</b></td>
	  <td><b>
			Rp. <input id="txtHargaBeli"  placeholder="harga beli" name="txtHargaBeli" class="angkaR"  size="14" maxlength="10" readonly/> 
		Qty :
		<input class="angkaC" name="txtJumlah" id="txtJumlah"size="4" maxlength="4" value="1" 
					 onblur="if (value == '') {value = '1'}" 
					 onfocus="if (value == '1') {value =''}"/>
	    <input name="btnPilih" type="submit" style="cursor:pointer;" value=" Pilih " />
	  </b></td>
    </tr>
	
	<tr>
      <td>&nbsp;</td>
      <td colspan="2" id="infoKodeTD"><p id="infoKodeP"></p></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><input name="btnSave" type="submit" style="cursor:pointer;" value=" SIMPAN TRANSAKSI " /></td>
    </tr>
</table>

<table class="table-list" width="750" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <th colspan="7">DAFTAR  ITEM BARANG/ BAHAN BAKU</th>
    </tr>
  <tr>
    <td width="26" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="66" align="center" bgcolor="#CCCCCC"><b>Kode</b></td>
    <td width="335" bgcolor="#CCCCCC"><b>Nama Barang/ Bahan Baku</b></td>
    <td width="97" align="right" bgcolor="#CCCCCC"><b>Harga Beli (Rp)</b></td>
    <td width="41" align="center" bgcolor="#CCCCCC"><b>Qty</b></td>
    <td width="97" align="right" bgcolor="#CCCCCC"><b>Subtotal (Rp)</b></td>
    <td width="52" align="center" bgcolor="#FFCC00"><b>Delete</b></td>
  </tr>
<?php

$tmpSql = "	SELECT * FROM tmp_pembelian
			WHERE userid='".$_SESSION['SES_LOGIN']."'
			GROUP BY id
 			ORDER BY id ASC ";
$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());

$total = 0; $qtyBrg = 0; $nomor=0;
while($tmpRow = mysql_fetch_array($tmpQry)) {
	$sTempItem	= " SELECT
						*
					FROM
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
					WHERE tbitem.kode='$tmpRow[kd_item]'
					ORDER BY
						tbitem.kode";
	//var_dump($sTempItem);exit();
	$qTempItem	= mysql_query($sTempItem, $koneksidb) or die ("Gagal Query Tmp Barang".mysql_error());
	$rTempItem	= mysql_fetch_array($qTempItem);

	/*$sTempbarang	= " SELECT * FROM barang WHERE kd_barang ='".$tmpRow['kd_barang']."'";
	$qTempbarang	= mysql_query($sTempbarang, $koneksidb) or die ("Gagal Query Tmp Barang".mysql_error());
	$rTempbarang	= mysql_fetch_array($qTempbarang);

	$sTempbahan	= " SELECT * FROM bahanbaku WHERE kd_bahan ='".$tmpRow['kd_bahan']."'";
	$qTempbahan	= mysql_query($sTempbahan, $koneksidb) or die ("Gagal Query Tmp Bahan".mysql_error());
	$rTempbahan	= mysql_fetch_array($qTempbahan);
	*/
	$ID			= $tmpRow['id'];
	$subSotal 	= $tmpRow['qty'] * intval($rTempItem['harga']);
	$total 		= $total + $subSotal;
	$qtyBrg 	= $qtyBrg + $tmpRow['qty'];
	
	$nomor++;
?>
  <tr>
    <td align="center"><b><?php echo $nomor; ?></b></td>
    <td align="center"><b><?php echo $rTempItem['kode'];?></b></td>
    <td><?php echo $rTempItem['nama']; ?></td>
    <td align="right"><?php echo format_angka($rTempItem['harga']); ?></td>
    <td align="center"><?php echo $tmpRow['qty']; ?></td>
    <td align="right"><?php echo format_angka($subSotal); ?></td>
    <td align="center" bgcolor="#FFFFCC"><a href="?page=Pembelian-Barang&Act=Delete&ID=<?php echo $ID; ?>" target="_self"><img src="images/hapus.gif" width="16" height="16" border="0" /></a></td>
  </tr>
<?php 
}?>
  <tr>
    <td colspan="4" align="right"><b>Grand Total : </b></td>
    <td align="center"><b><?php echo $qtyBrg; ?></b></td>
    <td align="right"><b><?php echo format_angka($total); ?></b></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
</form>
