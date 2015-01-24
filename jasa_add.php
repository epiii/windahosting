<script>
	function jnsJasa(event){
		$('#jasainfo').html('<img src="images/fbloader.gif">').fadeIn(1000);
		var jasa =$('#txtJnsJasa').val();
		$.ajax({
			url:'pAjax.php',
			type:'get',
			data:'aksi=cek&menu=addJasa&jasa='+jasa,
			dataType:'json',
			success:function(data){
				$('#jasainfo').html('<img src="images/fbloader.gif">').fadeOut(1000);
		
				if(jasa==''){
					$('#jasaInfo').html('<span style="color:red;">harus diisi</span>');
				}else if(data.status=='terpakai'){
					$('#jasaInfo').html('<span style="color:red;"> " '+jasa+' " telah digunakan</span>');
					$('#txtJnsJasa').val('');
					return false;
				}else{
					$('#jasaInfo').html('<span style="color:green;">tersedia</span>');
				}
			}
		});
	}
	
	function angkaValid(event) {
		//bukan angka
		if(this.value != this.value.replace(/[^0-9]/g, '')){
			this.value = this.value.replace(/[^0-9]/g, '');
			
			$('#hrgInfo').html('hanya angka').fadeIn();
			setTimeout(function(){
				$('#hrgInfo').fadeOut();
			},1000);
		}
		//angka
		else{
			var jum = (parseInt($(this).val())) + (parseInt($('#hrgBahanTB').val() ));
			$('#totalTB').val(jum); 
		}
	}
	
	function cmbBahan(event){
		$('#hrgBahanTB').val('');		
		$('#loadArea').html('<img src="images/fbloader.gif">').fadeIn(1000);
		var idBahan = $(this).val();
		$.ajax({
			type:'get',
			url:'pAjax.php',
			dataType:'json',
			data:'aksi=combo&menu=addJasa&idBahan='+idBahan,
			success:function(data){
				if(data.status=='gagal'){
					alert('gagal mengambil data harga [database-error]');
				}else{
					var hrg = data.harga_bahan;
					$('#loadArea').fadeOut(function(){
						$('#hrgBahanTB').val(hrg).fadeIn();
					});
					$('#txtHarga').attr('disabled',false).val('');
				}
			}
		});
	}

	function angkaPersen(event){
		console.log(thisVal);
		if(this.value != this.value.replace(/[^0-9.]/g, '')){
			this.value = this.value.replace(/[^0-9.]/g, '');
			$('#info').html('hanya angka (1-100)').fadeIn();
			setTimeout(function(){
				$('#info').fadeOut();
			},1000);
		
		}else{
			var thisVal = parseFloat($(this).val(), 10);
			if (!isNaN(thisVal)){
		
				thisVal = Math.max(1, Math.min(100, thisVal));
				$(this).val(thisVal);
			}
		}
	}
	$(document).ready(function() {
        $('#txtDiskon').on('keyup paste input',angkaPersen);
        $('#txtPpn').on('keyup paste input',angkaPersen);
        $('#txtHarga').val('pilih bahan baku dulu..');
		$('#cmbBahan').on('change',cmbBahan);
        $('#txtHarga').on('keyup',angkaValid);
        $('#txtJnsJasa').on('keyup',jnsJasa);
        $('#txtJnsJasa').on('blur',jnsJasa);
        $('#txtJnsJasa').on('change',jnsJasa);
        $('form').on('submit',jnsJasa);
    });
