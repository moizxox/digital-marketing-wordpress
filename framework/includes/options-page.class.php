<?php

final class WB_Options_Page {

	private $pages = array();

	private static $layout = '
		<div id="wb-framework">
			<div class="wb-header">
				<div class="wb-logo">
					WOOBRO <i>2.1</i>
					<span>support - <a href="https://www.woobro.com" target="_blank" title="WOOBRO">woobro.com</a></span>
				</div>
			</div>
			%message%
			<form method="post">
				<div class="wb-topbar">
					<a href="#" class="wb-collapse">
						<i class="fa fa-exchange"></i>
					</a>
					<div class="wb-cta-top">
						<input type="submit" name="submit" value="Save Changes" class="wb-save" />
						<a href="admin.php?page=%page%&restore=1" class="wb-reset-section">Restore Default Settings</a>
					</div>
					<div class="clear"></div>
				</div>
				<div class="wb-settings">
					<div class="wb-menu-bar">
						<ul>
							%menu%
						</ul>
					</div>
					<div class="wb-content">
						<h2>%title%</h2>
						%content%
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
			</form>
		</div>
	';

	private static $instance;

	public function __construct() {
		add_action('admin_print_styles', array($this, 'print_styles'));
		add_action('admin_print_scripts', array($this, 'print_scripts'));

		add_action('wp_ajax_get-permalinks', array($this, 'get_permalinks'));

		add_action('admin_menu', array($this, 'load_pages'));
	}

	public function add_page(array $page = array()) {
		$this->pages[$page['priority']] = $page;
	}

	public function print_styles() {
		wp_enqueue_style('wb', WB_FRAMEWORK_URL . '/assets/css/wb.css', array('thickbox'));
	}

	public function print_scripts() {
		wp_enqueue_script('wb-jquery-cookie', WB_FRAMEWORK_URL . '/assets/js/jquery.cookie.js', array('jquery'));

		wp_enqueue_script('wb', WB_FRAMEWORK_URL . '/assets/js/wb.js', array(
			'wb-jquery-cookie',
			'jquery-ui-autocomplete',
			'jquery-ui-sortable',
			'jquery-color',
			'thickbox',
			'media-upload'
		), '1.0', true);

		wp_localize_script('wb', 'wbL10n', array(
			'delete_confirmation' => __('Are you sure you want to delete this?', 'wb'),
			'no_items' => __('No items found.', 'wb')
		));


		if (function_exists('wp_enqueue_media')) {
			wp_enqueue_media();
		}
	}

	public function get_permalinks() {
		if (!current_user_can('edit_theme_options')) {
			return;
		}

		$term = isset($_REQUEST['term']) ? esc_attr($_REQUEST['term']) : '';

		$data = array();

		if (trim($term) == '') {
			return $suggestions;
		}

		$query = new WP_Query('post_type=any&posts_per_page=-1&post_status=publish&s=' . $term);

		foreach ($query->posts as $post) {
			switch ($post->post_type) {
				case 'revision':
				case 'nav_menu_item':
					break;
				case 'page':
					$permalink = get_page_link($post->ID);
					break;
				case 'post':
					$permalink = get_permalink($post->ID);
					break;
				case 'attachment':
					$permalink = get_attachment_link($post->ID);
					break;
				default:
					$permalink = get_post_permalink($post->ID);
					break;
			}

			$post_type_object = get_post_type_object($post->post_type);

			$data[] = array(
				'label' => $post->post_title,
				'value' => $permalink,
				'category' => $post_type_object->labels->name
			);
		}

		echo json_encode($data);

		exit;
	}

