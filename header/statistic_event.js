function convertMonthFormate(dateMonth)
{
	var month = "";
	if(dateMonth == 0)
	{		
		month = "1";
	}
	else month = dateMonth+1;	
	
	if(month < 10) month = "0"+ month ;
	
	return month;
}

function changeMonth(action,slideIndex)
{	

	var curMonth = document.getElementById("selectMonth").innerHTML;
	var date = new Date(curMonth+"/15");
	var month = date.getMonth();
	if(action == "-")
	{
		date.setMonth(date.getMonth()-1);				
	}
	else
	{		
		date.setMonth(date.getMonth()+1);				
	}
	
	month = convertMonthFormate(date.getMonth());
	
	document.getElementById("selectMonth").innerHTML =  date.getFullYear() +
			"/" + month;
			
	var overheadtype = "personal";
	if(document.getElementById("overhead_type_radio_all").checked)
	{
		overheadtype = "overall";
	}		
	
	var param = getParammeter();
	location.href = "?" + param +"&month="+date.getFullYear() + "/" + month + "#OptionMenu"; 			
	
}


function changeYear(action,slideIndex)
{	

	var curYear = document.getElementById("selectYear").innerHTML;	
	
	if(action == "-")
	{
		curYear--;
	}
	else
	{		
		curYear++;
	}

	document.getElementById("selectYear").innerHTML = curYear;
	
	var overheadtype = "personal";
	if(document.getElementById("overhead_type_radio_all").checked)
	{
		overheadtype = "overall";
	}
	var filter_non_statistic = "F"; // 濾除統計
	if(document.getElementById("overhead_type_radio_all").checked)
	
	var param = getParammeter();
	location.href = "?" + param +"&year="+curYear + "#OptionMenu";
}

function getParammeter()
{
	var slideIndex = document.getElementById("slidno").value;
	var overhead_summary = "personal";
	
	if(document.getElementById("overhead_type_radio_all").checked)
	{
		overhead_summary = "overall";
	}
	var is_statistic = "F"; // 濾除不納入統計
	if(document.getElementById("is_statistic").checked)
	{
		is_statistic = "T";
	}
	
	
	var result = "slideno=" + slideIndex +"&is_statistic="+is_statistic +"&overhead_summary="+overhead_summary;
	
	if(slideIndex == 0)
	{		
		var start_date = document.getElementById("start_date").value;
		var end_date = document.getElementById("end_date").value;	
		var overhead_category = document.getElementById("overhead_category").value;		
		var overhead_type = document.getElementById("overhead_type").value;
		var overhead_method = document.getElementById("overhead_method").value;
		var overhead_Item = document.getElementById("overhead_Item").value;
		var memo = document.getElementById("memo").value;
				
		result = result + "&start_date="+start_date
						+"&end_date="+end_date
						+"&overhead_category="+overhead_category
						+"&overhead_type="+overhead_type
						+"&overhead_method="+overhead_method
						+"&overhead_Item="+overhead_Item
						+"&memo="+memo;								
	}
	
	return result;
}

function radioOverheadtypeSelect(overheadtype)
{		
	if(overheadtype == "overall")
	{
		document.getElementById("overhead_type_radio_p").checked = false;
		document.getElementById("overhead_type_radio_all").checked = true;
	}
	else 
	{
		document.getElementById("overhead_type_radio_p").checked = true;
		document.getElementById("overhead_type_radio_all").checked = false;		
	}
	
	var curMonth = document.getElementById("curMonth").value;
	var curYear = document.getElementById("curYear").value;
	
	var param = getParammeter();	
	location.href = "?" + param +"&year="+curYear +"&month="+curMonth + "#OptionMenu";
}

function statistic_checkbox_click()
{
	var curMonth = document.getElementById("curMonth").value;
	var curYear = document.getElementById("curYear").value;
	
	var param = getParammeter();	
	location.href = "?" + param +"&year="+curYear +"&month="+curMonth + "#OptionMenu";
}

