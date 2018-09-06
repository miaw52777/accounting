<!DOCTYPE HTML>
<?php 
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/OverheadFunc.php"); 
include_once("function/StatisticFunc.php"); 
include_once("function/Mobile_Check.php"); 
include('./secure.php');

// check login
if(!is_login())
{
	header("Location: login.php");
	exit;
} 

/* 參數區 start */
$user_id = $_SESSION['user_id'];
$mode = 'nt';
$month = $_GET['month'];
$slideno = $_GET['slideno'];
$curYear = $_GET['year'];
/* 參數區 end */

/* 參數區 default start */

if($slideno == '') $slideno = '0';
if($month == '') 
{
	$month = new DateTime(str_replace('-','/',getToday()));
	$date = $month; 
}
else $date = new DateTime($month.'/01'); 

$curMonth = $date->format('Y/m');
if($curYear == '') $curYear = $date->format('Y');

/* 參數區 default start */




$menulist = defineMenuList();

if($menulist[$slideno]['title'] == "各月統計")
{
	// initial month information
	$start_time = $date->format('Y-m-01');
	$end_time = $date->format('Y-m-t');
	$time_scale = 'month';
}
else if($menulist[$slideno]['title'] == "各年統計")
{
	// initial year information
	$start_time = $curYear.'/01/01';
	$end_time = $curYear.'/12/31';
	$time_scale = 'year';
}
else if($menulist[$slideno]['title'] == "進階搜尋")
{
	// initial month information
	$start_time = $date->format('Y-m-01');
	$end_time = $date->format('Y-m-t');
	$time_scale = 'month';
}

$result = printStatisticData($time_scale,$mode,$user_id,$start_time,$end_time);
$dataPoints = $result['datapoint'];
$total_income_nt = $result['income'];
$total_outlay_nt = $result['outlay'];


// 取得目前收入/支出/結算
$querySummaryResult = SummaryTotalSettlement($user_id);
$SummarySettlement = getSQLResultInfo($querySummaryResult['DATA'], 'settlement');
$SummaryIncome = getSQLResultInfo($querySummaryResult['DATA'], 'income');
$SummaryOutlay = getSQLResultInfo($querySummaryResult['DATA'], 'outlay');


?>

