<header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap" style="font-size: 14px; font-weight: 300;">
                            <div class="form-header"></div>                           
                            <div class="header-button">
                                <div class="noti-wrap mr-2">
                                
								
								<?php include('_lng.php'); ?>
								
                                </div>
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <img src="images/icon/avatar-blank.png" alt="" />
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="#"><?=$_SESSION ["name"];?></a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="images/icon/avatar-blank.png" alt="" />
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="#"><?=$_SESSION ["fio"];?></a>
                                                    </h5>
                                                    <span class="email"><?=$_SESSION ["company_name"];?></span>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="<?=$domain_url;?>?do=exit&lng=<?=$_SESSION['lng'];?>">
                                                    <i class="zmdi zmdi-power"></i><?=$_LNG ['EXIT'];?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
<script>
        
</script>