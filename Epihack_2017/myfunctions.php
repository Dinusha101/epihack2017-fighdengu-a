<?php 
extract($_REQUEST);
#pagination
function pagination($tbl_name,$fields,$where='',$orderby='',$page_url,$pagesize,$page_no)
{
	if($where)$where=" WHERE ".$where;
	if($orderby)$orderby=" ORDER BY ".$orderby;
	$sql1="SELECT ".$fields." FROM ".$tbl_name.$where.$orderby;
	$rowsperpage = $pagesize;
	$website = $page_url; 
	$pagination = new CSSPagination($sql1, $rowsperpage, $website); 
	$pagination->setPage($page_no); 
	$qsql1 = @mysql_query($sql1) or die("failed");
	$RecordCount = mysql_num_rows($qsql1);
	$sql2 = "select ".$fields." FROM ".$tbl_name.$where.$orderby." LIMIT ".$pagination->getLimit() . ", ". $rowsperpage; 
	$result_query = @mysql_query($sql2);
	$page = $pagination->showPage();
	$resultArr = array();
	if($RecordCount>1)
	{
		while($res = mysql_fetch_assoc($result_query))
		{
			$resultArr[] = $res;
		}
	}
	else if($RecordCount==1)
	{
		$res = mysql_fetch_assoc($result_query);
		$resultArr[] = $res;
	}
	$array_res	=	array('Query'=>$resultArr,'page'=>$page,'nr'=>$RecordCount);
	return $array_res;
}
function paginationQry($sql1,$page_url,$pagesize,$page_no)
{
	$rowsperpage = $pagesize;
	$website = $page_url; 
	$pagination = new CSSPagination($sql1, $rowsperpage, $website); 
	$pagination->setPage($page_no); 
	$qsql1 = @mysql_query($sql1) or die("failed");
	$RecordCount = mysql_num_rows($qsql1);
	$sql2 = $sql1." LIMIT ".$pagination->getLimit() . ", ". $rowsperpage; 
	$result_query = @mysql_query($sql2);
	$page = $pagination->showPage();
	$resultArr = array();
	if($RecordCount>1)
	{
		while($res = mysql_fetch_assoc($result_query))
		{
			$resultArr[] = $res;
		}
	}
	else if($RecordCount==1)
	{
		$res = mysql_fetch_assoc($result_query);
		$resultArr[] = $res;
	}
	$array_res	=	array('Query'=>$resultArr,'page'=>$page,'nr'=>$RecordCount);
	return $array_res;
}
#pagination for array
function pagination_array($array, $page = 1, $link_prefix = false, $link_suffix = false, $limit_page = 20, $limit_number = 10)
{
	if (empty($page) or !$limit_page) $page = 1;
	$num_rows = count($array);
	if (!$num_rows or $limit_page >= $num_rows) return false;
	$num_pages = ceil($num_rows / $limit_page);
	$page_offset = ($page - 1) * $limit_page;
	//Calculating the first number to show.
	if ($limit_number)
	{
		$limit_number_start = $page - ceil($limit_number / 2);
		$limit_number_end = ceil($page + $limit_number / 2) - 1;
		if ($limit_number_start < 1) $limit_number_start = 1;
		//In case if the current page is at the beginning.
		$dif = ($limit_number_end - $limit_number_start);
		if ($dif < $limit_number) $limit_number_end = $limit_number_end + ($limit_number - ($dif + 1));
		if ($limit_number_end > $num_pages) $limit_number_end = $num_pages;
		//In case if the current page is at the ending.
		$dif = ($limit_number_end - $limit_number_start);
		if ($limit_number_start < 1) $limit_number_start = 1;
	}
	else
	{
		$limit_number_start = 1;
		$limit_number_end = $num_pages;
	}
	//Generating page links.
	for ($i = $limit_number_start; $i <= $limit_number_end; $i++)
	{
		$page_cur = "<a href='$link_prefix$i$link_suffix'>$i</a>";
		if ($page == $i) $page_cur = "<b>$i</b>";
		else $page_cur = "<a href='$link_prefix$i$link_suffix'>$i</a>";
		$panel .= " <span>$page_cur</span>";
	}
	$panel = trim($panel);
	//Navigation arrows.
	if ($limit_number_start > 1) $panel = "<b><a href='$link_prefix".(1)."$link_suffix'>&lt;&lt;</a>  <a href='$link_prefix".($page - 1)."$link_suffix'>&lt;</a></b> $panel";
	if ($limit_number_end < $num_pages) $panel = "$panel <b><a href='$link_prefix".($page + 1)."$link_suffix'>&gt;</a> <a href='$link_prefix$num_pages$link_suffix'>&gt;&gt;</a></b>";
	$output['panel'] = $panel; //Panel HTML source.
	$output['offset'] = $page_offset; //Current page number.
	$output['limit'] = $limit_page; //Number of resuts per page.
	$output['array'] = array_slice($array, $page_offset, $limit_page, true); //Array of current page results.
	
	return $output;
}
#position add
function pos_add($table_name,$field_name,$catpos,$id,$where='')
{
	if($where)$where = "WHERE ".$where;
	$selectSql = mysql_query("SELECT * FROM ".$table_name." ".$where." ORDER BY ".$field_name." ASC");
	$rows = mysql_num_rows($selectSql);
	
	if($catpos!="Last")
	{
		$limit=$catpos-1;//0
		
		$squery = mysql_query("SELECT * FROM ".$table_name." ".$where." ORDER BY ".$field_name." ASC LIMIT $limit,$rows");
		while($resval=mysql_fetch_object($squery))
		{
			$inc = $resval->$field_name+1;
			$update=mysql_query("UPDATE ".$table_name." set ".$field_name."='$inc' WHERE ".$id."='".$resval->$id."'");
		}
		
		$fabric_pos = $catpos;
	}
	else
	{
		$fabric_pos=$rows+1;
	}
	return $fabric_pos;
}
#position update
function pos_update($table_name,$field_name,$catpos,$resultpos,$id,$where='')
{
	if($where)$where = " WHERE ".$where;
	if($resultpos<$catpos)
	{
		if($resultpos==1)
		{
			$limit=0;
			$max=($catpos-$resultpos)+1;
		}
		else
		{
			$limit=$resultpos;//4
			$max=($catpos-$resultpos);//5
		}
		
		$squery = mysql_query("SELECT * FROM ".$table_name.$where." ORDER BY ".$field_name." LIMIT $limit,$max");
		while($res=mysql_fetch_assoc($squery))
		{
			$inc=$res[$field_name]-1;
			$update = mysql_query("UPDATE ".$table_name." SET ".$field_name."='$inc' WHERE ".$id."='".$res[$id]."'");
		}
		
	}
	else
	{
		$limit=$catpos-1;//7
		$max=($resultpos-$catpos);//1
		$squery = mysql_query("SELECT * FROM ".$table_name.$where." ORDER BY ".$field_name." LIMIT $limit,$max");
		//echo "SELECT * FROM ".$table_name.$where." ORDER BY ".$field_name." LIMIT $limit,$max"."<br>";
		while($res=mysql_fetch_assoc($squery))
		{
			$inc=$res[$field_name]+1;
			//echo "UPDATE ".$table_name." SET ".$field_name."='$inc' WHERE ".$id."='".$res[$id]."'"."<br>";
			$update =  mysql_query("UPDATE ".$table_name." SET ".$field_name."='$inc' WHERE ".$id."='".$res[$id]."'");
		}
	}
	return $catpos;
}
#function show position box in insert form
function show_pos($table_name,$field_name,$where='')
{
	if($where)$where=" WHERE ".$where;
	$squery = mysql_query("SELECT ".$field_name." FROM ".$table_name." ".$where." ORDER BY ".$field_name." ASC");
	if(mysql_num_rows($squery)==0)
	{
		echo '<option value="1">1</option>';
	}
	else
	{
		while($res=mysql_fetch_object($squery))
		{
			echo '<option value="'.$res->$field_name.'">'.$res->$field_name.'</option>';	
		}
		echo '<option value="Last">Last</option>';
	}
}
#function show position box in edit form
function show_posupd($table_name,$field_name,$result_pos,$where='')
{
	if($where)$where=" WHERE ".$where;
	$selPos = mysql_query("SELECT ".$field_name." FROM ".$table_name.$where." ORDER BY ".$field_name." ASC");
	if(mysql_num_rows($selPos)>0)
	{
		while($resPos=mysql_fetch_object($selPos))
		{
			echo '<option value="'.$resPos->$field_name.'" '.isselected($resPos->$field_name,$result_pos).'>'.$resPos->$field_name.'</option>';
		}
	}
}
function insert($table,$exist='',$new_field='',$up_folder='')
{
        if($up_folder=="")
        {
            $folder = "../uploads/";
			if(!is_dir($folder))
			{
			mkdir($folder, 0777);
			}		
        }
        else
        {
            $folder = "../uploads/";
			if(!is_dir($folder))
			{
			mkdir($folder, 0777);
			}		
            $folder = $folder."$up_folder/";
			if(!is_dir($folder))
			{
			mkdir($folder, 0777);
			}		
			
        }   
   
        $selfield = mysql_query("show columns from $table");
        while($rowfield=@mysql_fetch_assoc($selfield))
        {
            $field[]=$rowfield[Field];
        }
        //print_r($field);
       
        if($exist)
        {
       
            $expexist=explode(",",$exist);
            foreach($expexist as $existvar)
            {
                $existcond.= "$existvar = '".variable($exist[$existvar])."' and  ";
            }
           
        }
        else
        {
       
            foreach($_POST as $key => $value)
            {
                if(@in_array($key,$field))
                {
                    $existcond.= "$key = '".variable($_POST[$key])."' and ";
                }
           
            }
       
        }
       
	   //	echo $existcond."<br>";
		
        $existcond = substr($existcond,0,strlen($existcond)-5);
		
        //echo $existcond."<br>";
		
		//echo "select * from $table where $existcond";
   
        $selexist=mysql_query("select * from $table where $existcond");
		
		//echo "select * from $table where $existcond";
		
        $checkexist = mysql_affected_rows();
       
        if($checkexist <= 0)
        {
       
            foreach($_POST as $key => $value)
            {
                if(@in_array($key,$field))
                {
                    $insertfield.= "$key, ";
                    $insertvalue.= "'".variable($_POST[$key])."', ";
                }
           
            }
           
            if($new_field)
            {
                foreach($new_field as $key => $value)
                {
                    if(@in_array($key,$field))
                    {
                        $insertfield.= "$key, ";
                        $insertvalue.= "'".variable($new_field[$key])."', ";
                    }
               
                }
            }
           
            if($_FILES)
            {
           
                foreach($_FILES as $key => $value)
                {
                    if(@in_array($key,$field) && $_FILES[$key][name])
                    {
                        $insertfield.= "$key, ";
                        $file_name = rand(1,99999).$_FILES[$key][name];
                        $strFileType = strtolower($_FILES[$key]["type"]);
                        $insertvalue.= "'".$file_name."', ";
                        move_uploaded_file($_FILES[$key][tmp_name],$folder.$file_name);
                       
                    }
               
                }
           
            }
            if(@in_array("date",$field))
            {
                $insert_field = $insertfield."date";
                $insert_value = $insertvalue."curdate()";
            }
            else
            {
                $insert_field = substr($insertfield,0,strlen($insertfield)-2);
                $insert_value = substr($insertvalue,0,strlen($insertvalue)-2);
            }
           
            //echo $insert_field,"<br>";
            //echo $insert_value;
            $ins_query = "insert into $table($insert_field) values($insert_value)";
            //echo $ins_query;
            $result = mysql_query($ins_query);
           
            if(mysql_error())
            {
                echo mysql_error(),"<br>",$ins_query;
            }
           
            $ar = mysql_affected_rows();
            $insert_id = mysql_insert_id();
           
            return array('ar'=>$ar,'id'=>$insert_id);
       
        }
        else
        {
            //echo "Already Exist";
            return array(exist=>'1');
        }
   
}
function update($table,$upcond,$new_field='',$up_folder='',$checkbox='')
{
   
        if($up_folder=="")
        {
            $folder = "upload/";
        }
        else
        {
            $folder = "$up_folder/";
        }
   
   
        $selfield = mysql_query("show columns from $table");
		
        while($rowfield=@mysql_fetch_assoc($selfield))
        {
            $field[]=$rowfield[Field];
        }
        //print_r($field);
      
            foreach($_POST as $key => $value)
            {
                if(@in_array($key,$field))
                {
                    $updatefield.= "$key = "."'".variable($_POST[$key])."', ";
                }
           
            }
           
            if($new_field)
            {
                foreach($new_field as $key => $value)
                {
                    if(@in_array($key,$field))
                    {
                        $updatefield.= "$key = "."'".variable($new_field[$key])."', ";
                    }
               
                }
            }
           
            if($_FILES)
            {
           
                foreach($_FILES as $key => $value)
                {
                    if(@in_array($key,$field) && $_FILES[$key][name])
                    {
                        $file_name = rand(1,99999).$_FILES[$key][name];
                        $updatefield.= "$key = "."'".$file_name."', ";
                        move_uploaded_file($_FILES[$key][tmp_name],$folder.$file_name);
                    }
               
                }
           
            }
           
            if($checkbox)
            {
           
                $expcheckbox=explode(",",$checkbox);
                foreach($expcheckbox as $checkboxvar)
                {
                    if(@in_array($checkboxvar,$field) && $_POST[$checkboxvar]=="")
                    {
                        $updatefield.= "$checkboxvar = "."'".$_POST[$checkboxvar]."', ";
                    }
                }
               
            }
           
            $insert_field = substr($updatefield,0,strlen($updatefield)-2);
           
            //echo $insert_field,"<br>";
            $up_query = "update $table set $insert_field where $upcond";
            $result = mysql_query($up_query);
            //echo $up_query."<br>";
           
            if(mysql_error())
            {
                echo mysql_error(),"<br>",$ins_query;
            }
           
            $ar = mysql_affected_rows();
           
            return array(ar=>$ar);
   
}
function select_query($table_name, $field_name, $condition, $limitations='',$print='')
{
	if($field_name == "")
	{
		$field_name = "*";
	}
	
	$selectQ = "select $field_name from $table_name";
	
	if($condition!="")
	{
		$selectQ .= " where $condition";
	}
	if($limitations!="")
	{
		$selectQ .= " limit $limitations";
	}
	if($print){echo $selectQ."<br>";}
   
	$resQuery    =    mysql_query($selectQ);
	
	if(($_SERVER['HTTP_HOST']=="localhost:81" || $_SERVER['HTTP_HOST']=="localhost") && mysql_error())
	{
		echo $sql,"<br>",mysql_error();
	}
   
	$NumRows    =    mysql_num_rows($resQuery);
   
	if($limitations == 1)
	{
		$fetchArray    =    mysql_fetch_assoc($resQuery);
	   
		$fetchArray['nr']    =    $NumRows;
	   
		return $fetchArray;
	}
	else
	{
		while($res = mysql_fetch_assoc($resQuery))
		{
			$resultArr[] = $res;
		}
	
		return array(Query => $resQuery,nr => $NumRows,result=>$resultArr);
	}
	
}
function delete_query($table_name, $condition)
    {
        $deleteQ = "delete from $table_name where $condition";
       
        //echo $deleteQ."<br>";
       
        return mysql_query($deleteQ);
    }
