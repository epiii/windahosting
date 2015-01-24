<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if($_GET){
	if(isset($_POST['btnSave'])){
		
		$message = array();
		if(trim($_POST['txtBahan'])==""){
			$message[]="<b>Nama Bahan Baku</b> tidak boleh kosong!";
		}
		if(trim($_POST['txtHBahan'])=="" OR ! is_numeric(trim($_POST['txtHBahan']))){
			$message[]="<b>Harga Bahan</b> Baku tidak boleh kosong, harus diisi angka!";
		}
		#if(! is_numeric(trim($_POST['txtQty']))){
			#$message[]="<b>Stok</b> bahan baku harus diisi angka!";
		#}
		
		#Baca Variabel Form
		$txtLama	= $_POST['txtLama'];
		$txtBahan	= $_POST['txtBahan'];
		$txtBahan	= str_replace("'","&acute;",$txtBahan);
		$txtHBahan	= $_POST['txtHBahan'];
		$txtHBahan	= str_replace("'","&acute;",$txtHBahan);
		$txtHBahan	= str_replace("'","",$txtHBahan);
		$txtKeterangan	= $_POST['txtKeterangan'];
		$txtKeterangan	= str_replace("'","&acute;",$txtKeterangan);
		
		#Validasi Nama Barang, jika sudah akan ditolak
		$sqlCek = "SELECT * FROM bahanbaku WHERE kd_bahan<>'".$_POST['txtKode']."' AND nm_bahan='$txtBahan'";
		$qryCek = mysql_query($sqlCek, $koneksidb) or die ("Error Query".mysql_error());
		
		if(mysql_num_rows($qryCek)>=1){
			$message[] = " Maaf, bahan baku <b> $txtBahan </b> sudah ada! ganti dengan yang lain";
		}
		
				
		
		# Jika jumlah error message tidak ada, simpan datanya
		if(count($message)==0){	
			$qryUpdate=mysql_query("UPDATE bahanbaku 
								   SET nm_bahan='$txtBahan', harga_bahan='$txtHBahan', keterangan='$txtKeterangan' WHERE kd_bahan ='".$_POST['txtKode']."'") or die ("Gagal query update".mysql_error());
			if($qryUpdate){
				echo "<meta http-equiv='refresh' content='0; url=?page=Data-Bahan'>";
			}
			exit;
		}
		
		#pesan error
		if(! count($message)==0 ){
			echo "<div class='mssgBox'>";
			echo "<img src='images/attention.png' class='imgBox'> <hr>";
			$Num=0;
			foreach ($message as $indeks=>$pesan_tampil){
				$Num++;
				echo "$nbsp;$nbsp;$Num. $pesan_tampil<br>";
			}
			echo "</div> <br>";
		}
	}
	
	# TAMPILKAN DATA LOGIN UNTUK DIEDIT
	$KodeEdit= isset($_GET['Kode']) ?  $_GET['Kode'] : $_POST['txtKode']; 
	$sqlShow = "SELECT * FROM bahanbaku WHERE kd_bahan='$KodeEdit'";
	$qryShow = mysql_query($sqlShow, $koneksidb)  or die ("Query ambil data bahanbaku salah : ".mysql_error());
	$dataShow = mysql_fetch_array($qryShow); 
	
	#masukkan data ke variabel
	$dataKode	= $dataShow['kd_bahan'];
	$dataNama	= isset($dataShow['nm_bahan']) ?  $dataShow['nm_bahan'] : $_POST['txtBahan'];
	$dataLama	= $dataShow['nm_bahan'];
	$dataHBahan = isset($dataShow['harga_bahan']) ?  $dataShow['harga_bahan'] : $_POST['txtHBahan'];
	$dataQty	= isset($dataShow['jml_bahan']) ?  $dataShow['jml_bahan'] : $_POST['txtQty'];
	$dataKeterangan	= isset($dataShow['keterangan']) ?  $dataShow['keterangan'] : $_POST['txtKeterangan'];	
}
?>
<form action="" method="post" name="form1" target="_self" id="form1">
  <table width="74%" border="0" cellspacing="1" cellpadding="2">
    <tr>
      <th colspan="3" scope="col"><div align="center">EDIT DATA BAHAN BAKU</div></th>
    </tr>
    <tr>
      <td width="24%">Kode Bahan Baku</td>
      <td width="1%">:</td>
      <td width="75%"><input name="txtLock" id="txtLock" value="<?php echo $dataKode; ?>" size="10" maxlength="4" readonly="readonly"/>
      <input name="txtKode" type="hidden" id="txtKode" value="<?php echo $dataKode; ?>" /></td>
    </tr>
    <tr>
      <td>Nama Bahan Baku</td>
      <td>:</td>
      <td><label>
        <input name="txtBahan" type="text" id="txtBahan" value="<?php echo $dataNama; ?>" size="30" maxlength="50" />
        <input name="txtLama" type="hidden" id="txtLama" value="<?php echo $dataLama; ?>" />
      </label></td>
    </tr>
    <tr>
      <td>Harga Bahan Baku</td>
      <td>:</td>
      <td><label>
        <input name="txtHBahan" type="text" id="txtHBahan" value="<?php echo $dataHBahan; ?>" size="20" maxlength="10" />
      </label></td>
    </tr>
    <tr>
      <td>Keterangan</td>
      <td>:</td>
      <td><label>
        <input name="txtKeterangan" type="text" id="txtKeterangan" value="<?php echo $dataKeterangan; ?>" size="30" maxlength="50" />
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><label>
        <input type="submit" name="btnSave" id="btnSave" value="SIMPAN">
      </label></td>
    </tr>
  </table>
</form>
