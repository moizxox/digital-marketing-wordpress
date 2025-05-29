<?php

/**
 * Template Name: No Sidebar
 */

if (!defined('ABSPATH')) {
	exit;
}

get_header();

the_post();

?>

<div class="hero hero_page_2 heading-bg">
	<div class="container">
		<div class="hero__inner clearfix">
			<h1 class="hero__title"><?php the_title(); ?></h1>
		</div>
	</div>
</div>

<main class="main-container">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="content content_left">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
