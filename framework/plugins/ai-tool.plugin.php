<?php

class AI_Tool extends WB_Plugin {

	public function __construct() {
		parent::__construct();
		add_action('init', array($this, 'action_init'));
		add_action('manage_ai-tool_posts_columns', array($this, 'filter_manage_ai_tool_posts_columns'));
		add_action('manage_ai-tool_posts_custom_column', array($this, 'action_manage_ai_tool_posts_custom_column'), 10, 2);
		add_action('save_post', array($this, 'action_save_post'));
		add_action('add_meta_boxes', array($this, 'action_add_meta_boxes'));
	}

	public function action_init() {
		register_post_type('ai-tool', array(
			'labels' => array(
				'name'               => _x('AI Tools', 'post type general name', 'wb'),
				'singular_name'      => _x('AI Tool', 'post type singular name', 'wb'),
				'menu_name'          => _x('AI Tools', 'admin menu', 'wb'),
				'name_admin_bar'     => _x('AI Tool', 'add new on admin bar', 'wb'),
				'add_new'            => _x('Add New', 'ai-tool', 'wb'),
				'add_new_item'       => __('Add New AI Tool', 'wb'),
				'new_item'           => __('New AI Tool', 'wb'),
				'edit_item'          => __('Edit AI Tool', 'wb'),
				'view_item'          => __('View AI Tool', 'wb'),
				'all_items'          => __('All AI Tools', 'wb'),
				'search_items'       => __('Search AI Tools', 'wb'),
				'parent_item_colon'  => __('Parent AI Tools:', 'wb'),
				'not_found'          => __('No AI tools found.', 'wb'),
				'not_found_in_trash' => __('No AI tools found in Trash.', 'wb')
			),
			'public'               => true,
			'menu_position'        => 30,
			'menu_icon'            => 'dashicons-admin-generic',
			'supports'             => array('title', 'editor', 'excerpt', 'thumbnail')
		));

		register_taxonomy('ai-tool-category', 'ai-tool', array(
			'labels' => array(
				'name'              => _x('Categories', 'taxonomy general name', 'wb'),
				'singular_name'     => _x('Category', 'taxonomy singular name', 'wb'),
				'menu_name'         => __('Categories', 'wb'),
				'parent_item'       => __('Parent Category', 'wb'),
				'parent_item_colon' => __('Parent Category:', 'wb'),
				'edit_item'         => __('Edit Category', 'wb'),
				'update_item'       => __('Update Category', 'wb'),
				'add_new_item'      => __('Add New Category', 'wb'),
				'new_item_name'     => __('New Category Name', 'wb'),
				'all_items'         => __('All Categories', 'wb'),
				'search_items'      => __('Search Categories', 'wb')
			),
			'public' => true,
			'show_admin_column' => true,
			'hierarchical' => true
		));

		register_taxonomy('ai-tool-pricing-option', 'ai-tool', array(
			'labels' => array(
				'name'              => _x('Pricing Options', 'taxonomy general name', 'wb'),
				'singular_name'     => _x('Pricing Option', 'taxonomy singular name', 'wb'),
				'menu_name'         => __('Pricing Options', 'wb'),
				'parent_item'       => __('Parent Pricing Option', 'wb'),
				'parent_item_colon' => __('Parent Pricing Option:', 'wb'),
				'edit_item'         => __('Edit Pricing Option', 'wb'),
				'update_item'       => __('Update Pricing Option', 'wb'),
				'add_new_item'      => __('Add New Pricing Option', 'wb'),
				'new_item_name'     => __('New Pricing Option Name', 'wb'),
				'all_items'         => __('All Pricing Options', 'wb'),
				'search_items'      => __('Search Pricing Options', 'wb')
			),
			'public' => true,
			'publicly_queryable' => false,
			'show_admin_column' => true,
			'hierarchical' => true
		));
	}

	public function filter_manage_ai_tool_posts_columns($columns) {
		$columns['affiliate_commission'] = __('Is Affiliate / Commission', 'wb');
		return $columns;
	}

	public function action_manage_ai_tool_posts_custom_column($column, $post_id) {
		if ($column == 'affiliate_commission') {
			$affiliate = get_post_meta($post_id, '_is_affiliate', true);
			$commission = get_post_meta($post_id, '_commission', true);

			if ($affiliate) {
				_e('Yes', 'wb');
			} else {
				_e('No', 'wb');
			}

			if ($commission) {
				echo ' / <strong>' . $commission . '</strong>';
			}
		}
	}

