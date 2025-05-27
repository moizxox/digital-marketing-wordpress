<?php

/**
 * Template Name: Guides
 */

if (!defined('ABSPATH')) {
	exit;
}

$no_hero = true;

get_header();

the_post();

$guides = get_posts(array(
	'post_type' => 'guide',
	'posts_per_page' => -1
));

?>

<main class="main">
	<div class="container">
		<div class="row">
			<?php if ($guides) : ?>
				<div class="col-xl-3">
					<div class="side-panel side-panel_guide">
						<div class="side-panel__heading">
							<h3><?php _e('Guides', 'wb'); ?></h3>
							<button class="side-panel-close side-panel_guide__close">
								<i class="icon icon-close"></i>
							</button>
						</div>
						<div class="side-panel__body">
							<ul class="side-panel__list">
								<?php foreach ($guides as $guide) : ?>
									<li>
										<a href="<?php echo get_permalink($guide); ?>">
											<?php echo get_the_title($guide); ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="<?php echo $guides ? 'col-xl-9' : 'col-xl-12'; ?>">
				<div class="side-toggle side-toggle_guide">
					<h3><?php _e('Guides', 'wb'); ?></h3>
					<button class="side-toggle__btn side-toggle_guide__btn">
						<span></span>
						<span></span>
					</button>
				</div>
				<div class="content">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
