<?php

class Post extends WB_Plugin {

	public function action_init() {
		register_taxonomy('post-type', 'post', array(
			'labels' => array(
				'name'              => _x('Types', 'taxonomy general name', 'wb'),
				'singular_name'     => _x('Type', 'taxonomy singular name', 'wb'),
				'menu_name'         => __('Types', 'wb'),
				'parent_item'       => __('Parent Type', 'wb'),
				'parent_item_colon' => __('Parent Type:', 'wb'),
				'edit_item'         => __('Edit Type', 'wb'),
				'update_item'       => __('Update Type', 'wb'),
				'add_new_item'      => __('Add New Type', 'wb'),
				'new_item_name'     => __('New Type Name', 'wb'),
				'all_items'         => __('All Types', 'wb'),
				'search_items'      => __('Search Types', 'wb')
			),
			'public' => true,
			'show_admin_column' => true,
			'hierarchical' => true
		));
	}

	public function action_add_meta_boxes() {
		add_meta_box('post-details', __('Post Details', 'tb'), array($this, 'post_details_meta_box'), 'post', 'side');
		add_meta_box('related-posts', __('Related Posts', 'wb'), array($this, 'related_posts_meta_box'), 'post', 'side');
	}

	public function post_details_meta_box($post) {
		wp_nonce_field('post_details_meta_box', 'post_details_meta_box_nonce');

		$website_url = get_post_meta($post->ID, '_website_url', true);

		?>
		<p>
			<label for="website_url"><?php _e('Website URL', 'wb'); ?></label>
			<input type="text" name="website_url" value="<?php echo $website_url; ?>" class="widefat" id="website_url">
		</p>
		<?php
	}

	public function related_posts_meta_box($post) {
		wp_nonce_field('related_posts_meta_box', 'related_posts_meta_box_nonce');

		$related_posts = get_post_meta($post->ID, '_related_posts', true);

		if ($related_posts) {
			$related_posts = get_posts(array(
				'post_type' => 'post',
				'numberposts' => 12,
				'post__in' => $related_posts
			));
		}

		?>
		<p>
			<label for="related_posts"><?php _e('Posts', 'wb'); ?></label>
			<select name="related_posts[]" class="widefat" id="related_posts" multiple>
				<?php if ($related_posts) : ?>
					<?php foreach ($related_posts as $related_post) : ?>
						<option value="<?php echo $related_post->ID; ?>" selected>
							<?php echo $related_post->post_title; ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</p>
		<script type="text/javascript">
			jQuery(function ($) {
				$('#related_posts').select2({
					placeholder: '<?php _e('Choose posts', 'wb'); ?>',
					allowClear: true,
					tags: true,
					ajax: {
						url: '<?php echo add_query_arg('action', 'get_posts', admin_url('admin-ajax.php')); ?>',
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

	public function action_save_post($post_id) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		if (isset($_POST['post_details_meta_box_nonce']) && wp_verify_nonce($_POST['post_details_meta_box_nonce'], 'post_details_meta_box')) {
			update_post_meta($post_id, '_website_url', esc_url($_POST['website_url']));
		}

		if (isset($_POST['related_posts_meta_box_nonce']) && wp_verify_nonce($_POST['related_posts_meta_box_nonce'], 'related_posts_meta_box')) {
			if ($_POST['related_posts']) {
				update_post_meta($post_id, '_related_posts', $_POST['related_posts']);
			} else {
				delete_post_meta($post_id, '_related_posts');
			}
		}
	}

	public function action_wp_ajax_get_posts() {
		$term = isset($_REQUEST['term']) ? $_REQUEST['term'] : '';

		if (!$term) {
			echo '[]';

			exit;
		}

		$posts = get_posts(array(
			'post_type' => 'post',
			'numberposts' => 15,
			's' => $term
		));

		if (!$posts) {
			echo '[]';

			exit;
		}

		$data = array();

		foreach ($posts as $post) {
			$data[] = array(
				'id' => $post->ID,
				'text' => $post->post_title
			);
		}

		echo json_encode($data);

		exit;
	}

	public function action_wp_ajax_get_post_categories() {
		$term = isset($_REQUEST['term']) ? $_REQUEST['term'] : '';

		if (!$term) {
			echo '[]';

			exit;
		}

		$categories = get_terms(array(
			'taxonomy' => 'category',
			'search' => $term
		));

		if (!$categories) {
			echo '[]';

			exit;
		}

		$data = array();

		foreach ($categories as $category) {
			$data[] = array(
				'id' => $category->term_id,
				'text' => $category->name
			);
		}

		echo json_encode($data);

		exit;
	}

}
