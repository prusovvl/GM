UPDATE `transactions` SET `up_status` = 'VO' WHERE ID = ${TRANSAC_ID} AND OrderDateTime BETWEEN DATE_SUB(CURDATE(), INTERVAL 60 DAY) AND NOW() AND OrderMonth IN (MONTH(DATE_SUB(CURDATE(), INTERVAL 60 DAY)), MONTH(CURDATE()))