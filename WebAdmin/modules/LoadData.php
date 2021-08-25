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

include ('../lng/'.$lng.'.php');

ini_set('log_errors', 'On');
ini_set('error_log', $error_logs_path.'php_LoadPage_errors.log');

$GetData = json_decode(file_get_contents('php://input'), true);

//include ("sql/".$GetData["cmd"].".php");



$MakeSQL = new SQL();

$MakeSQL->Connect($host, $user_base_login, $user_base_pass, $user_base);

/*$GetSeeList = $MakeSQL->SQLQuery ($QUERY["SEE_LIST"]);

$SeeListAdd="";

		foreach ($GetSeeList as $SeeList)
		{
			if(count(@$SeeList)==0) break;
			
			$SeeListAdd.=$SeeList['see_id'].",";			
			
		}
		


$SeeListAdd = str_replace (",--","",$SeeListAdd."--");*/



include ("sql/".$GetData["cmd"].".php");
$GetMakeReq = $MakeSQL->SQLQuery ($QUERY["MAKE_REQ"]);
//err_log("error", $QUERY["MAKE_REQ"], "");

//echo $QUERY["MAKE_REQ"];

$data = array();

if ($GetMakeReq->num_rows==0) {
	$data["data"]=[];
	echo json_encode($data);
	$MakeSQL->Close();
	exit;							}
									
									$MakeSQL->Close();
									

$index=0;
foreach ($GetMakeReq as $MakeReq)
{
$data["data"][$index]=$MakeReq;
$index++;
}
	
	
	
	echo json_encode($data);


?>