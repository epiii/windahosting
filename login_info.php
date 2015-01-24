<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
</head>
<body>
<?php
$loginSql = "SELECT * FROM user_login WHERE userid='".$_SESSION['SES_LOGIN']."'";
$loginQry = mysql_query($loginSql, $koneksidb)  or die ("Query user salah : ".mysql_error());
$nomor  = 0; 
$loginRow = mysql_fetch_array($loginQry);
?> <br><br>
<table width="600" border="0" class="table-list">
  <tr>
    <td colspan="3"><strong>INFO LOGIN </strong></td>
  </tr>
  <tr>
    <td width="195">User ID </td>
    <td width="10"><strong>:</strong></td>
    <td width="381"><?php echo $loginRow['userid']; ?></td>
  </tr>
  <tr>
    <td>Nama Anda </td>
    <td><strong>:</strong></td>
    <td><?php echo $loginRow['nama']; ?></td>
  </tr>
</table>
</body>
</html>
