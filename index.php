<?php
require_once("includes/php_utils.php"); 
require_once('includes/config.php'); 
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

	<!-- TODO:  make this a function -->
	<a href="index.php?lang=fr"> <?php echo _("Francais"); ?></a>
	<a href="index.php?lang=en"> <?php echo _("Anglais"); ?> </a>

	<div id="wrapper">

		<h1><?php echo _("Blogue"); ?></h1>
		<hr />

		<?php
			$postedOnTr = _("posted_on");
			$lirePlusTr = _("lire_plus");
			try {
				$stmt = $db->query('SELECT * FROM blog_posts_bi WHERE frPostDate IS NOT NULL OR enPostDate IS NOT NULL ORDER BY postID DESC');

				$stmt->setFetchMode(PDO::FETCH_CLASS, 'BlogEntry');
				while ($row = $stmt->fetch()) {
					//⁄⁄‚‚echo '<pre>', print_r($row), '</pre>';
					$postId = $row->postID;
					$postDate = $row->getPostDate($language);
					$postTitle = $row->getTitle($language);
					$postDesc = $row->getDescription($language);
					
					// Add row to session to it can be accessed in other pages
					$_SESSION[BLOGUE . $postId] = $row;

					if (! empty($postDate)) {
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
					}
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