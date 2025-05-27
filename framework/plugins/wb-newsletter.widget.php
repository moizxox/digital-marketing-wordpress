<?php

class WB_Newsletter extends WP_Widget {

	public function __construct() {
		$widget_ops  = array(
			'classname' => 'newsletter',
			'description' => __('Newsletter', 'wb')
		);

		parent::__construct('wb-newsletter', __('Newsletter (WOOBRO)', 'wb'), $widget_ops, array(
			'width'  => 400,
			'height' => 350
		));
	}

	public function widget($args, $instance) {
		$title = !empty($instance['title']) ? $instance['title'] : '';
		$slogan = !empty($instance['slogan']) ? $instance['slogan'] : '';
		$newsletter_code = !empty($instance['newsletter_code']) ? $instance['newsletter_code'] : '';

		if (!$newsletter_code) {
			return;
		}

		?>
		<?php if (is_page_template('page-templates/homepage.php')) : ?>
			<div class="subscribe bdrs-5">
				<div class="row">
					<div class="col-lg-6">
						<div class="subscribe__info">
							<?php if ($title) : ?>
								<h2 class="subscribe__title"><?php echo $title; ?></h2>
							<?php endif; ?>
							<?php if ($slogan) : ?>
								<div class="subscribe__text"><?php echo $slogan; ?></div>
							<?php endif; ?>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="subscribe-form__wrap">
							<?php echo $newsletter_code; ?>
						</div>
					</div>
				</div>
			</div>
		<?php else : ?>
			<div class="subscribe bdrs-5 subscribe_sidebar">
				<div class="subscribe__info">
					<?php if ($title) : ?>
						<h2 class="subscribe__title"><?php echo $title; ?></h2>
					<?php endif; ?>
					<?php if ($slogan) : ?>
						<div class="subscribe__text"><?php echo $slogan; ?></div>
					<?php endif; ?>
				</div>
				<div class="subscribe-form__wrap">
					<?php echo $newsletter_code; ?>
				</div>
			</div>
		<?php endif; ?>
		<?php

		wp_enqueue_script('mc-validate', WB_THEME_URL . '/js/mc-validate.js');
	}

	public function update($new_instance, $old_instance) {
		$new_instance = wp_parse_args($new_instance, array(
			'title' => '',
			'slogan' => '',
			'newsletter_code' => ''
		));

		$instance = $old_instance;

		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['slogan'] = sanitize_text_field($new_instance['slogan']);
		$instance['newsletter_code'] = $new_instance['newsletter_code'];

		return $instance;
	}

	public function form($instance) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => '',
				'slogan' => '',
				'newsletter_code' => ''
			)
		);

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wb'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('slogan'); ?>"><?php _e('Slogan:', 'wb'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('slogan'); ?>" value="<?php echo esc_attr($instance['slogan']); ?>" class="widefat" id="<?php echo $this->get_field_id('slogan'); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('newsletter_code'); ?>"><?php _e('Newsletter Code:', 'wb'); ?></label>
			<textarea name="<?php echo $this->get_field_name('newsletter_code'); ?>" rows="5" cols="20" class="widefat" id="<?php echo $this->get_field_id('newsletter_code'); ?>"><?php echo $instance['newsletter_code']; ?></textarea>
		</p>
		<?php
	}

}
