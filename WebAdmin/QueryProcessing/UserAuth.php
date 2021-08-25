<?php
session_start ();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

include ('../config.php');
include ('../class/SQL.php');

$GetData = json_decode(file_get_contents('php://input'), true);


include ('../lng/'.$GetData ['lng'].'.php');

ini_set('log_errors', 'On');
ini_set('error_log', $error_logs_path.'php_AUTH_errors.log');


if (!isset($GetData["login"]) or !isset($GetData["pass"]))
{
			$Result ['code'] = -1;
			$Result ['msg'] = $_LNG ['PARAM_ERR'];
			echo json_encode($Result);
exit();	
}





	


$MakeSQL = new SQL();

$MakeSQL->Connect($host, $user_base_login, $user_base_pass, $user_base);

$GetUserAuth = $MakeSQL->SQLQuery ("SELECT a.company_id, a.fio, a.`status`, a.id, a.type as 'user_type', (SELECT a1.name FROM company a1 WHERE a1.id = a.company_id) as 'company_name' FROM users a WHERE a.login = '".htmlspecialchars($GetData["login"])."' AND a.password = ENCRYPT('".htmlspecialchars($GetData["pass"])."', a.password)");
		
		foreach ($GetUserAuth as $UserAuth);

$MakeSQL->Close();		

		if (count (@$UserAuth)==0)
		{
			$Result ['code'] = -1;
			$Result ['msg'] = $_LNG ['AUTH_ERR'];
			
			echo json_encode($Result);
			exit;
		}
		
		(boolean)$is_stop = false;
		
		if ((int)$UserAuth['status']!=1) {
		
	
				$Result ['msg'] = $_LNG ['USER_IS_BLOCK'];
			
			
			$is_stop = true;
		}
		
		if ($is_stop==true)
		{
			$Result ['code'] = -1;
			echo json_encode($Result);
			
			exit;
		}
		
		
		
$_SESSION ["IS_AUTH"] = TRUE;
$_SESSION ["company_id"] = (int)$UserAuth['company_id'];
$_SESSION ["user_id"] = (int)$UserAuth['id'];
$_SESSION ["fio"] = $UserAuth['fio'];
$_SESSION ["company_name"] = $UserAuth['company_name'];
$_SESSION ["user_type"] = $UserAuth['user_type'];

$Result ['code'] = 0;
echo json_encode($Result);

//redirect("../dashboard.php");

//print_r($_SESSION);

?>