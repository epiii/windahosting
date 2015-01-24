<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if($_GET) {
	if(isset($_POST['btnSave'])){
		# Validasi form, jika kosong sampaikan pesan error
		$message = array();
		if (trim($_POST['txtSupplier'])=="") {
			$message[] = "Nama Supplier tidak boleh kosong !";		
		}
		if (trim($_POST['txtAlamat'])=="") {
			$message[] = "<b>Alamat Lengkap</b> tidak boleh kosong !";		
		}
		if (trim($_POST['txtTelpon'])=="") {
			$message[] = "<b>No Telpon</b> tidak boleh kosong !";		
		}
		
		# Baca Variabel Form
		$txtSupplier= $_POST['txtSupplier'];
		$txtSupplier= str_replace("'","&acute;",$txtSupplier);
		$txtLama	= $_POST['txtLama'];
		$txtAlamat	= $_POST['txtAlamat'];
		$txtAlamat	= str_replace("'","&acute;",$txtAlamat);
		$txtTelpon	= $_POST['txtTelpon'];
		$txtTelpon	= str_replace("'","&acute;",$txtTelpon);
		
		# Validasi Nama Supplier, jika sudah ada akan ditolak
		$sqlCek="SELECT * FROM supplier WHERE nm_supplier='$txtSupplier' AND NOT(nm_supplier='$txtLama')";
		$qryCek=mysql_query($sqlCek, $koneksidb) or die ("Eror Query".mysql_error()); 
		if(mysql_num_rows($qryCek)>=1){
			$message[] = "Maaf, Supplier <b> $txtSupplier </b> sudah ada, ganti dengan yang lain";
		}
		
		# Jika jumlah error message tidak ada, simpan datanya
		if(count($message)==0){	
			$qryUpdate=mysql_query("UPDATE supplier SET nm_supplier='$txtSupplier', alamat='$txtAlamat',
								   telepon='$txtTelpon' WHERE kd_supplier ='".$_POST['txtKode']."'") 
					   or die ("Gagal query update".mysql_error());
			if($qryUpdate){
				echo "<meta http-equiv='refresh' content='0; url=?page=Data-Supplier'>";
			}
			exit;
		}	
		
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
	} // Penutup POST
	
	# TAMPILKAN DATA LOGIN UNTUK DIEDIT
	$KodeEdit= isset($_GET['Kode']) ?  $_GET['Kode'] : $_POST['txtKode']; 
	$sqlShow = "SELECT * FROM supplier WHERE kd_supplier='$KodeEdit'";
	$qryShow = mysql_query($sqlShow, $koneksidb)  or die ("Query ambil data supplier salah : ".mysql_error());
	$dataShow = mysql_fetch_array($qryShow); 
	
	# MASUKKAN DATA KE VARIABEL
	$dataKode	= $dataShow['kd_supplier'];
	$dataNama	= isset($dataShow['nm_supplier']) ?  $dataShow['nm_supplier'] : $_POST['txtSupplier'];
	$dataLama	= $dataShow['nm_supplier'];
	$dataAlamat = isset($dataShow['alamat']) ?  $dataShow['alamat'] : $_POST['txtAlamat'];
	$dataTelpon = isset($dataShow['telepon']) ?  $dataShow['telepon'] : $_POST['txtTelpon'];
} // Penutup GET
?>
<form action="" method="post" name="frmedit">
<table class="table-list" width="100%" style="margin-top:0px;">
	<tr>
	  <th colspan="3">UBAH DATA SUPPLIER </th>
	</tr>
	<tr>
	  <td width="15%"><b>Kode</b></td>
	  <td width="1%"><b>:</b></td>
	  <td width="84%"><input name="txtLock" value="<?php echo $dataKode; ?>" size="8" maxlength="4"  readonly="readonly"/>
    <input name="txtKode" type="hidden" value="<?php echo $dataKode; ?>" /></td></tr>
	<tr>
	  <td><b>Nama Supplier </b></td>
	  <td><b>:</b></td>
	  <td><input name="txtSupplier" type="text" value="<?php echo $dataNama; ?>" size="80" maxlength="100" />
      <input name="txtLama" type="hidden" value="<?php echo $dataLama; ?>" /></td></tr>
	<tr>
      <td><b>Alamat Lengkap </b></td>
	  <td><b>:</b></td>
	  <td><input name="txtAlamat" value="<?php echo $dataAlamat; ?>" size="80" maxlength="200" /></td>
    </tr>
	<tr>
      <td><b>No Telpon </b></td>
	  <td><b>:</b></td>
	  <td><input name="txtTelpon" value="<?php echo $dataTelpon; ?>" size="20" maxlength="20" /></td>
    </tr>
	<tr><td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><input type="submit" name="btnSave" value=" SIMPAN " style="cursor:pointer;"></td>
    </tr>
</table>
</form>

