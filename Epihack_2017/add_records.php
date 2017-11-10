<?php
//ob_start();
session_start();
require "config.php";
require_once("myfunctions.php");
unset($_SESSION['sample']);
$qqqq=mysql_query("select * from tbl_settings") or die(mysql_error());
$rrrr=mysql_fetch_array($qqqq);
if(!isset($_SESSION['center_id']))
	 {
	  echo("<script language='javascript'>window.location.href='index.php'</script>");
	  exit;
	 }
	
$sq1=mysql_query("select * from tbl_center where cen_id='".$_SESSION['pat_id']."'")or die(mysql_error());
$re1=mysql_fetch_array($sq1);
$id = base64_decode($q);
$id1 = base64_decode($q1);
if($id)
{
	$res_prev = select_query("tbl_patient_data","*","pat_id='".$id."'","1");
}

//print_r($_SESSION['page1']);
//unset($_SESSION['page1']);



if($action=="page1-2" || $action=="")
{
#update Product
	if($q)
	{
		if(isset($btn_submit) || isset($jQ))
		{
		$selWardD=mysql_query("select * from street_details where street='".$_REQUEST['pat_street']."'") or die("failed");
		$resWard=mysql_fetch_array($selWardD);
		
		$selDisD=mysql_query("select * from hospital_details where AreaHospitals='".$_REQUEST['pat_institution']."'") or die("failed");
		$resDis=mysql_fetch_array($selDisD);
		
			$arr1 = array(
							'pat_dob'=>forDate($pat_dob1),
							'pat_invdate'=>forDate($pat_invesdate1),
							'pat_testdate'=>forDate($virus_test_date),
							'pat_sward'=>$resWard['Ward'],
							'pat_ward'=>implode(",",$_REQUEST['pat_ward1']),
							'pat_district'=>$resWard['District'],
							'ins_district'=>$resDis['District'],
							'pat_caldays'=>$_REQUEST['cal_days'],
              'pat_death_date'=>forDate($pat_death_date1),
              'pat_admission_date'=>forDate($pat_admission_date1),
              'pat_onsetdate'=>forDate($pat_onsetdate1)
							);
			$upd = update("tbl_patient_data","pat_id='".$id."'",$arr1);
			if($upd)
			{
				echo'<script language=javascript>alert("Records updated successfully.");</script>';
				echo'<script language=javascript>window.location.href="add_records.php"</script>';
			}
		}
		//$res = select_query("tbl_product","*","prod_id='".$id."'","1");
	}
	else
	{
		#new product
		if(isset($btn_submit) || isset($jQ))
		{
		$dt=date("Y-m-d");
		
		$selWardD=mysql_query("select * from street_details where street='".$_REQUEST['pat_street']."'") or die(mysql_error());
		$resWard=mysql_fetch_array($selWardD);
		
		$selDisD=mysql_query("select * from hospital_details where AreaHospitals='".$_REQUEST['pat_institution']."'") or die("failed1");
		$resDis=mysql_fetch_array($selDisD);
		
    
			$arr2 = array(
							'pat_center'=>$_SESSION['center_id'],
							'pat_dob'=>forDate($pat_dob1),
							'pat_invdate'=>forDate($pat_invesdate1),
							'pat_testdate'=>forDate($virus_test_date),
							'pat_date'=>date('Y-m-d'),
							'pat_status'=>'Y',
							'pat_sward'=>$resWard['Ward'],
							'pat_ward'=>implode(",",$_REQUEST['pat_ward1']),
							'pat_district'=>$resWard['District'],
							'ins_district'=>$resDis['District'],
							'pat_caldays'=>$_REQUEST['cal_days'],
              'pat_death_date'=>forDate($pat_death_date1),
              'pat_admission_date'=>forDate($pat_admission_date1),
              'pat_onsetdate'=>forDate($pat_onsetdate1)
             // 'pat_address'=>$_REQUEST['pat_address']

              						
						);
						

						
			$ins = insert("tbl_patient_data","",$arr2,"");
			if($ins['id'])
			{
				echo'<script language=javascript>alert("Records added successfully.");</script>';
				echo'<script language=javascript>window.location.href="add_records.php"</script>';
			}
			else
			{
				$res = $_REQUEST;
			}
		}
	}
}
else if($action=="page3")
{
	if(isset($btn_submit))
	{
		//$val=$_REQUEST;
		//print_r($_REQUEST);
		
		$arr6 = array(
						'pat_sampledate1'=>forDate($sample_blooddate1),
						'pat_sampletestdate1'=>forDate($virus_test_date1),
					);
		
				
			if($id!="")
			{
				$upd4 = update("tbl_patient_data","pat_id='".base64_decode($_REQUEST['q'])."'",$arr6);
			}			
		
		
		if($upd4 || $ins4)
		{
			echo'<script language=javascript>alert("Records Updated Successfully.!");</script>';
			echo'<script language=javascript>window.location.href="view_general_information.php"</script>';	}
	//$res = select_query("tbl_product","*","prod_id='".$id."'","1");
}
}

