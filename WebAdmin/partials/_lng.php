						<?php 
								foreach ($_SESSION ["LNG_ARR"] as $key=>$val) { 								
								if ($lng==$key) {
								?>	
								<a class="nav-link dropdown-toggle" href="#" id="dropdown09" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">								
								<span class="flag-icon flag-icon-<?=$key;?>"> </span> <?=$key;?></a>
								<? } else {?>
								
                            <div class="dropdown-menu" aria-labelledby="dropdown09">
                                <a class="dropdown-item" href="?lng=<?=$key;?>"><span class="flag-icon flag-icon-<?=$key;?>"> </span> <?=$key;?></a>                                
                            </div>							
								<? }
								
								}?>