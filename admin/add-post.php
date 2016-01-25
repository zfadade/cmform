<?php 
require_once('../includes/config.php');
require_once('../includes/php_utils.php');
$language = setLanguage();

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Add Post</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
  <script src="../tinymce/js/tinymce/tinymce.min.js"></script>
    

  <script>
          tinymce.init({
          	 language: "fr_FR",
              selector: "textarea",
              plugins: [
                  "advlist autolink colorpicker lists link image charmap print preview anchor",
                  "searchreplace visualblocks code fullscreen",
                  "insertdatetime media table contextmenu paste"
              ],
              toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image  emoticons| save"
          });
  </script>
</head>
<body>

<div id="wrapper">

	<?php include('menu.php');?>
	<p><a href="./">Blog Admin Index</a></p>

	<h2>Add Post</h2>

	<?php

	if (isset($_POST['submit_save']) ) {
	//if form has been submitted process it
		$publishBlog = isset($_POST['submit_post']) ? true : false;

		$formData = extractFormData($_POST);

		//collect form data
		extract($formData);

		//very basic validation
		if($postTitle ==''){
			$error[] = 'Please enter the title.';
		}

		if($postDesc ==''){
			$error[] = 'Please enter the description.';
		}

		if($postCont ==''){
			$error[] = 'Please enter the content.';
		}

		if(!isset($error)) {

			insertIntoDb($db, $formData, $language);
		}

	}

	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}

	function insertIntoDb($db, $row, $language) {
	try {
		var_dump($row);
		$stmt = $language === ENGLISH ? 
				$db->prepare('INSERT INTO blog_posts_bi (enTitle,enDesc,enContents) VALUES (:postTitle, :postDesc, :postCont)') 
				:
				$db->prepare('INSERT INTO blog_posts_bi (frTitle,frDesc,frContents) VALUES (:postTitle, :postDesc, :postCont)') ;

		$stmt->execute(array(
				':postTitle' => $row['postTitle'],
				':postDesc' => $row['postDesc'],
				':postCont' => $row['postCont']
		));

		//redirect to index page
		header('Location: index.php?action=added');
		exit;

		} catch(PDOException $e) {
		    echo $e->getMessage();
			}

	}

	function extractFormData($submitData) {
		$formData = array_map( 'stripslashes', $submitData);

		// Need to do error checking, etc !
		return $formData;
	}
	?>

	<form action='' method='post'>

		<p><label>Title</label><br />
		<input type='text' name='postTitle' value='<?php if(isset($error)){ echo $_POST['postTitle'];}?>'></p>

		<p><label>Description</label><br />
		<textarea name='postDesc' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['postDesc'];}?></textarea></p>

		<p><label>Content</label><br />
		<textarea name='postCont' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['postCont'];}?></textarea></p>

		<p><input type='submit' name='submit_save' value='Save'></p>

	</form>

</div>
