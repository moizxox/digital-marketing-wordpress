<?php

/**
 * Template Name: Search
 */

if (!defined('ABSPATH')) {
	exit;
}

wp_enqueue_script('jquery.mCustomScrollbar', WB_THEME_URL . '/js/jquery.mCustomScrollbar.js', array('main'));
wp_enqueue_script('jquery.fancybox', WB_THEME_URL . '/js/jquery.fancybox.js', array('jquery.mCustomScrollbar'));

get_header();

the_post();

global $wpdb;

$type = (isset($_GET['type']) && in_array($_GET['type'], array('tool', 'course', 'service', 'ai-tool', 'ai-agent'))) ? $_GET['type'] : 'tool';
$query = isset($_GET['query']) ? esc_attr($_GET['query']) : '';
$per_page = (isset($_GET['per_page']) && in_array($_GET['per_page'], array('12', '24', '48', '96'))) ? $_GET['per_page'] : '12';
$sort = (isset($_GET['sort']) && in_array($_GET['sort'], array('alphabetically', 'popularity', 'price-hl', 'price-lh'))) ? $_GET['sort'] : 'alphabetically';
$pricing_option = isset($_GET['pricing_option']) ? (array) $_GET['pricing_option'] : array();
$price = isset($_GET['price']) ? (array) $_GET['price'] : array();
$currency = isset($_GET['currency']) ? $_GET['currency'] : '';
$country = isset($_GET['country']) ? (array) $_GET['country'] : array();
$city = isset($_GET['city']) ? (array) $_GET['city'] : array();
$tag = isset($_GET['ftag']) ? (array) $_GET['ftag'] : array();

