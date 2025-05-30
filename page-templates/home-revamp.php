<?php

/**
 * Template Name: Homepage revamp
 */

if (!defined('ABSPATH')) {
	exit;
}


wp_enqueue_script('typed', WB_THEME_URL . '/js/typed.js', array('main'));
wp_enqueue_script('owl.carousel', WB_THEME_URL . '/js/owl.carousel.js', array('typed'));

the_post();

if (($popular_terms = wp_cache_get('_popular_terms')) === false) {
	$popular_terms = get_terms(array(
		'taxonomy' => array(
			'tool-category',
			'tool-tag',
			'course-category',
			'course-tag',
			'service-category',
			'service-tag'
		),
		'number' => 4,
		'meta_query' => array(
			array(
				'key' => '_views',
				'type' => 'NUMERIC'
			)
		),
		'orderby' => 'meta_value_num',
		'order' => 'DESC'
	));

	wp_cache_set('_popular_terms', $popular_terms, 'wb', DAY_IN_SECONDS);
}

$featured_tools = get_post_meta($post->ID, '_featured_tools', true);
$featured_tool_categories = get_post_meta($post->ID, '_featured_tool_categories', true);
$featured_tool_categories_sort = get_post_meta($post->ID, '_featured_tool_categories_sort', true);

if ($featured_tool_categories) {
	$featured_tool_categories = get_terms(array(
		'taxonomy' => 'tool-category',
		'include' => $featured_tool_categories
	));
}

//
$featured_tools_2 = get_post_meta($post->ID, '_featured_tools_2', true);
$featured_tool_categories_2 = get_post_meta($post->ID, '_featured_tool_categories_2', true);
$featured_tool_categories_sort_2 = get_post_meta($post->ID, '_featured_tool_categories_sort_2', true);

if ($featured_tool_categories_2) {
	$featured_tool_categories_2 = get_terms(array(
		'taxonomy' => 'tool-category',
		'include' => $featured_tool_categories_2
	));
}

$featured_tools_3 = get_post_meta($post->ID, '_featured_tools_3', true);
$featured_tool_categories_3 = get_post_meta($post->ID, '_featured_tool_categories_3', true);
$featured_tool_categories_sort_3 = get_post_meta($post->ID, '_featured_tool_categories_sort_3', true);

if ($featured_tool_categories_3) {
	$featured_tool_categories_3 = get_terms(array(
		'taxonomy' => 'tool-category',
		'include' => $featured_tool_categories_3
	));
}
//

$featured_courses = get_post_meta($post->ID, '_featured_courses', true);
$featured_course_categories = get_post_meta($post->ID, '_featured_course_categories', true);
$featured_course_categories_sort = get_post_meta($post->ID, '_featured_course_categories_sort', true);

if ($featured_course_categories) {
	$featured_course_categories = get_terms(array(
		'taxonomy' => 'course-category',
		'include' => $featured_course_categories
	));
}

$featured_services = get_post_meta($post->ID, '_featured_services', true);
$featured_service_categories = get_post_meta($post->ID, '_featured_service_categories', true);
$featured_service_categories_sort = get_post_meta($post->ID, '_featured_service_categories_sort', true);

if ($featured_service_categories) {
	$featured_service_categories = get_terms(array(
		'taxonomy' => 'service-category',
		'include' => $featured_service_categories
	));
}

