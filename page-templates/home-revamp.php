<?php

/**
 * Template Name: Homepage Revamp
 */

if (!defined('ABSPATH')) {
    exit;
}

// Enqueue necessary scripts
wp_enqueue_script('typed', WB_THEME_URL . '/js/typed.js', array('main'));
wp_enqueue_script('owl.carousel', WB_THEME_URL . '/js/owl.carousel.js', array('typed'));

the_post();

// Get popular terms for the search section
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

get_header();
?>

<!-- Hero Section -->
<section>
    <div class="absolute top-[94%] sm:top-[78.2%] text-white">
        <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/bg-left-e1748528653547.png" alt="" />
    </div>
    <div class="absolute h-[calc(100vh-100px)] right-[0%] top-[90px] text-white">
        <img class="h-[100%]" src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/bg-right.png" alt="" />
    </div>
    <div class="xl:h-[calc(100vh-86px)] pt-8 z-50 flex-col lg:flex-row my-gradient-background px-[5%] sm:px-[10%] gap-5 lg:items-center lg:justify-between flex overflow-hidden">
        <div class="text-white mb-7 z-50 xl:w-[60%]">
            <h1 class="text-[30px] sm:text-[40px] leading-[33px] sm:leading-[45px] mb-1.5 font-bold">
                <?php _e('One Stop Shop For All Your', 'wb'); ?>
                <span class="text-[#FFCC00]"><?php _e('Digital Marketing', 'wb'); ?></span>
                <?php _e('Needs', 'wb'); ?>
            </h1>
            <h2 class="text-[18px] sm:text-[22px] font-medium mb-4.75">
                <?php _e('Search for Digital Marketing', 'wb'); ?>
                <span class="element" data-text1="Tools" data-text2="Courses" data-text3="Services" data-loop="true" data-backdelay="3000">
                    <?php _e('Tools', 'wb'); ?>
                </span>
            </h2>
            
            <?php if ($search_page = wb_get_page_by_template('search')) : ?>
                <form action="<?php echo get_permalink($search_page); ?>" method="get">
                    <div class="mb-6 w-full bg-[#FFFFFF1A] rounded-[8px] p-3 flex gap-2 justify-between items-center">
                        <input 
                            type="text" 
                            name="query" 
                            class="bg-white text-[#797979] w-[75%] px-2 sm:px-5 py-3 rounded-sm" 
                            placeholder="<?php _e('e.g. SEO or Email Marketing', 'wb'); ?>"
                        />
                        <button type="submit" class="cursor-pointer bg-[#FFCC00] px-2 py-3 sm:px-5 flex gap-3 items-center rounded-sm text-[var(--primary)]">
                            <?php _e('Search', 'wb'); ?> <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                    
                    <div class="mb-6 grid sm:flex grid-cols-2 gap-6">
                        <div class="flex gap-2 items-center">
                            <input class="w-5 h-5 focus:ring-[var(--primary)]" name="type" type="radio" id="tools" value="tool" checked />
                            <label for="tools"><?php _e('Tools', 'wb'); ?></label>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input name="type" class="w-5 h-5 focus:ring-[var(--primary)]" type="radio" id="services" value="service" />
                            <label for="services"><?php _e('Services', 'wb'); ?></label>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input name="type" class="w-5 h-5 focus:ring-[var(--primary)]" type="radio" id="content" value="content" />
                            <label for="content"><?php _e('Content', 'wb'); ?></label>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input name="type" class="w-5 h-5 focus:ring-[var(--primary)]" type="radio" id="courses" value="course" />
                            <label for="courses"><?php _e('Courses', 'wb'); ?></label>
                        </div>
                    </div>
                </form>
            <?php endif; ?>

            <?php if ($popular_terms) : ?>
                <div class="grid md:flex grid-cols-2 gap-6">
                    <?php foreach ($popular_terms as $popular_term) : ?>
                        <a href="<?php echo get_term_link($popular_term); ?>" class="bg-white py-2.5 px-4 rounded-xl sm:rounded-3xl text-[13px] text-[var(--primary)] cursor-pointer">
                            <?php echo $popular_term->name; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="hidden z-50 xl:block">
            <img class="w-full max-w-[400px]" src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/740Vector_Flat-01-1-1.png" alt="" />
        </div>
    </div>
</section>

<?php get_footer(); ?>