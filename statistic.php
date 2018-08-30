<!DOCTYPE HTML>
<?php 
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/OverheadFunc.php"); 
include_once("function/StatisticFunc.php"); 
include_once("function/Mobile_Check.php"); 

$user_id = "miaw52777";
$mode = 'nt';
$month = $_GET['month'];

if($month == '') 
{
	$month = new DateTime(str_replace('-','/',getToday()));
	$date = $month; 
}
else $date = new DateTime($month.'/01'); 

$curMonth = $date->format('Y/m');


$slideno = $_GET['slideno'];
if($slideno == '') $slideno = '1';

$curYear = $_GET['year'];
if($curYear == '') $curYear = $date->format('Y');

// initial month information
$start_time = $date->format('Y-m-01');
$end_time = $date->format('Y-m-t');

// initial year information
$start_time_Year = $curYear.'/01/01';
$end_time_Year = $curYear.'/12/31';

/*
echo date("Y-m-d",strtotime("-1 month"));
echo '<br>';
echo $date->format('Y-m').'<br>';
echo $date->format('Y-m-01').'<br>'; // first of month
echo $date->format('Y-m-t').'<br>'; // end of month
*/

// get month raw data
$result = printStatisticMonthData($mode,$user_id,$start_time,$end_time);
$dataPoints = $result['datapoint'];
$total_income_nt = $result['income'];
$total_outlay_nt = $result['outlay'];

// get year raw data
$resultYear = printStatisticYearData($mode,$user_id,$start_time_Year,$end_time_Year);
$dataPointsYear = $resultYear['datapoint'];
$total_income_nt_Year = $resultYear['income'];
$total_outlay_nt_Year = $resultYear['outlay'];


?>

<html>
	<head>
		<? require_once('./header/title.php');  ?>
		<meta charset="utf-8" /> 
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="stylesheet" href="css/slideshow.css" />				
		<link href='css/fullcalendar.min.css' rel='stylesheet' />	
		<script src="header/statistic_event.js" type="text/javascript"></script>		
		
		<!-- line chart -->
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>	 
		 <script type="text/javascript">
		  google.charts.load('current', {'packages':['corechart']});
		  google.charts.setOnLoadCallback(drawChart);

		  function drawChart() {
			var dataMonth = new google.visualization.DataTable();
			dataMonth.addColumn({ type: 'string', id: 'date' });
			dataMonth.addColumn({ type: 'number', id: 'income', label: '收入' });
			dataMonth.addColumn({ type: 'number', id: 'outlay', label: '支出' });
			dataMonth.addColumn({ type: 'number', id: 'render', label: '結算' });
			
			dataMonth.addRows([
			  <? echo $dataPoints; ?>
			  ]);
			  
			var dataYear = new google.visualization.DataTable();
			dataYear.addColumn({ type: 'string', id: 'date' });
			dataYear.addColumn({ type: 'number', id: 'income', label: '收入' });
			dataYear.addColumn({ type: 'number', id: 'outlay', label: '支出' });
			dataYear.addColumn({ type: 'number', id: 'render', label: '結算' });
			
			dataYear.addRows([
			  <? echo $dataPointsYear; ?>
			  ]);  


			var options = {
			  title: '',
			  curveType: 'none',
			  titleTextStyle: {		  
				fontName: 'Arial',
				fontSize: 30			
			  },
			  gend: { position: 'none' },
			  pointSize: 15,
			  series: {
				0: { pointShape: 'square', color:'green' },
				1: { pointShape: 'square', color:'blue' },
				2: { pointShape: 'square', color:'brown' }
			  }
			};

			var chartMonth = new google.visualization.LineChart(document.getElementById('line_chart_month'));
			chartMonth.draw(dataMonth, options);
			
			var chartYear = new google.visualization.LineChart(document.getElementById('line_chart_year'));
			chartYear.draw(dataYear, options);
			
		  }
		</script>
		<!-- line chart -->
		
		<script type="text/javascript">					
			var slideIndex = <? echo $slideno; ?>;			
		</script>
		
		
		
	</head>
	
	<body class="is-preload">							
		<? 
			require_once('./header/topHeader.php'); 
			echo printmenuList();
		?> 
				
		<!-- Heading -->
		<div id="heading" >
			<h1></h1>
		</div>		

		<!-- 統計 slideshow menu -->
			<section class="wrapper">
				<div class="inner">
					<header class="special">
						<h2>Show Statistic Chart</h2>						
					</header>
					 <? printStatisticMenu(); ?>
			</section>
			
			<section id="main" class="wrapper">
			<? 
				require_once('./header/statistic_by_month.php');
				require_once('./header/statistic_by_year.php');
				
			?>
			</section>
		

			<? 
				require_once('./header/footer.php'); 						
			?>

				 
		<!-- Scripts -->										
			<script src="header/slideshow.js" type="text/javascript"></script>			
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>

