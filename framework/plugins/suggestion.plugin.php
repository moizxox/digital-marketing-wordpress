<?php

class Suggestion extends WB_Plugin {

	public function action_current_screen($current_screen) {
		if ($current_screen->base !== 'edit') {
			return;
		}

		?>
		<script type="text/javascript">
			window.onload = function () {
				jQuery(function ($) {
					if ($('#post-search-input').length) {
						$('#post-search-input')
							.prop('autocomplete', 'off')
							.autocomplete({
								source: '<?php echo admin_url('admin-ajax.php'); ?>?action=suggest&type=<?php echo $current_screen->post_type; ?>',
								select: function (event, ui) {
									location.href = ui.item.value.replace(/&amp;/g, '&');
								}
							});
					}
				});
			};
		</script>
		<?php
	}

	public function filter_query_vars($vars) {
		$vars[] = 'suggest';

		return $vars;
	}

	public function action_rewrite_rules_array($rules) {
		$new_rules = array();

		$search_page = wb_get_page_by_template('search');

		if (!$search_page) {
			return $rules;
		}

		$new_rules[$search_page->post_name . '/suggest/?$']  = 'index.php?pagename=' . $search_page->post_name .'&suggest=1';

		return $new_rules + $rules;
	}

	public function action_template_redirect() {
		$suggest = get_query_var('suggest');

		if ($suggest) {
			$type = (isset($_GET['type']) && in_array($_GET['type'], array('tool', 'course', 'service', 'ai-tool', 'ai-agent'))) ? $_GET['type'] : 'tool';
			$query = isset($_GET['query']) ? esc_attr($_GET['query']) : '';

			$suggestions = array();

			$categories = get_terms(array(
				'taxonomy' => $type . '-category',
				'number' => 10,
				'search' => $query,
				'meta_query' => array(
					array(
						'key' => '_views',
						'type' => 'NUMERIC',
					)
				),
				'orderby' => 'meta_value_num',
				'order' => 'ASC'
			));

			if ($categories) {
				foreach ($categories as $category) {
					$suggestions[] = array(
						'value' => $category->name,
						'data' => get_term_link($category)
					);
				}
			}

			if (count($suggestions) != 10) {
				$tags = get_terms(array(
					'taxonomy' => $type . '-tag',
					'number' => 10,
					'search' => $query,
					'meta_query' => array(
						array(
							'key' => '_views',
							'type' => 'NUMERIC',
						)
					),
					'orderby' => 'meta_value_num',
					'order' => 'ASC'
				));

				if ($tags) {
					foreach ($tags as $tag) {
						$suggestions[] = array(
							'value' => $tag->name,
							'data' => get_term_link($tag)
						);
					}
				}
			}

			if (count($suggestions) != 10) {
				$items = get_posts(array(
					'post_type' => $type,
					'posts_per_page' => 10,
					's' => $query,
					'meta_key' => '_views',
					'orderby' => 'meta_value_num',
					'order' => 'DESC'
				));

				if ($items) {
					foreach ($items as $item) {
						$suggestions[] = array(
							'value' => $item->post_title,
							'data' => get_permalink($item)
						);
					}
				}
			}

			wp_send_json(array(
				'suggestions' => $suggestions
			));
		}
	}

	public function action_wp_ajax_suggest() {
		$type = (isset($_GET['type']) && in_array($_GET['type'], array('tool', 'course', 'service', 'ai-tool', 'ai-agent'))) ? $_GET['type'] : 'tool';
		$term = isset($_GET['term']) ? esc_attr($_GET['term']) : '';

		$suggestions = array();

		if ($term) {
			$items = get_posts(array(
				'post_type' => $type,
				'post_status' => 'any',
				'posts_per_page' => 25,
				's' => $term,
				'order' => 'DESC'
			));

			if ($items) {
				foreach ($items as $item) {
					$suggestions[] = array(
						'label' => $item->post_title,
						'value' => get_edit_post_link($item)
					);
				}
			}
		}

		wp_send_json($suggestions);

		exit;
	}

}
