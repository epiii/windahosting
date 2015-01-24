<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if($_GET) {
	# HAPUS DAFTAR jasa DI TMP
	if(isset($_GET['Act'])){
		if(trim($_GET['Act'])=="Delete"){
			# Hapus Tmp jika datanya sudah dipindah
			mysql_query("DELETE FROM tmp_jasa WHERE id='".$_GET['ID']."' AND userid='".$_SESSION['SES_LOGIN']."'", $koneksidb) 
				or die ("Gagal kosongkan tmp".mysql_error());
		}
		if(trim($_GET['Act'])=="Sucsses"){
			echo "<b>DATA BERHASIL DISIMPAN</b> <br><br>";
		}
	}
	
	if($_POST) {
	# TOMBOL PILIH (KODE jasa) DIKLIK
	if(isset($_POST['btnPilih'])){
		$message = array();
		if (trim($_POST['txtKode'])=="") {
			$message[] = "<b>Kode Barang belum diisi</b>, ketik secara manual!";		
		}
		#if (trim($_POST['txtHargaBeli'])=="" OR ! is_numeric(trim($_POST['txtHargaBeli']))) {
			#$message[] = "Data <b>Harga Beli belum diisi</b>, silahkan <b>isi dengan nominal uang</b> !";		
		}
		if (trim($_POST['txtJumlah'])=="" OR ! is_numeric(trim($_POST['txtJumlah']))) {
			$message[] = "Data <b>Jumlah jasa (Qty) belum diisi</b>, silahkan <b>isi dengan angka</b> !";		
		}
		
		# Baca variabel
		$txtKode	= $_POST['txtKode'];
		$txtKode	= str_replace("'","&acute;",$txtKode);
		#$txtHargaJasa = $_POST['txtHargaBeli'];
		#$txtHargaJasa = str_replace("'","&acute;",$txtHargaBeli);
		#$txtHargaJasa = str_replace(".","",$txtHargaBeli);
		$txtJumlah	= $_POST['txtJumlah'];
		$txtJumlah	= str_replace("'","&acute;",$txtJumlah);
		
		# Jika jumlah error message tidak ada
		if(count($message)==0){			
			// Membedakan antara bahan baku dengan Barang dengan bahan baku
			if(substr($txtKode,0,2)!="BB"){
				$jasaSql ="SELECT * FROM jasa WHERE kd_jasa='$txtKode'";
				$jasaQry = mysql_query($jasaSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				$jasaRow = mysql_fetch_array($jasaQry);
				$jasaQty = mysql_num_rows($jasaQry);
				if ($jasaQty >= 1) {
					
					$tmpSql = "	INSERT INTO tmp_jasa 
								SET kd_jasa='$jasaRow[kd_jasa]', harga_jasa='$txtHargaJasa', userid='".$_SESSION['SES_LOGIN']."'";
					mysql_query($tmpSql, $koneksidb) or die ("Gagal Query detail jasa : ".mysql_error());
					$txtKode	= "";
					$txtJumlah	= "";
				}
				else {
					$message[] = "Tidak ada jasa dengan kode <b>$txtKode'</b>, silahkan ganti";
				}
			}else{
				
				//Ini Buat Bahan Baku
				$bahanSql ="SELECT * FROM bahanbaku WHERE kd_bahan='$txtKode'";
				$bahanQry = mysql_query($bahanSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				$bahanRow = mysql_fetch_array($bahanQry);
				$bahanQty = mysql_num_rows($bahanQry);
				if ($bahanQty >= 1) {
					
					$tmpSql = "	INSERT INTO tmp_jasa 
								SET kd_bahan='$bahanRow[kd_bahan]', harga_bahan='$txtHargaBahan', 
							    jml_bahan='$txtJumlah', userid='".$_SESSION['SES_LOGIN']."'";
					mysql_query($tmpSql, $koneksidb) or die ("Gagal Query detail bahan : ".mysql_error());
					$txtKode	= "";
					$txtJumlah	= "";
					$txtHargaBahan = "";
				}
				else {
					$message[] = "Tidak ada bahan dengan kode <b>$txtKode'</b> , silahkan ganti";
				}
			
			}
		}

	}
	
	# JIKA TOMBOL SIMPAN DIKLIK
	if(isset($_POST['btnSave'])){
		$message = array();
		if (trim($_POST['cmbPelanggan'])=="BLANK") {
			$message[] = "<b>Nama Pelanggan belum dipilih</b>, silahkan pilih lagi !";		
		}
		if (trim($_POST['cmbTanggal'])=="") {
			$message[] = "Tanggal transaksi belum diisi, pilih pada combo !";		
		}
		$tmpSql ="SELECT COUNT(*) As qty FROM tmp_pembelian WHERE userid='".$_SESSION['SES_LOGIN']."'";
		$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
		$tmpRow = mysql_fetch_array($tmpQry);
		if ($tmpRow['qty'] < 1) {
			$message[] = "<b>Item Barang</b> belum ada yang dimasukan, <b>minimal 1 barang</b>.";
		}
		
		# Baca variabel
		$cmbPelanggan= $_POST['cmbPelanggan'];
		$cmbPelanggan= str_replace("'","&acute;",$cmbPelanggan);
		#$txtCatatan	= $_POST['txtCatatan'];
		#$txtCatatan = str_replace("'","&acute;",$txtCatatan);
		$cmbTanggal =$_POST['cmbTanggal'];
		#$cmbTempo	= $_POST['cmbTempo'];
				
		# Jika jumlah error message tidak ada
		if(count($message)==0){			
			$kodeBaru	= buatKode("transaksi", "TJ");
			$qrySave=mysql_query("	INSERT INTO transaksi 
								 	SET no_transaksi='$kodeBaru', 
									tgl_transaksi='".InggrisTgl($_POST['cmbTanggal'])."', 
									kd_pelanggan='$cmbPelanggan', userid='".$_SESSION['SES_LOGIN']."'") or die ("Gagal query".mysql_error());
			if($qrySave){
				
				# Ambil semua data jasa yang dipilih, berdasarkan kasir yg login
				$tmpSql ="SELECT * FROM tmp_jasa WHERE userid='".$_SESSION['SES_LOGIN']."' and kd_jasa <>''";
				$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				while ($tmpRow = mysql_fetch_array($tmpQry)) {

					// Masukkan semua jasa yang udah diisi ke tabel pembelian detail
						$jasaSql = "  INSERT INTO jasa_item SET no_transaksi='$kodeBaru', 
									  kd_jasa='$tmpRow[kd_jasa]', kd_bahan='$tmpRow[kd_bahan]', harga_jasa='$tmpRow[harga_jasa]'";
						mysql_query($jasaSql, $koneksidb) or die ("Gagal Query Simpan detail jasa".mysql_error());
	
						// Update stok
						#$jasaSql = "	UPDATE jasa SET jml_bahan=jml_bahan+ $tmpRow[jml_bahan], harga_bahan='$tmpRow[harga_bahan]'
										#WHERE kd_bahan='$tmpRow[kd_bahan]'";
						#mysql_query($bahanSql, $koneksidb) or die ("Gagal Query Edit Qty".mysql_error());
  
	 
				}
				
				//Ambil Data Bahan Baku
				$tmpSql ="SELECT * FROM tmp_jasa WHERE userid='".$_SESSION['SES_LOGIN']."' and kd_bahan <>''";
				$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				while ($tmpRow = mysql_fetch_array($tmpQry)) {
 
 
						$bahanSql = " 	INSERT INTO jasa_item 
										SET no_transaksi='$kodeBaru', 
									  		kd_bahan='$tmpRow[kd_bahan]', 
											harga_bahan='$tmpRow[harga_bahan]',
									  		jml_bahan='$tmpRow[jml_bahan]'";
						mysql_query($bahanSql, $koneksidb) or die ("Gagal Query Simpan detail Bahan Baku".mysql_error());
	
						// Update stok
						$bahanSql = "	UPDATE bahanbaku SET jml_bahan='jml_bahan + $tmpRow[qty], harga_bahan='$tmpRow[harga_bahan]'
										WHERE kd_bahan='$tmpRow[kd_bahan]'";
						mysql_query($bahanSql, $koneksidb) or die ("Gagal Query Edit Jumlah Bahan".mysql_error());
	 
				}
				
				# Kosongkan Tmp jika datanya sudah dipindah
				mysql_query("DELETE FROM tmp_jasa WHERE userid='".$_SESSION['SES_LOGIN']."'", $koneksidb) 
						or die ("Gagal kosongkan tmp".mysql_error());
				
				// Refresh form
				echo "<meta http-equiv='refresh' content='0; url=?page=Transaksi-Jasa&Act=Sucsses'>";
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


# TAMPILKAN DATA KE FORM
$nomorTransaksi = buatKode("transaksi", "TJ");
$tglTransaksi 	= isset($_POST['cmbTanggal']) ? $_POST['cmbTanggal'] : date('d-m-Y');
$dataPelanggan	= isset($_POST['txtPelanggan']) ? $_POST['txtPelanggan']:'';
#$jatuhtempo 	= isset($_POST['cmbTempo']) ? $_POST['cmbTempo'] : date('d-m-Y');
#$dataCatatan	= isset($_POST['txtCatatan']) ? $_POST['txtCatatan'] : '';
?>
<form action="" method="post"  name="frmadd">
<table width="750" cellspacing="1" class="table-common" style="margin-top:0px;">
	<tr>
	  <td colspan="3" align="right"><h1 align="center">TRANSAKSI JASA</h1> </td>
	</tr>
	<tr>
	  <td width="20%"><b>No. Transaksi Jasa</b></td>
	  <td width="1%"><b>:</b></td>
	  <td width="79%"><input name="txtNomor" value="<?php echo $nomorTransaksi; ?>" size="9" maxlength="9" readonly="readonly"/></td></tr>
	<tr>
      <td><b>Tanggal Transaksi</b></td>
	  <td><b>:</b></td>
	  <td><?php echo form_tanggal("cmbTanggal",$tglTransaksi); ?></td>
    </tr>
	<tr>
      <td><b>Pelanggan</b></td>
	  <td><b>:</b></td>
	  <td><select name="cmbPelanggan" id="cmbPelanggan">
        <option value="BLANK"> </option>
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
      </select>
      </label></td>
    </tr>
	<tr>
	  <td><b>Kode Jasa</b></td>
	  <td><b>:</b></td>
	  <td><b>
	    <input name="txtKode" id="txtKode" class="angkaC" size="14" maxlength="20" />
 
		Qty :
		<input class="angkaC" name="txtJumlah" size="4" maxlength="4" value="1" 
					 onblur="if (value == '') {value = '1'}" 
					 onfocus="if (value == '1') {value =''}"/>
	    <input name="btnPilih" type="submit" style="cursor:pointer;" value=" Pilih " />
	  </b></td>
    </tr>
	<tr>
	  <td>&nbsp;</td>
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
    <th colspan="7">DAFTAR  ITEM JASA</th>
    </tr>
  <tr>
    <td width="26" align="center" bgcolor="#CCCCCC"><b>No</b></td>
    <td width="66" align="center" bgcolor="#CCCCCC"><b>Kode</b></td>
    <td width="335" bgcolor="#CCCCCC"><b>Nama Barang/ Bahan Baku</b></td>
    <td width="97" align="right" bgcolor="#CCCCCC"><b>Harga  (Rp)</b></td>
    <td width="41" align="center" bgcolor="#CCCCCC"><b>Qty</b></td>
    <td width="97" align="right" bgcolor="#CCCCCC"><b>Subtotal (Rp)</b></td>
    <td width="52" align="center" bgcolor="#FFCC00"><b>Delete</b></td>
  </tr>
<?php

$tmpSql = "	SELECT * FROM tmp_jasa
			WHERE userid='".$_SESSION['SES_LOGIN']."'
			GROUP BY id
 			ORDER BY id ASC ";
$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());

$total = 0; $qtyBrg = 0; $nomor=0;
while($tmpRow = mysql_fetch_array($tmpQry)) {
$sTempjasa	= " SELECT * FROM jasa WHERE kd_jasa ='".$tmpRow['kd_jasa']."'";
$qTempjasa	= mysql_query($sTempjasa, $koneksidb) or die ("Gagal Query Tmp Barang".mysql_error());
$rTempjasa	= mysql_fetch_array($qTempjasa);

$sTempbahan	= " SELECT * FROM bahanbaku WHERE kd_bahan ='".$tmpRow['kd_bahan']."'";
$qTempbahan	= mysql_query($sTempbahan, $koneksidb) or die ("Gagal Query Tmp Bahan".mysql_error());
$rTempbahan	= mysql_fetch_array($qTempbahan);

	$ID		= $tmpRow['id'];
	$subSotal = $tmpRow['jml_bahan'] * intval($tmpRow['harga_bahan']+$tmpRow['harga_jasa']);
	$total 	= $total + $subSotal;
	$qtyBrg = $qtyBrg + $tmpRow['jml_bahan'];
	
	$nomor++;
?>
  <tr>
    <td align="center"><b><?php echo $nomor; ?></b></td>
    <td align="center"><b><?php echo $rTempjasa['kd_jasa'].$rTempbahan['kd_bahan']; ?></b></td>
    <td><?php echo $rTempjasa['nama_jasa'].$rTempbahan['nm_bahan']; ?></td>
    <td align="right"><?php echo format_angka($tmpRow['harga_jasa'].$tmpRow['harga_bahan']); ?></td>
    <td align="center"><?php echo $tmpRow['jml_bahan']; ?></td>
    <td align="right"><?php echo format_angka($subSotal); ?></td>
    <td align="center" bgcolor="#FFFFCC"><a href="?page=Transaksi-Jasa&Act=Delete&ID=<?php echo $ID; ?>" target="_self"><img src="images/hapus.gif" width="16" height="16" border="0" /></a></td>
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
