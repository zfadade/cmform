
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Carole Meyer </title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

<?php
// define variables and set to empty values
$clientName = $clientEmail = "";
$nameErr = $emailErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["clientName"])) {
	    $nameErr = "Name is required";
	  } else {
	    $clientName = cleanInput($_POST["clientName"]);
	     // check if name only contains letters and whitespace
	    if (!preg_match("/^[a-zA-Z ]*$/", $clientName)) {
	      $nameErr = "Only letters and white space allowed"; 
	    }
	  }

	if (empty($_POST["clientEmail"])) {
	    $emailErr = "Email is required";
	  } else {
	    $clientEmail = cleanInput($_POST["clientEmail"]);
	    if (!filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
  			$emailErr = "Invalid email format"; 
		}
	  }

	  error_log("nameErr: " . $nameErr . " emailErr: " . $emailErr);
	  if (empty($nameErr) and empty($emailErr)) {
	  	sendMail($clientEmail, $clientName);
	  }

}

function cleanInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


function sendMail($clientEmail, $clientName) {
	require 'PHPMailerAutoload.php';

	$my_init_data = parse_ini_file("../secure/my_php.ini");

	$mail = new PHPMailer;

	//$mail->SMTPDebug = 3;                               // Enable verbose debug output

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'zinnerab@gmail.com';               // SMTP username
	$mail->Password = $my_init_data['zinner_ab_pass'];    // SMTP password
	$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;                                    // TCP port to connect to

	$mail->setFrom('zinnerab@gmail.com', 'MyCompany');

	$mail->addAddress($clientEmail, $clientName);     		// Add a recipient
	// $mail->addAddress('ellen@example.com');              // Name is optional
	$mail->addReplyTo('zinnerab@gmail.com', 'Contact');
	//$mail->addCC('cc@example.com');
	//$mail->addBCC('bcc@example.com');

	$mail->addAttachment('resources/LoremIpsum.pdf');         // Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');     // Optional name
	$mail->isHTML(true);                                  	// Set email format to HTML

	$mail->Subject = 'Here is the document you requested';
	$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	if (!$mail->send()) {
	    echo 'Message could not be sent.';
	    echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
	    echo 'Message has been sent';
	}
}


function xsendMail($to, $clientName) {
	$subject = "Document that you requested (not yet attached, please be patient)";

	$msg = "Dear " . $clientName . ":\nAs requested, here is the CV of You Know Who";
	//define the headers we want passed. Note that they are separated with \r\n
	$headers = "From: zinnerab@gmail.com\r\nReply-To: zinnerab@gmail.com";

	error_log("Sending email to " . $to  . " msg: " . $msg . "\n");
	$mail_sent = @mail($to, $subject, $msg, $headers);
	echo $mail_sent ? "Mail sent" : "Mail failed";
}

?>

	<div id="wrapper">

		<h1>Welcome to Carole Meyer's home page</h1>
		<hr />
		<p> Please register to receive Carole Meyer's resum&eacute; by email</p>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			Your name:
			<input type="text" name="clientName" value="<?php echo $clientName;?>" >
			<span class="error">* <?php echo $nameErr;?></span>
			<br><br>

			Your email:
			<input type="text" name="clientEmail" value="<?php echo $clientEmail;?>" >
			<span class="error">* <?php echo $emailErr;?></span>
			<br><br>
			<input type="submit" value="Envoyer">

		</form>
	</div>

</body>
</html>