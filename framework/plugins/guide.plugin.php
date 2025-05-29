<?php

class Guide extends WB_Plugin {

	public function action_init() {
		register_post_type('guide', array(
			'labels' => array(
				'name'               => _x('Guides', 'post type general name', 'wb'),
				'singular_name'      => _x('Guide', 'post type singular name', 'wb'),
				'menu_name'          => _x('Guides', 'admin menu', 'wb'),
				'name_admin_bar'     => _x('Guide', 'add new on admin bar', 'wb'),
				'add_new'            => _x('Add New', 'guide', 'wb'),
				'add_new_item'       => __('Add New Guide', 'wb'),
				'new_item'           => __('New Guide', 'wb'),
				'edit_item'          => __('Edit Guide', 'wb'),
				'view_item'          => __('View Guide', 'wb'),
				'all_items'          => __('All Guides', 'wb'),
				'search_items'       => __('Search Guides', 'wb'),
				'parent_item_colon'  => __('Parent Guides:', 'wb'),
				'not_found'          => __('No guides found.', 'wb'),
				'not_found_in_trash' => __('No guides found in Trash.', 'wb')
			),
			'public'               => true,
			'menu_position'        => 35,
			'menu_icon'            => 'dashicons-book',
			'supports'             => array('title', 'editor')
		));


		$GLOBALS['menu'][34] = array(
			0 => '',
			1 => 'read',
			2 => 'separator34',
			3 => '',
			4 => 'wp-menu-separator'
		);
	}

}
