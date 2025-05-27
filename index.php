<?php

if (!defined('ABSPATH')) {
	exit;
}

wp_enqueue_script('jquery.mCustomScrollbar', WB_THEME_URL . '/js/jquery.mCustomScrollbar.js', array('main'));
wp_enqueue_script('jquery.fancybox', WB_THEME_URL . '/js/jquery.fancybox.js', array('jquery.mCustomScrollbar'));

get_header();

$query = isset($_GET['s']) ? esc_attr($_GET['s']) : '';
$per_page = (isset($_GET['per_page']) && in_array($_GET['per_page'], array('12', '24', '48', '96'))) ? $_GET['per_page'] : '12';
$sort = (isset($_GET['sort']) && in_array($_GET['sort'], array('alphabetically', 'popularity', 'price-hl', 'price-lh'))) ? $_GET['sort'] : 'alphabetically';
$type = isset($_GET['type']) ? (array) $_GET['type'] : array();
$tag = isset($_GET['post_tag']) ? (array) $_GET['post_tag'] : array();

$types = get_terms(array(
	'taxonomy' => 'post-type',
	'orderby' => 'ID',
	'order' => 'ASC'
));

$tags = get_terms(array(
	'taxonomy' => 'post_tag',
	'orderby' => 'ID',
	'order' => 'ASC'
));

?>

<div class="hero hero_search-radio hero_center heading-bg">
	<div class="container">
		<div class="hero__inner clearfix max-w-823">
			<h1 class="hero__title"><?php echo ($page_for_posts = get_option('page_for_posts')) ? get_the_title($page_for_posts) : __('Blog', 'wb'); ?></h1>
			<form action="<?php echo home_url('/'); ?>" method="get" class="hero-search-wrap">
				<div class="hero-search hero-search_single">
					<div class="hero-search__input">
						<input type="text" name="s" value="<?php echo $query; ?>" class="form-control" placeholder="<?php _e('e.g. SEO or Email Marketing', 'wb'); ?>">
					</div>
					<div class="hero-search__append">
						<button type="submit" class="btn btn-green hero-search__btn">
							<i class="icon icon-search"></i> <?php _e('SEARCH', 'wb'); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

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
				<form action="<?php echo strtok($_SERVER['REQUEST_URI'], '?'); ?>" method="get" autocomplete="off">
					<input type="hidden" name="per_page" value="<?php echo $per_page; ?>">
					<input type="hidden" name="sort" value="<?php echo $sort; ?>">
					<div class="side-panel__body">
						<?php if ($types) : ?>
							<div class="product-filter__box">
								<div class="product-filter__box__heading">
									<h3 class="product-filter__box__title"><?php _e('Content Types', 'wb'); ?></h3>
									<?php if (count($types) > 5) : ?>
										<a href="#id01" class="product-filter__box__all" data-fancybox>
											<?php _e('See All', 'wb'); ?>
										</a>
									<?php endif; ?>
								</div>
								<ul class="product-filter__list">
									<?php foreach (array_slice($types, 0, 5) as $_type) : ?>
										<li>
											<label class="product-filter-control">
												<input type="checkbox" name="type[]" value="<?php echo $_type->term_id; ?>" <?php echo in_array($_type->term_id, $type) ? 'checked' : ''; ?>>
												<span class="product-filter-control__label">
													<?php echo $_type->name; ?>
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
									<h3 class="product-filter__box__title"><?php _e('Tags', 'wb'); ?></h3>
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
												<input type="checkbox" name="post_tag[]" value="<?php echo $_tag->term_id; ?>" <?php echo in_array($_tag->term_id, $tag) ? 'checked' : ''; ?>>
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
						<button class="btn btn-link btn-block product-filter__clear" style="display: <?php echo (!empty($tag) || !empty($type)) ? 'block' : 'none'; ?>">
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
							<?php if (is_search()) : ?>
								<input type="hidden" name="query" value="<?php echo $query; ?>">
							<?php endif; ?>
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
										</div>
									<?php endif; ?>
									<div class="product-box__info">
										<h3 class="product-box__title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h3>
										<div class="product-box__text">
											<p><?php echo $post->post_excerpt ? mb_strimwidth($post->post_content, 0, 180, '...') : mb_strimwidth($post->post_content, 0, 180, '...'); ?></p>
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
											</div>
										</div>
										<div class="product-box__info-list__middle">
											<div class="product-box__description">
												<p><?php echo $post->post_excerpt ? mb_strimwidth($post->post_content, 0, 300, '...') : mb_strimwidth($post->post_content, 0, 300, '...'); ?></p>
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
										</div>
									</div>
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

<div class="popup-box" id="id01">
	<div class="popup-box-head">
		<h4><?php _e('Filter Results', 'wb'); ?></h4>
	</div>
	<form action="<?php the_permalink($page_for_posts); ?>" method="get" autocomplete="off">
		<input type="hidden" name="per_page" value="<?php echo $per_page; ?>">
		<input type="hidden" name="sort" value="<?php echo $sort; ?>">
		<div class="popup-box-content">
		<div class="popup-box-content-elem popup-box-content-elem__430">
			<div class="product-filter__body">
				<?php if ($types) : ?>
					<div class="product-filter__box">
						<h3 class="product-filter__box__title"><?php _e('Content Types', 'wb'); ?></h3>
						<ul class="product-filter__list product-filter__list__columns">
							<?php foreach ($types as $_type) : ?>
								<li>
									<label class="product-filter-control">
										<input type="checkbox" name="type[]" value="<?php echo $_type->term_id; ?>" <?php echo in_array($_type->term_id, $type) ? 'checked' : ''; ?>>
										<span class="product-filter-control__label">
											<?php echo $_type->name; ?>
										</span>
									</label>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				<?php if ($tags) : ?>
					<div class="product-filter__box">
						<h3 class="product-filter__box__title"><?php _e('Tags', 'wb'); ?></h3>
						<ul class="product-filter__list product-filter__list__columns">
							<?php foreach ($tags as $_tag) : ?>
								<li>
									<label class="product-filter-control">
										<input type="checkbox" name="post_tag[]" value="<?php echo $_tag->term_id; ?>" <?php echo in_array($_tag->term_id, $tag) ? 'checked' : ''; ?>>
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
		<div class="popup-box-content-elem popup-box-content-elem__95" style="display: <?php echo ($type || $tag) ? 'block' : 'none'; ?>">
			<div class="filter-s-results">
				<div class="filter-s-results-e">
					<div class="filter-s-results-title">
						<?php _e('Selected', 'wb'); ?>:
					</div>
				</div>
				<div class="filter-s-results-e">
					<ul class="filter-ch-area">
						<li class="filter-ch-elem" id="tag" style="display: <?php echo $tag ? 'block' : 'none'; ?>">
							<div class="checked-el">
								<span>
									<?php _e('Tags', 'wb'); ?>
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
