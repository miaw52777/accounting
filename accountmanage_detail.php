<!DOCTYPE HTML>
<?php 
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/Mobile_Check.php");
include_once("function/OverheadFunc.php"); 
include_once("function/StatisticFunc.php"); 
include('./secure.php');

// check login
if( !is_login())
{	
	header("Location: login.php?page=".$_SERVER['REQUEST_URI']);
	exit;
} 

$user_id = $_SESSION['user_id'];

// 取得目前收入/支出/結算
$querySummaryResult = SummaryTotalSettlement($user_id);
$SummarySettlement = getSQLResultInfo($querySummaryResult['DATA'], 'settlement');

$method = $_GET['name'];
if($method == '') $method = '現金';
$mode = $_GET['mode'];
if($mode == "") $mode= 'nt';
?>

<html>
	<head>
		<? require_once('./header/title.php');  ?>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link rel="stylesheet" href="assets/css/main.css" />	
		<script src="header/table_search.js" type="text/javascript"></script>			
		<script src="header/home_event.js" type="text/javascript"></script>	
	</head>
	
	<body class="is-preload">

							
		<? 
			require_once('./header/topHeader.php'); 
			echo printmenuList();
		?> 
		<!-- Banner -->
		<section id="heading">
			<div class="inner">
				<h1>帳戶管理</h1>					
			</div>
			
		</section>
 
		<!-- History -->
			<section class="wrapper">
				<div class="inner">
					<header class="special">
						<h2><? echo $method;?></h2>		
						<p>user : <? echo $user_id; ?></P>			
						<p>帳戶總計 : <? echo $SummarySettlement; ?></p>		
					</header>						
				</div>
			</section>

			<!-- 帳戶列表 -->
			<section id="main" class="wrapper" name="main">
				<div class="inner">
					<div class="content">								
							
							<a href="accountmanage.php"> <button type="button" class="primary" >回帳戶列表</button>	</a>
							 <br><br>
							 
							  
							  
							  <? 
								$rule_overhead_record = ' and (method = "'.$method.'" or (overhead_category = "轉帳" and overhead_xfer_to = "'.$method.'") )';
								$queryResult = getOverheadRawdataNoTime($user_id,$rule_overhead_record);
							//var_dump($queryResult['SQL']);

							/*********** Start to print search data **********************/
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
									$nt = $temp[$mode];
									if($temp['overhead_category'] == "收入") $total_income_nt += $nt;
									if($temp['overhead_category'] == "支出") $total_outlay_nt += $nt;
									if($temp['overhead_category'] == "轉帳")
									{
										if($method == $temp['method'])
										{
											$total_income_nt -= $nt; /*轉出*/
										}
										else
										{
											$total_income_nt += $nt; /*轉入*/
										}																				
									}
									
									if(($tmpday == "") || ($tmpday != $temp['statistic_time']))
									{
										$sourceStr = array(":DATE", ":TOTAL_OUTLAY_NT",":RECORD",":TOTAL_INCOME_NT",":TOTAL_SUM_NT");
										$replaceStr   = array($tmpday,$total_outlay_nt_daily,$recordHtml,$total_income_nt_daily,$total_income_nt_daily-$total_outlay_nt_daily);
										$html = str_replace($sourceStr,$replaceStr,$html);	
										$htmlResult .= $html;
									
										$html = "<div class=\"table-wrapper\">
												<b>:DATE</b>
												<table id=\"accountTable\">
													<thead>
														<tr>
															<th></th>
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
															<td><font color=\"blue\">支出 : NT$:TOTAL_OUTLAY_NT</font><BR>
																<font color=\"brown\">結算 : NT$:TOTAL_SUM_NT</font>
															</td>
															<td></td>
															
															
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
										$color = "blue";
									}
									if($temp['overhead_category'] == "轉帳") 
									{
										if($method == $temp['method'])
										{
											$total_outlay_nt_daily -= $nt; /*轉出*/
											$temp['overhead_category'] = '轉出';
										}
										else
										{
											$total_outlay_nt_daily += $nt; /*轉入*/
											$temp['overhead_category'] = '轉入';
										}											
										$color = "purple";
									}
										
									$recordTmpHtml = '<tr>
														<td><font color=":COLOR">:OVERHEAD_CATEGORY</font></td>
														<td>:OVERHEAD_ITEM</td>
														<td>:NT</td>				
														<td>
														<img src="./image/delete.png" id="img_overhead_delete" alt="刪除" title="刪除" onclick="delOverhead(\':GUID\');" width="32"> </img>
														
														<a href="showEditOVerheadForm.php?guid=:GUID&page='.base64_encode($_SERVER['REQUEST_URI'].'#OptionMenu').'"> <img src="./image/edit.png" id="img_overhead_edit" alt="編輯" title="編輯" onclick="showOverhead(\':GUID\');" width="30"> </img></a>
														</td>
													</tr>
												   ';		

 												
									
									if($temp['is_statistic'] == 'F') $item = $temp['overhead_item'].' <img src="./image/non_statistic.png" witdth="15" height="15" alt="不納入統計" title="不納入統計"></image>'; 
									else $item = $temp['overhead_item'];
									
									$sourceStr = array(":OVERHEAD_CATEGORY", ":OVERHEAD_ITEM",":NT",":COLOR",":GUID");
									$replaceStr   = array($temp['overhead_category'],$item,$nt,$color,$temp['guid']);
									$recordTmpHtml = str_replace($sourceStr,$replaceStr,$recordTmpHtml);
									$recordHtml .= $recordTmpHtml;			
									
								}
								
								$sourceStr = array(":DATE", ":TOTAL_OUTLAY_NT",":RECORD",":TOTAL_INCOME_NT",":TOTAL_SUM_NT");
								$replaceStr = array($tmpday,$total_outlay_nt_daily,$recordHtml,$total_income_nt_daily,$total_income_nt_daily-$total_outlay_nt_daily);
								$html = str_replace($sourceStr,$replaceStr,$html);	
								$htmlResult .= $html;
								
								
								/*echo "<div class=\"table-wrapper\">						
												<table>
													<thead>
														<tr>
															<th>總收入</th>
															<th>總支出</th>
															<th>結算</th>
															<th></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td><font color=\"green\">".$total_income_nt."</font></td>
															<td><font color=\"blue\">".$total_outlay_nt."</font></td>
															<td><font color=\"brown\">".($total_income_nt-$total_outlay_nt)."</font></td>
															<td></td>
														</tr>				
													</tbody>									
												</table>
											</div>";*/
								echo $htmlResult;						
							}
							else
							{
								echo 'Error : '.$queryResult['MSG'];
							}
							
								
						      ?>
						 
					</div>
				</div>
			</section>

			
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

?>