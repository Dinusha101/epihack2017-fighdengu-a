<?php
$host = "localhost";

$user = "root";

$pwd = "";

$connection = mysql_connect($host,$user,$pwd) or die('Could not Connect:');

$db = "Epihack";

mysql_select_db($db) or die("DB Connection Failed!");

/*$host = "localhost";

$user = "demoamri_ksacade";

$pwd = "ksa@2012";

$connection = mysql_connect($host,$user,$pwd);

$db = "demoamri_ksacademy";

mysql_select_db($db) or die("DB Connection Failed!");
*/

$query=mysql_query("select * from tbl_settings") or die(mysql_error());

$result=mysql_fetch_array($query);


?>
