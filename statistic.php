<!DOCTYPE HTML>
<?php 
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/OverheadFunc.php"); 
include_once("function/StatisticFunc.php"); 
include_once("function/Mobile_Check.php"); 
include('./secure.php');

// check login
if( !is_login())
{	
	header("Location: login.php?page=".$_SERVER['REQUEST_URI']);
	exit;
} 

/* 參數區 start */
$user_id = $_SESSION['user_id'];
$mode = 'nt';
$month = $_GET['month'];
$slideno = $_GET['slideno'];
$curYear = $_GET['year'];
$overhead_summary = $_GET['overhead_summary'];
$is_statistic = $_GET['is_statistic'];

/* 參數區 end */

/* 參數區 default start */

if($slideno == '') $slideno = '0';

if(isset($_GET['start_date']))
{
	
	if($_GET['overhead_type_radio_p'] == 'on')
	{		
		$overhead_summary = 'personal';
	}
	else if($_GET['overhead_type_radio_all'] == 'on')
	{	
		$overhead_summary = 'overall';
	}

}
else
{
	if($is_statistic == "") $is_statistic = "T";	
}


if($overhead_summary == 'personal') $mode = "pnt";
else $mode = "nt";
	
// start_time & end_time
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
	if(isset($_GET['start_date']))
	{
		$start_time = $_GET['start_date'];
		$end_time = $_GET['end_date'];		
	}
	else
	{
		$start_time = $date->format('Y-m-01');
		$end_time = $date->format('Y-m-t');
	}
	$time_scale = 'month';
}

$result = printStatisticData($time_scale,$mode,$user_id,$start_time,$end_time,$is_statistic);
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
		
		<form id='StatisticOverheadForm' action="?slide=0#OptionMenu" method="get">
				
		<!-- Heading -->
		<div id="heading" >
			<h1>Statistic</h1>
		</div>		
		
		<input name="slidno" id="slidno" type="hidden" value="<? echo $slideno; ?>"/>
		<input name="curMonth" id="curMonth" type="hidden" value="<? echo $curMonth; ?>"/>
		<input name="curYear" id="curYear" type="hidden" value="<? echo $curYear; ?>"/>
		
		<!-- 統計 slideshow menu -->
			<section class="wrapper">
				<div class="inner">
					<header class="special">
						<h2>Show Statistic Chart</h2>						<div id ="OptionMenu"> </div>
						<p>
						user : <? echo $user_id; ?><br>
						帳戶總計 : <? echo $SummarySettlement; ?>
						</P>
						
					</header>					 
					 
					 <? printStatisticMenu($menulist, $slideno); ?>	
					 
					 <!-- Radio Option : Personal/Overall/Statistic -->
					 <?
							if($overhead_summary == 'personal') 
							{
								$checked_personal = "checked";
								$checked_overall = "";
							}
							else
							{
								$checked_personal = "";
								$checked_overall = "checked";								
							}
					 ?>
					 <div style="text-align:center">
						<div class="row gtr-uniform">
							<div class="col-4 col-12-small">
								<input type="radio" id="overhead_type_radio_p" name="overhead_type_radio_p" onclick="radioOverheadtypeSelect('personal');"  <? echo $checked_personal; ?> >
								<label for="overhead_type_radio_p">個人開銷</label>
								</div>
							<div class="col-4 col-12-xsmall">
								<input type="radio" id="overhead_type_radio_all" name="overhead_type_radio_all" onclick="radioOverheadtypeSelect('overall');" <? echo $checked_overall; ?> >
								<label for="overhead_type_radio_all">全部開銷</label>
							</div>
							<div class="col-4 col-12-xsmall">
								<?
									$check_statistic = "";
									if($is_statistic == "T")
									{
										$check_statistic = "checked";
									}
								?>
								<input type="checkbox" id="is_statistic" name="is_statistic" onclick="statistic_checkbox_click();" <? echo $check_statistic; ?> >
								<label for="is_statistic"><img src="./image/non_statistic.png" witdth="15" height="15" alt="不納入統計" title="不納入統計"></image>濾除不納入統計</label>
							</div>
						</div>							
					</div>
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
			
		</form>

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
	$menulist[$i]['pageurl'] = "?slideno=".$i."#OptionMenu";
	$i++;
	
	$menulist[$i]['title'] = "各月統計";
	$menulist[$i]['image'] = "image/month.png";
	$menulist[$i]['pageurl'] = "?slideno=".$i."#OptionMenu";
	
	$i++;
	$menulist[$i]['title'] = "各年統計";
	$menulist[$i]['image'] = "image/year.png";
	$menulist[$i]['pageurl'] = "?slideno=".$i."#OptionMenu";
	$i++;
	$menulist[$i]['title'] = "項目排名";
	$menulist[$i]['image'] = "image/ranking.png";
	$menulist[$i]['pageurl'] = "?slideno=".$i."#OptionMenu";
	$i++;
	$menulist[$i]['title'] = "類型比例";
	$menulist[$i]['image'] = "image/piechart.png";
	$menulist[$i]['pageurl'] = "?slideno=".$i."#OptionMenu";
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
function printStatisticData($time_scale, $mode,$user_id,$start_time,$end_time,$is_statistic)
{	
	$rule = "";	
	if($is_statistic == "T") $rule .= getOverheadRecord_Select_Rule('IS_STATISTIC','T');
	
	$result = array();
	if($time_scale == "month")
	{
		$queryResult = getStatisticByWeek($mode,$user_id,$start_time,$end_time,$rule);	
	}
	else if($time_scale == "year")
	{
		$queryResult = getStatisticByMonth($mode,$user_id,$start_time,$end_time,$rule);			
	}
	else {return;}
	
	//var_dump($queryResult);
	
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



?>
