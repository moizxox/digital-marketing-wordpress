				<?php if (!defined('ABSPATH')) exit; ?>
				<footer class="footer">
					<div class="container">
						<div class="footer__inner">
							<?php if (is_active_sidebar('footer')) : ?>
								<div class="footer__top">
									<div class="row">
										<?php dynamic_sidebar('footer'); ?>
									</div>
								</div>
							<?php endif; ?>
							
							<div class="footer-bottom ">
								<ul>
									<li><a href="https://digitalmarketingsupermarket.com/about/" title="">About</a></li>
									<li><a href="https://digitalmarketingsupermarket.com/contact-us/" title="">Contact</a></li>
									<li><a href="https://digitalmarketingsupermarket.com/terms-and-conditions/" title="">Terms and Conditions</a></li>
									<li><a href="https://digitalmarketingsupermarket.com/privacy-policy" title="">Privacy Policy</a></li>
								</ul>
								<div class="footer-social-medial-logo">
								<div class="logo-footer">
									<?php do_action('wbcdlaf_logo'); ?>
								</div>
								<div class="social-media-footer">
									<a href="#" title=""><img src="<?php echo WB_THEME_URL; ?>/images/facebook.svg" alt="Facebook"></a>
									<a href="#" title=""><img src="<?php echo WB_THEME_URL; ?>/images/linkedin.svg" alt="Linkedin"></a>
									<a href="#" title=""><img src="<?php echo WB_THEME_URL; ?>/images/instagram.svg" alt="Instagram"></a>
								</div>
									<div class="clear"></div>
									</div>
									</div>
								
							<?php do_action('wbcdg_footer_text'); ?>
						
						</div>
					</div>
				</footer>
			</div>
		<?php wp_footer(); ?>
	</body>
</html>
