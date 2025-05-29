<?php

if (!defined('ABSPATH')) {
	exit;
}

$no_hero = true;

get_header();

$term = get_queried_object();

?>

<main class="main">
	<div class="container">
		<h1 class="category-title-page"><?php echo $term->name; ?></h1>
		<div class="row">
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
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
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
	</div>
</main>

<?php get_footer(); ?>
