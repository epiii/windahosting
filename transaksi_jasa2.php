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
	// =========================================================================
	
	if($_POST) {
	# TOMBOL PILIH (KODE jasa) DIKLIK
	if(isset($_POST['btnPilih'])){
		$message = array();
		if (trim($_POST['txtKode'])=="") {
			$message[] = "<b>Kode Jasa belum diisi</b>,!";		
		}
		if (trim($_POST['txtJumlah'])=="" OR ! is_numeric(trim($_POST['txtJumlah']))) {
			$message[] = "Data <b>Jumlah Bahan Baku (Qty) belum diisi</b>, silahkan <b>isi dengan angka</b> !";		
		}
		
		# Baca variabel
		$txtKode	= $_POST['txtKode'];
		$txtKode	= str_replace("'","&acute;",$txtKode);
		$txtJumlah	= $_POST['txtJumlah'];
		$txtJumlah	= str_replace("'","&acute;",$txtJumlah);
		
		# Jika jumlah error message tidak ada
		if(count($message)==0){			 
				$jasaSql ="SELECT * FROM jasa WHERE kd_jasa='$txtKode'";
				$jasaQry = mysql_query($jasaSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				$jasaRow = mysql_fetch_array($jasaQry);
				$jasaQty = mysql_num_rows($jasaQry);
				
			// memilih bahan baku 
				$bahanSql ="SELECT * FROM bahanbaku WHERE kd_bahan='$jasaRow[kd_bahan]'";
				$bahanQry = mysql_query($bahanSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				$bahanRow = mysql_fetch_array($bahanQry);
				$bahanQty = mysql_num_rows($bahanQry);
			
				if ($jasaQty >= 1) {
					
					$tmpSql = "INSERT INTO tmp_jasa 
							   SET 	kd_jasa='$jasaRow[kd_jasa]', 
							   		harga_jasa='$jasaRow[harga_jasa]', 
									kd_bahan='$bahanRow[kd_bahan]',  
									jml_bahan='$txtJumlah', 
									userid='".$_SESSION['SES_LOGIN']."'";
					mysql_query($tmpSql, $koneksidb) or die ("Gagal Insert Transaksi Jasa : ".mysql_error());
					$txtKode	= "";
					$txtHarga	= "";
				}
				else {
					$message[] = "Tidak ada barang dengan kode <b>$txtKode'</b>, silahkan ganti";
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
		$tmpSql ="SELECT COUNT(*) As id FROM tmp_jasa WHERE userid='".$_SESSION['SES_LOGIN']."'";
		$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
		$tmpRow = mysql_fetch_array($tmpQry);
		#if ($tmpRow['jml_bahan'] < 1) {
			#$message[] = "<b>Item Bahan Baku</b> belum ada yang dimasukan, <b>minimal 1 bahan baku</b>.";
		#}
		
		# Baca variabel
		$txtKode= $_POST['txtKode'];
		$cmbPelanggan= $_POST['cmbPelanggan'];
		$cmbPelanggan= str_replace("'","&acute;",$cmbPelanggan);
		$txtCatatan	= $_POST['txtCatatan'];
		$txtCatatan = str_replace("'","&acute;",$txtCatatan);
		$cmbTanggal =$_POST['cmbTanggal'];
				
		# Jika jumlah error message tidak ada
		if(count($message)==0){			
			$kodeBaru	= buatKode("transaksi", "TJ");
			$qrySave=mysql_query("INSERT INTO transaksi SET no_transaksi='$kodeBaru', tgl_transaksi='".InggrisTgl($_POST['cmbTanggal'])."', 
								  kd_pelanggan='$cmbPelanggan', catatan='$txtCatatan', userid='".$_SESSION['SES_LOGIN']."'") or die ("Gagal query".mysql_error());
			if($qrySave){
				# Ambil semua data jasa yang dipilih, berdasarkan kasir yg login
				$tmpSql ="SELECT * FROM tmp_jasa WHERE userid='".$_SESSION['SES_LOGIN']."'";
				$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				while ($tmpRow = mysql_fetch_array($tmpQry)) {
					// Masukkan semua jasa yang udah diisi ke tabel penjualan detail
					$itemSql = "INSERT INTO jasa_item SET no_transaksi='$kodeBaru', kd_jasa='$tmpRow[kd_jasa]', kd_bahan='$tmpRow[kd_bahan]',
								harga_jasa='$tmpRow[harga_jasa]', jml_bahan='$tmpRow[jml_bahan]'";

		  			mysql_query($itemSql, $koneksidb) or die ("Gagal Query Simpan detail jasa".mysql_error());
					
					 #Update qty
					 
					$qBAHAN = mysql_query("SELECT * FROM bahanbaku WHERE kd_bahan='$tmpRow[kd_bahan]'");
					$rBAHAN	= mysql_fetch_array($qBAHAN);
					$editJml=$rBAHAN['jml_bahan'] - $tmpRow['jml_bahan'];
					$bahanSql = " UPDATE bahanbaku 
								  SET jml_bahan='".$editJml."'
								  WHERE kd_bahan='$tmpRow[kd_bahan]'";
		  			mysql_query($bahanSql, $koneksidb) or die ("Gagal Query Edit Qty".mysql_error());
				}
				
				//Ambil Data Bahan Baku
				$tmpSql ="SELECT * FROM tmp_jasa WHERE userid='".$_SESSION['SES_LOGIN']."' and kd_bahan <>''";
				$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
				while ($tmpRow = mysql_fetch_array($tmpQry)) {
 
 
						$bahanSql = " INSERT INTO jasa_item SET no_transaksi='$kodeBaru', 
									  kd_bahan='$tmpRow[kd_bahan]', harga_jasa='$tmpRow[harga_jasa]',
									  jml_bahan='$tmpRow[jml_bahan]'";
						mysql_query($bahanSql, $koneksidb) or die ("Gagal Query Simpan detail Bahan Baku".mysql_error());
	
		 
				}
				
				# Kosongkan Tmp jika datanya sudah dipindah
				mysql_query("DELETE FROM tmp_jasa WHERE userid='".$_SESSION['SES_LOGIN']."'", $koneksidb) or die ("Gagal kosongkan tmp".mysql_error());
				
				// Refresh form
				echo "<meta http-equiv='refresh' content='0; url=nota_jasa.php?noNota=$kodeBaru'>";
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
$nomorTransaksi = buatKode("transaksi", "TJ");
$tglTransaksi 	= isset($_POST['cmbTanggal']) ? $_POST['cmbTanggal'] : date('d-m-Y');
$dataPelanggan	= isset($_POST['txtPelanggan']) ? $_POST['txtPelanggan']:'';
$dataCatatan	= isset($_POST['txtCatatan']) ? $_POST['txtCatatan'] : '';
?>
<form action="" method="post"  name="frmadd">
<table width="750" cellspacing="1" class="table-common" style="margin-top:0px;">
	<tr>
	  <td colspan="3" align="right"><h1 align="center">TRANSAKSI JASA</h1> </td>
	</tr>
	<tr>
	  <td width="20%"><b>No Transaksi Jasa</b></td>
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
	  <td><label>
	    <select name="cmbPelanggan">
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
      <td><b>Catatan</b></td>
	  <td><b>:</b></td>
	  <td><input name="txtCatatan" value="<?php echo $dataCatatan; ?>" size="30" maxlength="100" /></td>
    </tr>
	<tr><td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
    </tr>
	<tr>
	  <td><b>Kode Jasa</b></td>
	  <td><b>:</b></td>
	  <td><b>
       <input name="txtKode" class="angkaC" size="14" maxlength="20" />
	    Qty (Bahan Baku)
	  :
 <input class="angkaC" name="txtJumlah" size="2" maxlength="4" value="1" 
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
    <th colspan="8">DAFTAR JASA </th>
  </tr>
  <tr>
    <td width="34" align="center" bgcolor="#CCCCCC"><div align="center"><b>No</b></div></td>
    <td width="78" align="center" bgcolor="#CCCCCC"><b>Kode</b></td>
    <td width="342" bgcolor="#CCCCCC"><b>Nama Jasa</b></td>
    <td width="107" align="right" bgcolor="#CCCCCC"><b>Harga</b></td>
    <td width="95" align="right" bgcolor="#CCCCCC"><b>Subtotal</b></td>
    <td width="63" align="center" bgcolor="#FFCC00"><b>Delete</b></td>
  </tr>
  <?php
$tmpSql = "	SELECT jasa.*, tmp_jasa.id, tmp_jasa.harga_jasa,tmp_jasa.jml_bahan
			FROM jasa, tmp_jasa
			WHERE jasa.kd_jasa=tmp_jasa.kd_jasa AND tmp_jasa.userid='".$_SESSION['SES_LOGIN']."'
			ORDER BY jasa.kd_jasa ";
$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
$total = 0; $qtyBrg = 0; $nomor=0;
while($tmpRow = mysql_fetch_array($tmpQry)) {
	$ID		= $tmpRow['id'];
	$subtotal = $tmpRow['jml_bahan'] * $tmpRow['harga_jasa'];
	$total 	= $total + intval($subtotal);
	
	$nomor++;
	
$sTEMPBRG ="SELECT * FROM bahanbaku WHERE kd_bahan='$tmpRow[kd_bahan]'";
$qTEMPBRG = mysql_query($sTEMPBRG, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
$rTEMPBRG = mysql_fetch_array($qTEMPBRG);	
?>
  <tr>
    <td align="center"><b><?php echo $nomor; ?></b></td>
    <td align="center"><b><?php echo $tmpRow['kd_jasa']; ?></b></td>
    <td>
		<?php echo $tmpRow['nama_jasa']; ?><br />
		<?php echo "(Bahan Baku: ".$rTEMPBRG['nm_bahan']."/Qty: ".$tmpRow['jml_bahan'].")";?></td>
    <td align="right"><?php echo format_angka($tmpRow['harga_jasa']); ?></td>
    <td align="right"><?php echo format_angka($subtotal); ?></td>
    <td align="center" bgcolor="#FFFFCC"><a href="?page=Transaksi-Jasa&Act=Delete&ID=<?php echo $ID; ?>" target="_self"><img src="images/hapus.gif" width="16" height="16" border="0" /></a></td>
  </tr>
  <?php 
}?>
  <tr>
    <td colspan="4" align="right"><b>Grand Total : </b></td>
    <td align="right"><b><?php echo format_angka($total); ?></b></td>
    <td align="right">&nbsp;</td>
  </tr>
</table>
</form>
