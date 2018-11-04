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
	overhead_method_change();
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
	overhead_data["page"] =  document.getElementById("page").value;				
	overhead_data["overhead_xfer_to"] =  document.getElementById("overhead_xfer_to").value;
	
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
	else document.getElementById("overhead_Item").style.backgroundColor = "";		
	
	if(document.getElementById("overhead_category").value == "轉帳")
	{
		if(document.getElementById("overhead_Method").value == document.getElementById("overhead_xfer_to").value)
		{
			document.getElementById("overhead_Method").style.backgroundColor = "red";		
			document.getElementById("overhead_xfer_to").style.backgroundColor = "red";		
			alert("轉入與轉出帳戶不可相同!!");
			return null;			
		}			
	}
	else 
	{
		document.getElementById("overhead_Method").style.backgroundColor = "";		
		document.getElementById("overhead_xfer_to").style.backgroundColor = "";	
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
   
   var sOption = " <option value=\"\">-Select-</option> ";
   
   for (l in listArr) // spec		
   {
	   var tempArr = listArr[l].split("@");
	   var arrtype = tempArr[0];	   
	   var arrname = tempArr[1];	  
	   
	   if(arrtype == type)
	   {
		   if(arrname != "")
		   {			   
			 sOption += " <option value=\""+arrname+"\">"+arrname+"</option> "; 		   
		   }
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

//根據收入/支出列出對應的消費方式
function overhead_category_change()
{
   var option_Arr_Str = document.getElementById("overhead_category_method_str").value;			
   var listArr = option_Arr_Str.split(";");		   
   var sel_category = document.getElementById("overhead_category").value;	
   var sOption = "";
   
   for (l in listArr) // spec		
   {
	   var tempArr = listArr[l].split("@");
	   var arrcategory = tempArr[0]; //支出/收入/轉帳
	   var arrname = tempArr[1]; // 名稱	  
	   var arrcheckoutday = tempArr[2]; // 結帳日
	   var arrpaymentday = tempArr[3]; // 繳費日
	   
	   if(arrcategory.includes(sel_category))
	   {
		   if(arrname != "")
		   {			   
			 sOption += " <option value=\""+arrname+"\">"+arrname+"</option> "; 		   
		   }
	   }	   
   }		   
   document.getElementById("overhead_Method").innerHTML = sOption;   
   document.getElementById("overhead_xfer_to").innerHTML = sOption;
   if(sel_category != "轉帳")
   {
	  document.getElementById("overhead_xfer_to").style.display = "none";	
   }
   else 
   {
	   document.getElementById("overhead_xfer_to").style.display = "";	
   }
    	
   overhead_method_change();
}



//根據選擇自動切換結帳日
function overhead_method_change()
{	
   var option_Arr_Str = document.getElementById("overhead_category_method_str").value;		
   var listArr = option_Arr_Str.split(";");		      
   var sel_category = document.getElementById("overhead_category").value;	
   var sel_method = document.getElementById("overhead_Method").value;	
   var overhead_date = document.getElementById("overhead-date").value; //現在消費時間		  
   var sOption = "";
   
   for (l in listArr) // spec		
   {
	   var tempArr = listArr[l].split("@");
	   var arrcategory = tempArr[0]; //支出/收入
	   var arrname = tempArr[1]; // 名稱	  
	   var arrcheckoutday = tempArr[2]; // 結帳日
	   var arrpaymentday = tempArr[3]; // 繳費日
	   
	   
	   if(arrname == sel_method && sel_category != "收入" && arrcheckoutday != "" && arrpaymentday != "")
	   {		   
		   
		   // auto select 統計時間		
		   
		   var overhead_Arr = overhead_date.split("-"); //2018/08/01		   
		   var overhead_year = Number(overhead_Arr[0]);
		   var overhead_month = Number(overhead_Arr[1]);
		   var overhead_day = Number(overhead_Arr[2]);
		  		   
		   var checkoutday = overhead_year + "-" + overhead_Arr[1] + "-" + arrcheckoutday; // 當月結帳日
		   var paymentday = document.getElementById("statistict_time").value; // default		   
		   var shiftMonth = 0;
		   var tmpcheckoutday = new Date(checkoutday);
		   var tmpoverhead_date = new Date(overhead_date);
		   
		   //alert("overhead_date :" + tmpoverhead_date + ",checkoutday : " + tmpcheckoutday);
		   if(tmpcheckoutday > tmpoverhead_date)
		   {
			   // 在當月結帳日之前歸給這期繳費日, ex : 結帳日:12號/繳費日01號, 9/1 消費就會歸給 10/1
			   shiftMonth = 1;
			   var tmpMonth = (overhead_month+shiftMonth) % 12;
			   var tmpYear = overhead_year;			   
			   if(overhead_month+shiftMonth > 12)  tmpYear = overhead_year + 1;			   
			   if(tmpMonth == 0) tmpMonth = 12;			   
			   
		   }
		   else
		   {
			   // 在當月結帳日之後歸給這期繳費日, ex : 結帳日:12號/繳費日01號, 9/13 消費就會歸給 11/1
			   shiftMonth = 2;
			   var tmpMonth = (overhead_month+shiftMonth) % 12;			   
			   var tmpYear = overhead_year;
			   if(overhead_month+shiftMonth > 12)  tmpYear = overhead_year + 1;			   
			   if(tmpMonth == 0) tmpMonth = 12;			   
		   }
		   
		   if(Number(tmpMonth) > 0 && Number(tmpMonth) < 10) // 0~9
		   {
			   tmpMonth = "0" + Number(tmpMonth);
		   }
		   
		   if(Number(arrpaymentday) > 0 && Number(arrpaymentday) < 10) // 0~9
		   {
			   arrpaymentday = "0" + Number(arrpaymentday);
		   }
		   
		   paymentday = tmpYear + "-" + tmpMonth + "-" + arrpaymentday;		   		   
		    
		   document.getElementById("statistict_time").value = paymentday;
		   document.getElementById("statistict_time").style.display = "";
		   document.getElementById("statistict_time").style.backgroundColor = "pink";
		   break;
	   }	
	   else 
	   {		   
		   document.getElementById("statistict_time").value = overhead_date;
		   document.getElementById("statistict_time").style.backgroundColor = "";
	   }
   }	   
}


// initial page load
var show_statistic_time = false;
var show_overhead_time = false;