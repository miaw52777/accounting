<?php 
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/Mobile_Check.php");
include_once("function/OverheadFunc.php"); 

$user_id = "miaw52777";


//var_dump($_POST['is_statistic']);

//var_dump($_POST['action']);
/*
if(isset($_POST['data'])){			    
	if (get_magic_quotes_gpc()) {
		$_POST['data'] = stripslashes($_POST['data']);					
	}
	echo json_encode(SaveProblem(json_decode($_POST['data'],true)));
}else{
	echo json_encode(array("success" => false ,"err"=>'nodata'));
}*/

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
			
			
			<input type="image" src="./image/new.png" alt="新增" id="addOverhead" name="addOverhead" height="30" width="30" value="+" />
			<!--
			<img src="./image/new.png" id="img_overhead_add" height="30" width="30" alt="新增" title="新增" onclick="addOverhead();"> </img>	-->	
		</div>
		
		
		<input name="overhead_Item" id="overhead_Item" type="text" size="10" placeholder="項目" list="overhead_Item_list" Autofocus="on" Required />	 
		<datalist id="overhead_Item_list"> 
			<option>加油</option> 
			<option>早餐</option> 
			<option>午餐</option> 
			<option>晚餐</option> 
		</datalist>
		
		<b><font color="#EE7700">NT$ </font></b><input name="overheadDollar" id="overheadDollar" type="text" size="10" Required placeholder="開銷總額" />
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
		 <select name="overhead_category">
			　<option value="支出" style="color:red">支出</option>
			　<option value="收入" style="color:green"> 收入</option>					
		</select>
		 <select name="overhead_Method">
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
		 <input type="checkbox" name="is_statistic">此筆不納入統計
		 
		 
		 <br>
			 備註:<br>
			 <div>
				<textarea name="memo" style="height: 50px;width:400px;"></textarea>		
			 </div>
		</div>
	</form>
 
	<!-- where the response will be displayed -->
	<div id='response'></div>
</fieldset>	
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script>
$(document).ready(function(){
    $('#overheadForm').submit(function(){
     
        $.ajax({
            type: 'POST',
            url: 'insertOverhead.php', 
            data: $(this).serialize()
        })
        .done(function(data){
             
            // show the response
            $('#response').html(data);
             
        })
        .fail(function() {
         
            // just in case posting your form failed
            alert( "Posting failed." );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
});
</script>
 
</body>
</html>