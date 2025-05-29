<?php

class General extends WB_Plugin {

	/**
	 * @Data wbcdg_data default_data
	 */
	public $data;

	public function default_data() {
		return array(
			'facebook_link' => '',
			'linkedin_link' => '',
			'instagram_link' => '',
			'footer_text' => sprintf('tags added &copy; %d Copyright Digital Marketing Supermarket. Made by <a href="https://www.woobro.com" target="_blank">WOOBRO</a>', date('Y'))
		);
	}

	/**
	 * @OptionsPage 'Contact Details - General' 1 envelope
	 */
	public function options_page() {
		if (isset($_GET['restore'])) {
			delete_option('wbcdg_data');

			$this->data = $this->default_data();

			$this->data['success'] = __('Settings has been restored to default.', 'wb');
		}

		if (isset($_POST['submit'])) {
			update_option('wbcdg_data', $this->data = stripslashes_deep($_POST['data']));

			$this->data['success'] = __('Your changes have been successfully saved.', 'wb');
		}

		echo WB_Options_Page::render($this->path . '/templates/options-page.php', $this->data);
	}

	public function action_wbcdg_social_links() {
		?>
		<div class="social-media-footer">
			<?php if ($facebook_link = esc_url($this->data['facebook_link'])) : ?>
				<a href="<?php echo $facebook_link; ?>" target="_blank" title="Facebook">
					<img src="<?php echo WB_THEME_URL; ?>/images/facebook.svg" alt="Facebook">
				</a>
			<?php endif; ?>
			<?php if ($linkedin_link = esc_url($this->data['linkedin_link'])) : ?>
				<a href="<?php echo $linkedin_link; ?>" target="_blank" title="LinkedIn">
					<img src="<?php echo WB_THEME_URL; ?>/images/linkedin.svg" alt="LinkedIn">
				</a>
			<?php endif; ?>
			<?php if ($instagram_link = esc_url($this->data['instagram_link'])) : ?>
				<a href="<?php echo $instagram_link; ?>" title="Instagram">
					<img src="<?php echo WB_THEME_URL; ?>/images/instagram.svg" alt="Instagram">
				</a>
			<?php endif; ?>
		</div>
		<?php
	}

	public function action_wbcdg_footer_text() {
		if (!$this->data['footer_text']) {
			return;
		}

		?>
		<div class="footer__bottom">
			<div class="copyright">
				<?php echo $this->data['footer_text']; ?>
			</div>
		</div>
		<?php
	}

}
