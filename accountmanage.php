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
$SummarySettlement = getSQLResultInfo($querySummaryResult['DATA'], 'settlement');

$overhead_type = $_GET['overhead_type'];
$overhead_type = 'overall'; // lock mode
if($overhead_type == 'personal')
{
	$checked_personal = 'checked';
	$checked_overall = '';
	$mode = 'pnt';
}
else 
{
	$checked_personal = '';
	$checked_overall = 'checked';
	$mode = 'nt';
}

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
		<script src="header/account_manage_event.js" type="text/javascript"></script>	
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
						<h2>帳戶列表</h2>		
						<p>user : <? echo $user_id; ?></P>			
						<p>帳戶總計 : <? echo $SummarySettlement; ?></p>		
					</header>						
				</div>
				
				<!-- 帳戶列表 -->
				<div class="inner" style="background-color:white">
					<div class="content">	
							<!-- Option 不顯示 
							<div class="row" style="text-align:center">
								<div class="col-6 col-12-small">
									<input type="radio" id="overhead_type_radio_p" name="overhead_type_radio_p" onclick="radioOverheadtypeSelect('personal');"  <? echo $checked_personal; ?> >
									<label for="overhead_type_radio_p">個人開銷</label>
								</div>
								<div class="col-6 col-12-xsmall">
									<input type="radio" id="overhead_type_radio_all" name="overhead_type_radio_all" onclick="radioOverheadtypeSelect('overall');" <? echo $checked_overall; ?> >
									<label for="overhead_type_radio_all">全部開銷</label>
								</div>
							</div>
							-->
							 
							<input type="text" id="tableInput" onkeyup="table_search_bar('accountTable')" placeholder="Search for names.." title="Type in a name">
							 
						 <br>
							<table id="accountTable">
							  <tr class="header">								
								<th>名稱</th>	
								<th>Total 金額</th>
								<th>Total 金額(含不統計)</th>
								<th>編輯動作</th>
							  </tr>
							  
							  
							  <?
								$accout_list = getOverhead_Account($user_id,$mode);
								$total_nt = 0;
								$total_nt_non_statistic = 0;
								$total_nt_last_month = 0;
								while($temp=mysqli_fetch_assoc($accout_list['DATA']))
								{		
									$templateTable = '<tr>
														<td>:IMAGE
															:NAME
														</td>
														<td>:NT</td>
														<td>:INCLUDE_NON_STATISTIC_NT</td>
														<td>
															
															<img src="./image/delete.png" id="img_delete" alt="刪除" title="刪除" onclick="deleteAccount(\':ACCOUNT_ID\');" width="32"> </img>
														
															<a href="?guid=:ACCOUNT_ID&page='.base64_encode($_SERVER['REQUEST_URI']).'"> 
															<img src="./image/edit.png" id="img_edit" alt="編輯" title="編輯" width="30"> </img>
															</a>
															
															<a href="accountmanage_detail.php?name=:NAME&mode=:MODE"> 
															<img src="./image/information.png" id="img_edit" alt="詳細資料" title="詳細資料" width="30"> </img>
															</a>
														</td>	
													  </tr>
													  ';
									
									if($temp['type'] == '信用卡')				  
									{
										$image = '<img src="./image/credit_card.png" witdth="18" height="18" alt="'.$temp['type'].'" title="'.$temp['type'].'"></image>';
									}
									else if($temp['type'] == '銀行')				  
									{
										$image = '<img src="./image/bank.png" witdth="18" height="18" alt="'.$temp['type'].'" title="'.$temp['type'].'"></image>';
									}
									else if($temp['type'] == '郵局')				  
									{
										$image = '<img src="./image/post.png" witdth="18" height="18" alt="'.$temp['type'].'" title="'.$temp['type'].'"></image>';
									}
									else 
									{
										$image = '<img src="./image/cash.png" witdth="18" height="18" alt="'.$temp['type'].'" title="'.$temp['type'].'"></image>';
									}
													  
									
									$nt = $temp['nt'] + $temp['nt_overhead_non_statistic']; // 帳戶起始金額 + 消費金額									
									$total_nt += $nt;
									$total_nt_last_month += $temp['nt_overhead_last_month'];
									
									$nt_non_statistic =  $temp['nt'] + $temp['nt_overhead']; // 帳戶起始金額 + 消費金額(含不列入統計)					
									$total_nt_non_statistic += $nt_non_statistic;
									
									if($nt_non_statistic <> $nt)
									{
										$nt_non_statistic_str = '<font color="blue">'.$nt_non_statistic.'</font>';
									}
									else $nt_non_statistic_str = $nt_non_statistic;
									
									$sourceStr = array(":NAME", ":IMAGE",":NT", ":MONTH_NT", ":ACCOUNT_ID",":MODE", ":INCLUDE_NON_STATISTIC_NT");
									$replaceStr   = array($temp['name'],$image,$nt,$temp['nt_overhead_last_month'],$temp['account_id'],$mode, $nt_non_statistic_str);					
									$templateTable = str_replace($sourceStr,$replaceStr,$templateTable);		
									echo $templateTable;		
								}
							
							 ?>
							  
							  <tfoot>
								<tr>
									<td>Total</td>
									<td><? echo $total_nt; ?></td>
									<td><? echo $total_nt_non_statistic; ?></td>
									<td></td>															
								</tr>
							</tfoot>
							
							</table>
						 
					</div>
				</div>
				<!-- 帳戶列表 End-->
				
			
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