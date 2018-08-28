function changeMonth(action)
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
	
	if(date.getMonth() == 0)
	{		
		month = "1";
	}
	else month = date.getMonth()+1;
	
	
	document.getElementById("selectMonth").innerHTML =  date.getFullYear() +
			"/" + month;
			
	location.href = "statistic.php?month="+date.getFullYear() +
			"/" + month + "#main"; 		
	
	
}