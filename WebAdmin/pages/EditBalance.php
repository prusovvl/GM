<!-- add user modal -->
		
		<style>
		
	h2 {
  text-align: center;
  padding: 20px 0;
}

.table-bordered {
  border: 1px solid black !important;
}

table caption {
	padding: .5em 0;
}

@media screen and (max-width: 767px) {
  table caption {
    display: none;
  }
}

.p {
  text-align: center;
  padding-top: 140px;
  font-size: 14px;
}	

		</style>
		
							
							<?
							
							$MakeSQL = new SQL();

$MakeSQL->Connect($host, $user_base_login, $user_base_pass, $user_base);

	if ($_SESSION ["user_type"]!=1)
		{
$GetCcyBalance = $MakeSQL->SQLQuery ("SELECT a.ccy, a.id AS 'ccy_id', b.amount FROM ref_ccy a 
LEFT JOIN balance b on b.ccy_id = a.id  AND b.company_id = ".$_SESSION['company_id']." where a.is_show = 'Y'");
		}

		?>
		
		
		<h2>Управління балансами</h2>
		
		<center>
		
		
		<?php
		
		if ($_SESSION ["user_type"]==1)
		{
			
			$GetCompanyList = $MakeSQL->SQLQuery ("SELECT a.id, a.name FROM company a ORDER BY a.id asc");
			?>
			<div class="rs-select2--light rs-select2--md" id='src' style='width:300px;'>
			<select class="js-select2" id='CmpBalance' onChange='GetCmpBall()'>
			<option value='0' />Виберіть компанію
			<?
			foreach ($GetCompanyList as $CompanyList)
		{?>
			
			<option value='<?=$CompanyList['id'];?>' /><?=$CompanyList['name'];?>
			
		<?}?>
		
		</select>
		</div>
		
		</center>
		<?
			
		}
		
$MakeSQL->Close();	


		?>
		
		<script>
		
		function GetCmpBall()
		{
			
			if (document.getElementById('CmpBalance').value>0)
			{
					var SendData = new Object();
		SendData ['cmp_id'] = document.getElementById('CmpBalance').value;
		
	
		
		
			  SendData.cmd = "GetCmpBall";

  		
		   GLOBAL_DATA.IsBlock = false;
		   SendReq (SendData);
			}
			else
			{
				document.getElementById('CntResult').innerHTML = "";
			}
			
		}
		
		</script>
		

<div class="container">
  <div class="row">
    <div class="col-xs-12">
      <div class="table-responsive" data-pattern="priority-columns">
	  
	  <?php
	if ($_SESSION ["user_type"]!=1)
		{	
	?>
	  
	  
        <table summary="This table shows how to create responsive tables using RWD-Table-Patterns' functionality" class="table table-bordered table-hover">



          <thead>
            <tr>
              <th>Валюта</th>
              <th data-priority="1">Баланс</th>

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
			
		/*	if ($_SESSION ["user_type"]!=1)
				$can_edit = 'disabled';*/
			
				
			
			?>
		
		            <tr>
              <td><?=$CcyBalance ['ccy'];?></td>
              <td><input type='text' value='<?=@round($CcyBalance ['amount']/100, 2);?>' disabled></td>
			  

			  
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
		
		
			  SendData.cmd = "EditBalance";

  		
		   GLOBAL_DATA.IsBlock = false;
		   SendReq (SendData);
		
		//	console.log (JSON.stringify(UpdBalanceSend));
		
	}
	
	
	</script>
	
	<?php
	if ($_SESSION ["user_type"]==1) {?>
	
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
	
	
		<?
	}
		}
		else
		{?>
 	
	<div id='CntResult'></div>
	
	
		<?}?>
		  
    </div>
  </div>
</div>
		

		
		



		

     

			<!-- add user modal -->