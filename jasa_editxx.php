<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if($_GET){
	if(isset($_POST['btnSave'])){
		
		$message = array();
		if(trim($_POST['txtJnsJasa'])==""){
			$message[] = "<b>Jenis Jasa</b> tidak kosong!!";
		}
		if(trim($_POST['txtHarga'])=="" OR ! is_numeric(trim($_POST['txtHarga']))){
			$message[] = "<b>Silahkan </b> Mengisi Harga Jasa Dahulu!";
		}
		//if(trim($_POST['txtDiskon'])=="" OR ! is_numeric(trim($_POST['txtDiskon']))){
			//$message[] = "<b> Silahkan Mengisi</b> Diskon (%) Jasa Terlebih Dahulu!";
		//}
		if(trim($_POST['cmbKategori'])=="BLANK"){
			$message[] = "<b> Kategori Jasa </b> belum dipilih!";
		}
		
		#Baca Variabel Form
		$txtJnsJasa = $_POST['txtJnsJasa'];
		$txtJnsJasa = str_replace("'","&acute;",$txtJnsJasa);
		$txtHarga = $_POST['txtHarga'];
		$txtHarga = str_replace("'","&acute;",$txtHarga);
		$txtHarga = str_replace("'","",$txtHarga);
		//$txtDiskon = $_POST['txtDiskon'];
		//$txtDiskon = str_replace("'","&acute;",$txtDiskon);
		$txtKeterangan = $_POST['txtKeterangan'];
		$txtKeterangan = str_replace("'","&acute;",$txtKeterangan);
		$cmbKategori = $_POST['cmbKategori'];
		$txtLama = $_POST['txtLama']; 
		
		#Validasi Nama Barang, jika sudah ada akan ditolak
		$sqlCek="SELECT * FROM jasa WHERE nama_jasa='txtJnsJasa' AND NOT(nama_jasa='$txtLama')";
		$qryCek=mysql_query($sqlCek, $koneksidb) or die ("Error Query".mysql_error());
		if(mysql_num_rows($qryCek)>=1){
			$message[] = " Maaf, Jenis Jasa <b> $txtJnsJasa </b> sudah ada, ganti dengan yang lain";
		}
		
		#Simpan Perubahan Data
		if(count($message)==0){
			$qryUpdate=mysql_query(" UPDATE jasa 
									 SET nama_jasa='$txtJnsJasa', harga_jasa='$txtHarga', keterangan='$txtKeterangan', kd_kategori='$cmbKategori' 
								     WHERE kd_jasa ='".$_POST['txtKode']."'") or die ("Gagal query update ".mysql_error());
			
			if($qryUpdate){
				echo "<meta http-equiv='refresh' content='0; url=?page=Data-Jasa'>";
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
	}	
	
		# TAMPILKAN DATA UNTUK DIEDIT
		$KodeEdit= isset($_GET['Kode']) ?  $_GET['Kode'] : $_POST['txtKode']; 
		$sqlShow = "SELECT * FROM jasa WHERE kd_jasa='$KodeEdit'";
		$qryShow = mysql_query($sqlShow, $koneksidb)  or die ("Query ambil data jasa salah : ".mysql_error());
		$dataShow= mysql_fetch_array($qryShow);
		
		# MASUKKAN DATA KE VARIABEL
		$dataKode	= $dataShow['kd_jasa'];
		$dataNama	= isset($dataShow['nama_jasa']) ?  $dataShow['nama_jasa'] : $_POST['txtJnsJasa'];
		$dataLama	= $dataShow['nama_jasa'];
		$dataHarga 	= isset($dataShow['harga_jasa']) ?  $dataShow['harga_jasa'] : $_POST['txtHarga'];
		//$dataDiskon	= isset($dataShow['diskon']) ?  $dataShow['diskon'] : $_POST['txtDiskon'];
		$dataKeterangan	= isset($dataShow['keterangan']) ?  $dataShow['keterangan'] : $_POST['txtKeterangan'];
		$dataKategori	= isset($dataShow['kd_kategori']) ?  $dataShow['kd_kategori'] : $_POST['cmbKategori'];


}
?>
<form action="?page=Edit-Jasa" method="post" name="form1" target="_self" id="form1">
  <table width="100%" border="0" cellspacing="1" cellpadding="2">
    <tr>
      <th colspan="3" scope="col">UBAH DATA JASA</th>
    </tr>
    <tr>
      <td width="11%">Kode Jasa</td>
      <td width="2%">:</td>
      <td width="87%"><label>
        <input name="txtLock" type="text" id="txtLock" value="<?php echo $dataKode; ?>" size="10" maxlength="4" readonly="readonly"/>
        <input name="txtKode" type="hidden" id="txtKode" value="<?php echo $dataKode; ?>" />
      </label></td>
    </tr>
    <tr>
      <td>Nama Jasa</td>
      <td>:</td>
      <td><label>
        <input name="txtJnsJasa" type="text" id="txtJnsJasa" value="<?php echo $dataNama; ?>" size="50" maxlength="100" />
        <input name="txtLama" type="hidden" id="txtLama" value="<?php echo $dataJasaLm; ?>" />
      </label></td>
    </tr>
    <tr>
      <td>Harga</td>
      <td>:</td>
      <td><label>
        <input name="txtHarga" type="text" id="txtHarga" value="<?php echo $dataHarga; ?>" size="20" maxlength="10" />
      </label></td>
    </tr>
	 <tr>
      <td>Keterangan</td>
      <td>:</td>
      <td><label>
        <input name="txtKeterangan" type="text" id="txtKeterangan" value="<?php echo $dataKeterangan; ?>" size="20" maxlength="50" />
      </label></td>
    </tr>
    <tr>
      <td>Kategori Jasa</td>
      <td>:</td>
      <td><label>
        <select name="cmbKategori" id="cmbKategori">
        	<option value="BLANK"></option>
            <?php
				$dataSql = "SELECT * FROM kategori WHERE jns_kategori=2 ORDER BY kd_kategori";
				$dataQry = mysql_query($dataSql) or die ("Gagal Query ".mysql_error());
					while ($dataRow = mysql_fetch_array($dataQry)) {
						if ($dataRow['kd_kategori']== $dataKategori) {
						$cek = " selected";
						} else { $cek=""; }
						echo "<option value='$dataRow[kd_kategori]' $cek>$dataRow[nm_kategori]</option>";
					}
	  $sqlData ="";
			?>
        </select>
      </label></td>
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