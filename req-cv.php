
<?php
require_once("includes/php_utils.php");
//require './vendor/autoload.php';

// i18n:
$language = setLanguage();

$nameErr = $emailErr = "";
$clientName = defaultVal($_SESSION, "clientName", "");
$clientEmail = defaultVal($_SESSION, "clientEmail", "");
$clientComment = defaultVal($_SESSION, "clientComment", "");

// The form has been submitted.  Do error correction, and act on data if it's good
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	echo 'Form got submitted' . "<br>";

	// Verify client name
	$clientName = filter_input(INPUT_POST, 'clientName', FILTER_SANITIZE_STRING);
	if (empty($clientName)) {
	    $nameErr = _("nom_obligatoire");
	  } else {

	    if (!preg_match("/^[a-zA-Z0-9 .]*$/", $clientName)) {
	      $nameErr = _("courriel_chars"); 
	      $clientName = "";
	      //$nameErr = "Only letters, numbers and white space allowed"; 
	    }
	  }

	// Verify client email
	$clientEmail = filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_STRING);
	if (empty($clientEmail)) {
	    $emailErr = _("courriel_obligatoire");
	  } else {
	    if (!filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
  			$emailErr = _("courriel_invalide"); 
  			$clientEmail = "";
		}
	  }

	 // Client comment
	$clientComment = filter_input(INPUT_POST, 'clientComment', FILTER_SANITIZE_STRING);

	// Send email if data was OK
	error_log("nameErr: " . $nameErr . " emailErr: " . $emailErr);
	if (empty($nameErr) and empty($emailErr)) {
		echo 'No errors so will send mail' . "<br>";
		sendMail($clientEmail, $clientName, $clientComment);
		echo 'THANK YOU!' . "<br>";

		// Sent mail OK; clean up everything
		$nameErr = $emailErr = "";
		$_SESSION["clientName"] = "";
		$_SESSION["clientEmail"] = "";
		$_SESSION["clientComment"] = "";

		$_SESSION["thankYouName"] = $clientName;
		sleep(5);    //For debugging
		header('Location: form_merci.php');
	}
	else {
		// Data was bad.  Save variables so they'll be there when form is redisplayed
		$_SESSION["clientName"] = $clientName; 
		$_SESSION["clientEmail"] = $clientEmail; 
		$_SESSION["clientComment"] = $clientComment; 
		$_SESSION["thankYouName"] = "";
	}
}

function sendMail($clientEmail, $clientName, $clientComment) {

	echo 'Setting up mail header...' . "<br>";
	$mail = setupMailHeader();
	echo 'Mail header set up' . "<br>";
	$mail->AddEmbeddedImage('resources/logo_CM_2016.png', 'logoimg', 'logo_CM_2016.png');

	$bodytext = <<<EOD1
    <html>
    <body>
    <p><img src="cid:logoimg" /></p>
    <p>Merci pour avoir contact&eacute;
    <span style="color:#5990B1 ;font-size:18px;font-weight:bold;"> CMCO</style></span>.</p>
    <p> Voici le document que vous avez demand&eacute;.</p>
EOD1;

	if (! empty($clientComment)) {
		$bodyComment = <<<EOD2
		<p>Votre commentaire ...</p>
		$clientComment
EOD2;

	$bodytext .= $bodyComment;
	}

	$bodyFinish = <<<EOD3
    </body>
    </html>
EOD3;

	$bodytext .= $bodyFinish;

	$mail->addAddress($clientEmail, $clientName);     		// Add a recipient

	$mail->addAttachment('resources/LoremIpsum.pdf');         // Add attachments

	$mail->Subject = 'Voici le document que vous avez demand&eacute';
	$mail->Body    = $bodytext;
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';


	echo 'Calling mail->send() ...' . "<br>";;
	if (!$mail->send()) {
	    echo 'Message could not be sent.'. "<br>";;
	    echo 'Mailer Error: ' . $mail->ErrorInfo;
	    error_log("FAIL:  Unable to send email to ".$clientName." at ".$clientEmail." with comment ".$clientComment);
	} else {
	    echo 'Votre demande a &eacute;t&eacute; envoy&eacute;e';
	    error_log("Email sent to ".$clientName." at ".$clientEmail." with comment ".$clientComment);
	}
}

function setupMailHeader() {
	echo 'Hello from setupMailHeader()'. "<br>";

	require 'vendor/autoload.php';
	require_once('includes/config.php');

	echo 'Instantiating new PHPMailer'. "<br>";
	$mail = new PHPMailer;
	echo 'Instantiated !'. "<br>";
	//$mail->SMTPDebug = 3;                               // Enable verbose debug output
	
	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';  					  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = $my_init_data['cmform_smtp_username']; 		// SMTP username
	$mail->Password = $my_init_data['cmform_smtp_password'];    	// SMTP password
	$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;                                    // TCP port to connect to

	$mail->isHTML(true);                                  	// Set email format to HTML

	$mail->setFrom($my_init_data['cmform_from_email'], 'MyCompany');
	$mail->addReplyTo($my_init_data['cmform_replyto_email'], 'Contact');
	$mail->addBCC($my_init_data['cmform_bcc_email']);

	return $mail;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Elorac Reyem </title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

	<div id="wrapper">

		<h1><?php echo _("Bienvenue chez"); ?></h1>

		<a href="req-cv.php?lang=fr"> <?php echo _("Francais"); ?></a>
		<a href="req-cv.php?lang=en"> <?php echo _("Anglais"); ?> </a>
		<hr />
		<p><?php echo _("Recevoir CV"); ?> </p>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?lang=" . $language); ?>">
			<?php echo _("Votre nom"); ?>
			<input type="text" name="clientName" value="<?php echo $clientName;?>" >
			<span class="error">* <?php echo $nameErr;?></span>
			<br><br>

			<?php echo _("Votre courriel"); ?>
			<input type="text" name="clientEmail" value="<?php echo $clientEmail;?>" >
			<span class="error">* <?php echo $emailErr;?></span>
			<br><br>
			
			<?php echo _("Commentaires"); ?>
			<textarea name="clientComment" id="clientComment" rows="6" cols="33" maxlength="200"><?php echo $clientComment;?></textarea>
			<p><input type="submit" value="<?php echo _("Envoyez");?>"></p>

		</form>
	</div>

</body>
</html>
