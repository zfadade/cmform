<?php 
require_once('../includes/config.php');
require_once("../includes/php_utils.php");

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }

// i18n:
$language = setLanguage();
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Edit Post</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">

  <script "text/javascript" src="/cmform/vendor/tinymce/tinymce/tinymce.min.js"></script>
  <script "text/javascript"  src="/cmform/js/initTiny.js"></script>
</head>
<body>
	<div id="wrapper">

		<?php include('menu.php');?>
		<p><a href="./">Blog Admin Index</a></p>

		<h2>Edit Post</h2>


	<?php

	//if form has been submitted process it
	if (isset($_POST['update']) or isset($_POST['updateAndPost']) )  {

		// echo "SERVER  " . '<bp>';
		// var_dump($_SERVER);



		//very basic validation
		// if (empty($postID)) {
		// 	$error[] = 'This post is missing a valid id!.';
		// }

		// if($postTitle ==''){
		// 	$error[] = 'Please enter the title.';
		// }

		// if($postDesc ==''){
		// 	$error[] = 'Please enter the description.';
		// }

		// if($postCont ==''){
		// 	$error[] = 'Please enter the content.';
		// }

		if (!isset($error)) {
			try {
				insertOrUpdateBlog($db);

				//redirect to index page
				header('Location: index.php?action=updated');
				exit;

			} catch(PDOException $e) {
				//$stmt->debugDumpParams();
			    echo $e->getMessage();
			}

		}
	}

	// END OF SUBMIT[] code .......

	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo $error.'<br />';
		}
	}

	$idFromGet = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
	// echo "id from GET: ", $idFromGet, '<br>';
	try {

		$stmt = $db->prepare('SELECT * FROM blog_posts_bi WHERE postID = :postID');
		$stmt->bindParam(':postID', $idFromGet);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'BlogEntry');
		$row = $stmt->fetch();

	} catch(Exception $e) {
		echo $e->getMessage();
	}	

	if (empty($row)) {
		$row = new BlogEntry();
	}

	print <<< END

	<form method='post' action="{$hereFunc(htmlspecialchars($_SERVER['PHP_SELF']))}" > 

		<input type='hidden' name='postID' value="{$row->getPostId()}"/>
	
		<h2 id="frTitle">FRAN&Ccedil;AIS</h2>
		<p>
			<label>Titre</label><br />
			<input type='text' name='frTitle' value="{$row->getTitle(FRENCH)}">
		</p>

		<p>
			<label>D&eacute;scription</label><br />
			<input type='text' name='frDesc' value="{$row->getDescription(FRENCH)}" />
		</p>

		<p>
			<label>Contenu</label><br />
			<textarea name='frContents' class="frTextArea"  cols='60' rows='10'>
				{$row->getContents(FRENCH)}
			</textarea>
		</p>

		<h2 id="enTitle">ENGLISH</h2>
		<p>
			<label>Title</label><br />
			<input type='text' name='enTitle' value="{$row->getTitle(ENGLISH)}">
		</p>

		<p>
			<label>Description</label><br />
			<input type='text' name='enDesc' value="{$row->getDescription(ENGLISH)}" />
		</p>

		<p>
			<label>Contents</label><br />
			<textarea name='enContents' class="enTextArea"  cols='60' rows='10'>
				{$row->getContents(ENGLISH)}
			</textarea>
		</p>
		<p>
			<input type='submit' name='update' value='Update'>
			<input type='submit' name='updateAndPost' value='Update and Post'>
		</p>

	</form>

</div>

</body>
</html>	
END;


function insertOrUpdateBlog($db) {
	echo  "POST ". '<bp>';
	var_dump($_POST);

	// WHY ???
	$_POST = array_map( 'stripslashes', $_POST );

	//collect form data
	extract($_POST);

	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	if (empty($postID)) {
		// Insert new row into DB
		$stmt = $db->prepare("INSERT INTO blog_posts_bi (frTitle, enTitle, frDesc, enDesc, frContents, enContents) 
			VALUES (:frTitle, :enTitle, :frDesc, :enDesc, :frContents, :enContents)");

		// insert new row into DB
		$stmt->execute(array(
			'frTitle' => $frTitle,
			'enTitle' => $enTitle,
			'frDesc' => $frDesc,
			'enDesc' => $enDesc,
			'frContents' => $frContents,
			'enContents' => $enContents ));

	}
	else {
		// Update existing in DB
		$stmt = $db->prepare('UPDATE blog_posts_bi SET 
				frTitle = :frTitle, enTitle = :enTitle, 
				frDesc = :frDesc, enDesc = :enDesc, 
				frContents = :frContents, enContents = :enContents, 
				WHERE postID = :postID');

		$stmt->execute(array(
			'frTitle' => $frTitle,
			'enTitle' => $enTitle,
			'frDesc' => $frDesc,
			'enDesc' => $enDesc,
			'frContents' => $frContents,
			'enContents' => $enContents,
			'postID' => $postID ));
}
}
