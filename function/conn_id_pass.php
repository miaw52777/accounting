<?php
  
$sql_hostname = "localhost";
$sql_database = "accounting";
$sql_username = "miaw";
$sql_password = "0000";

$link = mysqli_connect($sql_hostname, $sql_username, $sql_password) or trigger_error(mysqli_error(),E_USER_ERROR);

?>
