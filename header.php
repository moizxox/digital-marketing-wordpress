<?php if (!defined('ABSPATH')) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		

		
		
		<title><?php wp_title('-', true, 'right'); ?></title>
		<?php wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap'); ?>
		 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
		<?php wp_enqueue_style('font-awesome', WB_THEME_URL . '/css/font-awesome.css'); ?>
		<?php wp_enqueue_style('style', get_stylesheet_uri()); ?>
		<?php wp_enqueue_style('responsive', WB_THEME_URL . '/css/responsive.css'); ?>
		<?php wp_enqueue_script('cookie', WB_THEME_URL . '/js/cookie.js', array('jquery')); ?>
		<?php wp_enqueue_script('jquery.formstyler', WB_THEME_URL . '/js/jquery.formstyler.js', array('cookie')); ?>
		<?php wp_enqueue_script('main', WB_THEME_URL . '/js/main.js', array('jquery.formstyler')); ?>
		<?php wp_head(); ?>
	</head>
	<body class="<?php echo isset($GLOBALS['no_hero']) ? 'page_no-hero' : 'main-page'; ?>">
		<?php if (function_exists('gtm4wp_the_gtm_tag')) gtm4wp_the_gtm_tag(); ?>
		<div class="main-wrap">
			<div class="site__layer"></div>
		<header class="flex h-[86px] items-center justify-between px-[10%] bg-[#0C2452] py-4 relative">
  <!-- Logo -->
  <a href="<?php echo esc_url(home_url('/')); ?>">
    <img src="https://digitalmarketingsupermarket.com/wp-content/uploads/2025/05/logo.png" alt="Logo" class="h-10" />
  </a>

  <!-- Desktop Navigation -->
  <nav class="hidden lg:block">
    <?php
      wp_nav_menu(array(
        'theme_location' => 'main',
        'container' => false,
        'menu_class' => 'flex gap-6 text-white',
        'fallback_cb' => false
      ));
    ?>
  </nav>

  <!-- Mobile Nav -->
  <div class="hidden flex-col justify-between h-[calc(100vh-86px)] bg-[#0C2452] absolute top-full left-0 w-full p-6 text-white lg:hidden z-70" id="mob-nav">
    <nav>
      <?php
        wp_nav_menu(array(
          'theme_location' => 'main',
          'container' => false,
          'menu_class' => 'flex flex-col gap-4',
          'fallback_cb' => false
        ));
      ?>
    </nav>
    <?php if ($compare_page = wb_get_page_by_template('compare')) : ?>
      <a href="<?php echo get_permalink($compare_page->ID); ?>" class="rounded-sm bg-[#FFCC00] w-fit border p-3 flex gap-3 items-center">
        <span class="text-[#0C2452] md:block"><?php _e('Compare', 'wb'); ?></span>
        <span class="rounded-sm bg-[#0C2452] px-2 py-0.5 text-white text-sm"><?php echo count(Compare::get_ids()); ?></span>
      </a>
    <?php endif; ?>
  </div>

  <!-- Desktop Compare Button -->
  <?php if ($compare_page) : ?>
    <a href="<?php echo get_permalink($compare_page->ID); ?>" class="hidden lg:flex rounded-sm bg-[#FFCC00] p-3 gap-3 items-center ml-4">
      <span class="text-[#0C2452] hidden md:block"><?php _e('Compare', 'wb'); ?></span>
      <span class="rounded-sm bg-[#0C2452] px-2 py-0.5 text-white text-sm"><?php echo count(Compare::get_ids()); ?></span>
    </a>
  <?php endif; ?>

  <!-- Toggle Button for Mobile -->
  <div class="lg:hidden text-white text-2xl ml-4 cursor-pointer" id="toggle-btn">
    <i class="fa-solid fa-bars"></i>
  </div>
</header>


			<?php if ($compare_page) : ?>
				<a href="<?php echo get_permalink($compare_page->ID); ?>" class="compare-fixed">
					<div class="compare-fixed__text"><?php _e('COMPARE', 'wb'); ?></div>
					<span class="badge-num"><?php echo count(Compare::get_ids()); ?></span>
				</a>
			<?php endif; ?>
