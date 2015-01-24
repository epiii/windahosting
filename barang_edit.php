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
			#$message[] = "<b>Harga Beli</b> Barang tidak boleh kosong, harus diisi angka !";
		}
		if (trim($_POST['txtHargaJual']=="") OR ! is_numeric(trim($_POST['txtHargaJual']))){
			$message[] = "<b>Harga Jual</b> Barang tidak boleh kosong, harus diisi angka !";
		}
		if (trim($_POST['txtppn']=="") OR ! is_numeric(trim($_POST['txtppn']))){
			$message[] = "<b>Ppn (%)</b> barang tidak boleh kosong, harus diisi angka !";
		}
		#if (! is_numeric(trim($_POST['txtQty']))){
			#$message[] = "<b>Qty</b> Barang Belum diisi!";
		#}
		if (trim($_POST['cmbKategori'])=="BLANK"){
			$message[] = "<b>Kategori Barang</b> Belum dipilih!";
		}	
		
		#Baca Variabel Form
		$txtLama	= $_POST['txtLama'];
		$txtBarang = $_POST['txtBarang'];
		$txtBarang = str_replace("'","&acute;",$txtBarang);
		$txtHargaBeli = $_POST['txtHargaBeli'];
		$txtHargaBeli = str_replace("'","&acute;",$txtHargaBeli);
		$txtHargaBeli = str_replace("'","",$txtHargaBeli);
		$txtHargaJual = $_POST['txtHargaJual'];
		$txtHargaJual = str_replace("'","&acute;",$txtHargaJual);
		$txtHargaJual = str_replace("'","",$txtHargaJual);
		$txtppn = $_POST['txtppn'];
		$txtppn = str_replace("'","&acute;",$txtppn);
		$txtKeterangan = $_POST['txtKeterangan'];
		$txtKeterangan = str_replace("'","&acute;",$txtKeterangan);
		$txtGambar = trim($_POST['txtgambar']);
		$cmbKategori = $_POST['cmbKategori'];
		
		#Validasi Nama Barang, jika sudah akan ditolak
		$sqlCek = "SELECT * FROM barang WHERE kd_barang<>'".$_POST['txtKode']."' AND nm_barang='$txtBarang'";
		$qryCek = mysql_query($sqlCek, $koneksidb) or die ("Error Query".mysql_error());
		
		if(mysql_num_rows($qryCek)>=1){
			$message[] = " Maaf, barang <b> $txtBarang </b> sudah ada! ganti dengan yang lain";
		}
		
		# Validasi Ppn
		#if (! trim($_POST['txtHargaBeli'])=="" AND ! trim($_POST['txtHargaJual'])=="") {
			#$besarppn = intval($txtHargaBeli) * (intval($txtppn)/100);
			#$hargappn = intval($txtHargaBelil) + $besarppn;
			#if (intval($txtHargaBeli) <= $hargaPpn ){
				#$message[] = "<b>Harga Jual</b> masih salah, terhitung <b> Anda merugi </b> ! <br>
								#&nbsp; Harga belum Ppn : Rp. ".format_angka($txtHargaBeli)." <br>
								#&nbsp; Harga sudah Ppn : Rp. ".format_angka($hargappn).", 
								#Sedangkan modal Anda Rp. ".format_angka($txtHargaBeli)."<br>
								#&nbsp; <b>Solusi :</b> Anda harus <b>mengurangi besar % Ppn</b>, atau <b>Menaikan Harga Jual</b>.";		
			#}
		
		#}
		
		#Simpan Data ke Database
		if(count($message)==0){
			$qryUpdate=mysql_query(" UPDATE barang 
								     SET nm_barang='$txtBarang', harga_beli='$txtHargaBeli', harga_jual='$txtHargaJual', ppn='$txtppn', keterangan='$txtKeterangan', link_gambar='$txtGambar', kd_kategori='$cmbKategori' 
								     WHERE kd_barang='".$_POST['txtKode']."'") or die ("Gagal query update".mysql_error());
			if($qryUpdate){
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
	
	# TAMPILKAN DATA LOGIN UNTUK DIEDIT
	$KodeEdit= isset($_GET['Kode']) ?  $_GET['Kode'] : $_POST['txtKode']; 
	$sqlShow = "SELECT * FROM barang WHERE kd_barang='$KodeEdit'";
	$qryShow = mysql_query($sqlShow, $koneksidb)  or die ("Query ambil data barang salah : ".mysql_error());
	$dataShow = mysql_fetch_array($qryShow); 
	
	#Masukkan Data ke Variabel
	$dataKode= $dataShow['kd_barang'];
	$dataNama= isset($dataShow['nm_barang']) ?  $dataShow['nm_barang'] : $_POST['txtBarang'];
	$dataLama= $dataShow['nm_barang'];
	$dataHBeli= isset($dataShow['harga_beli']) ?  $dataShow['harga_beli'] : $_POST['txtHargaBeli'];
	$dataHJual= isset($dataShow['harga_jual']) ?  $dataShow['harga_jual'] : $_POST['txtHargaJual'];
	$datappn= isset($dataShow['ppn']) ?  $dataShow['ppn'] : $_POST['txtppn'];
	$dataStok= isset($dataShow['qty']) ?  $dataShow['qty'] : $_POST['txtQty'];
	$dataKeterangan= isset($dataShow['keterangan']) ?  $dataShow['keterangan'] : $_POST['txtKeterangan'];
	$dataGambar= isset($dataShow['link_gambar']) ?  $dataShow['link_gambar'] : $_POST['txtGambar'];
	$dataKategori= isset($dataShow['kategori']) ?  $dataShow['kategori'] : $_POST['cmbKategori'];
}
?>
<form action="" method="post" name="form1" target="_self" id="form1">
  <table width="87%" border="0" cellspacing="1" cellpadding="2">
    <tr>
      <th colspan="3" scope="col"><div align="center">EDIT DATA BARANG</div></th>
    </tr>
    <tr>
      <td width="14%">Kode Barang</td>
      <td width="2%">:</td>
      <td width="84%"><label>
        <input name="txtLock" type="text" id="txtLock" value="<?php echo $dataKode; ?>" size="10" maxlength="4" readonly="readonly"/>
        <input name="txtKode" type="hidden" id="txtKode" value="<?php echo $dataKode; ?>" />
      </label></td>
    </tr>
    <tr>
      <td>Nama Barang</td>
      <td>:</td>
      <td><label>
        <input name="txtBarang" type="text" id="txtBarang" value="<?php echo $dataNama; ?>" size="60" maxlength="50" />
        <input name="txtLama" type="hidden" id="txtLama" value="<?php echo $dataNamaLm; ?>" />
      </label></td>
    </tr>
    <tr>
      <td>Harga Beli</td>
      <td>:</td>
      <td><label>
        <input name="txtHargaBeli" type="text" id="txtHargaBeli" value="<?php echo $dataHBeli; ?>" size="20" maxlength="10" />
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
      <td>Ppn (%)</td>
      <td>:</td>
      <td><label>
<input name="txtppn" type="text" id="txtppn" value="<?php echo $datappn; ?>" size="10" maxlength="30" />
%</label></td>
    </tr>
    <tr>
      <td>Keterangan</td>
      <td>:</td>
      <td><label>
        <input name="txtKeterangan" type="text" id="txtKeterangan" value="<?php echo $dataKeterangan; ?>" size="10" maxlength="30">
      </label></td>
    </tr>
    <tr>
      <td>Gambar Barang</td>
      <td>:</td>
      <td><label>
        <input name="txtgambar" type="text" id="txtgambar" value="<?php echo $dataGambar; ?>" size="60" />
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
	  	if ($dataRow['kd_kategori']== $dataKategori) {
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
