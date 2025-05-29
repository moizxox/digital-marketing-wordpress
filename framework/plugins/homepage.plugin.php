<?php

class Homepage extends WB_Plugin {

	public function action_add_meta_boxes() {
		global $post;

		$page_template = get_post_meta($post->ID, '_wp_page_template', true);

		if ($page_template !== 'page-templates/homepage.php') {
			return;
		}

		add_meta_box('homepage-promotion-boxes', __('Promotion Boxes', 'wb'), array($this, 'homepage_promotion_boxes_meta_box'), 'page');
		add_meta_box('homepage-featured-categories', __('Featured Categories', 'wb'), array($this, 'homepage_featured_categories_meta_box'), 'page', 'side');
		add_meta_box('homepage-featured-content-categories', __('Featured Content Categories', 'wb'), array($this, 'homepage_featured_content_categories_meta_box'), 'page', 'side');
		add_meta_box('homepage-promotion-text', __('Promotion Text', 'wb'), array($this, 'homepage_promotion_text_meta_box'), 'page');
	}

	public function homepage_promotion_boxes_meta_box($post) {
		wp_nonce_field('homepage_promotion_boxes_meta_box', 'homepage_promotion_boxes_meta_box_nonce');

		$promotion_boxes = get_post_meta($post->ID, '_promotion_boxes', true);

		?>
		<div id="wb-framework">
			<div class="wb-section">
				<input type="submit" value="<?php _e('Add New', 'wb'); ?>" class="wb-btn" wb-action="add" wb-target="#promotion_boxes">
			</div>
			<div id="promotion_boxes" wb-list>
				<?php if ($promotion_boxes) : ?>
					<?php foreach ($promotion_boxes as $key => $promotion_box) : ?>
						<div class="wb-section wb-slider">
							<div class="form-area" style="width: 98%;">
								<input type="text" name="promotion_boxes[<?php echo $key; ?>][image]" value="<?php echo $promotion_box['image']; ?>" id="val-<?php echo $key; ?>" placeholder="<?php _e('No media selected', 'wb'); ?>" wb-action="upload" wb-target="#val-<?php echo $key; ?>">
								<span><?php _e('Recommended image size: 60 x 55 px', 'wb'); ?></span>
								<p>
									<input type="submit" value="<?php _e('Upload', 'wb'); ?>" class="wb-btn" wb-action="upload" wb-target="#val-<?php echo $key; ?>">
								</p>
								<h4><?php _e('Title', 'wb'); ?></h4>
								<input type="text" name="promotion_boxes[<?php echo $key; ?>][title]" value="<?php echo $promotion_box['title']; ?>">
								<h4><?php _e('Description', 'wb'); ?></h4>
								<textarea name="promotion_boxes[<?php echo $key; ?>][description]"><?php echo $promotion_box['description']; ?></textarea>
								<span><?php _e('HTML is allowed', 'wb'); ?></span>
								<div class="wb-description">
									<h4><?php _e('Button Text', 'wb'); ?></h4>
									<input type="text" name="promotion_boxes[<?php echo $key; ?>][button_text]" value="<?php echo $promotion_box['button_text']; ?>">
								</div>
								<div class="wb-cta">
									<h4><?php _e('Button URL', 'wb'); ?></h4>
									<input type="text" name="promotion_boxes[<?php echo $key; ?>][button_url]" value="<?php echo $promotion_box['button_url']; ?>" wb-action="autocomplete">
									<span><?php _e('Enter custom URL or page title', 'wb'); ?></span>
								</div>
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
			var __key_promotion_boxes = <?php echo (isset($promotion_boxes) && !empty($promotion_boxes)) ? max(array_keys($promotion_boxes)) + 1 : 0; ?>;
			var __template_promotion_boxes = ' \
				<div class="wb-section wb-slider"> \
					<div class="form-area" style="width: 98%;"> \
						<input type="text" name="promotion_boxes[##key##][image]" id="val-##key##" placeholder="<?php _e('No media selected', 'wb'); ?>" wb-action="upload" wb-target="#val-##key##"> \
						<span><?php _e('Recommended image size: 60 x 55 px', 'wb'); ?></span> \
						<p> \
							<input type="submit" value="<?php _e('Upload', 'wb'); ?>" class="wb-btn" wb-action="upload" wb-target="#val-##key##"> \
						</p> \
						<h4><?php _e('Title', 'wb'); ?></h4> \
						<input type="text" name="promotion_boxes[##key##][title]"> \
						<h4><?php _e('Description', 'wb'); ?></h4> \
						<textarea name="promotion_boxes[##key##][description]"></textarea> \
						<span><?php _e('HTML is allowed', 'wb'); ?></span> \
						<div class="wb-description"> \
							<h4><?php _e('Button Text', 'wb'); ?></h4> \
							<input type="text" name="promotion_boxes[##key##][button_text]"> \
						</div> \
						<div class="wb-cta"> \
							<h4><?php _e('Button URL', 'wb'); ?></h4> \
							<input type="text" name="promotion_boxes[##key##][button_url]" wb-action="autocomplete"> \
							<span><?php _e('Enter custom URL or page title', 'wb'); ?></span> \
						</div> \
						<div class="clear"></div> \
						<a href="#" title="<?php _e('Delete', 'wb'); ?>" class="wb-delete" wb-action="remove"> \
							<i class="fa fa-trash-o"></i> \
						</a> \
					</div> \
					<div class="clear"></div> \
				</div> \
			';
		</script>
		<?php
	}

