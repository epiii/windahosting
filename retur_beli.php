<link rel="stylesheet" type="text/css" media="screen" href="styles/smoothness/jquery-ui-1.10.1.custom.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="styles/smoothness/jquery.ui.combogrid.css"/>

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.ui.combogrid-1.6.3.js"></script>
<script>
	function angkaValid(event) {
		//bukan angka
		//range x to y
		 /*var thisVal = parseInt($(this).val(), 10);
		 console.log(thisVal);
		if (!isNaN(thisVal)){
			thisVal = Math.max(1, Math.min(100, thisVal));
			$(this).val(thisVal);
		}*/
		
		//var value = $(this).val();
		//value = value.replace(/^(0*0-9)/,"");
		//$(this).val(value);
		
		if(this.value != this.value.replace(/[^0-9]/g, '')){
			this.value = this.value.replace(/[^0-9]/g, '');
			
			$('#infoP').html('hanya angka').fadeIn();
			setTimeout(function(){
				$('#infoP').fadeOut();
			},1000);
		}
	}

	jQuery(document).ready(function(){
		$("#txtBrgBhn").on('keyup', function(e){
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			var keyCode = $.ui.keyCode;
			if(key != keyCode.ENTER && key != keyCode.LEFT && key != keyCode.RIGHT && key != keyCode.DOWN) {
				//$('#txtHargaBeli').val('');
				$('#txtKode').val('');
			}
		});
		$( "#txtBrgBhn" ).combogrid({
			debug:true,
			colModel: [{
					'columnName':'kode',
					'hide':true,
					'width':'10',
					'label':'kode'
				}, {
					'columnName':'nama',
					'width':'45',
					'label':'barang/bahan'
				},{
					'columnName':'harga',
					'width':'45',
					'label':'harga'
				}],
			url: 'pAjax.php?aksi=autoCom&menu=returBeli',
			select: function( event, ui ) {
				$( "#txtBrgBhn" ).val( ui.item.nama);
				$( "#txtKode" ).val( ui.item.kode);
				//$( "#txtHargaBeli" ).val( ui.item.harga_beli);
				return false;
			}
		});
	});
	
	function noInput(event){
		$('#txtBrgBhn').focus();
		$('#infoP').html('<span style="color:red;">kode terisi otomatis (setelah anda pilih barang/bahan)</span>').fadeIn();
		setTimeout(function(){
			$('#infoP').fadeOut();
		},3000);
	}
	
	function submitPilih(event){
		var kode 	= $('#txtKode').val();
		var catatan	= $('#txtCatatan').val();
		if(kode==''){
			$('#infoP').html('<span style="color:red;">silahkan pilih barang / bahan</span>').fadeIn();
			$('#txtBrgBhn').focus();
			setTimeout(function(){
				$('#infoP').fadeOut();
			},3000);
			return false;
		}else if(catatan==''){
			$('#infoP').html('<span style="color:red;">silahkan isi catatan</span>').fadeIn();
			$('#txtCatatan').focus();
			setTimeout(function(){
				$('#infoP').fadeOut();
			},3000);
			return false;
		}
	}
	
	function submitTrans(event){
		var supplier	= $('#cmbSupplier').val();
		var itemx		= $('.tmpTR').length;
		//alert(itemx);
		if(supplier==''){
			$('#infoP').html('<span style="color:red;">silahkan pilih supplier</span>').fadeIn();
			$('#cmbSupplier').focus();
			setTimeout(function(){
				$('#infoP').fadeOut();
			},3000);
			event.stopPropagation();
			event.preventDefault();
			//return false;
		}else if(itemx=0){
			$('#infoP').html('<span style="color:red;">silahkan pilih barang minimal 1</span>').fadeIn();
			$('#txtBrgBhn').focus();
			setTimeout(function(){
				$('#infoP').fadeOut();
			},3000);
			event.stopPropagation();
			event.preventDefault();
			//return false;
		}
	}
	
	$(document).ready(function(){
		//$('form').on('submit',submitTrans);
		$('#btnSave').on('click',submitTrans);
		$('#btnPilih').on('click',submitPilih);
		$('#txtJumlah').on('keyup input paste',angkaValid);
		$('#txtKode').on('focus',noInput);
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
			mysql_query("DELETE FROM tmp_returbeli WHERE id='".$_GET['ID']."' AND userid='".$_SESSION['SES_LOGIN']."'", $koneksidb) 
				or die ("Gagal kosongkan tmp".mysql_error());
		}
		if(trim($_GET['Act'])=="Sucsses"){
			echo "<b>DATA BERHASIL DISIMPAN</b> <br><br>";
		}
	}
	// =========================================================================
	
	if($_POST) {
	# TOMBOL PILIH (KODE barang) DIKLIK
	if(isset($_POST['btnPilih'])){
		$message = array();
		if (trim($_POST['txtKode'])=="") {
			$message[] = "<b>Kode Barang belum diisi</b>, !";		
		}
		if (trim($_POST['txtCatatan'])=="" OR ! trim($_POST['txtCatatan'])) {
			$message[] = "Catatan<b> barang belum diisi</b>, silahkan diisi !";		
		}
		if (trim($_POST['txtJumlah'])=="" OR ! is_numeric(trim($_POST['txtJumlah']))) {
			$message[] = "Data <b>Jumlah barang (Qty) belum diisi</b>, silahkan <b>isi dengan angka</b> !";		
		}
		
		# Baca variabel
		$txtKode	= $_POST['txtKode'];
		$txtKode	= str_replace("'","&acute;",$txtKode);
		$txtCatatan = $_POST['txtCatatan'];
		$txtCatatan = str_replace("'","&acute;",$txtCatatan);
		$txtCatatan = str_replace(".","",$txtCatatan);
		$txtJumlah	= $_POST['txtJumlah'];
		$txtJumlah	= str_replace("'","&acute;",$txtJumlah);
		
		# Jika jumlah error message tidak ada
		# Jika jumlah error message tidak ada
		if(count($message)==0){			
			// Membedakan antara bahan baku dengan Barang dengan bahan baku
			if(substr($txtKode,0,2)!="BB"){
				$barangSql ="SELECT * FROM barang WHERE kd_barang='$txtKode'";
				$barangQry = mysql_query($barangSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				$barangRow = mysql_fetch_array($barangQry);
				$barangQty = mysql_num_rows($barangQry);
				if ($barangQty >= 1) {
					
					$tmpSql = "INSERT INTO tmp_returbeli SET 	kd_barang	='$barangRow[kd_barang]', 
																catatan		='$txtCatatan',
																qty			='$txtJumlah', 
																userid		='".$_SESSION['SES_LOGIN']."'";
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
				//var_dump($bahanSql);exit();
				$bahanQry = mysql_query($bahanSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				$bahanRow = mysql_fetch_array($bahanQry);
				$bahanQty = mysql_num_rows($bahanQry);
				if ($bahanQty >= 1) {
					
					$tmpSql = "INSERT INTO tmp_returbeli SET 	kd_bahan	='$bahanRow[kd_bahan]', 
																qty			='$txtJumlah', 
																catatan		='$txtCatatan', 
																userid		='".$_SESSION['SES_LOGIN']."'";
					#var_dump($tmpSql);exit();
					mysql_query($tmpSql, $koneksidb) or die ("Gagal Query detail bahan : ".mysql_error());
					$txtKode	= "";
					$txtJumlah	= "";
					$txtHargaBeli = "";
				}
				else {
					$message[] = "Tidak ada bahan dengan kode <b>$txtKode'</b> , silahkan ganti";
				}
			
			}
		}

	}
	// ============================================================================
	
	# JIKA TOMBOL SIMPAN DIKLIK
	if(isset($_POST['btnSave'])){
		$message = array();
		if (trim($_POST['cmbSupplier'])=="BLANK") {
			$message[] = "<b>Nama Supplier belum dipilih</b>, silahkan pilih lagi !";
		}
		if (trim($_POST['cmbTanggal'])=="") {
			$message[] = "Tanggal transaksi belum diisi, pilih pada combo !";		
		}
		$tmpSql ="SELECT COUNT(*) As qty FROM tmp_returbeli WHERE userid='".$_SESSION['SES_LOGIN']."'";
		$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
		$tmpRow = mysql_fetch_array($tmpQry);
		if ($tmpRow['qty'] < 1) {
			$message[] = "<b>Item Barang</b> belum ada yang dimasukan, <b>minimal 1 barang</b>.";
		}
		
		# Baca variabel
		$txtKode= $_POST['txtKode'];
		$cmbSupplier= $_POST['cmbSupplier'];
		$cmbSupplier= str_replace("'","&acute;",$cmbSupplier);
		$txtCatatan	= $_POST['txtCatatan'];
		$txtCatatan = str_replace("'","&acute;",$txtCatatan);
		$cmbTanggal =$_POST['cmbTanggal'];
				
		# Jika jumlah error message tidak ada
		if(count($message)==0){			
			$kodeBaru	= buatKode("returbeli", "RB");
			$sql		= "INSERT INTO returbeli SET 	no_returbeli	='$kodeBaru', 
														tgl_transaksi	='".InggrisTgl($_POST['cmbTanggal'])."', 
														kd_supplier		='$cmbSupplier', 
														userid			='".$_SESSION['SES_LOGIN']."'";
			$qrySave	= mysql_query($sql) or die ("Gagal query".mysql_error());
			if($qrySave){
				# Ambil semua data barang yang dipilih, berdasarkan kasir yg login
				$tmpSql ="SELECT * FROM tmp_returbeli WHERE userid='".$_SESSION['SES_LOGIN']."'";
				$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				while ($tmpRow = mysql_fetch_array($tmpQry)) {
					// Masukkan semua barang yang udah diisi ke tabel returbeli detail
					if($tmpRow['kd_barang']!=''){ // jika barang
						$kode	= "kd_barang";
						$tabel	= 'barang';
						$qty	= 'qty';
					}else{// jika bahan baku
						$kode	= "kd_bahan";
						$tabel	= 'bahanbaku';
						$qty	= 'jml_bahan';
					}
					$itemSql = "INSERT INTO returbeli_item SET 	no_returbeli='$kodeBaru', 
																$kode		='$tmpRow[$kode]', 
																catatan		='$tmpRow[catatan]', 
																jumlah		='$tmpRow[qty]'";

		  			mysql_query($itemSql, $koneksidb) or die ("Gagal Query Simpan detail barang".mysql_error());
					
					// Update qty
					$barangSql = "UPDATE ".$tabel." SET ".$qty."=".$qty." - ".$tmpRow['qty']." WHERE ".$kode."='$tmpRow[$kode]'";
		  			mysql_query($barangSql, $koneksidb) or die ("Gagal Query Edit Qty".mysql_error());
				}
				//Ambil Data Bahan Baku
				$tmpSql ="SELECT * FROM tmp_returbeli WHERE userid='".$_SESSION['SES_LOGIN']."' and kd_bahan <>''";
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
	 
				}
				
				# Kosongkan Tmp jika datanya sudah dipindah
				mysql_query("DELETE FROM tmp_returbeli WHERE userid='".$_SESSION['SES_LOGIN']."'", $koneksidb) or die ("Gagal kosongkan tmp".mysql_error());
				
				// Refresh form
				echo "<meta http-equiv='refresh' content='0; url=?page=Retur-Pembelian&Act=Sucsses'>";
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

	} // Penutup POST
} // Penutup GET

# TAMPILKAN DATA KE FORM
$nomorRetur = buatKode("returbeli", "RB");
$tglTransaksi 	= isset($_POST['cmbTanggal']) ? $_POST['cmbTanggal'] : date('d-m-Y');
$dataSupplier	= isset($_POST['txtSupplier']) ? $_POST['txtPelanggan'] : '';
//$dataCatatan	= isset($_POST['txtCatatan']) ? $_POST['txtCatatan'] : '';
?>
<form action="" method="post"  name="frmadd">
<table width="750" cellspacing="1" class="table-common" style="margin-top:0px;">
	<tr>
	  <td colspan="3" align="right"><h1 align="center">RETUR PEMBELIAN BARANG / BAHAN BAKU</h1> </td>
	</tr>
	<tr>
	  <td width="20%"><b>No Retur </b></td>
	  <td width="1%"><b>:</b></td>
	  <td width="79%"><input name="txtNomor" value="<?php echo $nomorRetur; ?>" size="9" maxlength="9" readonly="readonly"/></td></tr>
	<tr>
      <td><b>Tanggal Retur Beli</b></td>
	  <td><b>:</b></td>
	  <td><?php echo form_tanggal("cmbTanggal",$tglTransaksi); ?></td>
    </tr>
	<tr>
      <td><b>Supplier</b></td>
	  <td><b>:</b></td>
	  <td><label>
	    <select  required id="cmbSupplier" name="cmbSupplier">
        <option value="">Pilih Supplier ..</option>
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
      </select>
      </label></td>
    </tr>
	<tr><td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
    </tr>
	<tr>
		<td><b>Kode Barang / Bahan Baku</b></td>
		<td><b>:</b></td>
		<td><b>
			<input readonly id="txtKode" name="txtKode" class="angkaC" size="4" maxlength="20" placeholder="kode" />
			<input id="txtBrgBhn"name="txtBrgBhn" size="50" maxlength="100" placeholder="cari kode berdasarkan nama barang/bahan "/>
		</td>
	</tr>
	<tr>
		<td>Catatan </td>
		<td>:</td>
		<td>
			<input  placeholder="catatan  / keterangan" id="txtCatatan"name="txtCatatan" value="<?php echo $dataCatatan; ?>" size="30" maxlength="100" />
			Qty :
			<input class="angkaC" id="txtJumlah"name="txtJumlah" size="2" maxlength="4" value="1" 
	  		 onblur="if (value == '') {value = '1'}" 
      		 onfocus="if (value == '1') {value =''}"/>
			<input id="btnPilih" name="btnPilih" type="submit" style="cursor:pointer;" value=" Pilih " /></b>
		</td>
	</tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td ><p id="infoP" style="color:red;"></p></td>
    </tr>

	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><input id="btnSave" name="btnSave" type="submit" style="cursor:pointer;" value=" SIMPAN TRANSAKSI " /></td>
    </tr>
</table>
<table class="table-list" width="750" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <th colspan="7">DAFTAR  ITEM BARANG / BAHAN BAKU</th>
  </tr>
  <tr>
    <td width="30" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="89" align="left" bgcolor="#CCCCCC"><b>Kode</b></td>
    <td width="317" bgcolor="#CCCCCC" align="left"><b>Nama Barang / Bahan Baku</b></td>
    <td width="153" align="left" bgcolor="#CCCCCC"><b>Catatan</b></td>
    <td width="61" align="left" bgcolor="#CCCCCC"><b>Qty</b></td>
    <td width="69" align="center" bgcolor="#FFCC00"><b>Delete</b></td>
  </tr>
  <?php

$tmpSql = "	SELECT * FROM tmp_returbeli
			WHERE userid='".$_SESSION['SES_LOGIN']."'
			GROUP BY id
 			ORDER BY id ASC ";
$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());

$total = 0; $qtyBrg = 0; $nomor=0;
while($tmpRow = mysql_fetch_array($tmpQry)) {
$sTempbarang	= " SELECT * FROM barang WHERE kd_barang ='".$tmpRow['kd_barang']."'";
$qTempbarang	= mysql_query($sTempbarang, $koneksidb) or die ("Gagal Query Tmp Barang".mysql_error());
$rTempbarang	= mysql_fetch_array($qTempbarang);

$sTempbahan	= " SELECT * FROM bahanbaku WHERE kd_bahan ='".$tmpRow['kd_bahan']."'";
$qTempbahan	= mysql_query($sTempbahan, $koneksidb) or die ("Gagal Query Tmp Bahan".mysql_error());
$rTempbahan	= mysql_fetch_array($qTempbahan);

	$ID		= $tmpRow['id'];
	$qtyBrg = $qtyBrg + $tmpRow['qty'];
	
	$nomor++;
?>
 
  <tr class="tmpTR">
    <td align="center"><b><?php echo $nomor; ?></b></td>
    <td align="left"><b><?php echo $rTempbarang['kd_barang'].$rTempbahan['kd_bahan']; ?></b></td>
    <td align="left"><?php echo $rTempbarang['nm_barang'].$rTempbahan['nm_bahan']; ?></td>
    <td align="left"><?php echo $tmpRow['catatan']; ?></td>
    <td align="left"><?php echo $tmpRow['qty']; ?></td>
    <td align="center" bgcolor="#FFFFCC"><a href="?page=Retur-Pembelian&Act=Delete&ID=<?php echo $ID; ?>" target="_self"><img src="images/hapus.gif" width="16" height="16" border="0" /></a></td>
  </tr>
  <?php 
}?>
  <tr>
    <td colspan="6" align="right">&nbsp;</td>
  </tr>
</table>
</form>
