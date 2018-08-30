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
	
	location.href = "?slideno="+ slideIndex +"&month="+date.getFullYear() +
			"/" + month + "#main"; 		
	
	
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
	
	location.href = "?slideno="+ slideIndex +"&year="+curYear + "#main";
}

