<?php 
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/Mobile_Check.php");
include_once("function/OverheadFunc.php"); 

$user_id = "miaw52777";


// 取得開銷項目 list
$overhead_item_list = getOverhead_Item_List($user_id);				

$overhead_item_Arr_Str = "";
while($temp=mysqli_fetch_assoc($overhead_item_list['DATA']))
{				
	$overhead_item_Arr_Str .= $temp['type'].'@'.$temp['name'].'@'.$temp['is_necessary'].';';
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<? require_once('./header/title.php');  ?>

<link rel="stylesheet" type="text/css" href="./css/leftMenu.css">

</head>
  
<script type="text/javascript">

function show_checkoutday_text()
{	
	show_statistic_time = !show_statistic_time;
	
	if(show_statistic_time)
	{
		document.getElementById("statistict_time").style.display = "";	
	}
	else
	{
		document.getElementById("statistict_time").style.display = "none";	
	}
}

function show_overheadtime_text()
{	
	show_overhead_time = !show_overhead_time;
	
	if(show_overhead_time)
	{
		document.getElementById("overhead-date").style.display = "";	
		document.getElementById("overhead-time").style.display = "";	
	}
	else
	{
		document.getElementById("overhead-date").style.display = "none";	
		document.getElementById("overhead-time").style.display = "none";	
	}
	
}
function overhead_time_change(selectTime)
{	
	var overhead_date = document.getElementById("overhead-date").value;
	var overhead_time = document.getElementById("overhead-time").value;
	
	document.getElementById("img_overheadtime_title").title="消費Date/time : " + overhead_date + " " + overhead_time;
}
function statistict_time_change(selectTime)
{	
	document.getElementById("img_checkoutday_title").title="結帳日 : "+selectTime;
}

function delOverhead(guid) 
{ 
	if (confirm("確定要刪除?"))
	{
		location.href = "deleteOverhead.php?guid="+guid; 	
	}
}
// 將資訊帶入編輯頁面
function editOverhead(guid)
{
	location.href = "index.php?action=updateoverhead&guid="+guid; 
}
function performUpdateOverhead(guid)
{
	var overhead_data = overheadParam();
	if(overhead_data != null)
	{			
		window.location.href = "updateOverhead.php?guid="+guid+"&data="+JSON.stringify(overhead_data);
	}
}
function overheadParam()
{
	var overhead_data = new Object;
	if(document.getElementById("is_necessary").checked)
	{	
		overhead_data["is_necessary"] =  "T";
	}
	else
	{
		overhead_data["is_necessary"] =  "F";
	}
	
	if(document.getElementById("is_statistic").checked)
	{
		overhead_data["is_statistic"] =  "F";
	}
	else
	{
		overhead_data["is_statistic"] =  "T";
	}
	
	overhead_data["overhead_type"] =  document.getElementById("overhead_type").value;	
	overhead_data["overhead_category"] =  document.getElementById("overhead_category").value;
	overhead_data["overhead_Item"] =  document.getElementById("overhead_Item").value;
	overhead_data["overhead_Method"] =  document.getElementById("overhead_Method").value;
	overhead_data["overheadDollar"] =  document.getElementById("overheadDollar").value;
	overhead_data["PersonalDollar"] =  document.getElementById("PersonalDollar").value;
	overhead_data["memo"] =  document.getElementById("memo").value;
	overhead_data["overhead-date"] =  document.getElementById("overhead-date").value + " " + document.getElementById("overhead-time").value;
	overhead_data["statistict_time"] =  document.getElementById("statistict_time").value;		
	overhead_data["user_id"] =  "<? echo $user_id; ?>";		
	
	if(Number.isInteger(Number(overhead_data["overheadDollar"])) == false)
	{	
		alert("開銷總額必須是數字!");
		return null;
	}
	if(Number.isInteger(Number(overhead_data["PersonalDollar"])) == false)
	{	
		alert("個人開銷必須是數字!");
		return null;
	}
	
	if(overhead_data["overhead_Item"] == "")
	{
		document.getElementById("overhead_Item").style.backgroundColor = "red";		
		alert("項目不可為空!!");
		return null;
	}
	if(overhead_data["overheadDollar"] == "")
	{
		document.getElementById("overheadDollar").style.backgroundColor = "red";		
		alert("開銷總額不可為空!!");
		return null;
	}
	else 
	{
		return overhead_data;
	}
}
function addOverhead()
{
	var overhead_data = overheadParam();
	if(overhead_data != null)
	{			
		window.location.href = "insertOverhead.php?data="+JSON.stringify(overhead_data);
	}
}
function checkIsNum(sobject)
{	
	if(Number.isInteger(Number(sobject.value)) == false)
	{				
		sobject.style.backgroundColor = "red";
	}
	else{sobject.style.backgroundColor = "";}
	
}

function radioOverheadtypeSelect(overheadtype)
{	
	//window.location.href = "selectOverhead.php?user_id=<? echo $user_id; ?>&overhead_type_radio=" + overheadtype;
	
	window.location.href = "?user_id=<? echo $user_id; ?>&overhead_type_radio=" + overheadtype;
	/*if(overheadtype == "personal")
	{
		document.getElementById("overhead_type_radio_personal").checked = true;
		document.getElementById("overhead_type_radio_overall").checked = false;
	}
	else
	{
		document.getElementById("overhead_type_radio_personal").checked = false;
		document.getElementById("overhead_type_radio_overall").checked = true;
	}*/
}
function sel_overhead_Item_change(value)
{
	document.getElementById("overhead_Item").value = value;	
	is_necessary_check();
}

function overhead_type_change(type)
{	
   var user_id = "<? echo $user_id; ?>";
   var overhead_item_Arr_Str = "<? echo $overhead_item_Arr_Str; ?>";   
   
   var listArr = overhead_item_Arr_Str.split(";");		
   
   var sOption = " <option value=\"\"></option> ";
   
   for (l in listArr) // spec		
   {
	   var tempArr = listArr[l].split("@");
	   var arrtype = tempArr[0];	   
	   var arrname = tempArr[1];	  
	   
	   if(arrtype == type)
	   {
		   sOption += " <option value=\""+arrname+"\">"+arrname+"</option> "; 		   
	   }	   
   }		   
   document.getElementById("sel_overhead_Item").innerHTML = sOption;
   is_necessary_check();
}

function is_necessary_check()
{
	var type = document.getElementById("overhead_type").value;
	var name = document.getElementById("sel_overhead_Item").value;
	var overhead_item_Arr_Str = "<? echo $overhead_item_Arr_Str; ?>";   
	var listArr = overhead_item_Arr_Str.split(";");		
	if(name == "") return;
	
	for (l in listArr) // spec		
    {
	   var tempArr = listArr[l].split("@");
	   var arrtype = tempArr[0];	   
	   var arrname = tempArr[1];	  
	   var arrIsNecessary = tempArr[2];	
	   
	   if(arrtype == type && arrname == name && arrIsNecessary == "T" )
	   {
		   document.getElementById("is_necessary").checked = true;
		   break;
	   }	   
	   else 
	   {
		   document.getElementById("is_necessary").checked = false;
	   }
    }	
	
	
}

// initial page load
var show_statistic_time = false;
var show_overhead_time = false;

 
</script>

<? 
	require_once('./header/topHeader.php'); 
	echo menuList("Home");
?> 

<div>  
<? 
	require_once('./overheadRecordForm.php'); 
	$paramArr = array();
	
	if($_GET['action'] == "updateoverhead")
	{		
		$action = 'UPDATE';		
		$paramArr['GUID'] = $_GET['guid'];		
		$paramArr['TITLE'] = "編輯消費項目";
		
		$rule = getOverheadRecord_Select_Rule('GUID',$paramArr['GUID']);				
		$overhead_record_result = getOverheadRecord($user_id,$rule);
				
		
		if($overhead_record_result['RESULT'])
		{
			$paramArr['OVERHEAD_DATE'] = getSQLResultInfo($overhead_record_result['DATA'],'overhead_date');
			$paramArr['OVERHEAD_TIME'] = getSQLResultInfo($overhead_record_result['DATA'],'overhead_time');
			$paramArr['STATISTIC_TIME'] = getSQLResultInfo($overhead_record_result['DATA'],'statistic_time');
			$paramArr['NT'] = getSQLResultInfo($overhead_record_result['DATA'],'nt');
			$paramArr['PNT'] = getSQLResultInfo($overhead_record_result['DATA'],'pnt');
			$overhead_category = getSQLResultInfo($overhead_record_result['DATA'],'overhead_category');
			if($overhead_category == "支出")
			{
				$paramArr['OVERHEAD_CATEGORY_OUTLAY'] = "SELECTED";
				$paramArr['OVERHEAD_CATEGORY_INCOME'] = "";
			}
			else
			{
				$paramArr['OVERHEAD_CATEGORY_OUTLAY'] = "";
				$paramArr['OVERHEAD_CATEGORY_INCOME'] = "SELECTED";
			}
			if(getSQLResultInfo($overhead_record_result['DATA'],'is_statistic') == "F") $paramArr['IS_STATISTIC'] = "CHECKED";			
			else $paramArr['IS_STATISTIC'] = "";
			
			if(getSQLResultInfo($overhead_record_result['DATA'],'is_necessary') == "T") $paramArr['IS_NECESSARY'] = "CHECKED";			
			else $paramArr['IS_NECESSARY'] = "";
			
			
			$paramArr['MEMO'] = getSQLResultInfo($overhead_record_result['DATA'],'Memo');
			$paramArr['OVERHEAD_METHOD'] = getSQLResultInfo($overhead_record_result['DATA'],'method');
			$paramArr['OVERHEAD_NAME'] = getSQLResultInfo($overhead_record_result['DATA'],'overhead_item');
			$paramArr['OVERHEAD_TYPE'] = getSQLResultInfo($overhead_record_result['DATA'],'overhead_type');		
		    $paramArr['USER_ID'] = $user_id;		
			$paramArr['ITEM'] = getSQLResultInfo($overhead_record_result['DATA'],'overhead_item');		
			//var_dump($paramArr);
			echo generateOverheadForm($action, $paramArr);			
		}
		else
		{
			echo 'Error : '.$overhead_record_result['MSG'];
		}		
	}
	else
	{
		$action = 'NEW';		
		$paramArr['TITLE'] = "新增消費項目";
		$paramArr['ITEM'] = "";
		$paramArr['OVERHEAD_DATE'] = getToday();
		$paramArr['OVERHEAD_TIME'] = getNowTime();
		$paramArr['STATISTIC_TIME'] = getToday();
		$paramArr['NT'] = "";
		$paramArr['PNT'] = "";
		$paramArr['OVERHEAD_CATEGORY_OUTLAY'] = "SELECT";
		$paramArr['OVERHEAD_CATEGORY_INCOME'] = "";
		$paramArr['IS_STATISTIC'] = "";
		$paramArr['IS_NECESSARY'] = "";
		$paramArr['MEMO'] = "";
		$paramArr['OVERHEAD_METHOD'] = "";
		$paramArr['OVERHEAD_NAME'] = "";
		$paramArr['OVERHEAD_TYPE'] = "";
		$paramArr['GUID'] = "";
		$paramArr['USER_ID'] = $user_id;		
		echo generateOverheadForm($action, $paramArr);
		
	}
	
?>
 
	<?
	   //  print 消費歷史	   
		if(isset($_GET['result']))
		{
			// send insert or delete, then show result
			$actionArray = json_decode($_GET['result'], true);
			if($actionArray['action'] == 'insert')
			{
				echo '新增';				
			}
			else if($actionArray['action'] == 'delete')
			{
				echo '刪除';				
			}
			else if($actionArray['action'] == 'updateResult')
			{
				echo '修改';				
			}
			
			if($actionArray['success'] = 'true')
			{
				echo "成功!!";
			}
			else
			{
				echo  "Error : ".$actionArray["err"];
			}
			
			echo '<br>';
		}
		
		 
		$overhead_type_radio = $_GET['overhead_type_radio'];	
		if($overhead_type_radio == "") $overhead_type_radio='personal';
		
		
		if($overhead_type_radio == 'personal')
		{
			echo '<input type="radio" name="overhead_type_radio" onclick="radioOverheadtypeSelect(\'personal\');" checked>個人開銷 
				  <input type="radio" name="overhead_type_radio" onclick="radioOverheadtypeSelect(\'overall\');">全部開銷 <br>';
		}
		else 
		{
			echo '<input type="radio" name="overhead_type_radio" onclick="radioOverheadtypeSelect(\'personal\');">個人開銷 
			  <input type="radio" name="overhead_type_radio" onclick="radioOverheadtypeSelect(\'overall\');" checked>全部開銷 <br>';			
		}		
		
		$queryOverheadRecordResult = getOverheadRecord($user_id);
		echo 'Total count: '.mysqli_num_rows($queryOverheadRecordResult['DATA']).'<br>';		
		
		if($queryOverheadRecordResult["RESULT"])
		{
		    // 顯示開銷
			$count = 1;
			
			while($temp=mysqli_fetch_assoc($queryOverheadRecordResult['DATA']))
			{
				$htmlTemplate = '<div class="container">  
								  <p>:ITEM NT$ :NT (:METHOD)
								  <img src="./image/delete.png" id="img_overhead_delete" alt="刪除" title="刪除" onclick="delOverhead(\':GUID\');" class="right"> </img>
								  <img src="./image/edit.png" id="img_overhead_edit" alt="編輯" title="編輯" onclick="editOverhead(\':GUID\');" class="right"> </img>
								  </p>
								  
								  <span class="time-left">結帳日::STATISTIC_TIME</span>
								  <span class="time-right">消費時間::RECTIME</span>								  
								</div>';				
				
				$sourceStr = array(":ITEM", ":NT",':RECTIME',':STATISTIC_TIME',':GUID',":METHOD");
				
				if($overhead_type_radio == "personal")
				{
					$nt = $temp['pnt'];
				}
				else
				{
					$nt = $temp['nt'];
				}
				$replaceStr   = array($temp['overhead_item'],$nt,$temp['rectime'],$temp['statistic_time'],$temp['guid'],$temp['method']);
				
				$htmlTemplate = str_replace($sourceStr,$replaceStr,$htmlTemplate);
				
				echo $htmlTemplate;
			
				$count++;
			}
		}
		else
		{
			echo 'Get Overhead Records Error : '. $queryOverheadRecordResult["MSG"];
		}
	?>

 
 </div>
 
   
 <link rel="stylesheet" type="text/css" href="./css/cheatStyle.css">
 </body>


</html>