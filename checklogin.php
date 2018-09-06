<?php
include_once('./secure.php');
include_once('./function/conn.php');
include_once('./function/CommFunc.php');
$user_id = $_POST['email'];$pass = $_POST['pass'];
if(isset($_POST['email']) && isset($_POST['pass']))
{	
	$sql = sprintf("SELECT * FROM user_list t where 1=1 and user_id = '%s' and user_pass = '%s' ",$user_id, $pass);	
	$returnMsg = QuerySQL($sql);		
	if(($returnMsg['RESULT']) && ($returnMsg['REC_CNT'] > 0))
	{
		// check priv pass		$_SESSION['loginuid'] = '1';
		$_SESSION['user_id'] = $user_id;
		echo json_encode(array('success' => true));				if($_POST['page'] != "")		{			header("Location: ".$_POST['page']);		}
		else header("Location: index.php");
	}
	else
	{
		// check priv fail, pls retry
		echo json_encode(array('success' => false)); 
		header("Location: login.php");
	}
	exit;
}

if(!is_login())
{
	header("Location: login.php");
	exit;
} 
?>