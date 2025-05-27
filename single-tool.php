<?php

if (!defined('ABSPATH')) {
	exit;
}

wp_enqueue_script('jquery.mCustomScrollbar', WB_THEME_URL . '/js/jquery.mCustomScrollbar.js', array('main'));
wp_enqueue_script('jquery.fancybox', WB_THEME_URL . '/js/jquery.fancybox.js', array('jquery.mCustomScrollbar'));
wp_enqueue_script('owl.carousel', WB_THEME_URL . '/js/owl.carousel.js', array('jquery.fancybox'));

get_header();

the_post();

$categories = wp_get_post_terms($post->ID, 'tool-category', 'fields=ids');
$tags = wp_get_post_terms($post->ID, 'tool-tag');
$pricing_options = wp_get_post_terms($post->ID, 'tool-pricing-option');

$related_tools = array();

if ($related_tools = get_post_meta($post->ID, '_related_tools', true)) {
	$related_tools = get_posts(array(
		'post_type' => 'tool',
		'numberposts' => 12,
		'post__in' => $related_tools
	));
} else if ($categories) {
	$related_tools = get_posts(array(
		'post_type' => 'tool',
		'posts_per_page' => 12,
		'post__not_in' => array($post->ID),
		'tax_query' => array(
			array(
				'taxonomy' => 'tool-category',
				'field' => 'term_id',
				'terms' => $categories,
				'operator' => 'IN'
			)
		)
	));
}

?>

<div class="hero hero_single heading-bg">
	<div class="container">
		<div class="hero__inner clearfix">
			<?php if ($search_page = wb_get_page_by_template('search')) : ?>
				<form action="<?php echo get_permalink($search_page); ?>" method="get" class="hero-search hero-search_single">
					<div class="hero-search__input">
						<input type="text" name="query" class="form-control" placeholder="<?php _e('e.g. SEO or Email Marketing', 'wb'); ?>">
						<input type="hidden" name="type" value="<?php echo get_post_type(); ?>">
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

<div class="single-heading">
	<div class="container">
		<div class="single-heading__inner">
			<h1 class="single-heading__title text-xl"><?php the_title(); ?></h1>
		</div>
	</div>
</div>