function Execute($sql)
{
	$resQ = mysql_query($sql);
	
	return($resQ);
}
function getRow($sqlRow)
{
	$resArr = mysql_fetch_array(mysql_query($sqlRow));
	
	return $resArr;
}
function variable($vari)
{
	$varVal = ereg_replace("'","''",stripslashes($vari));
	
	$varVal = mysql_real_escape_string(trim($varVal));
	
	return $varVal;
}
function divert($fileName)
{
	echo "<meta http-equiv='refresh' content='0;url=$fileName'>";
	exit();
}
function encrypt($inp,$mod='')
{
	if($mod == 0)
	{
		return base64_encode($inp);
	}
	else
	{
		return base64_decode($inp);
	}
}
function check_session($sess_val,$path='')
{
	if(!$sess_val)
	{	
		divert($file_login);
	}
	
}
function ischecked($val1, $val2)
{
	
	if($val1==$val2)
	{
		return 'checked="checked"';
	}
	else
	{
		return '';
	}
}
/*~~~~~~~~~~CHECKING A CHECKBOX SET OR NOT END~~~~~~~~~~*/
/*~~~~~~~~~~CHECKING A SELECT BOX SELECTED OR NOT START~~~~~~~~~~*/
function isselected($val1, $val2)
{
	
	if($val1==$val2)
	{
		return 'selected="selected"';
	}
	else
	{
		return '';
	}
}	
function parsing_criteria($pairs)
{
	 $criteria = array();
	 if($pairs!='all')
	 {
		$pairs = explode(",",$pairs);
		foreach($pairs as $row)
		{
			$array_row = explode("___",$row);
			//print_r($array_row);
			if($array_row[0])
			{
				$criteria[$array_row[0]] = $array_row[1];
			}
		}
	}
	return $criteria;
}
function show_dashvalues()
{
	#number of products
	$selProduct = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_products FROM tbl_product WHERE prod_status='Y'"));
	$ret['total_products'] = $selProduct['total_products'];
	#Total sales
	$selRevenue = mysql_fetch_array(mysql_query("SELECT SUM(usd_sub_total) AS total_revenues FROM tbl_order WHERE (payment='Collected' OR payment='Dispatched') AND status='Y'"));
	$ret['total_revenues'] = $selRevenue['total_revenues'];
	#Total orders WHERE payment='Collected' AND status='Y'
	$selOrders = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_orders FROM tbl_order"));
	$ret['total_orders'] = $selOrders['total_orders'];
	#Total customers
	$selUsers = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_customers FROM tbl_users WHERE user_status='Y'"));
	$ret['total_customers'] = $selUsers['total_customers'];
	#Today Orders
	$selTodayOrders = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS today_orders FROM tbl_order WHERE dt='".date('Y-m-d')."'"));
	$ret['today_orders'] = $selTodayOrders['today_orders'];
	#current month orders
	$selCmonthOrders = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS cmonth_orders FROM tbl_order WHERE MONTH(dt)=MONTH(CURDATE())"));
	$ret['cmonth_orders'] = $selCmonthOrders['cmonth_orders'];
	#current year orders
	$selCyearOrders = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS cyear_orders FROM tbl_order WHERE YEAR(dt)=YEAR(CURDATE())"));
	$ret['cyear_orders'] = $selCyearOrders['cyear_orders'];
	#Total Tax
	$selTax = mysql_fetch_array(mysql_query("SELECT SUM(tax) AS total_tax FROM tbl_order WHERE (payment='Collected' OR payment='Dispatched') AND status='Y'"));
	$ret['total_tax'] = $selTax['total_tax'];
	#Total Tax
	$selShipping = mysql_fetch_array(mysql_query("SELECT SUM(shipping_cost) AS total_shipping FROM tbl_order WHERE (payment='Collected' OR payment='Dispatched') AND status='Y'"));
	$ret['total_shipping'] = $selShipping['total_shipping'];
	#Total Quantity
	$selQty =mysql_query("SELECT quan FROM tbl_order WHERE (payment='Collected' OR payment='Dispatched') AND status='Y'");
	while($resQty =  mysql_fetch_array($selQty))
	{
		$quan = explode(",",$resQty['quan']);
		
		$val += array_sum($quan);
	}
	$ret['total_qty'] = $val;
	#Processing Orders
	$selProcessing = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_processing FROM tbl_order WHERE (payment='Processing') AND status='Y'"));
	$ret['total_processing'] = $selProcessing['total_processing'];
	#Completed Orders
	$selCompleted = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_completed FROM tbl_order WHERE (payment='Collected') AND status='Y'"));
	$ret['total_completed'] = $selCompleted['total_completed'];
	#Dispatched Orders
	$selDispatched = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total_dispatched FROM tbl_order WHERE (payment='Dispatched') AND status='Y'"));
	$ret['total_dispatched'] = $selDispatched['total_dispatched'];
	
	return $ret;
}
#product sale count in order table
function product_salecount($product_id,$arg='')
{
	
	$selProducts = mysql_query("SELECT prod_id,quan,usd_prod_total,pro_usd_uamt FROM tbl_order WHERE FIND_IN_SET(".$product_id.",prod_id)");
	
	if(mysql_num_rows($selProducts))
	{
		while($resProducts = mysql_fetch_assoc($selProducts))
		{
			$selProduct = mysql_fetch_array(mysql_query("SELECT prod_name FROM tbl_product WHERE prod_id='".$product_id."'"));
			$explode_prod = explode(",",$resProducts['prod_id']);
			$prod_index = array_search($product_id,$explode_prod);
			$explode_qty = explode(",",$resProducts['quan']);
			$explode_amt = explode(",",$resProducts['usd_prod_total']);
			$total_qty = $total_qty+$explode_qty[$prod_index];
			$total_amount = $total_amount+$explode_amt[$prod_index];
		}
		if($arg)
		{
			$arr = array('product'=>$selProduct['prod_name'],'qty'=>$total_qty,'amt'=>$total_amount);
		}
		else
		{
			$arr = $total_qty;
		}
	}
	else
	{
		//$arr = array('qty'=>0,'amt'=>0);
	}
	return $arr;
}
function product_salecountall($limit="")
{
	
	$selProducts = mysql_query("SELECT * FROM tbl_product WHERE prod_status='Y'");
	
	$prodArr = array();
	
	if(mysql_num_rows($selProducts)>0)
	{
		$i=0;
		while($res = mysql_fetch_array($selProducts))
		{
			$prod = product_salecount($res['prod_id']);
			
			if($prod)
			{
				$i++;
				if($limit)
				{
					if($i<=$limit)
					{
						
						$pkey = $res['prod_id'];
						$prodArr[$pkey] = $prod;
					}
				}
				else
				{
					$pkey = $res['prod_id'];
					$prodArr[$pkey] = $prod;	
				}
			}
		}
		arsort($prodArr);
		return $prodArr;
	}
}
#newsletter
function send_newsletter($htitle,$sendn_val)
{
	#select settings
		 $selSettings=mysql_query("select * from tbl_settings") or die(mysql_error());
		 $resSettings=mysql_fetch_array($selSettings);
		 $settings_earg = @unserialize($resSettings['extra_fields']);
		 $fname=$resSettings['fromname'];
		 $femail=$resSettings['set_email'];
		 $temail=$resSettings['testemail'];
		 $verification=$resSettings['verification'];
	#select Newsletter Info
		 $fletter=mysql_query("select * from tbl_newsletter where nl_id='".$sendn_val."' and nl_status='Y'")or die(mysql_error());
		 $res=mysql_fetch_array($fletter);
		 $subject=$res['nl_title'];
		 $nlid=$res['nl_id'];
		 $message=$res['nl_content'];
		 $date=date("Y-m-d",strtotime($resSettings['set_days']." day ".$resSettings['set_hrs']." hours ".$resSettings['set_mins']." minutes"));
		 #define email address array
		$addr_users = array();
		$addr_abookusers = array();
		
		if($htitle=="user" || $htitle=="all" || $htitle=="noneffective" || $htitle=="effective" || $htitle=="privileged" || $htitle=="mprivileged")
		{
			#user List
			if($htitle=="noneffective")
			{
				$cond = "having ctorder<'".$settings_earg['noneffective']."'";
			}
			else if($htitle=="effective")
			{
				$cond = "having ctorder BETWEEN '".$settings_earg['effective_from']."' AND '".$settings_earg['effective_to']."'";
			}
			else if($htitle=="privileged")
			{
				$cond = "having ctorder BETWEEN '".$settings_earg['previleged_from']."' AND '".$settings_earg['previleged_to']."'";
			}
			else if($htitle=="mprivileged")
			{
				$cond = "having ctorder>".$settings_earg['mprevileged'];
			}
		
			if($cond)
			{
				$UserList = mysql_query("SELECT U.user_id, U.user_name, U.user_email, U.user_dt, U.user_mobile, count( O.id ) AS ctorder FROM tbl_users U LEFT JOIN tbl_order O ON U.user_id = O.user_id GROUP BY U.user_id $cond");	
			}
			else #all user
			{
				$UserList=mysql_query("select * from tbl_users where user_status='Y'")or die(mysql_error());
				
			}
			$UserRows=mysql_num_rows($UserList);
			if($UserRows)
			{
				while($resUsers=mysql_fetch_array($UserList))
				{
					 $title = "Users";
					 $addr_users[]=$resUsers['user_email'];
					 $id=$resUsers['user_id'];
					 $insert=mysql_query("insert into tbl_history(id,ab_id,hnewsletter,htitle,dt) values('$id','".$title."','$nlid','$title','$date')")or die(mysql_error());	
				}
			}	
		}
		
		#address book
		$subscriber_id = (is_integer($htitle)) ? $htitle : "";
		if($htitle=="0" || $htitle=="all")
		{
			//echo $htitle;
			$sub_id = ($subscriber_id) ? $subscriber_id : 0;
			$sqlAddrBook=mysql_query("select * from tbl_addr_book where ab_id='".$sub_id."' and status='Y'")or die(mysql_error());
			$AddressBookRows=mysql_num_rows($sqlAddrBook);
			if($AddressBookRows>0)
			{
				 while($resAbook=mysql_fetch_array($sqlAddrBook))
				 {
				 	 $title = "Subscribers";
					 $addr_abookusers[]=$resAbook['email'];
					 $id=$resAbook['aid'];
					 $abid=$resAbook['ab_id'];
					 $insert=mysql_query("insert into tbl_history(id,ab_id,hnewsletter,htitle,dt) values('$id','$abid','$nlid','$title','$date')")or die(mysql_error());	
				 }
			}
		}
		
		$MergeEmailAddress = array_merge($addr_users,$addr_abookusers);
		//print_r($MergeEmailAddress);
		if($MergeEmailAddress)
		{
			$list=implode(',',$MergeEmailAddress);
	 
	   	    $from = stripslashes($fname)."<".stripslashes($femail).">";
	   	    
			$mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";
	  		
			$headers = "From: $from\r\nBcc: {$list}" . "\r\n" .
			   "MIME-Version: 1.0\r\n" .
				  "Content-Type: multipart/mixed;\r\n" .
				  " boundary=\"{$mime_boundary}\"";
				$message = $res['nl_content'] . "\r\n";   
			   $message = "This is a multi-part message in MIME format.\n\n" .
				  "--{$mime_boundary}\n" .
				  "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
				  "Content-Transfer-Encoding: 7bit\n\n" .
			   $message . "\n\n";
				
				$i=1;
			  
			   while($i<=5)
			   {
					   if($res['nl_attach'.$i]!="")
					   {
						  $attach = "../mail_attach/".$sendn_val."/".$res['nl_attach'.$i];
						  $name = $res['nl_attach'.$i];
						  $type = filetype($attach);
						  $size = filesize($attach);
						  $file = fopen($attach,'rb');
						   $data = fread($file,filesize($attach));
						   fclose($file);
						  $data = chunk_split(base64_encode($data));
							 $message .= "--{$mime_boundary}\n" .
								"Content-Type: {$type};\n" .
								" name=\"{$name}\"\n" .
								"Content-Disposition: attachment;\n" .
								" filename=\"{$name}\"\n" .
								"Content-Transfer-Encoding: base64\n\n" .
							 $data . "\n\n";
					   }
					   $i++;
			   }
		 	
				$message.="--{$mime_boundary}--\n";
				$sendmail = @mail($tomail, $subject, $message, $headers);
				if($sendmail)
				{
 					$ret = 'success';
				}
				else
				{
					$ret = 'failure';
				}
	 	}
		else
		{
			$ret = "failure1";
		}
		return $ret;
}
function reIndexArray( $arr, $val )
{
   $new_array = array();
   
   $new_array[0] = $val;
   
   foreach($arr as $arrval)
   {
   		if($arrval!=$val)
   		$new_array[] = $arrval;
   }
   return $new_array;
}
#show parent attributes depends on category
function show_filterattributes($cat_id)
{
	$sel = select_query("tbl_product","attribute_parent","FIND_IN_SET(".$cat_id.",cat_id)");
	if($sel['nr'])
	{
		foreach($sel['result'] as $res)
		{
			$ex_val = explode(",",$res['attribute_parent']);
			foreach($ex_val as $val)
			{
				$sel = select_query("tbl_productattributes","attr_slug","attr_id ='".$val."'","1");
				$arr[] = $sel['attr_slug'];
			}
		}
		return @array_unique($arr);
	}
}
function show_filterattributesets($cat_id)
{
	$sel = select_query("tbl_product","product_attributes","FIND_IN_SET(".$cat_id.",cat_id)");
	if($sel['nr'])
	{
		foreach($sel['result'] as $res)
		{
			$ex_val = explode(",",$res['product_attributes']);
			foreach($ex_val as $val)
			{
				/*$sel = select_query("tbl_productattributes","attr_slug","attr_id ='".$val."'","1");
				$arr[] = $sel['attr_slug'];*/
				$arr[] = $val;
			}
		}
		return @array_unique($arr);
	}
}

