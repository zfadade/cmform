
<?php

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


$clientName = $clientEmail =  $clientComment = "";
$nameErr = $emailErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["clientName"])) {
	    $nameErr = "Votre nom est obligatoire";
	  } else {
	    $clientName = cleanInput($_POST["clientName"]);
	     // check if name only contains letters and whitespace
	    if (!preg_match("/^[a-zA-Z0-9 .]*$/", $clientName)) {
	      $nameErr = "Seulement des lettres, chiffres et espace autoris&eacute;s"; 
	      //$nameErr = "Only letters, numbers and white space allowed"; 
	    }
	  }

	if (empty($_POST["clientEmail"])) {
	    $emailErr = "Votre courriel est obligatoire";
	  } else {
	    $clientEmail = cleanInput($_POST["clientEmail"]);
	    if (!filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
  			$emailErr = "Courriel invalide"; 
		}
	  }

	  $clientComment = cleanInput($_POST["clientComment"]);

	  error_log("nameErr: " . $nameErr . " emailErr: " . $emailErr);
	  if (empty($nameErr) and empty($emailErr)) {
	  	sendMail($clientEmail, $clientName, $clientComment);
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
		<br><br>
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
	} else {
	    echo 'Votre demande a &eacute;t&eacute; envoy&eacute;e';

	    //echo '<script> alert("Votre demande a &eacute;t&eacute; envoy&eacute;e"); </script>';
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

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Carole Meyer </title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

	<div id="wrapper">

		<h1><?php echo _("Bienvenue chez Carole Meyer Formation"); ?></h1>
		<hr />
		<p><?php echo _("Recevoir CV"); ?> </p>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<?php echo _("Votre nom"); ?>
			<input type="text" name="clientName" value="<?php echo $clientName;?>" >
			<span class="error">* <?php echo $nameErr;?></span>
			<br><br>

			<?php echo _("Votre courriel"); ?>
			<input type="text" name="clientEmail" value="<?php echo $clientEmail;?>" >
			<span class="error">* <?php echo $emailErr;?></span>
			<br><br>

			<?php echo _("Vos commentaires sont les bienvenus"); ?><br>
			<textarea name="clientComment" id="clientComment" rows="6" cols="33" maxlength="200"> </textarea>
			<p><input type="submit" value="Envoyez"></p>

		</form>
	</div>

</body>
</html>