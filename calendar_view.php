<?
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/OverheadFunc.php"); 
include_once("function/StatisticFunc.php"); 


$user_id = $_GET['user_id'];
$show_time = $_GET['show_time'];
$start_time = $_GET['start_time'];
$end_time = $_GET['end_time'];
$mode = $_GET['mode'];
if($mode == '') $mode = 'nt';
if($show_time == '') $show_time = $start_time;

$queryResult = getOverheadRawdata($user_id,$start_time,$end_time);

if($queryResult['RESULT'])
{		
	$total_income_nt = 0;
	$total_outlay_nt = 0;
	$total_income_nt_daily = 0;
	$total_outlay_nt_daily = 0;
	
	$dataPoints = array();
	$i=0;
	$tmpday = "";
		

	while($temp=mysqli_fetch_assoc($queryResult['DATA']))
	{		
		$nt = $temp[$mode];
		if(($tmpday == "") || ($tmpday != $temp['statistic_time']))
		{
			/*$dataPoints[$i]['title']='結算'.' NT$'.($total_income_nt_daily-$total_outlay_nt_daily);
			$dataPoints[$i]['start']=$tmpday;
			$dataPoints[$i]['color']="#cc3300";		
			$i++;
			
			$dataPoints[$i]['title']='總收入'.' NT$'.$total_income_nt_daily;
			$dataPoints[$i]['start']=$tmpday;
			$dataPoints[$i]['color']="#004d00";		
			$i++;
			
			$dataPoints[$i]['title']='總支出'.' NT$'.$total_outlay_nt_daily;
			$dataPoints[$i]['start']=$tmpday;
			$dataPoints[$i]['color']="#0099ff";		
			$i++;*/
			
			$tmpday = $temp['statistic_time'];
			$total_income_nt_daily = 0;
			$total_outlay_nt_daily = 0;
		}
			
		if($temp['day'] != $temp['statistic_time'])
		{
			$dataPoints[$i]['title']=$temp['overhead_item'].' NT$'.$temp['nt'].' 消費時間:'.$temp['rectime'];
			$dataPoints[$i]['start']=$temp['statistic_time'].' 12:00:00';			
		}
		else
		{
			$dataPoints[$i]['title']=$temp['overhead_item'].' NT$'.$temp['nt'];
			$dataPoints[$i]['start']=$temp['rectime'];
		}
		if($temp['overhead_category']=='收入')
		{
			$dataPoints[$i]['color']="green";	
			$total_income_nt_daily += $nt;			
			$total_income_nt += $nt;
		}
		else 
		{
			$dataPoints[$i]['color']="";		
			$total_outlay_nt_daily += $nt;
			$total_outlay_nt += $nt;
		}
		
		$i++;
	}	
	/*
	$dataPoints[$i]['title']='結算'.' NT$'.($total_income_nt_daily-$total_outlay_nt_daily);
	$dataPoints[$i]['start']=$tmpday;
	$dataPoints[$i]['color']="#cc3300";		
	$i++;
	
	$dataPoints[$i]['title']='總收入'.' NT$'.$total_income_nt_daily;
	$dataPoints[$i]['start']=$tmpday;
	$dataPoints[$i]['color']="#004d00";		
	$i++;
	
	$dataPoints[$i]['title']='總支出'.' NT$'.$total_outlay_nt_daily;
	$dataPoints[$i]['start']=$tmpday;
	$dataPoints[$i]['color']="#0099ff";		
	$i++;
	*/
	/*
	$dataPoints[$i]['title']='週結算'.' NT$'.($total_outlay_nt-$total_income_nt);
	$dataPoints[$i]['start']=$start_time;
	$dataPoints[$i]['end']=$end_time;
	$dataPoints[$i]['color']="brown";		
	$i++;
	
	$dataPoints[$i]['title']='週收入'.' NT$'.($total_income_nt);
	$dataPoints[$i]['start']=$start_time;
	$dataPoints[$i]['end']=$end_time;
	$dataPoints[$i]['color']="green";		
	$i++;
	
	$dataPoints[$i]['title']='週支出'.' NT$'.($total_outlay_nt);
	$dataPoints[$i]['start']=$start_time;
	$dataPoints[$i]['end']=$end_time;
	$dataPoints[$i]['color']="";		
	$i++;*/
	
	
	
//	var_dump($dataPoints);
}
else
{
	echo 'Error : '.$queryResult['MSG'];
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<link href='css/fullcalendar.min.css' rel='stylesheet' />
<link href='css/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<script src='header/moment.min.js'></script>
<script src="assets/js/jquery.min.js"></script>
<script src='header/fullcalendar.min.js'></script>
<script>
  $(document).ready(function() {

    $('#calendar').fullCalendar({
      defaultDate: '<? echo $show_time; ?>',
      editable: false,
      eventLimit: true, 
	  navLinks: true, 
	  header: {
        left: 'prev,next today',
		center: 'title',
		right: 'month,listMonth'
      },	  
      events: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
    });

  });

</script>
<link rel="stylesheet" href="css/calendar.css" />	
</head>
<body>
  <div id='calendar'></div>
</body>
</html>
