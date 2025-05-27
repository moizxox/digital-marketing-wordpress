<?php

if (!defined('ABSPATH')) {
	exit;
}

wp_enqueue_script('typed', WB_THEME_URL . '/js/typed.js', array('main'));
wp_enqueue_script('owl.carousel', WB_THEME_URL . '/js/owl.carousel.js', array('typed'));

get_header();

the_post();

if (($popular_terms = wp_cache_get('_popular_terms')) === false) {
	$popular_terms = get_terms(array(
		'taxonomy' => array(
			'tool-category',
			'tool-tag',
			'course-category',
			'course-tag',
			'service-category',
			'service-tag'
		),
		'number' => 4,
		'meta_query' => array(
			array(
				'key' => '_views',
				'type' => 'NUMERIC'
			)
		),
		'orderby' => 'meta_value_num',
		'order' => 'DESC'
	));

	wp_cache_set('_popular_terms', $popular_terms, 'wb', DAY_IN_SECONDS);
}

$featured_tools = get_post_meta($post->ID, '_featured_tools', true);
$featured_tool_categories = get_post_meta($post->ID, '_featured_tool_categories', true);
$featured_tool_categories_sort = get_post_meta($post->ID, '_featured_tool_categories_sort', true);

if ($featured_tool_categories) {
	$featured_tool_categories = get_terms(array(
		'taxonomy' => 'tool-category',
		'include' => $featured_tool_categories
	));
}

//
$featured_tools_2 = get_post_meta($post->ID, '_featured_tools_2', true);
$featured_tool_categories_2 = get_post_meta($post->ID, '_featured_tool_categories_2', true);
$featured_tool_categories_sort_2 = get_post_meta($post->ID, '_featured_tool_categories_sort_2', true);

if ($featured_tool_categories_2) {
	$featured_tool_categories_2 = get_terms(array(
		'taxonomy' => 'tool-category',
		'include' => $featured_tool_categories_2
	));
}

$featured_tools_3 = get_post_meta($post->ID, '_featured_tools_3', true);
$featured_tool_categories_3 = get_post_meta($post->ID, '_featured_tool_categories_3', true);
$featured_tool_categories_sort_3 = get_post_meta($post->ID, '_featured_tool_categories_sort_3', true);

if ($featured_tool_categories_3) {
	$featured_tool_categories_3 = get_terms(array(
		'taxonomy' => 'tool-category',
		'include' => $featured_tool_categories_3
	));
}
//

$featured_courses = get_post_meta($post->ID, '_featured_courses', true);
$featured_course_categories = get_post_meta($post->ID, '_featured_course_categories', true);
$featured_course_categories_sort = get_post_meta($post->ID, '_featured_course_categories_sort', true);

if ($featured_course_categories) {
	$featured_course_categories = get_terms(array(
		'taxonomy' => 'course-category',
		'include' => $featured_course_categories
	));
}

$featured_services = get_post_meta($post->ID, '_featured_services', true);
$featured_service_categories = get_post_meta($post->ID, '_featured_service_categories', true);
$featured_service_categories_sort = get_post_meta($post->ID, '_featured_service_categories_sort', true);

if ($featured_service_categories) {
	$featured_service_categories = get_terms(array(
		'taxonomy' => 'service-category',
		'include' => $featured_service_categories
	));
}

?>