if (isset($_GET['type']) && isset($_GET['category'])) {
	$term = get_term_by('term_taxonomy_id', (int) $_GET['category']);

	if (!$term) {
		echo 'error';

		exit;
	}

?>
	<div class="carousel-products owl-carousel" data-autoplay="false" data-nav-text="[&quot;&lt;i class='icon icon-arrow-right'&gt;&lt;/i&gt; &quot;,&quot;&lt;i class='ficon icon-arrow-right'&gt;&lt;/i&gt;&quot;]" data-nav="true" data-dots="false" data-loop="true" data-slidespeed="200" data-margin="54" data-responsive="{&quot;0&quot;:{ &quot;margin&quot; : 20, &quot;items&quot;: &quot;1&quot;}, &quot;600&quot;:{&quot;margin&quot; : 20, &quot;items&quot;: &quot;2&quot;}, &quot;850&quot;:{&quot;margin&quot; : 15 , &quot;items&quot;: &quot;3&quot;}, &quot;1200&quot;:{&quot;items&quot;: &quot;4&quot;}}">
		<?php

		if (isset($featured_tools[$term->term_id])) {
			$posts_args = array(
				'post_type' => esc_attr($_GET['type']),
				'posts_per_page' => 8,
				'post__in' => $featured_tools[$term->term_id]
			);

			if ($featured_tool_categories_sort == '1') {
				$posts_args['orderby'] = 'name';
				$posts_args['order'] = 'ASC';
			} else if ($featured_tool_categories_sort == '2') {
				$posts_args['meta_query'][] = array(
					'key' => '_views',
					'type' => 'NUMERIC'
				);

				$posts_args['orderby'] = 'meta_value_num';
				$posts_args['order'] = 'DESC';
			}
		} elseif (isset($featured_tools_2[$term->term_id])) {
			$posts_args = array(
				'post_type' => esc_attr($_GET['type']),
				'posts_per_page' => 8,
				'post__in' => $featured_tools_2[$term->term_id]
			);

			if ($featured_tool_categories_sort_2 == '1') {
				$posts_args['orderby'] = 'name';
				$posts_args['order'] = 'ASC';
			} else if ($featured_tool_categories_sort_2 == '2') {
				$posts_args['meta_query'][] = array(
					'key' => '_views',
					'type' => 'NUMERIC'
				);

				$posts_args['orderby'] = 'meta_value_num';
				$posts_args['order'] = 'DESC';
			}
		} elseif (isset($featured_tools_3[$term->term_id])) {
			$posts_args = array(
				'post_type' => esc_attr($_GET['type']),
				'posts_per_page' => 8,
				'post__in' => $featured_tools_3[$term->term_id]
			);

			if ($featured_tool_categories_sort_3 == '1') {
				$posts_args['orderby'] = 'name';
				$posts_args['order'] = 'ASC';
			} else if ($featured_tool_categories_sort_3 == '2') {
				$posts_args['meta_query'][] = array(
					'key' => '_views',
					'type' => 'NUMERIC'
				);

				$posts_args['orderby'] = 'meta_value_num';
				$posts_args['order'] = 'DESC';
			}
		} elseif (isset($featured_courses[$term->term_id])) {
			$posts_args = array(
				'post_type' => esc_attr($_GET['type']),
				'posts_per_page' => 8,
				'post__in' => $featured_courses[$term->term_id]
			);

			if ($featured_course_categories_sort == '1') {
				$posts_args['orderby'] = 'name';
				$posts_args['order'] = 'ASC';
			} else if ($featured_course_categories_sort == '2') {
				$posts_args['meta_query'][] = array(
					'key' => '_views',
					'type' => 'NUMERIC'
				);

				$posts_args['orderby'] = 'meta_value_num';
				$posts_args['order'] = 'DESC';
			}
		} elseif (isset($featured_services[$term->term_id])) {
			$posts_args = array(
				'post_type' => esc_attr($_GET['type']),
				'posts_per_page' => 8,
				'post__in' => $featured_services[$term->term_id]
			);

			if ($featured_service_categories_sort == '1') {
				$posts_args['orderby'] = 'name';
				$posts_args['order'] = 'ASC';
			} else if ($featured_service_categories_sort == '2') {
				$posts_args['meta_query'][] = array(
					'key' => '_views',
					'type' => 'NUMERIC'
				);

				$posts_args['orderby'] = 'meta_value_num';
				$posts_args['order'] = 'DESC';
			}
		}

		$posts = get_posts($posts_args);

		?>
		<?php foreach ($posts as $post) : ?>
			<div class="item">
				<div class="product-box">
					<?php if (has_post_thumbnail($post)) : ?>
						<div class="product-box__image">
							<a href="<?php echo get_permalink($post); ?>">
								<?php echo get_the_post_thumbnail($post, '480x360'); ?>
							</a>
						</div>
					<?php endif; ?>
					<div class="product-box__info">
						<h3 class="product-box__title">
							<a href="<?php echo get_permalink($post); ?>">
								<?php echo mb_strimwidth(get_the_title($post), 0, 50, '...'); ?>
							</a>
						</h3>
						<div class="product-box__text">
							<p><?php echo $post->post_excerpt ? mb_strimwidth($post->post_content, 0, 180, '...') : mb_strimwidth($post->post_content, 0, 180, '...'); ?></p>
						</div>
					</div>
					<?php $amount = get_post_meta($post->ID, '_amount', true); ?>
					<?php if ($amount != '') : ?>
						<div class="product-box__price">
							<?php if ($amount === '0') : ?>
								<?php _e('FREE', 'wb'); ?> <div class="price"></div>
							<?php elseif ($amount === 'Contact Vendor') : ?>
								<?php _e('Contact Vendor', 'wb'); ?> <div class="price"></div>
							<?php else : ?>
								<?php echo is_numeric($amount) ? __('Price from', 'wb') : ''; ?><div class="price"><?php echo get_post_meta($post->ID, '_currency', true); ?><?php echo $amount; ?></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php

	exit;
}