#update
if($q)
{
	$res = select_query("tbl_patient_data","*","pat_id='".$id."'","1");
	if($res['pat_id'])
	{
		$checkbasicval = 1;
	}
}
if($q1)
{
	$res1 = select_query("tbl_patient_data","*","pat_id='".$id1."'","1");
	if($res1['pat_id'])
	{
		$checkbasicval = 1;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Dengue Surveillance System</title>
<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
<link href="css/css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<link rel="stylesheet" href="calendar/css/jquery-ui.css" />
<script src="calendar/js/jquery-ui.js"></script>
<link rel="stylesheet" href="jq-val/css/validationEngine.jquery.css" type="text/css"/>
<script src="jq-val/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="jq-val/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<!--<link rel="stylesheet" href="js/jquery-ui.css" />
<script src="js/jquery-ui.js"></script>-->
<script src="js/ui/jquery.ui.datepicker.js"></script>
<script type="text/javascript" language="javascript">
		function beforeCall(form, options){
			if (window.console) 
			console.log("Right before the AJAX form validation call");
			return true;
		}
            
		// Called once the server replies to the ajax form validation request
		function ajaxValidationCallback(status, form, json, options){
			if (window.console) 
			console.log(status);
                
			if (status === true) {
				// uncomment these lines to submit the form to form.action
				 form.validationEngine('detach');
				 form.attr("method","post");
				 <?php
				 if(isset($_REQUEST['q']) || isset($_REQUEST['q1']))
				 {
				 ?>
				 form.attr("action","add_records.php?action=page1-2<?php if($_REQUEST['q']){echo "&q=".$q;}else{echo "";} ?><?php if($_REQUEST['q1']){echo "&q1=".$q1;}else{echo "";} ?>&jQ");
				 <?php
				 }
				 else
				 {
				 ?>
				 form.attr("action","add_records.php?action=page1-2&jQ");
				 <?php
				 }
				 ?>
				 form.submit();
				// or you may use AJAX again to submit the data
			}
		}
 jQuery(document).ready(function(){
		jQuery("#frm_pages").validationEngine();
		//$(".datepicker").datepicker();
		jQuery("#frm_pages1").validationEngine({
			ajaxFormValidation: true,
			ajaxFormValidationMethod: 'post',				
			onBeforeAjaxFormValidation: beforeCall,				
			onAjaxFormComplete: ajaxValidationCallback,
		});
		jQuery( "#pat_dob1" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		buttonImage: "images/calendar2.gif",
		maxDate: <?php echo date('d/m/Y'); ?>,
		buttonImageOnly: true
		});
  	jQuery( "#pat_invesdate1" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		buttonImage: "images/calendar2.gif",
		maxDate: <?php echo date('d/m/Y'); ?>,
		buttonImageOnly: true
    });
    jQuery( "#pat_admission_date1" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		buttonImage: "images/calendar2.gif",
		maxDate: <?php echo date('d/m/Y'); ?>,
		buttonImageOnly: true
    });
    jQuery( "#pat_onsetdate1" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		buttonImage: "images/calendar2.gif",
		maxDate: <?php echo date('d/m/Y'); ?>,
		buttonImageOnly: true
    });
    jQuery( "#pat_death_date1" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		buttonImage: "images/calendar2.gif",
		maxDate: <?php echo date('d/m/Y'); ?>,
		buttonImageOnly: true
    });
		jQuery( "#virus_test_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		buttonImage: "images/calendar2.gif",
		maxDate: <?php echo date('d/m/Y'); ?>,
		buttonImageOnly: true
		});
	});
</script>
<script type="text/javascript">
function monthDiff(d2, d1) {
	//alert(d2);

    var months;
	var timeDiff = Math.abs(d1.getTime() - d2.getTime());
var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24 * 365.25)); 

var months=Math.floor(diffDays-1);
    /*months = (d2.getFullYear() - d1.getFullYear()) * 12;
    months -= d1.getMonth() + 1;
    months += d2.getMonth();*/
    return months <= 0 ? 0 : months;
}