<div class="hero heading-bg hero_main">
	<div class="container">
		<div class="hero__inner">
			<div class="row">
				<div class="col-lg-7">
					<div class="hero__info">
						<h1 class="hero__title">
							<span><?php _e('One Stop Shop For', 'wb'); ?> </span>
							<span><?php _e(sprintf('All Your %sDigital', '<br>'), 'wb'); ?> </span>
							<span><?php _e('Marketing Needs', 'wb'); ?></span>
						</h1>
						<div class="hero-search-wrap">
							<h3 class="hero-search-wrap__title text-md">
								<span class="hero-search-wrap__title__text">
									<?php _e('Search for Digital Marketing', 'wb'); ?>
								</span>
								<?php /** <span class="element" data-text1="Tools" data-text2="Courses" data-text3="Services" data-loop="true" data-backdelay="3000"> */ ?>
								<span class="element" data-text1="Tools" data-text2="Courses" data-loop="true" data-backdelay="3000">
									<?php _e('Tools', 'wb'); ?>
								</span>
							</h3>
							<?php if ($search_page = wb_get_page_by_template('search')) : ?>
								<form action="<?php echo get_permalink($search_page); ?>" method="get">
									<div class="hero-search">
										<div class="hero-search__input">
											<input type="text" name="query" class="form-control" placeholder="<?php _e('e.g. SEO or Email Marketing', 'wb'); ?>">
										</div>
										<div class="hero-search__append">
											<button type="submit" class="btn btn-green hero-search__btn">
												<i class="icon icon-search"></i> <?php _e('SEARCH', 'wb'); ?>
											</button>
										</div>
									</div>
									<ul class="search-filter hero-search__filter">
										<li>
											<label class="radio">
												<input type="radio" name="type" value="tool" checked>
												<?php _e('Tools', 'wb'); ?>
											</label>
										</li>
										<li>
											<label class="radio">
												<input type="radio" name="type" value="course">
												<?php _e('Courses', 'wb'); ?>
											</label>
										</li>
										<!--
											<li>
												<label class="radio">
													<input type="radio" name="type" value="service">
													<?php _e('Services', 'wb'); ?>
												</label>
											</li>
										-->
									</ul>
									<div class="hero-search__mob">
										<button type="submit" class="btn btn-green hero-search__btn">
											<i class="icon icon-search"></i> <?php _e('SEARCH', 'wb'); ?>
										</button>
									</div>
								</form>
							<?php endif; ?>
						</div>
						<?php if ($popular_terms) : ?>
							<div class="tags hero-tags">
								<div class="tags-label"><?php _e('Popular', 'wb'); ?>:</div>
								<ul class="tags-list">
									<?php foreach ($popular_terms as $popular_term) : ?>
										<li>
											<a href="<?php echo get_term_link($popular_term); ?>" class="btn-tag">
												<?php echo $popular_term->name; ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="col-lg-5">
					<div class="hero__image">
						<div class="hero__image__inner">
							<img class="hero__image__bg" src="<?php echo WB_THEME_URL; ?>/images/hero-bg.png" srcset="<?php echo WB_THEME_URL; ?>/images/hero-bg@2x.png 2x" alt="<?php _e('BG', 'wb'); ?>">
							<img class="hero__image__girl flot-y" src="<?php echo WB_THEME_URL; ?>/images/hero-girl.png" srcset="<?php echo WB_THEME_URL; ?>/images/hero-girl@2x.png 2x" alt="<?php _e('Girl', 'wb'); ?>">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if ($promotion_boxes = get_post_meta($post->ID, '_promotion_boxes', true)) : ?>
	<?php unset($promotion_boxes[2]); // temp  ?>
	<div class="ml-boxes">
		<div class="container ml-boxes__container">
			<div class="ml-boxes__inner">
				<div class="row ml-boxes__grid m-0">
					<?php foreach ($promotion_boxes as $promotion_box) : ?>
						<div class="<?php echo (count($promotion_boxes) == 2) ? 'col-lg-6' : 'col-lg-4'; ?> ml-boxes__col p-0">
							<a href="<?php echo $promotion_box['button_url']; ?>" class="ml-box">
								<div class="ml-box__inner">
									<?php if ($image = $promotion_box['image']) : ?>
										<div class="ml-box__icon">
											<img src="<?php echo wb_image($image, 60, 55); ?>" alt="<?php echo $promotion_box['title']; ?>">
										</div>
									<?php endif; ?>
									<div class="ml-box__info">
										<?php if ($title = $promotion_box['title']) : ?>
											<h3 class="ml__title"><?php echo $title; ?></h3>
										<?php endif; ?>
										<?php echo wpautop($promotion_box['description']); ?>
										<?php if ($button_text = $promotion_box['button_text']) : ?>
											<span class="ml__btn btn btn-outline-white">
												<?php echo $button_text; ?>
											</span>
										<?php endif; ?>
									</div>
								</div>
							</a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if (is_active_sidebar('content-homepage')) : ?>
	<div class="subscribe-wrap subscribe-wrap_main">
		<div class="container">
			<?php dynamic_sidebar('content-homepage'); ?>
		</div>
	</div>
<?php endif; ?>

