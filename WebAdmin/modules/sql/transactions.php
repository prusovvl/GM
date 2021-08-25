<?php

//print_r($GetData);

if (@!$GetData["amount"] or !is_numeric(@$GetData["amount"])) $GetData["amount"] = 0;
if (@!$GetData["TransferUID"] or !is_numeric(@$GetData["TransferUID"])) $GetData["TransferUID"] = 0;
if (@!$GetData["ID"] or !is_numeric(@$GetData["ID"])) $GetData["ID"] = 0;
//if (@!$GetData["status"] or !is_numeric(@$GetData["status"])) $GetData["status"] = 0;

	
$QUERY["MAKE_REQ"]="SELECT b.ccy, c.ccy AS 'ccy_opl', a.TransferUID, a.PostOfficeZip, d.name AS 'PostOfficeZipName', a.rFName, a.rLName, a.rMName, a.rCity,  a.sFName, a.sLName, a.`sСountry`, a.sCity, 
 round(a.Amount/100, 2) as 'Amount', round (a.AmountOpl/100, 2) as 'AmountOpl', e.opis AS 'up_status', f.name AS 'send_status',


a.TransferID, a.sStreet,  a.rStreet,  a.rZip, a.OrderDateTime, a.Category,  a.ID 
FROM transactions a

LEFT JOIN ref_ccy b ON b.id = a.ccy_id
LEFT JOIN ref_ccy c ON c.id = a.ccy_opl_id
LEFT JOIN ref_office d ON d.id = a.PostOfficeZip
LEFT JOIN ref_status_up e ON e.name = a.up_status
LEFT JOIN ref_status f ON f.id = a.send_status 

where 

if (".htmlspecialchars(@$GetData ["amount"])."!=0, a.Amount=".htmlspecialchars(@$GetData ["amount"]).", a.Amount!=0) AND 
if (".htmlspecialchars(@$GetData ["ID"])."!=0, a.ID=".htmlspecialchars(@$GetData ["ID"]).", a.ID!=0) AND 
if (".htmlspecialchars(@$GetData ["TransferUID"])."!=0, a.TransferUID='".htmlspecialchars(@$GetData ["TransferUID"])."', a.TransferUID!='') AND "; 

if (isset($GetData ["status"]))
	$QUERY["MAKE_REQ"].=" a.up_status='".htmlspecialchars($GetData ["status"])."' AND ";

$QUERY["MAKE_REQ"].=" a.OrderDateTime BETWEEN CONCAT(DATE('".htmlspecialchars($GetData ["DateFrom"])."'), ' 00:00:00') AND CONCAT(DATE('".htmlspecialchars($GetData ["DateTo"])."'), ' 23:59:59') AND OrderMonth in (MONTH('".htmlspecialchars($GetData ["DateFrom"])."'), MONTH('".htmlspecialchars($GetData ["DateTo"])."')) ";
	
	if ($_SESSION ['user_type']!=1)
	  $QUERY["MAKE_REQ"].=" and company_id = ".$_SESSION ["company_id"];

/*$QUERY["MAKE_REQ"]="SELECT a.SENDER_IBAN,  a.RECIPIENT_IBAN,
sender.`name` AS 'SENDER_NAME', sender.edrpoy AS 'SENDER_CODE', 
recip.`name` as 'RECIENT_NAME', recip.edrpoy AS 'RECIPIENT_CODE',
round(a.AMOUNT/100,2) as 'AMOUNT', a.COUNT, a.ARG_DATE, a.ARG_NO, a.CODE_DESTINATION, a.CODE_TYPE_AMOUNT, a.ADD_TIME, a.SEND_TIME, a.BATCH_ID, 
if (a.`STATUS` = 3, CONCAT(b.`name`, ' №', (SELECT COUNT(1)+1 FROM settlement_sign_jn e WHERE e.CA_id = a.SENDER_ID AND e.batch_id = a.BATCH_ID)) , b.`name`) AS 'status_name',  b.color AS 'status_color', 
(if(c.user_id IS NULL or a.`STATUS` = 4, a.`STATUS`, 6)) as 'status' FROM settlement a 

LEFT JOIN settlement_sign_jn c ON c.batch_id = a.BATCH_ID AND c.user_id = ".$_SESSION['user_id']."
LEFT JOIN ref_status b ON b.id = (if(c.user_id IS NULL or a.`STATUS` = 4, a.`STATUS`, 6))
	
LEFT JOIN CounterAgents sender ON sender.id = a.SENDER_ID
LEFT JOIN CounterAgents recip ON recip.id = a.RECIPIENT_ID


WHERE 
if (".htmlspecialchars(@$GetData ["amount"])."!=0, a.AMOUNT=".htmlspecialchars(@$GetData ["amount"]).", a.AMOUNT!=0) AND 
if (".htmlspecialchars(@$GetData ["COUNT"])."!=0, a.COUNT=".htmlspecialchars(@$GetData ["COUNT"]).", a.COUNT!=0) AND 
if (".htmlspecialchars(@$GetData ["BATCH_ID"])."!=0, a.BATCH_ID=".htmlspecialchars(@$GetData ["BATCH_ID"]).", a.BATCH_ID!=0) AND "; 

if ($_SESSION ["group"]!=2)
	$QUERY["MAKE_REQ"].="if (".htmlspecialchars(@$_SESSION ["CA_id"])."!=0, a.SENDER_ID=".htmlspecialchars(@$_SESSION ["CA_id"]).", a.SENDER_ID!=0) AND ";
else
	$QUERY["MAKE_REQ"].=" a.SENDER_ID>=0 AND ";
	
	
$QUERY["MAKE_REQ"].="if (".htmlspecialchars(@$GetData["status"])."!=0, a.STATUS=".htmlspecialchars(@$GetData["status"]).", a.STATUS!=0)

AND a.ADD_TIME BETWEEN '".htmlspecialchars($GetData["DateFrom"])." 00:00:00' AND '".htmlspecialchars($GetData["DateTo"])." 23:59:59' AND 
a.`ADD_MONTH` in (MONTH('".htmlspecialchars($GetData["DateFrom"])."'), MONTH('".htmlspecialchars($GetData["DateTo"])."'))";*/
?>