<div class="product-container">
	<div class="container">
		<div class="product">
			<div class="row">
				<div class="col-md-5">
					<div class="product__left">
						<div class="product-box-detail">
							<?php if (has_post_thumbnail()) : ?>
								<div class="product-box-detail__image">
									<img src="<?php echo wp_get_attachment_image_src(get_post_thumbnail_id(), '480x360')[0]; ?>" srcset="<?php echo wp_get_attachment_image_src(get_post_thumbnail_id(), '480x360')[0]; ?> 1x, <?php echo wb_image(wp_get_attachment_image_src(get_post_thumbnail_id(), 'full')[0], 960, 720); ?> 2x" alt="<?php the_title(); ?>">
									<?php // the_post_thumbnail('480x360'); ?>
									<?php if ($logo = get_post_meta($post->ID, '_logo', true)) : ?>
										<div class="product-box-detail__logo">
											<img src="<?php echo wb_image($logo, 150, 150); ?>" alt="<?php the_title(); ?>">
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<div class="product-box-detail__info">
								<h2 class="text-md product-box-detail__title"><?php the_title(); ?></h2>
								<ul class="product-box-detail__btns">
									<?php if ($website_url = get_post_meta($post->ID, '_website_url', true)) : ?>
										<li>
											<a href="<?php echo $website_url; ?>" target="_blank" class="btn btn-green btn-square gtm-visit">
												<?php _e('Visit Website', 'wb'); ?>
											</a>
										</li>
									<?php endif; ?>
									<?php if ($compare_page = wb_get_page_by_template('compare')) : ?>
										<li>
											<a href="<?php echo get_permalink($compare_page); ?>" class="btn btn-outline-blue-light btn-square roduct-box-detail__add-compare <?php echo Compare::in_list($post) ? 'added' : ''; ?>" data-action="<?php echo get_permalink($post) . 'add/'; ?>">
												<?php _e(Compare::in_list($post) ? 'Compare' : 'Add to Compare', 'wb'); ?>
											</a>
										</li>
									<?php endif; ?>
								</ul>
								<div class="share product-box-detail__share">
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
						<div class="row">
							<?php if ($tags) : ?>
								<div class="col-lg-6 hidden-md">
									<div class="product-box-detail__di">
										<h2 class="text-md product-content__title">
											<?php _e('Features', 'wb'); ?>
										</h2>
										<ul class="product-tag-list">
											<?php foreach ($tags as $tag) : ?>
												<li>
													<a href="<?php echo get_term_link($tag); ?>" class="btn-tag">
														<?php echo $tag->name; ?>
													</a>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								</div>
							<?php endif; ?>
							<div class="<?php echo $tags ? 'col-lg-6' : 'col-lg-12'; ?>">
								<div class="product-box-detail__di">
									<?php $amount = get_post_meta($post->ID, '_amount', true); ?>
									<?php if ($amount || $pricing_options) : ?>
										<h2 class="text-md product-content__title">
											<?php _e('Pricing', 'wb'); ?>
										</h2>
									<?php endif; ?>
									<?php if ($amount != '') : ?>
										<div class="price-b product-box-detail__price">
											<?php if ($amount === '0') : ?>
												<?php _e('FREE', 'wb'); ?>
											<?php else : ?>
												<?php _e('from', 'wb'); ?>
												<?php echo get_post_meta($post->ID, '_currency', true); ?><?php echo $amount; ?>
											<?php endif; ?>
										</div>
									<?php endif; ?>
									<?php if ($pricing_options) : ?>
										<ul class="product-marker-list">
											<?php foreach ($pricing_options as $pricing_option) : ?>
												<li>
													<span><?php echo $pricing_option->name; ?></span>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="product__right">
						<div class="product-info">
							<?php if ($post->post_content) : ?>
								<div class="product-content">
									<h2 class="text-md product-content__title"><?php _e('Description', 'wb'); ?></h2>
									<?php the_content(); ?>
								</div>
							<?php endif; ?>
							<?php if ($clients = get_post_meta($post->ID, '_clients', true)) : ?>
								<div class="product-content product-brand">
									<h2 class="text-md product-content__title"><?php _e('Used by', 'wb'); ?></h2>
									<ul class="product-brand__list">
										<?php foreach ($clients as $client) : ?>
											<li>
												<span>
													<img src="<?php echo $client['image']; ?>" alt="<?php echo $client['title']; ?>">
												</span>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
							<?php if ($additional_content_text = get_post_meta($post->ID, '_additional_content_text', true)) : ?>
								<div class="product-content">
									<?php if ($additional_content_title = get_post_meta($post->ID, '_additional_content_title', true)) : ?>
										<h2 class="text-md product-content__title">
											<?php echo $additional_content_title; ?>
										</h2>
									<?php endif; ?>
									<?php echo wpautop($additional_content_text); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="visible-md">
				<?php if ($tags) : ?>
					<div class="product-box-detail__di">
						<h2 class="text-md product-content__title">
							<?php _e('Features', 'wb'); ?>
						</h2>
						<ul class="product-tag-list">
							<?php foreach ($tags as $tag) : ?>
								<li>
									<a href="<?php echo get_term_link($tag); ?>" class="btn-tag">
										<?php echo $tag->name; ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				<ul class="product-box-detail__btns">
					<?php if ($website_url = get_post_meta($post->ID, '_website_url', true)) : ?>
						<li>
							<a href="<?php echo $website_url; ?>" target="_blank" class="btn btn-green btn-square">
								<?php _e('Visit Website', 'wb'); ?>
							</a>
						</li>
					<?php endif; ?>
					<?php if ($compare_page) : ?>
						<li>
							<a href="<?php echo get_permalink($compare_page); ?>" class="btn btn-outline-blue-light btn-square roduct-box-detail__add-compare <?php echo Compare::in_list($post) ? 'added' : ''; ?>" data-action="<?php echo get_permalink($post) . 'add/'; ?>">
								<?php _e(Compare::in_list($post) ? 'Compare' : 'Add to Compare', 'wb'); ?>
							</a>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
		<?php if ($related_tools) : ?>
			<div class="products-related">
				<h2 class="text-lg products-related__title">
					<?php _e('Similar Tools', 'wb'); ?>
				</h2>
				<div class="carousel-products owl-carousel" data-autoplay="false" data-nav-text="[&quot;&lt;i class='icon icon-arrow-right'&gt;&lt;/i&gt; &quot;,&quot;&lt;i class='ficon icon-arrow-right'&gt;&lt;/i&gt;&quot;]" data-nav="true" data-dots="false" data-loop="true" data-slidespeed="200" data-margin="54" data-responsive="{&quot;0&quot;:{ &quot;margin&quot; : 20, &quot;items&quot;: &quot;1&quot;}, &quot;600&quot;:{&quot;margin&quot; : 20, &quot;items&quot;: &quot;2&quot;}, &quot;850&quot;:{&quot;margin&quot; : 15 , &quot;items&quot;: &quot;3&quot;}, &quot;1200&quot;:{&quot;items&quot;: &quot;4&quot;}}">
					<?php foreach ($related_tools as $related_tool) : ?>
						<div class="item">
							<div class="product-box">
								<?php if (has_post_thumbnail($related_tool)) : ?>
									<div class="product-box__image">
										<a href="<?php echo get_permalink($related_tool); ?>">
											<?php echo get_the_post_thumbnail($related_tool, '480x360'); ?>
										</a>
									</div>
								<?php endif; ?>
								<div class="product-box__info">
									<h3 class="product-box__title">
										<a href="<?php echo get_permalink($related_tool); ?>">
											<?php echo get_the_title($related_tool); ?>
										</a>
									</h3>
									<div class="product-box__text">
										<p><?php echo $related_tool->post_excerpt ? $related_tool->post_excerpt : mb_strimwidth($related_tool->post_content, 0, 80, '...'); ?></p>
									</div>
								</div>
								<?php $amount = get_post_meta($related_tool->ID, '_amount', true); ?>
								<?php if ($amount != '') : ?>
									<div class="product-box__price">
										<?php if ($amount === '0') : ?>
											<?php _e('FREE', 'wb'); ?> <div class="price"></div>
										<?php elseif ($amount === 'Contact Vendor') : ?>
											<?php _e('Contact Vendor', 'wb'); ?> <div class="price"></div>
										<?php else : ?>
											<?php echo is_numeric($amount) ? __('Price from', 'wb') : ''; ?><div class="price"><?php echo get_post_meta($related_tool->ID, '_currency', true); ?><?php echo $amount; ?></div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

<div id="modal-compare">
	<div class="modal-content">
		<div class="modal-content__in">
			<div class="compare-info">
				<div class="compare-info__photo">
					<img src="<?php echo WB_THEME_URL; ?>/images/compare-add.png" srcset="<?php echo WB_THEME_URL; ?>/images/compare-add@2x.png 2x" alt="<?php _e('Compare', 'wb'); ?>">
				</div>
				<h3 class="compare-info__title">
					<?php _e(sprintf('You added “%s” to the comparison list.', get_the_title()), 'wb'); ?> <br>
					<?php _e(sprintf('You have %s items in your comparison list.', '<span class="badge-num">1</span>'), 'wb'); ?>
				</h3>
				<div class="compare-info__check">
					<label>
						<input type="checkbox">
						<span class="compare-info__check__label">
							<?php _e('Don’t show me this next time', 'wb'); ?>
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

<?php get_footer(); ?>
