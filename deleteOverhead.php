<?
include_once("function/CommFunc.php");
include_once("function/OverheadFunc.php"); 

$guid = $_GET["guid"];
$result = deleteOverheadRecord($guid);
if($result == "")
{
	header("location:index.php");
}
else	
{
	echo $result;
}
?>