<?php
include_once "library/inc.sesadmin.php";
if($_GET){
	if(empty($_GET['Kode'])){
		echo "<b>Data yang dihapus tidak ada</b>";
	}
	else{
		$sqlDelete = "DELETE FROM barang WHERE kd_barang='".$_GET['Kode']."'";
		$qryDelete = mysql_query($sqlDelete, $koneksidb) or die ("Error hapus data".mysql_error());
		if($qryDelete){
			echo "<meta http-equiv='refresh' content='0; url=?page=Data-Barang'>";
		}
	}
}
?>