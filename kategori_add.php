<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if($_GET) {
	if(isset($_POST['btnSave'])){
		# Validasi form, jika kosong sampaikan pesan error
		$message = array();
		if (trim($_POST['txtKategori'])=="") {
			$message[] = "<b>Nama Kategori</b> tidak boleh kosong !";		
		}
		
		# Baca Variabel Form
		$txtKategori= $_POST['txtKategori'];
		$txtKategori= str_replace("'","&acute;",$txtKategori);
		$cmbJenis= $_POST['cmbJenis'];
		# Validasi Nama Kategori, jika sudah ada akan ditolak
		$sqlCek="SELECT * FROM kategori WHERE nm_kategori='$txtKategori'";
		$qryCek=mysql_query($sqlCek, $koneksidb) or die ("Eror Query".mysql_error()); 
		if(mysql_num_rows($qryCek)>=1){
			$message[] = "Maaf, Kategori <b> $txtKategori </b> sudah ada, ganti dengan yang lain";
		}

		# SIMPAN DATA KE DATABASE. Jika jumlah error message tidak ada, simpan datanya
		if(count($message)==0){			
			$NewID	= buatKode("kategori", "K");
			$qrySave=mysql_query("INSERT INTO kategori SET kd_kategori='$NewID', nm_kategori='$txtKategori', jns_kategori='$cmbJenis'") 
					 or die ("Gagal query ".mysql_error());
			if($qrySave){
				echo "<meta http-equiv='refresh' content='0; url=?page=Data-Kategori'>";
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
	// Supaya saat ada pesan error, data di dalam form tidak hilang. Jadi, tinggal meneruskan/memperbaiki yg salah
	$dataKode	= buatKode("kategori", "K");
	$dataKategori	= isset($_POST['txtKategori']) ? $_POST['txtKategori'] : '';
} // Penutup GET
?>
<form action="?page=Add-Kategori" method="post" name="frmadd" target="_self">
<table class="table-list" width="100%" style="margin-top:0px;">
	<tr>
	  <th colspan="3">TAMBAH DATA KATEGORI </th>
	</tr>
	<tr>
	  <td width="15%"><b>Kode</b></td>
	  <td width="1%"><b>:</b></td>
	  <td width="84%"><input name="txtKode" value="<?php echo $dataKode; ?>" size="10" maxlength="4" readonly="readonly"/></td></tr>
	<tr>
	  <td><b>Nama Kategori </b></td>
	  <td><b>:</b></td>
	  <td><input name="txtKategori" value="<?php echo $dataKategori; ?>" size="80" maxlength="100" /></td>
	</tr>
	<tr>
	  <td><strong>Jenis Kategori</strong></td>
	  <td><strong>:</strong></td>
	  <td><label>
	    <select name="cmbJenis" id="cmbJenis">
	      <option value="1">Barang</option>
	      <option value="2">Jasa</option>
	      </select>
	  </label></td>
    </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><input type="submit" name="btnSave" value=" SIMPAN " style="cursor:pointer;" /></td>
    </tr>
</table>
</form>