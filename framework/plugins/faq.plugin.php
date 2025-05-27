<?php

class FAQ extends WB_Plugin {

	public function action_init() {
		register_post_type('faq', array(
			'labels' => array(
				'name'               => _x('FAQs', 'post type general name', 'wb'),
				'singular_name'      => _x('FAQ', 'post type singular name', 'wb'),
				'menu_name'          => _x('FAQs', 'admin menu', 'wb'),
				'name_admin_bar'     => _x('FAQ', 'add new on admin bar', 'wb'),
				'add_new'            => _x('Add New', 'faq', 'wb'),
				'add_new_item'       => __('Add New FAQ', 'wb'),
				'new_item'           => __('New FAQ', 'wb'),
				'edit_item'          => __('Edit FAQ', 'wb'),
				'view_item'          => __('View FAQ', 'wb'),
				'all_items'          => __('All FAQs', 'wb'),
				'search_items'       => __('Search FAQs', 'wb'),
				'parent_item_colon'  => __('Parent FAQs:', 'wb'),
				'not_found'          => __('No faqs found.', 'wb'),
				'not_found_in_trash' => __('No faqs found in Trash.', 'wb')
			),
			'public'               => true,
			'publicly_queryable'   => false,
			'menu_position'        => 36,
			'menu_icon'            => 'dashicons-editor-help',
			'supports'             => array('title', 'editor')
		));

		register_taxonomy('faq-category', 'faq', array(
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
			'publicly_queryable' => false,
			'show_admin_column' => true,
			'hierarchical' => true
		));
	}

}
