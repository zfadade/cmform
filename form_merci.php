<?php
session_start();
require_once("includes/php_utils.php");

$thankYouName = defaultVal($_SESSION, "thankYouName", "");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Elorac Reyem - Merci </title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

	<div id="wrapper">
		<h1> 
		<?php echo "Merci ".$thankYouName ." pour votre int&eacute;r&ecirc;t"; ?>
		</h1>

	</div>

</body>