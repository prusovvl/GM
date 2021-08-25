<aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="?page=index">
                    <img src="images/icon/logo.png" alt="" />
                </a>
                <a href="#" class="ml-3">
                    <img src="images/icon/logo-2.png" alt="" />
                </a>
                <p class="ml-2"></p>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
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
                </nav>
            </div>
        </aside>