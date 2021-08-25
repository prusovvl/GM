<header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="index.html">
                            <img src="images/icon/logo.png" alt="CoolAdmin" />
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                   <ul class="list-unstyled navbar__list">
					
					<script>
					function LoadPage(toLoad)
					{
						
						var SendData = new Object();
				   SendData.cmd = "LoadPage";
				   SendData.page = toLoad;
		
				   GLOBAL_DATA.IsBlock = false;
				   SendReq (SendData);
						
					}
					</script>

                        <li id='menu_transactions'>
                            <a href="#" onclick="LoadPage('transactions');">
                            <?=$_LNG ['PAYMENTS'];?></a>
                        </li>

                        <li id='menu_transactions'>
                            <a href="#" onclick="LoadPage('EditBalance');">
                            <?=$_LNG ['CHNG_BALANCE'];?></a>
                        </li>
						
						<? if ($_SESSION ['user_type']==1) { ?>

                        <li id='menu_transactions'>
                            <a href="#" onclick="LoadPage('Turoven');">
                            <?=$_LNG ['TURNOVER'];?></a>
                        </li>
						
						<?}?>
						
                    </ul>
                </div>
            </nav>
        </header>