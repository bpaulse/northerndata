<?php

require "./basic_db.php";
$function = new Database( 'local' );
$function::$dbId = 'local';

require "./test_mail_send.php";

$name = $_POST["name"];
$surname = $_POST["surname"];
$email = $_POST["email"];
$mobile = $_POST["mobile"];
$message = $_POST["message"];
 
$Subject = "Northern Data: Client Feedback Form";
// $companyEmail = "bevan@datanav.co.za";
$companyEmail = "info@northerndata.co.za";

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers = "From: " . $companyEmail . "\r\n" . "CC: " . $email;
 
// prepare email body text

$Body = "<table width='100%'>";
$Body .= "<tr><td style='background-color: #A3A3A3;'>" . $Subject . "</td></tr>";

$Body .= "<tr><td>&nbsp;</td></tr>";
$Body .= "<tr><td>The Client Feedback Form has been completed from the Northern Data Website. A ND Representative will be in contact shortly. Below is the information completed.</td></tr>";
$Body .= "<tr><td>&nbsp;</td></tr>";
$Body .= "<tr><td>Name    : " . $name . " " . $surname . "</td></tr>";
$Body .= "<tr><td>Email   : " . $email . "</td></tr>";
$Body .= "<tr><td>Mobile  : " . $mobile . "</td></tr>";
$Body .= "<tr><td>Message : " . $message . "</td></tr>";

$Body .= "<tr><td>&nbsp;</td></tr>";
$Body .= "<tr><td>Thank you for taking the time in completing the Form.</td></tr>";
$Body .= "<tr><td>Regards</td></tr>";
$Body .= "<tr><td>Northern Data</td></tr>";
$Body .= "<tr><td>&nbsp;</td></tr>";

$Body .= "</table>";


$fullname = $name . " " . $surname;
$Subject .= " (" . $fullname . ")";

$companyName = "Northern Data";
$companyEmail = "info@northerndata.co.za";


$info = array(
	"to" => array( array( "name" => $fullname, "email" => $email ), array( "name" => $companyName, "email" => $companyEmail ) ), 
	"from" => array( "name" => $companyName, "email" => $companyEmail ),
	"subject" => $Subject,
	"content" => $Body
);

$count = 0;

foreach ($info["to"] as $toRecipient){
	if ( SendGridMail($toRecipient, $info) ) {
		$id = $function->AddFormInfo(
			array(
				'name' => $name,
				'surname' => $surname,
				'email' => $email,
				'mobile' => $mobile,
				'comment' => $message
			)
		);

		// echo "DB-ID: " . $id . "\n";

		if ( $id > 0 ){
			$count++;
		}
		
	} 
}

if ( $count == 2 ){
	echo "ok";
} else {
	echo "failure";
}





 
?>