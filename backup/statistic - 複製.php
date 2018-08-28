<!DOCTYPE HTML>
<?php 
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/OverheadFunc.php"); 
include_once("function/StatisticFunc.php"); 

$user_id = "miaw52777";

$slideno = $_GET['slideno'];
if($slideno == '') $slideno = 1;
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
		<link rel="stylesheet" href="css/calendar.css" />	
		<!-- Calendar start -->	
		<link href='css/fullcalendar.min.css' rel='stylesheet' />
		<link href='css/fullcalendar.print.min.css' rel='stylesheet' media='print' />
		<script src='header/moment.min.js'></script>		
		<script src='header/fullcalendar.min.js'></script>
		<!-- Calendar end -->	
		<script>
  $(document).ready(function() {
 alert("X");
    $('#calendar').fullCalendar({
      defaultDate: '2018-03-12',
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      events: [
        {
          title: 'All Day Event',
          start: '2018-03-01'
        },
        {
          title: 'Long Event',
          start: '2018-03-07',
          end: '2018-03-10'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2018-03-09T16:00:00'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2018-03-16T16:00:00'
        },
        {
          title: 'Conference',
          start: '2018-03-11',
          end: '2018-03-13'
        },
        {
          title: 'Meeting',
          start: '2018-03-12T10:30:00',
          end: '2018-03-12T12:30:00'
        },
        {
          title: 'Lunch',
          start: '2018-03-12T12:00:00'
        },
        {
          title: 'Meeting',
          start: '2018-03-12T14:30:00'
        },
        {
          title: 'Happy Hour',
          start: '2018-03-12T17:30:00'
        },
        {
          title: 'Dinner',
          start: '2018-03-12T20:00:00'
        },
        {
          title: 'Birthday Party',
          start: '2018-03-13T07:00:00'
        },
        {
          title: 'Click for Google',
          url: 'http://google.com/',
          start: '2018-03-28'
        }
      ]
    });
 alert("X");
 });
</script>
	</head>
	
	<body class="is-preload">
<div id='calendar'></div>
							
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
				<div class="inner">
					<div class="content">
					
					
					
					
						
						<? 
							$start_time = '2018/08/15';
							$end_time = '2018/08/25'; 
							
							printStatisticData($user_id,$start_time,$end_time); 
						?>			
					</div>
				</div>
			</section>
			<? 
				require_once('./header/footer.php'); 						
			?>

				 
		<!-- Scripts -->							
			<script src="header/statistic_event.js" type="text/javascript"></script>			
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
	$menulist[0]['title'] = "各週統計";
	$menulist[0]['image'] = "image/week.png";
	$menulist[1]['title'] = "各月統計";
	$menulist[1]['image'] = "image/month.png";
	$menulist[2]['title'] = "各年統計";
	$menulist[2]['image'] = "image/year.png";
	$menulist[3]['title'] = "項目排名";
	$menulist[3]['image'] = "image/ranking.png";
	$menulist[4]['title'] = "類型比例";
	$menulist[4]['image'] = "image/piechart.png";
	
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

function printStatisticData($user_id,$start_time,$end_time)
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