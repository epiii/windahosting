<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if($_GET) {
	if(isset($_POST['btnSave'])){
		# Validasi form, jika kosong sampaikan pesan error
		$message = array();
		if (trim($_POST['txtPelanggan'])=="") {
			$message[] = "<b>Nama Pelanggan</b> tidak boleh kosong !";		
		}
		if (trim($_POST['txtAlamat'])=="") {
			$message[] = "<b>Alamat Lengkap</b> tidak boleh kosong !";		
		}
		if (trim($_POST['txtTelepon'])=="") {
			$message[] = "<b>No Telpon</b> tidak boleh kosong !";		
		}
		if (trim($_POST['txtKPos'])=="") {
			$message[] = "<b>Kode Pos</b> tidak boleh kosong !";		
		}
		
		# Baca Variabel Form
		$txtPelanggan = $_POST['txtPelanggan'];
		$txtPelanggan= str_replace("'","&acute;",$txtPelanggan);
		$txtAlamat	= $_POST['txtAlamat'];
		$txtAlamat	= str_replace("'","&acute;",$txtAlamat);
		$txtTelepon	= $_POST['txtTelepon'];
		$txtTelepon	= str_replace("'","&acute;",$txtTelepon);
		$txtKPos	= $_POST['txtKPos'];
		$txtKPos	= str_replace("'","&acute;",$txtKPos);
		
		# Validasi Nama Supplier, jika sudah ada akan ditolak
		$sqlCek="SELECT * FROM pelanggan WHERE nm_pelanggan='$txtPelanggan'";
		$qryCek=mysql_query($sqlCek, $koneksidb) or die ("Eror Query".mysql_error()); 
		if(mysql_num_rows($qryCek)>=1){
			$message[] = "Maaf, pelanggan <b> $txtPelanggan </b> sudah ada, ganti dengan yang lain";
		}

		# Jika jumlah error message tidak ada, simpan datanya
		if(count($message)==0){			
			$kodeBaru	= buatKode("pelanggan", "P");
			$qrySave=mysql_query("INSERT INTO pelanggan SET kd_pelanggan='$kodeBaru', nm_pelanggan='$txtPelanggan',
								  alamat='$txtAlamat', telepon='$txtTelepon',kode_pos='$txtKPos'") or die ("Gagal query".mysql_error());
			if($qrySave){
				echo "<meta http-equiv='refresh' content='0; url=?page=Data-Pelanggan'>";
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
	$dataKode	= buatKode("pelanggan", "P");
	$dataPelanggan	= isset($_POST['txtPelanggan']) ? $_POST['txtPelanggan'] : '';
	$dataAlamat = isset($_POST['txtAlamat']) ? $_POST['txtAlamat'] : '';
	$dataTelepon = isset($_POST['txtTelepon']) ? $_POST['txtTelepon'] : '';
	$dataKPos = isset($_POST['txtKPos']) ? $_POST['txtKPos'] : '';
	
} // Penutup GET
?>
<form action="?page=Add-Pelanggan" method="post" name="frmadd">
<table width="101%" cellpadding="2" cellspacing="1" class="table-list" style="margin-top:0px;">
	<tr>
	  <th colspan="3">TAMBAH DATA PELANGGAN </th>
	</tr>
	<tr>
	  <td width="15%"><b>Kode</b></td>
	  <td width="1%"><b>:</b></td>
	  <td width="84%"><input name="txtKode" value="<?php echo $dataKode; ?>" size="10" maxlength="4" readonly="readonly"/></td></tr>
	<tr>
	  <td><b>Nama Pelanggan </b></td>
	  <td><b>:</b></td>
	  <td><input name="txtPelanggan" value="<?php echo $dataPelanggan; ?>" size="80" maxlength="100" /></td>
	</tr>
	<tr>
      <td><b>Alamat Lengkap </b></td>
	  <td><b>:</b></td>
	  <td><input name="txtAlamat" value="<?php echo $dataAlamat; ?>" size="80" maxlength="200" /></td>
    </tr>
	<tr>
      <td><b>No Telpon </b></td>
	  <td><b>:</b></td>
	  <td><input name="txtTelepon" value="<?php echo $dataTelepon; ?>" size="20" maxlength="20" /></td>
    </tr>
	<tr>
      <td><b>Kode Pos </b></td>
	  <td><b>:</b></td>
	  <td><input name="txtKPos" value="<?php echo $dataKPos; ?>" size="20" maxlength="20" /></td>
    </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><input type="submit" name="btnSave" value=" SIMPAN " style="cursor:pointer;" /></td>
    </tr>
</table>
</form>
