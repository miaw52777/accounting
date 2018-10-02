<!DOCTYPE HTML>
<?php 
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/Mobile_Check.php");
include_once("function/OverheadFunc.php"); 
include('./secure.php');

 

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
				

		<!-- History -->
			<section class="wrapper">
				<div class="inner">
					<header class="special">
						<h2>Show History</h2>	
						 					
						
					</header>
					<div class="testimonials">
		  
		  
		  
		 <div class="container">
  <h1>Testing - Editable Drop Down</h1>
  <form>

    <h3>New test</h3>
    <div class="row">
      <div class="col-sm-3">
        <div class="input-group dropdown">
          <input type="text" class="form-control countrycode dropdown-toggle" value="(+47)">
          <ul class="dropdown-menu">
            <li><a href="#" data-value="+47">Norway (+47)</a></li>
            <li><a href="#" data-value="+1">USA (+1)</a></li>
            <li><a href="#" data-value="+55">Japan (+55)</a></li>
          </ul>
          <span role="button" class="input-group-addon dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></span>
        </div>
      </div>
      <div class="col-sm-9">
        <input type="txt" class="form-control" value="23456789" id="phone1">
      </div>
    </div>
  </form>
</div>
		  
		  
		  
		  
		  
		  
		  
					
					</div>
				</div>
			</section>
			
			
			
			
			
			
			
			



</span>
			
			
			
			
			
			
			
			
			
			
			

			<? 
				require_once('./header/footer.php'); 						
			?>

				 
		<!-- Scripts -->		
			
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>
  <script>
	$(function() {
  $('.dropdown-menu a').click(function() {
    console.log($(this).attr('data-value'));
    $(this).closest('.dropdown').find('input.countrycode')
      .val('(' + $(this).attr('data-value') + ')');
  });
});
</script>

	</body>
</html>

<?

?>