get_header();

?>
<!-- hero section  -->
<section class="overflow-hidden my-gradient-background relative">
	<div class="absolute bottom-0 left-0 text-white">
		<img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/bg-left-e1748528653547.png" alt="" />
	</div>
	<div class="absolute right-[0%] top-[-60px] text-white">
		<img class="h-[100%]" src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/bg-right.png"
			alt="" />
	</div>
	<div
		class=" z-50 flex-col lg:flex-row gap-5 lg:items-center lg:justify-between flex overflow-hidden  max-w-[1440px] mx-auto py-[120px] min-h-[calc(100vh-86px)] px-[20px] md:px-[30px]">
		<div class=" mb-7 z-50 xl:w-[60%]">
			<h1 class="text-white text-[30px] sm:text-[40px] leading-[33px] sm:leading-[45px] mb-1.5 font-bold">

				<?php _e('One Stop Shop For', 'wb'); ?> <?php _e(sprintf('All Your %sDigital', '<br>'), 'wb'); ?>
				<span class="text-[#FFCC00]"><?php _e('Marketing Needs', 'wb'); ?></span> Needs
			</h1>
			<h2 class="text-[18px] text-white sm:text-[22px] font-medium mb-4.75">
				<?php _e('Search for Digital Marketing', 'wb'); ?> |
				<span class="element" data-text1="Tools" data-text2="Courses" data-text3="Services" data-loop="true" data-backdelay="3000">
					<?php _e('Tools', 'wb'); ?>
				</span>
			</h2>
			<?php if ($search_page = wb_get_page_by_template('search')) : ?>
				<form action="<?php echo get_permalink($search_page); ?>" method="get">
					<div class="mb-6 w-full bg-[#FFFFFF1A] rounded-[8px] p-3 flex gap-2 justify-between items-center">
						<input class="bg-white text-[#797979] w-[75%] px-2 sm:px-5 py-3  rounded-sm"
							placeholder="<?php _e('e.g. SEO or Email Marketing', 'wb'); ?>" type="text" />
						<button type="submit"
							class="cursor-pointer bg-[#FFCC00] px-2 py-3 sm:px-5 flex gap-3 items-center rounded-sm text-[var(--primary)]">
							<?php _e('SEARCH', 'wb'); ?>
							<i class="fa-solid fa-magnifying-glass"></i>
						</button>
					</div>
				</form>
			<?php endif; ?>
			<div class="mb-6 grid sm:flex grid-cols-2 gap-6">
				<div class="flex gap-2 items-center">
					<input class="w-5 h-5 focus:ring-[var(--primary)]" name="radio" type="radio" id="tools" /><label
						for="tools"> <?php _e('Tools', 'wb'); ?></label>
				</div>
				<div class="flex gap-2 items-center">
					<input name="radio" class="w-5 h-5 focus:ring-[var(--primary)]" type="radio" id="services" /><label
						for="services"> <?php _e('services', 'wb'); ?></label>
				</div>
				<div class="flex gap-2 items-center">
					<input name="radio" class="w-5 h-5 focus:ring-[var(--primary)]" type="radio" id="content" /><label
						for="content"> <?php _e('content', 'wb'); ?></label>
				</div>
				<div class="flex gap-2 items-center">
					<input name="radio" class="w-5 h-5 focus:ring-[var(--primary)]" type="radio" id="courses" /><label
						for="courses"> <?php _e('Courses', 'wb'); ?></label>
				</div>
			</div>
			<div class="grid  md:flex grid-cols-2 gap-6">
				<?php if ($popular_terms) : ?>
					<?php foreach ($popular_terms as $popular_term) : ?>
						<a href="<?php echo get_term_link($popular_term); ?>"
							class="bg-white py-2.5 px-4 rounded-xl sm:rounded-3xl text-[13px] text-[var(--primary)] cursor-pointer">
							<?php echo $popular_term->name; ?>
						</a>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
		<div class="hidden z-50 xl:block">
			<img class="w-full max-w-[400px]"
				src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/740Vector_Flat-01-1-1.png" alt="" />
		</div>
	</div>
