<?@session_start();?>
<? 

include ('../config.php');
//include ('class/SQL.php');



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

$_SESSION ['lng'] = $lng;

if (!isset($_LNG))
	include ('../lng/'.$lng.'.php');

	

if (@$_SESSION ["RULES"] ["index_transactions"]==true) { ?>
<h3 class="title-5 m-b-35" id='index_transactions'><?=$_LNG ['PAYMENTS'];?></h3>
<?}?>
<div class="table-data__tool" id="main_div" style="font-size: 14px; font-weight: 300;">
    <div class="table-data__tool-left">
    <form id="transacSearchForm">
	
	
	<?php
	
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);





ini_set('log_errors', 'On');
ini_set('error_log', $error_logs_path.'php_LoadPage_errors.log');


	
	$MakeSQL = new SQL();

$MakeSQL->Connect($host, $user_base_login, $user_base_pass, $user_base);

/*$GetMerchantList = $MakeSQL->SQLQuery ("SELECT a.merchant_id as 'merchant_id', b.merchant_id as 'merchant'  FROM user_merchant a 

LEFT JOIN merchant b ON b.id = a.merchant_id

WHERE a.user_id = ".$_SESSION['user_id']);*/

	
	?>
	
	<script>
	var status_max_length = 0;
	
	function isLoad (InpData)
	{
		if (InpData.cmd=="MakeSign")
		{
			
			table.ajax.reload();
			
		}
		console.log(InpData);
		
	}
	
	function GetSearchType()
	{
		
		if (document.getElementById("searchParamList").value=='status')
		{		
			document.getElementById("search-txt").style.display='none';
			document.getElementById("search-status").style.display='block';
						
			document.getElementById("txt_search").style.width =(status_max_length*document.getElementById("main_div").style.fontSize.split('px')[0]/3)+'px';
			
			
		}
		else
		{
			document.getElementById("search-txt").style.display='block';
			document.getElementById("search-status").style.display='none';		
		}
		
		
	}
	
	function LocalCall()
	{
		
		 document.getElementById("context-menu").style.display = 'none';
		
	}
	
	</script>
	
  <!--  <div class="rs-select2--light rs-select2--md">
	
	
	
	
	
            <select class="js-select2" name="merchant_id" id="merchant_id">
                <option value="0" selected="selected"><?=$_LNG ['ALL_MERCHANTS'];?></option>
				
				<?php 
				
				if (gettype(@$GetMakeReq)!="integer")
											{
														  
													 
			//	foreach ($GetMerchantList as $MerchantList) {
				?>
				<option value="<?=@$MerchantList["merchant_id"];?>"><?=@$MerchantList["merchant"];?></option> 
                             
											<? //}  
											
}?>				
            </select>
			
            <div class="dropDownSelect2"></div>
        </div> 
		-->
        <div class="rs-select2--light rs-select2--md" id='src'>
            <select class="js-select2" name="searchParam" id="searchParamList" onchange='GetSearchType();'>               
                <option value="amount">Amount</option>              
                <option value="ID">ID</option>              
                <option value="TransferUID">TransferUID</option>
                <option value="status"><?=$_LNG ['STATUS'];?></option>
            </select>
            <div class="dropDownSelect2"></div>
        </div>
		
		
        <div class="rs-select2--light rs-select2--lg" id='txt_search'>
		   
<span id='search-txt'>
		   <input id="search-input" name="search" type="text" class="form-control" aria-required="true"
                aria-invalid="false" value="" placeholder="<?=$_LNG ['SEARCHPARAM'];?>">
</span>

<?

$GetStatusList = $MakeSQL->SQLQuery ("SELECT a.name, a.opis FROM ref_status_up a ");

$i=0;

 foreach ($GetStatusList as $StatusList)	
 {
		$GetStatusList_next [$i] = $StatusList;
 $i++;
 }