	public function action_save_post($post_id) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		if (isset($_POST['ai_tool_details_meta_box_nonce']) && wp_verify_nonce($_POST['ai_tool_details_meta_box_nonce'], 'ai_tool_details_meta_box')) {
			update_post_meta($post_id, '_logo', esc_url($_POST['logo']));
			update_post_meta($post_id, '_website_url', esc_url($_POST['website_url']));
			update_post_meta($post_id, '_amount', esc_attr($_POST['amount']));
			update_post_meta($post_id, '_currency', esc_attr($_POST['currency']));
			update_post_meta($post_id, '_is_affiliate', isset($_POST['affiliate']));
			update_post_meta($post_id, '_commission', esc_attr($_POST['commission']));
		}

		if (isset($_POST['ai_tool_clients_meta_box_nonce']) && wp_verify_nonce($_POST['ai_tool_clients_meta_box_nonce'], 'ai_tool_clients_meta_box')) {
			if ($_POST['clients']) {
				update_post_meta($post_id, '_clients', $_POST['clients']);
			} else {
				delete_post_meta($post_id, '_clients');
			}
		}

		if (isset($_POST['ai_tool_additional_content_box_nonce']) && wp_verify_nonce($_POST['ai_tool_additional_content_box_nonce'], 'ai_tool_additional_content_box')) {
			update_post_meta($post_id, '_additional_content_title', esc_attr($_POST['additional_content_title']));
			update_post_meta($post_id, '_additional_content_text', $_POST['additional_content_text']);
		}

		if (isset($_POST['related_ai_tools_meta_box_nonce']) && wp_verify_nonce($_POST['related_ai_tools_meta_box_nonce'], 'related_ai_tools_meta_box')) {
			if ($_POST['related_tools']) {
				update_post_meta($post_id, '_related_tools', $_POST['related_tools']);
			} else {
				delete_post_meta($post_id, '_related_tools');
			}
		}