</section>

<!-- //service section  -->

<?php if ($promotion_boxes = get_post_meta($post->ID, '_promotion_boxes', true)) : ?>
	<section class="px-[5%] sm:px-[10%] pb-[10%]">
		<h1 class="text-center mt-10 sm:mt-20 mb-6 text-[40px]">
			Our <span class="text-[var(--primary)]">Services</span>
		</h1>
		<div class="grid sm:grid-cols-2 xl:grid-cols-4 justify-between gap-5">
			<?php foreach ($promotion_boxes as $promotion_box) : ?>
				<div class="bg-[#EAEFFF70] basis-[25%] text-white h-[300px] flex flex-col justify-between rounded-sm p-4">
					<div>
						<?php if ($image = $promotion_box['image']) : ?>

							<img src="<?php echo wb_image($image, 60, 55); ?>" alt="<?php echo $promotion_box['title']; ?>">

						<?php endif; ?>
						<?php if ($title = $promotion_box['title']) : ?>
							<h1 class="text-[#1B2134] mb-1 text-[19px] font-semibold"><?php echo $title; ?></h1>
						<?php endif; ?>

						<p class="text-[#737373] mb-1">
							<?php echo wpautop($promotion_box['description']); ?>
						</p>
					</div>

					<a href="<?php echo $promotion_box['button_url']; ?>" class="py-3 px-3 rounded-sm flex items-center gap-2 w-fit bg-[#0F44F31A] text-[var(--primary)]">
						Explore
						<i class="fa-solid fa-chevron-right"></i>
					</a>
				</div>



			<?php endforeach; ?>
		</div>

	</section>
<?php endif; ?>


