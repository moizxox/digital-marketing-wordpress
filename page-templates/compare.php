<?php

/**
 * Template Name: Compare
 */

if (!defined('ABSPATH')) {
	exit;
}

wp_enqueue_script('slick', WB_THEME_URL . '/js/slick.js', array('main'));

get_header();

the_post();

$tool_ids = Compare::get_ids('tool');
$course_ids = Compare::get_ids('course');
$service_ids = Compare::get_ids('service');

$type = (isset($_GET['type']) && in_array($_GET['type'], array('tool', 'course', 'service'))) ? $_GET['type'] : 'tool';
$category = isset($_GET['category']) ? intval($_GET['category']) : null;

$items_args = array(
	'post_type' => $type,
	'posts_per_page' => -1,
	'post__in' => ${$type . '_ids'} ? ${$type . '_ids'} : array(-1)
);

if ($category) {
	$items_args['tax_query'] = array(
		array(
			'taxonomy' => $type . '-category',
			'field' => 'term_id',
			'terms' => $category
		)
	);
}

$items = get_posts($items_args);

$categories = array();

if ($items) {
	foreach ($items as $item) {
		$post_terms = wp_get_post_terms($item->ID, $type . '-category');

		if ($post_terms) {
			foreach ($post_terms as $post_term) {
				$categories[$post_term->term_id] = $post_term->name;
			}
		}
	}
}

?>

<div class="hero hero_compare heading-bg">
	<div class="container">
		<div class="hero__inner clearfix">
			<h1 class="hero__title"><?php the_title(); ?></h1>
			<ul class="hero-tabs-nav">
				<li <?php echo ($type == 'tool') ? 'class="current_tab"' : ''; ?>>
					<a href="<?php echo add_query_arg('type', 'tool'); ?>">
						<span><?php _e(sprintf('Digital Marketing Tools (%d)', count($tool_ids)), 'wb'); ?></span>
					</a>
				</li>
				<li <?php echo ($type == 'course') ? 'class="current_tab"' : ''; ?>>
					<a href="<?php echo add_query_arg('type', 'course'); ?>">
						<span><?php _e(sprintf('Digital Marketing Courses (%d)', count($course_ids)), 'wb'); ?></span>
					</a>
				</li>
				<li <?php echo ($type == 'service') ? 'class="current_tab"' : ''; ?>>
					<a href="<?php echo add_query_arg('type', 'service'); ?>">
						<span><?php _e(sprintf('Digital Marketing Services (%d)', count($service_ids)), 'wb'); ?></span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>

