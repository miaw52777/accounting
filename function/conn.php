<?php


$sql_hostname = "localhost";
$sql_database = "accounting";
$sql_username = "miaw";
$sql_password = "0000";

$link = mysqli_connect($sql_hostname, $sql_username, $sql_password) or trigger_error(mysqli_error(),E_USER_ERROR);

mysqli_query("SET NAMES 'utf8'",$link); 
mysqli_query("SET CHARACTER_SET_CLIENT=utf8",$link); 
mysqli_query("SET CHARACTER_SET_RESULTS=utf8",$link);
$db_select = mysqli_select_db($link,$sql_database);
if (!$db_select) {
    die("Database selection failed: " . mysqli_error());
}

?>