function monthCalc()
{
	//alert(d1);
	var invdt=document.getElementById('pat_invesdate1').value;
	var newdtinv=trim(invdt).split("/");
	//print(newdt);
	var indt2=newdtinv[2] +"-"+ newdtinv[1] +"-"+ newdtinv[0];
	d1 = new Date(indt2);
	//alert(d1);
	var dob=document.getElementById('pat_dob1').value;
	var newdt=trim(dob).split("/");
	//print(newdt);
	var nd2=newdt[2] +"-"+ newdt[1] +"-"+ newdt[0];
	//nd1=trim(nd2);
	//alert(nd2);
	
    d2 = new Date(nd2);
	//alert(d2);
	document.getElementById('cal_days').readOnly="true";
	document.getElementById('cal_days').value=monthDiff(d1, d2);
    //alert(monthDiff(d1, d2));
}
function checkSno(field, rules, i, options){
		if(document.getElementById("hid_ajax").value=="")
			{
//			alert("serial Number  already exist !");
			return options.allrules.validate2Snl.alertText;
//			return false;
			}
}
function divenable(val,div,matc)
{

	if(val==matc)
	{
		document.getElementById(div).style.display='block';	
	}
	else
	{
		document.getElementById(div).style.display='none';	
	}
}	

function divenables_dob(val,div,matc,bdiv)
{

	if(val==matc)
	{
		document.getElementById(div).style.display='block';	
		document.getElementById(bdiv).style.display='none';	
	}
	else
	{
		document.getElementById(div).style.display='none';
		document.getElementById(div).style.display='none';	
	}
}	
</script>
<script language="javascript">
var xmlHttp9
function ajaxsnl(str)
{
//alert(this.value);
	
xmlHttp9=GetXmlHttpObject()
if (xmlHttp9==null)
 {
 alert ("Browser does not support HTTP request")
 return
 }

var a="";
<?php
if($_REQUEST['q'])
{
?>
var val=<?php echo base64_decode($_REQUEST['q']); ?>;
var a="&q="+val;
<?php
}
?>
var url9="ajax_serial.php"
url9=url9+"?slid="+str+a
xmlHttp9.onreadystatechange=stateChanged9
xmlHttp9.open("GET",url9,true)
xmlHttp9.send(null)
}
function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}
function stateChanged9() 
{ 
	if (xmlHttp9.readyState==4 || xmlHttp9.readyState=="complete")
	{
		var r=trim(xmlHttp9.responseText);
		if(r=='OK')
		{
		document.getElementById("hid_ajax").value=1;
		}
		else
		{
//		alert("serial Number  already exist !");
		document.getElementById("hid_ajax").value='';
		}
	} 
}
function GetXmlHttpObject()
{

var xmlHttp9=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp9=new XMLHttpRequest();

 }

catch (e)

 {

 //Internet Explorer

 try

  {

  xmlHttp9=new ActiveXObject("Msxml2.XMLHTTP");

  }

 catch (e)

  {

  xmlHttp9=new ActiveXObject("Microsoft.XMLHTTP");

  }

 }

return xmlHttp9;

}
</script>
<script language="javascript">
var xmlHttp2
function get_street()
{
var mySelections = [];

var ward=document.getElementById("pat_ward").value;
//alert();

$('#pat_ward option').each(function(i) {
                if (this.selected == true) {
                        mySelections.push(this.value);
                }
        });

	
xmlHttp2=GetXmlHttpObject()
if (xmlHttp2==null)
 {
 alert ("Browser does not support HTTP request")
 return
 }

var a="";

var url9="ajax_street.php"
url9=url9+"?ward="+mySelections
xmlHttp2.onreadystatechange=stateChanged2
xmlHttp2.open("GET",url9,true)
xmlHttp2.send(null)
}
function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}
function stateChanged2() 
{ 
	if (xmlHttp2.readyState==4 || xmlHttp2.readyState=="complete")
	{
		var r=trim(xmlHttp2.responseText);
		if(r!='')
		{
		document.getElementById('proimage1').style.display="inline-block";
		document.getElementById('proimage1').innerHTML=""; 
 		document.getElementById('proimage1').innerHTML=r; 
		}
		
	} 
}
function GetXmlHttpObject()
{

var xmlHttp2=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp2=new XMLHttpRequest();

 }

catch (e)

 {

 //Internet Explorer

 try

  {

  xmlHttp2=new ActiveXObject("Msxml2.XMLHTTP");

  }

 catch (e)

  {

  xmlHttp2=new ActiveXObject("Microsoft.XMLHTTP");

  }

 }

