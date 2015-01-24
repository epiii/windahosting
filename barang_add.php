<?php 
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if($_GET){
	if(isset($_POST['btnSave'])){
		
		$message = array();
		if (trim($_POST['txtBarang'])==""){
			$message[] = "<b>Nama Barang</b> Belum diisi!";
		}
		if (trim($_POST['txtHargaBeli']=="") OR ! is_numeric(trim($_POST['txtHargaBeli']))){
			$message[] = "<b>Harga Beli</b> Barang tidak boleh kosong, harus diisi angka !";
		}
		if (trim($_POST['txtHargaJual']=="") OR ! is_numeric(trim($_POST['txtHargaJual']))){
			$message[] = "<b>Harga Jual</b> Barang tidak boleh kosong, harus diisi angka !";
		}
		if (trim($_POST['txtppn']=="") OR ! is_numeric(trim($_POST['txtppn']))){
			$message[] = "<b>Ppn (%)</b> barang tidak boleh kosong, harus diisi angka !";
		}
		if (trim($_POST['txtDiskon'])=="" OR ! is_numeric(trim($_POST['txtDiskon']))) {
			$message[] = "<b>Dikson (%)</b> jual tidak boleh kosong, harus diisi angka !";
		}
		if (! is_numeric(trim($_POST['txtQty']))){
			$message[] = "<b>Qty</b> Barang Belum diisi!";
		}
		if (trim($_POST['cmbKategori'])=="BLANK"){
			$message[] = "<b>Kategori Barang</b> Belum dipilih!";
		}	
		
		#Baca Variabel Form
		$txtBarang = $_POST['txtBarang'];
		$txtBarang = str_replace("'","&acute;",$txtBarang);
		$txtHargaBeli = $_POST['txtHargaBeli'];
		$txtHargaBeli = str_replace("'","&acute;",$txtHargaBeli);
		$txtQty		= $_POST['txtQty'];
		$txtQty		= str_replace("'","",$txtQty);
		$txtHargaBeli = str_replace("'","",$txtHargaBeli);
		$txtHargaJual = $_POST['txtHargaJual'];
		$txtHargaJual = str_replace("'","&acute;",$txtHargaJual);
		$txtHargaJual = str_replace("'","",$txtHargaJual);
		$txtppn = $_POST['txtppn'];
		$txtppn = str_replace("'","&acute;",$txtppn);
		$txtDiskon		= $_POST['txtDiskon'];
		$txtDiskon		= str_replace("'","&acute;",$txtDiskon);
		$txtKeterangan = $_POST['txtKeterangan'];
		$txtKeterangan = str_replace("'","&acute;",$txtKeterangan);
		$txtGambar = trim($_POST['txtgambar']);
		$cmbKategori = $_POST['cmbKategori'];
		
		#Validasi Nama Barang, jika sudah akan ditolak
		$sqlCek = "SELECT * FROM barang WHERE nm_barang='$txtBarang'";
		$qryCek = mysql_query($sqlCek, $koneksidb) or die ("Error Query".mysql_error());
		
		if(mysql_num_rows($qryCek)>=1){
			$message[] = " Maaf, barang <b> $txtBarang </b> sudah ada! ganti dengan yang lain";
		}
		
		#Validasi Diskon
		if (! trim($_POST['txtHargaBeli'])=="" AND ! trim($_POST['txtHargaJual'])=="") {
			$besarDiskon = intval($txtHargaJual) * (intval($txtDiskon)/100);
			$hargaDiskon = intval($txtHargaJual) - $besarDiskon;
			if (intval($txtHargaBeli) >= $hargaDiskon ){
				$message[] = "<b>Harga Jual</b> masih salah, terhitung <b> Anda merugi </b> ! <br>
								&nbsp; Harga belum diskon : Rp. ".format_angka($txtHargaJual)." <br>
								&nbsp; Diskon ($txtDiskon %) : Rp.  ".format_angka($besarDiskon)." <br>
								&nbsp; Harga sudah diskon : Rp. ".format_angka($hargaDiskon).", 
								Sedangkan modal Anda Rp. ".format_angka($txtHargaBeli)."<br>
								&nbsp; <b>Solusi :</b> Anda harus <b>mengurangi besar % Diskon</b>, atau <b>Menaikan Harga Jual</b>.";		
			}
		
		}
		
		#Simpan Data ke Database
		if(count($message)==0){
			$kodeBaru		= buatKode("barang","B");
			$sql="INSERT INTO barang 	SET kd_barang	='$kodeBaru',
											nm_barang	='$txtBarang',
											harga_beli	='$txtHargaBeli',
											harga_jual	='$txtHargaJual',
											qty			='$txtQty',
											ppn			='$txtppn',
											diskon		='$txtDiskon', 
											keterangan	='$txtKeterangan',
											kd_kategori	='$cmbKategori', 
											link_gambar	='$txtGambar'";
			//var_dump($sql);exit();
			$qrySave=mysql_query($sql) or die ("Gagal query".mysql_error());
			if($qrySave){
				echo "<meta http-equiv='refresh' content='0; url=?page=Data-Barang'>";
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
	$dataKode= buatKode("barang", "B");
	$dataQty= isset($_POST['txtQty']) ? $_POST['txtQty'] : '';
	$dataNama= isset($_POST['txtBarang']) ? $_POST['txtBarang'] : '';
	$dataHBeli= isset($_POST['txtHargaBeli']) ? $_POST['txtHargaBeli'] : '';
	$dataHJual= isset($_POST['txtHargaJual']) ? $_POST['txtHargaJual'] : '';
	$datappn= isset($_POST['txtppn']) ? $_POST['txtppn'] : '';
	$dataDiskon	= isset($_POST['txtDiskon']) ? $_POST['txtDiskon'] : '';
	$dataStok= isset($_POST['txtQty']) ? $_POST['txtQty'] : '';
	$dataKeterangan= isset($_POST['txtKeterangan']) ? $_POST['txtKeterangan'] : '';
	$dataKategori= isset($_POST['cmbKategori']) ? $_POST['cmbKategori'] : '';
}
?>
<form action="?page=Add-Barang" method="post" name="form1" target="_self" id="form1">
  <table width="100%" border="0" cellspacing="1" cellpadding="2">
    <tr>
      <th colspan="3" scope="col">TAMBAH DATA BARANG</th>
    </tr>
    <tr>
      <td width="14%">Kode Barang</td>
      <td width="2%">:</td>
      <td width="84%"><label>
        <input name="txtKode" type="text" id="txtKode" value="<?php echo $dataKode; ?>" size="10" maxlength="4" readonly="readonly"/>
      </label></td>
    </tr>
    <tr>
      <td>Nama Barang</td>
      <td>:</td>
      <td><label>
        <input name="txtBarang" type="text" id="txtBarang" value="<?php echo $dataNama; ?>" size="50" maxlength="100">
      </label></td>
    </tr>
    <tr>
      <td>Harga Beli</td>
      <td>:</td>
      <td><label>
        <input name="txtHargaBeli" type="text" id="txtHargaBeli" value="<?php echo $dataHBeli; ?>" size="20" maxlength="10">
      </label></td>
    </tr>
    <tr>
      <td>Harga Jual</td>
      <td>:</td>
      <td><label>
        <input name="txtHargaJual" type="text" id="txtHargaJual" value="<?php echo $dataHJual; ?>" size="20" maxlength="10" />
      </label></td>
    </tr>
    <tr>
      <td>Qty</td>
      <td>:</td>
      <td><label>
        <input name="txtQty" type="text" id="txtQty" value="<?php echo $dataQty; ?>" size="10" maxlength="30">
      </label></td>
    </tr>
    <tr>
      <td>Ppn (%)</td>
      <td>:</td>
      <td><label>
<input name="txtppn" type="text" id="txtppn" value="<?php echo $datappn; ?>" size="10" maxlength="30" />
%</label></td>
    </tr>
    <tr>
      <td>Diskon (%)</td>
      <td>:</td>
      <td><label>
        <input name="txtDiskon" type="text" id="txtDiskon" value="<?php echo $dataDiskon; ?>" size="10" maxlength="30" />
      %</label></td>
    </tr>
    <tr>
      <td>Keterangan</td>
      <td>:</td>
      <td><label>
        <input name="txtKeterangan" type="text" id="txtKeterangan" value="<?php echo $dataKeterangan; ?>" size="50" maxlength="50" />
      </label></td>
    </tr>
    <tr>
      <td>Gambar Barang</td>
      <td>:</td>
      <td><label>
        <input name="txtgambar" type="text" id="txtgambar" value="<?php echo $dataGambar; ?>" />
      </label></td>
    </tr>
    <tr>
      <td>Kategori Barang</td>
      <td>:</td>
      <td><select name="cmbKategori">
        <option value="BLANK"> </option>
        <?php
	  $dataSql = "SELECT * FROM kategori WHERE jns_kategori=1 ORDER BY kd_kategori";
	  $dataQry = mysql_query($dataSql, $koneksidb) or die ("Gagal Query".mysql_error());
	  while ($dataRow = mysql_fetch_array($dataQry)) {
	  	if ($dataRow['kd_kategori']== $_POST['cmbKategori']) {
			$cek = " selected";
		} else { $cek=""; }
	  	echo "<option value='$dataRow[kd_kategori]' $cek>$dataRow[nm_kategori]</option>";
	  }
	  $sqlData ="";
	  ?>
      </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" name="btnSave" id="btnSave" value="SIMPAN" /></td>
    </tr>
  </table>
</form>
