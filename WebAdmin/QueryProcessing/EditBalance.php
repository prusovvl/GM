<?php

session_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

include ('../config.php');
include ('../class/SQL.php');
include ("../func/globals.php");

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

if ($_SESSION ["user_type"]!=1)
{
				$Result ['code'] = -1;
			$Result ['msg'] = "Доступ заборонено";
			echo json_encode($Result);
			
exit(0);
}

ini_set('log_errors', 'On');
ini_set('error_log', $error_logs_path.'php_EditBalance_errors.log');

$GetData = json_decode(file_get_contents('php://input'), true);

//print_r($GetData);

							$MakeSQL = new SQL();

$MakeSQL->Connect($host, $user_base_login, $user_base_pass, $user_base);

$MakeSQL->autocommit(false);

foreach ($GetData['ccy'] as $ccy => $amount)
{
	
	if (trim($amount)=='' or intval ($amount)==0)
			continue;
	
	
//	echo $ccy ."---". $amount."---".$GetData['type']."---".$GetData['comment']."<br>";
	


$GetCurBalance = $MakeSQL->SQLQuery ("SELECT a.amount, a.last_upd FROM balance a WHERE a.company_id = ".htmlspecialchars($GetData ["cmp_id"])." AND a.ccy_id = (SELECT b.id FROM ref_ccy b WHERE b.ccy = '".htmlspecialchars($ccy)."')", false);

if ($GLOBALS["sql_err"])
{
	$MakeSQL->rollback();
	$MakeSQL->autocommit(true);

$MakeSQL->Close();

			$Result ['code'] = -1;
			$Result ['msg'] = "Помилка виконання запиту";
			echo json_encode($Result);
			
exit(0);
	
}

if (is_array($GetCurBalance) || is_object($GetCurBalance))
{
foreach (@$GetCurBalance as $CurBalance);

}

if (($GetData['type']=="2" or $GetData['type']=="4") and $CurBalance['amount']<$amount)
{
	
		$MakeSQL->rollback();
	$MakeSQL->autocommit(true);

$MakeSQL->Close();

			$Result ['code'] = -1;
			$Result ['msg'] = "Сума списання не може бути більше залишку по рахунку";
			echo json_encode($Result);
			
exit(0);
	
}
	

$amount_befor = 0;
$last_upd = 'NOW()';

 if (@count (@$CurBalance)>0) 
 {
	 $amount_befor = $CurBalance["amount"];
	 $last_upd = "'".$CurBalance["last_upd"]."'";
 }


$Balance_jn_result = $MakeSQL->SQLQuery ("INSERT INTO balance_jn (company_id, ccy_id, amount_befor, amount_upd, last_upd, user_upd, `type`, `comment`, upd_time) 
VALUES (".htmlspecialchars($GetData ["cmp_id"]).", (SELECT b.id FROM ref_ccy b WHERE b.ccy = '".htmlspecialchars($ccy)."'), ".$amount_befor.", ".htmlspecialchars($amount).", ".$last_upd.", ".$_SESSION ["user_id"].", 
".htmlspecialchars($GetData['type']).", '".htmlspecialchars($GetData['comment'])."', NOW())", false);


if ($Balance_jn_result=="error")
{
	$MakeSQL->rollback();
	$MakeSQL->autocommit(true);

$MakeSQL->Close();

			$Result ['code'] = -1;
			$Result ['msg'] = "Помилка виконання запиту";
			echo json_encode($Result);
			
exit(0);
	
}

if ($GetData['type']=="1" or $GetData['type']=="3")
	$Balance_result = $MakeSQL->SQLQuery ("INSERT INTO balance (company_id, ccy_id, amount, last_upd) VALUES(".htmlspecialchars($GetData ["cmp_id"]).", (SELECT b.id FROM ref_ccy b WHERE b.ccy = '".htmlspecialchars($ccy)."'), 
 ".(htmlspecialchars($amount)).", NOW()) ON DUPLICATE KEY UPDATE amount=amount+".(htmlspecialchars($amount)).", last_upd=NOW()", false);

else
		$Balance_result = $MakeSQL->SQLQuery ("INSERT INTO balance (company_id, ccy_id, amount, last_upd) VALUES(".htmlspecialchars($GetData ["cmp_id"]).", (SELECT b.id FROM ref_ccy b WHERE b.ccy = '".htmlspecialchars($ccy)."'), 
 ".(htmlspecialchars($amount)).", NOW()) ON DUPLICATE KEY UPDATE amount=amount-".(htmlspecialchars($amount)).", last_upd=NOW()", false);
	
 

if ($Balance_result=="error")
{
	$MakeSQL->rollback();
	$MakeSQL->autocommit(true);

$MakeSQL->Close();

			$Result ['code'] = -1;
			$Result ['msg'] = "Помилка виконання запиту";
			echo json_encode($Result);
			
exit(0);
	
}




}

$MakeSQL->commit();

$MakeSQL->autocommit(true);

$MakeSQL->Close();


			$Result ['code'] = 0;
			//$Result ['msg'] = "Помилка виконання запиту";
			
			echo json_encode($Result);
			

?>