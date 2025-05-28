<?php

class AI_Tool extends WB_Plugin {

	public function __construct() {
		parent::__construct();
		add_action('init', array($this, 'action_init'));
		add_action('manage_ai-tool_posts_columns', array($this, 'filter_manage_ai_tool_posts_columns'));
		add_action('manage_ai-tool_posts_custom_column', array($this, 'action_manage_ai_tool_posts_custom_column'), 10, 2);
		add_action('save_post', array($this, 'action_save_post'));
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
	}
}
