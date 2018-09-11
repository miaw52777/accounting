function deleteAccount(account_id) 
{ 
	if (confirm("確定要刪除帳戶?"))
	{
		location.href = "deleteAccount.php?account_id="+account_id; 	
	}
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
		
	location.href = "?overhead_type=" + overheadtype + "#main";
}