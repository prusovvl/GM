<?php
@session_start();
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

if ($_SESSION ["user_type"]!=1)
{
				$Result ['code'] = -1;
			$Result ['msg'] = "Доступ заборонено";
			echo json_encode($Result);
			
exit(0);
}

if (!isset($_GET['lng']))
	$lng = $_SESSION ['lng'];
else
	$lng = $_GET['lng'];

$_SESSION ['lng'] = $lng;

if (!isset($_LNG))
	include ('../lng/'.$lng.'.php');

$GetData = json_decode(file_get_contents('php://input'), true);

							$MakeSQL = new SQL();

$MakeSQL->Connect($host, $user_base_login, $user_base_pass, $user_base);


$GetCcyBalance = $MakeSQL->SQLQuery ("SELECT a.ccy, a.id AS 'ccy_id', b.amount FROM ref_ccy a 
LEFT JOIN balance b on b.ccy_id = a.id  AND b.company_id = ".htmlspecialchars($GetData['cmp_id'])." where a.is_show = 'Y'");


$MakeSQL->Close();	

?>


 <table summary="This table shows how to create responsive tables using RWD-Table-Patterns' functionality" class="table table-bordered table-hover">



          <thead>
            <tr>
              <th>Валюта</th>
              <th data-priority="1">Баланс</th>
			  			  <? if ($_SESSION ["user_type"]==1) {?>
              <th data-priority="1">Зміни</th>
			  <? } ?>
            </tr>
          </thead>
          <tbody>

<script>

var ccy_list = new Array();

function BallUpd(x)
{
	ccy_list.push (x.id);
	
	document.getElementById('bal_upd').removeAttribute("disabled");
	
	

	
}

</script>

		

		<?
		foreach ($GetCcyBalance as $CcyBalance)
		{
			$can_edit = '';
			
			if ($_SESSION ["user_type"]!=1)
				$can_edit = 'disabled';
			
				
			
			?>
		
		            <tr>
              <td><?=$CcyBalance ['ccy'];?></td>
              <td><input type='text' value='<?=@round($CcyBalance ['amount']/100, 2);?>' disabled></td>
			  <? if ($_SESSION ["user_type"]==1) {?>
			  <td><input type='text' id='<?=$CcyBalance ['ccy'];?>' onkeypress='BallUpd(this)'></td>
			  <?}?>
			  
            </tr>
		

		<?}?>
		</tbody>
	        </table>
      </div><!--end of .table-responsive-->
          
		  
		  <style>
		  td {
    padding: 5px; /* Поля вокруг текста */ 
    border: 0px solid; /* Граница вокруг ячеек */ 
   }
   
   .button1 {
  background-color: white;
  color: black;
  border: 1px solid #000000;
  width:100px;  
}

button:disabled,
button[disabled]{
  border: 1px solid #999999;
  background-color: #cccccc;
  color: #666666;
}
   
   
  </style>
	
	<script>
	function UpdBalance()
	{
		
		if (document.getElementById('type').value=="0")
		{
			
						$.toast({
    heading: LOCALIZATION [CUR_LNG].ERROR_TITLE,
    text: LOCALIZATION [CUR_LNG].SET_TYPE,
    showHideTransition: 'plain',
	hideAfter: 5000,
	stack: 4,
	position: 'bottom-right',
    icon: 'error'
});	
			
			return;
		}
		
		if (document.getElementById('comment').value.trim()=="")
		{
			
						$.toast({
    heading: LOCALIZATION [CUR_LNG].ERROR_TITLE,
    text: LOCALIZATION [CUR_LNG].SET_COMMENT,
    showHideTransition: 'plain',
	hideAfter: 5000,
	stack: 4,
	position: 'bottom-right',
    icon: 'error'
});	
			
			return;
		}
		
		var SendData = new Object();
		SendData ['ccy'] = new Object();
		
		for (var i = 0; i<=ccy_list.length-1; i++)
			SendData ['ccy'][ccy_list[i]] = Math.round(parseFloat(document.getElementById(ccy_list[i]).value.replace(",", "."))*100);
		
		
		SendData ['type'] = document.getElementById('type').value;
		SendData ['comment'] = document.getElementById('comment').value;
		SendData ['cmp_id'] = <?=htmlspecialchars($GetData['cmp_id']);?>;
		
		
			  SendData.cmd = "EditBalance";

  		
		   GLOBAL_DATA.IsBlock = false;
		   SendReq (SendData);
		
			//console.log (JSON.stringify(SendData));
		
	} 
	
	
	</script>
	
	
<table style='border-collapse: separate;'>	

<tr>

<td style='width:200px;'>

	  <select id='type' style='width:200px;'>

<option value='0'>Тип операції
<option value='2'>Списання
<option value='3'>Зарахування

</select>

</td>

<td> Коментар: </td> <td> <input type='text' id='comment'>  </td>

<td> 
  <button class='button1' onclick='UpdBalance()' id='bal_upd' disabled>Оновити</button>
 </td>

</tr>

</table>	