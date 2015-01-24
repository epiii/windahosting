<?php
$myHost	="localhost";
$myUser	="root";
$myPass	="";
$myDbs	="printshopdb";

// $myHost	="mysql.idhostinger.com";
// $myUser	="u657795192_winda";
// $myPass	="1tambah1=2";
// $myDbs	="u657795192_winda";



$koneksidb	=	mysql_connect($myHost, $myUser, $myPass);
if (!$koneksidb) {
	echo "Failed Connection !";
}
mysql_select_db($myDbs) or die ("Database not Found !");
?>