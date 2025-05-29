<?php

if (!defined('ABSPATH')) {
	exit;
}

$no_hero = true;

get_header();

the_post();

$categories = wp_get_post_terms($post->ID, 'blog-post-category');
$tags = wp_get_post_terms($post->ID, 'blog-post-tag');

$blog_posts = get_posts(array(
	'post_type' => 'blog-post',
	'posts_per_page' => 25,
	'post__not_in' => array($post->ID),
	'tax_query' => array(
		array(
			'taxonomy' => 'blog-post-category',
			'field' => 'term_id',
			'terms' => wp_get_post_terms($post->ID, 'blog-post-category', 'fields=ids'),
			'operator' => 'IN'
		)
	)
));

?>

<main class="main">
	<div class="container">
		<div class="row">
			<div class="<?php echo $blog_posts ? 'col-xl-8' : 'col-xl-12'; ?>">
				<div class="side-toggle side-toggle_guide">
					<h3><?php _e('Blog', 'wb'); ?></h3>
					<button class="side-toggle__btn side-toggle_guide__btn">
						<span></span>
						<span></span>
					</button>
				</div>
				<div class="content guide-content blog-content">
					
							<?php if ($categories) : ?>
						<div class="blog-categories">
							
							
								<?php foreach ($categories as $category) : ?>
									
										<a href="<?php echo get_term_link($category); ?>">
											<?php echo $category->name; ?>
										</a>
									
								<?php endforeach; ?>
							
						</div>
					<?php endif; ?>
					
					<h1><?php the_title(); ?></h1>
					<div class="blog-date"><?php the_date(); ?></div>
					
					<?php the_content(); ?>
					
			
					<?php if ($tags) : ?>
						<div class="blog-tags">
							<h2 class="text-md product-content__title">
								<?php _e('Tags', 'wb'); ?>
							</h2>
							
								<?php foreach ($tags as $tag) : ?>
									
										<a href="<?php echo get_term_link($tag); ?>" class="btn-tag">
											<?php echo $tag->name; ?>
										</a>
									
								<?php endforeach; ?>
							
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php if ($blog_posts) : ?>
				<div class="col-xl-4">
					<div class="side-panel side-panel_guide blog_sidebar">
						<div class="side-panel__heading">
							<h3><?php _e('Topics', 'wb'); ?></h3>
							<button class="side-panel-close side-panel_guide__close">
								<i class="icon icon-close"></i>
							</button>
						</div>
						<div class="side-panel__body">
							<ul class="side-panel__list">
								<?php foreach ($blog_posts as $blog_post) : ?>
									<li>
										<a href="<?php echo get_permalink($blog_post); ?>">
											<?php echo get_the_title($blog_post); ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
