<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if($_GET){
	if(isset($_POST['btnSave'])){
		
		$message = array();
		if(trim($_POST['txtJnsJasa'])==""){
			$message[] = "<b>Jenis Jasa</b> tidak kosong!!";
		}
		if (trim($_POST['txtHarga'])=="" OR ! is_numeric(trim($_POST['txtHarga']))) {
			$message[] = "<b>Harga</b> Jasa tidak boleh kosong, harus diisi angka !";		
		}
		if(trim($_POST['cmbBahan'])=="BLANK") {
			$message[] = "<b> Bahan Baku </b> Jasa belum dipilih!";
		}
		if(trim($_POST['cmbKategori'])=="BLANK"){
			$message[] = "<b> Kategori Jasa </b> belum dipilih!";
		}
		
		#Validasi Nama Barang, jika sudah ada akan ditolak
		$sqlCek="SELECT * FROM jasa WHERE nama_jasa='txtJnsJasa'";
		$qryCek=mysql_query($sqlCek, $koneksidb) or die ("Error Query".mysql_error());
		if(mysql_num_rows($qryCek)>=1){
			$message[] = " Maaf, Jenis Jasa <b> $txtJnsJasa </b> sudah ada, ganti dengan yang lain";
		}
		
		# Validasi Diskon, rugi atau laba
		//if (! trim($_POST['txtHargaBeli'])=="" AND ! trim($_POST['txtHargaJual'])=="") {
			//$besarDiskon = intval($txtHargaJual) * (intval($txtDiskon)/100);
			//$hargaDiskon = intval($txtHargaJual) - $besarDiskon;
			//if (intval($txtHargaBeli) >= $hargaDiskon ){
				//$message[] = "<b>Harga Jual</b> masih salah, terhitung <b> Anda merugi </b> ! <br>
								//&nbsp; Harga belum diskon : Rp. ".format_angka($txtHargaJual)." <br>
								//&nbsp; Diskon ($txtDiskon %) : Rp.  ".format_angka($besarDiskon)." <br>
								//&nbsp; Harga sudah diskon : Rp. ".format_angka($hargaDiskon).", 
								//Sedangkan modal Anda Rp. ".format_angka($txtHargaBeli)."<br>
								//&nbsp; <b>Solusi :</b> Anda harus <b>mengurangi besar % Diskon</b>, atau <b>Menaikan Harga Jual</b>.";		
			//}
		//}
			#Baca Variabel Form
			$txtJnsJasa = $_POST['txtJnsJasa'];
			$txtJnsJasa = str_replace("'","&acute;",$txtJnsJasa); 
			$txtHarga = $_POST['txtHarga'];
			$txtHarga= str_replace("'","&acute;",$txtHarga);
			$txtHarga = str_replace(".","",$txtHarga);
			$txtKeterangan	= $_POST['txtKeterangan'];
			$txtKeterangan	= str_replace("'","&acute;",$txtKeterangan);
			$cmbKategori = $_POST['cmbKategori'];
			$cmbBahan	= $_POST['cmbBahan'];
	
	
		#Simpan Data ke Database
		if(count($message)==0){
			$kodeBaru = buatKode("jasa", "J");
			$qrySave=mysql_query("INSERT INTO jasa SET kd_jasa='$kodeBaru', nama_jasa='$txtJnsJasa', kd_bahan='$cmbBahan', harga_jasa='$txtHarga', kd_kategori='$cmbKategori'") or die ("Gagal query ".mysql_error());
			
		if($qrySave){
			echo "<meta http-equiv='refresh' content='0; url=?page=Data-Jasa'>";
		}
		exit;
	}
		#JIKA ADA PESAN ERROR DARI VALIDASI
		// (Form Kosong, atau Duplikat ada), Ditampilkan lewat kode ini
		if (! count($message)==0){
			echo "<div class ='mssgBox'>";
			echo "<img src='images/attention.png' class='imgBox'> <hr>";
			$Num=0;
			foreach ($message as $indeks=>$pesan_tampil){
				$Num++;
				echo "&nbsp;&nbsp;$Num. $pesan_tampil<br>";
			}
			echo "</div> <br>";
		}
	
	

	
}
		#Masukkan Data ke Variabel
		$dataKode= buatKode("jasa", "J");
		$dataJasa= isset($_POST['txtJnsJasa']) ? $_POST['txtJnsJasa'] : ''; 
	    $dataHarga 	= isset($_POST['txtHarga']) ? $_POST['txtHarga'] : '';
		$dataKeterangan	= isset($_POST['txtKeterangan']) ? $_POST['txtKeterangan'] : '';
		$dataKategori= isset($_POST['cmbKategori']) ? $_POST['cmbKategori'] : '';
		$dataBahan	= isset($_POST['cmbBahan']) ? $_POST['cmbBahan'] : '';

}
?>
<form action="?page=Add-Jasa" method="post" enctype="multipart/form-data" name="form1" target="_self" id="form1">
  <table width="67%" border="0" cellspacing="1" cellpadding="2">
    <tr>
      <th colspan="3" scope="col">TAMBAH DATA JASA</th>
    </tr>
    <tr>
      <td width="22%"><strong>Kode Jasa</strong></td>
      <td width="1%"><strong>:</strong></td>
      <td width="77%"><strong>
        <input name="txtKode" value="<?php echo $dataKode; ?>" size="10" maxlength="4" readonly="readonly" />
      </strong></td>
    </tr>
    <tr>
      <td><strong>Nama Jasa</strong></td>
      <td><strong>:</strong></td>
      <td><strong>
        <input name="txtJnsJasa" value="<?php echo $dataJasa; ?>" size="50" maxlength="50" />
      </strong></td>
    </tr>
    <tr>
      <td><strong>Bahan Baku</strong></td>
      <td><strong>:</strong></td>
      <td><strong>
        <label>
          <select name="cmbBahan" id="cmbBahan">
            <option value="BLANK"></option>
            <?php
			$dataSql = " SELECT * FROM bahanbaku ORDER BY nm_bahan";
			$dataQry = mysql_query($dataSql, $koneksidb) or die ("Gagal Query".mysql_error());
			while ($dataRow = mysql_fetch_array($dataQry)){
				if ($dataRow['kd_bahan']==$_POST['cmbBahan']){
					$cek = "selected";
				} else { $cek=="";}
				echo "<option value='$dataRow[kd_bahan]' $cek>$dataRow[nm_bahan] (Rp. ".format_angka($dataRow[harga_bahan]).",-)</option>";
			}
			$sqlData = "";
			?>
          </select>
        </label>
      </strong></td>
    </tr>
    <tr>
      <td><strong>Harga Jasa </strong></td>
      <td><strong>:</strong></td>
      <td><strong>
        <label>
          <input name="txtHarga" type="text" id="txtHarga" value="<?php echo $dataHarga; ?>" size="20" maxlength="10" />
        </label>
      </strong></td>
    </tr>
    <tr>
      <td><strong>Keterangan</strong></td>
      <td><strong>:</strong></td>
      <td><strong>
        <label>
          <input name="txtKeterangan" type="text" id="txtKeterangan" value="<?php echo $dataKeterangan; ?>" size="20" maxlength="200" />
        </label>
      </strong></td>
    </tr>
    <tr>
      <td><strong>Kategori Jasa</strong></td>
      <td><strong>:</strong></td>
      <td><strong>
        <label>
          <select name="cmbKategori" id="cmbKategori">
            <option value="BLANK"></option>
            <?php
			$dataSql = " SELECT * FROM kategori WHERE jns_kategori=2 ORDER BY kd_kategori";
			$dataQry = mysql_query($dataSql, $koneksidb) or die ("Gagal Query".mysql_error());
			while ($dataRow = mysql_fetch_array($dataQry)){
				if ($dataRow['kd_kategori']==$_POST['cmbKategori']){
					$cek = "selected";
				} else { $cek=="";}
				echo "<option value='$dataRow[kd_kategori]' $cek>$dataRow[nm_kategori]</option>";
			}
			$sqlData = "";
			?>
          </select>
        </label>
      </strong></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><label>
        <input type="submit" name="btnSave" id="btnSave" value="Simpan" />
      </label></td>
    </tr>
  </table>
</form>