<!-- Carousel Section white bg (for prices Post types) -->
<?php if ($featured_tool_categories) : ?>
<section class="bg-white px-[5%] py-10 sm:px-[10%] sm:py-20">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
        <div class="flex items-center gap-2">
            <span class="bg-[#FFCC00] text-[var(--primary)] py-1 px-2 rounded-sm text-[20px]">
                <?php echo array_sum(array_column($featured_tool_categories, 'count')); ?>
            </span>
            <h1 class="text-[23px] sm:text-[40px]">
                <?php _e('Digital Marketing Tools', 'wb'); ?>
            </h1>
        </div>
        <?php if ($tools_page = wb_get_page_by_template('tools')) : ?>
        <div class="flex justify-end">
            <a href="<?php echo get_permalink($tools_page); ?>" class="bg-[var(--primary)] h-fit text-white py-2 px-5 rounded-sm">
                <?php _e('View All', 'wb'); ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
    <div class="carousel-tabs tabsi">
        <ul class="carousel-tabs-nav tabs-nav mt-5 lg:flex grid grid-cols-2 items-center gap-5">
            <?php $i = 0; foreach ($featured_tool_categories as $featured_tool_category) : ?>
            <li <?php echo ($i == 0) ? 'class="current_tab"' : ''; ?>>
                <a href="<?php echo get_term_link($featured_tool_category); ?>" 
                   data-type="tool" 
                   data-category="<?php echo $featured_tool_category->term_id; ?>"
                   class="py-2.5 px-3 rounded-sm text-[14px] text-[#5A6478] border border-[#00000033] cursor-pointer">
                    <?php echo $featured_tool_category->name; ?>
                </a>
            </li>
            <?php $i++; endforeach; ?>
        </ul>
        <div class="carousel-tabs-content tabs-content">
            <?php $i = 0; foreach ($featured_tool_categories as $featured_tool_category) : ?>
            <div class="tabs-content-tab <?php echo ($i == 0) ? 'active_tab' : ''; ?>">
                <?php if ($i == 0) : ?>
                <div class="carousel-products owl-carousel mt-5 grid sm:grid-cols-2 xl:grid-cols-4 justify-between items-center gap-5" 
                     data-autoplay="false" 
                     data-nav-text="[&quot;&lt;i class='icon icon-arrow-right'&gt;&lt;/i&gt; &quot;,&quot;&lt;i class='ficon icon-arrow-right'&gt;&lt;/i&gt;&quot;]" 
                     data-nav="true" 
                     data-dots="false" 
                     data-loop="true" 
                     data-slidespeed="200" 
                     data-margin="54" 
                     data-responsive="{&quot;0&quot;:{ &quot;margin&quot; : 20, &quot;items&quot;: &quot;1&quot;}, &quot;600&quot;:{&quot;margin&quot; : 20, &quot;items&quot;: &quot;2&quot;}, &quot;850&quot;:{&quot;margin&quot; : 15 , &quot;items&quot;: &quot;3&quot;}, &quot;1200&quot;:{&quot;items&quot;: &quot;4&quot;}}">
                    <?php
                    if (!isset($featured_tools[$featured_tool_category->term_id]) || !$featured_tools[$featured_tool_category->term_id]) {
                        continue;
                    }

                    $tools_args = array(
                        'post_type' => 'tool',
                        'posts_per_page' => 8,
                        'post__in' => $featured_tools[$featured_tool_category->term_id]
                    );

                    if ($featured_tool_categories_sort == '1') {
                        $tools_args['orderby'] = 'name';
                        $tools_args['order'] = 'ASC';
                    } else if ($featured_tool_categories_sort == '2') {
                        $tools_args['meta_query'][] = array(
                            'key' => '_views',
                            'type' => 'NUMERIC'
                        );

                        $tools_args['orderby'] = 'meta_value_num';
                        $tools_args['order'] = 'DESC';
                    }

                    $tools = get_posts($tools_args);
                    ?>
                    <?php foreach ($tools as $tool) : ?>
                    <div class="item">
                        <div class="bg-white border border-[#00000033] shadow-xl rounded-sm">
                            <?php if (has_post_thumbnail($tool)) : ?>
                            <div class="p-4 flex flex-col items-center">
                                <a href="<?php echo get_permalink($tool); ?>">
                                    <?php echo get_the_post_thumbnail($tool, '480x360'); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            <div class="p-4 flex flex-col items-center">
                                <h1 class="text-[#1B1D1F] text-center text-[20px] font-semibold">
                                    <a href="<?php echo get_permalink($tool); ?>">
                                        <?php echo mb_strimwidth(get_the_title($tool), 0, 50, '...'); ?>
                                    </a>
                                </h1>
                                <p class="text-[#5A6478] text-center text-[14px] font-normal">
                                    <?php echo $tool->post_excerpt ? mb_strimwidth($tool->post_excerpt, 0, 180, '...') : mb_strimwidth($tool->post_content, 0, 180, '...'); ?>
                                </p>
                                <?php $amount = get_post_meta($tool->ID, '_amount', true); ?>
                                <?php if ($amount != '') : ?>
                                <h1 class="flex gap-2 items-center justify-center text-[#1B1D1F] text-[14px] text-center">
                                    <?php if ($amount === '0') : ?>
                                        <?php _e('FREE', 'wb'); ?>
                                    <?php elseif ($amount === 'Contact Vendor') : ?>
                                        <?php _e('Contact Vendor', 'wb'); ?>
                                    <?php else : ?>
                                        <?php echo is_numeric($amount) ? __('Price from', 'wb') : ''; ?>
                                        <span class="text-[#1B1D1F] text-center text-[20px] font-semibold">
                                            <?php echo get_post_meta($tool->ID, '_currency', true); ?><?php echo $amount; ?>
                                        </span>
                                    <?php endif; ?>
                                </h1>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo get_permalink($tool); ?>" class="block text-center py-3.5 rounded-b-sm bg-[var(--primary)] text-white">
                                <?php _e('Buy Now', 'wb'); ?>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else : ?>
                <div id="content-<?php echo $featured_tool_category->term_id; ?>"></div>
                <?php endif; ?>
            </div>
            <?php $i++; endforeach; ?>
        </div>
        <?php if ($tools_page) : ?>
        <div class="carousel-tabs__view-mob">
            <a href="<?php echo get_permalink($tools_page); ?>" class="btn btn-outline-blue">
                <?php _e('View All', 'wb'); ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- Carousel Section yellow bg (for Post types which dont have prices) -->
