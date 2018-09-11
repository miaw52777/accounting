<?

/********************  Account Start *****************************/
// 取得 帳戶資訊 查詢條件
function getOverhead_Account_Select_Rule($col, $value)
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
		if(strtoupper($col)  == "VALID")
		{
			$rule = sprintf(' and valid = %s ',GetSqlValueString($value,'text'));			
		}
		else if(strtoupper($col)  == "NAME")
		{
			$rule = sprintf(' and name = %s ',GetSqlValueString($value,'text'));			
		}
		else if(strtoupper($col)  == "TYPE")
		{
			$rule = sprintf(' and type = %s ',GetSqlValueString($value,'text'));			
		}
		else if(strtoupper($col)  == "IS_STATISTIC")
		{
			$rule = sprintf(' and is_statistic = %s ',GetSqlValueString($value,'text'));			
		}
	}
	
	if($printLog == 'T')
	{
		echo 'rule='.$rule.'<br>';
	}
	return $rule;

}

// 取得帳戶資訊
function getOverhead_Account($user_id,$mode, $rule='')
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	
	if($mode == '') $mode = 'nt';
	$querySQL = 'SELECT t.* 
						,IFNULL((select sum((case when z.overhead_category = "支出" then -1*:MODE else :MODE end)  ) pnt from overhead_record z where 1=1 and z.user_id = t.user_id and z.method = t.name),0) nt_overhead
						,IFNULL(
								(select sum((case when z.overhead_category = "支出" then -1*:MODE else :MODE end)  ) nt 
								 from overhead_record z 
								 where 1=1 
									and z.user_id = t.user_id
									and z.method = t.name
									and z.statistic_time like (SELECT DATE_FORMAT(max(a.statistic_time),"%Y-%m%") max_time
																FROM overhead_record a 
																WHERE 1=1
																	and a.user_id = z.user_id
																	and a.method = z.method)
								),0
						) nt_overhead_last_month   
				FROM overhead_account t 
				where 1=1 				
					and user_id="'.$user_id.'" 
					'.$rule.'
				order by seq
				';	
				
	$querySQL = str_replace(":MODE",$mode,$querySQL);	
				
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;
}

//取得銷帳戶資訊-Name
function getOverhead_Account_Name($user_id, $rule='')
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	$querySQL = 'SELECT distinct name 
				FROM overhead_account 
				where 1=1 
					and user_id=\''.$user_id.'\' 
					'.$rule.'
				order by seq
				';	
	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;	
}

// 刪除帳戶資訊
function deleteAccount($account_id)
{
	$sql = "delete from overhead_account where 1=1 and account_id = '$account_id'";
	echo $sql.'<br>';
	
	include_once("conn.php");	
	include_once("CommFunc.php");	
	
	$returnMsg = ExecuteSQL($sql);		
	
	return $returnMsg;	
}




/********************  Account End *****************************/

/********************  Overhead Item Start *****************************/
// 取得 開銷項目 查詢條件
function getOverhead_Item_List_Select_Rule($col, $value)
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
		if(strtoupper($col)  == "VALID")
		{
			$rule = sprintf(' and valid = %s ',GetSqlValueString($value,'text'));			
		}
		else if(strtoupper($col)  == "NAME")
		{
			$rule = sprintf(' and name = %s ',GetSqlValueString($value,'text'));			
		}
		else if(strtoupper($col)  == "TYPE")
		{
			$rule = sprintf(' and type = %s ',GetSqlValueString($value,'text'));			
		}		
	}
	
	if($printLog == 'T')
	{
		echo 'rule='.$rule.'<br>';
	}
	return $rule;

}
//取得 default 開銷項目
function getOverhead_Item_List($user_id, $rule='')
{
	include_once("conn.php");	
	include_once("CommFunc.php");		
	$querySQL = 'SELECT * 
				FROM Overhead_Item_List 
				where 1=1 				
					and user_id="'.$user_id.'" 
					'.$rule.'
				order by seq
				';	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;	
}

//取得 default 開銷項目所有 TYPE(食衣住行...)
function getOverhead_Item_List_Type($user_id, $rule='')
{
	include_once("conn.php");	
	include_once("CommFunc.php");		
	
	$querySQL = 'SELECT distinct type 
				FROM Overhead_Item_List 
				where 1=1 				
					and user_id="'.$user_id.'" 
					'.$rule.'
				order by seq
				';	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;	
}

/********************  Overhead Item End *****************************/

/********************  Overhead Record Start *****************************/
// 取得 OverheadRecord 查詢條件
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
			$rule = sprintf(' and is_statistic = %s ',GetSqlValueString($value,'text'));			
		}		
		elseif(strtoupper($col) == 'IS_NECESSARY')
		{
			$rule = sprintf(' and is_necessary = %s ',GetSqlValueString($value,'text'));			
		}		
		elseif(strtoupper($col) == 'OVERHEAD_TYPE')
		{
			$rule = sprintf('and overhead_type = %s',GetSqlValueString($value,'text'));
		}
		elseif(strtoupper($col)  == "OVERHEAD_CATEGORY")
		{
			$rule = sprintf(' and overhead_category = %s ',GetSqlValueString($value,'text'));			
		}	
		elseif(strtoupper($col)  == "METHOD")
		{
			$rule = sprintf(' and method = %s ',GetSqlValueString($value,'text'));			
		}
		elseif(strtoupper($col)  == "MEMO")
		{
			$rule = sprintf(' and memo like %s ',GetSqlValueString($value,'text'));			
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


//查詢開銷紀錄
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


//新增開銷項目
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

//修改開銷內容
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

/********************  Overhead Record End *****************************/

/********************  Overhead Summary Start *****************************/
// 目前所有支出/收入/結算
function SummaryTotalSettlement($user_id)
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	$sql = "select (income-outlay) settlement, income, outlay
			FROM
			(
				select 
				IFNULL((
					SELECT sum(nt)  nt
					FROM overhead_record
					where 1=1
						and overhead_category = '收入'
						and user_id = '".$user_id."'
						and is_statistic = 'T'
				),0) income
				,IFNULL((
					SELECT sum(pnt)  nt
					FROM overhead_record
					where 1=1
						and overhead_category = '支出'
						and user_id = '".$user_id."'
						and is_statistic = 'T'
				  ),0) outlay
				from dual  
			)t
			";
	$returnMsg = QuerySQL($sql);		
	return $returnMsg;	
}

/********************  Overhead Summary End *****************************/



?>