<main class="main page-compare">
	<div class="container max-w_1030">
		<div class="compare-heading">
			<div class="row">
				<div class="<?php echo $categories ? 'col-xl-6' : 'col-xl-12'; ?>">
					<div class="compare-heading__left">
						<span class="products-carousel-tabs__count badge-num"><?php echo count($items); ?></span>
						<h2 class="compare-heading__title text-lg">
							<?php _e(sprintf('Compare Digital Marketing %ss', ucfirst($type)), 'wb'); ?>
						</h2>
					</div>
				</div>
				<?php if ($categories) : ?>
					<div class="col-xl-6">
						<div class="compare-heading__right">
							<form method="get" class="compare-choose">
								<input type="hidden" name="type" value="<?php echo $type; ?>">
								<div class="compare-choose__label"><?php _e('Choose a category', 'wb'); ?></div>
								<select name="category" class="select-styler select-radius compare-choose__select">
									<option value=""><?php _e('All', 'wb'); ?></option>
									<?php foreach ($categories as $category_id => $category_name) : ?>
										<option value="<?php echo $category_id; ?>" <?php selected($category_id, $category); ?>>
											<?php echo $category_name; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</form>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="compare-body">
			<?php if ($items) : ?>
				<ul class="compare-body-heading compare-col">
					<li class="compare__row_1 compare__row"></li>
					<li class="compare__row_2 compare__row"> 
						<div class="compare__row__body"><?php _e('Categories', 'wb'); ?></div>
					</li>
					<li class="compare__row_3 compare__row">
						<div class="compare__row__body"><?php _e('Price Options', 'wb'); ?></div>
					</li>
					<li class="compare__row_4 compare__row">
						<div class="compare__row__body"><?php _e('Price', 'wb'); ?></div>
					</li>
					<li class="compare__row_5 compare__row">
						<div class="compare__row__body"><?php _e('Features', 'wb'); ?></div>
					</li>
				</ul>
				<div class="compare-carousel carousel_wrapper" data-columns="3" data-slides-to-scroll="1" data-pagination="on" data-navigation="on" data-auto-height="on" data-draggable="on" data-infinite="off" data-autoplay="off" data-autoplay-speed="3000" data-pause-on-hover="off" data-vertical="off" data-vertical-swipe="off" data-tablet-landscape="2" data-tablet-portrait="2" data-mobile="2">
					<div class="carousel compare-carousel">
						<?php foreach ($items as $item) : ?>
							<div class="item">
								<div class="compare-box compare-col">
									<div class="compare-box__top compare__row compare__row_1">
										<?php if (has_post_thumbnail($item)) : ?>
											<div class="compare-box__image">
												<?php echo get_the_post_thumbnail($item, '330x250'); ?>
											</div>
										<?php endif; ?>
										<div class="compare__row__body">
											<h2 class="compare-box__title">
												<a href="<?php echo get_permalink($item); ?>">
													<?php echo get_the_title($item); ?>
												</a>
											</h2>
											<?php if ($website_url = get_post_meta($item->ID, '_website_url', true)) : ?>
												<div class="compare-box__top__button">
													<a href="<?php echo $website_url; ?>" target="_blank" class="btn btn-green btn-square">
														<?php _e('Visit Website', 'wb'); ?>
													</a>
												</div>
											<?php endif; ?>
										</div>
									</div>
									<div class="compare-box__row compare__row compare__row_2">
										<div class="compare__row__body">
											<?php if ($categories = wp_get_post_terms($item->ID, $type . '-category', 'fields=names')) : ?>
												<ul>
													<?php foreach ($categories as $category) : ?>
														<li><?php echo $category; ?></li>
													<?php endforeach; ?>
												</ul>
											<?php else : ?>
												-
											<?php endif; ?>
										</div>
									</div>
									<div class="compare-box__row compare__row compare__row_3">
										<div class="compare__row__body">
											<?php if ($categories = wp_get_post_terms($item->ID, $type . '-pricing-option', 'fields=names')) : ?>
												<ul>
													<?php foreach ($categories as $category) : ?>
														<li><?php echo $category; ?></li>
													<?php endforeach; ?>
												</ul>
											<?php else : ?>
												-
											<?php endif; ?>
										</div>
									</div>
									<div class="compare-box__row compare__row compare__row_4">
										<div class="compare__row__body">
											<?php $amount = get_post_meta($item->ID, '_amount', true); ?>
											<?php if ($amount != '') : ?>
												<?php if ($amount === '0') : ?>
													<?php _e('FREE', 'wb'); ?>
												<?php else : ?>
													<?php echo is_numeric($amount) ? __('Price from', 'wb') : ''; ?>
													<?php echo get_post_meta($item->ID, '_currency', true); ?><?php echo $amount; ?>
												<?php endif; ?>
											<?php else : ?>
												-
											<?php endif; ?>
										</div>
									</div>
									<div class="compare-box__row compare__row compare__row_5">
										<div class="compare__row__body">
											<?php if ($tags = wp_get_post_terms($item->ID, $type . '-tag', 'fields=names')) : ?>
												<ul>
													<?php foreach ($tags as $tag) : ?>
														<li><?php echo $tag; ?></li>
													<?php endforeach; ?>
												</ul>
											<?php else : ?>
												-
											<?php endif; ?>
										</div>
									</div>
									<div class="compare-box__bottom compare__row">
										<div class="compare__row__body">
											<?php if ($website_url) : ?>
												<div class="compare-box__bottom__button">
													<a href="<?php echo $website_url; ?>" target="_blank" class="btn btn-green btn-square">
														<?php _e('Visit Website', 'wb'); ?>
													</a>
												</div>
											<?php endif; ?>
											<a href="<?php echo get_permalink($item); ?>remove/" class="compare-box__remove">
												<?php _e('Remove from list', 'wb'); ?>
											</a>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
			<p class="empty" <?php echo $items ? 'style="display: none;"' : ''; ?>><?php _e(sprintf('You have no %ss on your comparison list.', $type), 'wb'); ?></p>
		</div>
	</div>
</main>

<?php get_footer(); ?>
