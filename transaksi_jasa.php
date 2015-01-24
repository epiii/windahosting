<script src="js/jquery.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="styles/smoothness/jquery-ui-1.10.1.custom.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="styles/smoothness/jquery.ui.combogrid.css"/>

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.ui.combogrid-1.6.3.js"></script>

<script>
jQuery(document).ready(function(){
	$("#txtJasa").on('keyup', function(e){
		var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		var keyCode = $.ui.keyCode;
		if(key != keyCode.ENTER && key != keyCode.LEFT && key != keyCode.RIGHT && key != keyCode.DOWN) {
			$('#txtKodeJasa').val('');
			$('#txtKodeBBaku').val('');
			$('#txtNamaBBaku').val('');
		}
	});

	$( "#txtJasa" ).combogrid({
		debug:true,
		colModel: [{
				'columnName':'kd_jasa',
				'hide':true,
				'width':'8',
				'label':'kode'
			}, {
				'columnName':'nama_jasa',
				'width':'48',
				'label':'Jasa'
			},{
				'columnName':'harga_jasa',
				'width':'22',
				'label':'Hrg_Jasa'
			},{
				'columnName':'harga_bahan',
				'width':'22',
				'label':'Hrg_Bahan'
			}],
		url: 'pAjax.php?aksi=autoCom&menu=transJasa',
		select: function( event, ui ) {
			$( "#txtKodeJasa" ).val( ui.item.kd_jasa);
			$( "#txtJasa" ).val( ui.item.nama_jasa);
			$( "#txtKodeBBaku" ).val( ui.item.kd_bahan);
			$( "#txtNamaBBaku" ).val( ui.item.nm_bahan);
			return false;
		}
	});

	$('#txtKodeJasa').on('focus',function(){
		$('#txtJasa').focus();

		$('#infoJasaP').html('<span style="color:red;">kode terisi otomatis (setelah anda pilih barang)</span>').fadeIn();
		setTimeout(function(){
			$('#infoJasaP').html('&nbsp;');
		},3000);
	});
	$('#txtKodeBBaku').on('focus',function(){
		$('#txtJasa').focus();

		$('#infoBBakuP').html('<span style="color:red;">kode bahan baku terisi otomatis (setelah anda pilih barang)</span>').fadeIn();
		setTimeout(function(){
			$('#infoBBakuP').html('&nbsp;');
		},3000);
	});
	$('#txtNamaBBaku').on('focus',function(){
		$('#txtJasa').focus();

		$('#infoBBakuP').html('<span style="color:red;">nama bahan baku terisi otomatis (setelah anda pilih barang)</span>').fadeIn();
		setTimeout(function(){
			$('#infoBBakuP').fadeOut();
		},3000);
	});
});
</script>