<?
function printStatisticMenu()
{
	$menulist = array();
	$i=0;
	/*$menulist[$i]['title'] = "各週統計";
	$menulist[$i]['image'] = "image/week.png";
	$i++;*/
	$menulist[$i]['title'] = "各月統計";
	$menulist[$i]['image'] = "image/month.png";
	$i++;
	$menulist[$i]['title'] = "各年統計";
	$menulist[$i]['image'] = "image/year.png";
	$i++;
	$menulist[$i]['title'] = "項目排名";
	$menulist[$i]['image'] = "image/ranking.png";
	$i++;
	$menulist[$i]['title'] = "類型比例";
	$menulist[$i]['image'] = "image/piechart.png";
	
	$imageHtml = '';
	$dotHtml = '';
	for($i=0;$i<count($menulist);$i++)
	{
		
		$imageTemplateHtml = '<div class="mySlides fade" align="center">								   								 
						<img src=":IMAGE_URL" alt=":TITLE" title=":TITLE" width="50"/>									
						<h3>:TITLE</h3>			
				    </div>'; 
		$dotTemplateHtml = '<span class="dot" onclick="currentSlide('.($i+1).')"></span> '; 				
		
		$sourceStr = array(":TITLE", ":IMAGE_URL");
		$replaceStr   = array($menulist[$i]['title'],$menulist[$i]['image']);					
		$imageTemplateHtml = str_replace($sourceStr,$replaceStr,$imageTemplateHtml);
	
		$imageHtml .= $imageTemplateHtml;
		$dotHtml .= $dotTemplateHtml;		
	}
	
	
	$htmlStr = '<div class="slideshow-container">
				
				'.$imageHtml.'
				
				<!-- Next and previous buttons -->
				  <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
				  <a class="next" onclick="plusSlides(1)">&#10095;</a>
				 </div>
				 
				 <!-- The dots/circles -->
					<div style="text-align:center">
					  '.$dotHtml.'
					</div>
				';		
	
	echo $htmlStr;
}

function printStatisticMonthData($mode,$user_id,$start_time,$end_time)
{	
	$result = array();
	$queryResult = getStatisticByWeek($mode,$user_id,$start_time,$end_time);	
	
	$dataPoints = "";
	$count=0;
	$total_income = 0;
	$total_outlay = 0;
	while($temp=mysqli_fetch_assoc($queryResult['DATA']))
	{		
		$date = $temp['statistic_time'];
		$nt_income = $temp['nt_income'];
		$nt_outlay = $temp['nt_outlay'];
		
		$total_income += $nt_income;
		$total_outlay += $nt_outlay;
		
		if($count==0)
		{
			$dataPoints .= '';	
		}
		else
		{
			$dataPoints .= ',';	
		}
		$dataPoints .= '[ ("'.$date.'"), '.$nt_income.', '.$nt_outlay.', '.($nt_income-$nt_outlay).']';
		$count++;
	}
	$result['datapoint'] = $dataPoints;
	$result['income'] = $total_income;
	$result['outlay'] = $total_outlay;
	
	return $result;
}

function printStatisticYearData($mode,$user_id,$start_time,$end_time)
{	
	$result = array();
	$queryResult = getStatisticByMonth($mode,$user_id,$start_time,$end_time);	
	
	$dataPoints = "";
	$count=0;
	$total_income = 0;
	$total_outlay = 0;
	
	while($temp=mysqli_fetch_assoc($queryResult['DATA']))
	{		
		$date = $temp['month'];
		$nt_income = $temp['nt_income'];
		$nt_outlay = $temp['nt_outlay'];
		
		$total_income += $nt_income;
		$total_outlay += $nt_outlay;
		
		if($count==0)
		{
			$dataPoints .= '';	
		}
		else
		{
			$dataPoints .= ',';	
		}
		$dataPoints .= '[ ("'.$date.'"), '.$nt_income.', '.$nt_outlay.', '.($nt_income-$nt_outlay).']';
		$count++;
	}
	$result['datapoint'] = $dataPoints;
	$result['income'] = $total_income;
	$result['outlay'] = $total_outlay;
	
	return $result;
}



