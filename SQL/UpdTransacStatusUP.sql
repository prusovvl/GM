UPDATE `transactions` SET `up_status` = 'VO' WHERE ID = ${TRANSAC_ID} AND OrderDateTime BETWEEN CONCAT(DATE('${OrderDate}'), ' 00:00:00') AND CONCAT(DATE('${OrderDate}'), ' 23:59:59') AND OrderMonth = MONTH('${OrderDate}')