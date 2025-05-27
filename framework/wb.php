<?php

if (!defined('ABSPATH')) {
	exit;
}

define('WB_THEME_DIR', get_template_directory());
define('WB_THEME_URL', get_template_directory_uri());

define('WB_FRAMEWORK_DIR', WB_THEME_DIR . '/framework');
define('WB_FRAMEWORK_URL', WB_THEME_URL . '/framework');

include WB_FRAMEWORK_DIR . '/includes/helper.functions.php';
include WB_FRAMEWORK_DIR . '/includes/theme.functions.php';

include WB_FRAMEWORK_DIR . '/includes/plugin-manager.class.php';
include WB_FRAMEWORK_DIR . '/includes/plugin.class.php';
include WB_FRAMEWORK_DIR . '/includes/options-page.class.php';

$plugin_manager = new WB_Plugin_Manager();
$plugin_manager->load_plugins();

if (is_dir($languages_dir = WB_FRAMEWORK_DIR . '/languages')) {
	load_theme_textdomain('wb', $languages_dir);
}