function printDailyOverheadData($user_id,$start_time,$end_time)
{
	$queryResult = getOverheadRawdata($user_id,$start_time,$end_time);
	
	if($queryResult['RESULT'])
	{		
		$total_income_nt = 0;
		$total_outlay_nt = 0;
		$total_income_nt_daily = 0;
		$total_outlay_nt_daily = 0;
		$tmpday="";
		$htmlResult = "";
				
		
		while($temp=mysqli_fetch_assoc($queryResult['DATA']))
		{		
			$nt = $temp['nt'];
			if($temp['overhead_category'] == "收入") $total_income_nt += $nt;
			if($temp['overhead_category'] == "支出") $total_outlay_nt += $nt;
			
			if(($tmpday == "") || ($tmpday != $temp['statistic_time']))
			{
				$sourceStr = array(":DATE", ":TOTAL_OUTLAY_NT",":RECORD",":TOTAL_INCOME_NT",":TOTAL_SUM_NT");
				$replaceStr   = array($tmpday,$total_outlay_nt_daily,$recordHtml,$total_income_nt_daily,$total_income_nt_daily-$total_outlay_nt_daily);
				$html = str_replace($sourceStr,$replaceStr,$html);	
				$htmlResult .= $html;
			
				$html = "<div class=\"table-wrapper\">
						<b>:DATE</b>
						<table>
							<thead>
								<tr>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								:RECORD					
							</tbody>
							<tfoot>
								<tr>
									<td colspan=\"2\"><font color=\"green\">收入 : NT$:TOTAL_INCOME_NT</font></td>
									<td><font color=\"red\">支出 : NT$:TOTAL_OUTLAY_NT</font><BR>
										<font color=\"blue\">結算 : NT$:TOTAL_SUM_NT</font>
									</td>
									
								</tr>
							</tfoot>
						</table>
					</div>
					";
				$recordHtml = '';
				$total_income_nt_daily = 0;
				$total_outlay_nt_daily = 0;
				$tmpday = $temp['statistic_time'];
			}
			
			if($temp['overhead_category'] == "收入")
			{
				$total_income_nt_daily += $nt;
				$color = "green";
			}
			if($temp['overhead_category'] == "支出") 
			{
				$total_outlay_nt_daily += $nt;
				$color = "red";
			}
				
			$recordTmpHtml = "<tr>
								<td><font color=\":COLOR\">:OVERHEAD_CATEGORY</font></td>
								<td>:OVERHEAD_ITEM</td>
								<td>:NT</td>
							</tr>
						   ";				
			
			$sourceStr = array(":OVERHEAD_CATEGORY", ":OVERHEAD_ITEM",":NT",":COLOR");
			$replaceStr   = array($temp['overhead_category'],$temp['overhead_item'],$nt,$color);
			$recordTmpHtml = str_replace($sourceStr,$replaceStr,$recordTmpHtml);
			$recordHtml .= $recordTmpHtml;			
			
		}
		
		$sourceStr = array(":DATE", ":TOTAL_OUTLAY_NT",":RECORD",":TOTAL_INCOME_NT",":TOTAL_SUM_NT");
		$replaceStr = array($tmpday,$total_outlay_nt_daily,$recordHtml,$total_income_nt_daily,$total_income_nt_daily-$total_outlay_nt_daily);
		$html = str_replace($sourceStr,$replaceStr,$html);	
		$htmlResult .= $html;
		
		
		echo "<div class=\"table-wrapper\">						
						<table>
							<thead>
								<tr>
									<th>總支出</th>
									<th>總收入</th>
									<th>結算</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><font color=\"green\">".$total_income_nt."</font></td>
									<td><font color=\"red\">".$total_outlay_nt."</font></td>
									<td><font color=\"blue\">".($total_income_nt-$total_outlay_nt)."</font></td>
								</tr>				
							</tbody>									
						</table>
					</div>";
		echo $htmlResult;						
	}
	else
	{
		echo 'Error : '.$queryResult['MSG'];
	}

}

?>
