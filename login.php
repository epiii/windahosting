<div><center><form action="?page=Login-Validasi" method="post" name="logForm" target="_self">
  <table class="table-common" width="500" border="0" cellpadding="2" cellspacing="1" bgcolor="#999999">
    <tr>
      <td width="106" rowspan="5" align="center" bgcolor="#FFFFFF"><img src="images/login-key.jpg" width="116" height="75" /></td>
      <th colspan="2" bgcolor="#CCCCCC"><b>LOGIN TOKO </b></td> 
    </tr>
    <tr>
     <td width="117" bgcolor="#FFFFFF"><b>User Login </b></td>
      <td width="263" bgcolor="#FFFFFF"><b>: 
      <input name="txtUser" type="text" size="30" maxlength="20" />
      </b></td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF"><b>Password</b></td>
      <td bgcolor="#FFFFFF"><b>:
        <input name="txtPassword" type="password" id="txtPassword" size="30" maxlength="20" />
      </label></td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF"><b>Level</b></td>
      <td bgcolor="#FFFFFF"><b>:
   <select name="cmbLevel">
              <option value="BLANK"> </option>
              <?php
		  $arrHak	= array("Kasir", "Admin");
          foreach ($arrHak as $index => $value) {
            if ($_POST['cmbLevel']==$value) {
                $cek="selected";
            } else { $cek = ""; }
            echo "<option value='$value' $cek>$value</option>";
          }
          ?>
            </select>
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><label>
        <input type="submit" name="btnlogin" id="btnlogin" value="Login" />
      </label></td>
    </tr>
  </table>
</form>
</center>
  <div>
    admin <br>
    - user : admin <br>
    - pass : admin <br>
    kasir <br>
    - user : kasir <br>
    - pass : kasir <br>
  </div></div> 