<?php

if (!defined('ABSPATH')) {
	exit;
}

get_header();

?>

<div class="hero hero_page_2 heading-bg">
	<div class="container max-w_955">
		<div class="hero__inner clearfix">
			<h1 class="hero__title"><?php _e('Page not found', 'wb'); ?></h1>
		</div>
	</div>
</div>

<main class="main-container">
	<div class="container max-w_955">
		<div class="row">
			<div class="col-lg-12">
				<div class="content content_left">
					<p><?php _e('We\'re sorry, but the page you were looking for doesn\'t exist.', 'wb'); ?></p>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
