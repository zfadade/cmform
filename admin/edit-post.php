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
  <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script>
          tinymce.init({
              selector: "textarea",
              plugins: [
                  "advlist autolink lists link image charmap print preview anchor",
                  "searchreplace visualblocks code fullscreen",
                  "insertdatetime media table contextmenu paste"
              ],
              toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
          });
  </script>
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

		echo  "POST ". '<bp>';
		var_dump($_POST);
		$_POST = array_map( 'stripslashes', $_POST );

		//collect form data
		extract($_POST);

		//very basic validation
		if($postID ==''){
			$error[] = 'This post is missing a valid id!.';
		}

		if($postTitle ==''){
			$error[] = 'Please enter the title.';
		}

		if($postDesc ==''){
			$error[] = 'Please enter the description.';
		}

		if($postCont ==''){
			$error[] = 'Please enter the content.';
		}

		if(!isset($error)){

			try {

				echo "Updating form for ", $language;
				$stmt = $language === ENGLISH ? 
					$db->prepare('UPDATE blog_posts_bi SET enTitle = :postTitle, enDesc = :postDesc, enContents = :postCont WHERE postID = :postID') 
					:
					$db->prepare('UPDATE blog_posts_bi SET frTitle = :postTitle, frDesc = :postDesc, frContents= :postCont WHERE postID = :postID') ;


				//insert into database
				$stmt->execute(array(
					'postTitle' => $postTitle,
					'postDesc' => $postDesc,
					'postCont' => $postCont,
					'postID' => $postID
				));

				//redirect to index page
				header('Location: index.php?action=updated');
				exit;

			} catch(PDOException $e) {
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

	echo "Getting row for postID: ", $_GET['id'], '<br>';
	try {

		$stmt = $db->prepare('SELECT * FROM blog_posts_bi WHERE postID = :postID');
		$stmt->bindParam(':postID', $_GET['id']);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'BlogEntry');
		$row = $stmt->fetch();
		var_dump($row);

	} catch(PDOException $e) {
	    echo $e->getMessage();
	}	

	// THis is needed to call a function from a Heredoc
	// $formFunc = function($fn) {
	// 	return $fn;
	// };

	print <<< END

	<form method='post' action="{$hereFunc(htmlspecialchars($_SERVER['PHP_SELF']))}" > 

		<input type='hidden' name='postID' value='{$row->getPostId()}'/>

		<p><label>Title</label><br />
		<input type='text' name='postTitle' value='{$row->getTitle($language)}'>
		</p>

		<p><label>Description</label><br />
		<textarea name='postDesc' cols='60' rows='10'>
			{$row->getDescription($language)}
			</textarea></p>

		<p><
			label>Content</label><br />
			<textarea name='postCont' cols='60' rows='10'>
				{$row->getContents($language)}
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
