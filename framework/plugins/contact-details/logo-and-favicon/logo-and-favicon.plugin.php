<?php

class Logo_And_Favicon extends WB_Plugin {

	/**
	 * @Data wbcdlaf_data default_data
	 */
	public $data;

	public function default_data() {
		return array(
			'logo' => WB_THEME_URL . '/images/logo.png',
			'retina_logo' => WB_THEME_URL . '/images/logo@2x.png',
			'admin_logo' => WB_THEME_URL . '/images/wb-admin.png',
			'favicon' => WB_THEME_URL . '/images/wb-favicon.png'
		);
	}

	/**
	 * @OptionsPage 'Contact Details - Logo And Favicon' 2
	 */
	public function options_page() {
		if (isset($_GET['restore'])) {
			delete_option('wbcdlaf_data');

			$this->data = $this->default_data();

			$this->data['success'] = __('Settings has been restored to default.', 'wb');
		}

		if (isset($_POST['submit'])) {
			update_option('wbcdlaf_data', $this->data = stripslashes_deep($_POST['data']));

			$this->data['success'] = __('Your changes have been successfully saved.', 'wb');
		}

		echo WB_Options_Page::render($this->path . '/templates/options-page.php', $this->data);
	}

	public function action_wbcdlaf_logo() {
		if (!$this->data['logo']) {
			return;
		}

		$srcset = '';

		if ($this->data['retina_logo']) {
			$srcset = 'srcset="' . $this->data['retina_logo'] . ' 2x"';
		}

		?>
		<div class="logo">
			<a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('name'); ?>">
				<img src="<?php echo $this->data['logo']; ?>" <?php echo $srcset; ?> alt="<?php bloginfo('name'); ?>">
			</a>
		</div>
		<?php
	}

	public function action_login_enqueue_scripts() {
		if (!$this->data['admin_logo']) {
			return;
		}

		?>
		<style type="text/css">
			#login h1 a {
				background-image : url('<?php echo $this->data['admin_logo']; ?>');
				padding-bottom : 30px;
			}
		</style>
		<?php
	}

	public function action_wp_head() {
		if (!$this->data['favicon']) {
			return;
		}

		?>
		<link rel="shortcut icon" href="<?php echo $this->data['favicon']; ?>">
		<?php
	}

}
