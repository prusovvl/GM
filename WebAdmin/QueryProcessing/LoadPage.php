<?php

session_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

include ('../config.php');
include ('../class/SQL.php');

if (@$_SESSION ["IS_AUTH"]!=true)
{
	echo "auth_error";
	
	ob_flush();
    flush();
	sleep(5);
	redirect("index.php");
	ob_end_flush();
	exit;
}

if (!isset($_GET['lng']))
	$lng = $_SESSION ['lng'];
else
	$lng = $_GET['lng'];

include ('lng/'.$lng.'.php');

ini_set('log_errors', 'On');
ini_set('error_log', $error_logs_path.'php_LoadPage_errors.log');

$GetData = json_decode(file_get_contents('php://input'), true);

include ("../pages/".$GetData["page"].".php");

?>