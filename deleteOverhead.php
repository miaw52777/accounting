<?
include_once("function/CommFunc.php");
include_once("function/OverheadFunc.php"); 

$guid = $_GET["guid"];
$delete_result = deleteOverheadRecord($guid);

if(delete_result)
{
	$result = json_encode(array("action"=> "delete","success" => true ,"err"=>""));
}
else
{
	$result = json_encode(array("action"=> "delete", "success" => false ,"err"=>$delete_result["MSG"]));
}

header("location:index.php?result=".$result);


?>