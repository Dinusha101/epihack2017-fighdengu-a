<?php
ob_end_clean();
session_start();
require "config.php";
require_once("myfunctions.php");
if(!isset($_SESSION['center_id']))
	 {
	  echo("<script language='javascript'>window.location.href='index.php'</script>");
	  exit;
	 }

$from=date('Y-m-d',strtotime($_REQUEST['datefrom']));
$to=date('Y-m-d',strtotime($_REQUEST['dateto']));

$cond="";

if($from!="" && $to!="" && $from<$to)
{
	$cond.="and pat_invdate BETWEEN '$from' and '$to'";
}

$result=mysql_query("select * from  tbl_patient_data where pat_status='Y' $cond group by pat_id");

$num_fields = mysql_num_fields($result);

// Fetch MySQL result headers
$headers = array();
$headers[] = "Sno";
for ($i = 0; $i < $num_fields; $i++) {
    $headers[] = strtoupper(mysql_field_name($result , $i));
}


$result=mysql_query("select * from tbl_patient_data where pat_status='Y' $cond group by pat_id");

$num_fields = mysql_num_fields($result);

// Filename with current date
$current_date = date("y/m/d");
$filename = "Dengue-data-" . $current_date . ".csv";

// Open php output stream and write headers
$fp = fopen('php://output', 'w');
if ($fp && $result) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='.$filename);
    header('Pragma: no-cache');
    header('Expires: 0');
    //echo "Identification \n\n";
    // Write mysql headers to csv
    fputcsv($fp, $headers);
    $row_tally = 0;
    // Write mysql rows to csv
    while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    $row_tally = $row_tally + 1;
    echo $row_tally.",";
        fputcsv($fp, array_values($row));
    }
	//mysql_query("update tbl_test set file_time='".date('H:i:s')."' where id='$tid'"); 
	//mysql_query("DROP TABLE ".$table);
    die;
	}
	?>