		if (isset($_POST['ai_tool_contacts_meta_box_nonce']) && wp_verify_nonce($_POST['ai_tool_contacts_meta_box_nonce'], 'ai_tool_contacts_meta_box')) {
			update_post_meta($post_id, '_contacts_email', esc_attr($_POST['contacts_email']));
			update_post_meta($post_id, '_contacts_phone', esc_attr($_POST['contacts_phone']));
			update_post_meta($post_id, '_contacts_facebook_url', esc_url($_POST['contacts_facebook_url']));
			update_post_meta($post_id, '_contacts_twitter_url', esc_url($_POST['contacts_twitter_url']));
			update_post_meta($post_id, '_contacts_linkedin_url', esc_url($_POST['contacts_linkedin_url']));
		}
	}

	public function action_add_meta_boxes() {
		add_meta_box('ai-tool-details', __('AI Tool Details', 'tb'), array($this, 'ai_tool_details_meta_box'), 'ai-tool', 'side');
		add_meta_box('ai-tool-clients', __('AI Tool Clients', 'tb'), array($this, 'ai_tool_clients_meta_box'), 'ai-tool', 'normal');
		add_meta_box('ai-tool-additional-content', __('AI Tool Additional Content', 'tb'), array($this, 'ai_tool_additional_content_box'), 'ai-tool', 'normal');
		add_meta_box('related-ai-tools', __('Related AI Tools', 'wb'), array($this, 'related_ai_tools_meta_box'), 'ai-tool', 'side');
		add_meta_box('ai-tool-contacts', __('AI Tool Contacts (Internal)', 'tb'), array($this, 'ai_tool_contacts_meta_box'), 'ai-tool', 'side');
	}

	public function ai_tool_details_meta_box($post) {
		wp_nonce_field('ai_tool_details_meta_box', 'ai_tool_details_meta_box_nonce');
		$logo = get_post_meta($post->ID, '_logo', true);
		$website_url = get_post_meta($post->ID, '_website_url', true);
		$amount = get_post_meta($post->ID, '_amount', true);
		$currency = get_post_meta($post->ID, '_currency', true);
		$affiliate = get_post_meta($post->ID, '_is_affiliate', true);
		$commission = get_post_meta($post->ID, '_commission', true);
		?>
		<p>
			<label for="logo">
				<?php _e('Logo', 'wb'); ?>
				(<a href="#" wb-action="upload" wb-target="#logo"><?php _e('Upload', 'wb'); ?></a>)
			</label>
			<input type="text" name="logo" value="<?php echo $logo; ?>" class="widefat" id="logo">
			<span class="description"><?php _e('Recommended image size: 150 x 150 px', 'wb'); ?></span>
		</p>
		<p>
			<label for="website_url"><?php _e('Website URL', 'wb'); ?></label>
			<input type="text" name="website_url" value="<?php echo $website_url; ?>" class="widefat" id="website_url">
		</p>
		<h4><?php _e('Price', 'wb'); ?></h4>
		<p>
			<label for="amount"><?php _e('Amount', 'wb'); ?></label>
			<input type="text" name="amount" value="<?php echo $amount; ?>" class="widefat" id="amount">
		</p>
		<p>
			<label for="currency"><?php _e('Currency', 'wb'); ?></label>
			<input type="text" name="currency" value="<?php echo $currency; ?>" class="widefat" id="currency">
		</p>
		<p>
			<input type="checkbox" name="affiliate" value="1" <?php checked($affiliate, '1'); ?>>
			<label for="affiliate"><?php _e('Affiliate', 'wb'); ?></label>
		</p>
		<p>
			<label for="commission"><?php _e('Commission', 'wb'); ?></label>
			<input type="text" name="commission" value="<?php echo $commission; ?>" class="widefat" id="commission">
		</p>
		<?php
	}

	public function ai_tool_clients_meta_box($post) {
		wp_nonce_field('ai_tool_clients_meta_box', 'ai_tool_clients_meta_box_nonce');
		$clients = get_post_meta($post->ID, '_clients', true);
		?>
		<div id="wb-framework">
			<div class="wb-section">
				<input type="submit" value="<?php _e('Add New', 'wb'); ?>" class="wb-btn" wb-action="add">
			</div>
			<div wb-list>
				<?php if ($clients) : ?>
					<?php foreach ($clients as $key => $client) : ?>
						<div class="wb-section wb-slider">
							<div class="label-area">
								<div class="wb-image">
									<img src="<?php echo wb_image(esc_url($client['image']), 400, 300); ?>" alt="" id="img-<?php echo $key; ?>">
								</div>
							</div>
							<div class="form-area">
								<input type="text" name="clients[<?php echo $key; ?>][image]" value="<?php echo esc_url($client['image']); ?>" id="val-c-<?php echo $key; ?>" placeholder="<?php _e('No media selected', 'wb'); ?>" wb-action="upload" wb-target="#val-c-<?php echo $key; ?>" wb-image="#img-c-<?php echo $key; ?>">
								<span><?php _e('Recommended image size: unlimited x 100 px', 'wb'); ?></span>
								<p>
									<input type="submit" value="<?php _e('Upload', 'wb'); ?>" class="wb-btn" wb-action="upload" wb-target="#val-c-<?php echo $key; ?>" wb-image="#img-c-<?php echo $key; ?>">
								</p>
								<h4><?php _e('Title', 'wb'); ?></h4>
								<input type="text" name="clients[<?php echo $key; ?>][title]" value="<?php echo esc_attr($client['title']); ?>" />
								<div class="clear"></div>
								<a href="#" title="<?php _e('Delete', 'wb'); ?>" class="wb-delete" wb-action="remove">
									<i class="fa fa-trash-o"></i>
								</a>
							</div>
							<div class="clear"></div>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<p class="wb-empty"><?php _e('No items found.', 'wb'); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<script type="text/javascript">
			var wb_saveable = true,
				wb_resettable = true;
			var __key = <?php echo (isset($clients) && !empty($clients)) ? max(array_keys($clients)) + 1 : 0; ?>;
			var __template = ' \
				<div class="wb-section wb-slider"> \
					<div class="label-area"> \
						<div class="wb-image"> \
							<img src="<?php echo WB_FRAMEWORK_URL; ?>/assets/img/thumbnail.gif" alt="" id="img-c-##key##"> \
						</div> \
					</div> \
					<div class="form-area"> \
						<input type="text" name="clients[##key##][image]" id="val-c-##key##" placeholder="<?php _e("No media selected", "wb"); ?>" wb-action="upload" wb-target="#val-c-##key##" wb-image="#img-c-##key##"> \
						<span><?php _e("Recommended image size: unlimited x 100 px", "wb"); ?></span> \
						<p> \
							<input type="submit" value="<?php _e("Upload", "wb"); ?>" class="wb-btn" wb-action="upload" wb-target="#val-c-##key##" wb-image="#img-c-##key##"> \
						</p> \
						<h4><?php _e('Title', 'wb'); ?></h4> \
						<input type="text" name="clients[##key##][title]" /> \
						<div class="clear"></div> \
						<a href="#" title="<?php _e("Delete", "wb"); ?>" class="wb-delete" wb-action="remove"> \
							<i class="fa fa-trash-o"></i> \
						</a> \
					</div> \
					<div class="clear"></div> \
				</div> \
			';
		</script>
		<?php
	}

	public function ai_tool_additional_content_box($post) {
		wp_nonce_field('ai_tool_additional_content_box', 'ai_tool_additional_content_box_nonce');
		$title = get_post_meta($post->ID, '_additional_content_title', true);
		$text = get_post_meta($post->ID, '_additional_content_text', true);
		?>
		<div id="wb-framework">
			<div class="wb-section">
				<div class="label-area">
					<?php _e('Title', 'wb'); ?>
				</div>
				<div class="form-area">
					<input type="text" name="additional_content_title" value="<?php echo $title; ?>">
				</div>
				<div class="clear"></div>
			</div>
			<div class="wb-section">
				<h4><?php _e('Text', 'wb'); ?></h4>
				<?php wp_editor($text, 'additional_content_text', 'textarea_name=additional_content_text&textarea_rows=15'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php
	}

	public function related_ai_tools_meta_box($post) {
		wp_nonce_field('related_ai_tools_meta_box', 'related_ai_tools_meta_box_nonce');
		$related_tools = get_post_meta($post->ID, '_related_tools', true);
		if ($related_tools) {
			$related_tools = get_posts(array(
				'post_type' => 'ai-tool',
				'numberposts' => 12,
				'post__in' => $related_tools
			));
		}
		?>
		<p>
			<label for="related_tools"><?php _e('AI Tools', 'wb'); ?></label>
			<select name="related_tools[]" class="widefat" id="related_tools" multiple>
				<?php if ($related_tools) : ?>
					<?php foreach ($related_tools as $related_tool) : ?>
						<option value="<?php echo $related_tool->ID; ?>" selected>
							<?php echo $related_tool->post_title; ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</p>
		<script type="text/javascript">
			jQuery(function ($) {
				$('#related_tools').select2({
					placeholder: '<?php _e('Choose AI Tools', 'wb'); ?>',
					allowClear: true,
					tags: true,
					ajax: {
						url: '<?php echo add_query_arg('action', 'get_ai_tools', admin_url('admin-ajax.php')); ?>',
						dataType: 'json',
						delay: 250,
						data: function (params) {
							return {
								term: params.term
							};
						},
						processResults: function (data, params) {
							return {
								results: data
							};
						}
					}
				});
			});
		</script>
		<?php
		wp_enqueue_style('select2', WB_THEME_URL . '/css/select2.css');
		wp_enqueue_script('select2', WB_THEME_URL . '/js/select2.js', array('jquery'));
	}

	public function ai_tool_contacts_meta_box($post) {
		wp_nonce_field('ai_tool_contacts_meta_box', 'ai_tool_contacts_meta_box_nonce');
		$email = get_post_meta($post->ID, '_contacts_email', true);
		$phone = get_post_meta($post->ID, '_contacts_phone', true);
		$facebook_url = get_post_meta($post->ID, '_contacts_facebook_url', true);
		$twitter_url = get_post_meta($post->ID, '_contacts_twitter_url', true);
		$linkedin_url = get_post_meta($post->ID, '_contacts_linkedin_url', true);
		?>
		<p>
			<label for="email"><?php _e('Email', 'wb'); ?></label>
			<input type="text" name="contacts_email" value="<?php echo $email; ?>" class="widefat" id="email">
		</p>
		<p>
			<label for="phone"><?php _e('Phone', 'wb'); ?></label>
			<input type="text" name="contacts_phone" value="<?php echo $phone; ?>" class="widefat" id="phone">
		</p>
		<h4><?php _e('Social', 'wb'); ?></h4>
		<p>
			<label for="facebook_url"><?php _e('Facebook URL', 'wb'); ?></label>
			<input type="text" name="contacts_facebook_url" value="<?php echo $facebook_url; ?>" class="widefat" id="facebook_url">
		</p>
		<p>
			<label for="twitter_url"><?php _e('Twitter URL', 'wb'); ?></label>
			<input type="text" name="contacts_twitter_url" value="<?php echo $twitter_url; ?>" class="widefat" id="twitter_url">
		</p>
		<p>
			<label for="linkedin_url"><?php _e('LinkedIn URL', 'wb'); ?></label>
			<input type="text" name="contacts_linkedin_url" value="<?php echo $linkedin_url; ?>" class="widefat" id="linkedin_url">
		</p>
		<?php
	}
}
