<?php 
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/Mobile_Check.php");
include_once("function/OverheadFunc.php"); 

$user_id = "miaw52777";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<title>Miaw Accounting Room</title>
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

function addOverhead()
{
	var overhead_data = new Object;
	overhead_data["is_statistic"] =  document.getElementById("is_statistic").value;
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
	
	
	
	//alert(JSON.stringify(overhead_data));
	window.location.href = "insertOverhead.php?data="+JSON.stringify(overhead_data);
}

// initial page load
var show_statistic_time = false;
var show_overhead_time = false;
</script>

<fieldset>
	<legend>新的開銷</legend>
	<form id='overheadForm'>
		<div>			
			
			<img src="./image/overhead_time.png" id="img_overheadtime_title" height="30" width="30" alt="消費Date/time" title="消費Date/time : <? echo getToday().' '.getNowTime(); ?>" onclick="show_overheadtime_text();"> </img>		
			<input type="date" id="overhead-date" name="overhead-date" value="<? echo getToday(); ?>" style="display:none" onchange="overhead_time_change();"/>				
			<input type="time" id="overhead-time" name="overhead-time" value="<? echo getNowTime(); ?>" style="display:none" onchange="overhead_time_change();"/>
			<img src="./image/checkout_day.png" id="img_checkoutday_title" height="30" width="30" alt="結帳日" title="結帳日 : <? echo getToday();?>" onclick="show_checkoutday_text();"> </img>
			<input type="date" id="statistict_time" name="statistict_time" value="<? echo getToday();?>" style="display:none" onchange="statistict_time_change(this.value);"/>
			
			<!--
			<input type="image" src="./image/new.png" alt="新增" id="addOverhead" name="addOverhead" height="30" width="30" value="+" />Required
			-->	
			
			<img src="./image/new.png" id="img_overhead_add" height="30" width="30" alt="新增" title="新增" onclick="addOverhead();"> </img>	
		</div>
		
		
		<input name="overhead_Item" id="overhead_Item" type="text" size="10" placeholder="項目" list="overhead_Item_list" Autofocus="on"  />	 
		<datalist id="overhead_Item_list"> 
			<option>加油</option> 
			<option>早餐</option> 
			<option>午餐</option> 
			<option>晚餐</option> 
		</datalist>
		
		<b><font color="#EE7700">NT$ </font></b><input name="overheadDollar" id="overheadDollar" type="text" size="10" placeholder="開銷總額" />
		<b><font color="#EE7700">PNT$ </font></b><input name="PersonalDollar" id="PersonalDollar" type="text" size="10" placeholder="個人開銷金額" />
		
				
		
		 <br>
		<div>
		 
		 <select name="overhead_type" id="overhead_type">
		 <?
			$overhead_typelist = array("食","衣","住","行","育","樂","他");		
			for($i=0;$i<count($overhead_typelist);$i++)
			{
				echo '<option value='.$overhead_typelist[$i].'>'.$overhead_typelist[$i].'</option>';
				
			}
		 
		 ?>
		</select>
		 <select name="overhead_category" id = "overhead_category">
			　<option value="支出" style="color:red">支出</option>
			　<option value="收入" style="color:green"> 收入</option>					
		</select>
		 <select name="overhead_Method" id="overhead_Method">
		 <?
			
			$returnMsg = getOverheadMethod($user_id);	
			
			if(!$returnMsg['RESULT'])						
			{							
				echo $returnMsg['MSG'];						
			}						
			else						
			{							
				while($temp=mysqli_fetch_assoc($returnMsg['DATA']))
				{
					echo '<option value="'.$temp['type_id'].'">'.$temp['type_name'].'</option>';
				}
			}
		
		 ?>
		 </select>	
		 <input type="checkbox" name="is_statistic" id="is_statistic">此筆不納入統計
		 
		 
		 <br>
			 備註:<br>
			 <div>
				<textarea name="memo" id="memo" style="height: 50px;width:400px;"></textarea>		
			 </div>
		</div>
		
	</form>
 
	<?
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
		
		echo 'Show 50 records.(default)<br>';
		
		$queryOverheadRecordResult = getOverheadRecord($user_id);
		if($queryOverheadRecordResult["RESULT"])
		{
		    // 顯示開銷
			while($temp=mysqli_fetch_assoc($queryOverheadRecordResult['DATA']))
			{
				echo '消費時間 : '.$temp['rectime'].', 結帳時間 : '.$temp['statistic_time'].$temp['overhead_category'].' '.$temp['overhead_item'].' NT$'.$temp['nt'].' PNT$'.$temp['pnt'];
				echo '<img src="./image/delete.png" id="img_overhead_delete" height="30" width="30" alt="刪除" title="刪除" onclick="delOverhead(\''.$temp['guid'].'\');"> </img>';
				echo '<br>';
			}
		}
		else
		{
			echo 'Get Overhead Records Error : '. $queryOverheadRecordResult["MSG"];
		}
	?>
	<!-- where the response will be displayed -->
	<div id='response'></div>
</fieldset>	

 
 
</body>
</html>