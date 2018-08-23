<?

// 取得開銷方式(現金)
function getOverheadMethod($user_id)
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	$querySQL = 'SELECT * FROM OverheadMethod where 1=1 and valid = "T" and user_id="'.$user_id.'"';	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;
}

//取得 default 開銷項目
function getOverhead_Item_List($user_id)
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	$querySQL = 'SELECT * FROM Overhead_Item_List where 1=1 and user_id=\''.$user_id.'\' order by seq';	
	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;	
}

//取得 default 開銷項目
function getOverhead_Item_List_test($user_id,$type)
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	$querySQL = 'SELECT name FROM Overhead_Item_List where 1=1 and user_id="'.$user_id.'" and type="'.$type.'" order by seq';	
	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;	
}

//查詢開銷紀錄
function getOverheadRecord($user_id, $limit=50)
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	$querySQL = 'SELECT t.* '.
	$querySQL .= 'FROM overhead_record t where 1=1 and user_id="'.$user_id.'" ';	
	$querySQL .= ' order by rectime desc ';
	if($limit > 0) 
	{ // no limit, show all`
		$querySQL .= ' limit '.$limit; //default show 50 records.
	}
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;	
}


//新增開銷
function newOverhead($guid,$user_id,$is_statistic,$is_necessary,$type,$category,$item,$method,$total_nt,$personal_nt,$memo,$statistic_time,$rectime)
{
	$sql = "INSERT INTO overhead_record (guid, user_id, is_statistic,is_necessary, overhead_type, overhead_category, overhead_item, method, nt, pnt, Memo, statistic_time, rectime) 
			VALUES (':GUID',':USER_ID', ':IS_STATISTIC', ':IS_NECESSARY',':TYPE', ':CATEGORY', ':ITEM', ':METHOD', ':TOTAL_NT', ':PERSONAL_NT', ':MEMO', ':STATISTIC_TIME', ':RECTIME')";
		$sourceStr = array(":GUID",":USER_ID", ":IS_STATISTIC",":IS_NECESSARY",':TYPE', ':CATEGORY', ':ITEM', ':METHOD', ':TOTAL_NT', ':PERSONAL_NT', ':MEMO', ':STATISTIC_TIME', ':RECTIME');
		$replaceStr = array($guid,$user_id,$is_statistic,$is_necessary,$type,$category,$item,$method,$total_nt,$personal_nt,$memo,$statistic_time, $rectime);	
	
	$sql = str_replace($sourceStr,$replaceStr,$sql);	

	//echo $sql;
	include_once("conn.php");	
	include_once("CommFunc.php");	
	$returnMsg = ExecuteSQL($sql);		
	
	return $returnMsg;	
}

// 刪除開銷
function deleteOverheadRecord($guid)
{
	$sql = "delete from overhead_record where 1=1 and guid = '$guid'";
	echo $sql.'<br>';
	
	include_once("conn.php");	
	include_once("CommFunc.php");	
	
	$returnMsg = ExecuteSQL($sql);		
	
	return $returnMsg;	
}

?>

