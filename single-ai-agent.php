<?php

if (!defined('ABSPATH')) {
	exit;
}

wp_enqueue_script('jquery.mCustomScrollbar', WB_THEME_URL . '/js/jquery.mCustomScrollbar.js', array('main'));
wp_enqueue_script('jquery.fancybox', WB_THEME_URL . '/js/jquery.fancybox.js', array('jquery.mCustomScrollbar'));
wp_enqueue_script('owl.carousel', WB_THEME_URL . '/js/owl.carousel.js', array('jquery.fancybox'));

get_header();

the_post();

$categories = wp_get_post_terms($post->ID, 'ai-agent-category', 'fields=ids');
$tags = wp_get_post_terms($post->ID, 'ai-agent-tag');
$pricing_options = wp_get_post_terms($post->ID, 'ai-agent-pricing-option');
?>

<div class="hero hero_single heading-bg">
	<div class="container">
		<div class="hero__inner clearfix">
			<?php if ($search_page = wb_get_page_by_template('search')) : ?>
				<form action="<?php echo get_permalink($search_page); ?>" method="get" class="hero-search hero-search_single">
					<div class="hero-search__input">
						<input type="text" name="query" class="form-control" placeholder="<?php _e('e.g. AI Agent or Automation', 'wb'); ?>">
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
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?> 