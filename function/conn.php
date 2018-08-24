<?php


  
include ("conn_id_pass.php");
include ("GetSQLValueString.php");
if(!function_exists('json_encode'))
{
	include('JSON.php');
	function json_encode($val)
	{
		$json = new Services_JSON();
		return $json->encode($val);
	}

	function json_decode($val)
	{
		$json = new Services_JSON();
		return $json->decode($val);
	}
}
  
mysqli_query("SET NAMES 'utf8'",$link); 
mysqli_query("SET CHARACTER_SET_CLIENT=utf8",$link); 
mysqli_query("SET CHARACTER_SET_RESULTS=utf8",$link);
$db_select = mysqli_select_db($link,$sql_database);
if (!$db_select) {
    die("Database selection failed: " . mysqli_error());
}

?>