<div class="products-carousels">
	<?php if ($featured_tool_categories) : ?>
		<div class="products-carousel-tabs" style="padding-bottom: 0px;">
			<div class="container">
				<div class="products-carousel-tabs__heading">
					<div class="products-carousel-tabs__heading__left">
						<span class="products-carousel-tabs__count badge-num badge-num">
							<?php echo array_sum(array_column($featured_tool_categories, 'count')); ?>
						</span>
						<h2 class="text-lg products-carousel-tabs__title">
							<?php _e('Digital Marketing Tools', 'wb'); ?>
						</h2>
					</div>
					<?php if ($tools_page = wb_get_page_by_template('tools')) : ?>
						<a href="<?php echo get_permalink($tools_page); ?>" class="btn btn-outline-blue carousel-tabs__view">
							<?php _e('View All', 'wb'); ?>
						</a>
					<?php endif; ?>
				</div>
				<div class="carousel-tabs tabsi">
					<ul class="carousel-tabs-nav tabs-nav">
						<?php $i = 0; foreach ($featured_tool_categories as $featured_tool_category) : ?>
							<li <?php echo ($i == 0) ? 'class="current_tab"' : ''; ?>>
								<a href="<?php echo get_term_link($featured_tool_category); ?>" data-type="tool" data-category="<?php echo $featured_tool_category->term_id; ?>">
									<?php echo $featured_tool_category->name; ?>
								</a>
							</li>
						<?php $i++; endforeach; ?>
					</ul>
					<div class="carousel-tabs-content tabs-content">
						<?php $i = 0; foreach ($featured_tool_categories as $featured_tool_category) : ?>
							<div class="tabs-content-tab <?php echo ($i == 0) ? 'active_tab' : ''; ?>">
								<?php if ($i == 0) : ?>
									<div class="carousel-products owl-carousel" data-autoplay="false" data-nav-text="[&quot;&lt;i class='icon icon-arrow-right'&gt;&lt;/i&gt; &quot;,&quot;&lt;i class='ficon icon-arrow-right'&gt;&lt;/i&gt;&quot;]" data-nav="true" data-dots="false" data-loop="true" data-slidespeed="200" data-margin="54" data-responsive="{&quot;0&quot;:{ &quot;margin&quot; : 20, &quot;items&quot;: &quot;1&quot;}, &quot;600&quot;:{&quot;margin&quot; : 20, &quot;items&quot;: &quot;2&quot;}, &quot;850&quot;:{&quot;margin&quot; : 15 , &quot;items&quot;: &quot;3&quot;}, &quot;1200&quot;:{&quot;items&quot;: &quot;4&quot;}}">
										<?php

										if (!isset($featured_tools[$featured_tool_category->term_id]) || !$featured_tools[$featured_tool_category->term_id]) {
											continue;
										}

										$tools_args = array(
											'post_type' => 'tool',
											'posts_per_page' => 8,
											'post__in' => $featured_tools[$featured_tool_category->term_id]
										);

										if ($featured_tool_categories_sort == '1') {
											$tools_args['orderby'] = 'name';
											$tools_args['order'] = 'ASC';
										} else if ($featured_tool_categories_sort == '2') {
											$tools_args['meta_query'][] = array(
												'key' => '_views',
												'type' => 'NUMERIC'
											);

											$tools_args['orderby'] = 'meta_value_num';
											$tools_args['order'] = 'DESC';
										}

										$tools = get_posts($tools_args);

										?>
										<?php foreach ($tools as $tool) : ?>
											<div class="item">
												<div class="product-box">
													<?php if (has_post_thumbnail($tool)) : ?>
														<div class="product-box__image">
															<a href="<?php echo get_permalink($tool); ?>">
																<?php echo get_the_post_thumbnail($tool, '480x360'); ?>
															</a>
														</div>
													<?php endif; ?>
													<div class="product-box__info">
														<h3 class="product-box__title">
															<a href="<?php echo get_permalink($tool); ?>">
																<?php echo get_the_title($tool); ?>
															</a>
														</h3>
														<div class="product-box__text">
															<p><?php echo $tool->post_excerpt ? mb_strimwidth(strip_tags($tool->post_content), 0, 180, '...') : mb_strimwidth(strip_tags($tool->post_content), 0, 180, '...'); ?></p>
														</div>
													</div>
													<?php $amount = get_post_meta($tool->ID, '_amount', true); ?>
													<?php if ($amount != '') : ?>
														<div class="product-box__price">
															<?php if ($amount === '0') : ?>
																<?php _e('FREE', 'wb'); ?> <div class="price"></div>
															<?php elseif ($amount === 'Contact Vendor') : ?>
																<?php _e('Contact Vendor', 'wb'); ?> <div class="price"></div>
															<?php else : ?>
																<?php echo is_numeric($amount) ? __('Price from', 'wb') : ''; ?><div class="price"><?php echo get_post_meta($tool->ID, '_currency', true); ?><?php echo $amount; ?></div>
															<?php endif; ?>
														</div>
													<?php endif; ?>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								<?php else : ?>
									<div id="content-<?php echo $featured_tool_category->term_id; ?>"></div>
								<?php endif; ?>
							</div>
						<?php $i++; endforeach; ?>
					</div>
					<?php if ($tools_page) : ?>
						<div class="carousel-tabs__view-mob">
							<a href="<?php echo get_permalink($tools_page); ?>" class="btn btn-outline-blue">
								<?php _e('View All', 'wb'); ?>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<!-- -->
	<?php if ($featured_tool_categories_2) : ?>
		<div class="products-carousel-tabs-2">
			<div class="container">
				<div class="carousel-tabs tabsi">
					<ul class="carousel-tabs-nav tabs-nav">
						<?php $i = 0; foreach ($featured_tool_categories_2 as $featured_tool_category) : ?>
							<li <?php echo ($i == 0) ? 'class="current_tab"' : ''; ?>>
								<a href="<?php echo get_term_link($featured_tool_category); ?>" data-type="tool" data-category="<?php echo $featured_tool_category->term_id; ?>">
									<?php echo $featured_tool_category->name; ?>
								</a>
							</li>
						<?php $i++; endforeach; ?>
					</ul>
					<div class="carousel-tabs-content tabs-content">
						<?php $i = 0; foreach ($featured_tool_categories_2 as $featured_tool_category) : ?>
							<div class="tabs-content-tab <?php echo ($i == 0) ? 'active_tab' : ''; ?>">
								<?php if (isset($featured_tools_2[$featured_tool_category->term_id]) && $featured_tools_2[$featured_tool_category->term_id]) : ?>
									<?php if ($i == 0) : ?>
										<div class="carousel-products owl-carousel" data-autoplay="false" data-nav-text="[&quot;&lt;i class='icon icon-arrow-right'&gt;&lt;/i&gt; &quot;,&quot;&lt;i class='ficon icon-arrow-right'&gt;&lt;/i&gt;&quot;]" data-nav="true" data-dots="false" data-loop="true" data-slidespeed="200" data-margin="54" data-responsive="{&quot;0&quot;:{ &quot;margin&quot; : 20, &quot;items&quot;: &quot;1&quot;}, &quot;600&quot;:{&quot;margin&quot; : 20, &quot;items&quot;: &quot;2&quot;}, &quot;850&quot;:{&quot;margin&quot; : 15 , &quot;items&quot;: &quot;3&quot;}, &quot;1200&quot;:{&quot;items&quot;: &quot;4&quot;}}">
											<?php

											$tools_args = array(
												'post_type' => 'tool',
												'posts_per_page' => 8,
												'post__in' => $featured_tools_2[$featured_tool_category->term_id]
											);

											if ($featured_tool_categories_sort_2 == '1') {
												$tools_args['orderby'] = 'name';
												$tools_args['order'] = 'ASC';
											} else if ($featured_tool_categories_sort_2 == '2') {
												$tools_args['meta_query'][] = array(
													'key' => '_views',
													'type' => 'NUMERIC'
												);

												$tools_args['orderby'] = 'meta_value_num';
												$tools_args['order'] = 'DESC';
											}

											$tools = get_posts($tools_args);

											?>
											<?php foreach ($tools as $tool) : ?>
												<div class="item">
													<div class="product-box">
														<?php if (has_post_thumbnail($tool)) : ?>
															<div class="product-box__image">
																<a href="<?php echo get_permalink($tool); ?>">
																	<?php echo get_the_post_thumbnail($tool, '480x360'); ?>
																</a>
															</div>
														<?php endif; ?>
														<div class="product-box__info">
															<h3 class="product-box__title">
																<a href="<?php echo get_permalink($tool); ?>">
																	<?php echo get_the_title($tool); ?>
																</a>
															</h3>
															<div class="product-box__text">
																<p><?php echo $tool->post_excerpt ? mb_strimwidth($tool->post_content, 0, 180, '...') : mb_strimwidth($tool->post_content, 0, 180, '...'); ?></p>
															</div>
														</div>
														<?php $amount = get_post_meta($tool->ID, '_amount', true); ?>
														<?php if ($amount != '') : ?>
															<div class="product-box__price">
																<?php if ($amount === '0') : ?>
																	<?php _e('FREE', 'wb'); ?> <div class="price"></div>
																<?php elseif ($amount === 'Contact Vendor') : ?>
																	<?php _e('Contact Vendor', 'wb'); ?> <div class="price"></div>
																<?php else : ?>
																	<?php echo is_numeric($amount) ? __('Price from', 'wb') : ''; ?><div class="price"><?php echo get_post_meta($tool->ID, '_currency', true); ?><?php echo $amount; ?></div>
																<?php endif; ?>
															</div>
														<?php endif; ?>
													</div>
												</div>
											<?php endforeach; ?>
										</div>
									<?php else : ?>
										<div id="content-<?php echo $featured_tool_category->term_id; ?>"></div>
									<?php endif; ?>
								<?php endif; ?>
							</div>
						<?php $i++; endforeach; ?>
					</div>
					<?php if ($tools_page) : ?>
						<div class="carousel-tabs__view-mob">
							<a href="<?php echo get_permalink($tools_page); ?>" class="btn btn-outline-blue">
								<?php _e('View All', 'wb'); ?>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($featured_tool_categories_3) : ?>
		<div class="products-carousel-tabs-2">
			<div class="container">
				<div class="carousel-tabs tabsi">
					<ul class="carousel-tabs-nav tabs-nav">
						<?php $i = 0; foreach ($featured_tool_categories_3 as $featured_tool_category) : ?>
							<li <?php echo ($i == 0) ? 'class="current_tab"' : ''; ?>>
								<a href="<?php echo get_term_link($featured_tool_category); ?>" data-type="tool" data-category="<?php echo $featured_tool_category->term_id; ?>">
									<?php echo $featured_tool_category->name; ?>
								</a>
							</li>
						<?php $i++; endforeach; ?>
					</ul>
					<div class="carousel-tabs-content tabs-content">
						<?php $i = 0; foreach ($featured_tool_categories_3 as $featured_tool_category) : ?>
							<div class="tabs-content-tab <?php echo ($i == 0) ? 'active_tab' : ''; ?>">
								<?php if (isset($featured_tools_3[$featured_tool_category->term_id]) && $featured_tools_3[$featured_tool_category->term_id]) : ?>
									<?php if ($i == 0) : ?>
										<div class="carousel-products owl-carousel" data-autoplay="false" data-nav-text="[&quot;&lt;i class='icon icon-arrow-right'&gt;&lt;/i&gt; &quot;,&quot;&lt;i class='ficon icon-arrow-right'&gt;&lt;/i&gt;&quot;]" data-nav="true" data-dots="false" data-loop="true" data-slidespeed="200" data-margin="54" data-responsive="{&quot;0&quot;:{ &quot;margin&quot; : 20, &quot;items&quot;: &quot;1&quot;}, &quot;600&quot;:{&quot;margin&quot; : 20, &quot;items&quot;: &quot;2&quot;}, &quot;850&quot;:{&quot;margin&quot; : 15 , &quot;items&quot;: &quot;3&quot;}, &quot;1200&quot;:{&quot;items&quot;: &quot;4&quot;}}">
											<?php

											$tools_args = array(
												'post_type' => 'tool',
												'posts_per_page' => 8,
												'post__in' => $featured_tools_3[$featured_tool_category->term_id]
											);

											if ($featured_tool_categories_sort_3 == '1') {
												$tools_args['orderby'] = 'name';
												$tools_args['order'] = 'ASC';
											} else if ($featured_tool_categories_sort_3 == '2') {
												$tools_args['meta_query'][] = array(
													'key' => '_views',
													'type' => 'NUMERIC'
												);

												$tools_args['orderby'] = 'meta_value_num';
												$tools_args['order'] = 'DESC';
											}

											$tools = get_posts($tools_args);

											?>
											<?php foreach ($tools as $tool) : ?>
												<div class="item">
													<div class="product-box">
														<?php if (has_post_thumbnail($tool)) : ?>
															<div class="product-box__image">
																<a href="<?php echo get_permalink($tool); ?>">
																	<?php echo get_the_post_thumbnail($tool, '480x360'); ?>
																</a>
															</div>
														<?php endif; ?>
														<div class="product-box__info">
															<h3 class="product-box__title">
																<a href="<?php echo get_permalink($tool); ?>">
																	<?php echo get_the_title($tool); ?>
																</a>
															</h3>
															<div class="product-box__text">
																<p><?php echo $tool->post_excerpt ? mb_strimwidth($tool->post_content, 0, 180, '...') : mb_strimwidth($tool->post_content, 0, 180, '...'); ?></p>
															</div>
														</div>
														<?php $amount = get_post_meta($tool->ID, '_amount', true); ?>
														<?php if ($amount != '') : ?>
															<div class="product-box__price">
																<?php if ($amount === '0') : ?>
																	<?php _e('FREE', 'wb'); ?> <div class="price"></div>
																<?php elseif ($amount === 'Contact Vendor') : ?>
																	<?php _e('Contact Vendor', 'wb'); ?> <div class="price"></div>
																<?php else : ?>
																	<?php echo is_numeric($amount) ? __('Price from', 'wb') : ''; ?><div class="price"><?php echo get_post_meta($tool->ID, '_currency', true); ?><?php echo $amount; ?></div>
																<?php endif; ?>
															</div>
														<?php endif; ?>
													</div>
												</div>
											<?php endforeach; ?>
										</div>
									<?php else : ?>
										<div id="content-<?php echo $featured_tool_category->term_id; ?>"></div>
									<?php endif; ?>
								<?php endif; ?>
							</div>
						<?php $i++; endforeach; ?>
					</div>
					<?php if ($tools_page) : ?>
						<div class="carousel-tabs__view-mob">
							<a href="<?php echo get_permalink($tools_page); ?>" class="btn btn-outline-blue">
								<?php _e('View All', 'wb'); ?>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<!-- -->
	<?php if ($featured_course_categories) : ?>
		<div class="products-carousel-tabs">
			<div class="container">
				<div class="products-carousel-tabs__heading">
					<div class="products-carousel-tabs__heading__left">
						<span class="products-carousel-tabs__count badge-num badge-num">
							<?php echo array_sum(array_column($featured_course_categories, 'count')); ?>
						</span>
						<h2 class="text-lg products-carousel-tabs__title">
							<?php _e('Digital Marketing Courses', 'wb'); ?>
						</h2>
					</div>
					<?php if ($courses_page = wb_get_page_by_template('courses')) : ?>
						<a href="<?php echo get_permalink($courses_page); ?>" class="btn btn-outline-blue carousel-tabs__view">
							<?php _e('View All', 'wb'); ?>
						</a>
					<?php endif; ?>
				</div>
				<div class="carousel-tabs tabsi">
					<ul class="carousel-tabs-nav tabs-nav">
						<?php $i = 0; foreach ($featured_course_categories as $featured_course_category) : ?>
							<li <?php echo ($i == 0) ? 'class="current_tab"' : ''; ?>>
								<a href="<?php echo get_term_link($featured_course_category); ?>" data-type="course" data-category="<?php echo $featured_course_category->term_id; ?>">
									<?php echo $featured_course_category->name; ?>
								</a>
							</li>
						<?php $i++; endforeach; ?>
					</ul>
					<div class="carousel-tabs-content tabs-content">
						<?php $i = 0; foreach ($featured_course_categories as $featured_course_category) : ?>
							<div class="tabs-content-tab <?php echo ($i == 0) ? 'active_tab' : ''; ?>">
								<?php if ($i == 0) : ?>
									<div class="carousel-products owl-carousel" data-autoplay="false" data-nav-text="[&quot;&lt;i class='icon icon-arrow-right'&gt;&lt;/i&gt; &quot;,&quot;&lt;i class='ficon icon-arrow-right'&gt;&lt;/i&gt;&quot;]" data-nav="true" data-dots="false" data-loop="true" data-slidespeed="200" data-margin="54" data-responsive="{&quot;0&quot;:{ &quot;margin&quot; : 20, &quot;items&quot;: &quot;1&quot;}, &quot;600&quot;:{&quot;margin&quot; : 20, &quot;items&quot;: &quot;2&quot;}, &quot;850&quot;:{&quot;margin&quot; : 15 , &quot;items&quot;: &quot;3&quot;}, &quot;1200&quot;:{&quot;items&quot;: &quot;4&quot;}}">
										<?php

										if (!isset($featured_courses[$featured_course_category->term_id]) || !$featured_courses[$featured_course_category->term_id]) {
											continue;
										}

										$courses_args = array(
											'post_type' => 'course',
											'posts_per_page' => 8,
											'post__in' => $featured_courses[$featured_course_category->term_id]
										);

										if ($featured_course_categories_sort == '1') {
											$courses_args['orderby'] = 'name';
											$courses_args['order'] = 'ASC';
										} else if ($featured_course_categories_sort == '2') {
											$courses_args['meta_query'][] = array(
												'key' => '_views',
												'type' => 'NUMERIC'
											);

											$courses_args['orderby'] = 'meta_value_num';
											$courses_args['order'] = 'DESC';
										}

										$courses = get_posts($courses_args);

										?>
										<?php foreach ($courses as $course) : ?>
											<div class="item">
												<div class="product-box">
													<?php if (has_post_thumbnail($course)) : ?>
														<div class="product-box__image">
															<a href="<?php echo get_permalink($course); ?>">
																<?php echo get_the_post_thumbnail($course, '480x360'); ?>
															</a>
														</div>
													<?php endif; ?>
													<div class="product-box__info">
														<h3 class="product-box__title">
															<a href="<?php echo get_permalink($course); ?>">
																<?php echo get_the_title($course); ?>
															</a>
														</h3>
														<div class="product-box__text">
															<p><?php echo $course->post_excerpt ? mb_strimwidth($course->post_content, 0, 180, '...') : mb_strimwidth($course->post_content, 0, 180, '...'); ?></p>
														</div>
													</div>
													<?php $amount = get_post_meta($course->ID, '_amount', true); ?>
													<?php if ($amount != '') : ?>
														<div class="product-box__price">
															<?php if ($amount === '0') : ?>
																<?php _e('FREE', 'wb'); ?> <div class="price"></div>
															<?php elseif ($amount === 'Contact Vendor') : ?>
																<?php _e('Contact Vendor', 'wb'); ?> <div class="price"></div>
															<?php else : ?>
																<?php echo is_numeric($amount) ? __('Price from', 'wb') : ''; ?><div class="price"><?php echo get_post_meta($course->ID, '_currency', true); ?><?php echo $amount; ?></div>
															<?php endif; ?>
														</div>
													<?php endif; ?>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								<?php else : ?>
									<div id="content-<?php echo $featured_course_category->term_id; ?>"></div>
								<?php endif; ?>
							</div>
						<?php $i++; endforeach; ?>
					</div>
					<?php if ($courses_page) : ?>
						<div class="carousel-tabs__view-mob">
							<a href="<?php echo get_permalink($courses_page); ?>" class="btn btn-outline-blue">
								<?php _e('View All', 'wb'); ?>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($featured_service_categories) : ?>
		<div class="products-carousel-tabs">
			<div class="container">
				<div class="products-carousel-tabs__heading">
					<div class="products-carousel-tabs__heading__left">
						<span class="products-carousel-tabs__count badge-num badge-num">
							<?php echo array_sum(array_column($featured_service_categories, 'count')); ?>
						</span>
						<h2 class="text-lg products-carousel-tabs__title">
							<?php _e('Digital Marketing Services', 'wb'); ?>
						</h2>
					</div>
					<?php if ($services_page = wb_get_page_by_template('services')) : ?>
						<a href="<?php echo get_permalink($services_page); ?>" class="btn btn-outline-blue carousel-tabs__view">
							<?php _e('View All', 'wb'); ?>
						</a>
					<?php endif; ?>
				</div>
				<div class="carousel-tabs tabsi">
					<ul class="carousel-tabs-nav tabs-nav">
						<?php $i = 0; foreach ($featured_service_categories as $featured_service_category) : ?>
							<li <?php echo ($i == 0) ? 'class="current_tab"' : ''; ?>>
								<a href="<?php echo get_term_link($featured_service_category); ?>" data-type="service" data-category="<?php echo $featured_course_category->term_id; ?>">
									<?php echo $featured_service_category->name; ?>
								</a>
							</li>
						<?php $i++; endforeach; ?>
					</ul>
					<div class="carousel-tabs-content tabs-content">
						<?php $i = 0; foreach ($featured_service_categories as $featured_service_category) : ?>
							<div class="tabs-content-tab <?php echo ($i == 0) ? 'active_tab' : ''; ?>">
								<div class="carousel-products owl-carousel" data-autoplay="false" data-nav-text="[&quot;&lt;i class='icon icon-arrow-right'&gt;&lt;/i&gt; &quot;,&quot;&lt;i class='ficon icon-arrow-right'&gt;&lt;/i&gt;&quot;]" data-nav="true" data-dots="false" data-loop="true" data-slidespeed="200" data-margin="54" data-responsive="{&quot;0&quot;:{ &quot;margin&quot; : 20, &quot;items&quot;: &quot;1&quot;}, &quot;600&quot;:{&quot;margin&quot; : 20, &quot;items&quot;: &quot;2&quot;}, &quot;850&quot;:{&quot;margin&quot; : 15 , &quot;items&quot;: &quot;3&quot;}, &quot;1200&quot;:{&quot;items&quot;: &quot;4&quot;}}">
									<?php

									if (!isset($featured_services[$featured_service_category->term_id]) || !$featured_services[$featured_service_category->term_id]) {
										continue;
									}

									$services_args = array(
										'post_type' => 'service',
										'posts_per_page' => 8,
										'post__in' => $featured_services[$featured_service_category->term_id]
									);

									if ($featured_service_categories_sort == '1') {
										$services_args['orderby'] = 'name';
										$services_args['order'] = 'ASC';
									} else if ($featured_service_categories_sort == '2') {
										$services_args['meta_query'][] = array(
											'key' => '_views',
											'type' => 'NUMERIC'
										);

										$services_args['orderby'] = 'meta_value_num';
										$services_args['order'] = 'DESC';
									}

									$services = get_posts($services_args);

									?>
									<?php foreach ($services as $service) : ?>
										<div class="item">
											<div class="product-box">
												<?php if (has_post_thumbnail($service)) : ?>
													<div class="product-box__image">
														<a href="<?php echo get_permalink($service); ?>">
															<?php echo get_the_post_thumbnail($service, '480x360'); ?>
														</a>
													</div>
												<?php endif; ?>
												<div class="product-box__info">
													<h3 class="product-box__title">
														<a href="<?php echo get_permalink($service); ?>">
															<?php echo get_the_title($service); ?>
														</a>
													</h3>
													<div class="product-box__text">
														<p><?php echo $service->post_excerpt ? mb_strimwidth($service->post_content, 0, 180, '...') : mb_strimwidth($service->post_content, 0, 180, '...'); ?></p>
													</div>
												</div>
												<?php $amount = get_post_meta($service->ID, '_amount', true); ?>
												<?php if ($amount != '') : ?>
													<div class="product-box__price">
														<?php if ($amount === '0') : ?>
															<?php _e('FREE', 'wb'); ?> <div class="price"></div>
														<?php elseif ($amount === 'Contact Vendor') : ?>
															<?php _e('Contact Vendor', 'wb'); ?> <div class="price"></div>
														<?php else : ?>
															<?php echo is_numeric($amount) ? __('Price from', 'wb') : ''; ?><div class="price"><?php echo get_post_meta($service->ID, '_currency', true); ?><?php echo $amount; ?></div>
														<?php endif; ?>
													</div>
												<?php endif; ?>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						<?php $i++; endforeach; ?>
					</div>
					<?php if ($services_page) : ?>
						<div class="carousel-tabs__view-mob">
							<a href="<?php echo get_permalink($services_page); ?>" class="btn btn-outline-blue">
								<?php _e('View All', 'wb'); ?>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>

<?php if ($promotion_text_description = get_post_meta($post->ID, '_promotion_text_description', true)) : ?>
	<div class="works-about-wrap">
		<div class="container">
			<div class="works-about bdrs-5">
				<div class="row">
					<div class="col-lg-6">
						<div class="works-about__info">
							<?php if ($promotion_text_title = get_post_meta($post->ID, '_promotion_text_title', true)) : ?>
								<h2 class="text-lg works-about__title"><?php echo $promotion_text_title; ?></h2>
							<?php endif; ?>
							<?php echo wpautop($promotion_text_description); ?>
							<?php if ($promotion_text_button_text = get_post_meta($post->ID, '_promotion_text_button_text', true)) : ?>
								<div class="works-about__btn">
									<a href="<?php echo get_post_meta($post->ID, '_promotion_text_button_url', true); ?>" class="btn btn-square btn-blue btn_width_155">
										<?php echo $promotion_text_button_text; ?>
									</a>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="works-about__image">
							<img src="<?php echo WB_THEME_URL; ?>/images/how-it-works.png" srcset="<?php echo WB_THEME_URL; ?>/images/how-it-works@2x.png 2x" alt="">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php get_footer(); ?>
