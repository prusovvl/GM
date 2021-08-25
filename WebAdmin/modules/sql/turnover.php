 <?php
 
 $QUERY["MAKE_REQ"]="
SELECT t.`date`, b.ccy, b.ccy_code, c.`name` AS 'company_name', c.edrpou, t.MIN_DATE,

 (SELECT a3.amount_befor FROM balance_jn a3 WHERE a3.upd_time = t.MIN_DATE AND a3.company_id = t.company_id AND a3.ccy_id = t.ccy_id ORDER BY a3.id desc LIMIT 0, 1) AS 'balance_day_start',

(SELECT sum(a5.amount_upd) FROM balance_jn a5 WHERE a5.upd_time between t.MIN_DATE AND t.MAX_DATE AND a5.company_id = t.company_id AND a5.ccy_id = t.ccy_id AND a5.`type` = 3) AS 'balance_add',

(SELECT SUM(a6.amount_upd) FROM balance_jn a6 WHERE a6.upd_time between t.MIN_DATE AND t.MAX_DATE AND a6.company_id = t.company_id AND a6.ccy_id = t.ccy_id AND a6.`type` = 2) AS 'balance_remove',

if (t.ccy_id = 1, 
(SELECT sum(b1.Amount) FROM transactions b1 WHERE b1.add_time BETWEEN DATE(t.MIN_DATE) AND concat(DATE(t.MAX_DATE), ' 23:59:59') AND b1.company_id = t.company_id AND b1.ccy_id = t.ccy_id AND b1.send_status IN (7, 6) ), 
(SELECT SUM(b2.AmountOpl) FROM transactions b2 WHERE b2.add_time BETWEEN DATE(t.MIN_DATE) AND concat(DATE(t.MAX_DATE), ' 23:59:59') AND b2.company_id = t.company_id AND b2.ccy_opl_id = t.ccy_id AND b2.send_status IN (7, 6) )
) AS 'write_off_amount',

t.ccy_id,


(SELECT if ((a4.`type` = 5 OR a4.`type` = 3), a4.amount_befor + ifnull(a4.amount_upd,0), 

if ((a4.`type` = 4 OR a4.`type` = 2), 
ifnull(a4.amount_befor,0) - ifnull(a4.amount_upd,0), a4.amount_befor)


) FROM balance_jn a4 WHERE a4.upd_time = t.MAX_DATE AND a4.company_id = t.company_id AND a4.ccy_id = t.ccy_id ORDER BY a4.id desc LIMIT 0, 1) AS 'balance_day_end',


if (t.ccy_id = 1, 
(SELECT sum(c1.Amount) FROM transactions c1 WHERE c1.add_time BETWEEN t.MIN_DATE AND t.MAX_DATE AND c1.company_id = t.company_id AND c1.ccy_id = t.ccy_id AND c1.send_status = 8), 
(SELECT SUM(c2.AmountOpl) FROM transactions c2 WHERE c2.add_time BETWEEN t.MIN_DATE AND t.MAX_DATE AND c2.company_id = t.company_id AND c2.ccy_opl_id = t.ccy_id AND c2.send_status = 8 )
) AS 'refund_amount',



 t.MAX_DATE FROM (

SELECT substr(a.upd_time, 1, 10) AS 'date', 

(SELECT MIN(a1.upd_time) FROM balance_jn a1 WHERE SUBSTR(a1.upd_time, 1, 10) = substr(a.upd_time, 1, 10) AND a1.company_id = a.company_id AND a1.ccy_id = a.ccy_id) AS 'MIN_DATE',
(SELECT MAX(a2.upd_time) FROM balance_jn a2 WHERE SUBSTR(a2.upd_time, 1, 10) = substr(a.upd_time, 1, 10) AND a2.company_id = a.company_id AND a2.ccy_id = a.ccy_id) AS 'MAX_DATE',

 a.company_id, a.ccy_id FROM balance_jn a WHERE substr(a.upd_time, 1, 10) between '".htmlspecialchars($GetData ["DateFrom"])."' and CONCAT('".htmlspecialchars($GetData ["DateTo"])."', ' 23:59:59')  

 GROUP BY a.company_id, a.ccy_id, substr(a.upd_time, 1, 10)
 ORDER BY substr(a.upd_time, 1, 10), a.company_id, a.ccy_id ASC
 
 ) t
 
 LEFT JOIN ref_ccy b ON b.id = t.ccy_id
 LEFT JOIN company c ON c.id = t.company_id";
 
 
 ?>
 
 
 
 