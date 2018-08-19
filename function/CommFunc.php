<?
// 字串斷行
function stringWrap($string,$length=18,$append="<br>") 
{
  
  $result = wordwrap($string,$length,$append,true);

  return $result;
}

// Query SQL 並回傳成功或錯誤訊息
function QuerySQL($sql)
{
	include("conn.php");
	$returnMsg = array();
	
	$result = mysqli_query($link,$sql);	
	$msg = mysqli_error($link);
	
	if($printLog=='T')
	{
		echo $sql.'<br>';
	}
	//送出查詢並取得結果
	$result = mysqli_query($link,$sql);
	
	 
	if (!$result) 
	{ 
		$returnMsg['RESULT'] = false;
		$returnMsg['MSG'] = 'Sql Error : '.mysqli_error($link);
		$returnMsg['DATA']= '';
		$returnMsg['REC_CNT']= 0;		
		$returnMsg['SQL']= $sql;		
	}
	else
	{		
		if($printLog=='T')
		{ 
			echo var_dump($result).'<br>';
			echo 'Record count : '.mysqli_num_rows($result).'<br>';
			$data = mysqli_fetch_assoc($result);			
		}
		$returnMsg['RESULT'] = true;
		$returnMsg['MSG'] = ''; 
		$returnMsg['DATA']= $result;
		$returnMsg['REC_CNT']= mysqli_num_rows($result);
		$returnMsg['SQL']= $sql;		
	}
	return $returnMsg;
}

// Execute SQL 
function ExecuteSQL($sql,$printLog = 'F')
{
	include("conn.php");
			
	 mysqli_query($link,$sql);	
	 $msg = mysqli_error($link);
	 
	 //$printLog='T';
	 if($printLog=='T')
	 {
	 	echo $sql.'<br>';
	 }	
		
	 if($msg == '') return $msg;
	 else
	 {		
  		return 'Execute sql Fail : '.mysqli_errno($link) . ": " . $msg . "\n";
 	}
}

function getSQLResultInfo($result,$col)
{
	if(mysqli_num_rows($result)>0)
	{
		mysqli_data_seek($result,0);		
		$dataRow = mysqli_fetch_assoc($result);	
		//print_r($dataRow);		
        return $dataRow[$col];							
	}
} 

		
// convert sql result to array
function convertToAssocArray($queryResult)
{
	$array = array();
	$i=0;
	if(mysqli_num_rows($queryResult) > 0)
	{
		mysqli_data_seek($queryResult,0);
		while($temp = mysqli_fetch_assoc($queryResult))
		{
			$array[$i]=$temp;
			$i++;
		}	
		return $array;
	}
	return null;
}



function CompareArrayByFilter($source, $filter_array)
{
	$result_array = array();
	foreach ($source as $dataRow) 
	{ 
		$key_match_cnt=0;	 
		
		// compare column values
		reset($filter_array);
		while (list($key,$value) = each($filter_array)) 
		{
			// column exists in array
			if (array_key_exists($key,$dataRow))
			{	  
				// compare value
				if($dataRow[$key] == $value)
				{
					$key_match_cnt++;
				}
				else
				{
					break; // not match search next record
				}
			}
		} 
		// check compare result
		if(($key_match_cnt==count($filter_array)))
		{
			array_push($result_array,$dataRow);
		}
	} 
	return $result_array;
	
}

function SplitString($str,$delimeter = ",")
{	
	$tmpArr = explode($delimeter,$str);
	//print_r($tmpArr );
	//echo '<br><br>';
	$result = array();
	$tmpIndex=0;

	for ($i = 0; $i < count($tmpArr); $i++)
	{	
		if(trim($tmpArr[$i]) != '') // remove empty
		{			
			$result[$tmpIndex] = $tmpArr[$i];
			$tmpIndex++;
		}
	}	
	
	return $result;
	
} 
function getNowTime()
{
   date_default_timezone_set("Asia/Taipei"); 
   $rectime = date("H:i:s"); 
   return $rectime;
}

function getToday()
{
   date_default_timezone_set("Asia/Taipei"); 
   $rectime = date("Y-m-d"); 
   return $rectime;
}

function getNowTimeFordatePicker()
{
   date_default_timezone_set("Asia/Taipei"); 
   $rectime = date("Y-m-d")."T".date("H:i:s"); 
   return $rectime;
}
 
function guid()
{
    if (function_exists('com_create_guid'))
	{
        return com_create_guid();
    }
	else
	{
        mt_srand((double)microtime()*10000);
        $charid = strtoupper(md5(uniqid(rand(), true)));
        
        $uuid = substr($charid, 0, 8)
                .substr($charid, 8, 4)
                .substr($charid,12, 4)
                .substr($charid,16, 4)
                .substr($charid,20,12)
                ;
        return $uuid;
    }
} 
?>

