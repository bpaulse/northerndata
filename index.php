<?php
require "./basic_db.php";
$function = new Database( 'local' );
$function::$dbId = 'local';

function isMobileDevice() {
	return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

?>
<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>Northern Data, Specialists in Data Analysis & Analytics</title>
		<meta name="description" content="Northern Data is a Microsoft Gold Partner for Data Analytics and Analysis." />
		<meta name="keywords" content="Northern Data, Azure, Continuity, Business, Microsoft Gold Partner, Data, Analysis, Analytics" />
		<meta name="author" content="Northern Data" />
		<link rel="shortcut icon" href="./favicon.ico"> 
		<link rel="stylesheet" type="text/css" href="css/default.css" />
		<link rel="stylesheet" type="text/css" href="css/multilevelmenu.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<link rel="stylesheet" type="text/css" href="css/animations.css" />
		<script src="js/modernizr.custom.js"></script>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	</head>
	<body>
	<?php if(isMobileDevice()){
		include("mobile_home.php");
	} else { ?>
		<div class="pt-triggers">
			<!-- <button id="iterateEffects" class="pt-touch-button">Show next page transition</button> -->
			<button id="iterateEffects" class="pt-touch-button">
				<img width="70px" src="images/icon-menu.png" />
			</button>
			<div id="dl-menu" class="dl-menuwrapper" style="display: none;">
				<button class="dl-trigger" data-animation="58">Choose a transition</button>
				<ul class="dl-menu">
					<li data-animation="58"><a href="#">Cube to left</a></li>
				</ul>
			</div>
		</div>

		<div id="pt-main" class="pt-perspective">
			<div class="pt-page pt-page-1">
				<div class="logo">
						<div class="companyname">
							Northern Data<br />
							<div class="characteristics">
								manage | distribute | secure data
							</div>
						</div>
					</div>
				<!-- <h1><span>A collection of</span><strong>Page</strong> Transitions</h1> -->
			</div>

			<div class="pt-page pt-page-2">
				<div class="history"></div>
				<div class="horizontal-blue-line"></div>
				<span class="dot">
					<img src="images/icon-crew-white.png" width="100%" />
				</span>
				
				<div class="block-container-desc">
					<div class="vertical-text">About Us</div>
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
				</div>
			</div>

			<div class="pt-page pt-page-3">
				<div class="history"></div>
				<div class="horizontal-blue-line"></div>
				
				<span class="dot"><img src="images/services-icon.png" style="margin-top: 19px; margin-left: 23px; width: 70%; border: 0px solid black;" /></span>
				<div class="block-container-desc">
					<div class="vertical-text">Services</div>
					<div class="elementHolder">
						<div class="single-block-container"><img src="images/db-admin.png" width="60%" /></div>
						<div class="single-block-container-text">Database Administration</div>
					</div>

					<div class="elementHolder">
						<div class="single-block-container"><img src="images/azure.png" width="60%" /></div>
						<div class="single-block-container-text">Azure</div>
					</div>
	
					<div class="elementHolder">
						<div class="single-block-container"><img src="images/strategy-online.png" width="60%" /></div>
						<div class="single-block-container-text">Data Strategy</div>
					</div>
	
					<div class="elementHolder">
						<div class="single-block-container"><img src="images/strategy-insights.png" width="60%" /></div>
						<div class="single-block-container-text">Data Analytics</div>
					</div>

					<div class="elementHolder">
						<div class="single-block-container"><img src="images/analytics-icon.png" width="60%" /></div>
						<div class="single-block-container-text">Business Continuity Services</div>
					</div>

					<div class="elementHolder">
						<div class="single-block-container"><img src="images/offices-icon-white.png" width="60%" /></div>
						<div class="single-block-container-text">Information and Knowledge Management</div>
					</div>
				</div>
			</div>

			<div class="pt-page pt-page-7">
				<div class="history"></div>
				<div class="horizontal-blue-line"></div>
				<!-- <span class="dot"><img src="images/icon_about.png" width="100%" /></span> -->
				<span class="dot"><img src="images/icon_phone.png" width="100%" /></span>
				<div class="block-container-desc">
					<div class="vertical-text">Contact Us</div>
					<div class="container">
						<div class="row">
							<div class="col-sm-6">
								<div style="height: 100px;">
									<div style="float: left; font-size: 10pt; text-align: justify;">
										<img src="images/image004.jpg" /><br />
										We assist organizations of all sizes to effectively manage, distribute and secure their data with key focus on providing value add, knowledge management and timeous insights into their business. Northern Data is 100% Black Owned and LEVEL 1 B-BBEE. Northern Data is a Microsoft Gold Partner for Data Analytics.
										<?php
											// $PageName = 'ContactUs';
											// echo "<br >";
											// foreach ( $function->getPageInfo($PageName) as $content ){
											// 	echo $content['ContentText'];
											// 	echo "<br /><br />";
											// }
										?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
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
									<div class="visit">
										<img id="feedback-img" src="images/feedback-icon.png" data-toggle="modal" data-target="#modalForm" />
									</div>
								</div>
							</div>
						</div>
					</div>
			</div>

		</div>

		<div class="pt-message">
			<p>Your browser does not support CSS animations.</p>
		</div>

		<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
		<script src="js/jquery.dlmenu.js"></script>
		<script src="js/pagetransitions.js"></script>
		<script>
			$( document ).ready(function() {

				$('.visit').click(function(e) {
					e.preventDefault();
					$("#formModal").modal();
				});S

			});


			function submitContactForm(){
				var reg = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
				var name = $('#inputName').val();
				var surname = $('#inputSurname').val();
				var email = $('#inputEmail').val();
				var mobile = $('#inputMobile').val();
				var message = $('#inputMessage').val();
				if(name.trim() == '' ){
					alert('Please enter your name.');
					$('#inputName').focus();
					return false;
				}else if(surname.trim() == '' ){
					alert('Please enter your surname.');
					$('#inputSurname').focus();
					return false;
				}else if(email.trim() == '' ){
					alert('Please enter your email.');
					$('#inputEmail').focus();
					return false;
				}else if(email.trim() != '' && !reg.test(email)){
					alert('Please enter valid email.');
					$('#inputEmail').focus();
					return false;
				}else if(message.trim() == '' ){
					alert('Please enter your message.');
					$('#inputMessage').focus();
					return false;
				}else{
					$.ajax({
						type:'POST',
						url:'form-process.php',
						data:'contactFrmSubmit=1&name='+name+'&surname='+surname+'&email='+email+'&mobile='+mobile+'&message='+message,
						beforeSend: function () {
							$('.submitBtn').attr("disabled","disabled");
							$('.modal-body').css('opacity', '.5');
						},
						success:function(msg){
							if(msg == 'ok'){
								$('#inputName').val('');
								$('#inputSurname').val('');
								$('#inputEmail').val('');
								$('#inputMobile').val('');
								$('#inputMessage').val('');
								$('.statusMsg').html('<span style="color:green; font-weight: bold;">Thanks for contacting us, we\'ll get back to you soon.</p>');
							}else{
								$('.statusMsg').html('<span style="color:red; font-weight: bold;">Some problem occurred, please try again.</span>');
							}
							// $('#formModal').modal('hide');
							$("#formModal").trigger("reset");
							// $('.submitBtn').removeAttr("disabled");
							// $('.modal-body').css('opacity', '');
						},
						beforeClose: function(){
							console.log("closing");
							$("#formModal")[0].reset();
						}
					});
				}
			}


		</script>

		<!-- Modal -->
		<div class="modal fade" id="modalForm" role="dialog" data-backdrop="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span>
							<span class="sr-only">Close</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">Contact Form</h4>
					</div>
					
					<!-- Modal Body -->
					<div class="modal-body">
						<p class="statusMsg"></p>
						<form role="form">
							<div class="form-group">
								<label for="inputName">Name</label>
								<input type="text" class="form-control" id="inputName" placeholder="Enter your Name"/>
							</div>
							<div class="form-group">
								<label for="inputSurname">Surname</label>
								<input type="text" class="form-control" id="inputSurname" placeholder="Enter your Surname"/>
							</div>
							<div class="form-group">
								<label for="inputEmail">Email</label>
								<input type="email" class="form-control" id="inputEmail" placeholder="Enter your Email"/>
							</div>
							<div class="form-group">
								<label for="inputMobile">Mobile</label>
								<input type="text" class="form-control" id="inputMobile" placeholder="Enter your Mobile Number"/>
							</div>
							<div class="form-group">
								<label for="inputMessage">Message</label>
								<textarea class="form-control" id="inputMessage" placeholder="Enter your message"></textarea>
							</div>
						</form>
					</div>
					
					<!-- Modal Footer -->
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary submitBtn" onclick="submitContactForm()">SUBMIT</button>
					</div>
				</div>
			</div>
		</div>

	</body>
</html>
<?php } ?>
