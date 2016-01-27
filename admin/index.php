<?php
require_once('../includes/config.php');
require_once('../includes/php_utils.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }

//show message from add / edit page
if(isset($_GET['delpost'])){

	$stmt = $db->prepare('DELETE FROM blog_posts_bi WHERE postID = :postID') ;
	$stmt->execute(array(':postID' => $_GET['delpost']));

	header('Location: index.php?action=deleted');
	exit;
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
  <script language="JavaScript" type="text/javascript">
  function delpost(id, title)
  {
	  if (confirm("Are you sure you want to delete '" + title + "'"))
	  {
			// JavaScript redirect, as a result of some user actin
	  	window.location.href = 'index.php?delpost=' + id;
	  }
  }
  </script>
</head>
<body>

	<div id="wrapper">

	<?php include('menu.php');?>

	<?php
	//show message from add / edit page
	if(isset($_GET['action'])){
		echo '<h3>Post '.$_GET['action'].'.</h3>';
	}
	?>

	<table>
	<tr>
		<th>French Title</th>
		<th>English Title</th>
		<th>French Post Date</th>
		<th>English Post Date</th>
		<th>Action</th>
	</tr>
	<?php
		try {
			// Prepared Statement ?
			$stmt = $db->query('SELECT postID, frTitle, enTitle, frPostDate, enPostDate FROM blog_posts_bi ORDER BY postID DESC');
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'BlogEntry');
			while($row = $stmt->fetch()) {

				echo '<tr>';
				echo '<td>'.$row->frTitle .'</td>';
				echo '<td>'.$row->enTitle . '</td>';
				echo '<td>'. $row->getPostDate(FRENCH) .'</td>';
				echo '<td>'. $row->getPostDate(ENGLISH) .'</td>';
				?>

				<td>
					<a href="edit-post.php?id=<?php echo $row->postID;?>">Edit</a> |
					<a href="javascript:delpost('<?php echo $row->postID;?>','<?php echo $row->getTitles();?>')">Delete</a>
				</td>

				<?php
				echo '</tr>';

			}

		} catch(PDOException $e) {
		    echo $e->getMessage();
		}
	?>
	</table>

	<p><a href='edit-post.php'>Add Post</a></p>

</div>

</body>
</html>
