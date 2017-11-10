<?php session_start();
require_once "../config.php";
/* RECEIVE VALUE */
$s="";
$idno=$_REQUEST['idno'];
$egid=$_REQUEST['egid'];
$state=$_REQUEST['state'];
if($egid!='')
{
$s.=" and gid!='".$egid."'";
}
if($state!='')
{
$s.=" and state='".$state."'";
}
$validateError= "This username is already taken";
$validateSuccess= "This username is available";
	/* RETURN VALUE */
	$arrayToJs = array();
	$arrayToJs[0] = array();
$select_au=mysql_query("select * from tbl_patient_data where pat_center='".$_SESSION['center_id']."' and pat_status!='D' and pat_refno='$slid' $rcid
 order by pat_id asc");
$mail_found=mysql_num_rows($select_au);
if($mail_found == 0){		// validate??
	$arrayToJs[0][0] = 'idno';
	$arrayToJs[0][1] = true;			// RETURN TRUE
	$arrayToJs[0][2] = "This ID. No is valid";
			// RETURN ARRAY WITH success
}else{
	$arrayToJs[0][0] = 'idno';
	$arrayToJs[0][1] = false;
	$arrayToJs[0][2] = "This ID. No is already Exisit";
}
echo json_encode($arrayToJs);
?>