<section class="bg-[#FF92001A] px-[5%] py-10 sm:px-[10%] sm:py-20">
	<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
		<div class="flex items-center gap-2">
			<span class="bg-[#FFCC00] text-[var(--primary)] py-1 px-2 rounded-sm text-[20px]">965</span>
			<h1 class="text-[25px] sm:text-[40px]">
				Digital Marketing
				<span class="text-[var(--primary)]">Services</span>
			</h1>
		</div>
		<div class="flex justify-end">
			<button class="bg-[var(--primary)] h-fit text-white py-2 px-5 rounded-sm">
				View All
			</button>
		</div>
	</div>
	<div class="mt-5 lg:flex grid-cols-2 grid items-center gap-5">
		<button class="bg-white py-2.5 px-4 rounded-sm text-[14px] text-[#5A6478] cursor-pointer">
			Content Marketing
		</button>
		<button class="bg-white py-2.5 px-4 rounded-sm text-[12px] text-[#5A6478] cursor-pointer">
			Digital Marketing Services
		</button>
		<button class="bg-white py-2.5 px-4 rounded-sm text-[14px] text-[#5A6478] cursor-pointer">
			Email Marketing
		</button>
		<button class="bg-white py-2.5 px-4 rounded-sm text-[14px] text-[#5A6478] cursor-pointer">
			SEO Services
		</button>
		<button class="bg-white py-2.5 px-4 rounded-sm text-[14px] text-[#5A6478] cursor-pointer">
			Social Media
		</button>
		<button class="bg-white py-2.5 px-4 rounded-sm text-[14px] text-[#5A6478] cursor-pointer">
			Web Design
		</button>
	</div>
	<div class="mt-5 grid sm:grid-cols-2 xl:grid-cols-4 justify-between items-center gap-5">
		<div class="bg-white rounded-sm">
			<div class="p-4 flex flex-col items-center">
				<img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/Saly-1.png" alt="" />
				<h1 class="text-[#1B1D1F] text-center text-[20px] font-semibold">Broca</h1>
				<p class="text-[#5A6478] text-center text-[14px] font-normal">
					Broca - We help you tell your story better Broca uses AI to generate ad copy and
					content Whether ...
				</p>
				<h1 class="flex gap-2 items-center justify-center text-[#1B1D1F] text-[14px] text-center">
					Price from
					<span class="text-[#1B1D1F] text-center text-[20px] font-semibold">$49</span>
				</h1>
			</div>
		</div>
		<div class="bg-white rounded-sm">
			<div class="p-4 flex flex-col items-center">
				<img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/Saly-1.png" alt="" />
				<h1 class="text-[#1B1D1F] text-center text-[20px] font-semibold">Broca</h1>
				<p class="text-[#5A6478] text-center text-[14px] font-normal">
					Broca - We help you tell your story better Broca uses AI to generate ad copy and
					content Whether ...
				</p>
				<h1 class="flex gap-2 items-center justify-center text-[#1B1D1F] text-[14px] text-center">
					Price from
					<span class="text-[#1B1D1F] text-center text-[20px] font-semibold">$49</span>
				</h1>
			</div>
		</div>
		<div class="bg-white rounded-sm">
			<div class="p-4 flex flex-col items-center">
				<img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/Saly-1.png" alt="" />
				<h1 class="text-[#1B1D1F] text-center text-[20px] font-semibold">Broca</h1>
				<p class="text-[#5A6478] text-center text-[14px] font-normal">
					Broca - We help you tell your story better Broca uses AI to generate ad copy and
					content Whether ...
				</p>
				<h1 class="flex gap-2 items-center justify-center text-[#1B1D1F] text-[14px] text-center">
					Price from
					<span class="text-[#1B1D1F] text-center text-[20px] font-semibold">$49</span>
				</h1>
			</div>
		</div>
		<div class="bg-white rounded-sm">
			<div class="p-4 flex flex-col items-center">
				<img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/Saly-1.png" alt="" />
				<h1 class="text-[#1B1D1F] text-center text-[20px] font-semibold">Broca</h1>
				<p class="text-[#5A6478] text-center text-[14px] font-normal">
					Broca - We help you tell your story better Broca uses AI to generate ad copy and
					content Whether ...
				</p>
				<h1 class="flex gap-2 items-center justify-center text-[#1B1D1F] text-[14px] text-center">
					Price from
					<span class="text-[#1B1D1F] text-center text-[20px] font-semibold">$49</span>
				</h1>
			</div>
		</div>
	</div>
