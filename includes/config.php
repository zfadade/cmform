<?php
ob_start();
session_start();


$my_init_data = parse_ini_file("data.ini");
$db_host = $my_init_data['cmblog_db_host'];
$db_name = $my_init_data['cmblog_db_name'];
$db_username = $my_init_data['cmblog_db_username'];
$db_password = $my_init_data['cmblog_db_password'];

$db = new PDO("mysql:host=".$db_host.";port=8889;dbname=".$db_name, 
      $db_username, 
      $db_password);
//$db = new PDO("mysql:host=".DBHOST.";port=8889;dbname=".DBNAME, DBUSER, DBPASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


//set timezone
date_default_timezone_set('America/New_York');

//load classes as needed
function __autoload($class) {

   $class = strtolower($class);

	//if call from within assets adjust the path
   $classpath = 'classes/class.'.$class . '.php';
   if ( file_exists($classpath)) {
      require_once $classpath;
	}

	//if call from within admin adjust the path
   $classpath = '../classes/class.'.$class . '.php';
   if ( file_exists($classpath)) {
      require_once $classpath;
	}

	//if call from within admin adjust the path
   $classpath = '../../classes/class.'.$class . '.php';
   if ( file_exists($classpath)) {
      require_once $classpath;
	}

}

$user = new User($db);
?>
