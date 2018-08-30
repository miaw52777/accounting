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
	
	month = convertMonthFormate(date.getMonth());
	
	document.getElementById("selectMonth").innerHTML =  date.getFullYear() +
			"/" + month;
			
				
	var curYear = document.getElementById("selectYear").innerHTML;
	
	location.href = "statistic.php?slideno="+ slideIndex +"&year="+curYear+"&month="+date.getFullYear() +
			"/" + month + "#main"; 		
	
	
}


function changeYear(action)
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
	
	
	// get month information
	var curMonth = document.getElementById("selectMonth").innerHTML;
	var date = new Date(curMonth+"/15");
	var month = date.getMonth();
	month = convertMonthFormate(date.getMonth());
	
	location.href = "statistic.php?slideno="+ slideIndex +"&year="+curYear+"&month="+date.getFullYear() +
			"/" + month + "#main"; 		
	
	
}

f