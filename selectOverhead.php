<?
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/OverheadFunc.php"); 

//var_dump($_GET);
//echo "<br>";echo "<br>";echo "<br>";echo "<br>";
$user_id = $_GET['user_id'];
$overhead_type_radio = $_GET['overhead_type_radio'];

$queryOverheadRecordResult = getOverheadRecord($user_id);
$dataresult = "";
if($queryOverheadRecordResult["RESULT"])
{
	// 顯示開銷
	$count = 1;
	
	if($overhead_type_radio == "") $overhead_type_radio = 'personal';
	while($temp=mysqli_fetch_assoc($queryOverheadRecordResult['DATA']))
	{
		if($overhead_type_radio == "personal")
		{
			$dataresult .= 'Rec.'.$count.' 消費時間 : '.$temp['rectime'].', 結帳時間 : '.$temp['statistic_time'].$temp['overhead_category'].' '.$temp['overhead_item'].' NT$'.$temp['pnt'];
		}
		else
		{
			$dataresult .= 'Rec.'.$count.' 消費時間 : '.$temp['rectime'].', 結帳時間 : '.$temp['statistic_time'].$temp['overhead_category'].' '.$temp['overhead_item'].' NT$'.$temp['nt'];
		}
		$dataresult .= '<img src="./image/delete.png" id="img_overhead_delete" height="30" width="30" alt="刪除" title="刪除" onclick="delOverhead(\''.$temp['guid'].'\');"> </img>';
		$dataresult .= '<br>';
		$count++;
	}
	$result = json_encode(array("overhead_type_radio" => $overhead_type_radio, "success" => true ,"err"=>"", "data_record" => base64_encode(urldecode($dataresult)) ));
}
else
{
	$result = json_encode(array("overhead_type_radio" => $overhead_type_radio, "success" => false ,"err"=>$queryOverheadRecordResult["MSG"], "data_record" => ""));
}


header("location:index.php?selectResult=".$result);
?>