<?php

if (!defined('ABSPATH')) {
	exit;
}

get_header();

$type = 'ai-tool';
$pricing_options = get_terms(array(
	'taxonomy' => $type . '-pricing-option',
	'orderby' => 'ID',
	'order' => 'ASC'
));

?>

<div class="hero hero_category hero_center heading-bg">
	<div class="container">
		Hello
		<div class="hero__inner clearfix max-w-823">
			<h1 class="hero__title"><?php _e('AI Tools', 'wb'); ?></h1>
			<?php if ($search_page = wb_get_page_by_template('search')) : ?>
				<form action="<?php echo get_permalink($search_page); ?>" method="get" class="hero-search hero-search_single">
					<div class="hero-search__input">
						<input type="text" name="query" class="form-control" placeholder="<?php _e('e.g. ChatGPT or Midjourney', 'wb'); ?>">
						<input type="hidden" name="type" value="<?php echo $type; ?>">
					</div>
					<div class="hero-search__append">
						<button type="submit" class="btn btn-green hero-search__btn">
							<i class="icon icon-search"></i> <?php _e('SEARCH', 'wb'); ?>
						</button>
					</div>
				</form>
			<?php endif; ?>
		</div>
	</div>
</div>

<div class="category-container">
	<div class="container">
		<div class="category">
			<div class="row">
				<div class="col-lg-3">
					<div class="category__left">
						<div class="category-filter">
							<div class="category-filter__head">
								<h2 class="text-md category-filter__title"><?php _e('Filter', 'wb'); ?></h2>
								<a href="<?php echo get_post_type_archive_link($type); ?>" class="category-filter__clear"><?php _e('Clear All', 'wb'); ?></a>
							</div>
							<?php if ($pricing_options) : ?>
								<div class="category-filter__item">
									<h3 class="text-sm category-filter__subtitle"><?php _e('Pricing Options', 'wb'); ?></h3>
									<ul class="category-filter__list">
										<?php foreach ($pricing_options as $pricing_option) : ?>
											<li>
												<label class="checkbox">
													<input type="checkbox" name="pricing_option[]" value="<?php echo $pricing_option->term_id; ?>" <?php echo (isset($_GET['pricing_option']) && in_array($pricing_option->term_id, (array) $_GET['pricing_option'])) ? 'checked' : ''; ?>>
													<span class="checkbox__box"></span>
													<span class="checkbox__label"><?php echo $pricing_option->name; ?></span>
												</label>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="col-lg-9">
					<div class="category__right">
						<div class="category-sort">
							<div class="category-sort__label"><?php _e('Sort by:', 'wb'); ?></div>
							<div class="category-sort__select">
								<select name="sort" class="form-control">
									<option value="alphabetically" <?php echo (!isset($_GET['sort']) || $_GET['sort'] == 'alphabetically') ? 'selected' : ''; ?>><?php _e('Alphabetically', 'wb'); ?></option>
									<option value="popularity" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'popularity') ? 'selected' : ''; ?>><?php _e('Popularity', 'wb'); ?></option>
									<option value="price-lh" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price-lh') ? 'selected' : ''; ?>><?php _e('Price: Low to High', 'wb'); ?></option>
									<option value="price-hl" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price-hl') ? 'selected' : ''; ?>><?php _e('Price: High to Low', 'wb'); ?></option>
								</select>
							</div>
							<div class="category-sort__label"><?php _e('Show:', 'wb'); ?></div>
							<div class="category-sort__select">
								<select name="per_page" class="form-control">
									<option value="12" <?php echo (!isset($_GET['per_page']) || $_GET['per_page'] == '12') ? 'selected' : ''; ?>>12</option>
									<option value="24" <?php echo (isset($_GET['per_page']) && $_GET['per_page'] == '24') ? 'selected' : ''; ?>>24</option>
									<option value="48" <?php echo (isset($_GET['per_page']) && $_GET['per_page'] == '48') ? 'selected' : ''; ?>>48</option>
									<option value="96" <?php echo (isset($_GET['per_page']) && $_GET['per_page'] == '96') ? 'selected' : ''; ?>>96</option>
								</select>
							</div>
						</div>
						<?php if (have_posts()) : ?>
							<div class="row">
								<?php while (have_posts()) : the_post(); ?>
									<div class="col-lg-4 col-sm-6">
										<div class="product-box">
											<?php if (has_post_thumbnail()) : ?>
												<div class="product-box__image">
													<a href="<?php the_permalink(); ?>">
														<?php the_post_thumbnail('480x360'); ?>
													</a>
												</div>
											<?php endif; ?>
											<div class="product-box__info">
												<h3 class="product-box__title">
													<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
												</h3>
												<div class="product-box__text">
													<p><?php echo get_the_excerpt(); ?></p>
												</div>
											</div>
											<?php $amount = get_post_meta(get_the_ID(), '_amount', true); ?>
											<?php if ($amount != '') : ?>
												<div class="product-box__price">
													<?php if ($amount === '0') : ?>
														<?php _e('FREE', 'wb'); ?> <div class="price"></div>
													<?php elseif ($amount === 'Contact Vendor') : ?>
														<?php _e('Contact Vendor', 'wb'); ?> <div class="price"></div>
													<?php else : ?>
														<?php echo is_numeric($amount) ? __('Price from', 'wb') : ''; ?><div class="price"><?php echo get_post_meta(get_the_ID(), '_currency', true); ?><?php echo $amount; ?></div>
													<?php endif; ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
							<?php wb_pagination(); ?>
						<?php else : ?>
							<div class="category-empty">
								<div class="category-empty__icon">
									<i class="icon icon-search"></i>
								</div>
								<h2 class="text-md category-empty__title"><?php _e('No AI Tools Found', 'wb'); ?></h2>
								<div class="category-empty__text">
									<p><?php _e('Try changing your search criteria', 'wb'); ?></p>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?> 