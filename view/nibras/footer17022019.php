		
		<div class="col-md-12 menu-footer">
			<div class="container">
				<div class="row">
					
					<div class="col-md-3 wow bounceIn order-md-2 menu-item-footer list-menu" data-wow-delay=".5s">
						<h4>Informasi</h4>
				<?php if($menuinformasi) { ?>
						<ul>
					<?php foreach($menuinformasi as $menu) { ?>
							<li><a href="<?php echo URL_PROGRAM . $menu['al'] ?>"><?php echo $menu['nm'] ?></a></li>
					<?php } ?>
						</ul>
				<?php } ?>
					</div>
					<div class="col-md-2 wow bounceIn order-md-3 menu-item-footer list-img" data-wow-delay=".5s">
						<h4>Pembayaran</h4>
				<?php if($bank) { ?>
							<ul>
				<?php foreach($bank as $b) { ?>
								<li><img src="<?php echo URL_IMAGE.'_other/other_'.$b['lgs']?>" alt="<?php echo $b['nms'] ?>" title="<?php echo $b['nms'] ?>" class="rounded d-block img-fluid img-thumbnail"></li>
				<?php } ?>
							</ul>
				<?php } ?>
					</div>
					<div class="col-md-3 wow bounceIn order-md-4 menu-item-footer list-img" data-wow-delay=".5s">
						<h4>Pengiriman</h4>
			<?php if($shipping) { ?>
						<ul>
					<?php foreach($shipping as $ship) { ?>
					    <?php if($ship['logo'] != '' && !empty($ship['logo'])) { ?>
							<li><img src="<?php echo URL_IMAGE.$ship['logo'] ?>" class="rounded img-thumbnail">
							</li>
						<?php } ?>
					<?php } // end foreach shipping ?>
						</ul>
			<?php } // end if shipping ?>
					</div>
					
					
					
					<div class="col-md-4 wow bounceIn order-md-1 menu-item-footer" data-wow-delay=".5s">
						<h4> <?php echo $config_namatoko ?></h4>
						<i class="fa fa-map-marker fa-lg" aria-hidden="true"></i> <?php echo $config_alamattoko ?><br><Br>
						<?php echo nl2br($config_openingtime) ?>
						<?php if($config_pagefb != '') { ?>
						<div class="media-sosial">
							<a href="<?php echo $config_pagefb ?>"><i class="fa fa-facebook fa-lg" aria-hidden="true"></i></a>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<footer class="footer">
			<div class="container">
				&copy; 2018 <span><?php echo $config_namatoko ?>. All Rights Reserved.</span>
			</div>
		</footer>
		
		
		<script>
			new WOW().init();
			
			$(document).ready(function() {
			
				// breakpoint and up  
				$(window).resize(function(){
					if ($(window).width() >= 980){	

					  // when you hover a toggle show its dropdown menu
					  $(".navbar .dropdown-toggle").hover(function () {
						 $(this).parent().toggleClass("show");
						 $(this).parent().find(".dropdown-menu").toggleClass("show"); 
					   });

						// hide the menu when the mouse leaves the dropdown
					  $( ".navbar .dropdown-menu" ).mouseleave(function() {
						$(this).removeClass("show");  
					  });
				  
						// do something here
					}	
				});  
			
			});
		
		
		</script>
		
		<!-- WhatsHelp.io widget -->
        <script type="text/javascript">
            (function () {
            var options = {
            whatsapp: "+6287882100300", // WhatsApp number
            company_logo_url: "//static.whatshelp.io/img/flag.png", // URL of company logo (png, jpg, gif)
            greeting_message: "Assalamu'alaikum,...", // Text of greeting message
            call_to_action: "Chat me", // Call to action
            position: "left", // Position may be 'right' or 'left'
            };
            var proto = document.location.protocol, host = "whatshelp.io", url = proto + "//static." + host;
            var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
            s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
            var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
            })();
        </script>
        <!-- /WhatsHelp.io widget -->
		
		<?php if($config_googleanalisis != '') {?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $config_googleanalisis ?>"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', '<?php echo $config_googleanalisis ?>');
		</script>
		<?php } ?>
	</body>
</html>