<script>
	function kosongkan(event){
		$('#txtKodeJasa').val('');
		$('#txtJasa').val('');
		$('#txtKodeBBaku').val('');
		$('#txtNamaBBaku').val('');
		$('#txtjmlBahan').val('');
	}
	
	function angkaValid(event) {
		//bukan angka
		//range x to y
		 /*var thisVal = parseInt($(this).val(), 10);
		 console.log(thisVal);
		if (!isNaN(thisVal)){
			thisVal = Math.max(1, Math.min(100, thisVal));
			$(this).val(thisVal);
		}*/
		
		//var value = $(this).val();
		//value = value.replace(/^(0*0-9)/,"");
		//$(this).val(value);
		
		if(this.value != this.value.replace(/[^0-9]/g, '')){
			this.value = this.value.replace(/[^0-9]/g, '');
			
			$('#bahanBakuInfo').html('hanya angka').fadeIn();
			setTimeout(function(){
				$('#bahanBakuInfo').fadeOut();
			},1000);
		}
	}
	
	function delRow(id,kd_bhn){
		$.ajax({
			type:'get',
			url:'pAjax.php',
			data:'aksi=delRow&menu=transJasa&id='+id+'&kd_bhn='+kd_bhn,
			cache:false,
			dataType:'json',
			success:function(data){
				if(data.status==''){
					//alert('');
					loadRow();
					//loadCmbJasa();
				}else{
					alert(data.status);
				}
			}
		});
	}
	
	function addRow(){
	$('#jasaTBL').html('<tr><td colspan="7"align="center"><img src="images/fbloader.gif"></td></tr>').fadeIn(1000);
		
		var kd_jasa 	= $('#txtKodeJasa').val();
		var kd_bahan	= $('#txtKodeBBaku').val();
		var jml			= $('#txtjmlBahan').val();
		
		$.ajax({
			type:'get',
			url:'pAjax.php',
			data:'aksi=addRow&menu=transJasa&kd_jasa='+kd_jasa+'&kd_bahan='+kd_bahan+'&jml='+jml,
			cache:false,
			dataType:'json',
			success:function(data){
				if(data.status==''){
					loadRow();
					kosongkan();
				}else{
					alert(data.status);	
				}
			}
		});
	}
	
	function format_uang(nStr) {
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			//x1 = x1.replace(rgx, '$1' + ',' + '$2');
			x1 = x1.replace(rgx, '$1' + '.' + '$2');
		}
		return 'Rp. '+x1 + x2;
	}
	
	function loadRow(){
	$('#jasaTBL').html('<tr><td colspan="7"align="center"><img src="images/fbloader.gif"></td></tr>').fadeIn(1000);
		
		$.ajax({
			type:'get',
			url:'pAjax.php',
			data:'aksi=loadRow&menu=transJasa',
			cache:false,
			dataType:'json',
			success:function(data){
				var tr= '';
				var gtot=0;
				var no = 1;
				var sub_bahan 	= 0;
				var sub_jasa	= 0;
				if(typeof data.status==='undefined'){
					$.each(data, function (id,item){
						var id_tmp		= item.id_tmp;
						var kd_jasa 	= item.kd_jasa;
						var nama_jasa	= item.nama_jasa;
						var nama_bahan	= item.nama_bahan;
						var kd_bahan	= item.kd_bahan;
						var jml_bahan	= item.jml_bahan;
						var stok		= item.stok;
						var ppn			= item.ppn;
						var diskon		= item.diskon;
						
						var harga_bahan1= item.harga_bahan;
						var harga_bahan	= parseInt(harga_bahan1) * parseInt(jml_bahan);
							sub_bahan	= sub_bahan + harga_bahan;
						var harga_jasa	= item.harga_jasa;
							sub_jasa	= sub_jasa + parseInt(harga_jasa);
						var harga_kotor	= parseInt(harga_bahan) + parseInt(harga_jasa);
						var ppn_angka	= parseInt(ppn) * parseInt(harga_kotor) /100;
						var diskon_angka= parseInt(diskon) * parseInt(harga_kotor) /100;
						var sub_total	= parseInt(harga_kotor)-parseInt(diskon_angka)+parseInt(ppn_angka); 
						//var sub_total	= item.sub_total;
							gtot		= gtot + parseInt(sub_total);
						
						//console.log('harga * jml:'+harga_bahan);
						//console.log('harga kotor:'+harga_kotor);
						//console.log('harga jasa :'+harga_jasa);
						//console.log('ppn angka:'+ppn_angka);
						//console.log('disc angka :'+diskon_angka);
						//console.log('harga bersih :'+subtotal);
						//console.log(sub_total);
						//console.log(gtot);
						
						tr+= '<tr class="jasaTR">'
								+'<td align="center"><b>'+no+'</b></td>'
								+'<td align="center"><b>'+kd_jasa+'/'+kd_bahan+'</b></td>'
								+'<td>'+nama_jasa+'/'+nama_bahan+'</td>'
								+'<td align="right">'+jml_bahan+'</td>'
								//+'<td align="right">'+format_uang(harga_bahan1)+'</td>'
								+'<td align="right">'+format_uang(harga_bahan)+'</td>'
								+'<td align="right">'+format_uang(harga_jasa)+'</td>'
								+'<td align="right">'+ppn+'%</td>'
								+'<td align="right">'+diskon+'%</td>'
								+'<td align="right">'+format_uang(sub_total)+'</td>'
								+'<td align="center" bgcolor="#FFFFCC">'
									+'<a onclick="return confirm(\'yakin hapus `'+kd_jasa+'` ?\');" '
										+'href="javascript:delRow('+id_tmp+',\''+kd_bahan+'\');" target="_self">'
										+'<img src="images/hapus.gif" width="16" height="16" border="0" />'
									+'</a>'
								+'</td>'
							+'</tr>';
						no++;
					});
					tr+='<tr><td colspan="7" align="right">Grand Total :</td>'
							+'<td align="right"></td>'
							+'<td align="right"></td>'
							+'<td align="right">'+format_uang(gtot)+'</td>'
								+'<td></td>'
						+'</tr>'
					$('#jasaTBL').html(tr).fadeIn();
				}else{
					$('#jasaTBL').html('<tr><td colspan="11"align="center" style="color:red;">jasa kosong</td></tr>').fadeIn();			
				}
			}	
		});
		//alert('add row');	
	}
	
	function noChange(event) {
		$('#loadArea').html('field terisi otomatis(pilih jasa)').fadeIn();
		setTimeout(function(){
			$('#loadArea').fadeOut();
		},1000);
	}
	
	function noChange() {
		$('#loadArea').html('field terisi otomatis(pilih jasa)').fadeIn();
		setTimeout(function(){
			$('#loadArea').fadeOut();
		},1000);
	}
	
	/*function loadCmbJasa(){
		$.ajax({
			url:'pAjax.php',
			type:'get',
			data:'aksi=combo&menu=transJasa2',
			dataType:'json',
			success:function(data){
				var optionx = '';
				if(typeof data.status==='undefined'){
					$.each(data,function(id,item){
						optionx +='<option value="'+item.kd_jasa+'">'+item.nm_jasa+'</option>';
					});
				}else{
					optionx+='<option value="">'+data.status+'</option>';
				}
				$('#cmbJasa').html('<option value="">pilih jasa...</option>'+optionx);
			}
		});
	}*/
	
	$(document).ready(function() {
		//loadCmbJasa();
        loadRow();
		$('#txtjmlBahan').on('keyup',angkaValid	);
        //$('#cmbJasa').on('change',cmbJasa);
        $('#txtBBaku').on('keyup',noChange);
        //$('#btnPilih').on('click',addRow);

        $('#btnPilih').click(function(){
			if($('#txtKodeJasa').val()==''){
				alert('pilih jasa');
				$('#txtJasa').focus();
				return false;
			}else if($('#txtjmlBahan').val()==''){
				alert('isikan jumlah bahan baku');
				$('#txtjmlBahan').focus();
				return false;
			}else{
				var jml 	= $('#txtjmlBahan').val();
				var kd_bhn 	= $('#txtKodeBBaku').val();
				$.ajax({
					url:'pAjax.php',
					type:'get',
					data:'aksi=cek&menu=transJasa&kd_bhn='+kd_bhn+'&jml='+jml,
					dataType:'json',
					success:function(data){
						if(data.status==''){
							addRow();
							loadCmbJasa();
						}else{
							$('#bahanBakuInfo').html(data.status).fadeIn();
							setTimeout(function(){
								$('#bahanBakuInfo').fadeOut();
							},3000);
						}
					}
				});
			}
		});
    });
	
	function submitform(event){
		if(confirm('lanjutkan menyimpan data?')){
			//var x = $('#jasaTBL').html();
			//alert('=>'+x);
			//return false;
			//$.ajax();
			var jumTR= $('.jasaTR','#jasaTBL').length;
			if(jumTR==0){
				alert('minimal pilih 1 jasa ');
				//$('#imgInfo').fadeIn(function(){
					$('#imgInfo').html('minimal unggah 1 bukti kegiatan(scan/gambar)');
				//});
				//setTimeout(function(){
					///$('#imgInfo').fadeOut(1000,function(){
						//$('#imgInfo').html('');
					//});
				//},3000);
				event.stopPropagation();
				event.preventDefault();
			}
			//else{		
		}
	}
	$(document).ready(function(){
		$('form').on('submit',submitform);
	});
	
