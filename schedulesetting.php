<!DOCTYPE HTML>
<?php 
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/Mobile_Check.php");
include_once("function/OverheadFunc.php"); 
include('./secure.php');

// check login
if(!is_login())
{
	header("Location: login.php");
	exit;
} 

$user_id = $_SESSION['user_id'];

?>

<html>
	<head>
		<? require_once('./header/title.php');  ?>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link rel="stylesheet" href="assets/css/main.css" />			
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
	
		<!-- History -->
			<section id="main" class="wrapper">
				<div class="inner">
					<div class="content">
						<? 
							require_once('schduleEditForm.php'); 
							$paramArr = array();
			
					if($_GET['action'] == "updateoverhead")
					{		
						$action = 'UPDATE';		
						$paramArr['GUID'] = $_GET['guid'];		
						$paramArr['TITLE'] = "編輯排程";
						
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
								$paramArr['OVERHEAD_CATEGORY_INCOME'] = "";
							}
							else
							{
								$paramArr['OVERHEAD_CATEGORY_OUTLAY'] = "";
								$paramArr['OVERHEAD_CATEGORY_INCOME'] = "SELECTED";
							}
							if(getSQLResultInfo($overhead_record_result['DATA'],'is_statistic') == "F") $paramArr['IS_STATISTIC'] = "CHECKED";			
							else $paramArr['IS_STATISTIC'] = "";
							
							if(getSQLResultInfo($overhead_record_result['DATA'],'is_necessary') == "T") $paramArr['IS_NECESSARY'] = "CHECKED";			
							else $paramArr['IS_NECESSARY'] = "";
							
							
							$paramArr['MEMO'] = getSQLResultInfo($overhead_record_result['DATA'],'Memo');
							$paramArr['OVERHEAD_METHOD'] = getSQLResultInfo($overhead_record_result['DATA'],'method');
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
						$paramArr['TITLE'] = "新增排程";
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
						$paramArr['OVERHEAD_NAME'] = "";
						$paramArr['OVERHEAD_TYPE'] = "";
						$paramArr['GUID'] = "";
						$paramArr['USER_ID'] = $user_id;		
						echo generateOverheadForm($action, $paramArr);
						
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