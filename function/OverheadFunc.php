<?

// ���o�}�P�覡(�{��)
function getOverheadMethod($user_id)
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	$querySQL = 'SELECT * FROM OverheadMethod where 1=1 and valid = "T" and user_id="'.$user_id.'"';	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;
}

//���o default �}�P����
function getOverhead_Item_List($user_id)
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	$querySQL = 'SELECT * FROM Overhead_Item_List where 1=1 and user_id=\''.$user_id.'\' order by seq';	
	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;	
}

//���o default �}�P���ةҦ� TYPE(������...)
function getOverhead_Item_List_Type($user_id)
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	$querySQL = 'SELECT distinct type FROM Overhead_Item_List where 1=1 and user_id=\''.$user_id.'\' order by seq';	
	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;	
}

// ���o OverheadRecord �d�߱���
function getOverheadRecord_Select_Rule($col, $value)
{	
	$rule = "";
	if($printLog == 'T')
	{
		echo 'col='.strtoupper($col).'<br>';
		echo 'value='.$value.'<br>';
	}
	
	
	if($value=='%') 
	{
		$rule = '';
	}
	else
	{
		if(strtoupper($col)  == "GUID")
		{
			$rule = sprintf(' and guid = %s ',GetSqlValueString($value,'text'));			
		}
		else if(strtoupper($col)  == "IS_STATISTIC")
		{
			$rule = sprintf(' and IS_STATISTIC = %s ',GetSqlValueString($value,'text'));			
		}		
		elseif(strtoupper($col) == 'IS_NECESSARY')
		{
			$rule = sprintf(' and IS_NECESSARY = %s ',GetSqlValueString($value,'text'));			
		}		
		elseif(strtoupper($col) == 'OVERHEAD_TYPE')
		{
			$rule = sprintf('and OVERHEAD_TYPE = %s',GetSqlValueString($value,'text'));
		}
		elseif(strtoupper($col)  == "OVERHEAD_CATEGORY")
		{
			$rule = sprintf(' and OVERHEAD_CATEGORY = %s ',GetSqlValueString($value,'text'));			
		}	
		elseif(strtoupper($col)  == "METHOD")
		{
			$rule = sprintf(' and METHOD = %s ',GetSqlValueString($value,'text'));			
		}
		elseif(strtoupper($col)  == "MEMO")
		{
			$rule = sprintf(' and MEMO like %s ',GetSqlValueString($value,'text'));			
		}	
		elseif(strtoupper($col)  == "ITEM")
		{
			$rule = sprintf(' and overhead_item like %s ',GetSqlValueString($value,'text'));			
		}	
	}
	
	if($printLog == 'T')
	{
		echo 'rule='.$rule.'<br>';
	}
	return $rule;

}


//�d�߶}�P����
function getOverheadRecord($user_id,$rule = '', $limit=50)
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	$querySQL  = 'SELECT t.*, DATE(rectime) overhead_date, time(rectime) overhead_time '.
	$querySQL .= 'FROM overhead_record t ';
	$querySQL .= 'where 1=1 and user_id="'.$user_id.'" ';	
	$querySQL .= ' '.$rule.' ';
	$querySQL .= ' order by rectime desc ';
	if($limit > 0) 
	{ // no limit, show all`
		$querySQL .= ' limit '.$limit; //default show 50 records.
	}
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;	
}


//�s�W�}�P����
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

//�ק�}�P���e
function updateOverhead($guid,$user_id,$is_statistic,$is_necessary,$type,$category,$item,$method,$total_nt,$personal_nt,$memo,$statistic_time,$rectime)
{
	$sql = "update overhead_record 
			set user_id=':USER_ID', 
				is_statistic=':IS_STATISTIC', 
				is_necessary=':IS_NECESSARY',
				overhead_type=':TYPE', 
				overhead_category=':CATEGORY', 
				overhead_item=':ITEM', 
				method=':METHOD', 
				nt=':TOTAL_NT', 
				pnt=':PERSONAL_NT', 
				memo=':MEMO',
				statistic_time=':STATISTIC_TIME',
				rectime=':RECTIME'
			where 1=1
				and guid=':GUID'
			";
		$sourceStr = array(":GUID",":USER_ID", ":IS_STATISTIC",":IS_NECESSARY",':TYPE', ':CATEGORY', ':ITEM', ':METHOD', ':TOTAL_NT', ':PERSONAL_NT', ':MEMO', ':STATISTIC_TIME', ':RECTIME');
		$replaceStr = array($guid,$user_id,$is_statistic,$is_necessary,$type,$category,$item,$method,$total_nt,$personal_nt,$memo,$statistic_time, $rectime);	
	
	$sql = str_replace($sourceStr,$replaceStr,$sql);	

	//echo $sql;
	include_once("conn.php");	
	include_once("CommFunc.php");	
	$returnMsg = ExecuteSQL($sql);		
	
	return $returnMsg;	
}
// �R���}�P
function deleteOverheadRecord($guid)
{
	$sql = "delete from overhead_record where 1=1 and guid = '$guid'";
	echo $sql.'<br>';
	
	include_once("conn.php");	
	include_once("CommFunc.php");	
	
	$returnMsg = ExecuteSQL($sql);		
	
	return $returnMsg;	
}




//���o�P�b���T
function getOverhead_Account_Info($user_id)
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	$querySQL = 'SELECT * FROM overhead_account where 1=1 and user_id=\''.$user_id.'\' order by seq';	
	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;	
}

//���o�P�b���T-Name
function getOverhead_Account_Name($user_id)
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	$querySQL = 'SELECT distinct name FROM overhead_account where 1=1 and user_id=\''.$user_id.'\' order by seq';	
	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;	
}

?>

