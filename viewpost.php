<?php 
require_once('includes/config.php'); 
require_once('includes/php_utils.php'); 

$language = setLanguage();

//$stmt = $db->prepare('SELECT postID, postTitle, postCont, postDate FROM blog_posts WHERE postID = :postID');


$postId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$blogId = BLOGUE . $postId;
if (! isset($_SESSION[$blogId])) {
	header('Location: ./');
	exit;
}

// $row is an instance of BlogEntry class
$row = $_SESSION[$blogId];
// echo "<pre>", print_r($row), "</pre>";

//if post does not exists redirect user.
// if($row['postID'] == ''){
// 	header('Location: ./');
// 	exit;
// }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <!-- <link rel="stylesheet" href="style/normalize.css"> -->
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

<?php

print <<< END
	<a href="viewpost.php?id=$postId&lang=fr">Francais</a>
	<a href="viewpost.php?id=$postId&lang=en"> Anglais </a>
END;
?>
	<div id="wrapper">

		<h1><?php echo _("Blogue"); ?></h1>
		<hr />
		<p><a href="./">Blog Index</a></p>


		<?php	
			echo '<div>';
			$postDate = $row->getPostDate($language);

			echo '<p>Posted on ', $postDate , '</p>';
			echo '<p>'.$row->getContents($language) .'</p>';				
			echo '</div>';
		?>

	</div>

</body>
</html>