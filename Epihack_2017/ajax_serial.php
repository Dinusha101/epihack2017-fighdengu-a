<?php
ob_start();
session_start();
require "config.php";
$slid=$_REQUEST['slid'];
$sid=$_REQUEST['yid'];
$rid=$_REQUEST['q'];

if($rid!="")
{
$rcid=" and pat_id!=".$rid;
}

$qry = mysql_query("select * from tbl_patient_data where pat_status!='D' and pat_refno='$slid' $rcid
 order by pat_id asc") or die(mysql_error());
$nu=mysql_num_rows($qry);
if($nu==0)
{
 echo "OK";
}
else
{
 echo "NO";
}
?>
