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
$rowsperpage = $rrrr['set_bpsize'];
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
echo'<script language=javascript>window.location.href="upload_data.php?skey='.base64_encode($_REQUEST['sear_key']).'&datefrom='.$_REQUEST["date_from"].'&dateto='.$_REQUEST["date_to"].'"</script>';
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
	
	<link rel="StyleSheet" href="cal_styles.css" type="text/css" />
<SCRIPT src="cal_classes.js" type=text/javascript></SCRIPT>


<script type="text/javascript" src="js/jquery-1.7.1.min.js"> </script>
<link rel="stylesheet" href="calendar/css/jquery-ui.css" />
<script src="calendar/js/jquery-ui.js"></script>

<script src="js/ui/jquery.ui.datepicker.js"></script>
<script type="text/javascript" language="javascript">

var calendar;

 function newcal(id) {

	calendar = new myCal("date_from","popup",document.getElementById(id),"",620);

	calendar.toggle();

}

jQuery(document).ready(function(){
	
	
  	jQuery( "#applied_date1" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		buttonImage: "images/calendar2.gif",
		maxDate: <?php echo date('d/m/Y'); ?>,
		buttonImageOnly: true
    });
    jQuery( "#expire_date1" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		buttonImage: "images/calendar2.gif",
		maxDate: <?php echo date('d/m/Y'); ?>,
		buttonImageOnly: true
    });
    
	});
</SCRIPT>

<SCRIPT type=text/javascript>

var calendar;

 function newcal(id) {

	calendar = new myCal("date_to","popup",document.getElementById(id),"",660);

	calendar.toggle();

}

</SCRIPT>
<script type="text/javascript">
function val_date()
{
	//alert("hello");
	var str=document.frm_sear_user.date_from.value;
	var chunks = str.split("-");
	var frm=chunks[2]+chunks[1]+chunks[0];
	
	var str1=document.frm_sear_user.date_to.value;
	var chunks1 = str1.split("-");
	var to=chunks1[2]+chunks1[1]+chunks1[0];
	
	if(parseInt(frm)>parseInt(to))
	{
		alert("From date can not be greater than to date");
		document.frm_sear_user.date_to.focus();
		return false;
	}
	
	if((document.frm_sear_user.date_from.value=='') && (document.frm_sear_user.date_to.value!=''))
	{
		alert("Select from date");
		document.frm_sear_user.date_from.focus();
		return false;
	}
	if((document.frm_sear_user.date_from.value!='') && (document.frm_sear_user.date_to.value==''))
	{
		alert("Select to date");
		document.frm_sear_user.date_to.focus();
		return false;
	}
	
	//return false;
	
}
</script>

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
			    <li><a href="show_records.php"><span><strong>Show all records</strong></span></a></li>
			    <li><a href="add_records.php"><span><strong>Add records</strong></span></a></li>
			    <li><a href="upload_data.php" class="active"><span><strong>Data Management</strong></span></a></li>
				<li><a href="download_data.php" ><span><strong>Data Download</strong></span></a></li>
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
						<h2 class="left" style="padding:0px;">School Details<br /></h2>
						
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
						<tr>
                                            <!--  Applied date -->
                 
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">District</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><span id="proimage1">
                    <select name="sch_division" id="sch_division" class="custom-select validate[]">
                      <option value=''>None - Please Select</option>
                      <?php
                        $SelSchool=mysql_query("select distinct District  from street_details") or die("failed");
                        while($resSchool=mysql_fetch_array($SelSchool))
                        {         
                        ?>
                                          <option value="<?php echo $resSchool['District'];?>"><?php echo $resSchool['District'];?></option>
                                          <?php
                        }
                        ?>
                    </select>
                    </span> </td>
                </tr>



                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Division</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><span id="proimage1">
                    <select name="sch_division" id="sch_division" class="custom-select validate[]">
                      <option value=''>None - Please Select</option>
                      <?php
                        $SelSchool=mysql_query("select distinct DistrictID  from street_details") or die("failed");
                        while($resSchool=mysql_fetch_array($SelSchool))
                        {         
                        ?>
                                          <option value="<?php echo $resSchool['DistrictID'];?>"><?php echo $resSchool['DistrictID'];?></option>
                                          <?php
                        }
                        ?>
                    </select>
                    </span> </td>
                </tr>
              


 
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">School Name*</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_name" id="pat_name" type="text" class="validate[required]" maxlength=""  value="<?php echo $res['pat_name']; ?>" /></td>
                </tr>

<tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Address 1</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_name" id="pat_name" type="text" class="validate[required]" maxlength=""  value="<?php echo $res['pat_name']; ?>" /></td>
                </tr>
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Address 2</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_name" id="pat_name" type="text" class="validate[required]" maxlength=""  value="<?php echo $res['pat_name']; ?>" /></td>
                </tr>
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Address 3</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_name" id="pat_name" type="text" class="validate[required]" maxlength=""  value="<?php echo $res['pat_name']; ?>" /></td>
                </tr>
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Street Name</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_name" id="pat_name" type="text" class="validate[required]" maxlength=""  value="<?php echo $res['pat_name']; ?>" /></td>
                </tr>
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Latitude</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_name" id="pat_name" type="text" class="validate[required]" maxlength=""  value="<?php echo $res['pat_name']; ?>" /></td>
                </tr>
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Longitude</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_name" id="pat_name" type="text" class="validate[required]" maxlength=""  value="<?php echo $res['pat_name']; ?>" /></td>
                </tr>
                



              

                <tr>
                  <td colspan="3" align="center"><input name="btn_submit" type="submit" class="" value="Submit" />
                    <input type="hidden" name="hid_ajax" id="hid_ajax" <?php if($q) { ?> value="1" <?php } else { ?> value="" <?php } ?> /></td>
                </tr>
          <td class="innertop_tablepad"><form method="post" name="frm_sear_user" id="frm_sear_user" action="">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                
              </table>
            </form></td>
        </tr>
		<tr align="right"><td colspan="3"><?php if($_REQUEST["datefrom"]!="" && $_REQUEST["dateto"]!="") { ?><a href="download_complete.php?datefrom=<?php echo $_REQUEST["datefrom"]; ?>&dateto=<?php echo $_REQUEST["dateto"]; ?>" > <img src="images/download-but.jpg"  border="0"/></a> <?php } ?></td></tr>
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
				<div class="box">
					<!-- Box Head -->
					<div class="box-head">
						<h2 class="left" style="padding:0px;">Control Measurement Details<br /></h2>
						
						<div class="right">
    
						</div>
						
					</div>
					<div>
								          <div class="table">
            <form method="post" enctype="multipart/form-data" name="frm_pages1" id="frm_pages1" action="jq-ajax/ajaxIDNO.php">
                
                
                
                
 





              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <input name="cen_id" id="cen_id" type="hidden" size="15" value="<?php echo $_SESSION['center_id'];?>"  readonly=""/>
                
                <!--  Type -->
                <tr>
                  <td width="27%" class="form_text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Test Done </td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><select name="control_type" id="control_type" class="validate[required]">
                      <option value="">Select Test</option>
                      <option value="ICON" <?php if($val1['virus_test_done']=="ICON") { ?> selected="selected" <?php } ?>>ICON</option>
                      <option value="Abate" <?php if($val1['virus_test_done']=="Abate") { ?> selected="selected" <?php } ?>>Abate</option>
                      <option value="Fogging" <?php if($val1['virus_test_done']=="Fogging") { ?> selected="selected" <?php } ?>>Fogging</option>
                      <option value="Aquatain" <?php if($val1['virus_test_done']=="Aquatain") { ?> selected="selected" <?php } ?>>Aquatain</option>
                      <option value="Dunks" <?php if($val1['virus_test_done']=="Dunks") { ?> selected="selected" <?php } ?>>Dunks</option>
                    </select>
                  </td>
                </tr>


                <!--  Applied date -->
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Date of Applied</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="applied_date1" id="applied_date1" readonly="readonly" type="text"   value="<?php echo ($res['applied_date'])&&($res['applied_date']!="0000-00-00")?date('d/m/Y',strtotime($res['applied_date'])):"";?>" />
                  </td>
                </tr>

                <!--  expire date -->
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Date of Expire</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="expire_date1" id="expire_date1"  readonly="readonly" type="text"  class="validate[required] datepicker" value="<?php echo ($res['expire_date'])&&($res['expire_date']!="0000-00-00")?date('d/m/Y',strtotime($res['expire_date'])):"";?>" />
                  </td>
                </tr>

             <!--  Applied Area  -->
              
 
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Aplied Area*</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_name" id="pat_name" type="text" class="validate[required]" maxlength=""  value="<?php echo $res['pat_name']; ?>" /></td>
                </tr>


                



              

                <tr>
                  <td colspan="3" align="center"><input name="btn_submit" type="submit" class="" value="Submit" />
                    <input type="hidden" name="hid_ajax" id="hid_ajax" <?php if($q) { ?> value="1" <?php } else { ?> value="" <?php } ?> /></td>
                </tr>
              </table>
              *  Mandatory fields
            </form>
           
          </div>
          <!-- Table -->
								</div>
			</div>
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