	public function homepage_featured_categories_meta_box($post) {
		wp_nonce_field('homepage_featured_categories_meta_box', 'homepage_featured_categories_meta_box_nonce');

		$featured_tool_categories = get_post_meta($post->ID, '_featured_tool_categories', true);
		$featured_tool_categories_sort = get_post_meta($post->ID, '_featured_tool_categories_sort', true);

		if ($featured_tool_categories) {
			$featured_tool_categories = get_terms(array(
				'taxonomy' => 'tool-category',
				'include' => $featured_tool_categories
			));
		}

		$featured_tools = get_post_meta($post->ID, '_featured_tools', true);

		//
		$featured_tool_categories_2 = get_post_meta($post->ID, '_featured_tool_categories_2', true);
		$featured_tool_categories_sort_2 = get_post_meta($post->ID, '_featured_tool_categories_sort_2', true);

		if ($featured_tool_categories_2) {
			$featured_tool_categories_2 = get_terms(array(
				'taxonomy' => 'tool-category',
				'include' => $featured_tool_categories_2
			));
		}

		$featured_tools_2 = get_post_meta($post->ID, '_featured_tools_2', true);

		$featured_tool_categories_3 = get_post_meta($post->ID, '_featured_tool_categories_3', true);
		$featured_tool_categories_sort_3 = get_post_meta($post->ID, '_featured_tool_categories_sort_3', true);

		if ($featured_tool_categories_3) {
			$featured_tool_categories_3 = get_terms(array(
				'taxonomy' => 'tool-category',
				'include' => $featured_tool_categories_3
			));
		}

		$featured_tools_3 = get_post_meta($post->ID, '_featured_tools_3', true);
		//

		$featured_course_categories = get_post_meta($post->ID, '_featured_course_categories', true);
		$featured_course_categories_sort = get_post_meta($post->ID, '_featured_course_categories_sort', true);

		if ($featured_course_categories) {
			$featured_course_categories = get_terms(array(
				'taxonomy' => 'course-category',
				'include' => $featured_course_categories
			));
		}

		$featured_courses = get_post_meta($post->ID, '_featured_courses', true);

		$featured_service_categories = get_post_meta($post->ID, '_featured_service_categories', true);
		$featured_service_categories_sort = get_post_meta($post->ID, '_featured_service_categories_sort', true);

		if ($featured_service_categories) {
			$featured_service_categories = get_terms(array(
				'taxonomy' => 'service-category',
				'include' => $featured_service_categories
			));
		}

		$featured_services = get_post_meta($post->ID, '_featured_services', true);

		?>
		<h4><?php _e('Tools #1', 'wb'); ?></h4>
		<p>
			<label for="featured_tool_categories"><?php _e('Categories', 'wb'); ?></label>
			<select name="featured_tool_categories[]" class="widefat" id="featured_tool_categories" multiple>
				<?php if ($featured_tool_categories) : ?>
					<?php foreach ($featured_tool_categories as $featured_tool_category) : ?>
						<option value="<?php echo $featured_tool_category->term_id; ?>" selected>
							<?php echo $featured_tool_category->name; ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			<?php if ($featured_tool_categories) : ?>
				<?php foreach ($featured_tool_categories as $featured_tool_category) : ?>
					<h5><?php echo $featured_tool_category->name; ?></h5>
					<select name="featured_tools[<?php echo $featured_tool_category->term_id; ?>][]" class="widefat" id="featured_tools-<?php echo $featured_tool_category->term_id; ?>" multiple>
						<?php if (isset($featured_tools[$featured_tool_category->term_id]) && $featured_tools[$featured_tool_category->term_id]) : ?>
							<?php

							$tools = get_posts(array(
								'post_type' => 'tool',
								'posts_per_page' => -1,
								'post__in' => $featured_tools[$featured_tool_category->term_id]
							));

							?>
							<?php foreach ($tools as $tool) : ?>
								<option value="<?php echo $tool->ID; ?>" selected>
									<?php echo $tool->post_title; ?>
								</option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<script type="text/javascript">
						jQuery(function ($) {
							$('#featured_tools-<?php echo $featured_tool_category->term_id; ?>').select2({
								placeholder: '<?php _e('Choose tools', 'wb'); ?>',
								allowClear: true,
								tags: true,
								ajax: {
									url: '<?php echo add_query_arg(array('action' => 'get_tools', 'category' => $featured_tool_category->term_id), admin_url('admin-ajax.php')); ?>',
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
				<?php endforeach; ?>
			<?php endif; ?>
		</p>
		<p>
			<label for="featured_tool_categories_sort"><?php _e('Sort By', 'wb'); ?></label>
			<select name="featured_tool_categories_sort" class="widefat" id="featured_tool_categories_sort">
				<option value="0" <?php selected($featured_tool_categories_sort, '0'); ?>>
					<?php _e('Default', 'wb'); ?>
				</option>
				<option value="1" <?php selected($featured_tool_categories_sort, '1'); ?>>
					<?php _e('Alphabetically', 'wb'); ?>
				</option>
				<option value="2" <?php selected($featured_tool_categories_sort, '2'); ?>>
					<?php _e('Popularity', 'wb'); ?>
				</option>
			</select>
		</p>
		<br>
		<hr>
		<br>
		<!-- -->
		<h4><?php _e('Tools #2', 'wb'); ?></h4>
		<p>
			<label for="featured_tool_categories_2"><?php _e('Categories', 'wb'); ?></label>
			<select name="featured_tool_categories_2[]" class="widefat" id="featured_tool_categories_2" multiple>
				<?php if ($featured_tool_categories_2) : ?>
					<?php foreach ($featured_tool_categories_2 as $featured_tool_category) : ?>
						<option value="<?php echo $featured_tool_category->term_id; ?>" selected>
							<?php echo $featured_tool_category->name; ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			<?php if ($featured_tool_categories_2) : ?>
				<?php foreach ($featured_tool_categories_2 as $featured_tool_category) : ?>
					<h5><?php echo $featured_tool_category->name; ?></h5>
					<select name="featured_tools_2[<?php echo $featured_tool_category->term_id; ?>][]" class="widefat" id="featured_tools-<?php echo $featured_tool_category->term_id; ?>_2" multiple>
						<?php if (isset($featured_tools_2[$featured_tool_category->term_id]) && $featured_tools_2[$featured_tool_category->term_id]) : ?>
							<?php

							$tools = get_posts(array(
								'post_type' => 'tool',
								'posts_per_page' => -1,
								'post__in' => $featured_tools_2[$featured_tool_category->term_id]
							));

							?>
							<?php foreach ($tools as $tool) : ?>
								<option value="<?php echo $tool->ID; ?>" selected>
									<?php echo $tool->post_title; ?>
								</option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<script type="text/javascript">
						jQuery(function ($) {
							$('#featured_tools-<?php echo $featured_tool_category->term_id; ?>_2').select2({
								placeholder: '<?php _e('Choose tools', 'wb'); ?>',
								allowClear: true,
								tags: true,
								ajax: {
									url: '<?php echo add_query_arg(array('action' => 'get_tools', 'category' => $featured_tool_category->term_id), admin_url('admin-ajax.php')); ?>',
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
				<?php endforeach; ?>
			<?php endif; ?>
		</p>
		<p>
			<label for="featured_tool_categories_sort_2"><?php _e('Sort By', 'wb'); ?></label>
			<select name="featured_tool_categories_sort_2" class="widefat" id="featured_tool_categories_sort_2">
				<option value="0" <?php selected($featured_tool_categories_sort_2, '0'); ?>>
					<?php _e('Default', 'wb'); ?>
				</option>
				<option value="1" <?php selected($featured_tool_categories_sort_2, '1'); ?>>
					<?php _e('Alphabetically', 'wb'); ?>
				</option>
				<option value="2" <?php selected($featured_tool_categories_sort_2, '2'); ?>>
					<?php _e('Popularity', 'wb'); ?>
				</option>
			</select>
		</p>
		<br>
		<hr>
		<br>
		<h4><?php _e('Tools #3', 'wb'); ?></h4>
		<p>
			<label for="featured_tool_categories_3"><?php _e('Categories', 'wb'); ?></label>
			<select name="featured_tool_categories_3[]" class="widefat" id="featured_tool_categories_3" multiple>
				<?php if ($featured_tool_categories_3) : ?>
					<?php foreach ($featured_tool_categories_3 as $featured_tool_category) : ?>
						<option value="<?php echo $featured_tool_category->term_id; ?>" selected>
							<?php echo $featured_tool_category->name; ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			<?php if ($featured_tool_categories_3) : ?>
				<?php foreach ($featured_tool_categories_3 as $featured_tool_category) : ?>
					<h5><?php echo $featured_tool_category->name; ?></h5>
					<select name="featured_tools_3[<?php echo $featured_tool_category->term_id; ?>][]" class="widefat" id="featured_tools-<?php echo $featured_tool_category->term_id; ?>_3" multiple>
						<?php if (isset($featured_tools_3[$featured_tool_category->term_id]) && $featured_tools_3[$featured_tool_category->term_id]) : ?>
							<?php

							$tools = get_posts(array(
								'post_type' => 'tool',
								'posts_per_page' => -1,
								'post__in' => $featured_tools_3[$featured_tool_category->term_id]
							));

							?>
							<?php foreach ($tools as $tool) : ?>
								<option value="<?php echo $tool->ID; ?>" selected>
									<?php echo $tool->post_title; ?>
								</option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<script type="text/javascript">
						jQuery(function ($) {
							$('#featured_tools-<?php echo $featured_tool_category->term_id; ?>_3').select2({
								placeholder: '<?php _e('Choose tools', 'wb'); ?>',
								allowClear: true,
								tags: true,
								ajax: {
									url: '<?php echo add_query_arg(array('action' => 'get_tools', 'category' => $featured_tool_category->term_id), admin_url('admin-ajax.php')); ?>',
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
				<?php endforeach; ?>
			<?php endif; ?>
		</p>
		<p>
			<label for="featured_tool_categories_sort_3"><?php _e('Sort By', 'wb'); ?></label>
			<select name="featured_tool_categories_sort_3" class="widefat" id="featured_tool_categories_sort_3">
				<option value="0" <?php selected($featured_tool_categories_sort_3, '0'); ?>>
					<?php _e('Default', 'wb'); ?>
				</option>
				<option value="1" <?php selected($featured_tool_categories_sort_3, '1'); ?>>
					<?php _e('Alphabetically', 'wb'); ?>
				</option>
				<option value="2" <?php selected($featured_tool_categories_sort_3, '2'); ?>>
					<?php _e('Popularity', 'wb'); ?>
				</option>
			</select>
		</p>
		<br>
		<hr>
		<br>
		<!-- -->
		<h4><?php _e('Courses', 'wb'); ?></h4>
		<p>
			<label for="featured_course_categories"><?php _e('Categories', 'wb'); ?></label>
			<select name="featured_course_categories[]" class="widefat" id="featured_course_categories" multiple>
				<?php if ($featured_course_categories) : ?>
					<?php foreach ($featured_course_categories as $featured_course_category) : ?>
						<option value="<?php echo $featured_course_category->term_id; ?>" selected>
							<?php echo $featured_course_category->name; ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			<?php if ($featured_course_categories) : ?>
				<?php foreach ($featured_course_categories as $featured_course_category) : ?>
					<h5><?php echo $featured_course_category->name; ?></h5>
					<select name="featured_courses[<?php echo $featured_course_category->term_id; ?>][]" class="widefat" id="featured_courses-<?php echo $featured_course_category->term_id; ?>" multiple>
						<?php if (isset($featured_courses[$featured_course_category->term_id]) && $featured_courses[$featured_course_category->term_id]) : ?>
							<?php

							$courses = get_posts(array(
								'post_type' => 'course',
								'posts_per_page' => -1,
								'post__in' => $featured_courses[$featured_course_category->term_id]
							));

							?>
							<?php foreach ($courses as $course) : ?>
								<option value="<?php echo $course->ID; ?>" selected>
									<?php echo $course->post_title; ?>
								</option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<script type="text/javascript">
						jQuery(function ($) {
							$('#featured_courses-<?php echo $featured_course_category->term_id; ?>').select2({
								placeholder: '<?php _e('Choose courses', 'wb'); ?>',
								allowClear: true,
								tags: true,
								ajax: {
									url: '<?php echo add_query_arg(array('action' => 'get_courses', 'category' => $featured_course_category->term_id), admin_url('admin-ajax.php')); ?>',
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
				<?php endforeach; ?>
			<?php endif; ?>
		</p>
		<p>
			<label for="featured_course_categories_sort"><?php _e('Sort By', 'wb'); ?></label>
			<select name="featured_course_categories_sort" class="widefat" id="featured_course_categories_sort">
				<option value="0" <?php selected($featured_course_categories_sort, '0'); ?>>
					<?php _e('Default', 'wb'); ?>
				</option>
				<option value="1" <?php selected($featured_course_categories_sort, '1'); ?>>
					<?php _e('Alphabetically', 'wb'); ?>
				</option>
				<option value="2" <?php selected($featured_course_categories_sort, '2'); ?>>
					<?php _e('Popularity', 'wb'); ?>
				</option>
			</select>
		</p>
		<br>
		<hr>
		<br>
		<h4><?php _e('Services', 'wb'); ?></h4>
		<p>
			<label for="featured_service_categories"><?php _e('Categories', 'wb'); ?></label>
			<select name="featured_service_categories[]" class="widefat" id="featured_service_categories" multiple>
				<?php if ($featured_service_categories) : ?>
					<?php foreach ($featured_service_categories as $featured_service_category) : ?>
						<option value="<?php echo $featured_service_category->term_id; ?>" selected>
							<?php echo $featured_service_category->name; ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			<?php if ($featured_service_categories) : ?>
				<?php foreach ($featured_service_categories as $featured_service_category) : ?>
					<h5><?php echo $featured_service_category->name; ?></h5>
					<select name="featured_services[<?php echo $featured_service_category->term_id; ?>][]" class="widefat" id="featured_services-<?php echo $featured_service_category->term_id; ?>" multiple>
						<?php if (isset($featured_services[$featured_service_category->term_id]) && $featured_services[$featured_service_category->term_id]) : ?>
							<?php

							$services = get_posts(array(
								'post_type' => 'service',
								'posts_per_page' => -1,
								'post__in' => $featured_services[$featured_service_category->term_id]
							));

							?>
							<?php foreach ($services as $service) : ?>
								<option value="<?php echo $service->ID; ?>" selected>
									<?php echo $service->post_title; ?>
								</option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<script type="text/javascript">
						jQuery(function ($) {
							$('#featured_services-<?php echo $featured_service_category->term_id; ?>').select2({
								placeholder: '<?php _e('Choose services', 'wb'); ?>',
								allowClear: true,
								tags: true,
								ajax: {
									url: '<?php echo add_query_arg(array('action' => 'get_services', 'category' => $featured_service_category->term_id), admin_url('admin-ajax.php')); ?>',
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
				<?php endforeach; ?>
			<?php endif; ?>
		</p>
		<p>
			<label for="featured_service_categories_sort"><?php _e('Sort By', 'wb'); ?></label>
			<select name="featured_service_categories_sort" class="widefat" id="featured_service_categories_sort">
				<option value="0" <?php selected($featured_service_categories_sort, '0'); ?>>
					<?php _e('Default', 'wb'); ?>
				</option>
				<option value="1" <?php selected($featured_service_categories_sort, '1'); ?>>
					<?php _e('Alphabetically', 'wb'); ?>
				</option>
				<option value="2" <?php selected($featured_service_categories_sort, '2'); ?>>
					<?php _e('Popularity', 'wb'); ?>
				</option>
			</select>
		</p>
		<script type="text/javascript">
			jQuery(function ($) {
				$('#featured_tool_categories, #featured_tool_categories_2, #featured_tool_categories_3').select2({
					placeholder: '<?php _e('Choose categories', 'wb'); ?>',
					allowClear: true,
					tags: true,
					ajax: {
						url: '<?php echo add_query_arg('action', 'get_tool_categories', admin_url('admin-ajax.php')); ?>',
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

				$('#featured_course_categories').select2({
					placeholder: '<?php _e('Choose categories', 'wb'); ?>',
					allowClear: true,
					tags: true,
					ajax: {
						url: '<?php echo add_query_arg('action', 'get_course_categories', admin_url('admin-ajax.php')); ?>',
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

				$('#featured_service_categories').select2({
					placeholder: '<?php _e('Choose categories', 'wb'); ?>',
					allowClear: true,
					tags: true,
					ajax: {
						url: '<?php echo add_query_arg('action', 'get_service_categories', admin_url('admin-ajax.php')); ?>',
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

	public function homepage_featured_content_categories_meta_box($post) {
		wp_nonce_field('homepage_featured_content_categories_meta_box', 'homepage_featured_content_categories_meta_box_nonce');

		$featured_content_categories = get_post_meta($post->ID, '_featured_content_categories', true);
		$featured_content_categories_sort = get_post_meta($post->ID, '_featured_content_categories_sort', true);

		if ($featured_content_categories) {
			$featured_content_categories = get_terms(array(
				'taxonomy' => 'category',
				'include' => $featured_content_categories
			));
		}

		$featured_content = get_post_meta($post->ID, '_featured_content', true);

		$featured_content_categories_2 = get_post_meta($post->ID, '_featured_content_categories_2', true);
		$featured_content_categories_sort_2 = get_post_meta($post->ID, '_featured_content_categories_sort_2', true);

		if ($featured_content_categories_2) {
			$featured_content_categories_2 = get_terms(array(
				'taxonomy' => 'category',
				'include' => $featured_content_categories_2
			));
		}

		$featured_content_2 = get_post_meta($post->ID, '_featured_content_2', true);

		?>
		<h4><?php _e('Content #1', 'wb'); ?></h4>
		<p>
			<label for="featured_content_categories"><?php _e('Categories', 'wb'); ?></label>
			<select name="featured_content_categories[]" class="widefat" id="featured_content_categories" multiple>
				<?php if ($featured_content_categories) : ?>
					<?php foreach ($featured_content_categories as $featured_content_category) : ?>
						<option value="<?php echo $featured_content_category->term_id; ?>" selected>
							<?php echo $featured_content_category->name; ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			<?php if ($featured_content_categories) : ?>
				<?php foreach ($featured_content_categories as $featured_content_category) : ?>
					<h5><?php echo $featured_content_category->name; ?></h5>
					<select name="featured_content[<?php echo $featured_content_category->term_id; ?>][]" class="widefat" id="featured_content-<?php echo $featured_content_category->term_id; ?>" multiple>
						<?php if (isset($featured_content[$featured_content_category->term_id]) && $featured_content[$featured_content_category->term_id]) : ?>
							<?php

							$content = get_posts(array(
								'post_type' => 'post',
								'posts_per_page' => -1,
								'post__in' => $featured_content[$featured_content_category->term_id]
							));

							?>
							<?php foreach ($content as $content_post) : ?>
								<option value="<?php echo $content_post->ID; ?>" selected>
									<?php echo $content_post->post_title; ?>
								</option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<script type="text/javascript">
						jQuery(function ($) {
							$('#featured_content-<?php echo $featured_content_category->term_id; ?>').select2({
								placeholder: '<?php _e('Choose posts', 'wb'); ?>',
								allowClear: true,
								tags: true,
								ajax: {
									url: '<?php echo add_query_arg(array('action' => 'get_posts', 'category' => $featured_content_category->term_id), admin_url('admin-ajax.php')); ?>',
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
				<?php endforeach; ?>
			<?php endif; ?>
		</p>
		<p>
			<label for="featured_content_categories_sort"><?php _e('Sort By', 'wb'); ?></label>
			<select name="featured_content_categories_sort" class="widefat" id="featured_content_categories_sort">
				<option value="0" <?php selected($featured_content_categories_sort, '0'); ?>>
					<?php _e('Default', 'wb'); ?>
				</option>
				<option value="1" <?php selected($featured_content_categories_sort, '1'); ?>>
					<?php _e('Alphabetically', 'wb'); ?>
				</option>
				<option value="2" <?php selected($featured_content_categories_sort, '2'); ?>>
					<?php _e('Popularity', 'wb'); ?>
				</option>
			</select>
		</p>
		<br>
		<hr>
		<br>
		<h4><?php _e('Content #2', 'wb'); ?></h4>
		<p>
			<label for="featured_content_categories_2"><?php _e('Categories', 'wb'); ?></label>
			<select name="featured_content_categories_2[]" class="widefat" id="featured_content_categories_2" multiple>
				<?php if ($featured_content_categories_2) : ?>
					<?php foreach ($featured_content_categories_2 as $featured_content_category) : ?>
						<option value="<?php echo $featured_content_category->term_id; ?>" selected>
							<?php echo $featured_content_category->name; ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			<?php if ($featured_content_categories_2) : ?>
				<?php foreach ($featured_content_categories_2 as $featured_content_category) : ?>
					<h5><?php echo $featured_content_category->name; ?></h5>
					<select name="featured_content_2[<?php echo $featured_content_category->term_id; ?>][]" class="widefat" id="featured_content-<?php echo $featured_content_category->term_id; ?>_2" multiple>
						<?php if (isset($featured_content_2[$featured_content_category->term_id]) && $featured_content_2[$featured_content_category->term_id]) : ?>
							<?php

							$content = get_posts(array(
								'post_type' => 'post',
								'posts_per_page' => -1,
								'post__in' => $featured_content_2[$featured_content_category->term_id]
							));

							?>
							<?php foreach ($content as $content_post) : ?>
								<option value="<?php echo $content_post->ID; ?>" selected>
									<?php echo $content_post->post_title; ?>
								</option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<script type="text/javascript">
						jQuery(function ($) {
							$('#featured_content-<?php echo $featured_content_category->term_id; ?>_2').select2({
								placeholder: '<?php _e('Choose posts', 'wb'); ?>',
								allowClear: true,
								tags: true,
								ajax: {
									url: '<?php echo add_query_arg(array('action' => 'get_posts', 'category' => $featured_content_category->term_id), admin_url('admin-ajax.php')); ?>',
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
				<?php endforeach; ?>
			<?php endif; ?>
		</p>
		<p>
			<label for="featured_content_categories_sort_2"><?php _e('Sort By', 'wb'); ?></label>
			<select name="featured_content_categories_sort_2" class="widefat" id="featured_content_categories_sort_2">
				<option value="0" <?php selected($featured_content_categories_sort_2, '0'); ?>>
					<?php _e('Default', 'wb'); ?>
				</option>
				<option value="1" <?php selected($featured_content_categories_sort_2, '1'); ?>>
					<?php _e('Alphabetically', 'wb'); ?>
				</option>
				<option value="2" <?php selected($featured_content_categories_sort_2, '2'); ?>>
					<?php _e('Popularity', 'wb'); ?>
				</option>
			</select>
		</p>
		<script type="text/javascript">
			jQuery(function ($) {
				$('#featured_content_categories, #featured_content_categories_2').select2({
					placeholder: '<?php _e('Choose categories', 'wb'); ?>',
					allowClear: true,
					tags: true,
					ajax: {
						url: '<?php echo add_query_arg('action', 'get_post_categories', admin_url('admin-ajax.php')); ?>',
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

	public function homepage_promotion_text_meta_box($post) {
		wp_nonce_field('homepage_promotion_text_meta_box', 'homepage_promotion_text_meta_box_nonce');

		$promotion_text_title = get_post_meta($post->ID, '_promotion_text_title', true);
		$promotion_text_description = get_post_meta($post->ID, '_promotion_text_description', true);
		$promotion_text_button_text = get_post_meta($post->ID, '_promotion_text_button_text', true);
		$promotion_text_button_url = get_post_meta($post->ID, '_promotion_text_button_url', true);

		?>
		<div id="wb-framework">
			<div class="wb-section">
				<div class="form-area" style="width: 100%;">
					<h4><?php _e('Title', 'wb'); ?></h4>
					<input type="text" name="promotion_text_title" value="<?php echo $promotion_text_title; ?>">
					<h4><?php _e('Description', 'wb'); ?></h4>
					<?php wp_editor($promotion_text_description, 'promotion_text_description', 'textarea_name=promotion_text_description&textarea_rows=5'); ?>
					<span><?php _e('HTML is allowed', 'wb'); ?></span>
					<div class="wb-description">
						<h4><?php _e('Button Text', 'wb'); ?></h4>
						<input type="text" name="promotion_text_button_text" value="<?php echo $promotion_text_button_text; ?>">
					</div>
					<div class="wb-cta">
						<h4><?php _e('Button URL', 'wb'); ?></h4>
						<input type="text" name="promotion_text_button_url" value="<?php echo $promotion_text_button_url; ?>" wb-action="autocomplete">
						<span><?php _e('Enter custom URL or page title', 'wb'); ?></span>
					</div>
					<div class="clear"></div>
				</div>
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

		if (isset($_POST['homepage_promotion_boxes_meta_box_nonce']) && wp_verify_nonce($_POST['homepage_promotion_boxes_meta_box_nonce'], 'homepage_promotion_boxes_meta_box')) {
			if ($_POST['promotion_boxes']) {
				update_post_meta($post_id, '_promotion_boxes', $_POST['promotion_boxes']);
			} else {
				delete_post_meta($post_id, '_promotion_boxes');
			}
		}

		if (isset($_POST['homepage_featured_categories_meta_box_nonce']) && wp_verify_nonce($_POST['homepage_featured_categories_meta_box_nonce'], 'homepage_featured_categories_meta_box')) {
			update_post_meta($post_id, '_featured_tool_categories_sort', esc_attr($_POST['featured_tool_categories_sort']));

			if ($_POST['featured_tool_categories']) {
				update_post_meta($post_id, '_featured_tool_categories', $_POST['featured_tool_categories']);
			} else {
				delete_post_meta($post_id, '_featured_tool_categories');
			}

			if ($_POST['featured_tools']) {
				update_post_meta($post_id, '_featured_tools', $_POST['featured_tools']);
			} else {
				delete_post_meta($post_id, '_featured_tools');
			}

			update_post_meta($post_id, '_featured_course_categories_sort', esc_attr($_POST['featured_course_categories_sort']));

			//
			if ($_POST['featured_tool_categories_2']) {
				update_post_meta($post_id, '_featured_tool_categories_2', $_POST['featured_tool_categories_2']);
			} else {
				delete_post_meta($post_id, '_featured_tool_categories_2');
			}

			if ($_POST['featured_tools_2']) {
				update_post_meta($post_id, '_featured_tools_2', $_POST['featured_tools_2']);
			} else {
				delete_post_meta($post_id, '_featured_tools_2');
			}

			update_post_meta($post_id, '_featured_course_categories_sort_2', esc_attr($_POST['featured_course_categories_sort_2']));

			if ($_POST['featured_tool_categories_3']) {
				update_post_meta($post_id, '_featured_tool_categories_3', $_POST['featured_tool_categories_3']);
			} else {
				delete_post_meta($post_id, '_featured_tool_categories_3');
			}

			if ($_POST['featured_tools_3']) {
				update_post_meta($post_id, '_featured_tools_3', $_POST['featured_tools_3']);
			} else {
				delete_post_meta($post_id, '_featured_tools_3');
			}

			update_post_meta($post_id, '_featured_course_categories_sort_3', esc_attr($_POST['featured_course_categories_sort_3']));
			//

			if ($_POST['featured_course_categories']) {
				update_post_meta($post_id, '_featured_course_categories', $_POST['featured_course_categories']);
			} else {
				delete_post_meta($post_id, '_featured_course_categories');
			}

			if ($_POST['featured_courses']) {
				update_post_meta($post_id, '_featured_courses', $_POST['featured_courses']);
			} else {
				delete_post_meta($post_id, '_featured_courses');
			}

			update_post_meta($post_id, '_featured_service_categories_sort', esc_attr($_POST['featured_service_categories_sort']));

			if ($_POST['featured_service_categories']) {
				update_post_meta($post_id, '_featured_service_categories', $_POST['featured_service_categories']);
			} else {
				delete_post_meta($post_id, '_featured_service_categories');
			}

			if ($_POST['featured_services']) {
				update_post_meta($post_id, '_featured_services', $_POST['featured_services']);
			} else {
				delete_post_meta($post_id, '_featured_services');
			}
		}

		if (isset($_POST['homepage_featured_content_categories_meta_box_nonce']) && wp_verify_nonce($_POST['homepage_featured_content_categories_meta_box_nonce'], 'homepage_featured_content_categories_meta_box')) {
			update_post_meta($post_id, '_featured_content_categories_sort', esc_attr($_POST['featured_content_categories_sort']));

			if ($_POST['featured_content_categories']) {
				update_post_meta($post_id, '_featured_content_categories', $_POST['featured_content_categories']);
			} else {
				delete_post_meta($post_id, '_featured_content_categories');
			}

			if ($_POST['featured_content']) {
				update_post_meta($post_id, '_featured_content', $_POST['featured_content']);
			} else {
				delete_post_meta($post_id, '_featured_content');
			}

			update_post_meta($post_id, '_featured_content_categories_sort_2', esc_attr($_POST['featured_content_categories_sort_2']));

			if ($_POST['featured_content_categories_2']) {
				update_post_meta($post_id, '_featured_content_categories_2', $_POST['featured_content_categories_2']);
			} else {
				delete_post_meta($post_id, '_featured_content_categories_2');
			}

			if ($_POST['featured_content_2']) {
				update_post_meta($post_id, '_featured_content_2', $_POST['featured_content_2']);
			} else {
				delete_post_meta($post_id, '_featured_content_2');
			}
		}

		if (isset($_POST['homepage_promotion_text_meta_box_nonce']) && wp_verify_nonce($_POST['homepage_promotion_text_meta_box_nonce'], 'homepage_promotion_text_meta_box')) {
			update_post_meta($post_id, '_promotion_text_title', esc_attr($_POST['promotion_text_title']));
			update_post_meta($post_id, '_promotion_text_description', $_POST['promotion_text_description']);
			update_post_meta($post_id, '_promotion_text_button_text', esc_attr($_POST['promotion_text_button_text']));
			update_post_meta($post_id, '_promotion_text_button_url', esc_url($_POST['promotion_text_button_url']));
		}
	}

}
