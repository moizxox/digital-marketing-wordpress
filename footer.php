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
							<div class="footer-bottom">
								<div class="footer-social-medial-logo">
									<div class="logo-footer">
										<?php do_action('wbcdlaf_logo'); ?>
									</div>
									<div class="footer-menu-bottom-center">
										<ul>
											<li><a href="https://digitalmarketingsupermarket.com/about/" title="About">About</a></li>
											<li><a href="https://digitalmarketingsupermarket.com/blog/" title="Blog">Blog</a></li>
											<li><a href="https://digitalmarketingsupermarket.com/contact-us/" title="Contact">Contact</a></li>
											<li><a href="https://digitalmarketingsupermarket.com/terms-and-conditions/" title="Terms and Conditions">Terms and Conditions</a></li>
											<li><a href="https://digitalmarketingsupermarket.com/privacy-policy-2/" title="Privacy Policy">Privacy Policy</a></li>
										</ul>
									</div>
									<?php do_action('wbcdg_social_links'); ?>
									<div class="clear"></div>
								</div>
							</div>
							<?php do_action('wbcdg_footer_text'); ?>
						</div>
					</div>
				</footer>
			</div>
		<?php wp_footer(); ?>
		<div class="cookie-bar" style="display: none;">
			<div class="content-cb">
				<video autoplay="" loop="" muted="" playsinline="">
					<source src="<?php echo WB_THEME_URL; ?>/images/woobro-cookie.webm" type="video/webm">
					<source src="<?php echo WB_THEME_URL; ?>/images/woobro-cookie.mp4" type="video/mp4">
				</video>
				<h3>We use Cookies</h3>
				<p>
					This website uses cookies to ensure you get the best experience on our website.
					<a href="<?php _e('/privacy-policy-2/', 'wb'); ?>" title="Pricacy Policy">Learn more.</a>
				</p>
				<a href="#" title="Accept Cookies" class="accept-cta">Accept</a>
				<span>extra fiels</span>
			</div>
		</div>
	</body>
</html>
