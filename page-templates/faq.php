<?php

/**
 * Template Name: FAQ
 */

if (!defined('ABSPATH')) {
	exit;
}

get_header();

the_post();

$categories = get_terms('faq-category');

?>

<div class="hero hero_page heading-bg">
	<div class="container max-w_955">
		<div class="hero__inner">
			<a href="#" class="btn btn-blue-light bdrs-5 btn-back" onclick="javascript:history.back(-1);">
				<i class="icon icon-triangle-back"></i> <?php _e('Back', 'wb'); ?>
			</a>
			<h1 class="hero__title"><?php the_title(); ?></h1>
		</div>
	</div>
</div>

<main class="main-container">
	<div class="container max-w_955">
		<?php if ($categories) : ?>
			<?php foreach ($categories as $category) : ?>
				<div class="faq">
					<h2 class="faq__title text-md"><?php echo $category->name; ?></h2>
					<div class="faq-boxes">
						<?php

						$faqs = get_posts(array(
							'post_type' => 'faq',
							'posts_per_page' => -1,
							'tax_query' => array(
								array(
									'taxonomy' => 'faq-category',
									'field' => 'term_id',
									'terms' => $category->term_id
								)
							)
						));

						?>
						<?php if ($faqs) : ?>
							<?php foreach ($faqs as $faq) : ?>
								<div class="faq-box">
									<h3><?php echo get_the_title($faq); ?></h3>
									<?php echo get_the_content(null, false, $faq); ?>
								</div>
							<?php endforeach; ?>
						<?php else : ?>
							<p><?php _e('Apologies, but no entries were found.', 'wb'); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<p><?php _e('Apologies, but no entries were found.', 'wb'); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php get_footer(); ?>
