<?php
ob_start();
session_start();
include_once "library/inc.connection.php";
include_once "library/inc.library.php";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>:: Printshop-Healthy Solution For Printer</title>
<link href="styles/style.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="plugins/dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen" />
<script type="text/javascript" src="plugins/dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script> 
<script type="text/javascript">
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script></head>
<div id="wrap">
<body>
<table width="100%" class="table-main">
  <tr>
    <td height="103" colspan="2"><a href="?page"><div id="header"></div>
    </a></td>
  </tr>
  <tr valign="top">
    <td width="15%"  style="border-right:5px solid #DDDDDD;"><div style="margin:5px; padding:5px;"><?php include "menu.php"; ?></div></td>
    <td width="69%" height="550"><div style="margin:5px; padding:5px;"><?php include "load_file.php";?></div></td>
  </tr>
</table>
</body>
</div>
</html>
