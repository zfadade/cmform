<?php
require_once("includes/php_utils.php"); 
require_once('includes/config.php'); 
// i18n:
$language = setLanguage();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo _("Blogue"); ?></title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

	<div id="wrapper">

		<h1><?php echo _("Blogue"); ?></h1>
		<hr />

		<?php
			try {

				$stmt = $db->query('SELECT postID, postTitle, postDesc, postDate FROM blog_posts ORDER BY postID DESC');
				while ($row = $stmt->fetch()) {
					$postId = $row['postID'];
					$postTitle = $row['postTitle'];
					$postDate = strftime("%d %b %Y %H:%M", strtotime($row['postDate']));
					$postDesc = $row['postDesc'];
					$postId = $row['postID'];
					$postedOnTr = _("posted_on");
					$lirePlusTr = _("lire_plus");

					print <<< END
					<div>
						<h1>
							<a href="viewpost.php?id=$postId"> $postTitle</a>
						</h1>
						<p>$postedOnTr $postDate</p>
						<p>$postDesc</p>
						<p>
							<a href="viewpost.php?id=$postId">$lirePlusTr</a>
						</p>
					<div>
END;
/*				
					echo '<div>';
					echo '<h1><a href="viewpost.php?id='.$row['postID'].'">'.$row['postTitle'].'</a></h1>';
					// strftime("%d %b %Y %H:%M", strtotime($row['postDate']));
					echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($row['postDate'])).'</p>';
					echo '<p>'.$row['postDesc'].'</p>';	
					echo '<p><a href="viewpost.php?id='.$row['postID'].'">Read More</a></p>';				
					echo '</div>';
*/
				}

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
		?>

	</div>

</body>
</html>