</section>
<!-- Carousel Section white bg (for Post types which dont have prices) -->
<section class="bg-white px-[5%] py-10 sm:px-[10%] sm:py-20">
	<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
		<div class="flex items-center gap-2">
			<span class="bg-[#FFCC00] text-[var(--primary)] py-1 px-2 rounded-sm text-[20px]">965</span>
			<h1 class="text-[25px] sm:text-[40px]">
				Digital Marketing
				<span class="text-[var(--primary)]">Services</span>
			</h1>
		</div>
		<div class="flex justify-end">
			<button class="bg-[var(--primary)] h-fit text-white py-2 px-5 rounded-sm">
				View All
			</button>
		</div>
	</div>
	<div class="mt-5 lg:flex grid-cols-2 grid items-center gap-5">
		<button class="bg-white py-2.5 px-4 rounded-sm text-[14px] text-[#5A6478] cursor-pointer">
			Content Marketing
		</button>
		<button class="bg-white py-2.5 px-4 rounded-sm text-[12px] text-[#5A6478] cursor-pointer">
			Digital Marketing Services
		</button>
		<button class="bg-white py-2.5 px-4 rounded-sm text-[14px] text-[#5A6478] cursor-pointer">
			Email Marketing
		</button>
		<button class="bg-white py-2.5 px-4 rounded-sm text-[14px] text-[#5A6478] cursor-pointer">
			SEO Services
		</button>
		<button class="bg-white py-2.5 px-4 rounded-sm text-[14px] text-[#5A6478] cursor-pointer">
			Social Media
		</button>
		<button class="bg-white py-2.5 px-4 rounded-sm text-[14px] text-[#5A6478] cursor-pointer">
			Web Design
		</button>
	</div>
	<div class="mt-5 grid sm:grid-cols-2 xl:grid-cols-4 justify-between items-center gap-5">
		<div class="bg-white rounded-sm">
			<div class="p-4 flex flex-col items-center">
				<img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/Saly-1.png" alt="" />
				<h1 class="text-[#1B1D1F] text-center text-[20px] font-semibold">Broca</h1>
				<p class="text-[#5A6478] text-center text-[14px] font-normal">
					Broca - We help you tell your story better Broca uses AI to generate ad copy and
					content Whether ...
				</p>
				<h1 class="flex gap-2 items-center justify-center text-[#1B1D1F] text-[14px] text-center">
					Price from
					<span class="text-[#1B1D1F] text-center text-[20px] font-semibold">$49</span>
				</h1>
			</div>
		</div>
		<div class="bg-white rounded-sm">
			<div class="p-4 flex flex-col items-center">
				<img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/Saly-1.png" alt="" />
				<h1 class="text-[#1B1D1F] text-center text-[20px] font-semibold">Broca</h1>
				<p class="text-[#5A6478] text-center text-[14px] font-normal">
					Broca - We help you tell your story better Broca uses AI to generate ad copy and
					content Whether ...
				</p>
				<h1 class="flex gap-2 items-center justify-center text-[#1B1D1F] text-[14px] text-center">
					Price from
					<span class="text-[#1B1D1F] text-center text-[20px] font-semibold">$49</span>
				</h1>
			</div>
		</div>
		<div class="bg-white rounded-sm">
			<div class="p-4 flex flex-col items-center">
				<img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/Saly-1.png" alt="" />
				<h1 class="text-[#1B1D1F] text-center text-[20px] font-semibold">Broca</h1>
				<p class="text-[#5A6478] text-center text-[14px] font-normal">
					Broca - We help you tell your story better Broca uses AI to generate ad copy and
					content Whether ...
				</p>
				<h1 class="flex gap-2 items-center justify-center text-[#1B1D1F] text-[14px] text-center">
					Price from
					<span class="text-[#1B1D1F] text-center text-[20px] font-semibold">$49</span>
				</h1>
			</div>
		</div>
		<div class="bg-white rounded-sm">
			<div class="p-4 flex flex-col items-center">
				<img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/Saly-1.png" alt="" />
				<h1 class="text-[#1B1D1F] text-center text-[20px] font-semibold">Broca</h1>
				<p class="text-[#5A6478] text-center text-[14px] font-normal">
					Broca - We help you tell your story better Broca uses AI to generate ad copy and
					content Whether ...
				</p>
				<h1 class="flex gap-2 items-center justify-center text-[#1B1D1F] text-[14px] text-center">
					Price from
					<span class="text-[#1B1D1F] text-center text-[20px] font-semibold">$49</span>
				</h1>
			</div>
		</div>
	</div>
