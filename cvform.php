
<?php

session_start();
require_once("includes/php_utils.php");

// i18n:
$language = "fr_FR";

if (isset($_GET["lang"]))
{
    $lang = filter_input(INPUT_GET, 'lang', FILTER_SANITIZE_STRING);
    if (strpos($lang, "en_") === 0) {
        $language = "en_US";
    }
}

putenv("LANG=" . $language);
setlocale(LC_ALL, $language);
//echo "Setting language to " . $language;

// Set the text domain as "messages"
$domain = "messages";
bindtextdomain($domain, "locale");

//bind_textdomain_codeset($domain, 'UTF-8');
textdomain($domain);


$nameErr = $emailErr = "";
$clientName = defaultVal($_SESSION, "clientName", "");
$clientEmail = defaultVal($_SESSION, "clientEmail", "");
$clientComment = defaultVal($_SESSION, "clientComment", "");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$clientName = filter_input(INPUT_POST, 'clientName', FILTER_SANITIZE_STRING);
	if (empty($clientName)) {
	    $nameErr = "Votre nom est obligatoire";
	  } else {

	    if (!preg_match("/^[a-zA-Z0-9 .]*$/", $clientName)) {
	      $nameErr = "Seulement des lettres, chiffres et espace autoris&eacute;s"; 
	      $clientName = "";
	      //$nameErr = "Only letters, numbers and white space allowed"; 
	    }
	  }

	$clientEmail = filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_STRING);
	if (empty($clientEmail)) {
	    $emailErr = "Votre courriel est obligatoire";
	  } else {
	    if (!filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
  			$emailErr = "Courriel invalide"; 
  			$clientEmail = "";
		}
	  }

	  $clientComment = filter_input(INPUT_POST, 'clientComment', FILTER_SANITIZE_STRING);

	  error_log("nameErr: " . $nameErr . " emailErr: " . $emailErr);
	  if (empty($nameErr) and empty($emailErr)) {
	  	sendMail($clientEmail, $clientName, $clientComment);

	  	// Sent mail OK; clean up everything
		$nameErr = $emailErr = "";
		$_SESSION["clientName"] = "";
		$_SESSION["clientEmail"] = "";
		$_SESSION["clientComment"] = "";

		$_SESSION["thankYouName"] = $clientName;
		header('Location: form_merci.php');
	  }
	  else {
	  	// Save variables so they'll be there when form is redisplayed
		$_SESSION["clientName"] = $clientName; 
		$_SESSION["clientEmail"] = $clientEmail; 
		$_SESSION["clientComment"] = $clientComment; 
	  }
}

function cleanInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


function sendMail($clientEmail, $clientName, $clientComment) {

	$mail = setupMailHeader();
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

	if (!$mail->send()) {
	    echo 'Message could not be sent.';
	    echo 'Mailer Error: ' . $mail->ErrorInfo;
	    error_log("FAIL:  Unable to send email to ".$clientName." at ".$clientEmail." with comment ".$clientComment);
	} else {
	    echo 'Votre demande a &eacute;t&eacute; envoy&eacute;e';
	    error_log("Email sent to ".$clientName." at ".$clientEmail." with comment ".$clientComment);
	}
}

function setupMailHeader() {

	require 'PHPMailerAutoload.php';
	$my_init_data = parse_ini_file("../secure/my_php.ini");

	$mail = new PHPMailer;
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

		<h1><?php echo _("Bienvenue chez Elorec Reyem"); ?></h1>
		<hr />
		<p><?php echo _("Recevoir CV"); ?> </p>
		<p><?php echo _("Test Line"); ?> </p>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<?php echo _("Votre nom"); ?>
			<input type="text" name="clientName" value="<?php echo $clientName;?>" >
			<span class="error">* <?php echo $nameErr;?></span>
			<br><br>

			<?php echo _("Votre courriel"); ?>
			<input type="text" name="clientEmail" value="<?php echo $clientEmail;?>" >
			<span class="error">* <?php echo $emailErr;?></span>
			<br><br>
			<textarea name="clientComment" id="clientComment" rows="6" cols="33" maxlength="200"><?php echo $clientComment;?></textarea>
			<p><input type="submit" value="Envoyez"></p>

		</form>
	</div>

</body>
</html>
