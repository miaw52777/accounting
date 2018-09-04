<?php
/*
此文件為後端文件 使用者不應直接拜訪此頁面
*/
 
if(!isset($_SESSION))
{
	session_name("accounting");
	session_set_cookie_params("1209600"); // 14days = 3600 * 24 * 14
	session_start();		
}
if(!function_exists("is_login")){
	function is_login()	
	{
		include_once("./function/CommFunc.php");	
		
		return isset($_SESSION['loginuid'])&& ($_SESSION['loginuid']!="logout") && 	$_SESSION['loginuid'];
	
	}	
}

if(!function_exists("is_admin")){
	function is_admin()	{
		return isset($_SESSION['adminlevel'])&& ($_SESSION['adminlevel']=="a");
	}
}
if(!function_exists("is_CSRF")){
	function is_CSRF()	{
		$site = "http://localhost/accounting";
		return ($_SERVER['HTTP_REFERER'] != $site);
	}	
}
$UID = isset($_SESSION['loginuid']) ? $_SESSION['loginuid'] : 0;

?>