<html>
	<head>
		<? require_once('./header/title.php');  ?>
		<meta charset="utf-8" /> 
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link rel="stylesheet" href="assets/css/main.css" />					
		<link rel="stylesheet" href="css/scrollmenu.css" />				
		<link href='css/fullcalendar.min.css' rel='stylesheet' />	
		<script src="header/statistic_event.js" type="text/javascript"></script>		
		
		<!-- line chart -->
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>	 
		 <script type="text/javascript">
		  google.charts.load('current', {'packages':['corechart']});
		  google.charts.setOnLoadCallback(drawChart);

			function drawChart() 
			{
				var data = new google.visualization.DataTable();
				data.addColumn({ type: 'string', id: 'date' });
				data.addColumn({ type: 'number', id: 'income', label: '收入' });
				data.addColumn({ type: 'number', id: 'outlay', label: '支出' });
				data.addColumn({ type: 'number', id: 'render', label: '結算' });
				
				data.addRows([
				  <? echo $dataPoints; ?>
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
				data.addColumn({type: 'string', role: 'annotation'});
				var view = new google.visualization.DataView(data);
				view.setColumns([0, 1,
					{ calc: "stringify",
						sourceColumn: 1,
						type: "string",
						role: "none" },2,
					{ calc: "stringify",
						sourceColumn: 2,
						type: "string",
						role: "none" },3,
					{ calc: "stringify",
						sourceColumn: 3,
						type: "string",
						role: "none" }]);

				var chart = new google.visualization.LineChart(document.getElementById('line_chart'));

				chart.draw(view, options);
				$(document).ready(function () 
				{                
					$(".checkbox").change(function() 
					{

						view = new google.visualization.DataView(data);
						var tes =[0];
						
						if($("#income").is(':checked')) {

							tes.push(1,
								{ calc: "stringify",
									sourceColumn: 1,
									type: "string",
									role: "none" });                    
									}
						if($("#outlay").is(':checked'))
						{
							tes.push(2,
								{ calc: "stringify",
									sourceColumn: 2,
									type: "string",
									role: "none" });
						}
						if($("#render").is(':checked'))
						{
							tes.push(3,
								{ calc: "stringify",
									sourceColumn: 3,
									type: "string",
									role: "none"});
						}
						view.setColumns(tes);


						chart.draw(view, options);

					});
				});

			}

		</script>
		<!-- line chart -->
	 	
		
		
	</head>
	
	<body class="is-preload">							
		<? 
			require_once('./header/topHeader.php'); 
			echo printmenuList();
		?> 
		
		
				
		<!-- Heading -->
		<div id="heading" >
			<h1>Statistic</h1>
		</div>		

		<!-- 統計 slideshow menu -->
			<section class="wrapper">
				<div class="inner">
					<header class="special">
						<h2>Show Statistic Chart</h2>						
						<p>
						user : <? echo $user_id; ?><br>
						帳戶總計 : <? echo $SummarySettlement; ?>
						</P>
						
					</header>
					 <? printStatisticMenu($menulist, $slideno); ?>	
			</section>
			
			
			
			<? 
			
				if($menulist[$slideno]['title'] == "各月統計")
				{
					require_once('./statistic_content/statistic_by_month.php');
				}
				else if($menulist[$slideno]['title'] == "各年統計")
				{
					require_once('./statistic_content/statistic_by_year.php');
				}
				else if($menulist[$slideno]['title'] == "進階搜尋")
				{
					require_once('./statistic_content/statistic_search.php');
				}
				
			?>
			
		

			<? 
				require_once('./header/footer.php'); 						
			?>

				 
		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>

<?
// 定義選單
function defineMenuList()
{
	$menulist = array();
	$i=0;
	$menulist[$i]['title'] = "進階搜尋";
	$menulist[$i]['image'] = "image/search.png";
	$menulist[$i]['pageurl'] = "?slideno=".$i;	
	$i++;
	
	$menulist[$i]['title'] = "各月統計";
	$menulist[$i]['image'] = "image/month.png";
	$menulist[$i]['pageurl'] = "?slideno=".$i;	
	
	$i++;
	$menulist[$i]['title'] = "各年統計";
	$menulist[$i]['image'] = "image/year.png";
	$menulist[$i]['pageurl'] = "?slideno=".$i;
	$i++;
	$menulist[$i]['title'] = "項目排名";
	$menulist[$i]['image'] = "image/ranking.png";
	$menulist[$i]['pageurl'] = "?slideno=".$i;
	$i++;
	$menulist[$i]['title'] = "類型比例";
	$menulist[$i]['image'] = "image/piechart.png";
	$menulist[$i]['pageurl'] = "?slideno=".$i;
	return $menulist;	
}

// 印出選單
function printStatisticMenu($menulist, $slideno)
{
	$imageHtml = '';
	$dotHtml = '';
	for($i=0;$i<count($menulist);$i++)
	{
		
		$imageTemplateHtml = '<a href=":PAGE_URL" class=":ACTIVE">
								<img src=":IMAGE_URL" alt=":TITLE" title=":TITLE" width="50"/><br>									
								:TITLE
								</a>	
							'; 
					
		if($slideno == $i) $is_active = "active";
		else $is_active = "";
		
		$sourceStr = array(":TITLE", ":IMAGE_URL",":PAGE_URL", ":ACTIVE");
		$replaceStr   = array($menulist[$i]['title'],$menulist[$i]['image'],$menulist[$i]['pageurl'],$is_active);					
		$imageTemplateHtml = str_replace($sourceStr,$replaceStr,$imageTemplateHtml);
	
		$imageHtml .= $imageTemplateHtml;		
		
	}
	
	  
	$htmlStr = '<div class="scrollmenu">				
					<div style="text-align:center">
					  '.$imageHtml.'
					</div>
				</div>	
				';		
	
	echo $htmlStr;
}


// 印出一個月的 RAW DATA
function printStatisticData($time_scale, $mode,$user_id,$start_time,$end_time)
{	
	$result = array();
	if($time_scale == "month")
	{
		$queryResult = getStatisticByWeek($mode,$user_id,$start_time,$end_time);	
	}
	else if($time_scale == "year")
	{
		$queryResult = getStatisticByMonth($mode,$user_id,$start_time,$end_time);			
	}
	else {return;}
	
	$dataPoints = "";
	$count=0;
	$total_income = 0;
	$total_outlay = 0;
	
	while($temp=mysqli_fetch_assoc($queryResult['DATA']))
	{		
		if($time_scale == "month")
		{
			$date = $temp['statistic_time'];
		}
		else if($time_scale == "year")
		{
			$date = $temp['month'];
		}
		
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