</script>
<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if($_GET){
	if(isset($_POST['btnSave'])){
		
		/*$message = array();
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
		}*/
		
		#Validasi Nama Barang, jika sudah ada akan ditolak
		/*$sqlCek="SELECT * FROM jasa WHERE nama_jasa='txtJnsJasa'";
		$qryCek=mysql_query($sqlCek, $koneksidb) or die ("Error Query".mysql_error());
		if(mysql_num_rows($qryCek)>=1){
			$message[] = " Maaf, Jenis Jasa <b> $txtJnsJasa </b> sudah ada, ganti dengan yang lain";
		}*/
		
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
			$txtJnsJasa 	= $_POST['txtJnsJasa'];
			$txtJnsJasa 	= str_replace("'","&acute;",$txtJnsJasa); 
			$txtHarga 		= $_POST['txtHarga'];
			$txtHarga		= str_replace("'","&acute;",$txtHarga);
			$txtHarga 		= str_replace(".","",$txtHarga);
			$txtKeterangan	= $_POST['txtKeterangan'];
			$txtKeterangan	= str_replace("'","&acute;",$txtKeterangan);
			$cmbKategori 	= $_POST['cmbKategori'];
			$cmbBahan		= $_POST['cmbBahan'];
			$txtPpn			= $_POST['txtPpn'];
			$txtDiskon		= $_POST['txtDiskon'];
	
	
		#Simpan Data ke Database
		if(count($message)==0){
			$kodeBaru = buatKode("jasa", "J");
			$sql	="INSERT INTO jasa SET 	kd_jasa		='$kodeBaru', 
														nama_jasa	='$txtJnsJasa', 
														kd_bahan	='$cmbBahan', 
														diskon		='$txtDiskon', 
														ppn			='$txtPpn', 
														harga_jasa	='$txtHarga', 
														keterangan	='$txtKeterangan', 
														kd_kategori	='$cmbKategori'";
			//var_dump($sql);exit();
			$qrySave=mysql_query($sql) or die ("Gagal query ".mysql_error());
		if($qrySave){
			echo "<meta http-equiv='refresh' content='0; url=?page=Data-Jasa'>";
		}
		exit;
		}
	#JIKA ADA PESAN ERROR DARI VALIDASI
	// (Form Kosong, atau Duplikat ada), Ditampilkan lewat kode ini
	/*if (! count($message)==0){
		echo "<div class ='mssgBox'>";
		echo "<img src='images/attention.png' class='imgBox'> <hr>";
		$Num=0;
		foreach ($message as $indeks=>$pesan_tampil){
			$Num++;
			echo "&nbsp;&nbsp;$Num. $pesan_tampil<br>";
		}
		echo "</div> <br>";
	}*/
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
<form autocomplete="off" action="?page=Add-Jasa" method="post" enctype="multipart/form-data" name="form1" target="_self" id="form1">
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
        <input placeholder="nama jasa ...."required="required" name="txtJnsJasa" id="txtJnsJasa" value="<?php echo $dataJasa; ?>" size="50" maxlength="20" /> &nbsp;<span id="jasaInfo" style="color:red;"></span>
      </strong></td>
    </tr>
    <tr>
      <td><strong>Bahan Baku</strong></td>
      <td><strong>:</strong></td>
      <td><strong>
        <label>
          <select required name="cmbBahan" id="cmbBahan">
            <option value="">pilih bahan baku ..</option>
            <?php
			$dataSql = " SELECT * FROM bahanbaku ORDER BY nm_bahan";
			$dataQry = mysql_query($dataSql, $koneksidb) or die ("Gagal Query".mysql_error());
			while ($dataRow = mysql_fetch_array($dataQry)){
				if ($dataRow['kd_bahan']==$_POST['cmbBahan']){
					$cek = "selected";
				} else { $cek=="";}
				echo "<option value='$dataRow[kd_bahan]' $cek>$dataRow[nm_bahan]</option>";
			}
			$sqlData = "";
			?>
          </select>
        </label>
      </strong></td>
    </tr>
    
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr>
      <td><b>Harga Bahan</b></td>
      <td>:</td>
      <td><label>
        <input placeholder="harga bahan baku.." type="text" readonly="readonly" name="hrgBahanTB" id="hrgBahanTB" />
      </label><span id="loadArea"></span></td>
    </tr>
    <tr>
      <td><strong>Harga Jasa</strong></td>
      <td><strong>:</strong></td>
      <td><strong>
        <label>
          <input  required="required" name="txtHarga" type="text" id="txtHarga" disabled="disabled" value="<?php echo $dataHarga; ?>" size="20" maxlength="10" />	
        </label><span id="hrgInfo" style="color:red;"></span>
      </strong></td>
    </tr>
    <tr><td colspan="3">___________________________________<b>+</b> </td></tr>
    <tr>
    	<td><b>Total Harga</b></td>
    	<td>:</td>
    	<td><input placeholder="total harga ..."readonly="readonly" type="text" id="totalTB" name="totalTB"/></td>
    </tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    
    <tr>
      <td><strong>Diskon</strong></td>
      <td><strong>:</strong></td>
      <td><strong>
        <label>
          <input placeholder="diskon" name="txtDiskon" type="text" id="txtDiskon" value="<?php echo $dataDiskon; ?>" size="20" />%
        </label>
      </strong><p id="info" style="color:red;"></p></td>
    </tr>
	
    <tr>
      <td><strong>ppn</strong></td>
      <td><strong>:</strong></td>
      <td><strong>
        <label>
          <input placeholder="ppn" name="txtPpn" type="text" id="txtPpn" value="<?php echo $dataPpn; ?>" size="20" />%
        </label>
      </strong></td>
    </tr>
	
	<tr>
      <td><strong>Keterangan</strong></td>
      <td><strong>:</strong></td>
      <td><strong>
        <label>
          <!--<input name="txtKeterangan" type="text" id="txtKeterangan" value="<?php echo $dataKeterangan; ?>" size="20" maxlength="200" />-->
          <textarea name="txtKeterangan" id="txtKeterangan" placeholder="keterangan..."cols="16"><?php echo $dataKeterangan; ?></textarea>
        </label>
      </strong></td>
    </tr>
    <tr>
      <td><strong>Kategori Jasa</strong></td>
      <td><strong>:</strong></td>
      <td><strong>
        <label>
          <select required name="cmbKategori" id="cmbKategori">
            <option value="">pilih kategori..</option>
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