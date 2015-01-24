<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if($_GET) {
	if(isset($_POST['btnSave'])){
		# Validasi form, jika kosong sampaikan pesan error
		$message = array();
		if (trim($_POST['txtSupplier'])=="") {
			$message[] = "<b>Nama Supplier</b> tidak boleh kosong !";		
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
		$txtAlamat	= $_POST['txtAlamat'];
		$txtAlamat	= str_replace("'","&acute;",$txtAlamat);
		$txtTelpon	= $_POST['txtTelpon'];
		$txtTelpon	= str_replace("'","&acute;",$txtTelpon);
		
		# Validasi Nama Supplier, jika sudah ada akan ditolak
		$sqlCek="SELECT * FROM supplier WHERE nm_supplier='$txtSupplier'";
		$qryCek=mysql_query($sqlCek, $koneksidb) or die ("Eror Query".mysql_error()); 
		if(mysql_num_rows($qryCek)>=1){
			$message[] = "Maaf, supplier <b> $txtSupplier </b> sudah ada, ganti dengan yang lain";
		}

		# Jika jumlah error message tidak ada, simpan datanya
		if(count($message)==0){			
			$kodeBaru	= buatKode("supplier", "S");
			$qrySave=mysql_query("INSERT INTO supplier SET kd_supplier='$kodeBaru', nm_supplier='$txtSupplier',
								  alamat='$txtAlamat', telepon='$txtTelpon'") or die ("Gagal query".mysql_error());
			if($qrySave){
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
		
	# MASUKKAN DATA KE VARIABEL
	$dataKode	= buatKode("supplier", "S");
	$dataNama	= isset($_POST['txtSupplier']) ? $_POST['txtSupplier'] : '';
	$dataAlamat = isset($_POST['txtAlamat']) ? $_POST['txtAlamat'] : '';
	$dataTelpon = isset($_POST['txtTelpon']) ? $_POST['txtTelpon'] : '';
} // Penutup GET
?>
<form action="?page=Add-Supplier" method="post" name="frmadd">
<table width="101%" cellpadding="2" cellspacing="1" class="table-list" style="margin-top:0px;">
	<tr>
	  <th colspan="3">TAMBAH DATA SUPPLIER </th>
	</tr>
	<tr>
	  <td width="15%"><b>Kode</b></td>
	  <td width="1%"><b>:</b></td>
	  <td width="84%"><input name="txtKode" value="<?php echo $dataKode; ?>" size="10" maxlength="4" readonly="readonly"/></td></tr>
	<tr>
	  <td><b>Nama Supplier </b></td>
	  <td><b>:</b></td>
	  <td><input name="txtSupplier" value="<?php echo $dataNama; ?>" size="80" maxlength="100" /></td>
	</tr>
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
	  <td><input type="submit" name="btnSave" value=" SIMPAN " style="cursor:pointer;" /></td>
    </tr>
</table>
</form>
