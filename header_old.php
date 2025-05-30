<?php if (!defined('ABSPATH')) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">





	<title><?php wp_title('-', true, 'right'); ?></title>
	<?php wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap'); ?>
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
		<header class="header">
			<div class="container">
				<div class="header__inner">
					<div class="header__left">
						<?php do_action('wbcdlaf_logo'); ?>
						<button class="navbar-toggler">
							<span></span>
							<span></span>
						</button>
					</div>
					<nav class="header__right">
						<?php wp_nav_menu('theme_location=main&container=&menu_class=main-menu-list&fallback_cb=wb_nav_main_menu_fallback'); ?>
						<?php if ($compare_page = wb_get_page_by_template('compare')) : ?>
							<a href="<?php echo get_permalink($compare_page->ID); ?>" class="btn btn-outline-white header__btn">
								<?php _e('Compare', 'wb'); ?> <span class="badge-num"><?php echo count(Compare::get_ids()); ?></span>
							</a>
						<?php endif; ?>
					</nav>
				</div>
			</div>
		</header>
		<?php if ($compare_page) : ?>
			<a href="<?php echo get_permalink($compare_page->ID); ?>" class="compare-fixed">
				<div class="compare-fixed__text"><?php _e('COMPARE', 'wb'); ?></div>
				<span class="badge-num"><?php echo count(Compare::get_ids()); ?></span>
			</a>
		<?php endif; ?>