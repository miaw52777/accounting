<?
include_once("function/CommFunc.php");
include_once("function/OverheadFunc.php"); 


$account_id = $_GET["account_id"];
$delete_result = deleteAccount($account_id);

if(delete_result)
{
	$result = json_encode(array("action"=> "delete","success" => true ,"err"=>""));
}
else
{
	$result = json_encode(array("action"=> "delete", "success" => false ,"err"=>$delete_result["MSG"]));
}


if (strpos($_SERVER['HTTP_REFERER'], ".php?") !== false)
{
	header("location:".$_SERVER['HTTP_REFERER']."&result=".$result);
}
else
{
	header("location:".$_SERVER['HTTP_REFERER']."?result=".$result);
}

?>