if ($type == 'service') {
	$pricing_options = array();
	$prices = array();
} else {
	$pricing_options = get_terms(array(
		'taxonomy' => $type . '-pricing-option',
		'orderby' => 'ID',
		'order' => 'ASC'
	));

	$prices = $wpdb->get_col("
		SELECT DISTINCT wm.meta_value
		FROM {$wpdb->posts} wp
		INNER JOIN {$wpdb->postmeta} wm ON (wm.post_id = wp.ID AND wm.meta_key = '_amount')
		WHERE wp.post_type = '{$type}'
		AND wp.post_status = 'publish'
	");
}

if ($prices) {
	$prices = Filter::get_prices($prices);
	$currencies = array();
	$currencies = array_filter($currencies);

	if (!$currency) {
		$currency = array_values($currencies);
		$currency = $currency ? $currency[0] : '';
	}
}

// Only get location terms for relevant post types
if (in_array($type, array('tool', 'course', 'service'))) {
	$countries = get_terms(array(
		'taxonomy' => $type . '-location',
		'parent' => 0,
		'orderby' => 'ID',
		'order' => 'ASC'
	));

	$cities = get_terms(array(
		'taxonomy' => $type . '-location',
		'exclude_parents' => 1,
		'orderby' => 'ID',
		'order' => 'ASC'
	));
} else {
	$countries = array();
	$cities = array();
}

$tags = get_terms(array(
	'taxonomy' => $type . '-tag',
	'orderby' => 'ID',
	'order' => 'ASC'
));

?>

<div class="hero hero_search-radio hero_center heading-bg">
	<div class="container">
		<div class="hero__inner clearfix max-w-823">
			<h1 class="hero__title"><?php the_title(); ?></h1>
			<?php if ($search_page = wb_get_page_by_template('search')) : ?>
				<form action="<?php echo get_permalink($search_page); ?>" method="get" class="hero-search-wrap">
					<ul class="search-filter search_page hero-search__filter">
						<li>
							<label class="radio">
								<input type="radio" name="type" value="tool" <?php checked($type, 'tool'); ?>>
								<?php _e('Tools', 'wb'); ?>
							</label>
						</li>
						<li>
							<label class="radio">
								<input type="radio" name="type" value="course" <?php checked($type, 'course'); ?>>
								<?php _e('Courses', 'wb'); ?>
							</label>
						</li>
						<li>
							<label class="radio">
								<input type="radio" name="type" value="service" <?php checked($type, 'service'); ?>>
								<?php _e('Services', 'wb'); ?>
							</label>
						</li>
						<li>
							<label class="radio">
								<input type="radio" name="type" value="ai-tool" <?php checked($type, 'ai-tool'); ?>>
								<?php _e('AI Tools', 'wb'); ?>
							</label>
						</li>
						<li>
							<label class="radio">
								<input type="radio" name="type" value="ai-agent" <?php checked($type, 'ai-agent'); ?>>
								<?php _e('AI Agents', 'wb'); ?>
							</label>
						</li>
					</ul>
					<div class="hero-search hero-search_single">
						<div class="hero-search__input">
							<input type="text" name="query" value="<?php echo $query; ?>" class="form-control" placeholder="<?php _e('e.g. SEO or Email Marketing', 'wb'); ?>">
						</div>
						<div class="hero-search__append">
							<button type="submit" class="btn btn-green hero-search__btn">
								<i class="icon icon-search"></i> <?php _e('SEARCH', 'wb'); ?>
							</button>
						</div>
					</div>
				</form>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php

$args = array(
	'post_type' => $type,
	'posts_per_page' => $per_page,
	'search_page' => true,
	'paged' => get_query_var('paged') ? get_query_var('paged') : 1
);

if (!empty($query)) {
	$args['s'] = $query;
}

// Add tax query for categories and tags
$tax_query = array();

if (isset($_GET['category']) && !empty($_GET['category'])) {
	$tax_query[] = array(
		'taxonomy' => $type . '-category',
		'field' => 'slug',
		'terms' => $_GET['category']
	);
}

if (isset($_GET['tag']) && !empty($_GET['tag'])) {
	$tax_query[] = array(
		'taxonomy' => $type . '-tag',
		'field' => 'slug',
		'terms' => $_GET['tag']
	);
}

if (!empty($tax_query)) {
	$args['tax_query'] = $tax_query;
}

// Add meta query for prices
$meta_query = array();

if (isset($_GET['price_min']) && !empty($_GET['price_min'])) {
	$meta_query[] = array(
		'key' => '_amount',
		'value' => floatval($_GET['price_min']),
		'type' => 'NUMERIC',
		'compare' => '>='
	);
}

if (isset($_GET['price_max']) && !empty($_GET['price_max'])) {
	$meta_query[] = array(
		'key' => '_amount',
		'value' => floatval($_GET['price_max']),
		'type' => 'NUMERIC',
		'compare' => '<='
	);
}

if (!empty($meta_query)) {
	$args['meta_query'] = $meta_query;
}

query_posts($args);

?>

<main class="main page-catalog">
	<div class="container">
		<div class="products-row-small-filter">
			<div class="product-filter side-panel products-row-small-filter__sidebar">
				<div class="side-panel__heading">
					<div class="side-panel__heading__inner">
						<h3><?php _e('Filter Results', 'wb'); ?></h3>
						<span class="badge-num found-posts"><?php echo $wp_query->found_posts; ?></span>
						<button class="side-panel-close product-filter__heading__close">
							<i class="icon icon-close"></i>
						</button>
					</div>
				</div>
				<form action="<?php the_permalink(); ?>" method="get" autocomplete="off">
					<input type="hidden" name="type" value="<?php echo $type; ?>">
					<input type="hidden" name="per_page" value="<?php echo $per_page; ?>">
					<input type="hidden" name="sort" value="<?php echo $sort; ?>">
					<div class="side-panel__body">
						<?php if ($pricing_options) : ?>
							<div class="product-filter__box">
								<div class="product-filter__box__heading">
									<h3 class="product-filter__box__title">
										<?php _e('Pricing Options', 'wb'); ?>
									</h3>
								</div>
								<ul class="product-filter__list">
									<?php foreach ($pricing_options as $_pricing_option) : ?>
										<li>
											<label class="product-filter-control">
												<input type="checkbox" name="pricing_option[]" value="<?php echo $_pricing_option->term_id; ?>" <?php echo in_array($_pricing_option->term_id, $pricing_option) ? 'checked' : ''; ?>>
												<span class="product-filter-control__label">
													<?php echo $_pricing_option->name; ?>
												</span>
											</label>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
						<?php if ($prices) : ?>
							<div class="product-filter__box">
								<div class="product-filter__box__heading">
									<h3 class="product-filter__box__title">
										<?php _e('Price', 'wb'); ?>
									</h3>
									<?php if ($currencies) : ?>
										<div class="product-filter__box__all">
											<div class="btn-group btn-group-sm currency-group">
												<?php foreach ($currencies as $_currency) : ?>
													<button type="button" class="btn btn-secondary <?php echo ($_currency == $currency) ? 'active' : ''; ?>" data-currency="<?php echo $_currency; ?>">
														<?php echo $_currency; ?>
													</button>
												<?php endforeach; ?>
											</div>
											<input type="hidden" name="currency" value="<?php echo $currency; ?>">
										</div>
									<?php endif; ?>
								</div>
								<ul class="product-filter__list">
									<li>
										<label class="product-filter-control">
											<input type="checkbox" name="price[]" value="0" <?php echo in_array('0', $price) ? 'checked' : ''; ?>>
											<span class="product-filter-control__label">
												<?php _e('Free', 'wb'); ?></span>
										</label>
									</li>
									<?php foreach ($prices['list'] as $_price) : ?>
										<li>
											<label class="product-filter-control">
												<input type="checkbox" name="price[]" value="<?php echo $_price['ranges']['min']; ?>-<?php echo $_price['ranges']['max']; ?>" <?php echo in_array($_price['ranges']['min'] . '-' . $_price['ranges']['max'], $price) ? 'checked' : ''; ?>>
												<span class="product-filter-control__label">
													<span class="curr-symbol"><?php echo $currency; ?></span><?php echo $_price['ranges']['min']; ?> -
													<span class="curr-symbol"><?php echo $currency; ?></span><?php echo $_price['ranges']['max']; ?>
												</span>
											</label>
										</li>
									<?php endforeach; ?>
									<?php $last_price_range = array_key_last($prices['list']); ?>
									<?php if ($prices['max'] > $prices['list'][$last_price_range]['ranges']['max']) : ?>
										<li>
											<label class="product-filter-control">
												<input type="checkbox" name="price[]" value="<?php echo $prices['list'][$last_price_range]['ranges']['max']; ?>" <?php echo in_array($prices['list'][$last_price_range]['ranges']['max'], $price) ? 'checked' : ''; ?>>
												<span class="product-filter-control__label">
													<?php _e('Over', 'wb'); ?> <span class="curr-symbol"><?php echo $currency; ?></span><?php echo $prices['list'][$last_price_range]['ranges']['max']; ?>
												</span>
											</label>
										</li>
									<?php endif; ?>
								</ul>
							</div>
						<?php endif; ?>
						<?php if ($countries) : ?>
							<div class="product-filter__box">
								<div class="product-filter__box__heading">
									<h3 class="product-filter__box__title">
										<?php _e('Country', 'wb'); ?>
									</h3>
									<?php if (count($countries) > 5) : ?>
										<a href="#id01" class="product-filter__box__all" data-fancybox>
											<?php _e('See All', 'wb'); ?>
										</a>
									<?php endif; ?>
								</div>
								<ul class="product-filter__list">
									<?php foreach (array_slice($countries, 0, 5) as $_country) : ?>
										<li>
											<label class="product-filter-control">
												<input type="checkbox" name="country[]" value="<?php echo $_country->term_id; ?>" <?php echo in_array($_country->term_id, $country) ? 'checked' : ''; ?>>
												<span class="product-filter-control__label">
													<?php echo $_country->name; ?>
												</span>
											</label>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
						<?php if ($cities) : ?>
							<div class="product-filter__box">
								<div class="product-filter__box__heading">
									<h3 class="product-filter__box__title">
										<?php _e('City', 'wb'); ?>
									</h3>
									<?php if (count($cities) > 5) : ?>
										<a href="#id01" class="product-filter__box__all" data-fancybox>
											<?php _e('See All', 'wb'); ?>
										</a>
									<?php endif; ?>
								</div>
								<ul class="product-filter__list">
									<?php foreach (array_slice($cities, 0, 5) as $_city) : ?>
										<li>
											<label class="product-filter-control">
												<input type="checkbox" name="city[]" value="<?php echo $_city->term_id; ?>" <?php echo in_array($_city->term_id, $city) ? 'checked' : ''; ?>>
												<span class="product-filter-control__label">
													<?php echo $_city->name; ?>
												</span>
											</label>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
						<?php if ($tags) : ?>
							<div class="product-filter__box">
								<div class="product-filter__box__heading">
									<h3 class="product-filter__box__title">
										<?php

										switch ($type) {
											case 'tool':
												_e('Features', 'wb');
												break;
											case 'course':
												_e('What you\'ll learn', 'wb');
												break;
											case 'ai-tool':
												_e('AI Tool Features', 'wb');
												break;
											case 'ai-agent':
												_e('AI Agent Features', 'wb');
												break;
											default:
												_e('Services', 'wb');
												break;
										}

										?>
									</h3>
									<?php if (count($tags) > 5) : ?>
										<a href="#id01" class="product-filter__box__all" data-fancybox>
											<?php _e('See All', 'wb'); ?>
										</a>
									<?php endif; ?>
								</div>
								<ul class="product-filter__list">
									<?php foreach (array_slice($tags, 0, 5) as $_tag) : ?>
										<li>
											<label class="product-filter-control">
												<input type="checkbox" name="ftag[]" value="<?php echo $_tag->term_id; ?>" <?php echo in_array($_tag->term_id, $tag) ? 'checked' : ''; ?>>
												<span class="product-filter-control__label">
													<?php echo $_tag->name; ?>
												</span>
											</label>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
					</div>
					<div class="product-filter__bottom">
						<button class="btn btn-green btn-block product-filter__apply">
							<?php _e('Apply Filters', 'wb'); ?>
						</button>
						<button class="btn btn-link btn-block product-filter__clear" style="display: <?php echo (!empty($pricing_option) || !empty($price) || !empty($country) || !empty($city) || !empty($tag)) ? 'block' : 'none'; ?>">
							<i class="icon icon-clear"></i>
							<?php _e('Clear All Filters', 'wb'); ?>
						</button>
					</div>
				</form>
			</div>
			<div class="products-row-small-filter__content">
				<div class="control-bar control-bar_products">
					<div class="control-bar__left">
						<ul class="control-bar-view">
							<li class="control-bar-item">
								<label <?php echo !isset($_COOKIE['_lv']) ? 'class="active"' : ''; ?> id="grid">
									<input type="radio" name="view" <?php echo !isset($_COOKIE['_lv']) ? 'checked' : ''; ?>>
									<span class="control-bar__label"><?php _e('Grid View', 'wb'); ?></span>
								</label>
							</li>
							<li class="control-bar-item">
								<label <?php echo isset($_COOKIE['_lv']) ? 'class="active"' : ''; ?> id="list">
									<input type="radio" name="view" <?php echo isset($_COOKIE['_lv']) ? 'checked' : ''; ?>>
									<span class="control-bar__label"><?php _e('List View', 'wb'); ?></span>
								</label>
							</li>
						</ul>
					</div>
					<div class="control-bar__right">
						<form method="get">
							<input type="hidden" name="type" value="<?php echo $type; ?>">
							<input type="hidden" name="query" value="<?php echo $query; ?>">
							<ul class="control-bar-list">
								<li class="control-bar-item">
									<div class="control-bar__label"><?php _e('Show', 'wb'); ?></div>
									<select name="per_page" class="control-bar__select select-styler"> 
										<option value="12" <?php selected('12', $per_page); ?>>
											<?php _e('12 per page', 'wb'); ?>
										</option>
										<option value="24" <?php selected('24', $per_page); ?>>
											<?php _e('24 per page', 'wb'); ?>
										</option>
										<option value="48" <?php selected('48', $per_page); ?>>
											<?php _e('48 per page', 'wb'); ?>
										</option>
										<option value="96" <?php selected('96', $per_page); ?>>
											<?php _e('96 per page', 'wb'); ?>
										</option>
									</select>
								</li>
								<li class="control-bar-item">
									<div class="control-bar__label"><?php _e('Sort by', 'wb'); ?></div>
									<select name="sort" class="control-bar__select select-styler">
										<option value="alphabetically" <?php selected('alphabetically', $sort); ?>>
											<?php _e('Alphabetically', 'wb'); ?>
										</option>
										<option value="popularity" <?php selected('popularity', $sort); ?>>
											<?php _e('Popularity', 'wb'); ?>
										</option>
										<option value="price-hl" <?php selected('price-hl', $sort); ?>>
											<?php _e('Price (High to Low)', 'wb'); ?>
										</option>
										<option value="price-lh" <?php selected('price-lh', $sort); ?>>
											<?php _e('Price (Low to High)', 'wb'); ?>
										</option>
									</select>
								</li>
							</ul>
						</form>
					</div>
				</div>
				<div class="side-toggle">
					<h3><?php _e('Filter', 'wb'); ?></h3>
					<span class="badge-num found-posts"><?php echo $wp_query->found_posts; ?></span>
					<button class="side-toggle__btn filter-toggle-btn">
						<span></span>
						<span></span>
					</button>
				</div>
				<?php if (have_posts()) : ?>
					<div class="products row display-flex row-gap-10 <?php echo isset($_COOKIE['_lv']) ? 'products-list' : 'products-grid'; ?>">
						<?php while (have_posts()) : the_post(); ?>
							<div class="procuct__col col-md-4 col-sm-6 col-gap-10">
								<div class="product-box product-box_wide">
									<?php if (has_post_thumbnail()) : ?>
										<div class="product-box__image">
											<a href="<?php the_permalink(); ?>">
												<?php echo the_post_thumbnail('480x360'); ?>
											</a>
											<?php if ($logo = get_post_meta($post->ID, '_logo', true)) : ?>
												<div class="product-box__logo">
													<a href="<?php the_permalink(); ?>">
														<img src="<?php echo wb_image($logo, 130, 40); ?>" alt="<?php the_title(); ?>">
													</a>
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
									<div class="product-box__info">
										<h3 class="product-box__title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h3>
										<div class="product-box__text">
											<p><?php echo $post->post_excerpt ? mb_strimwidth(strip_tags($post->post_content), 0, 180, '...') : mb_strimwidth(strip_tags($post->post_content), 0, 180, '...'); ?></p>
										</div>
									</div>
									<div class="product-box__info-list">
										<div class="product-box__info-list__top-wrap">
											<?php if (has_post_thumbnail()) : ?>
												<div class="product-box__info-list__image">
													<a href="<?php the_permalink(); ?>">
														<?php echo the_post_thumbnail('480x360'); ?>
													</a>
												</div>
											<?php endif; ?>
											<div class="product-box__info-list__top">
												<h3 class="product-box__title">
													<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
												</h3>
												<?php $amount = get_post_meta($post->ID, '_amount', true); ?>
												<?php if ($amount != '') : ?>
													<div class="product-box__price">
														<?php if ($amount === '0') : ?>
															<?php _e('FREE', 'wb'); ?> <div class="price"></div>
														<?php elseif ($amount === 'Contact Vendor') : ?>
															<?php _e('Contact Vendor', 'wb'); ?> <div class="price"></div>
														<?php else : ?>
															<?php echo is_numeric($amount) ? __('Price from', 'wb') : ''; ?><div class="price"><?php echo get_post_meta($post->ID, '_currency', true); ?><?php echo $amount; ?></div>
														<?php endif; ?>
													</div>
												<?php endif; ?>
											</div>
										</div>
										<div class="product-box__info-list__middle">
											<div class="product-box__description">
												<p><?php echo $post->post_excerpt ? mb_strimwidth(strip_tags($post->post_content), 0, 300, '...') : mb_strimwidth(strip_tags($post->post_content), 0, 300, '...'); ?></p>
											</div>
										</div>
										<div class="product-box__info-list__bottom">
											<div class="share">
												<div class="share-label">
													<i class="icon icon-share"></i>
													<span><?php _e('Share', 'wb'); ?></span>
												</div>
												<div class="share-soc">
													<ul class="share-soc-list">
														<li>
															<a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>+<?php the_permalink(); ?>" target="_blank">
																<i class="icon fa fa-twitter"></i>
															</a>
														</li>
														<li>
															<a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank">
																<i class="icon fa fa-facebook"></i>
															</a>
														</li>
														<li>
															<a href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php printf(__('Check out this %s %s.', 'wb'), get_post_type(), get_permalink()); ?>">
																<i class="icon fa fa-envelope"></i>
															</a>
														</li>
													</ul>
												</div>
											</div>
											<ul class="product-box__info-list__btns">
												<?php if ($compare_page = wb_get_page_by_template('compare')) : ?>
													<li>
														<a href="<?php echo get_permalink($compare_page); ?>" class="btn btn-outline-blue-light btn-square roduct-box-detail__add-compare <?php echo Compare::in_list($post) ? 'added' : ''; ?>" data-action="<?php echo get_permalink($post) . 'add/'; ?>">
															<?php _e(Compare::in_list($post) ? 'Compare' : 'Add to Compare', 'wb'); ?>
														</a>
													</li>
												<?php endif; ?>
												<?php if ($website_url = get_post_meta($post->ID, '_website_url', true)) : ?>
													<li>
														<a href="<?php echo $website_url; ?>" target="_blank" class="btn btn-green btn-square gtm-visit">
															<?php _e('Visit Website', 'wb'); ?>
														</a>
													</li>
												<?php endif; ?>
											</ul>
										</div>
									</div>
									<?php $amount = get_post_meta($post->ID, '_amount', true); ?>
									<?php if ($amount != '') : ?>
										<div class="product-box__price">
											<?php if ($amount === '0') : ?>
												<?php _e('FREE', 'wb'); ?> <div class="price"></div>
											<?php elseif ($amount === 'Contact Vendor') : ?>
												<?php _e('Contact Vendor', 'wb'); ?> <div class="price"></div>
											<?php else : ?>
												<?php echo is_numeric($amount) ? __('Price from', 'wb') : ''; ?><div class="price"><?php echo get_post_meta($post->ID, '_currency', true); ?><?php echo $amount; ?></div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
					<div class="result-count">
						<?php

						$paged = !empty($GLOBALS['wp_query']->query_vars['paged']) ? $GLOBALS['wp_query']->query_vars['paged'] : 1;
						$prev_posts = ($paged - 1) * $GLOBALS['wp_query']->query_vars['posts_per_page'];
						$from = 1 + $prev_posts;
						$to = count($GLOBALS['wp_query']->posts) + $prev_posts;

						printf(__('Displaying results %d to %d of %d matches', 'wb'), $from, $to, $GLOBALS['wp_query']->found_posts);

						?>
					</div>
					<div class="clearfix"></div>
					<?php wb_pagination('before=<ul class="page-navi">&current=<li class="active"><a href="#">%s</a></li>'); ?>
				<?php else : ?>
					<div class="products row row-gap-10">
						<div class="col-md-12">
							<p class="text-center"><?php _e('Apologies, but no entries were found.', 'wb'); ?></p>
						</div>
					</div>
				<?php endif; ?>
				<?php wp_reset_query(); ?>
			</div>
		</div>
	</div>
</main>

<div id="modal-compare">
	<div class="modal-content">
		<div class="modal-content__in">
			<div class="compare-info">
				<div class="compare-info__photo">
					<img src="<?php echo WB_THEME_URL; ?>/images/compare-add.png" srcset="<?php echo WB_THEME_URL; ?>/images/compare-add@2x.png 2x" alt="<?php _e('Compare', 'wb'); ?>">
				</div>
				<h3 class="compare-info__title">
					<?php _e(sprintf('You added "%s" to the comparison list.', '<span id="title"></span>'), 'wb'); ?> <br>
					<?php _e(sprintf('You have %s items in your comparison list.', '<span class="badge-num">1</span>'), 'wb'); ?>
				</h3>
				<div class="compare-info__check">
					<label>
						<input type="checkbox">
						<span class="compare-info__check__label">
							<?php _e('Don\'t show me this next time', 'wb'); ?>
						</span>
					</label>
				</div>
				<ul class="compare-info__links">
					<li>
						<button class="btn btn-link"><?php _e('Continue browsing', 'wb'); ?></button>
					</li>
					<?php if ($compare_page) : ?>
						<li>
							<button class="btn btn-green" onclick="javascript:location.href='<?php echo get_permalink($compare_page); ?>';">
								<?php _e('Compare now', 'wb'); ?>
							</button>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="popup-box" id="id01">
	<div class="popup-box-head">
		<h4><?php _e('Filter Results', 'wb'); ?></h4>
	</div>
	<form action="<?php the_permalink(); ?>" method="get" autocomplete="off">
		<input type="hidden" name="type" value="<?php echo $type; ?>">
		<input type="hidden" name="per_page" value="<?php echo $per_page; ?>">
		<input type="hidden" name="sort" value="<?php echo $sort; ?>">
		<div class="popup-box-content">
		<div class="popup-box-content-elem popup-box-content-elem__430">
			<div class="product-filter__body">
				<?php if ($pricing_options) : ?>
					<div class="product-filter__box">
						<h3 class="product-filter__box__title">
							<?php _e('Pricing Options', 'wb'); ?>
						</h3>
						<ul class="product-filter__list">
							<?php foreach ($pricing_options as $_pricing_option) : ?>
								<li>
									<label class="product-filter-control">
										<input type="checkbox" name="pricing_option[]" value="<?php echo $_pricing_option->term_id; ?>" <?php echo in_array($_pricing_option->term_id, $pricing_option) ? 'checked' : ''; ?>>
										<span class="product-filter-control__label">
											<?php echo $_pricing_option->name; ?>
										</span>
									</label>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				<?php if ($prices) : ?>
					<div class="product-filter__box">
						<h3 class="product-filter__box__title">
							<?php _e('Price', 'wb'); ?>
							<?php if ($currencies) : ?>
								<div class="btn-group btn-group-sm currency-group">
									<?php foreach ($currencies as $_currency) : ?>
										<button type="button" class="btn btn-secondary <?php echo ($_currency == $currency) ? 'active' : ''; ?>" data-currency="<?php echo $_currency; ?>">
											<?php echo $_currency; ?>
										</button>
									<?php endforeach; ?>
								</div>
								<input type="hidden" name="currency" value="<?php echo $currency; ?>">
							<?php endif; ?>
						</h3>
						<ul class="product-filter__list">
							<li>
								<label class="product-filter-control">
									<input type="checkbox" name="price[]" value="0" <?php echo in_array('0', $price) ? 'checked' : ''; ?>>
									<span class="product-filter-control__label">
										<?php _e('Free', 'wb'); ?></span>
								</label>
							</li>
							<?php foreach ($prices['list'] as $_price) : ?>
								<li>
									<label class="product-filter-control">
										<input type="checkbox" name="price[]" value="<?php echo $_price['ranges']['min']; ?>-<?php echo $_price['ranges']['max']; ?>" <?php echo in_array($_price['ranges']['min'] . '-' . $_price['ranges']['max'], $price) ? 'checked' : ''; ?>>
										<span class="product-filter-control__label">
											<span class="curr-symbol"><?php echo $currency; ?></span><?php echo $_price['ranges']['min']; ?> -
											<span class="curr-symbol"><?php echo $currency; ?></span><?php echo $_price['ranges']['max']; ?>
										</span>
									</label>
								</li>
							<?php endforeach; ?>
							<?php $last_price_range = array_key_last($prices['list']); ?>
							<?php if ($prices['max'] > $prices['list'][$last_price_range]['ranges']['max']) : ?>
								<li>
									<label class="product-filter-control">
										<input type="checkbox" name="price[]" value="<?php echo $prices['list'][$last_price_range]['ranges']['max']; ?>" <?php echo in_array($prices['list'][$last_price_range]['ranges']['max'], $price) ? 'checked' : ''; ?>>
										<span class="product-filter-control__label">
											<?php _e('Over', 'wb'); ?> <span class="curr-symbol"><?php echo $currency; ?></span><?php echo $prices['list'][$last_price_range]['ranges']['max']; ?>
										</span>
									</label>
								</li>
							<?php endif; ?>
						</ul>
					</div>
				<?php endif; ?>
				<?php if ($countries) : ?>
					<div class="product-filter__box">
						<h3 class="product-filter__box__title">
							<?php _e('Country', 'wb'); ?>
						</h3>
						<ul class="product-filter__list product-filter__list__columns">
							<?php foreach ($countries as $_country) : ?>
								<li>
									<label class="product-filter-control">
										<input type="checkbox" name="country[]" value="<?php echo $_country->term_id; ?>" <?php echo in_array($_country->term_id, $country) ? 'checked' : ''; ?>>
										<span class="product-filter-control__label">
											<?php echo $_country->name; ?>
										</span>
									</label>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				<?php if ($cities) : ?>
					<div class="product-filter__box">
						<h3 class="product-filter__box__title">
							<?php _e('City', 'wb'); ?>
						</h3>
						<ul class="product-filter__list product-filter__list__columns">
							<?php foreach ($cities as $_city) : ?>
								<li>
									<label class="product-filter-control">
										<input type="checkbox" name="city[]" value="<?php echo $_city->term_id; ?>" <?php echo in_array($_city->term_id, $city) ? 'checked' : ''; ?>>
										<span class="product-filter-control__label">
											<?php echo $_city->name; ?>
										</span>
									</label>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				<?php if ($tags) : ?>
					<div class="product-filter__box">
						<h3 class="product-filter__box__title">
							<?php

							switch ($type) {
								case 'tool':
									_e('Features', 'wb');
									break;
								case 'course':
									_e('What you\'ll learn', 'wb');
									break;
								case 'ai-tool':
									_e('AI Tool Features', 'wb');
									break;
								case 'ai-agent':
									_e('AI Agent Features', 'wb');
									break;
								default:
									_e('Services', 'wb');
									break;
							}

							?>
						</h3>
						<ul class="product-filter__list product-filter__list__columns">
							<?php foreach ($tags as $_tag) : ?>
								<li>
									<label class="product-filter-control">
										<input type="checkbox" name="ftag[]" value="<?php echo $_tag->term_id; ?>" <?php echo in_array($_tag->term_id, $tag) ? 'checked' : ''; ?>>
										<span class="product-filter-control__label">
											<?php echo $_tag->name; ?>
										</span>
									</label>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="popup-box-content-elem popup-box-content-elem__95" style="display: <?php echo ($pricing_option || $price || $country || $city || $tag) ? 'block' : 'none'; ?>">
			<div class="filter-s-results">
				<div class="filter-s-results-e">
					<div class="filter-s-results-title">
						<?php _e('Selected', 'wb'); ?>:
					</div>
				</div>
				<div class="filter-s-results-e">
					<ul class="filter-ch-area">
						<li class="filter-ch-elem" id="pricing_option" style="display: <?php echo $pricing_option ? 'block' : 'none'; ?>">
							<div class="checked-el">
								<span>
									<?php _e('Pricing Options', 'wb'); ?>
									(<span class="count"><?php echo count($pricing_option); ?></span>)
								</span>
								<i class="checked-el-close"></i>
							</div>
						</li>
						<li class="filter-ch-elem" id="price" style="display: <?php echo $price ? 'block' : 'none'; ?>">
							<div class="checked-el">
								<span>
									<?php _e('Prices', 'wb'); ?>
									(<span class="count"><?php echo count($price); ?></span>)
								</span>
								<i class="checked-el-close"></i>
							</div>
						</li>
						<li class="filter-ch-elem" id="country" style="display: <?php echo $country ? 'block' : 'none'; ?>">
							<div class="checked-el">
								<span>
									<?php _e('Countries', 'wb'); ?>
									(<span class="count"><?php echo count($country); ?></span>)
								</span>
								<i class="checked-el-close"></i>
							</div>
						</li>
						<li class="filter-ch-elem" id="city" style="display: <?php echo $city ? 'block' : 'none'; ?>">
							<div class="checked-el">
								<span>
									<?php _e('Cities', 'wb'); ?>
									(<span class="count"><?php echo count($city); ?></span>)
								</span>
								<i class="checked-el-close"></i>
							</div>
						</li>
						<li class="filter-ch-elem" id="tag" style="display: <?php echo $tag ? 'block' : 'none'; ?>">
							<div class="checked-el">
								<span>
									<?php

									switch ($type) {
										case 'tool':
											_e('Features', 'wb');
											break;
										case 'course':
											_e('What you\'ll learn', 'wb');
											break;
										case 'ai-tool':
											_e('AI Tool Features', 'wb');
											break;
										case 'ai-agent':
											_e('AI Agent Features', 'wb');
											break;
										default:
											_e('Services', 'wb');
											break;
									}

									?>
									(<span class="count"><?php echo count($tag); ?></span>)
								</span>
								<i class="checked-el-close"></i>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
		</div>
		<div class="popup-box-footer">
			<ul class="popup-box-footer__bnts">
				<li>
					<button class="btn btn-link popup-filter__cencel" data-fancybox-close>
						<?php _e('Cancel', 'wb'); ?>
					</button>
				</li>
				<li>
					<button class="btn btn-green popup-filter__apply">
						<?php _e('Apply Filters', 'wb'); ?>
					</button>
				</li>
			</ul>
		</div>
	</form>
</div>

<?php get_footer(); ?>
