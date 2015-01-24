<?php
include_once "library/inc.sesadmin.php";
include_once "library/inc.library.php";
?>
<form action="?page=Daftar-Barang-Kategori-Show" method="post" name="form1" target="_self">
  <table width="500" border="0" cellpadding="2" cellspacing="1" class="table-list">
    <tr>
      <th colspan="3"><b>PILIH KATEGORI </b></th>
    </tr>
    <tr>
      <td width="155"><b>Nama Kategori </b></td>
      <td width="5"><b>:</b></td>
      <td width="326"><select name="cmbKategori">
        <option value="BLANK"></option>
        <?php
	  $dataSql = "SELECT * FROM kategori where jns_kategori = 1 ORDER BY kd_kategori";
	  $dataQry = mysql_query($dataSql, $koneksidb) or die ("Gagal Query".mysql_error());
	  while ($dataRow = mysql_fetch_array($dataQry)) {
	  	if ($dataRow['kd_kategori'] == $kdKategori) {
			$cek = " selected";
		} else { $cek=""; }
	  	echo "<option value='$dataRow[kd_kategori]' $cek>$dataRow[nm_kategori]</option>";
	  }
	  $sqlData ="";
	  ?>
      </select>        <input name="btnFilter" type="submit" value=" Tampilkan " /></td>
    </tr>
  </table>
  <p><a href="?page=Laporan-Data" target="_self" alt="Laporan-Data"><img src="images/back.png" width="30" height="30" /></p></a>
</form>