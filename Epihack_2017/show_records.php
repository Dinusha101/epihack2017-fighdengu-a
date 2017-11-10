<?php
ob_start();
session_start();
require "config.php";
require_once "myfunctions.php";
require_once("CSSPagination.class.php"); 
require_once("CSSPagination1.class.php"); 


if(!isset($_SESSION['center_id']))
	 {
	  echo("<script language='javascript'>window.location.href='index.php'</script>");
	  exit;
	 }
$user_id=base64_decode($_REQUEST['id']);
function getOwnURL() 
  { 
  $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : ""; 
  $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; 
  $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); 
  return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI']; 
  } 
  
function strleft($s1, $s2) 
  { 
  return substr($s1, 0, strpos($s1, $s2)); 
  }
$cont=getOwnURL();

$qqqq=mysql_query("select * from tbl_settings") or die(mysql_error());
$rrrr=mysql_fetch_array($qqqq);
$StartRow = 0;
$StartRow1 = 0;

$cond.="";
if($_REQUEST['skey']!="")
{
$cond.="and (pat_crno='".base64_decode($_REQUEST['skey'])."' or pat_aesno='".base64_decode($_REQUEST['skey'])."')";
}


$sql1="select * from tbl_patient_data where pat_center='".$_SESSION['center_id']."' AND  pat_status='Y' $cond ORDER BY pat_id";
$rowsperpage =100; // $rrrr['set_bpsize'];
$website = $_SERVER['PHP_SELF']."?skey=".$_REQUEST['skey']."&year=".$_REQUEST['year'];
$pagination = new CSSPagination($sql1, $rowsperpage, $website); 
$pagination->setPage($_GET[page]); 
$qsql1 = @mysql_query($sql1, $connection) or die("failed12");

$sql3="select * from tbl_patient_data where pat_center='".$_SESSION['center_id']."' AND pat_status='N' $cond group by pat_id";
$rowsperpage1 = $rrrr['set_bpsize'];
$website1 = $_SERVER['PHP_SELF']."?skey=".$_REQUEST['skey']."&year=".$_REQUEST['year'];
$pagination1 = new CSSPagination1($sql3, $rowsperpage1, $website1); 
$pagination1->setPage1($_GET[page1]); 
$qsql3 = @mysql_query($sql3, $connection) or die("failed");

if(isset($_REQUEST['search']))
{
echo'<script language=javascript>window.location.href="show_records.php?skey='.base64_encode($_REQUEST['sear_key']).'&year='.$_REQUEST["year_code"].'"</script>';
}
if(isset($_REQUEST['del']))
{
		$del=base64_decode($_REQUEST['del']);
		$update=mysql_query("update tbl_patient_data set pat_status='D' where pat_id='".$del."'") or die(mysql_error());
		
		echo'<script language=javascript>alert("Deleted successfully");</script>';
		echo'<script language=javascript>window.location.href="show_records.php"</script>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Dengue Surveillance System</title>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
</head>
<body>
<!-- Header -->
<div id="header">
	<div class="shell">
		<!-- Logo + Top Nav -->
		<div id="top">
			<h1><a href="#">Dengue Surveillance System</a></h1>
			<div id="top-navigation">
				Welcome <a href="#"><strong>CMC</strong></a>
				<span>|</span>
				<a href="#">Change Password</a>
				<span>|</span>
				<a href="#"> Settings</a>
				<span>|</span>
				<a href="logout.php">Log out</a>
			</div>
		</div>
		<!-- End Logo + Top Nav -->
		
		<!-- Main Nav -->
		<div id="navigation">
			<ul>
			    <li><a href="show_records.php" class="active"><span><strong>Show all records</strong></span></a></li>
			    <li><a href="add_records.php"><span><strong>Add records</strong></span></a></li>
			    <li><a href="upload_data.php"><span><strong>Data Management</strong></span></a></li>
				<li><a href="download_data.php"><span><strong>Data Download</strong></span></a></li>
			</ul>
		</div>
		<!-- End Main Nav -->
	</div>
</div>
<!-- End Header -->

<!-- Container -->
<div id="container">
	<div class="shell">
		
		
		<!-- Main -->
		<div id="main">
			<div class="cl">&nbsp;</div>
			
			<!-- Content -->
			<div id="content">
				
				<!-- Box -->
				<div class="box">
					<!-- Box Head -->
					<div class="box-head">
						<h2 class="left" style="padding:0px;">Patient details<br /></h2>
						
						<div class="right">
							<!--<label>search articles</label>
							<input type="text" class="field small-field" />
							<input type="submit" class="button" value="search" />-->
						</div>
					</div>
					<!-- End Box Head -->	

					<!-- Table -->
					<div class="table">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						
						 <?php
					$RecordCount = mysql_num_rows($qsql1);
					if($RecordCount==0)
					{
					?>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="no_recordbg">
                          <tr>
                            <td height="100" align="center">NO RECORDS AVAILABLE</td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php
					 }
					 else
					 {	
					?>
						
							<tr>
								<th width="13">Sno</th>
								<th>Name</th>
								<th>NIC</th>
								<th>Date</th>
								<th width="110" class="ac">Delete</th>
								<th width="110" class="ac">Edit</th>
							</tr>
							 <?php
							$sno=$pagination->getLimit() + 1;
							$sql2 = $sql1." limit " . $pagination->getLimit() . ", ". $rowsperpage; 
							$res2 = @mysql_query($sql2, $connection) or die("failed");
							$i = 1;	
							$c=1;
							$k=0;		
							while($res=mysql_fetch_array($res2))
							{
							
							
							?>
							<tr>
								<td><?php echo $sno; ?>.</td>
								<td><h3><?php echo $res['pat_name']; ?></h3></td>
								<td><?php echo $res['pat_nic']; ?></td>
								<td><?php echo date('d-M-Y',strtotime($res['pat_invdate'])); ?></td>
								<td><a href="show_records.php?del=<?php echo base64_encode($res['pat_id']); ?>" class="ico del"  onclick="return confirm('Are you sure want to delete this record ! ');">Delete</a></td><td><a href="add_records.php?q=<?php echo base64_encode($res['pat_id']); ?>" class="ico edit">Edit</a></td>
							</tr>
							
							<?php
							$c++;
							$i++;
							$k++;	
							$sno++;
							if($k==2)
							{
							$k=0;
							}
							}
							}
							?>
						</table>
						
						
						<!-- Pagging -->
						<!--<div class="pagging">
							<div class="left">Showing 1-12 of 44</div>
							<div class="right">
								<a href="#">Previous</a>
								<a href="#">1</a>
								<a href="#">2</a>
								<a href="#">3</a>
								<a href="#">4</a>
								<a href="#">245</a>
								<span>...</span>
								<a href="#">Next</a>
								<a href="#">View all</a>
							</div>
						</div>-->
						<!-- End Pagging -->
						
					</div>
					<!-- Table -->
					
				</div>
				<!-- End Box -->
				
				<!-- Box -->
				
				<!-- End Box -->

			</div>
			<!-- End Content -->
			
			<!-- Sidebar -->
			
			<!-- End Sidebar -->
			
			<div class="cl">&nbsp;</div>			
		</div>
		<!-- Main -->
	</div>
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