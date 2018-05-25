<?php
require "./basic_db.php";
$function = new Database( 'local' );
$function::$dbId = 'local';
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<title>A Collection of Page Transitions</title>
	<meta name="description" content="A Collection of Page Transitions with CSS Animations" />
	<meta name="keywords" content="page transition, css animation, website, effect, css3, jquery" />
	<meta name="author" content="Codrops" />
	<link rel="shortcut icon" href="../favicon.ico"> 
	<link rel="stylesheet" type="text/css" href="css/default.css" />
	<link rel="stylesheet" type="text/css" href="css/multilevelmenu.css" />
	<link rel="stylesheet" type="text/css" href="css/component.css" />
	<link rel="stylesheet" type="text/css" href="css/animations.css" />
	<script src="js/modernizr.custom.js"></script>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">NORTHERN DATA - ABOUT US</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="mobile_home.php">Home</a></li>
				<li class="active"><a href="#">About Us</a></li>
				<li><a href="mobile_services.php">Services</a></li>
				<li><a href="mobile_contact.php">Contact Us</a></li>
				<li><a href="mobile_form.php">Client Feedback</a></li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
<table width="100%">
	<tr align="center">
		<td>
			<br />
			Northern Data founded in 2014 as a data management consultancy on the principle that every organisation deserves the best in managing their data.
			<br /><br />
			We assist organizations of all sizes to effectively manage, distribute and secure their data with key focus on providing value add, knowledge management and timeous insights into their business. Northern Data is 100% Black Owned and LEVEL 1 B-BBEE. Northern Data is a Microsoft Gold Partner for Data Analytics.
			<?php
				// $PageName = 'AboutUs';
				// echo "<br >";
				// foreach ( $function->getPageInfo($PageName) as $content ){
				// 	echo $content['ContentText'];
				// 	echo "<br /><br />";
				// }
			?>
		</td>
	</tr>
<table>
</body>
