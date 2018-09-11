<?

// 統計收入/支出 By week
function getStatisticByWeek($mode,$user_id,$start_time,$end_time,$rule='')
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	
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
	$replaceStr = array($mode,$user_id,$start_time,$end_time);						
	$querySQL = str_replace($sourceStr,$replaceStr,$querySQL);			
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;
}

// 統計收入/支出 By Month
function getStatisticByMonth($mode,$user_id,$start_time,$end_time,$rule='')
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
	
	
	$rawsql = overheadRawdataSQL($rule);
	
	$querySQL = "select month
						,sum(case when overhead_category='收入' then :NT else 0 end) nt_income
						,sum(case when overhead_category='支出' then :NT else 0 end) nt_outlay
				from
				(
					".$rawsql."	
				)t    
				group by month
				";	
		
	$sourceStr = array(':NT',":USER_ID",":START_TIME", ":END_TIME");
	$replaceStr = array($mode,$user_id,$start_time,$end_time);	
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
	
	$querySQL .= ' order by statistic_time desc, rectime ';
		
	$sourceStr = array(":USER_ID",":START_TIME", ":END_TIME");
	$replaceStr = array($user_id,$start_time,$end_time);					
	$querySQL = str_replace($sourceStr,$replaceStr,$querySQL);		
	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;	
}
// 取得統計資料 Raw data(沒有時間限制)
function getOverheadRawdataNoTime($user_id,$rule='')
{
	include_once("conn.php");	
	include_once("CommFunc.php");	
		
	$querySQL =  overheadRawdataSQL($rule,'Y');	
	
	$querySQL .= ' order by statistic_time desc, rectime ';
		
	$sourceStr = array(":USER_ID");
	$replaceStr = array($user_id);					
	$querySQL = str_replace($sourceStr,$replaceStr,$querySQL);		
	
	$returnMsg = QuerySQL($querySQL);		
	return $returnMsg;	
}

function overheadRawdataSQL($rule='', $IS_NO_TIME="N")
{
	$sql = "SELECT MONTH(statistic_time) month 
					,WEEK(statistic_time) weekno 
					,date(rectime) day				
					,t.*    
					,DATE(rectime) overhead_date, time(rectime) overhead_time 
				FROM overhead_record t 
				where 1=1 
					   and t.user_id = ':USER_ID' 					   
			";
	if($IS_NO_TIME == 'N')
	{ 
		$sql .= "  and t.statistic_time >= ':START_TIME' 
				   and t.statistic_time <= ':END_TIME' 
				";
	}
	$sql .= ' '.$rule;
	return $sql;
}



?>

