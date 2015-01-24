<?php
include_once "library/inc.connection.php";	

if($_GET) {
	if($_POST) {
		$message = array();
		if ( trim($_POST['txtUser'])=="") {
			$message[] = "Data <b> User ID </b>  tidak boleh kosong !";		
		}
		if (trim($_POST['txtPassword'])=="") {
			$message[] = "Data <b> Password </b> tidak boleh kosong !";		
		}
		if (trim($_POST['cmbLevel'])=="BLANK") {
			$message[] = "Data <b>Level</b> belum dipilih !";		
		}
		
		# Baca variabel form
		$txtUser 	= $_POST['txtUser'];
		$txtUser 	= str_replace("'","&acute;",$txtUser);
		$txtPassword=$_POST['txtPassword'];
		$txtPassword= str_replace("'","&acute;",$txtPassword);
		$cmbLevel	=$_POST['cmbLevel'];
		
				# Jika jumlah error message tidak ada
		if(count($message)==0){	
			# LOGIN CEK KE TABEL USER LOGIN
			$loginSql = "SELECT * FROM user_login WHERE userid='".$txtUser."' 
						 AND password='".md5($txtPassword)."' AND level='$cmbLevel'";
			$loginQry = mysql_query($loginSql, $koneksidb)  or die ("Query Periksa Password Admin Salah : ".mysql_error());
		
			# JIKA LOGIN SUKSES
			if($loginQry){
				if (mysql_num_rows($loginQry) >=1) {
					$loginData = mysql_fetch_array($loginQry);
					// Jika yang login Administrator
					if($cmbLevel=="Admin") {
						$_SESSION['SES_ADMIN'] = "Admin";
						$_SESSION['SES_LOGIN'] = $loginData['userid']; 
					}
					
					// Jika yang login Kasir
					if($cmbLevel=="Kasir") {
						$_SESSION['SES_KASIR'] = "Kasir";
						$_SESSION['SES_LOGIN'] = $loginData['userid']; 
					}
					
					// Refresh
					echo "<meta http-equiv='refresh' content='0; url=?page=HalamanUtama'>";
				}
				else {
					 echo "Login Anda bukan ".$_POST['cmbLevel'];
				}
			}
		}
		# Jika ada error message ditemukan
		if (! count($message)==0 ){
		?>
            <div class="mssgBox">
			<?php 
			echo "<div><img src='images/Attention2.png' style='margin-bottom:0px;margin-right:5px;margin-left:0px;padding:0px;float:left;'>
				  <h3 style='padding:8px 0px 0px 0px; margin-top:0px;'>ERROR</h3><hr>";
				$Num=0;
				foreach ($message as $indeks=>$pesan_tampil) { 
				$Num++;
					echo "&nbsp;&nbsp;$Num. $pesan_tampil<br>";	
				} 
			echo "</div><div>&nbsp;</div>";?>
            </div>
			<?php 
		}
	}
}
?>
 