function show_brands($cat_id)
{
	$sel = select_query("tbl_product","brand_id","FIND_IN_SET(".$cat_id.",cat_id)");
	if($sel['nr'])
	{
		foreach($sel['result'] as $res)
		{
			$arr[] = $res['brand_id'];
		}
		
		if($arr)
		{
			$arrU = array_unique($arr);
			$impArr = implode(",",$arrU);
		}
		return $impArr;
	}
}

#show_price
function show_price($product_id,$price_status,$currency,$attr_id='')
{
	if($price_status=="fixed")
	{
		$sel = select_query("tbl_product","prod_ret_amt,prod_sp_amt","prod_id='".$product_id."'","1");
		$arr['prod_ret_amt'] = $sel['prod_ret_amt'];
		$arr['prod_sp_amt'] = $sel['prod_sp_amt'];
	}
	else
	{
		if(!$attr_id)
		{
			$sel = select_query("tbl_price","list_price,price","product_id='".$product_id."' AND price_default='Y'","1");
		}
		else
		{
			$sel = select_query("tbl_price","list_price,price","product_id='".$product_id."' AND feature_id='".$attr_id."'","1");
		}
		$arr['prod_ret_amt'] = $sel['list_price'];
		$arr['prod_sp_amt'] = $sel['price'];
		$arrval['attr_id'] = $attr_id;
	}
	if($arr['prod_ret_amt']!="")
	{
		$prod_ret_amt=number_format($arr['prod_ret_amt'], 2, '.', '');
	}
	else
	{
		$prod_ret_amt=number_format($arr['prod_sp_amt'], 2, '.', '');
	}
	
	$prod_sp_amt=number_format($arr['prod_sp_amt'], 2, '.', '');
	$arrval['lprice']=number_format(($prod_ret_amt / $currency), 2, '.', '');
	$arrval['sprice']=number_format(($prod_sp_amt / $currency), 2, '.', '');
	$save_rs=number_format(($lprice-$sprice), 2, '.', '');
	$arrval['save']=number_format(((($arrval['lprice']-$arrval['sprice'])/$arrval['lprice'])*100), 0, '.', '');
	return $arrval;
}
#function attribute parent name
function attr_parent($attr_id)
{
	if($attr_id)
	{
		$selAttrparent = select_query("tbl_productattributes C,tbl_productattributes P","P.attr_title AS pname,C.attr_title AS attr_name","C.attr_pid=P.attr_id AND C.attr_id='".$attr_id."'","1");
		$arr['attr_title'] = $selAttrparent['attr_name'];
		$arr['attr_parent'] = $selAttrparent['pname'];
		return $arr;
	}
}
function shipping_calc($product_id,$quan,$weight_type,$weight,$country_id,$currency)
{ 
	$selc=mysql_query("select * from tbl_country_list where cl_id='".$country_id."' LIMIT 1") or die(mysql_error());
	$rc=mysql_fetch_assoc($selc);
	$cl_price1=$rc['cl_price1'];
	$cl_price2=$rc['cl_price2'];
	$cl_price3=$rc['cl_price3'];
	$cl_price4=$rc['cl_price4'];
	$cl_price5=$rc['cl_price5'];
	$cl_price6=$rc['cl_price6'];
	$cl_price7=$rc['cl_price7'];
	$cl_price8=$rc['cl_price8'];
	$cl_discount=$rc['cl_discount'];
	$tsamt=0;
	$usd_tsamt=0;
	$gtamt=0;
	$pkg=0;
	$pgrm=0;
	$sgrm=0;
	$skg=0;
		if($weight_type=="GRM")
		{
			$sgrm = $quan * $weight;
		}
		else
		{
			$skg=$quan * $weight;
	
		}
	//echo $quan;
		$sumgrm=($sgrm/1000);
		$tsum=($sumgrm+$skg);
		if($tsum < 0.5 )
		{
			$samt=$cl_price1;
		}
		
		if(($tsum >= 0.5 ) && ($tsum < 1 ))
		{
			$samt=$cl_price2;
		}
		
		if(($tsum >= 1 ) && ($tsum < 2 ))
		{
			$samt=$cl_price3;
		}
		
		if(($tsum >= 2) && ($tsum < 5 ))
		{
			$samt=$cl_price4;
		}
		
		if(($tsum >= 5 ) && ($tsum < 10 ))
		{
			$samt=$cl_price5;
		}
	
		if(($tsum >= 10 ) && ($tsum < 15 ))
		{
			$samt=$cl_price6;
		}
		
		if(($tsum >= 15 ) && ($tsum < 20 ))
		{
			$samt=$cl_price7;
		}
		if($tsum >= 20 )
		{
			$samt=$cl_price8;
		}
		
		if($samt=="")
		{
			$samt=0.00;
		}
		$cur_amt=($samt/$currency);
		$camt = number_format($cur_amt, 2, '.', '');
		return array('shipping'=>$camt,'usd_shipping'=>$samt);
}
function shipping_overall_calc($pgrm,$pkg,$country_id,$currency)
{ 
	$selc=mysql_query("select * from tbl_country_list where cl_id='".$country_id."' LIMIT 1") or die(mysql_error());
	$rc=mysql_fetch_assoc($selc);
	$cl_price1=$rc['cl_price1'];
	$cl_price2=$rc['cl_price2'];
	$cl_price3=$rc['cl_price3'];
	$cl_price4=$rc['cl_price4'];
	$cl_price5=$rc['cl_price5'];
	$cl_price6=$rc['cl_price6'];
	$cl_price7=$rc['cl_price7'];
	$cl_price8=$rc['cl_price8'];
	$cl_discount=$rc['cl_discount'];
	$tsamt=0;
	$usd_tsamt=0;
	$gtamt=0;
	//$pkg=0;
	//$pgrm=0;
	$pgrm= ($pgrm) ? $pgrm : 0;
	//echo "<br>";
	$pkg= ($pkg) ? $pkg : 0;

		$sumgrm=($pgrm/1000);
		$tsum=($sumgrm+$pkg);
	
		if($tsum < 0.5 )
		{
			$samt=$cl_price1;
		}
		
		if(($tsum >= 0.5 ) && ($tsum < 1 ))
		{
			$samt=$cl_price2;
		}
		
		if(($tsum >= 1 ) && ($tsum < 2 ))
		{
			$samt=$cl_price3;
		}
		
		if(($tsum >= 2) && ($tsum < 5 ))
		{
			$samt=$cl_price4;
		}
		
		if(($tsum >= 5 ) && ($tsum < 10 ))
		{
			$samt=$cl_price5;
		}
	
		if(($tsum >= 10 ) && ($tsum < 15 ))
		{
			$samt=$cl_price6;
		}
		
		if(($tsum >= 15 ) && ($tsum < 20 ))
		{
			$samt=$cl_price7;
		}
		if($tsum >= 20 )
		{
			$samt=$cl_price8;
		}
		
		if($samt=="")
		{
			$samt=0.00;
		}
		$cur_amt=($samt/$currency);
		$camt = number_format($cur_amt, 2, '.', '');
		return array('shipping'=>$camt,'usd_shipping'=>$samt);
}
###################### SHIPPING CALCULATION DEPENDS ON COUNTRY ######################
function shipping_calc_ajax($q,$shipopt,$session_id,$currency)
{
	//$q-country id;
	//$q=$_REQUEST['q'];
	//$shipopt=$result['shipopt'];
	$selc=mysql_query("select * from tbl_country_list where cl_id='".$q."'") or die(mysql_error());
	$rc=mysql_fetch_array($selc);
	$cl_price1=$rc['cl_price1'];
	$cl_price2=$rc['cl_price2'];
	$cl_price3=$rc['cl_price3'];
	$cl_price4=$rc['cl_price4'];
	$cl_price5=$rc['cl_price5'];
	$cl_price6=$rc['cl_price6'];
	$cl_price7=$rc['cl_price7'];
	$cl_price8=$rc['cl_price8'];
	$cl_discount=$rc['cl_discount'];
	
	$select=mysql_query("select * from tbl_session where ses_id='".$session_id."'") or die(mysql_error());
	$ntot=mysql_num_rows($select);
	$i=0;
	$j=1;
	$sub_total=0;
	$usd_sub_total=0;
	$tsamt=0;
	$usd_tsamt=0;
	$gtamt=0;
	$pkg=0;
	$pgrm=0;
	$sgrm=0;
	$skg=0;
	
	while($res=mysql_fetch_array($select))
	{
			$i++;
			$selpro=mysql_query("select * from tbl_product where prod_id='".$res['prod_id']."' and prod_status='Y'") or die(mysql_error());
			$rpro=mysql_fetch_array($selpro);
			$seltax=mysql_query("select * from tbl_tax where tax_id='".$rpro['tax_id']."'") or die(mysql_error());
			$rtax=mysql_fetch_array($seltax);
			$sprice=number_format(($res['uamt'] / $currency), 2, '.', '');
			$tprice=number_format(($res['pamt'] / $currency), 2, '.', '');
			$prod_total=$tprice;
			$prod_usd_total=number_format($res['pamt'], 2, '.', '');
			
			if($shipopt=="IW")
			{
				$calc_shipping = shipping_calc($rpro['prod_id'],$res['quan'],$rpro['prod_wv'],$rpro['prod_weight'],$q,$currency);
				$total_shipping += $calc_shipping['shipping'];
				$total_usd_shipping += $calc_shipping['usd_shipping'];
			}
			else
			{
				$quan=$res['quan'];
				
				if($rpro['prod_wv']=="GRM")
				{
					$sgrm=$quan * $rpro['prod_weight']; 
					$pgrm=$pgrm + $sgrm;
				}
				else
				{
					$skg=$quan * $rpro['prod_weight'];
					$pkg=$pkg + $skg;
				}	
			}
			  $_SESSION['checkoutinfo']['hid_pid'.$i] = $rpro['prod_id'];
			  $_SESSION['checkoutinfo']['hid_pcode'.$i] = $rpro['prod_code'];
			  $_SESSION['checkoutinfo']['hid_uamt'.$i] = number_format($res['uamt'], 2, '.', '');
			  $_SESSION['checkoutinfo']['hid_usd_uamt'.$i] = number_format($res['uamt'], 2, '.', '');
			  $_SESSION['checkoutinfo']['hid_chk_uamt'.$i] = $sprice;
			  $_SESSION['checkoutinfo']['hid_prod_total'.$i] = $prod_total;
			  $_SESSION['checkoutinfo']['hid_usd_prod_total'.$i] = $prod_usd_total;
			  $_SESSION['checkoutinfo']['hid_tax'.$i] = $rtax['tax_percentage'];
			  $_SESSION['checkoutinfo']['hid_tax_type'.$i] = $rpro['prod_tax_type'];
			  $_SESSION['checkoutinfo']['hid_sid'.$i] = $res['s_id'];
			  $_SESSION['checkoutinfo']['hid_payment'.$i] = "Processing";
			  $_SESSION['checkoutinfo']['hid_pay_desc'.$i] = "Processing";
			  $_SESSION['checkoutinfo']['hid_shipamt'.$i] = ($calc_shipping['shipping']) ? $calc_shipping['shipping'] : "0.00";
			  $_SESSION['checkoutinfo']['hid_usd_shipamt'.$i] = ($calc_shipping['usd_shipping']) ? $calc_shipping['usd_shipping'] : "0.00";
			  $sub_total=$sub_total + $prod_total;
			  $usd_sub_total=$usd_sub_total + $prod_usd_total;
			  $j++;
	}
	
	$nnrow=mysql_num_rows($select);
	if($nnrow != 0)
	{
		  $prod_cost = number_format($sub_total, 2, '.', '');
		  $prod_usd_cost = number_format($usd_sub_total, 2, '.', '');
		  $country_discount = (($prod_cost * $cl_discount)/100);
		  $ctrydis = number_format($country_discount, 2, '.', '');
		  $country_usd_discount = (($prod_usd_cost * $cl_discount)/100);
		  $ctry_usd_dis = number_format($country_usd_discount, 2, '.', '');
		  #overall Shipping
		  if($shipopt!="IW")
		  {
			//echo $pgrm."###".$pkg;
			
			$calc_shipping = shipping_overall_calc($pgrm,$pkg,$q,$currency);
			//print_r($calc_shipping);
			$total_shipping = $calc_shipping['shipping'];
			$total_usd_shipping = $calc_shipping['usd_shipping'];
		  }
		  $_SESSION['totalinfo']['ntot'] = $ntot;
		  $_SESSION['totalinfo']['hid_product_cost'] = $prod_cost;
		  $_SESSION['totalinfo']['hid_shipping_cost'] = ($total_shipping) ? number_format($total_shipping, 2, '.', '') : "0.00";
		  $_SESSION['totalinfo']['hid_cod_cost'] = "0.00";
		  $_SESSION['totalinfo']['hid_coupon_cost'] = "0.00";
		  $_SESSION['totalinfo']['hid_coupon_val'] = "0.00";
		  $_SESSION['totalinfo']['hid_other_cost'] = $ctrydis;
		  
		  $_SESSION['totalinfo']['hid_usd_product_cost'] = $prod_usd_cost;
		  $_SESSION['totalinfo']['hid_usd_shipping_cost'] = ($total_usd_shipping) ? number_format($total_usd_shipping, 2, '.', '') : "0.00";
		  $_SESSION['totalinfo']['hid_usd_cod_cost'] = "0.00";
		  $_SESSION['totalinfo']['hid_usd_coupon_cost'] = "0.00";
		  $_SESSION['totalinfo']['hid_usd_coupon_val'] = "0.00";
		  $_SESSION['totalinfo']['hid_usd_other_cost'] = $ctry_usd_dis;
		 
		  $_SESSION['totalinfo']['hid_coupon_val'] = ($_SESSION['totalinfo']['hid_coupon_val'])? $_SESSION['totalinfo']['hid_coupon_val'] : "0.00";
		  $_SESSION['totalinfo']['hid_usd_coupon_val'] = ($_SESSION['totalinfo']['hid_usd_coupon_val'])? $_SESSION['totalinfo']['hid_usd_coupon_val'] : "0.00";
		  $_SESSION['totalinfo']['hid_coupon_cost'] = ($_SESSION['totalinfo']['hid_coupon_cost'])? $_SESSION['totalinfo']['hid_coupon_cost'] : "0.00";
		  $_SESSION['totalinfo']['hid_usd_coupon_cost'] = ($_SESSION['totalinfo']['hid_usd_coupon_cost'])? $_SESSION['totalinfo']['hid_usd_coupon_cost'] : "0.00";
		  
		  $grand_total = ($prod_cost+$total_shipping)-($ctrydis);
		  $gtotal = number_format($grand_total, 2, '.', '');
		  $grand_usd_total = ($prod_usd_cost+$total_usd_shipping)-($ctry_usd_dis);
		  $gtotal_usd = number_format($grand_usd_total, 2, '.', '');
		  $_SESSION['totalinfo']['hid_sub_total'] = $gtotal;
		  $_SESSION['totalinfo']['hid_usd_sub_total'] = $gtotal_usd;
	}
	return "1";
}
###################### SHIPPING CALCULATION DEPENDS ON COUNTRY END ######################

