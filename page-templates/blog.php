<?php

/**
 * Template Name: Blog
 */

if (!defined('ABSPATH')) {
	exit;
}

$no_hero = true;

get_header();

the_post();

$categories = get_terms(array(
	'taxonomy' => 'blog-post-category'
));

?>

<main class="main">
	<div class="container">
		<div class="works-about bdrs-5">
			<div class="row">
				<div class="col-lg-6">
					<div class="works-about__info">
						<?php if ($title = get_post_meta($post->ID, '_title', true)) : ?>
							<h1 class="text-lg works-about__title"><?php echo esc_attr($title); ?></h1>
						<?php endif; ?>
						<?php if ($description = get_post_meta($post->ID, '_description', true)) : ?>
							<?php echo wpautop($description); ?>
						<?php endif; ?>
					</div>
				</div>
				<?php if ($image = get_post_meta($post->ID, '_image', true)) : ?>
					<div class="col-lg-6">
						<div class="works-about__image">
							<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="container">
		<?php if ($categories) : ?>
			<?php foreach ($categories as $category) : ?>
				<h2 class="category-title"><?php echo $category->name; ?></h2>
				<div class="row">
					<?php

					query_posts(array(
						'post_type' => 'blog-post',
						'posts_per_page' => -1,
						'tax_query' => array(
							array(
								'taxonomy' => 'blog-post-category',
								'field' => 'term_id',
								'terms' => $category->term_id
							)
						)
					));

					?>
					<?php while (have_posts()) : the_post(); ?>
						<div class="col-xl-4 col-sm-6">
							<div class="item blog">
								<div class="product-box">
									<div class="product-box__image">
										<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
											<?php echo the_post_thumbnail('480x360'); ?>
										</a>
									</div>
									<div class="product-box__info">
										<h3 class="product-box__title">
											<a href="<?php the_permalink(); ?>"><?php the_title();?></a>
										</h3>
										<!--<div class="product-box__text">
											<p><?php the_author_meta('display_name'); ?></p>
										</div>-->
									</div>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</main>

<?php get_footer(); ?>