?>


	
		
				
		<span id='search-status' style='display:none'>
		<select class="js-select2" name="search-select" id="search-select" tabindex="-2" aria-hidden="true"> 
				<option value="0">Виберіть статус</option>
				<?
				

					foreach ($GetStatusList_next as $StatusList)	{ ?>
			
				<?
					
					
					?>
						<option value="<?=$StatusList["name"];?>"><?=$StatusList["opis"];?></option>
						
						<script>
						
						if (<?=strlen($StatusList["opis"]);?> > status_max_length) status_max_length = <?=strlen($StatusList["opis"]);?>;
						
						</script>
						
				<?} 	?>
				
				
		</select>
		<div class="dropDownSelect2"></div>
		</span>		
				
		</div>
		
		<?
		$MakeSQL->Close();
		?>
		
	

		
        
        <div class="rs-select2--light rs-select2--md">
            <input type="text" name="datepicker" class="datepicker form-control" id="datepickerStart"
                name="datepickerStart" data-date-format="yyyy-mm-dd" placeholder="<?=$_LNG ['FROM'];?>"
                autocomplete="off">
        </div>
        <div class="rs-select2--light rs-select2--md">
            <input type="text" name="datepicker" class="datepicker form-control" id="datepickerEnd" name="datepickeEnd"
                data-date-format="yyyy-mm-dd" placeholder="<?=$_LNG ['TO'];?>" autocomplete="off">
        </div>

        <button class="au-btn-filter ">
            <i class="fas fa-search"></i><?=$_LNG ['FIND'];?></button>         
    </div>
    </form>
</div>
<div style="font-size: 14px; font-weight: 300;">
    <table id="transactionTable" class="table table-striped table-bordered" style="width:100%; ">
        <thead>
			<th class="th-title">Amount</th>
            <th class="th-title">Amount_opl</th>
			<th class="th-title">Status</th>
            <th class="th-title">TransferUID</th>			
			<th class="th-title">ID</th>
			
            <th class="th-title">CCY</th>
            <th class="th-title">CCY_OPL</th>			
			<th class="th-title">Order DateTime</th>


            <th class="th-title">Recipient FName</th>
            <th class="th-title">Recipient LName</th>
            <th class="th-title">Recipient MName</th>
			<th class="th-title">Recipient City</th>
            <th class="th-title">Sender FName</th>			
            <th class="th-title">Sender LName</th>
            <th class="th-title">Sender Сountry</th>
			<th class="th-title">Sender City</th>
            
			
			
			<th class="th-title">PostOfficeZip</th>
            <th class="th-title">PostOfficeZipName</th>

            <th class="th-title">TransferID</th>
			<th class="th-title">Recipient Street</th>
			<th class="th-title">Recipient Zip</th>
			<th class="th-title">Sender Street</th>
			<th class="th-title">Category</th>

			
        </thead>
    </table>
