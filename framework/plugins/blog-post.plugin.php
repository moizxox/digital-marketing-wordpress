<?php

class Blog_Post extends WB_Plugin {

	public function action_init() {
		register_post_type('blog-post', array(
			'labels' => array(
				'name'               => _x('Posts', 'post type general name', 'wb'),
				'singular_name'      => _x('Post', 'post type singular name', 'wb'),
				'menu_name'          => _x('Blog', 'admin menu', 'wb'),
				'name_admin_bar'     => _x('Post', 'add new on admin bar', 'wb'),
				'add_new'            => _x('Add New', 'blog-post', 'wb'),
				'add_new_item'       => __('Add New Post', 'wb'),
				'new_item'           => __('New Post', 'wb'),
				'edit_item'          => __('Edit Post', 'wb'),
				'view_item'          => __('View Post', 'wb'),
				'all_items'          => __('All Posts', 'wb'),
				'search_items'       => __('Search Posts', 'wb'),
				'parent_item_colon'  => __('Parent Posts:', 'wb'),
				'not_found'          => __('No posts found.', 'wb'),
				'not_found_in_trash' => __('No posts found in Trash.', 'wb')
			),
			'public'               => true,
			'menu_position'        => 34,
			'menu_icon'            => 'dashicons-admin-post',
			'supports'             => array('title', 'editor', 'author', 'thumbnail'),
			'rewrite' => array(
				'slug' => 'blog'
			)
		));

		register_taxonomy('blog-post-category', 'blog-post', array(
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
			'hierarchical' => true,
			'rewrite' => array(
				'slug' => 'blog-category'
			)
		));

		register_taxonomy('blog-post-tag', 'blog-post', array(
			'labels' => array(
				'name'                       => _x('Tags', 'taxonomy general name', 'wb'),
				'singular_name'              => _x('Tag', 'taxonomy singular name', 'wb'),
				'search_items'               => __('Search Tags', 'wb'),
				'popular_items'              => __('Popular Tags', 'wb'),
				'all_items'                  => __('All Tags', 'wb'),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => __('Edit Tag', 'wb'),
				'update_item'                => __('Update Tag', 'wb'),
				'add_new_item'               => __('Add New Tag', 'wb'),
				'new_item_name'              => __('New Tag Name', 'wb'),
				'separate_items_with_commas' => __('Separate tags with commas', 'wb'),
				'add_or_remove_items'        => __('Add or remove tags', 'wb'),
				'choose_from_most_used'      => __('Choose from the most used tags', 'wb'),
				'not_found'                  => __('No tags found.', 'wb'),
				'menu_name'                  => __('Tags', 'wb'),
			),
			'public' => true,
			'show_admin_column' => true,
			'hierarchical' => false,
			'rewrite' => array(
				'slug' => 'blog-tag'
			)
		));
	}

	public function action_add_meta_boxes() {
		global $post;

		$page_template = get_post_meta($post->ID, '_wp_page_template', true);

		if ($page_template == 'page-templates/blog.php') {
			add_meta_box('blog-page-details', __('Blog Page Details', 'wb'), array($this, 'blog_page_details_meta_box'), 'page');
		}
	}

	public function blog_page_details_meta_box($post) {
		wp_nonce_field('blog_page_details_meta_box', 'blog_page_details_meta_box_nonce');

		$image = get_post_meta($post->ID, '_image', true);
		$title = get_post_meta($post->ID, '_title', true);
		$description = get_post_meta($post->ID, '_description', true);

		?>
		<div id="wb-framework">
			<div class="wb-section">
				<div class="label-area">
					<div class="wb-image">
						<img src="<?php echo wb_image(esc_url($image), 400, 300); ?>" alt="" id="img-bp">
					</div>
				</div>
				<div class="form-area">
					<input type="text" name="image" value="<?php echo esc_url($image); ?>" id="val-bp" placeholder="<?php _e('No media selected', 'wb'); ?>" wb-action="upload" wb-target="#val-bp" wb-image="#img-pb">
					<span><?php _e('Recommended image size: 785 x 515 px', 'wb'); ?></span>
					<p>
						<input type="submit" value="<?php _e('Upload', 'wb'); ?>" class="wb-btn" wb-action="upload" wb-target="#val-bp" wb-image="#img-bp">
					</p>
				</div>
				<div class="clear"></div>
			</div>
			<div class="wb-section">
				<div class="label-area">
					<?php _e('Title', 'wb'); ?>
				</div>
				<div class="form-area">
					<input type="text" name="title" value="<?php echo $title; ?>">
				</div>
				<div class="clear"></div>
			</div>
			<div class="wb-section">
				<h4><?php _e('Description', 'wb'); ?></h4>
				<?php wp_editor($description, 'description', 'textarea_name=description&textarea_rows=15'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php
	}

	public function action_save_post($post_id) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		if (isset($_POST['blog_page_details_meta_box_nonce']) && wp_verify_nonce($_POST['blog_page_details_meta_box_nonce'], 'blog_page_details_meta_box')) {
			update_post_meta($post_id, '_image', esc_url($_POST['image']));
			update_post_meta($post_id, '_title', esc_attr($_POST['title']));
			update_post_meta($post_id, '_description', $_POST['description']);
		}
	}

}
