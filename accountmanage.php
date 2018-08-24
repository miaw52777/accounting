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
				

		<!-- History -->
			<section class="wrapper">
				<div class="inner">
					<header class="special">
						<h2>Show History</h2>	
						 					
						
					</header>
					<div class="testimonials">
						 
						
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