</script>

<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";

if($_GET){
	#get ACT ==================================================================
	if(isset($_GET['Act'])){
		# tmp - DEL ===========================================================
		if(trim($_GET['Act'])=="Delete"){
			$sql	= "DELETE FROM tmp_jasa 
						WHERE 	id		='".$_GET['ID']."' AND 
								userid	='".$_SESSION['SES_LOGIN']."'";
			$exe	= mysql_query($sql) or die ("Gagal kosongkan tmp".mysql_error());
		}#end of  tmp - DEL ====================================================
		#tmp - SUCCESS==========================================================
		if(trim($_GET['Act'])=="Sucsses"){
			echo "<b>DATA BERHASIL DISIMPAN</b> <br><br>";
		}#tmp - SUCCESS=========================================================
	}#end of get ACT ===========================================================
	
	#post ======================================================================
	if($_POST){
		# post - btnPilih (add jasa) ===========================================
		if(isset($_POST['btnPilih'])){
			$tmpSql = "INSERT INTO tmp_jasa SET kd_jasa		= '$jasaRow[kd_jasa]', 
												jml_bahan	= '$txtjmlBahan', 
												userid		= '".$_SESSION['SES_LOGIN']."'";
			//var_dump($tmpSql);exit();
			$tmpExe	= mysql_query($tmpSql, $koneksidb) or die ("Gagal Query detail barang : ".mysql_error());
			$cmbJasa	= '';
			$txtHarga	= '';
		}#end of post - btnPilih (add jasa) =====================================
	}#end of post ===============================================================
	
	# post - btnSave (simpan trnsaksi) ==========================================
	if(isset($_POST['btnSave'])){
		$tmpSql = "SELECT COUNT(*) As id FROM tmp_jasa WHERE userid='".$_SESSION['SES_LOGIN']."'";
		//var_dump($tmpSql);exit();
		$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
		$tmpRow = mysql_fetch_array($tmpQry);
		
		# Baca variabel
		$txtjmlBahan	= $_POST['txtjmlBahan'];
		$cmbJasa		= $_POST['cmbJasa'];
		//$txtKode		= $_POST['txtKode'];
		$txtNomor		= $_POST['txtNomor'];
		//$txtPelanggan	= $_POST['txtPelanggan'];
		$cmbPelangganx	= $_POST['cmbPelanggan'];
		$cmbPelanggan	= str_replace("'","&acute;",$cmbPelangganx);
		$txtCatatan		= $_POST['txtCatatan'];
		$txtCatatan 	= str_replace("'","&acute;",$txtCatatan);
		$cmbTanggal 	= $_POST['cmbTanggal'];
			
		$kodeBaru	= buatKode("transaksi", "TJ");
		$sqlSave	= "INSERT INTO transaksi SET 	no_transaksi	='$kodeBaru', 
													tgl_transaksi	='".InggrisTgl($_POST['cmbTanggal'])."',
													kd_pelanggan		='$cmbPelanggan', 
													catatan			='$txtCatatan', 
													userid			='".$_SESSION['SES_LOGIN']."'";
		#var_dump($sqlSave);exit();
		$exeSave	= mysql_query($sqlSave) or die ("Gagal query ".mysql_error());
		
		#berhasil simpan transaksi ===========================================
		if($exeSave){
			# ambil data tmp_jasa berdasarkan sesi : 'userid'
			$tmpSql = "SELECT * FROM tmp_jasa WHERE userid='".$_SESSION['SES_LOGIN']."'";
			//var_dump($tmpSql);exit();
			$tmpQry = mysql_query($tmpSql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
			
			#simpan data tmp_jasa ke jasa_item ------------------------
			while ($tmpRow = mysql_fetch_array($tmpQry)) {
				# simpan data tmp_jasa -> jasa_item (loop)
				$itemSql = "INSERT INTO jasa_item SET 	no_transaksi	='$kodeBaru', 
														kd_jasa			='$tmpRow[kd_jasa]',
														kd_bahan		='$tmpRow[kd_bahan]',
														jml_bahan		='$tmpRow[jml_bahan]'";
				//var_dump($itemSql);exit();
				$exeSql	= mysql_query($itemSql, $koneksidb) or die ("Gagal Query detail jasa".mysql_error());
				
				#Update jml_bahan (stok berkurang)
				//$bahanSql = "UPDATE bahanbaku SET jml_bahan	= jml_bahan - $tmpRow[jml_bahan] WHERE kd_bahan='$tmpRow[kd_bahan]'";
		  		//var_dump($bahanSql);exit();
				//$bahanExe =  mysql_query($bahanSql, $koneksidb) or die ("Gagal Query Edit Qty".mysql_error());
			}#end of simpan data tmp_jasa ke jasa_item -----------------
			
			# Kosongkan Tmp jika datanya sudah dipindah
			$sqlDelTmp	= "DELETE FROM tmp_jasa WHERE userid='".$_SESSION['SES_LOGIN']."'";
			//var_dump($sqlDelTmp);exit();
			$exeDelTmp	= mysql_query($sqlDelTmp, $koneksidb) or die ("Gagal kosongkan tmp".mysql_error());
			#refresh halaman -------------
			if($exeDelTmp){
				echo "<meta http-equiv='refresh' content='0; url=nota_jasa.php?noNota=$kodeBaru'>";
			}#end of refresh halaman -----
		}#end of berhasil simpan transaksi ==============================================
	}# end of post - btnSave (simpan trnsaksi) ==========================================
}
# TAMPILKAN DATA KE FORM
$nomorTransaksi = buatKode("transaksi", "TJ");
$tglTransaksi 	= isset($_POST['cmbTanggal']) ? $_POST['cmbTanggal'] : date('d-m-Y');
$dataPelanggan	= isset($_POST['txtPelanggan']) ? $_POST['txtPelanggan']:'';
$dataCatatan	= isset($_POST['txtCatatan']) ? $_POST['txtCatatan'] : '';
?>
<form action="" method="post"  name="frmadd">
<table width="750" cellspacing="1" class="table-common" style="margin-top:0px;">
	<tr>
	  <td colspan="4" align="right"><h1 align="center">TRANSAKSI JASA</h1> </td>
	</tr>
	<tr>
	  <td width="20%"><b>No Transaksi Jasa</b></td>
	  <td width="1%"><b>:</b></td>
	  <td colspan="2"><input name="txtNomor" value="<?php echo $nomorTransaksi; ?>" size="9" maxlength="9" readonly="readonly"/></td></tr>
	<tr>
      <td><b>Tanggal Transaksi</b></td>
	  <td><b>:</b></td>
	  <td colspan="2"><?php echo form_tanggal("cmbTanggal",$tglTransaksi); ?></td>
    </tr>
	<tr>
      <td><b>Pelanggan</b></td>
	  <td><b>:</b></td>
	  <td colspan="2">
      	<select id="cmbPelanggan" name="cmbPelanggan" required>
        	<option value="">pilih pelanggan...</option>
        	<?php
				$dataSql = " SELECT * FROM pelanggan ORDER BY nm_pelanggan asc ";
				$dataQry = mysql_query($dataSql, $koneksidb) or die ("Gagal Query".mysql_error());
				while ($dataRow = mysql_fetch_array($dataQry)){
					if ($dataRow['kd_pelanggan']==$_POST['cmbPelanggan']){
						$cek = "selected";
					} else { $cek=="";}
					echo "<option value='$dataRow[kd_pelanggan]' $cek>$dataRow[nm_pelanggan]</option>";
				}
			?>
        </select>
        </td>
    </tr>
	<tr><td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td colspan="2">&nbsp;</td>
    </tr>
	<tr>
		<td><b>Jasa</b></td>
		<td><b>:</b></td>
		<td colspan="2">
			<input type="text" name="txtKodeJasa"id="txtKodeJasa" size="4" placeholder="kode" readonly>
			<input type="text" name="txtJasa"id="txtJasa" size="40" placeholder="cari jasa berdasarkan nama jasa" >
		</td>
	</tr>
	
	<tr>
		<td></td>
		<td colspan="3" ><p id="infoJasaP">&nbsp;</p></td>
	</tr>
	
	<tr>
		<td><strong>Bahan Baku</strong></td>
		<td><strong>:</strong></td>
		<td>
			<input id="txtKodeBBaku"  name="txtKodeBBaku"  type="text" size="4" placeholder="kode"  readonly="readonly"/>
			<input id="txtNamaBBaku" name="txtNamaBBaku" type="text" size="25" placeholder="bahan baku" readonly="readonly"/>
		</td>
		<td><b> Jumlah :</b>
			<input type="text" name="txtjmlBahan" id="txtjmlBahan" size="2" maxlength="4" placeholder="jumlah"/>
			<input name="btnPilih" id="btnPilih" type="button" style="cursor:pointer;" value=" Pilih " />
		</td>
    </tr>
	
	<tr>
		<td></td>
		<td colspan="3" ><p id="infoBBakuP">&nbsp;</p></td>
	</tr>
	
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td width="20%" ><span id="loadArea" style="color:red;"></span></td>
	  <td width="59%" ><span id="bahanBakuInfo" style="color:red;"></span></td>
    </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td colspan="2"><input name="btnSave" type="submit" style="cursor:pointer;" value=" SIMPAN TRANSAKSI " /></td>
    </tr>
</table>
<table class="table-list" width="750" border="0" cellspacing="1" cellpadding="2">
    	<tr><th colspan="10">DAFTAR JASA </th></tr>
            
        <tr>
            <th width="31" align="center" bgcolor="#CCCCCC"><div align="center"><b>No</b></div></th>
            <th width="69" align="center" bgcolor="#CCCCCC"><b>Kode</b></th>
            <th width="241" bgcolor="#CCCCCC"><b>Nama_Jasa/Bhn</b></th>
            <th width="103" align="right" bgcolor="#CCCCCC"><b>Qty_bhn</b></th>
            <!--<th width="103" align="right" bgcolor="#CCCCCC"><b>@Harga_Bhn</b></th>-->
            <th width="103" align="right" bgcolor="#CCCCCC"><b>Harga_Bhn</b></th>
            <th width="98" align="right" bgcolor="#CCCCCC"><b>Harga_Jasa</b></th>
            <th width="98" align="right" bgcolor="#CCCCCC"><b>ppn</b></th>
            <th width="98" align="right" bgcolor="#CCCCCC"><b>disc</b></th>
            <th width="111" align="right" bgcolor="#CCCCCC"><b>Subtotal</b></th>
            <th width="61" align="center" bgcolor="#FFCC00"><b>hapus</b></th>
        </tr>
    <tbody id="jasaTBL">
    
    </tbody>
</table>
</form>
