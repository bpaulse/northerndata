<?php
require("../sendgrid-php/sendgrid-php.php");

function SendGridMail () {

	$fromDetail = array( "name" => "Bevan Datanav", "email" => "bevan@datanav.co.za" );
	$toDetail = array( "name" => "Bevan Lieben", "email" => "bevanpaulse@gmail.com" );
	$subject = "Sending with SendGrid is Fun using php";

	$from = new SendGrid\Email($fromDetail["name"], $fromDetail["email"]);
	$to = new SendGrid\Email($toDetail["name"], $toDetail["email"]);
	$content = new SendGrid\Content("text/html", "</table><tr><td><b><i>Bevan Paulse with PHP Skills</i></b></td></tr></table>");
	$mail = new SendGrid\Mail($from, $subject, $to, $content);

	$mailsettings = parse_ini_file("mailsettings.ini");
	$apiKey = $mailsettings['SENDGRID_API_KEY'];

	$sg = new \SendGrid($apiKey);

	try {
		$response = $sg->client->mail()->send()->post($mail);
		//echo $response->statusCode();
		if ( $response->statusCode() == 202 ){
			echo "ok";
		 }
	} catch (Exception $e) {
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		echo "failure";
	}
}

SendGridMail();

?>