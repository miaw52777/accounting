<?

//var_dump($_GET);
echo json_encode(array("success" => false ,"err"=>"nodata"));
/*
if(isset($_REQUEST))
{
	if($_POST['is_statistic'] == "on")
	{
		$is_statistic = 'F';
	}		
	else
	{
		$is_statistic = 'T';
	}

	$type = $_POST['overhead_type'];
	$category = $_POST['overhead_category'];
	$item = $_POST['overhead_Item'];
	$method = $_POST['overhead_Method'];
	$total_nt = $_POST['overheadDollar'];
	$personal_nt = $_POST['PersonalDollar'];
	$memo = $_POST['memo'];
	$rectime =  $_POST['overhead-date'].' '.$_POST['overhead-time'];
	$statistict_time = $_POST['statistict_time'];
	if($personal_nt == "")
	{
		$personal_nt = $total_nt;
	}

	$guid = guid();
	$insert_result = newOverhead($guid, $user_id,$is_statistic,$type,$category,$item,$method,$total_nt,$personal_nt,$memo, $statistict_time,$rectime);
}	*/
?>