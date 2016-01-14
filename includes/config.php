<?php
ob_start();
session_start();

$my_init_data = parse_ini_file("data.ini");
$db_host = $my_init_data['cmblog_db_host'];
$db_name = $my_init_data['cmblog_db_name'];
$db_username = $my_init_data['cmblog_db_username'];
$db_password = $my_init_data['cmblog_db_password'];

// port=8889;  the default configuration for MAMP uses ports 8888 and 8889, and 7888
$db = new PDO("mysql:host=".$db_host.";dbname=".$db_name, 
      $db_username, 
      $db_password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


//set timezone
date_default_timezone_set($my_init_data['default_timezone']);

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
