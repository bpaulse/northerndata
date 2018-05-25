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
      <a class="navbar-brand" href="#">Home of NORTHERN DATA</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <!-- <ul class="nav navbar-nav">
        <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
        <li><a href="mobileversion.php">Temp</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="">Separated link</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>
      </ul> -->
      <!-- <form class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form> -->
      <ul class="nav navbar-nav navbar-right">
        <li><a href="mobile_home.php">Home</a></li>
        <li><a href="mobile_about.php">About Us</a></li>
        <li><a href="mobile_services.php">Services</a></li>
		<li class="active"><a href="mobile_contact.php">Contact Us</a></li>
		<li><a href="mobile_form.php">Client Feedback</a></li>
        <!-- <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
	            <li><a href="mobileversion.php">test</a></li>
          </ul>
        </li> -->
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
</body>
<table width="100%" height="100px">
	<tr align="center">
		<td colspan="3"><img src="images/logo.png" width="100px" /></td>
	</tr>
	<tr><td><img src="images/image004.jpg" /></td>
	<td>&nbsp;&nbsp;&nbsp;</td>
	<td style="alignment: justify;">
		We assist organizations of all sizes to effectively manage, distribute and secure their data with key focus on providing value add, knowledge management and timeous insights into their business. Northern Data is 100% Black Owned and LEVEL 1 B-BBEE. Northern Data is a Microsoft Gold Partner for Data Analytics.
		<?php
		// $PageName = 'ContactUs';
		// echo "<br >";
		// foreach ( $function->getPageInfo($PageName) as $content ){
		// 	echo $content['ContentText'];
		// 	echo "<br /><br />";
		// }
		?>
	</td>
	</tr>
</table>
<!-- <table> -->
<div style="width: 100%; border: 0px solid black;">CONTACT</div>
<div style="float: left; width: 65px; border: 0px solid black;">
	<img src="images/phone-icon.png" width="60px" /><br />
	<img src="images/email-icon-4.svg" width="60px" />
	<img src="images/online-support-icon.png" width="60px" />
</div>
<div style="float: left;font-size: 12pt; width: 220px; border: 0px solid black; padding-left: 10px;">
	Jody Roberts<br />
	+27 21 100 2121<br /><br />
	info@northerndata.co.za<br /><br />
	support@northerndata.co.za<br />
	+27 87 150 0204
</div>