<?php
ob_start();
session_start();
require "config.php";
if(isset($_SESSION['center_id']))
{
echo("<script language='javascript'>window.location.href='show_records.php'</script>");
exit;
}


if(isset($_REQUEST['log_submit']))
{
    $utype=$_REQUEST['utype'];
	$admin_uname=$_REQUEST['uname'];
	$admin_pass=base64_encode($_REQUEST['pwd']);
	$status="Y";
	$select=mysql_query("select * from tbl_center where cen_code= '".$admin_uname."' and cen_password ='".$admin_pass."' and cen_status='".$status."'") or die(mysql_error());
	$num_rows = mysql_num_rows($select);
	$row = mysql_fetch_array($select);
	if ($num_rows == 1)
	{				
	
	$insert_log=mysql_query("insert into tbl_center_log(cen_id,modified_by,log_dt) values('".$row["cen_id"]."','".$row["lab_code"]."',NOW())") or die(mysql_error());
				$_SESSION['center_no'] = $row["cen_code"];
				$_SESSION['center_id'] = $row["cen_id"];
				$_SESSION['center_name'] = $row["cen_name"];
				$_SESSION['lab_code'] = $row["lab_code"];
				header("Location:show_records.php");			
	}
	else
	{
	$msg="Invalid Username and Password";
	}
} 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Dengue Surveillance System</title>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
	<link href="css/login.css" rel="stylesheet" type="text/css" />

	
	<script language="javascript" type="text/javascript">
function val()
{
//	if(document.login.utype.value=="")
//	{
//	alert("Select the user type");
//	document.login.utype.focus();
//	return false;
//	}
	if(document.login.uname.value=="")
	{
	alert("Enter your username");
	document.login.uname.focus();
	return false;
	}
	if(document.login.pwd.value=="")
	{
	alert("Enter your password");
	document.login.pwd.focus();
	return false;
	}
}
</script>
</head>
<body>
<!-- Header -->
<div id="header">
	<div class="shell">
		<!-- Logo + Top Nav -->
		<div id="top">
			<h1 align="center"><a href="#">Dengue Surveillance System</a></h1>
			
			</div>
</div>
<!-- End Header -->

<!-- Container -->
<div id="container">
	<table width="50%" align="center" border="0" style="font-size: medium; padding:4px;" cellspacing="0" cellpadding="5">
                  <tr>
                    <td><form id="login" name="login" method="post" action="" onsubmit="return val();"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td align="center"><?php echo str_replace("-","<br>",$result['fromname']);?> Hospital/Lab Login<!--<img src="images/logo-index.jpg" />--></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                        <?php
		  if($msg != "")
		  {
		  ?>
                        <tr>
                          <td height="50"><table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" class="error_table">
                              <tr>
                                <td width="11%" height="30" align="center"><img src="images/icon/error_icon.gif" width="11" height="11" /></td>
                                <td width="89%" height="30" class="error_text"><?php echo $msg; ?></td>
                              </tr>
                          </table></td>
                        </tr>
                        <?php
		  }
		  else
		  {
		  ?>
                        <tr>
                          <td height="10">&nbsp;</td>
                        </tr>
                        <?php
		  }
		  ?>
                        <tr>
                          <td><table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr>
                                <td width="29%" class="login_formtext">User Name</td>
                                <td width="8%"  class="login_formtext">:</td>
                                <td width="63%"><input name="uname" type="text" id="uname" size="30" class="" autocomplete="off" /></td>
                              </tr>
                              <tr>
                                <td class="login_formtext">Password</td>
                                <td class="login_formtext">:</td>
                                <td><input name="pwd" type="password" id="pwd" class="" size="30" /></td>
                              </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td height="60"><table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr>
                                <td colspan="2" align="center"><input name="log_submit" type="submit" class="" style=" padding: 8px 30px; margin-left:2px;" value="Login" /></td>
                              </tr>
                              
                              
                          </table></td>
                        </tr>
                    </table></form></td>
                  </tr>
                </table>
</div>
<!-- End Container -->

<!-- Footer -->
<div id="footer">
	<div class="shell">
		<span class="left">&copy; 2017 - Epihack Sri Lanka</span>
		<!--<span class="right">
			Design by <a href="http://chocotemplates.com" target="_blank" title="The Sweetest CSS Templates WorldWide">Chocotemplates.com</a>
		</span>-->
	</div>
</div>
<!-- End Footer -->
	
</body>
</html>