<div class="page-wrapper">
        <!-- HEADER MOBILE-->
        <?php include('./partials/_header-mobile.php'); ?>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <?php include('./partials/_sidebar.php'); ?>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <?php include('./partials/_header.php'); ?>
            <!-- HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid" id="page_load">
    <?php
        include ("./pages/".$fist_page.".php");
    ?>                       
                    </div>
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>

    </div>
    <script>
    $.datepicker.setDefaults(
  $.extend(
    {'dateFormat':'yy-mm-dd'},
    $.datepicker.regional['<?=strtolower($_SESSION["lng"]);?>']
  )
);
</script>
    