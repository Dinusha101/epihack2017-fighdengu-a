<?php
ob_start();
session_start();
require "config.php";
$slid=$_REQUEST['ward'];


	$wardArr=explode(",",$slid);
	$i=0;
	foreach($wardArr as $wardDet)
	{
		$i++;
		if($i==1)
		{
		$WD.=" Ward LIKE '".$wardDet."'";
		}
		else
		{
			$WD.=" or Ward LIKE '".$wardDet."'";
		}
	
	}

//echo "select * from street_details where $WD order by id asc";

$qry = mysql_query("select * from street_details where $WD order by id asc") or die(mysql_error());
$nu=mysql_num_rows($qry);
if($nu!=0)
{
?>
<select name="pat_street" id="street" class="custom-select validate[required]">
  <option value=''>None - Please Select</option>
  <?php
 while($resStreet=mysql_fetch_array($qry))
 {
 	?>
  <option value="<?php echo $resStreet['street'];?>"><?php echo $resStreet['Label'];?></option>
  <?php
 }
 ?>
</select>
 
<?php
}
else
{
 echo "NO STREET DETAILS FOUND";
}
?>
  