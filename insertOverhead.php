<?
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/OverheadFunc.php"); 

//var_dump($_GET);
//echo "<br>";echo "<br>";echo "<br>";echo "<br>";
$dataArray = json_decode($_GET['data'], true);



if($dataArray['is_statistic'] == "on")
{
	$is_statistic = 'F';
}		
else
{
	$is_statistic = 'T';
}

if($dataArray['is_necessary'] == "on")
{
	$is_necessary = 'T';
}		
else
{
	$is_necessary = 'F';
}


$type = $dataArray['overhead_type'];
$category = $dataArray['overhead_category'];
$item = $dataArray['overhead_Item'];
$method = $dataArray['overhead_Method'];
$total_nt = $dataArray['overheadDollar'];
$personal_nt = $dataArray['PersonalDollar'];
$memo = $dataArray['memo'];
$rectime =  $dataArray['overhead-date'];
$statistict_time = $dataArray['statistict_time'];
if($personal_nt == "")
{
	$personal_nt = $total_nt;
}
$user_id = $dataArray['user_id'];

$guid = guid();

$insert_result = newOverhead($guid, $user_id,$is_statistic,$is_necessary,$type,$category,$item,$method,$total_nt,$personal_nt,$memo, $statistict_time,$rectime);

if($insert_result == "")
{
	$result = json_encode(array("action"=> "insert","success" => true ,"err"=>""));
}
else
{
	$result = json_encode(array("action"=> "insert", "success" => false ,"err"=>$insert_result["MSG"]));
}

header("location:index.php?result=".$result);

?>