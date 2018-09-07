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
	overhead_data["user_id"] =  document.getElementById("user_id").value;				
	
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
	var user_id = document.getElementById("user_id").value;		
	window.location.href = "?user_id="+user_id+"&overhead_type_radio=" + overheadtype;
}
function sel_overhead_Item_change(value,overhead_item_Arr_Str)
{
	document.getElementById("overhead_Item").value = value;	
	is_necessary_check(overhead_item_Arr_Str);
}

function overhead_type_change(type, overhead_item_Arr_Str)
{
   var user_id = document.getElementById("user_id").value;		   
   
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
   is_necessary_check(overhead_item_Arr_Str);
}

function is_necessary_check(overhead_item_Arr_Str)
{
	var type = document.getElementById("overhead_type").value;
	var name = document.getElementById("sel_overhead_Item").value;	
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

 