</div>
<script>
    const url = "modules/LoadData.php"
	
	let CurBatchId = 0;
	let status_list = new Array();
  
    function formatCurrency(num) {
        var xx = new Intl.NumberFormat('uk-UK', {
            style: 'currency',
            currency: 'UAH',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        return xx.format(num);
    }

    var table = $('#transactionTable').DataTable({
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-right'B>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        //   dom: 'Bfrtip',
        //     "dom": 'Brfltip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Transactions',
                text: '<span style="font-size: 12px;"><i class="fas fa-lg fa-file-download mr-1"></i> Excel</span>',
                exportOptions: {
                    orthogonal: 'export',
                }

            },
            {
                extend: 'csvHtml5',
                title: 'Transactions',
                text: '<span style="font-size: 12px;"><i class="fas fa-lg fa-file-download mr-1"></i> CSV</span>',
                exportOptions: {
                    orthogonal: 'export',
                }
            },
        ],
        processing: true,
        serverSide: false,
        language: {
            url: `i18n/dataTable/<?= $_SESSION['lng'];?>.json`,            
        },       

        lengthMenu: [[50, 200, 500, 1000, -1,], [50, 200, 500, 1000, '<?=$_LNG ['ALL'];?>']],
        autoWidth: false,
        searching: true,
        responsive: true,
		ajax: {
            url: url,
            type: "POST",
            contentType: 'application/json',           
			dataSrc:function (d) {
				
				for (i = 0; i<=d.data.length-1; i++)
					status_list [i]	 = d.data[i]["status"];
			
				
                return d.data;
                 },
			error: function (xhr, error, code)
            {
                console.log(xhr["responseText"]);             
            },
            data: function (d) {             
                
                var merchantID = $("#merchant_id option:selected").val();
                var dateFrom = $('#datepickerStart').val();
                var dateTo = $('#datepickerEnd').val();
                var searchParam = $("#searchParamList option:selected").val();
				var searchValue;
				
				if (document.getElementById("search-txt").style.display=='none')
					searchValue = $('#search-select').val();
				else
					searchValue = $('#search-input').val();
                
                var searchTranReq = {}

                searchTranReq['cmd'] = "transactions";
                searchTranReq['lng_id'] = '<?=$lng;?>';                    
               
                searchTranReq['merchant_id'] = "";
                searchTranReq['DateFrom'] = dateFrom;
                searchTranReq['DateTo'] = dateTo;

                if (typeof dateFrom == 'undefined' || dateFrom == "") {
                    delete searchTranReq.DateFrom
                }

                if (typeof dateTo == 'undefined' || dateTo == "") {
                    delete searchTranReq.DateTo
                }
                if (typeof merchantID !== 'undefined') {
                    searchTranReq['merchant_id'] = merchantID;
                }

                if (searchParam !== "" && searchValue !== "") {
                    if (searchParam == "amount") {
                        searchTranReq[searchParam] = parseFloat(searchValue.replace(' ', '')) * 100
                    } else {
                        searchTranReq[searchParam] = searchValue;
                    }
                }               
               
                
                return JSON.stringify(searchTranReq)
            }
			
        },
		rowId: 'GetTest',
					"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
 
$(nRow).css('color', aData["status_color"]);

$(nRow).on('contextmenu', function(e){


var row = table.row($(this)).data();
//status_list

/*
<? if ($_SESSION ["group"]!=2) { ?>
if (row["status"]!=6 && row["status"]!=4)
{
<?} else {?>	
console.log (row);
if ((row["status"]==3  && row["SENDER_CODE"]==0) || row["status"]==4 )
{
<?}?>
      CurBatchId = row['BATCH_ID'];
	  
  var  left = getMouseX();
  var  top  = getMouseY();
  
  
  document.getElementById("context-menu").style.top = top+'px';
  document.getElementById("context-menu").style.left = left+'px';
  document.getElementById("context-menu").style.display = 'block';
}*/

     
     return false;
});
},
        deferRender: true,
        columns: [

						 {
                data: 'Amount', render: function (data, type, row) {
                    return type === 'export' ?
                        data.replace(/[$,]/g, ''):
                        formatCurrency(parseFloat(data));
                }
            },
			
			 {
                data: 'AmountOpl', render: function (data, type, row) {
                    return type === 'export' ?
                        data.replace(/[$,]/g, ''):
                        formatCurrency(parseFloat(data));
                }
            },
		    { "data": "up_status" },
			
            { "data": "TransferUID" },
            { "data": "ID" },
			
			            { "data": "ccy" },
            { "data": "ccy_opl" },
			{ "data": "OrderDateTime" },
            
            { "data": "rFName" },
            { "data": "rLName" },
            { "data": "rMName" },
            { "data": "rCity" },
            { "data": "sFName" },
            { "data": "sLName" },
            { "data": "sСountry" },
            { "data": "sCity" },
			

			
			
			{ "data": "PostOfficeZip" },
            { "data": "PostOfficeZipName" },

       //     { "data": "send_status" },
            { "data": "TransferID" },
            { "data": "sStreet" },
            { "data": "rStreet" },
            { "data": "rZip" },
            { "data": "Category" }
			
        ],

    });
	



    table.on('xhr', function () {
        var data = table.ajax.json();        
        table.columns.adjust()
        table.responsive.recalc();      
    });

    $('#datepickerStart').datepicker({ dateFormat: 'yy-mm-dd' }).val();
    $('#datepickerEnd').datepicker({ dateFormat: 'yy-mm-dd' }).val();
    $("#datepickerStart").datepicker("setDate", new Date());
    $("#datepickerEnd").datepicker("setDate", new Date());

    $('#transacSearchForm').submit(function (e) {
        e.preventDefault();                    
        $('#transactionTable').DataTable().ajax.reload();
    });

    $(document).ready(function () {
        $('#transactionTable').DataTable()
        $("#transactions_page").addClass("active");                  
    });
	





</script>




