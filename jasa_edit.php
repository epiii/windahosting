<script>
	function jnsJasa(event){
		$('#jasainfo').html('<img src="images/fbloader.gif">').fadeIn(1000);
		var jasa =$('#txtJnsJasa').val();
		var jasaAwal =$('#txtLama').val();
		$.ajax({
			url:'pAjax.php',
			type:'get',
			data:'aksi=cek&menu=addJasa&jasa='+jasa+'&jasaAwal='+jasaAwal,
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
        $('#txtJnsJasa').on('submit',jnsJasa);
    });
</script>

<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if(isset($_POST['btnSave'])){
	#Baca Variabel Form
	$txtJnsJasa 	= str_replace("'","&acute;",$txtJnsJasa);

	$qryUpdate=" UPDATE jasa SET nama_jasa		='".mysql_real_escape_string($_POST['txtJnsJasa'])."', 
								 kd_bahan		='".mysql_real_escape_string($_POST['cmbBahan'])."', 
								 diskon			='".mysql_real_escape_string($_POST['txtDiskon'])."', 
								 ppn			='".mysql_real_escape_string($_POST['txtPpn'])."', 
								 harga_jasa		='".mysql_real_escape_string($_POST['txtHarga'])."', 
								 keterangan		='".mysql_real_escape_string($_POST['txtKeterangan'])."', 
								 kd_kategori	='".mysql_real_escape_string($_POST['cmbBahan'])."' 
				WHERE kd_jasa 	='".$_POST['txtKode']."'";
				//var_dump($qryUpdate);exit();
	$exeUpdate	= mysql_query($qryUpdate) or die ("Gagal query update ".mysql_error());
	
	if($exeUpdate){
		echo "<meta http-equiv='refresh' content='0; url=?page=Data-Jasa'>";
	}
}	
	
# TAMPILKAN DATA UNTUK DIEDIT
$KodeEdit= isset($_GET['Kode']) ?  $_GET['Kode'] : $_POST['txtKode']; 
//var_dump($KodeEdit);exit();

//$sqlShow = "SELECT * FROM jasa WHERE kd_jasa='$KodeEdit'";
$sqlShow = "SELECT 
				j.kd_jasa,
				j.nama_jasa,
				j.diskon,
				j.ppn,
				j.kd_bahan,
				j.harga_jasa,
				j.kd_kategori,
				j.keterangan,
				b.kd_bahan,
				b.nm_bahan,
				b.harga_bahan 
			FROM jasa j, bahanbaku b 
			WHERE 
				j.kd_jasa='$KodeEdit' and 
				b.kd_bahan = j.kd_bahan";
//var_dump($sqlShow);exit();
$qryShow = mysql_query($sqlShow, $koneksidb)  or die ("Query ambil data jasa salah : ".mysql_error());
//var_dump($qryShow);exit();
$dataShow= mysql_fetch_array($qryShow);
//var_dump($dataShow);exit();
# MASUKKAN DATA KE VARIABEL
$dataHargaTot	= $dataShow['harga_bahan'] + $dataShow['harga_jasa'] ;
?>
<form action="?page=Edit-Jasa" method="post" name="form1" target="_self" id="form1">
  <table width="100%" border="0" cellspacing="1" cellpadding="2">
    <tr>
      <th colspan="3" scope="col">UBAH DATA JASA</th>
    </tr>
    <tr>
      <td width="16%"><strong>Kode Jasa</strong></td>
      <td width="2%">:</td>
      <td width="82%"><label>
        <input name="txtLock" type="text" id="txtLock" value="<?php echo $dataShow[kd_jasa]; ?>" size="10" maxlength="4" readonly="readonly"/>
        <input name="txtKode" type="hidden" id="txtKode" value="<?php echo $dataShow[kd_jasa]; ?>" />
      </label></td>
    </tr>
    <tr>
      <td><strong>Nama Jasa</strong></td>
      <td>:</td>
      <td><label>
        <input required="required"  placeholder="nama jasa (wajib diisi)" name="txtJnsJasa" type="text" id="txtJnsJasa" value="<?php echo $dataShow[nama_jasa]; ?>" size="50" maxlength="100" />&nbsp;<span id="jasaInfo" style="color:red;"></span>
        <input name="txtLama" type="hidden" id="txtLama" value="<?php echo $dataShow[nama_jasa]; ?>" />
      </label></td>
    </tr>
    
    <tr>
      <td><strong>Bahan Baku</strong></td>
      <td>:</td>
      <td><strong>
        <label>
          <select required name="cmbBahan" id="cmbBahan">
            <option value="">pilih bahan baku ..</option>
            <?php
			$dataSql = " SELECT * FROM bahanbaku ORDER BY nm_bahan";
			$dataQry = mysql_query($dataSql, $koneksidb) or die ("Gagal Query".mysql_error());
			while ($dataRow = mysql_fetch_array($dataQry)){
				if ($dataRow['kd_bahan']==$dataShow['kd_bahan']){
					echo "<option value='$dataRow[kd_bahan]' selected='selected'>$dataRow[nm_bahan]</option>";
				} else {
					echo "<option value='$dataRow[kd_bahan]'>$dataRow[nm_bahan]</option>";
				}
			}
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
        <input value="<?php echo $dataShow[harga_bahan];?>" placeholder="harga bahan baku.." type="text" readonly="readonly" name="hrgBahanTB" id="hrgBahanTB" />
      </label><span id="loadArea"></span></td>
    </tr>
    <tr>
      <td><strong>Harga Jasa</strong></td>
      <td><strong>:</strong></td>
      <td><strong>
        <label>
          <input  required="required" name="txtHarga" type="text" id="txtHarga" value="<?php echo $dataShow[harga_jasa]; ?>" size="20" maxlength="10" />	
        </label><span id="hrgInfo" style="color:red;"></span>
      </strong></td>
    </tr>
    <tr><td colspan="3">___________________________________<b>+</b> </td></tr>
    <tr>
    	<td><b>Total Harga</b></td>
    	<td>:</td>
    	<td><input value="<?php echo $dataHargaTot;?>" placeholder="total harga ..."readonly="readonly" type="text" id="totalTB" name="totalTB"/></td>
    </tr>
    <tr><td colspan="3">&nbsp;</td></tr>
   
   <tr>
      <td><strong>Diskon</strong></td>
      <td><strong>:</strong></td>
      <td><strong>
        <label>
          <input placeholder="diskon" name="txtDiskon" type="text" id="txtDiskon" value="<?php echo $dataShow['diskon']; ?>" size="20" />%
        </label>
      </strong><p id="info" style="color:red;"></p></td>
    </tr>
	
    <tr>
      <td><strong>ppn</strong></td>
      <td><strong>:</strong></td>
      <td><strong>
        <label>
          <input placeholder="ppn" name="txtPpn" type="text" id="txtPpn" value="<?php echo $dataShow['ppn'];?>" size="20" />%
        </label>
      </strong></td>
    </tr>
	
	
     
    <tr>
      <td><strong>Keterangan</strong></td>
      <td><strong>:</strong></td>
      <td><strong>
        <label>
          <!--<input name="txtKeterangan" type="text" id="txtKeterangan" value="<?php echo $dataKeterangan; ?>" size="20" maxlength="200" />-->
          <textarea name="txtKeterangan" id="txtKeterangan" placeholder="keterangan..."cols="16"><?php echo $dataShow[keterangan]; ?></textarea>
        </label>
      </strong></td>
    </tr>
    <tr>
      <td><strong>Kategori Jasa</strong></td>
      <td>:</td>
      <td><label>
        <select required="required" name="cmbKategori" id="cmbKategori">
        	<option value="">pilih kategori ...</option>
            <?php
				$dataSql = "SELECT * FROM kategori WHERE jns_kategori='2' ORDER BY kd_kategori";
				//$dataSql = "SELECT * FROM kategori ORDER BY kd_kategori";
				//var_dump($ok);exit();
				$dataQry = mysql_query($dataSql) or die ("Gagal Query ".mysql_error());
					while ($dataRow = mysql_fetch_array($dataQry)) {
						//$ok = $dataRow['kd_kategori'];
						//var_dump($ok);exit();
						if ($dataRow['kd_kategori']== $dataShow['kd_kategori']) {
							echo "<option value='$dataRow[kd_kategori]' selected='selected'>$dataRow[nm_kategori]</option>";
						} else { 
							echo "<option value='$dataRow[kd_kategori]' >$dataRow[nm_kategori]</option>";
						}
					}
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