	public function load_pages() {
		if (!isset($this->pages[1])) {
			return;
		}

		ksort($this->pages);

		$menu_items = array();

		$page_title = '';

		foreach ($this->pages as $page) {
			if (isset($_GET['page']) && $_GET['page'] == $page['name']) {
				$page_title = $page['title'];
			}

			if (strpos($page['title'], ' - ') !== false) {
				$page_title_exploded = explode(' - ', $page['title']);

				$menu_items[$page_title_exploded[0]][] = array(
					'icon' => $page['icon'],
					'title' => __($page['title'], 'wb'),
					'name' => $page['name'],
					'content' => $page['content']
				);
			} else {
				$menu_items[$page['title']][] = array(
					'icon' => $page['icon'],
					'title' => __($page['title'], 'wb'),
					'name' => $page['name'],
					'content' => $page['content']
				);
			}
		}

		if ($menu_items) {
			add_menu_page('WOOBRO', 'WOOBRO', 'edit_theme_options', 'wb', function () {}, WB_FRAMEWORK_URL . '/assets/img/menu_icon.png');

			$menu = '';

			foreach ($menu_items as $menu_item) {
				if (count($menu_item) === 1) {
					add_submenu_page('wb', $menu_item[0]['title'], $menu_item[0]['title'], 'edit_theme_options', $menu_item[0]['name'], $menu_item[0]['content']);

					$menu .= '
						<li ' . (($page_title === $menu_item[0]['title']) ? 'class="active"' : '') . '>
							<a href="' . add_query_arg('page', $menu_item[0]['name'], admin_url('admin.php')) . '">
								<i class="fa fa-' . $menu_item[0]['icon'] . '"></i> ' .  $menu_item[0]['title'] . '
							</a>
						</li>
					';
				} else {
					foreach ($menu_item as $key => $_menu_item) {
						$page_title_exploded = explode(' - ', $_menu_item['title']);

						if ($key === 0) {
							add_submenu_page('wb', $_menu_item['title'], __($page_title_exploded[0], 'wb'), 'edit_theme_options', $_menu_item['name'], $_menu_item['content']);

							$menu .= '
								<li ' . ((strpos($page_title, $page_title_exploded[0] . ' - ') !== false) ? 'class="active"' : '') . '>
									<a href="' . add_query_arg('page', $_menu_item['name'], admin_url('admin.php')) . '">
										<i class="fa fa-'  . $_menu_item['icon'] . '"></i> ' . __($page_title_exploded[0], 'wb') . '
									</a>
									<ul ' . ((strpos($page_title, $page_title_exploded[0] . ' - ') !== false) ? '' : 'style="display : none;"') . '>
										<li ' . (($page_title === $_menu_item['title']) ? 'class="active"' : '') . '>
											<a href="' . add_query_arg('page', $_menu_item['name'], admin_url('admin.php')) . '">
												' . __($page_title_exploded[1], 'wb') . '
											</a>
										</li>
							';
						} else {
							add_submenu_page($menu_item[0]['name'], $_menu_item['title'], $_menu_item['title'], 'edit_theme_options', $_menu_item['name'], $_menu_item['content']);
							
							if ($GLOBALS['plugin_page'] === sanitize_title($_menu_item['title'])) {
								global $active_menu_item;

								$active_menu_item = $menu_item[0]['name'];

								add_action('parent_file', create_function('', 'global $active_menu_item, $plugin_page; $plugin_page = $active_menu_item;'));
							}

							$menu .= '
								<li ' . (($page_title === $_menu_item['title']) ? 'class="active"' : '') . '>
									<a href="' . add_query_arg('page', $_menu_item['name'], admin_url('admin.php')) . '">
										' . __($page_title_exploded[1], 'wb') . '
									</a>
								</li>
							';

							if ($_menu_item === end($menu_item)) {
								$menu .= '
										</ul>
									</li>
								';
							}
						}
					}
				}
			}

			self::$layout = str_replace(array('%title%', '%menu%'), array($page_title, $menu), self::$layout);
			
			unset($GLOBALS['submenu']['wb'][0]);
		}
	}

	public static function render($template, array $params = array(), $layout = true) {
		ob_start();

		extract($params);

		include $template;

		$content = ob_get_contents();

		ob_end_clean();

		if ($layout) {
			$message = '';

			if (isset($success)) {
				$message = '<div class="wb-message wb-successful">' . $success . '</div>';
			}

			if (isset($error)) {
				$message = '<div class="wb-message wb-error"><p>' . $error . '</p></div>';
			}

			$content = str_replace(array('%page%', '%message%', '%content%'), array($_GET['page'], $message, $content), self::$layout);
		}

		return $content;
	}

	public static function instance() {
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}
