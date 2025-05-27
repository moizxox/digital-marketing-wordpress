<?php

class Service extends WB_Plugin {

	public function action_init() {
		register_post_type('service', array(
			'labels' => array(
				'name'               => _x('Services', 'post type general name', 'wb'),
				'singular_name'      => _x('Service', 'post type singular name', 'wb'),
				'menu_name'          => _x('Services', 'admin menu', 'wb'),
				'name_admin_bar'     => _x('Service', 'add new on admin bar', 'wb'),
				'add_new'            => _x('Add New', 'service', 'wb'),
				'add_new_item'       => __('Add New Service', 'wb'),
				'new_item'           => __('New Service', 'wb'),
				'edit_item'          => __('Edit Service', 'wb'),
				'view_item'          => __('View Service', 'wb'),
				'all_items'          => __('All Services', 'wb'),
				'search_items'       => __('Search Services', 'wb'),
				'parent_item_colon'  => __('Parent Services:', 'wb'),
				'not_found'          => __('No services found.', 'wb'),
				'not_found_in_trash' => __('No services found in Trash.', 'wb')
			),
			'public'               => true,
			'menu_position'        => 33,
			'menu_icon'            => 'dashicons-groups',
			'supports'             => array('title', 'editor', 'excerpt', 'thumbnail')
		));

		register_taxonomy('service-category', 'service', array(
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

		register_taxonomy('service-tag', 'service', array(
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
			'hierarchical' => false
		));

		register_taxonomy('service-pricing-option', 'service', array(
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

		register_taxonomy('service-location', 'service', array(
			'labels' => array(
				'name'              => _x('Locations', 'taxonomy general name', 'wb'),
				'singular_name'     => _x('Location', 'taxonomy singular name', 'wb'),
				'menu_name'         => __('Locations', 'wb'),
				'parent_item'       => __('Parent Location', 'wb'),
				'parent_item_colon' => __('Parent Location:', 'wb'),
				'edit_item'         => __('Edit Location', 'wb'),
				'update_item'       => __('Update Location', 'wb'),
				'add_new_item'      => __('Add New Location', 'wb'),
				'new_item_name'     => __('New Location Name', 'wb'),
				'all_items'         => __('All Locations', 'wb'),
				'search_items'      => __('Search Locations', 'wb')
			),
			'public' => true,
			'publicly_queryable' => false,
			'show_admin_column' => true,
			'hierarchical' => true
		));

		/*
		if (isset($_GET['import-csv'])) {
			/**
			if ($posts = get_posts(['post_type' => 'service', 'posts_per_page' => -1, 'post_status' => 'all'])) {
				foreach ($posts as $post) {
					wp_delete_post($post->ID, true);
				}
			}

			exit;
			*//*

			if (($handle = fopen(WB_THEME_DIR . '/DigitalMarketing Post Types - Services.csv', 'r')) !== false) {
				$i = 0;

				while (($data = fgetcsv($handle, 1000, ',')) !== false) {
					if (!in_array($i, [0, 1, 2, 3])) {
						if ($data[1]) {
							$post_id = wp_insert_post([
								'post_type' => 'service',
								'post_status' => 'publish',
								'post_title' => $data[2],
								'post_content' => $data[11]
							]);

							if ($data[3]) {
								if (strpos($data[3], '| ') !== false) {
									$countries = explode('| ', $data[3]);

									if ($countries) {
										$terms = [];

										foreach ($countries as $country) {
											$_term = term_exists($country, 'service-location');

											if ($_term) {
												$terms[] = (int) $_term['term_id'];
											} else {
												$terms[] = (int) wp_insert_term($country, 'service-location')['term_id'];
											}
										}

										wp_set_post_terms($post_id, array_map('intval', $terms), 'service-location');

										unset($terms);
									}
								} else if (strpos($data[3], ', ') !== false) {
									$countries = explode(', ', $data[3]);

									if ($countries) {
										$terms = [];

										foreach ($countries as $country) {
											if (!$country) {
												continue;
											}

											$_term = term_exists($country, 'service-location');

											if ($_term) {
												$terms[] = (int) $_term['term_id'];
											} else {
												$terms[] = (int) wp_insert_term($country, 'service-location')['term_id'];
											}
										}

										wp_set_post_terms($post_id, array_map('intval', $terms), 'service-location');

										unset($terms);
									}
								} else {
									$term = term_exists($data[3], 'service-location');

									if ($term) {
										$term = (int) $term['term_id'];
									} else {
										try {
											$term = (int) wp_insert_term($data[3], 'service-location')['term_id'];
										} catch (Exception $e) {
											var_dump($e);

											exit;
										}
									}

									wp_set_post_terms($post_id, array((int) $term), 'service-location');
								}
							}

							if ($data[5]) {
								if (strpos($data[5], '| ') !== false) {
									$categories = explode('| ', $data[5]);

									if ($categories) {
										$terms = [];

										foreach ($categories as $category) {
											$_term = term_exists($category, 'service-category');

											if ($_term) {
												$terms[] = (int) $_term['term_id'];
											} else {
												$terms[] = (int) wp_insert_term($category, 'service-category')['term_id'];
											}
										}

										wp_set_post_terms($post_id, array_map('intval', $terms), 'service-category');

										unset($terms);
									}
								} else if (strpos($data[5], ', ') !== false) {
									$categories = explode(', ', $data[5]);

									if ($categories) {
										$terms = [];

										foreach ($categories as $category) {
											if (!$category) {
												continue;
											}

											$_term = term_exists($category, 'service-category');

											if ($_term) {
												$terms[] = (int) $_term['term_id'];
											} else {
												$terms[] = (int) wp_insert_term($category, 'service-category')['term_id'];
											}
										}

										wp_set_post_terms($post_id, array_map('intval', $terms), 'service-category');

										unset($terms);
									}
								} else {
									$term = term_exists($data[5], 'service-category');

									if ($term) {
										$term = (int) $term['term_id'];
									} else {
										try {
											$term = (int) wp_insert_term($data[5], 'service-category')['term_id'];
										} catch (Exception $e) {
											var_dump($e);

											exit;
										}
									}

									wp_set_post_terms($post_id, array((int) $term), 'service-category');
								}
							}


							update_post_meta($post_id, '_website_url', esc_url($data[1]));
						}
					}

					$i++;
				}

				fclose($handle);
			}

			exit;
		}
		*/
	}

	public function filter_manage_service_posts_columns($columns) {
		$columns['affiliate_commission'] = __('Is Affiliate / Commission', 'wb');

		return $columns;
	}

	/**
	 * @Filter manage_edit-service_sortable_columns
	 */
	public function __sortable_columns($columns) {
		$columns['affiliate_commission'] = __('Is Affiliate / Commission', 'wb');

		return $columns;
	}

	public function action_manage_service_posts_custom_column($column, $post_id) {
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

	public function action_add_meta_boxes() {
		add_meta_box('service-details', __('Service Details', 'tb'), array($this, 'service_details_meta_box'), 'service', 'side');
		add_meta_box('service-clients', __('Service Clients', 'tb'), array($this, 'service_clients_meta_box'), 'service', 'normal');
		add_meta_box('service-additional-content', __('Service Additional Content', 'tb'), array($this, 'service_additional_content_box'), 'service', 'normal');
		add_meta_box('related-services', __('Related Services', 'wb'), array($this, 'related_services_meta_box'), 'service', 'side');
		add_meta_box('service-contacts', __('Service Contacts (Internal)', 'tb'), array($this, 'service_contacts_meta_box'), 'service', 'side');
	}

	public function service_details_meta_box($post) {
		wp_nonce_field('service_details_meta_box', 'service_details_meta_box_nonce');

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

	public function service_clients_meta_box($post) {
		wp_nonce_field('service_clients_meta_box', 'service_clients_meta_box_nonce');

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

	public function service_additional_content_box($post) {
		wp_nonce_field('service_additional_content_box', 'service_additional_content_box_nonce');

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

	public function related_services_meta_box($post) {
		wp_nonce_field('related_services_meta_box', 'related_services_meta_box_nonce');

		$related_services = get_post_meta($post->ID, '_related_services', true);

		if ($related_services) {
			$related_services = get_posts(array(
				'post_type' => 'service',
				'numberposts' => 12,
				'post__in' => $related_services
			));
		}

		?>
		<p>
			<label for="related_services"><?php _e('Services', 'wb'); ?></label>
			<select name="related_services[]" class="widefat" id="related_services" multiple>
				<?php if ($related_services) : ?>
					<?php foreach ($related_services as $related_service) : ?>
						<option value="<?php echo $related_service->ID; ?>" selected>
							<?php echo $related_service->post_title; ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</p>
		<script type="text/javascript">
			jQuery(function ($) {
				$('#related_services').select2({
					placeholder: '<?php _e('Choose services', 'wb'); ?>',
					allowClear: true,
					tags: true,
					ajax: {
						url: '<?php echo add_query_arg('action', 'get_services', admin_url('admin-ajax.php')); ?>',
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

	public function service_contacts_meta_box($post) {
		wp_nonce_field('service_contacts_meta_box', 'service_contacts_meta_box_nonce');

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

	public function action_save_post($post_id) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		if (isset($_POST['service_details_meta_box_nonce']) && wp_verify_nonce($_POST['service_details_meta_box_nonce'], 'service_details_meta_box')) {
			update_post_meta($post_id, '_logo', esc_url($_POST['logo']));
			update_post_meta($post_id, '_website_url', esc_url($_POST['website_url']));
			update_post_meta($post_id, '_amount', esc_attr($_POST['amount']));
			update_post_meta($post_id, '_currency', esc_attr($_POST['currency']));
			update_post_meta($post_id, '_is_affiliate', isset($_POST['affiliate']));
			update_post_meta($post_id, '_commission', esc_attr($_POST['commission']));
		}

		if (isset($_POST['service_clients_meta_box_nonce']) && wp_verify_nonce($_POST['service_clients_meta_box_nonce'], 'service_clients_meta_box')) {
			if ($_POST['clients']) {
				update_post_meta($post_id, '_clients', $_POST['clients']);
			} else {
				delete_post_meta($post_id, '_clients');
			}
		}

		if (isset($_POST['service_additional_content_box_nonce']) && wp_verify_nonce($_POST['service_additional_content_box_nonce'], 'service_additional_content_box')) {
			update_post_meta($post_id, '_additional_content_title', esc_attr($_POST['additional_content_title']));
			update_post_meta($post_id, '_additional_content_text', $_POST['additional_content_text']);
		}

		if (isset($_POST['related_services_meta_box_nonce']) && wp_verify_nonce($_POST['related_services_meta_box_nonce'], 'related_services_meta_box')) {
			if ($_POST['related_services']) {
				update_post_meta($post_id, '_related_services', $_POST['related_services']);
			} else {
				delete_post_meta($post_id, '_related_services');
			}
		}

		if (isset($_POST['service_contacts_meta_box_nonce']) && wp_verify_nonce($_POST['service_contacts_meta_box_nonce'], 'service_contacts_meta_box')) {
			update_post_meta($post_id, '_contacts_email', esc_attr($_POST['contacts_email']));
			update_post_meta($post_id, '_contacts_phone', esc_attr($_POST['contacts_phone']));
			update_post_meta($post_id, '_contacts_facebook_url', esc_url($_POST['contacts_facebook_url']));
			update_post_meta($post_id, '_contacts_twitter_url', esc_url($_POST['contacts_twitter_url']));
			update_post_meta($post_id, '_contacts_linkedin_url', esc_url($_POST['contacts_linkedin_url']));
		}
	}

	public function action_wp_ajax_get_service_categories() {
		$term = isset($_REQUEST['term']) ? $_REQUEST['term'] : '';

		if (!$term) {
			echo '[]';

			exit;
		}

		$categories = get_terms(array(
			'taxonomy' => 'service-category',
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

	public function action_wp_ajax_get_services() {
		$term = isset($_REQUEST['term']) ? $_REQUEST['term'] : '';
		$category = isset($_REQUEST['category']) ? $_REQUEST['category'] : '';

		if (!$term) {
			echo '[]';

			exit;
		}

		$services_args = array(
			'post_type' => 'service',
			'numberposts' => 15,
			's' => $term
		);

		if ($category) {
			$services_args['tax_query'][] = array(
				'taxonomy'  => 'service-category',
				'field' => 'term_id',
				'terms' => (array) $category
			);
		}

		$services = get_posts($services_args);

		if (!$services) {
			echo '[]';

			exit;
		}

		$data = array();

		foreach ($services as $service) {
			$data[] = array(
				'id' => $service->ID,
				'text' => $service->post_title
			);
		}

		echo json_encode($data);

		exit;
	}

}