return xmlHttp2;

}
</script>
<script src='src/jquery-customselect.js'></script>
<link href='src/jquery-customselect.css' rel='stylesheet' />
</head>
<body>
<!-- Header -->
<div id="header">
  <div class="shell">
    <!-- Logo + Top Nav -->
    <div id="top">
      <h1><a href="#">Dengue Surveillance System</a></h1>
      <div id="top-navigation"> Welcome <a href="#"><strong>CMC</strong></a> <span>|</span> <a href="#">Change Password</a> <span>|</span> <a href="#"> Settings</a> <span>|</span> <a href="logout.php">Log out</a> </div>
    </div>
    <!-- End Logo + Top Nav -->
    <!-- Main Nav -->
    <div id="navigation">
      <ul>
      <li><a href="add_records.php" class="active"><span><strong>Add records</strong></span></a></li>
        <li><a href="show_records.php" ><span><strong>Show all records</strong></span></a></li>        
        <li><a href="upload_data.php"><span><strong>Data Managemnet</strong></span></a></li>
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
            <h2 class="left" style="padding:0px;">Add Patient Details<br />
            </h2>
            <div class="right">
              <!--<label>search articles</label>
							<input type="text" class="field small-field" />
							<input type="submit" class="button" value="search" />-->
            </div>
          </div>
          <!-- End Box Head -->
          <!-- Table -->
          <div class="table">
            <form method="post" enctype="multipart/form-data" name="frm_pages1" id="frm_pages1" action="jq-ajax/ajaxIDNO.php">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <input name="cen_id" id="cen_id" type="hidden" size="15" value="<?php echo $_SESSION['center_id'];?>"  readonly=""/>
                <tr class="head_bg">
                  <td height="28" colspan="3" class="form_head" style="background:#f9e2a0; font-weight:bold;"><div class="content-header" style="text-transform:uppercase;">A. Identification Section</div></td>
                </tr>
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Institution</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><select name="pat_institution" id="pat_institution" class="custom-select validate[required]">
                      <option value=''>None - Please Select</option>
                      <?php
	  $SelStreet=mysql_query("select * from hospital_details") or die("failed");
	  while($resStreet=mysql_fetch_array($SelStreet))
	  {
	  ?>
                      <option value="<?php echo $resStreet['AreaHospitals'];?>"><?php echo $resStreet['AreaHospitals'];?></option>
                      <?php
	  }
	  ?>
                    </select>
                  </td>
                </tr>
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Date of Admission</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_admission_date1" id="pat_admission_date1" readonly="readonly" type="text"   value="<?php echo ($res['pat_admission_date'])&&($res['pat_admission_date']!="0000-00-00")?date('d/m/Y',strtotime($res['pat_admission_date'])):"";?>" />
                  </td>
                </tr>
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Date of Onset*</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_onsetdate1" id="pat_onsetdate1"  readonly="readonly" type="text"  class="validate[required] datepicker" value="<?php echo ($res['pat_onsetdate'])&&($res['pat_onsetdate']!="0000-00-00")?date('d/m/Y',strtotime($res['pat_onsetdate'])):"";?>" />
                  </td>
                </tr>
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Serial No*</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_refno" id="pat_refno" type="text" class="validate[required,custom[integer],funcCall[checkSno]] crsno" onkeyup="ajaxsnl(this.value);" onchange="ajaxsnl(this.value);" onblur="ajaxsnl(this.value);" value="<?php echo ($res['pat_refno'])?$res['pat_refno']:""; ?>" <?php if($q) { ?> readonly="readonly" <?php } ?>  /></td>
                </tr>
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Patient NIC </td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_nic" id="pat_nic" type="text" maxlength="12"  value="<?php echo $res['pat_nic']; ?>"  placeholder="NIC of the patient" /></td>
                </tr>
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Notified Date*</td>
                  <td width="3%" align="center" class="form_text">:</td>
                   <td width="70%" class="form_text"><input name="pat_invesdate1" id="pat_invesdate1" readonly="readonly" type="text" class="validate[required] datepicker" value="<?php echo ($res['pat_invdate'])&&($res['pat_invdate']!="0000-00-00")?date('d/m/Y',strtotime($res['pat_invdate'])):"";?>" />  </td> 
           
                </tr>
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Patient Name *</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_name" id="pat_name" type="text" class="validate[required]" maxlength=""  value="<?php echo $res['pat_name']; ?>" /></td>
                </tr>
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Patient Telephone *</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_mobile" id="pat_mobile" type="text" class="validate[required]" maxlength="12"  value="<?php echo $res['pat_mobile']; ?>" /></td>
                </tr>
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Gender*</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_sex" id="pat_sex" type="radio" class="validate[required]"  <?php echo ischecked($res['pat_sex'],'1')?> value="1"/>
                    Male &nbsp;&nbsp;&nbsp;
                    <input name="pat_sex" id="pat_sex" type="radio" class="validate[required]" value="2" <?php echo ischecked($res['pat_sex'],'2')?>/>
                    Female&nbsp;&nbsp;&nbsp;
                     </td>
                </tr>
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text"><strong>Do you know DOB?</strong></td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><label>
                    <input type="radio" name="pat_dateBirth" id="pat_dateBirth" class="validate[required]"  value="1" <?php echo ischecked($res['pat_dateBirth'],'1')?> onclick="divenables_dob('val','div_dateBirth','val','div_dateBirthNot');"/>
                    Yes </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                    <input type="radio" name="pat_dateBirth" id="pat_dateBirth" class="validate[required]" value="2" <?php echo ischecked($res['pat_dateBirth'],'2')?> onclick="divenables_dob('val','div_dateBirthNot','val','div_dateBirth');"/>
                    No</label></td>
                </tr>
                <tr>
                  <td colspan="3" style="border-bottom:hidden;"><div id="div_dateBirth" <?php echo ($res['pat_dateBirth']==1)?'style="display:block;"':'style="display:none;"'; ?> style="display:none;">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                          <td width="27%" class="form_text"> Date of Birth</td>
                          <td width="3%" align="center" class="form_text">:</td>
                          <td width="70%" class="form_text"><input name="pat_dob1" id="pat_dob1" readonly="readonly" type="text" value="<?php echo ($res['pat_dob'])&&($res['pat_dob']!="0000-00-00")?date('d/m/Y',strtotime($res['pat_dob'])):"";?>" class="validate[required] datepicker" onblur="monthCalc();" onfocusout="monthCalc();" onclick="monthCalc();" onchange="monthCalc();" />
                            <input type="hidden" name="cal_days" id="cal_days" value="" />
                          </td>
                        </tr>
                      </table>
                    </div></td>
                </tr>
                <tr>
                  <td colspan="3" style="border-bottom:hidden;"><div id="div_dateBirthNot" <?php echo ($res['pat_dateBirth']==2)?'style="display:block;"':'style="display:none;"'; ?> style="display:none;">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                          <td width="27%" class="form_text"> Years</td>
                          <td width="3%" align="center" class="form_text">:</td>
                          <td width="70%" class="form_text"><input name="pat_yrs" id="pat_yrs" type="text" value="<?php echo $res['pat_yrs'];?>" class="validate[required,custom[integer]]" />
                            If less than one year enter '0' </td>
                        </tr>
                        <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                          <td width="27%" class="form_text"> Months</td>
                          <td width="3%" align="center" class="form_text">:</td>
                          <td width="70%" class="form_text"><input name="pat_month" id="pat_month" type="text" value="<?php echo $res['pat_month'];?>" class="validate[required,custom[integer]]" />
                            If less than one month enter '0' </td>
                        </tr>
                        <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                          <td width="27%" class="form_text"> Days</td>
                          <td width="3%" align="center" class="form_text">:</td>
                          <td width="70%" class="form_text"><input name="pat_days" id="pat_days" type="text" value="<?php echo $res['pat_days'];?>" class="validate[required,custom[integer]]" />
                          </td>
                        </tr>
                      </table>
                    </div></td>
                </tr>

                <tr class="head_bg" >
                  <td height="28" colspan="3" class="form_head" style="background:#f9e2a0; font-weight:bold;"><div class="content-header" style="text-transform:uppercase;">B. Residential/Work Details</div></td>
                </tr>

                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Home Number* </td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_homeno" id="pat_homeno" type="text" class="validate[required]" maxlength=""  value="<?php echo $res['pat_homeno']; ?>" /></td>
                </tr>



               <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Street*</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><span id="proimage1">
                    <select name="pat_street" id="street" class="custom-select validate[required]">
                      <option value=''>None - Please Select</option>
                      <?php
	  $SelStreet=mysql_query("select * from street_details") or die("failed");
	  while($resStreet=mysql_fetch_array($SelStreet))
	  {
	  ?>
                      <option value="<?php echo $resStreet['street'];?>"><?php echo $resStreet['Label'];?></option>
                      <?php
	  }
	  ?>
                    </select>
                    </span> </td>
                </tr>

    <!--
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Ward</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><select name="pat_ward1[]" id="pat_ward" multiple="multiple" onchange="get_street();" onblur="get_street();">
                      <?php
                          $Selward=mysql_query("select * from street_details where ward!='' group by Ward") or die("failed");
                          while($resward=mysql_fetch_array($Selward))
                          {
                          ?>
                                            <option value="<?php echo $resward['Ward']; ?>"><?php echo $resward['Ward']; ?></option>
                                            <?php
                            }
                            ?>
                    </select>
                  </td>
                </tr>
              -->
        <!--
                 <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">School </td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="pat_school" id="pat_school" type="text" class="validate[required]" maxlength=""  value="<?php echo $res['pat_school']; ?>" /></td>
                </tr>
        -->
                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">School</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><span id="proimage1">
                    <select name="pat_school" id="pat_school" class="custom-select validate[]">
                      <option value=''>None - Please Select</option>
                      <?php
                        $SelSchool=mysql_query("select * from school") or die("failed");
                        while($resSchool=mysql_fetch_array($SelSchool))
                        {         
                        ?>
                                          <option value="<?php echo $resSchool['school_name'];?>"><?php echo $resSchool['school_name'];?></option>
                                          <?php
                        }
                        ?>
                    </select>
                    </span> </td>
                </tr>


                <tr class="head_bg" >
                  <td height="28" colspan="3" class="form_head" style="background:#f9e2a0; font-weight:bold;"><div class="content-header" style="text-transform:uppercase;">C. Disease Details</div></td>
                </tr>
                <tr <?php if(($i%2)==0) { echo 'class=""';  } else { echo 'class="row_color"'; }?>>
                  <td width="27%" class="form_text"><strong>Diagnosis</strong> </td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><select name="pat_virus" id="pat_virus" class="validate[required]">
                      <option value="">Select Diagnosis</option>
                      <option value="Cholera" <?php if($val1['virus_name']=="Cholera") { ?> selected="selected" <?php } ?> >Cholera</option>
                      <option value="Plague" <?php if($val1['virus_name']=="Plague") { ?> selected="selected" <?php } ?>>Plague</option>
                      <option value="Yellow Fever" <?php if($val1['virus_name']=="Yellow Fever") { ?> selected="selected" <?php } ?>>Yellow Fever</option>
                      <option value="Acute Poliomyelitis/ Acute Flaccid Paralysis" <?php if($val1['virus_name']=="Acute Poliomyelitis/ Acute Flaccid Paralysis") { ?> selected="selected" <?php } ?>>Acute Poliomyelitis/ Acute Flaccid Paralysis</option>
                   
                   <option value="Chicken Pox" <?php if($val1['virus_name']=="Chicken Pox") { ?> selected="selected" <?php } ?>>Chicken Pox</option>
                   <option value="Dengue Fever" <?php if($val1['virus_name']=="Dengue Fever") { ?> selected="selected" <?php } ?>>Dengue Feverr</option>
                   <option value="Dengue Haemorrhagic Fever" <?php if($val1['virus_name']=="Dengue Haemorrhagic Fever") { ?> selected="selected" <?php } ?>>Dengue Haemorrhagic Fever</option>
                   <option value="Diphtheria" <?php if($val1['virus_name']=="Diphtheria") { ?> selected="selected" <?php } ?>>Diphtheria</option>
                   
                     <option value="Dysentery" <?php if($val1['virus_name']=="Dysentery") { ?> selected="selected" <?php } ?>>Dysentery</option>
                   <option value="Encephalitis" <?php if($val1['virus_name']=="Encephalitis") { ?> selected="selected" <?php } ?>>Encephalitis</option>
                   <option value="Enteric Fever" <?php if($val1['virus_name']=="Enteric Fever") { ?> selected="selected" <?php } ?>>Enteric Fever</option>
                   <option value="Food poisoning" <?php if($val1['virus_name']=="Food poisoning") { ?> selected="selected" <?php } ?>>Food poisoning</option>
                   <option value="Human Rabies" <?php if($val1['virus_name']=="Human Rabies") { ?> selected="selected" <?php } ?>>Human Rabies</option>
                   
                     <option value="Leptospirosis" <?php if($val1['virus_name']=="Leptospirosis") { ?> selected="selected" <?php } ?>>Leptospirosis</option>
                   <option value="Malaria" <?php if($val1['virus_name']=="Malaria") { ?> selected="selected" <?php } ?>>Malaria</option>
                   <option value="Measles" <?php if($val1['virus_name']=="Measles") { ?> selected="selected" <?php } ?>>Measles</option>
                   <option value="Meningitis" <?php if($val1['virus_name']=="Meningitis") { ?> selected="selected" <?php } ?>>Meningitis</option>
                   <option value="Mumps" <?php if($val1['virus_name']=="Mumps") { ?> selected="selected" <?php } ?>>Mumps</option>
                   
                     <option value="Rubella/Congenital Rubella Syndrome" <?php if($val1['virus_name']=="Rubella/Congenital Rubella Syndrome") { ?> selected="selected" <?php } ?>>Rubella/Congenital Rubella Syndrome</option>
                   <option value="Simple continued fever of 7 days or more" <?php if($val1['virus_name']=="Simple continued fever of 7 days or more") { ?> selected="selected" <?php } ?>>Simple continued fever of 7 days or more</option>
                   <option value="Tetanus" <?php if($val1['virus_name']=="Tetanus") { ?> selected="selected" <?php } ?>>Tetanus</option>
                   <option value="Neonatal Tetanus" <?php if($val1['virus_name']=="Neonatal Tetanus") { ?> selected="selected" <?php } ?>>Neonatal Tetanus</option>
                   <option value="Typhus Fever" <?php if($val1['virus_name']=="Typhus Fever") { ?> selected="selected" <?php } ?>>Typhus Fever</option>
                   
                     <option value="Viral Hepatitis" <?php if($val1['virus_name']=="Viral Hepatitis") { ?> selected="selected" <?php } ?>>Viral Hepatitis</option>
                   <option value="Whooping Cough" <?php if($val1['virus_name']=="Whooping Cough") { ?> selected="selected" <?php } ?>>Whooping Cough</option>
                   <option value="Tuberculosis" <?php if($val1['virus_name']=="Tuberculosis") { ?> selected="selected" <?php } ?>>Tuberculosis</option>

                   
                    
                    
                    
                    
                    </select></td>
                </tr>
               
                
                <tr <?php if(($i%2)==0) { echo 'class=""';  } else { echo 'class="row_color"'; }?>>
                  <td width="27%" class="form_text"> Date of Testing  </td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="virus_test_date" id="virus_test_date" type="text"  value="<?php echo ($val1['virus_test_date']) && ($val1['virus_test_date']!="0000-00-00")?date('d/m/Y',strtotime($val1['virus_test_date'])):""; ?>" readonly="readonly" class="datepicker" />
                  </td>
                </tr>
                <tr>
                  <td width="27%" class="form_text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Test Done </td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><select name="pat_test_done" id="pat_test_done" class="validate[required]">
                      <option value="">Select Test</option>
                      <option value="IgM" <?php if($val1['virus_test_done']=="IgM") { ?> selected="selected" <?php } ?>>IgM</option>
                      <option value="IgG" <?php if($val1['virus_test_done']=="IgG") { ?> selected="selected" <?php } ?>>IgG</option>
                      <option value="FBC" <?php if($val1['virus_test_done']=="FBC") { ?> selected="selected" <?php } ?>>FBC</option>                      
                      <option value="NS1" <?php if($val1['virus_test_done']=="NS1") { ?> selected="selected" <?php } ?>>Dengue NS1</option>
                      <option value="None" <?php if($val1['virus_test_done']=="None") { ?> selected="selected" <?php } ?>>None</option>
                    </select>
                  </td>
                </tr>
                <tr <?php if(($i%2)==0) { echo 'class=""';  } else { echo 'class="row_color"'; }?>>
                  <td width="27%" class="form_text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Test Result </td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><label>
                    <input type="radio" name="pat_result" id="pat_result" value="1" <?php echo ischecked($val1['pat_result'],'1')?>/>
                    Positive</label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                    <input type="radio" name="pat_result" id="pat_result" value="2" <?php echo ischecked($val1['pat_result'],'2')?>/>
                    Negative</label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                    <input type="radio" name="pat_result" id="pat_result" value="3" <?php echo ischecked($val1['pat_result'],'3')?>/>
                    None</label>
                  </td>
                </tr>
                <tr>
                  <td width="27%" class="form_text">Complications</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><select name="pat_complication" id="pat_complication" class="validate[required]">
                      <option value="">Select Complication</option>
                      <option value="Dengue Shock Syndrome" <?php if($val1['doc_designation_done']=="Dengue Shock Syndrome") { ?> selected="selected" <?php } ?>>Dengue Shock Syndrome</option>
                      <option value="Multi Organ Failure" <?php if($val1['doc_designation_done']=="Multi Organ Failure") { ?> selected="selected" <?php } ?>>Multi Organ Failure</option>
                      <option value="Dengue Encephalopathy" <?php if($val1['doc_designation_done']=="Dengue Encephalopathy ") { ?> selected="selected" <?php } ?>>Dengue Encephalopathy </option>
                      
                    </select>
                  </td>
                </tr>


                <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text"><strong>Patient Death</strong></td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><label>
                    <input type="radio" name="pat_death" id="pat_death" class="validate[required]"  value="1" <?php echo ischecked($res['pat_death'],'1')?> onclick="divenables_dob('val','div_death','val','div_notDeath');"/>
                    Yes </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                    <input type="radio" name="pat_death" id="pat_death" class="validate[required]" value="2" <?php echo ischecked($res['pat_death'],'2')?> onclick="divenables_dob('val','div_notDeath','val','div_death');"/>
                    No</label></td>
                </tr>
                <tr>
                  <td colspan="3" style="border-bottom:hidden;"><div id="div_death" <?php echo ($res['death_of_date']==1)?'style="display:block;"':'style="display:none;"'; ?> style="display:none;">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                          <td width="27%" class="form_text"> Date of Death</td>
                          <td width="3%" align="center" class="form_text">:</td>
                          <td width="70%" class="form_text"><input name="pat_death_date1" id="pat_death_date1" readonly="readonly" type="text" value="<?php echo ($res['pat_death_date'])&&($res['pat_death_date']!="0000-00-00")?date('d/m/Y',strtotime($res['pat_death_date'])):"";?>" class="validate[required] datepicker" onblur="monthCalc();" onfocusout="monthCalc();" onclick="monthCalc();" onchange="monthCalc();" />
                            <input type="hidden" name="cal_days" id="cal_days" value="" />
                          </td>
                        </tr>
                      </table>
                    </div></td>
                </tr>
                <tr>
                  <td colspan="3" style="border-bottom:hidden;"><div id="div_notDeath" <?php echo ($res['death_date']==2)?'style="display:block;"':'style="display:none;"'; ?> style="display:none;">

                    </div></td>
                </tr>
                <tr class="head_bg" >
                  <td height="28" colspan="3" class="form_head" style="background:#f9e2a0; font-weight:bold;"><div class="content-header" style="text-transform:uppercase;">D. Information</div></td>
                </tr>
                 <tr <?php if($k==0) { echo 'class=""';  } else { echo 'class="row_color"'; } $k++;if($k==2){$k=0;}?>>
                  <td width="27%" class="form_text">Doctor's Name </td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><input name="doc_name" id="doc_name" type="text" class="validate[]" maxlength=""  value="<?php echo $res['doc_name']; ?>" /></td>
                  
                </tr>
                <tr>
                  <td width="27%" class="form_text">Designation</td>
                  <td width="3%" align="center" class="form_text">:</td>
                  <td width="70%" class="form_text"><select name="doc_designation" id="doc_designation" class="validate[]">
                      <option value="">Select Designation</option>
                      <option value="HO" <?php if($val1['doc_designation_done']=="HO") { ?> selected="selected" <?php } ?>>HO</option>
                      <option value="MO" <?php if($val1['doc_designation_done']=="MO") { ?> selected="selected" <?php } ?>>MO</option>
                      <option value="MOH" <?php if($val1['doc_designation_done']=="MOH") { ?> selected="selected" <?php } ?>>MOH</option>
                      <option value="VP" <?php if($val1['doc_designation_done']=="VP") { ?> selected="selected" <?php } ?>>VP</option>
                      <option value="GP" <?php if($val1['doc_designation_done']=="GP") { ?> selected="selected" <?php } ?>>GP</option>
                    </select>
                  </td>
                </tr>

                <tr>
                  <td colspan="3" align="center"><input name="btn_submit" type="submit" class="" value="Submit" />
                    <input type="hidden" name="hid_ajax" id="hid_ajax" <?php if($q) { ?> value="1" <?php } else { ?> value="" <?php } ?> /></td>
                </tr>
              </table>
              *  Mandatory fields
            </form>
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
<script>
      $(function() {
        $("#street").customselect();
		$("#pat_institution").customselect();
      });
      </script>
<!-- Footer -->
<div id="footer">
  <div class="shell"> <span class="left">&copy; 2017 - Epihack Sri Lanka</span>
    <!--<span class="right">
			Design by <a href="http://chocotemplates.com" target="_blank" title="The Sweetest CSS Templates WorldWide">Chocotemplates.com</a>
		</span>-->
  </div>
</div>
<!-- End Footer -->
</body>
</html>
