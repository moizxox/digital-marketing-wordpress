<?php

/**
 * Template Name: Explore
 */

if (!defined('ABSPATH')) {
	exit;
}

get_header();

the_post();

$tool_count = wp_count_posts('tool')->publish;
$course_count = wp_count_posts('course')->publish;
$service_count = wp_count_posts('service')->publish;

$type = (isset($_GET['type']) && in_array($_GET['type'], array('tool', 'course', 'service'))) ? $_GET['type'] : 'tool';
$letter = (isset($_GET['letter']) && in_array($_GET['letter'], range('A', 'Z'))) ? $_GET['letter'] : '';

$letters = array();

if ($terms = get_terms($type . '-tag')) {
	foreach ($terms as $term) {
		$letters[ucfirst($term->name[0])][] = array(
			$term->term_id,
			$term->name,
			$term->count
		);
	}

	foreach ($letters as $key => $tags) {
		$letters[$key] = array_chunk($tags, ceil(count($tags) / 4));
	}
}

?>

<div class="hero hero_explore heading-bg hero_center">
	<div class="container">
		<div class="hero__inner clearfix">
			<h1 class="hero__title"><?php _e('Explore', 'wb'); ?></h1>
			<ul class="hero-tabs-nav">
				<li <?php echo ($type == 'tool') ? 'class="current_tab"' : ''; ?>>
					<a href="<?php echo add_query_arg('type', 'tool'); ?>">
						<span><?php _e(sprintf('Digital Marketing Tools (%d)', $tool_count), 'wb'); ?></span>
					</a>
				</li>
				<li <?php echo ($type == 'course') ? 'class="current_tab"' : ''; ?>>
					<a href="<?php echo add_query_arg('type', 'course'); ?>">
						<span><?php _e(sprintf('Digital Marketing Courses (%d)', $course_count), 'wb'); ?></span>
					</a>
				</li>
				<li <?php echo ($type == 'service') ? 'class="current_tab"' : ''; ?>>
					<a href="<?php echo add_query_arg('type', 'service'); ?>">
						<span><?php _e(sprintf('Digital Marketing Services (%d)', $service_count), 'wb'); ?></span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>

<main class="main page-explore">
	<div class="container">
		<div class="panel-white mb-35">
			<div class="desc-search-heading page-heading">
				<div class="heading-desc">
					<?php the_content(); ?>
				</div>
				<?php if ($search_page = wb_get_page_by_template('search')) : ?>
					<form action="<?php echo get_permalink($search_page); ?>" method="get" class="hero-search hero-search_single">
						<div class="hero-search__input">
							<input type="text" name="query" class="form-control" placeholder="<?php _e('e.g. SEO or Email Marketing', 'wb'); ?>">
							<input type="hidden" name="type" value="<?php echo $type; ?>">
						</div>
						<div class="hero-search__append">
							<button type="submit" class="btn btn-green hero-search__btn">
								<i class="icon icon-search"></i> <?php _e('SEARCH', 'wb'); ?>
							</button>
						</div>
					</form>
				<?php endif; ?>
			</div>
			<div class="explore-wrap">
				<div class="explore-alph pd-lr-50">
					<ul class="explore-alph-list">
						<li <?php echo ($letter == '') ? 'class="active"' : ''; ?>>
							<a href="<?php the_permalink(); ?>">
								<?php _e('All', 'wb'); ?>
							</a>
						</li>
						<?php foreach (range('A', 'Z') as $_letter) : ?>
							<li class="<?php echo ($letter == $_letter) ? 'active' : ''; ?> <?php echo !isset($letters[$_letter]) ? 'disabled' : ''; ?>">
								<a href="<?php echo add_query_arg('letter', $_letter); ?>">
									<?php echo $_letter; ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="explore-results pd-lr-50">
					<?php if ($letters) : ?>
						<?php

						if ($letter) {
							$letters = isset($letters[$letter]) ? array($letter => $letters[$letter]) : array();
						}

						?>
						<?php if ($letters) : ?>
							<?php foreach ($letters as $letter => $tags) : ?>
								<div class="explore-row">
									<div class="explore-row__letter">
										<span><?php echo $letter; ?></span>
									</div>
									<div class="explore-row-results">
										<div class="row">
											<?php for ($i = 0; $i < 4; $i++) : ?>
												<div class="col-lg-3">
													<?php if (isset($tags[$i])) : ?>
														<ul>
															<?php foreach ($tags[$i] as $tag) : ?>
																<li>
																	<a href="<?php echo get_term_link($tag[0]); ?>" class="btn-tag">
																		<?php echo $tag[1]; ?> (<?php echo $tag[2]; ?>)
																	</a>
																</li>
															<?php endforeach; ?>
														</ul>
													<?php endif; ?>
												</div>
											<?php endfor; ?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						<?php else : ?>
							<br>
							<p class="text-center"><?php _e('Apologies, but no entries were found.', 'wb'); ?></p>
						<?php endif; ?>
					<?php else : ?>
						<br>
						<p class="text-center"><?php _e('Apologies, but no entries were found.', 'wb'); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
