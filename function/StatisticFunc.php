<?

// 統計收入/支出 By week
function getStatisticByWeek($mode,$user_id,$start_time,$end_time,$rule='')
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	
	if($mode == 'pnt') $modeStr = 'pnt'; 
	else if($mode == 'nt_filter_non_statistic') $modeStr = 'nt_filter_non_statistic'; 
	else if($mode == 'pnt_filter_non_statistic') $modeStr = 'pnt_filter_non_statistic'; 
	else $modeStr = 'nt'; 
	
	$rawsql = overheadRawdataSQL($rule);
	$querySQL = "select weekno,statistic_time
						,sum(case when overhead_category='收入' then :NT else 0 end) nt_income
						,sum(case when overhead_category='支出' then :NT else 0 end) nt_outlay
				from
				(
					".$rawsql."	
				)t    
				group by weekno,statistic_time
				";	
		
	$sourceStr = array(':NT',":USER_ID",":START_TIME", ":END_TIME");
	$replaceStr = array($modeStr,$user_id,$start_time,$end_time);					
	$querySQL = str_replace($sourceStr,$replaceStr,$querySQL);		
	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;
}

// 統計收入/支出 By Month
function getStatisticByMonth($user_id,$start_time,$end_time,$rule='')
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	
	$rawsql = overheadRawdataSQL($rule);
	$querySQL = "select month,statistic_time,overhead_category
						,sum(nt) nt
						,sum(pnt) pnt
						,sum(nt_filter_non_statistic) nt_filter_non_statistic
						,sum(pnt_filter_non_statistic) pnt_filter_non_statistic
				from
				(
					".$rawsql."	
				)t    
				group by month,statistic_time,overhead_category
				";	
		
	$sourceStr = array(":USER_ID",":START_TIME", ":END_TIME");
	$replaceStr = array($user_id,$start_time,$end_time);					
	$querySQL = str_replace($sourceStr,$replaceStr,$querySQL);		
	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;
}
// 取得統計資料 Raw data
function getOverheadRawdata($user_id,$start_time,$end_time,$rule='')
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
		
	$querySQL =  overheadRawdataSQL($rule);	
	
	$querySQL .= ' order by statistic_time ';
		
	$sourceStr = array(":USER_ID",":START_TIME", ":END_TIME");
	$replaceStr = array($user_id,$start_time,$end_time);					
	$querySQL = str_replace($sourceStr,$replaceStr,$querySQL);		
	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;	
}
function overheadRawdataSQL($rule='')
{
	$sql = "SELECT MONTH(statistic_time) month
					,WEEK(statistic_time) weekno
					,date(rectime) day
					,case when t.is_statistic = 'F' then 0 else t.nt end nt_filter_non_statistic    
					,case when t.is_statistic = 'F' then 0 else t.pnt end pnt_filter_non_statistic
					,t.*    
				FROM overhead_record t
				where 1=1
				   and t.statistic_time >= ':START_TIME'
				   and t.statistic_time <= ':END_TIME'
				   and t.user_id = ':USER_ID'
			".$rule;
	return $sql;
}



?>

