<?@session_start();?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="overview-wrap">
								<?if(@$_SESSION ["RULES"] ["index_dashboard"]==true) {?>
                                    <h3 class="title-1" id='index_dashboard'><?=$_LNG ['DASHBOARD'];?></h2>                                    
								<?}?>
                                </div>
                            </div>
                        </div>
                       

                        <!-- FOOTER-->
                     
                        <!-- END FOOTER -->
                        <script>
                        $( document ).ready(function() {
                        $("#index_page").addClass("active");
                        });
</script>