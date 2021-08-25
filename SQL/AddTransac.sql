INSERT INTO `transactions` (`company_id`, `ccy_id`, `ccy_opl_id`, `TransferUID`, `PostOfficeZip`, `WorkPlace`, `TransferID`, `sFName`, `sLName`, `sCity`, `sStreet`, `sСountry`, `rFName`, `rLName`, `rMName`, `rStreet`, `rCity`,
 `rZip`, `Amount`, `AmountOpl`, `OrderDateTime`, `OrderMonth`, `Category`, `kgp`, `kgp_num`, `add_time`, `ID`, send_status) 
 
VALUES (${company_id}, (SELECT a1.id FROM ref_ccy a1 WHERE a1.ccy = '${Currency}'), (SELECT a1.id FROM ref_ccy a1 WHERE a1.ccy = '${CurrencyOpl}'), '${TransferUID}', ${PostOfficeZip}, ${WorkPlace}, ${TransferID}, '${sFName}', 
'${sLName}', '${sCity}', '${sStreet}', '${sСountry}', '${rFName}', '${rLName}', '${rMName}', '${rStreet}', '${rCity}', ${rZip}, ${Amount}, ${AmountOpl}, '${OrderDate} ${OrderTime}', MONTH('${OrderDate}'), '${Category}', 
'${kgp}', ${kgp_num}, NOW(), ${TRANSAC_ID}, 5);