###################### COUPON CALCULATION ######################
function coupon_calc_ajax($co_code)
{
	$query=mysql_query("select * from tbl_settings LIMIT 1") or die(mysql_error());
	$result=mysql_fetch_assoc($query);
	
	$cur_date=date("Y-m-d",strtotime("+0 day ".$result['set_hrs']." hours ".$result['set_mins']." minutes"));
	#$co_code=$_REQUEST['co_code'];
	$hid_product_cost= ($_SESSION['totalinfo']['hid_product_cost']) ? $_SESSION['totalinfo']['hid_product_cost'] : 0;
	$hid_usd_product_cost= ($_SESSION['totalinfo']['hid_usd_product_cost']) ? $_SESSION['totalinfo']['hid_usd_product_cost'] : 0;
	$hid_shipping_cost= ($_SESSION['totalinfo']['hid_shipping_cost']) ? $_SESSION['totalinfo']['hid_shipping_cost'] : 0;
	$hid_usd_shipping_cost= ($_SESSION['totalinfo']['hid_usd_shipping_cost']) ? $_SESSION['totalinfo']['hid_usd_shipping_cost'] : 0;
	$hid_other_cost = ($_SESSION['totalinfo']['hid_other_cost']) ? $_SESSION['totalinfo']['hid_other_cost'] : 0;
	$hid_usd_other_cost= ($_SESSION['totalinfo']['hid_usd_other_cost']) ? $_SESSION['totalinfo']['hid_usd_other_cost'] : 0;
	$hid_cod_cost= ($_SESSION['totalinfo']['hid_cod_cost']) ? $_SESSION['totalinfo']['hid_cod_cost'] : 0;
	$hid_usd_cod_cost = ($_SESSION['totalinfo']['hid_usd_cod_cost']) ? $_SESSION['totalinfo']['hid_usd_cod_cost'] : 0;
	$tot=$hid_product_cost;
	$usd_tot=$hid_usd_product_cost;
	
	$scoupon=mysql_query("select * from tbl_coupon where co_code='$co_code' and co_status='Y' and co_exp_date>='$cur_date'");
	$ncoupon=mysql_num_rows($scoupon);
	if($ncoupon>0)
	{
		$rcoupon=mysql_fetch_array($scoupon);
		$co_value=$rcoupon['co_value'];
		$dis_val=number_format((($tot*$co_value)/100), 2, '.', '');
		$dis_usd_val=number_format((($usd_tot*$co_value)/100), 2, '.', '');
		$dis_other_val=$dis_val + $hid_other_cost;
		$dis_usd_other_val=$dis_usd_val + $hid_usd_other_cost;
		$tot_dis_val=$tot - $dis_other_val;
		$tot_usd_dis_val=$usd_tot - $dis_usd_other_val;
		$total_amt=$tot_dis_val+$hid_shipping_cost+$hid_cod_cost;
		$total_usd_amt=$tot_dis_val+$hid_shipping_cost+$hid_usd_cod_cost;
		$dis_amt=number_format($total_amt, 2, '.', '');
		$dis_usd_amt=number_format($total_usd_amt, 2, '.', '');
		$_SESSION['totalinfo']['hid_coupon_code'] = $co_code;
		$_SESSION['totalinfo']['hid_coupon_val'] = $co_value;
		$_SESSION['totalinfo']['hid_usd_coupon_val'] = $co_value;
		$_SESSION['totalinfo']['hid_coupon_cost'] = $dis_val;
		$_SESSION['totalinfo']['hid_usd_coupon_cost'] = $dis_usd_val;
		$_SESSION['totalinfo']['hid_sub_total'] = $dis_amt;
		$_SESSION['totalinfo']['hid_usd_sub_total'] = $dis_usd_amt;
		#echo $co_value."|||".$dis_val."|||".$dis_usd_val."|||".$dis_amt."|||".$dis_usd_amt."|||Y";
	}
	else
	{
		$tot_dis_val=$tot - $hid_other_cost;
		$tot_usd_dis_val=$usd_tot - $hid_usd_other_cost;
		$total_amt=$tot_dis_val+$hid_shipping_cost+$hid_cod_cost;
		$total_usd_amt=$tot_dis_val+$hid_shipping_cost+$hid_usd_cod_cost;
		$dis_amt=number_format($total_amt, 2, '.', '');
		$dis_usd_amt=number_format($total_usd_amt, 2, '.', '');
		$_SESSION['totalinfo']['hid_coupon_code'] = $co_code;
		$_SESSION['totalinfo']['hid_coupon_val'] = "0.00";
		$_SESSION['totalinfo']['hid_usd_coupon_val'] = "0.00";
		$_SESSION['totalinfo']['hid_coupon_cost'] = "0.00";
		$_SESSION['totalinfo']['hid_usd_coupon_cost'] = "0.00";
		$_SESSION['totalinfo']['hid_sub_total'] = $dis_amt;
		$_SESSION['totalinfo']['hid_usd_sub_total'] = $dis_usd_amt;
		#echo "0.00|||0.00|||0.00|||".$dis_amt."|||".$dis_usd_amt."|||N";
	}
	return "1";
}
###################### COUPON CALCULATION END ######################

