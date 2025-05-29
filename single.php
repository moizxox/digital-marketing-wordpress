<?php

if (!defined('ABSPATH')) {
	exit;
}

wp_enqueue_script('jquery.mCustomScrollbar', WB_THEME_URL . '/js/jquery.mCustomScrollbar.js', array('main'));
wp_enqueue_script('jquery.fancybox', WB_THEME_URL . '/js/jquery.fancybox.js', array('jquery.mCustomScrollbar'));
wp_enqueue_script('owl.carousel', WB_THEME_URL . '/js/owl.carousel.js', array('jquery.fancybox'));

get_header();

the_post();

$categories = wp_get_post_terms($post->ID, 'category', 'fields=ids');
$tags = wp_get_post_terms($post->ID, 'post_tag');

$related_tools = array();

if ($related_posts = get_post_meta($post->ID, '_related_posts', true)) {
	$related_posts = get_posts(array(
		'post_type' => 'post',
		'numberposts' => 12,
		'post__in' => $related_posts
	));
} else if ($categories) {
	$related_posts = get_posts(array(
		'post_type' => 'post',
		'posts_per_page' => 12,
		'post__not_in' => array($post->ID),
		'tax_query' => array(
			array(
				'taxonomy' => 'category',
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
			<form action="<?php echo home_url('/'); ?>" method="get" class="hero-search hero-search_single">
				<div class="hero-search__input">
					<input type="text" name="s" class="form-control" placeholder="<?php _e('e.g. SEO or Email Marketing', 'wb'); ?>">
					<input type="hidden" name="post_type" value="post">
				</div>
				<div class="hero-search__append">
					<button type="submit" class="btn btn-green hero-search__btn">
						<i class="icon icon-search"></i> <?php _e('SEARCH', 'wb'); ?>
					</button>
				</div>
			</form>
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
											<?php _e('Tags', 'wb'); ?>
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
								<div class="product-box-detail__di"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="product__right">
						<div class="product-info">
							<?php if ($post->post_content) : ?>
								<div class="product-content">
									<?php the_content(); ?>
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
				</ul>
			</div>
		</div>
		<?php if ($related_posts) : ?>
			<div class="products-related">
				<h2 class="text-lg products-related__title">
					<?php _e('Similar Posts', 'wb'); ?>
				</h2>
				<div class="carousel-products owl-carousel" data-autoplay="false" data-nav-text="[&quot;&lt;i class='icon icon-arrow-right'&gt;&lt;/i&gt; &quot;,&quot;&lt;i class='ficon icon-arrow-right'&gt;&lt;/i&gt;&quot;]" data-nav="true" data-dots="false" data-loop="true" data-slidespeed="200" data-margin="54" data-responsive="{&quot;0&quot;:{ &quot;margin&quot; : 20, &quot;items&quot;: &quot;1&quot;}, &quot;600&quot;:{&quot;margin&quot; : 20, &quot;items&quot;: &quot;2&quot;}, &quot;850&quot;:{&quot;margin&quot; : 15 , &quot;items&quot;: &quot;3&quot;}, &quot;1200&quot;:{&quot;items&quot;: &quot;4&quot;}}">
					<?php foreach ($related_posts as $related_post) : ?>
						<div class="item">
							<div class="product-box">
								<?php if (has_post_thumbnail($related_post)) : ?>
									<div class="product-box__image">
										<a href="<?php echo get_permalink($related_post); ?>">
											<?php echo get_the_post_thumbnail($related_post, '480x360'); ?>
										</a>
									</div>
								<?php endif; ?>
								<div class="product-box__info">
									<h3 class="product-box__title">
										<a href="<?php echo get_permalink($related_post); ?>">
											<?php echo get_the_title($related_post); ?>
										</a>
									</h3>
									<div class="product-box__text">
										<p><?php echo $related_post->post_excerpt ? $related_post->post_excerpt : mb_strimwidth($related_post->post_content, 0, 80, '...'); ?></p>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>
