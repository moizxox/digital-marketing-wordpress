<?php

class WB_Promotion_Box extends WP_Widget {

	public function __construct() {
		$widget_ops  = array(
			'classname' => 'promotion-box',
			'description' => __('Promotion Box', 'wb')
		);

		parent::__construct('wb-promotion-box', __('Promotion Box (WOOBRO)', 'wb'), $widget_ops, array(
			'width'  => 400,
			'height' => 350
		));
	}

	public function widget($args, $instance) {
		$title = !empty($instance['title']) ? $instance['title'] : '';
		$content = !empty($instance['content']) ? $instance['content'] : '';
		$button_text = !empty($instance['button_text']) ? $instance['button_text'] : '';
		$button_url = !empty($instance['button_url']) ? $instance['button_url'] : '';
		$style = !empty($instance['style']) ? $instance['style'] : 'white';

		if (!$title) {
			return;
		}

		?>
		<?php if ($style == 'white') : ?>
			<div class="wh-info text-center">
				<h3 class="text-md wh-info__title"><?php echo $title; ?></h3>
				<div class="wh-info__desc">
					<?php echo wpautop($content); ?>
					<?php if ($button_text) : ?>
						<a href="<?php echo $button_url; ?>" class="btn btn-green-black btn-square wh-info__btn">
							<?php echo $button_text; ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		<?php else : ?>
			<div class="wr-info">
				<div class="wr-info__left">
					<h3 class="wr-info__title"><?php echo $title; ?></h3>
				</div>
				<?php if ($button_text) : ?>
					<div class="wr-info__right">
						<a href="<?php echo $button_url; ?>" class="btn btn-green-black btn-square wr-info__btn">
							<?php echo $button_text; ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php
	}

	public function update($new_instance, $old_instance) {
		$new_instance = wp_parse_args($new_instance, array(
			'title' => '',
			'content' => '',
			'button_text' => '',
			'button_url' => '',
			'style' => 'white'
		));

		$instance = $old_instance;

		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['content'] = $new_instance['content'];
		$instance['button_text'] = sanitize_text_field($new_instance['button_text']);
		$instance['button_url'] = esc_url($new_instance['button_url']);
		$instance['style'] = sanitize_text_field($new_instance['style']);

		return $instance;
	}

	public function form($instance) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => '',
				'content' => '',
				'button_text' => '',
				'button_url' => '',
				'style' => 'white'
			)
		);

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wb'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Content:', 'wb'); ?></label>
			<textarea name="<?php echo $this->get_field_name('content'); ?>" rows="5" cols="20" class="widefat" id="<?php echo $this->get_field_id('content'); ?>"><?php echo $instance['content']; ?></textarea>
		</p>
		<h4><?php _e('Button', 'wb'); ?></h4>
		<p>
			<label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Text:', 'wb'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('button_text'); ?>" value="<?php echo esc_attr($instance['button_text']); ?>" class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('button_url'); ?>"><?php _e('URL:', 'wb'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('button_url'); ?>" value="<?php echo esc_url($instance['button_url']); ?>" class="widefat" id="<?php echo $this->get_field_id('button_url'); ?>" wb-action="autocomplete">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Style:', 'wb'); ?></label>
			<select name="<?php echo $this->get_field_name('style'); ?>" class="widefat" id="<?php echo $this->get_field_id('style'); ?>">
				<option value="white" <?php selected('white', $instance['style']); ?>>
					<?php _e('White', 'wb'); ?>
				</option>
				<option value="purple" <?php selected('purple', $instance['style']); ?>>
					<?php _e('Purple', 'wb'); ?>
				</option>
			</select>
		</p>
		<?php
	}

}