###################### COD AND PAYMENT CALCULATION ######################
function payment_calc_ajax($chk,$cod_per)
{
		$query=mysql_query("select * from tbl_settings LIMIT 1") or die(mysql_error());
	    $result=mysql_fetch_assoc($query);
		$cur_date=date("Y-m-d",strtotime("+0 day ".$result['set_hrs']." hours ".$result['set_mins']." minutes"));
		//$cod_per=$_REQUEST['cod_per'];
		//$chk=$_REQUEST['chk'];
		$hid_product_cost= ($_SESSION['totalinfo']['hid_product_cost']) ? $_SESSION['totalinfo']['hid_product_cost'] : 0;
		$hid_usd_product_cost= ($_SESSION['totalinfo']['hid_usd_product_cost']) ? $_SESSION['totalinfo']['hid_usd_product_cost'] : 0;
		$hid_shipping_cost= ($_SESSION['totalinfo']['hid_shipping_cost']) ? $_SESSION['totalinfo']['hid_shipping_cost'] : 0;
		$hid_usd_shipping_cost= ($_SESSION['totalinfo']['hid_usd_shipping_cost']) ? $_SESSION['totalinfo']['hid_usd_shipping_cost'] : 0;
		$hid_other_cost = ($_SESSION['totalinfo']['hid_other_cost']) ? $_SESSION['totalinfo']['hid_other_cost'] : 0;
		$hid_usd_other_cost= ($_SESSION['totalinfo']['hid_usd_other_cost']) ? $_SESSION['totalinfo']['hid_usd_other_cost'] : 0;
		$hid_coupon_cost= ($_SESSION['totalinfo']['hid_coupon_cost'])? $_SESSION['totalinfo']['hid_coupon_cost'] : "0.00";
		$hid_usd_coupon_cost = ($_SESSION['totalinfo']['hid_usd_coupon_cost'])? $_SESSION['totalinfo']['hid_usd_coupon_cost'] : "0.00";
		$tot=$hid_product_cost;
		$usd_tot=$hid_usd_product_cost;
		
		if($chk=='Y')
		{
			$dis_val=number_format((($tot*$cod_per)/100), 2, '.', '');
			$dis_usd_val=number_format((($usd_tot*$cod_per)/100), 2, '.', '');
			
			$cod_val=$dis_val + $hid_product_cost +$hid_shipping_cost;
			$usd_cod_val=$dis_usd_val + $hid_usd_product_cost +$hid_shipping_cost;
			
			$tot_dis_val=$hid_coupon_cost + $hid_other_cost;
			$tot_dis_usd_val=$hid_usd_coupon_cost + $hid_usd_other_cost;
			
			$total_amt=$cod_val-$tot_dis_val;
			$total_usd_amt=$usd_cod_val-$tot_dis_usd_val;
			
			$dis_amt=number_format($total_amt, 2, '.', '');
			$dis_usd_amt=number_format($total_usd_amt, 2, '.', '');
			
			$_SESSION['totalinfo']['hid_cod_cost'] = $dis_val;
			$_SESSION['totalinfo']['hid_usd_cod_cost'] = $dis_usd_val;
			$_SESSION['totalinfo']['hid_sub_total'] = $dis_amt;
			$_SESSION['totalinfo']['hid_usd_sub_total'] = $dis_usd_amt;
			
			//echo $cod_per."|||".$dis_val."|||".$dis_usd_val."|||".$dis_amt."|||".$dis_usd_amt."|||".$chk;
		}
		else
		{
			
			$cod_val=$hid_product_cost +$hid_shipping_cost;
			$usd_cod_val=$hid_usd_product_cost +$hid_shipping_cost;
			
			$tot_dis_val=$hid_coupon_cost + $hid_other_cost;
			$tot_dis_usd_val=$hid_usd_coupon_cost + $hid_usd_other_cost;
			
			$total_amt=$cod_val-$tot_dis_val;
			$total_usd_amt=$usd_cod_val-$tot_dis_usd_val;
			
			$dis_amt=number_format($total_amt, 2, '.', '');
			$dis_usd_amt=number_format($total_usd_amt, 2, '.', '');
			
			$_SESSION['totalinfo']['hid_cod_cost'] = "0.00";
			$_SESSION['totalinfo']['hid_usd_cod_cost'] = "0.00";
			$_SESSION['totalinfo']['hid_sub_total'] = $dis_amt;
			$_SESSION['totalinfo']['hid_usd_sub_total'] = $dis_usd_amt;
			
			//echo $cod_per."|||0.00|||0.00|||".$dis_amt."|||".$dis_usd_amt."|||".$chk;
		}
		return 1;	
}
###################### COD AND PAYMENT CALCULATION END ######################

