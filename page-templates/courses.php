<?php

/**
 * Template Name: Courses
 */

if (!defined('ABSPATH')) {
	exit;
}

get_header();

the_post();

$sort = (isset($_GET['sort']) && in_array($_GET['sort'], array('alphabetically', 'popularity'))) ? $_GET['sort'] : 'alphabetically';

$categories_args = array(
	'orderby' => 'name',
	'order' => 'ASC' 
);

if ($sort == 'popularity') {
	$categories_args = array(
		'meta_query' => array(
			array(
				'key' => '_views',
				'type' => 'NUMERIC'
			)
		),
		'orderby' => 'meta_value_num',
		'order' => 'DESC' 
	);
}

$categories = get_terms('course-category', $categories_args);

?>

<div class="hero hero_posts hero_center heading-bg">
	<div class="container">
		<div class="hero__inner clearfix max-w-823">
			<h1 class="hero__title"><?php the_title(); ?></h1>
			<div class="hero__desc">
				<?php the_content(); ?>
			</div>
			<?php if ($search_page = wb_get_page_by_template('search')) : ?>
				<form action="<?php echo get_permalink($search_page); ?>" method="get" class="hero-search hero-search_single">
					<div class="hero-search__input">
						<input type="text" name="query" class="form-control" placeholder="<?php _e('e.g. SEO or Email Marketing', 'wb'); ?>">
						<input type="hidden" name="type" value="course">
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

<main class="main page-posts">
	<div class="container">
		<div class="control-bar">
			<form method="get" class="control-bar__right">
				<ul class="control-bar-list">
					<li class="control-bar-item">
						<div class="control-bar__label"><?php _e('Sort by', 'wb'); ?></div>
						<select name="sort" class="control-bar__select select-styler">
							<option value="alphabetically" <?php selected('alphabetically', $sort); ?>>
								<?php _e('Alphabetically', 'wb'); ?>
							</option>
							<option value="popularity" <?php selected('popularity', $sort); ?>>
								<?php _e('Popularity', 'wb'); ?>
							</option>
						</select>
					</li>
				</ul>
			</form>
		</div>
		<div class="posts-grid">
			<div class="row row-gap-10">
				<?php if ($categories) : ?>
					<?php foreach ($categories as $category) : ?>
						<div class="col-xl-3 col-md-4 col-sm-6 col-gap-10">
							<div class="post-box">
								<div class="post-box__image">
									<?php if ($thumbnail = get_term_meta($category->term_id, '_thumbnail', true)) : ?>
										<a href="<?php echo get_term_link($category); ?>">
											<img src="<?php echo wb_image($thumbnail, 290, 220); ?>" alt="<?php echo $category->name; ?>">
										</a>
									<?php endif; ?>
								</div>
								<div class="post-box__info">
									<h3 class="post-box__title box-title">
										<a href="<?php echo get_term_link($category); ?>">
											<?php echo $category->name; ?> (<?php echo $category->count; ?>)
										</a>
									</h3>
									<div class="post-box__desc box-desc">
										<?php echo term_description($category); ?>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<div class="col-md-12">
						<p class="text-center"><?php _e('Apologies, but no entries were found.', 'wb'); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
