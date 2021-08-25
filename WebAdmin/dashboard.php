<?php
session_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

include ('config.php');
include ('class/SQL.php');

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



//print_r($_SESSION);

include ('lng/'.$lng.'.php');

ini_set('log_errors', 'On');
ini_set('error_log', $error_logs_path.'php_DSHB_errors.log');

$fist_page = "transactions";


?>
<!DOCTYPE html>
<html lang="ua" onclick='LocalCall();'>

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    

    <!-- Title Page-->
    <title>Dashboard</title>

 

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
     <!-- Fonts -->
   
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Flags CSS-->
    <link href="css/flag-icon.min.css" rel="stylesheet" media="all">
    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">
    <link href="vendor/datatables/datatables.min.css" rel="stylesheet" media="all">
    <link href="vendor/jquery-ui/jquery-ui.min.css" rel="stylesheet" media="all">
	



    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">
	<link rel='stylesheet' href='css/toast.css'>
	<link rel='stylesheet' href='css/loader.css'>
	<link rel='stylesheet' href='css/remodal.css'>
	<link rel='stylesheet' href='css/remodal-default-theme.css'>
	<link rel='stylesheet' href='css/jquery-confirm.min.css'>
   

</head>

<body>  

	<div id="overlay">
			<div id="overlay_text"><div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>
			<div id="overlay_text_info"></div>
	</div>

    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <script src="vendor/jquery-ui.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/datatables/datatables.min.js"></script>
    <script src="vendor/inputmask/inputmask.min.js">
    </script>
    <script src="vendor/inputmask/jquery.inputmask.min.js">
    </script>
    <script src="vendor/slick/slick.min.js">
    </script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/jquery-ui/datepicker-ua.min.js">
    </script>
<?php include('./partials/_layout.php'); ?>
    <!-- Main JS-->
    <script src="js/main.js"></script>
    <script src="js/functions.js"></script>
	
	  	<script>
			CUR_LNG = '<?=$lng;?>';	
		</script>
	
	<script src="js/toast.js"></script>
	<script src="js/locali.js"></script>	
	 <script src="js/remodal.js"></script>
	 <script src="ajax/SendReq.js"></script>
	 
	 <script src="js/jquery-confirm.min.js"></script>
	
   
	



    <script>
	
	var MouseX = null;
	var MouseY = null;
    
document.addEventListener('mousemove', onMouseUpdate, false);
document.addEventListener('mouseenter', onMouseUpdate, false);
    
function onMouseUpdate(e) {
  MouseX = e.pageX;
  MouseY = e.pageY;
  
  //console.log(MouseX + "---" + MouseY);
  //646---344
    
}

function getMouseX() {
  return MouseX;
}

function getMouseY() {
  return MouseY;
}
	
	

		



</script>


<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="context-menu" style='position: absolute;'>
  <a class="dropdown-item" href="#" onclick='SignMake();'><?=$_LNG ['MAKE_SIGN'];?></a>
</div>
	

</body>



</html>
<!-- end document-->