#settings
$settings = select_query("tbl_settings","*","set_id=1","1");
$settings_earg = @unserialize($settings['extra_fields']);
$state=array(
				'1'=>'Kerala',
				'2'=>'Tamil Nadu',
				'3'=>'Karnataka',
				'4'=>'Orissa',
				'5'=>'Delhi',
				'6'=>'Himachal Pradesh'
			);
$center=array(
				'01'=>'TVM (KL)',
				'02'=>'ALP (KL)',
				'03'=>'ICH (TN)',
				'04'=>'SMC (TN)',
				'05'=>'KMC (TN)',
				'06'=>'MDU (TN)',
				'07'=>'VEL (TN)',
				'08'=>'MAN (KA)',
				'09'=>'BBSR (OR)',
				'10'=>'NDL (Delhi)',
				'11'=>'SHI (HP)'
			);
$symptoms=array(
				'1'=>'34.1 History of Fever',
				'2'=>'34.2 Headache',
				'3'=>'34.3 Vomiting',
				'4'=>'34.4 Poor Sucking / Irritability',
				'5'=>'34.5 Petechial Rash',
				'6'=>'34.6 Cough',
				'7'=>'34.7 Difficulty in breathing',
				'8'=>'34.8 Fast breathing at rest',
				'9'=>'34.9 Unable to feed',
				'10'=>'34.10 Prostration / Lethargy',
				'11'=>'34.11 Severe Malnutrition',
				'12'=>'34.12 Others',
				'13'=>'34.12 specify'
			);