</section>
<!-- How it Work Section -->
<section class="sm:px-[10%] px-[5%] pt-[7%] pb-[5%]">
	<h1 class="text-center mb-6 text-[40px]">
		How it <span class="text-[var(--primary)]">Works</span>
	</h1>
	<div class="grid sm:grid-cols-2 lg:grid-cols-3 justify-between gap-5">
		<div class="border border-[#0000000F] text-white flex flex-col justify-between rounded-sm p-4">
			<div>
				<img class="mb-4 p-2.5 rounded-sm bg-[#0F44F333]"
					src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/Layer_1.png"
					alt="Digital Marketing" />
				<h1 class="text-[var(--primary)] mb-4 text-[18px] font-bold">
					Discover The Best Tools and Courses
				</h1>
				<p class="text-[#6D6D6D] text-[14px] mb-1">
					Browse through our growing database of 7000+ Digital Marketing Tools, 4000+ Digital
					Marketing Service providers and over 950 Digital Marketing Courses collected in a wide
					range of categories.
				</p>
			</div>
		</div>
		<div class="border border-[#0000000F] text-white flex flex-col justify-between rounded-sm p-4">
			<div>
				<img class="mb-4 p-2.5 rounded-sm bg-[#0F44F333]"
					src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/Layer_1.png"
					alt="Digital Marketing" />
				<h1 class="text-[var(--primary)] mb-4 text-[20px] font-bold">Compare Your Options</h1>
				<p class="text-[#6D6D6D] text-[14px] mb-1">
					Browse through our growing database of 7000+ Digital Marketing Tools, 4000+ Digital
					Marketing Service providers and over 950 Digital Marketing Courses collected in a wide
					range of categories.
				</p>
			</div>
		</div>
		<div class="border border-[#0000000F] text-white flex flex-col justify-between rounded-sm p-4">
			<div>
				<img class="mb-4 p-2.5 rounded-sm bg-[#0F44F333]"
					src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/Layer_1.png"
					alt="Digital Marketing" />
				<h1 class="text-[var(--primary)] mb-4 text-[20px] font-bold">Grow Your Business</h1>
				<p class="text-[#6D6D6D] text-[14px] mb-1">
					Browse through our growing database of 7000+ Digital Marketing Tools, 4000+ Digital
					Marketing Service providers and over 950 Digital Marketing Courses collected in a wide
					range of categories.
				</p>
			</div>
		</div>
	</div>
</section>
<!-- Newsletter -->
<section class="px-[5%] sm:px-[10%] pb-[5%]">
	<div class="my-gradient-background text-white rounded-sm py-7">
		<div class="p-2 w-[100%] flex flex-col justify-between items-center">
			<h1 class="text-center text-[25px] sm:text-[40px] leading-[45px] mb-1.5 font-bold">
				Subscribe to Our <span class="text-[#FFCC00]">Newsletter</span>
			</h1>
			<h2 class="text-center text-[18px] sm:text-[22px] font-medium mb-4.75">
				Stay up to date with the latest marketing tools and tips.
			</h2>
			<div
				class="sm:w-[70%] w-full mb-6 bg-[#FFFFFF1A] rounded-[8px] flex flex-col  md:flex-row gap-3 justify-between p-6">
				<input class="bg-white text-[#797979] px-5 py-4 rounded-sm basis-[75%]"
					placeholder="e.g. SEO or Email Marketing" type="text" />
				<button
					class="cursor-pointer text-center text-[18px] font-semibold py-2 bg-[#FFCC00] px-5 gap-3 rounded-sm text-[var(--primary)]">
					Subscribe
				</button>
			</div>
		</div>
	</div>
</section>