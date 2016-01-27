<?php
// load Composer
require 'vendor/autoload.php';
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo _("Admin_modifier_blogue"); ?></title>
<!--   <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css"> 
  <script src="tinymce/js/tinymce/tinymce.min.js"></script>
  -->
  <script "text/javascript" src="/vendor/tinymce/tinymce/tinymce.min.js"></script>
  <script "text/javascript"  src="js/initTiny.js"></script>
</head>
<body>


  <form method='post'> 
    <center><h2>FRAN&Ccedil;AIS</h2></center>
    <p>
      <label>Titre</label>
      <br>
      <input type='text' name='frPostTitle' value=''>
    </p>
    <p>
      <label>D&eacute;scription</label>
      <br />
      <input type='text' name='frPostDesc'>
      &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    </p>

   <!--  <div class="editablediv" style="width:120px;height:30px;background:#EEE;border:1px solid #AAA;"/>
    Click here to edit!</div> -->

    <p><label>Contenu</label><br />
      <textarea name='postCont' class="frTextArea" cols='60' rows='10'>
      </textarea>
    </p>
    <br />
    <center><h2>ENGLISH</h2></center>
    <hr />
    <p>
      <label>Title</label>
      <br />
      <input type='text' name='enPostTitle' value=''>
   </p>
   <p>
      <label>Description</label>
      <br>
      <input type='text' name='enPostDesc'>
    </p>
    <p>
      <label>Contents</label><br />
      <textarea name='postCont' class="enTextArea"  cols='60' rows='10'>
      </textarea>
    </p>

    <p>
      <input type='submit' name='update' value='Update'>
    </p>

  </form>