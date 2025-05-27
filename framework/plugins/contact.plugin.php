<?php

class Contact extends WB_Plugin {

	public function action_add_meta_boxes() {
		global $post;

		$page_template = get_post_meta($post->ID, '_wp_page_template', true);

		if ($page_template == 'page-templates/contact.php') {
			add_meta_box('contact-page-details', __('Contact Details', 'tb'), array($this, 'contact_details_meta_box'), 'page', 'side');
		}
	}

	public function contact_details_meta_box($post) {
		wp_nonce_field('contact_details_meta_box', 'contact_details_meta_box_nonce');

		$contact_form_shortcode = get_post_meta($post->ID, '_contact_form_shortcode', true);

		?>
		<p>
			<label for="contact_form_shortcode"><?php _e('Contact Form Shortcode', 'wb'); ?></label>
			<input type="text" name="contact_form_shortcode" value="<?php echo $contact_form_shortcode; ?>" class="widefat" id="contact_form_shortcode">
		</p>
		<?php
	}

	public function action_save_post($post_id) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		if (isset($_POST['contact_details_meta_box_nonce']) && wp_verify_nonce($_POST['contact_details_meta_box_nonce'], 'contact_details_meta_box')) {
			update_post_meta($post_id, '_contact_form_shortcode', esc_attr($_POST['contact_form_shortcode']));
		}
	}

}
