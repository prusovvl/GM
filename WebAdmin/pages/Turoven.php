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


?>

<div class="table-data__tool" id="main_div" style="font-size: 14px; font-weight: 300;">
    <div class="table-data__tool-left">
    <form id="transacSearchForm">
	
	
	<?php
	
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);





ini_set('log_errors', 'On');
ini_set('error_log', $error_logs_path.'php_Turover_errors.log');

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
			<th class="th-title">Дата</th>

			<th class="th-title">Валюта</th>
            <th class="th-title">Компанія</th>			
			
			
            <th class="th-title">Балан на початок</th>
            <th class="th-title">Сума зарахування балансу</th>			
			<th class="th-title">Сума списання балансу</th>


            <th class="th-title">Сума списання транзакцій</th>
            <th class="th-title">Баланс на кінець</th>
			
	
            <th class="th-title">Сума повернутих</th>
			
			<th class="th-title">Код валюти</th>
			<th class="th-title">ЄДРПОУ</th>
	

			
        </thead>
    </table>
</div>


<script>
const url = "modules/LoadData.php"
	
	let CurBatchId = 0;
	//let status_list = new Array();
  
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
				
				/*for (i = 0; i<=d.data.length-1; i++)
					status_list [i]	 = d.data[i]["status"];*/
			
				
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

                
                var searchTranReq = {}

                searchTranReq['cmd'] = "turnover";
                searchTranReq['lng_id'] = '<?=$lng;?>';                    
               
                searchTranReq['DateFrom'] = dateFrom;
                searchTranReq['DateTo'] = dateTo;

                if (typeof dateFrom == 'undefined' || dateFrom == "") {
                    delete searchTranReq.DateFrom
                }

                if (typeof dateTo == 'undefined' || dateTo == "") {
                    delete searchTranReq.DateTo
                }
                     
               
                
                return JSON.stringify(searchTranReq)
            }
			
        },
		rowId: 'GetTest',
					"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
 

},
        deferRender: true,
        columns: [

						/* {
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
            },*/
		    { "data": "date" },
			
            { "data": "ccy" },
            { "data": "company_name" },
			
			{ "data": "balance_day_start" },
            { "data": "balance_add" },
			{ "data": "balance_remove" },
            
            { "data": "write_off_amount" },
            { "data": "balance_day_end" },
            { "data": "refund_amount", className: "none" },
            { "data": "ccy_code", className: "none" },
            { "data": "edrpou", className: "none" }
			
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