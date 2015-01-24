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
		if(! is_numeric(trim($_POST['txtQty']))){
			$message[]="<b>Stok</b> bahan baku harus diisi angka!";
		}
		
		#Baca Variabel Form
		$txtQty		= $_POST['txtQty'];
		$txtQty	= str_replace("'","&acute;",$txtQty);
		$txtBahan	= $_POST['txtBahan'];
		$txtBahan	= str_replace("'","&acute;",$txtBahan);
		$txtHBahan	= $_POST['txtHBahan'];
		$txtHBahan	= str_replace("'","&acute;",$txtHBahan);
		$txtHBahan	= str_replace("'","",$txtHBahan);
		$txtKeterangan	= $_POST['txtKeterangan'];
		$txtKeterangan	= str_replace("'","&acute;",$txtKeterangan);
		
		#validasi nama bahan
		$sqlCek="SELECT * FROM bahanbaku WHERE nm_bahan='$txtBahan'";
		$qryCek=mysql_query($sqlCek, $koneksidb) or die ("Error Query".mysql_error());
		if(mysql_num_rows($qryCek)>=1){
			$message[]="Maaf, bahan <b> $txtBahan </b> sudah ada, ganti dengan yang lain";
		}
		
		#simpan data ke database
		if(count($message)==0){
			$kodeBaru	= buatKode("bahanbaku", "BB");
			$qrySave	= mysql_query("INSERT INTO bahanbaku SET 
										kd_bahan	='$kodeBaru', 
										jml_bahan 	='$txtQty',
										nm_bahan	='$txtBahan', 
										harga_bahan	='$txtHBahan', 
										keterangan	='$txtKeterangan'") or die ("Gagal query".mysql_error());
			if($qrySave){
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
	
	#masukkan data ke variabel
	$dataKode	= buatKode("bahanbaku", "BB");
	$dataNama	= isset($_POST['txtBahan']) ? $_POST['txtBahan'] : '';
	$dataHBahan	= isset($_POST['txtHBahan']) ? $_POST['txtHBahan'] : '';
	$dataQty	= isset($_POST['txtQty']) ? $_POST['txtQty'] : '';
	$dataKeterangan	= isset($_POST['txtKeterangan']) ? $_POST['txtKeterangan'] : '';	
}
?>
<form action="?page=Add-Bahan" method="post" name="form1" target="_self" id="form1">
  <table width="100%" border="0" cellspacing="1" cellpadding="2">
    <tr>
      <th colspan="3" scope="col">TAMBAH DATA BAHAN BAKU</th>
    </tr>
    <tr>
      <td width="14%">Kode Bahan Baku</td>
      <td width="1%">:</td>
      <td width="85%"><label>
        <input name="txtKode" type="text" id="txtKode" value="<?php echo $dataKode; ?>" size="10" maxlength="4">
      </label></td>
    </tr>
    <tr>
      <td>Nama Bahan Baku</td>
      <td>:</td>
      <td><label>
        <input name="txtBahan" type="text" id="txtBahan" value="<?php echo $dataNama; ?>" size="50" maxlength="50">
      </label></td>
    </tr>
    <tr>
      <td>Harga Bahan</td>
      <td>:</td>
      <td><label>
        <input name="txtHBahan" type="text" id="txtHBahan" value="<?php echo $dataHBahan; ?>" size="20" maxlength="10" />
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
      <td>Keterangan</td>
      <td>:</td>
      <td><label>
        <input name="txtKeterangan" type="text" id="txtKeterangan" value="<?php echo $dataKeterangan; ?>" size="50" maxlength="50">
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
