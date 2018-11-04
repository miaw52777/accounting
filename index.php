<!DOCTYPE HTML>
<?php 
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/Mobile_Check.php");
include_once("function/OverheadFunc.php"); 
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
//var_dump($querySummaryResult['SQL']);
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
		<script src="header/home_event.js" type="text/javascript"></script>	
	</head>
	
	<body class="is-preload">

		<? 
			require_once('./header/topHeader.php'); 
			echo printmenuList();
		?> 

		<!-- Banner -->
			<section id="banner">
				<div class="inner">
					<h1>Accounting Home</h1>	
					<p>user : <? echo $user_id; ?></P>			
					<p>帳戶總計 : <? echo $SummarySettlement; ?></p>
				</div>
				<video autoplay loop muted playsinline src="images/banner.mp4"></video>				
			</section>
			<input name="user_id" id="user_id" type="hidden" value="<? echo $user_id; ?>"/>
		<!-- New or Edit -->
			<section class="wrapper">
				<div class="inner">
				<? 				
				
					require_once('./overheadRecordForm.php'); 
					$paramArr = array();
					
					if($_GET['action'] == "updateoverhead")
					{		
						$action = 'UPDATE';		
						$paramArr['GUID'] = $_GET['guid'];		
						$paramArr['TITLE'] = "編輯消費項目";
						
						$rule = getOverheadRecord_Select_Rule('GUID',$paramArr['GUID']);				
						$overhead_record_result = getOverheadRecord($user_id,$rule);
								
						
						if($overhead_record_result['RESULT'])
						{
							$paramArr['OVERHEAD_DATE'] = getSQLResultInfo($overhead_record_result['DATA'],'overhead_date');
							$paramArr['OVERHEAD_TIME'] = getSQLResultInfo($overhead_record_result['DATA'],'overhead_time');
							$paramArr['STATISTIC_TIME'] = getSQLResultInfo($overhead_record_result['DATA'],'statistic_time');
							$paramArr['NT'] = getSQLResultInfo($overhead_record_result['DATA'],'nt');
							$paramArr['PNT'] = getSQLResultInfo($overhead_record_result['DATA'],'pnt');
							$overhead_category = getSQLResultInfo($overhead_record_result['DATA'],'overhead_category');
							if($overhead_category == "支出")
							{
								$paramArr['OVERHEAD_CATEGORY_OUTLAY'] = "SELECTED";
								$paramArr['OVERHEAD_CATEGORY_XFER'] = "";
								$paramArr['OVERHEAD_CATEGORY_INCOME'] = "";
							}
							else if($overhead_category == "轉帳")
							{
								$paramArr['OVERHEAD_CATEGORY_OUTLAY'] = "";
								$paramArr['OVERHEAD_CATEGORY_XFER'] = "SELECTED";
								$paramArr['OVERHEAD_CATEGORY_INCOME'] = "";
							} 
							else
							{
								$paramArr['OVERHEAD_CATEGORY_OUTLAY'] = "";
								$paramArr['OVERHEAD_CATEGORY_XFER'] = "";
								$paramArr['OVERHEAD_CATEGORY_INCOME'] = "SELECTED";
							}
							
							
							if(getSQLResultInfo($overhead_record_result['DATA'],'is_statistic') == "F") $paramArr['IS_STATISTIC'] = "CHECKED";			
							else $paramArr['IS_STATISTIC'] = "";
							
							if(getSQLResultInfo($overhead_record_result['DATA'],'is_necessary') == "T") $paramArr['IS_NECESSARY'] = "CHECKED";			
							else $paramArr['IS_NECESSARY'] = "";
							
							
							$paramArr['MEMO'] = getSQLResultInfo($overhead_record_result['DATA'],'Memo');
							$paramArr['OVERHEAD_METHOD'] = getSQLResultInfo($overhead_record_result['DATA'],'method');
							$paramArr['OVERHEAD_XFER_TO'] = getSQLResultInfo($overhead_record_result['DATA'],'overhead_xfer_to');							
							$paramArr['OVERHEAD_NAME'] = getSQLResultInfo($overhead_record_result['DATA'],'overhead_item');
							$paramArr['OVERHEAD_TYPE'] = getSQLResultInfo($overhead_record_result['DATA'],'overhead_type');		
							$paramArr['USER_ID'] = $user_id;		
							$paramArr['ITEM'] = getSQLResultInfo($overhead_record_result['DATA'],'overhead_item');		
							//var_dump($paramArr);
							echo generateOverheadForm($action, $paramArr);			
						}
						else
						{
							echo 'Error : '.$overhead_record_result['MSG'];
						}		
					}
					else
					{
						$action = 'NEW';		
						$paramArr['TITLE'] = "新增消費項目";
						$paramArr['ITEM'] = "";
						$paramArr['OVERHEAD_DATE'] = getToday();
						$paramArr['OVERHEAD_TIME'] = getNowTime();
						$paramArr['STATISTIC_TIME'] = getToday();
						$paramArr['NT'] = "";
						$paramArr['PNT'] = "";
						$paramArr['OVERHEAD_CATEGORY_OUTLAY'] = "SELECT";
						$paramArr['OVERHEAD_CATEGORY_INCOME'] = "";
						$paramArr['IS_STATISTIC'] = "";
						$paramArr['IS_NECESSARY'] = "";
						$paramArr['MEMO'] = "";
						$paramArr['OVERHEAD_METHOD'] = "";
						$paramArr['OVERHEAD_XFER_TO'] = "";
						$paramArr['OVERHEAD_NAME'] = "";
						$paramArr['OVERHEAD_TYPE'] = "";
						$paramArr['GUID'] = "";
						$paramArr['USER_ID'] = $user_id;		
						echo generateOverheadForm($action, $paramArr);
						
					}
					
		?>
				</div>
			</section>
		<!-- History -->
			<section class="wrapper">
				<div class="inner">
					<header class="special">
						<h2>Show History</h2>	
						 					
									
						<?	
							$overhead_type_radio = $_GET['overhead_type_radio'];	
							if($overhead_type_radio == "") $overhead_type_radio='overall';
							$overhead_type_radioHtml = '
														<div class="col-4 col-12-small">
															<input type="radio" id="overhead_type_radio_p" name="overhead_type_radio" onclick="radioOverheadtypeSelect(\'personal\');" :P_CHECK>
															<label for="overhead_type_radio_p">個人開銷</label>
														
															<input type="radio" id="overhead_type_radio_all" name="overhead_type_radio" onclick="radioOverheadtypeSelect(\'overall\');" :ALL_CHECK>
															<label for="overhead_type_radio_all">全部開銷</label>
														</div>
														';
							if($overhead_type_radio == 'personal')
							{
								$pRadioCheck = 'checked';
								$allRadioCheck = '';
							}
							else 
							{
								$pRadioCheck = '';
								$allRadioCheck = 'checked';
							}	
							
							$sourceStr   = array(":P_CHECK",":ALL_CHECK");
							$replaceStr   = array($pRadioCheck,$allRadioCheck);
			
							$overhead_type_radioHtml = str_replace($sourceStr,$replaceStr,$overhead_type_radioHtml);
							
							//echo $overhead_type_radioHtml; // hide option mode
						?>											
					
				
					</header>
					<div class="testimonials">
						
						<? printOverheadHistory($user_id,$overhead_type_radio); ?>
						
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
function printOverheadHistory($user_id,$overhead_type_radio)
{	
	
	$queryOverheadRecordResult = getOverheadRecord($user_id);	
	
	if($queryOverheadRecordResult["RESULT"])
	{
		// 顯示開銷
		$count = 1;		
		while($temp=mysqli_fetch_assoc($queryOverheadRecordResult['DATA']))
		{		
			
			$getMoneyImage = '<img src="image/get_money.png" alt="收入" />';
			$payMoneyImage = '<img src="image/pay.png" alt="支出" />';
			$xferMoneyImage = '<img src="image/transfer_money.png" alt="轉帳" />';
			
			$overheadTimeText = '- <strong>消費時間</strong> <span>:OVERHEAD_TIME</span>';
			$statisticTimeText = '- <strong>結帳日</strong> <span>:STATISTIC_TIME</span>';
			$htmlTemplate = '<section>
									<div class="content">
										<blockquote>
											<p>:ITEM <b>NT$ :NT </b>:IS_STATISTIC
											<br>
											<span style="font-size:10px;">-:OVERHEAD_METHOD</span><br>
											<span style="font-size:10px;" hidden>-:IS_NECESSARY</span>									
											<span style="font-size:10px;">備註 : :MEMO</span>											
											</p>
											<img src="./image/delete.png" id="img_overhead_delete" alt="刪除" title="刪除" onclick="delOverhead(\':GUID\');" class="right" width="32"> </img>
							  <img src="./image/edit.png" id="img_overhead_edit" alt="編輯" title="編輯" onclick="editOverhead(\':GUID\');" width="30"> </img>
										</blockquote>
										<div class="author">
											<div class="image">
												:METHOD_IMAGE										
											</div>
											<p class="credit">'.$overheadTimeText.'<BR>'.$statisticTimeText.'
											</p>
										</div>
									</div>
								</section>
								';
						
			
			
			if($overhead_type_radio == "personal")
			{
				$nt = $temp['pnt'];
			}
			else
			{
				$nt = $temp['nt'];
			}
			
			if($temp['overhead_category'] == '收入')
			{
				$method_img = $getMoneyImage;
				$xfer_method = "";
			}
			else if($temp['overhead_category'] == '轉帳')
			{
				$method_img = $xferMoneyImage;	
				$xfer_method = "→".$temp['overhead_xfer_to'];
			}
			else
			{
				$method_img = $payMoneyImage;				
				$xfer_method = "";
			}
			
			if($temp['is_necessary'] == 'T') $is_necessary = '必要';
			else $is_necessary = '非必要';
			
			if($temp['is_statistic'] == 'F') $is_statistic = '<img src="./image/non_statistic.png" witdth="15" height="15" alt="不納入統計" title="不納入統計"></image>';
			else $is_statistic = '';
			
			
			$sourceStr = array(":ITEM", ":NT",':OVERHEAD_TIME',':STATISTIC_TIME',':GUID',":METHOD_IMAGE",":OVERHEAD_METHOD",":IS_NECESSARY", ":IS_STATISTIC",":MEMO");
			$replaceStr   = array($temp['overhead_item'],$nt,$temp['rectime'],$temp['statistic_time'],$temp['guid'],$method_img,$temp['method'].$xfer_method, $is_necessary,$is_statistic,$temp['Memo']);
			
			$htmlTemplate = str_replace($sourceStr,$replaceStr,$htmlTemplate);
			
			echo $htmlTemplate;
		
			$count++;
		}
	}
	else
	{
		echo 'Get Overhead Records Error : '. $queryOverheadRecordResult["MSG"];
	}
}



?>