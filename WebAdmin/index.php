<? session_start(); ?>
<!DOCTYPE html>
<html lang="uk">

<? 


ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

include ('config.php');
include ('class/SQL.php');

ini_set('log_errors', 'On');
ini_set('error_log', $error_logs_path.'php_INDEX_errors.log');


	$lng = "UA";



include("lng/".$lng.".php"); 

if (@$_GET['do']=='exit')
{
	session_destroy();
	session_start();
}

$_SESSION ['lng'] = $lng;


/*$MakeSQL = new SQL();

$MakeSQL->Connect($host, $user_base_login, $user_base_pass, $user_base);

$GetLng = $MakeSQL->SQLQuery ("SELECT a.name, a.sh_name, a.id FROM ref_lng a ORDER BY a.`ord` asc");
		
		foreach ($GetLng as $Lng)
			$LNG_ARR [$Lng["sh_name"]] = $Lng ["id"];
		
		$_SESSION ["LNG_ARR"] = $LNG_ARR;

$MakeSQL->Close();*/


?>

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="">
	<script src="js/functions.js"></script>
    <!-- Title Page-->
    <title><?=$_LNG ['ENTER'];?></title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
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

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">
	<link rel='stylesheet' href='css/toast.css'>
	<link rel='stylesheet' href='css/loader.css'>
	
	<script>
	CUR_LNG = '<?=$lng;?>';	
	</script>

</head>
	<div id="overlay">
			<div id="overlay_text"><div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>
			<div id="overlay_text_info"></div>
	</div>
<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
            <header>
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap" style="font-size: 14px; font-weight: 300;">
                            <div class="form-header"></div>                           
                            <div class="header-button">
                                <div class="noti-wrap mr-2">                                
								
		<?php include('./partials/_lng.php'); ?>
							
                                </div>                               
                            </div>
                        </div>
                    </div>
                </div>
            </header>
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#">
                                <img src="images/icon/logo.png" alt="">
                            </a>
                        </div>
                        <div class="login-form">
                            <form action="" method="post" onsubmit="SendLogin();return false;">
                                <div class="form-group">
                                    <label><?=$_LNG ['LOGIN'];?></label>
                                    <input class="au-input au-input--full" type="tel" name="login" id="login" value="">
                                </div>
                                <div class="form-group">
                                    <label><?=$_LNG ['PASS'];?></label>
                                    <input class="au-input au-input--full" type="password" name="pass" id="pass" value="">
                                </div>
                                <div class="login-checkbox">  
                                <!--                                 
                                    <label>
                                        <a href="#"><?=$_LNG ['PASS_FORGET'];?></a>
                                    </label>
                                    -->
                                </div>
                                <button class="au-btn au-btn--block btn-primary m-b-20" type="submit"><?=$_LNG ['ENTER'];?></button>
                                
                            </form>
                            <!--
                            <div class="register-link">
                                <p>
                                    <?=$_LNG ['NO_ACCOUNT'];?>
                                    <a href="#"><?=$_LNG ['MAKE_REG'];?></a>
                                </p>
                            </div>
                            -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>			
	
	<script>
	function SendLogin()
	{
				
		if (document.getElementById("login").value.trim()=='' || document.getElementById("pass").value.trim()=='')
		{
			ShowError (LOCALIZATION [CUR_LNG].ERROR_TITLE, LOCALIZATION [CUR_LNG].EMPTY_VALUE);
		}
		else
		{
		
		var SendData = new Object();
   SendData.cmd = "UserAuth";
   SendData.login = document.getElementById("login").value.trim();
   SendData.pass = hex_md5(document.getElementById("pass").value);
  		
		   GLOBAL_DATA.IsBlock = false;
		   SendReq (SendData);
		
		//alert (JSON.stringify(SendData));
		}
		
	}
	
	</script>

    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
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
    <script src="vendor/select2/select2.min.js">
    </script>

    <!-- Main JS-->
    <script src="js/main.js"></script>
    <script src="js/md5.js"></script>
	<script src="js/toast.js"></script>
	<script src="js/locali.js"></script>	
	<script src="ajax/SendReq.js"></script>
 <script>
    $(document).ready(function () {
            $("#phone").inputmask({ "mask": tel_num_prefix+"(999) 999-99-99" });
        });

        </script>
	

</body>

</html>
<!-- end document-->