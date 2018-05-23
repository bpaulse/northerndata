<?php
require("sendgrid-php/sendgrid-php.php");

function SendGridMail ($toDetails, $sendinfo) {

	$from = new SendGrid\Email($sendinfo["from"]["name"], $sendinfo["from"]["email"]);
	$to = new SendGrid\Email($toDetails["name"], $toDetails["email"]);
	$content = new SendGrid\Content("text/html", $sendinfo["content"]);
	$mail = new SendGrid\Mail($from, $sendinfo["subject"], $to, $content);

	$mailsettings = parse_ini_file("sendgrid-php/mailsettings.ini");
	$apiKey = $mailsettings['SENDGRID_API_KEY'];

	$sg = new \SendGrid($apiKey);

	try {
		$response = $sg->client->mail()->send()->post($mail);
		//echo $response->statusCode();
		if ( $response->statusCode() == 202 ){
			return true;
		 }
	} catch (Exception $e) {
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		echo false;
	}
}

?>