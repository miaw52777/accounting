<?
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/OverheadFunc.php"); 

//var_dump($_GET);
//echo "<br>";echo "<br>";echo "<br>";echo "<br>"; exit;
$dataArray = json_decode($_GET['data'], true);


$is_statistic = $dataArray['is_statistic'];
$is_necessary = $dataArray['is_necessary'];

$type = $dataArray['overhead_type'];
$category = $dataArray['overhead_category'];
$item = $dataArray['overhead_Item'];
$method = $dataArray['overhead_Method'];
$total_nt = $dataArray['overheadDollar'];
$personal_nt = $dataArray['PersonalDollar'];
$memo = $dataArray['memo'];
$rectime =  $dataArray['overhead-date'];
$statistict_time = $dataArray['statistict_time'];
$overhead_xfer_to = $dataArray['overhead_xfer_to'];

if($personal_nt == "")
{
	$personal_nt = $total_nt;
}
$user_id = $dataArray['user_id'];

$guid = $_GET['guid'];


$update_result = updateOverhead($guid, $user_id,$is_statistic,$is_necessary,$type,$category,$item,$method,$total_nt,$personal_nt,$memo, $statistict_time,$rectime,$overhead_xfer_to);

if($update_result == "")
{
	$result = json_encode(array("action"=> "updateResult","success" => true ,"err"=>""));
}
else
{
	$result = json_encode(array("action"=> "updateResult", "success" => false ,"err"=>$update_result["MSG"]));
}

$page = base64_decode($dataArray['page']);

if($page == "")
{
	header("location:index.php?result=".$result);
}
else
{
	header("location:".$page);
}
?>