$antibiotics=array(
				'1'=>'38.3.1 Inj Penicillin',
				'2'=>'38.3.2 Amoxycillin',
				'3'=>'38.3.3 Ampicillin',
				'4'=>'38.3.4 Cefotaxime',
				'5'=>'38.3.5 Ceftriaxone',
				'6'=>'38.3.6 Cotrimoxazole',
				'7'=>'38.3.7 Macrolide',
				'8'=>'38.3.8 Aminoglycosides',
				'9'=>'38.3.9 Chloramphenicol',
				'10'=>'38.3.10 Others',
				'11'=>'38.3.10 Others Specify'
			);
$Vaccine=array(
				'1'=>'50.1.1 Hib',
				'2'=>'50.1.2 Pneumococcal',
				'3'=>'50.1.3 Meningococcal',
				'4'=>'50.1.4 Measles',
				'5'=>'50.1.5 Mumps',
				'6'=>'50.1.6 Rubella',
				'7'=>'50.1.7 Hep_A',
				'8'=>'50.1.8 Hep_B',
				'9'=>'50.1.9 IPV',
				'10'=>'50.1.10 Influenza',
				'11'=>'50.1.11 UPI',
				'12'=>'50.1.12 Others',
				'13'=>'50.1.12 Others Specify',
				'14'=>'50.1.99 Unknown'
			);
$Disease=array(
				'1'=>'50.2.1 Meningitis',
				'2'=>'50.2.2 Pneumonia',
				'3'=>'50.2.3 Diarrhoea',
				'4'=>'50.3.4 Hepatitis',
				'5'=>'50.2.5 Others',
				'6'=>'50.2.5 Others Specify',
				'7'=>'50.2.8 Don\'t know'
			);
$Source=array(
				'1'=>'51.1 Vaccination card',
				'2'=>'51.2 Medical Records',
				'3'=>'51.3 Note book',
				'4'=>'51.4 Oral'
			);
function forDate($date)
{
	if($date!="" && $date!="0000-00-00")
	{
	$date_of_admission_ex=$date;
	$dsc_ex=explode("/",$date_of_admission_ex);
	return $date_of_admission_f=$dsc_ex[2]."-".$dsc_ex[1]."-".$dsc_ex[0];
	}
}

function forDateN($date)
{
	$date1=explode(" ",$date);
		
		return $dt1=forDate($date1[0]);
}

function forDateNew($date)
{
	if($date!="" && $date!="0000-00-00")
	{
	return date('Y-m-d',strtotime($date));
	}
}


?>