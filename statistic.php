<!DOCTYPE HTML>
<?php 
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/Mobile_Check.php");
include_once("function/OverheadFunc.php"); 

$user_id = "miaw52777";

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

		<!-- 統計 -->
			<section class="wrapper">
				<div class="inner">
					<header class="special">
						<h2>Show Statistic Chart</h2>
						<p>By Month/Week/Daily</p>
					</header>
					 <div class="highlights">
						<section>
							<div class="content">
								<header>
								 <div class="col-6 col-12-medium">
									<img src="image/week.png" alt="Week" width="50"/>									
									<h3>各週統計</h3>
								</div>									
								</header>								
							</div>
						</section>
						
						<section>
							<div class="content">
									<header>
									 <div class="col-6 col-12-medium">
										<img src="image/month.png" alt="Month" width="50"/>									
										<h3>各月統計</h3>
									</div>									
									</header>								
							</div>
						</section>
						
						<section>
							<div class="content">
									<header>
									 <div class="col-6 col-12-medium">
										<img src="image/year.png" alt="Year" width="50"/>									
										<h3>各年統計</h3>
									</div>									
									</header>								
							</div>
						</section>
						
						<section>
							<div class="content">
									<header>
									 <div class="col-6 col-12-medium">
										<img src="image/ranking.png" alt="項目排名" width="50"/>									
										<h3>項目排名</h3>
									</div>									
									</header>								
							</div>
						</section>
						
						<section>
							<div class="content">
									<header>
									 <div class="col-6 col-12-medium">
										<img src="image/piechart.png" alt="類型比例" width="50"/>									
										<h3>類型比例</h3>
									</div>									
									</header>								
							</div>
						</section>
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