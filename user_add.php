<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

# Opsi Level Login
$arrLevel	= array("Kasir","Admin");

if($_GET) {
	if(isset($_POST['btnSave'])){
		$message = array();
		if (trim($_POST['txtNama'])=="") {
			$message[] = "Nama lengkap boleh kosong !";		
		}
		if (trim($_POST['txtUser'])=="") {
			$message[] = "User tidak boleh kosong !";		
		}
		if (trim($_POST['txtPassword'])=="") {
			$message[] = "Password tidak boleh kosong !";		
		}
		if (trim($_POST['cmbLevel'])=="BLANK") {
			$message[] = "Level sistem belum dipilih !";		
		}
		
		# Baca Variabel Form
		$txtNama	= $_POST['txtNama'];
		$txtNama 	= str_replace("'","&acute;",$txtNama);
		$txtUser	= $_POST['txtUser'];
		$txtUser 	= str_replace("'","&acute;",$txtUser);
		$txtPassword= $_POST['txtPassword'];
		$txtPassword= str_replace("'","&acute;",$txtPassword);
		$cmbLevel	= $_POST['cmbLevel'];

		# Validasi Nama Kategori, jika sudah ada akan ditolak
		$sqlCek="SELECT * FROM user_login WHERE userid='$txtUser'";
		$qryCek=mysql_query($sqlCek, $koneksidb) or die ("Eror Query".mysql_error()); 
		if(mysql_num_rows($qryCek)>=1){
			$message[] = "Maaf, User <b> $txtUser </b> sudah ada, ganti dengan yang lain";
		}
		
		# Jika jumlah error message tidak ada
		if(count($message)==0){			
			$sqlSave="INSERT INTO user_login SET nama='$txtNama', userid='$txtUser', password ='".md5($txtPassword)."', level='$cmbLevel'";
			$qrySave=mysql_query($sqlSave, $koneksidb) or die ("Gagal query".mysql_error());
			if($qrySave){
				echo "<meta http-equiv='refresh' content='0; url=?page=Data-User&Act=Sucsses'>";
			}
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
	$dataNama	= isset($_POST['txtNama']) ? $_POST['txtNama'] : '';
	$dataUser	= isset($_POST['txtUser']) ? $_POST['txtUser'] : '';
	$dataLevel	= isset($_POST['cmbLevel']) ? $_POST['cmbLevel'] : '';
} // Penutup GET
?>
<form action="" method="post" name="frmadd">
<table class="table-common" width="100%" style="margin-top:0px;">
	<tr>
	  <th colspan="3">TAMBAH DATA USER LOGIN </th>
	</tr>
	<tr>
      <td><strong>Nama Lengkap </strong></td>
	  <td><b>:</b></td>
	  <td><input name="txtNama" value="<?php echo $dataNama; ?>"  size="60" maxlength="60"/></td>
    </tr>
	<tr>
	  <td width="15%"><strong>User ID </strong></td>
	  <td width="1%"><b>:</b></td>
	  <td width="84%"><input name="txtUser"  value="<?php echo $dataUser; ?>"  size="30" maxlength="30"/></td></tr>
	<tr>
	  <td><strong>Password</strong></td>
	  <td><b>:</b></td>
	  <td><input name="txtPassword" type="password"  value="" size="30" maxlength="30"/></td>
	</tr>
	<tr>
	  <td><strong>Level</strong></td>
	  <td><b>:</b></td>
	  <td><select name="cmbLevel">
        <option value="BLANK"> </option>
        <?php
          foreach ($arrLevel as $index => $value) {
            if ($value==$dataLevel) {
                $cek="selected";
            } else { $cek = ""; }
            echo "<option value='$value' $cek>$value</option>";
          }
          ?>
      </select></td>
    </tr>
	<tr><td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><input type="submit" name="btnSave" value=" SIMPAN " style="cursor:pointer;"></td>
    </tr>
</table>
</form>
