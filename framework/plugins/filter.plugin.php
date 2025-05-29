<?php

class Filter extends WB_Plugin {

	private $post_type;

	public function action_pre_get_posts($query) {
		if (is_admin() && (!$query->is_main_query() || !isset($query->query['search_page']))) {
			return $query;
		}

		if ($query->is_tax(array('tool-category', 'tool-tag', 'course-category', 'course-tag', 'service-category', 'service-tag', 'ai-tool-category', 'ai-tool-tag', 'ai-agent-category', 'ai-agent-tag')) || isset($query->query['search_page'])) {
			$type = (isset($_GET['type']) && in_array($_GET['type'], array('tool', 'course', 'service', 'ai-tool', 'ai-agent'))) ? $_GET['type'] : 'tool';

			$_query = isset($_GET['query']) ? esc_attr($_GET['query']) : '';

			if ($_query) {
				$query->set('s', $_query);
			}

			$per_page = (isset($_GET['per_page']) && in_array($_GET['per_page'], array('12', '24', '48', '96'))) ? $_GET['per_page'] : '12';

			$query->set('posts_per_page', $per_page);

			$sort = (isset($_GET['sort']) && in_array($_GET['sort'], array('alphabetically', 'popularity', 'price-hl', 'price-lh'))) ? $_GET['sort'] : 'alphabetically';

			$query->set('orderby', 'name');
			$query->set('order', 'ASC');

			if ($sort == 'popularity') {
				$query->set('meta_key',  '_views');
				$query->set('orderby', 'meta_value_num');
				$query->set('order', 'DESC');
			}

			if (in_array($sort, array('price-hl', 'price-lh'))) {
				$query->set('meta_key',  '_amount');
				$query->set('orderby', 'meta_value_num');

				if ($sort == 'price-lh') {
					$query->set('order', 'ASC');
				} else {
					$query->set('order', 'DESC');
				}
			}

			$pricing_option = isset($_GET['pricing_option']) ? (array) $_GET['pricing_option'] : array();

			$tax_query = array();

			if ($pricing_option) {
				$tax_query[] = array(
					'taxonomy' => $type . '-pricing-option',
					'terms' => array_map('intval', $pricing_option)
				);
			}

			$price = isset($_GET['price']) ? (array) $_GET['price'] : array();

			$meta_query = array();

			if ($price) {
				foreach ($price as $amount) {
					$amount = explode('-', $amount);

					if (isset($amount[1])) {
						$meta_query[] = array(
							'key' => '_amount',
							'value' => array(absint($amount[0]), absint($amount[1])),
							'type' => 'NUMERIC',
							'compare' => 'BETWEEN'
						);
					} else {
						if ($amount[0] == '0') {
							$meta_query[] = array(
								'key' => '_amount',
								'value' => '0',
								'type' => 'NUMERIC'
							);
						} else {
							$meta_query[] = array(
								'key' => '_amount',
								'value' => absint($amount[0]),
								'type' => 'NUMERIC',
								'compare' => '>'
							);
						}
					}
				}
			}

			$currency = isset($_GET['currency']) ? esc_attr($_GET['currency']) : '';

			if ($currency) {
				$meta_query[] = array(
					'key' => '_currency',
					'value' => $currency,
					'type' => 'CHAR'
				);
			}

			$country = isset($_GET['country']) ? (array) $_GET['country'] : array();

			if ($country) {
				$tax_query[] = array(
					'taxonomy' => $type . '-location',
					'terms' => array_map('intval', $country)
				);
			}

			$city = isset($_GET['city']) ? (array) $_GET['city'] : array();

			if ($city) {
				$tax_query[] = array(
					'taxonomy' => $type . '-location',
					'terms' => array_map('intval', $city)
				);
			}

			$tag = isset($_GET['ftag']) ? (array) $_GET['ftag'] : array();

			if ($tag) {
				$tax_query[] = array(
					'taxonomy' => $type . '-tag',
					'terms' => array_map('intval', $tag)
				);
			}

			if ($meta_query) {
				$meta_query['relation'] = 'OR';

				$query->set('meta_query', $meta_query);
			}

			if ($tax_query) {
				$query->set('tax_query', $tax_query);
			}
		}

		if (($query->is_home() || $query->is_category() || $query->is_search()) && $query->is_main_query()) {
			$query->set('post_type', 'post');

			$per_page = (isset($_REQUEST['per_page']) && in_array($_REQUEST['per_page'], array(12, 24, 48, 96))) ? $_REQUEST['per_page'] : 12;

			$query->set('posts_per_page', $per_page);

			$sort = (isset($_REQUEST['sort']) && in_array($_REQUEST['sort'], array('alphabetically', 'popularity'))) ? $_REQUEST['sort'] : 'alphabetically';

			$query->set('orderby', 'name');
			$query->set('order', 'ASC');

			if ($sort == 'popularity') {
				$query->set('meta_key', '_views');
				$query->set('orderby', 'meta_value_num');
				$query->set('order', 'DESC');
			}

			$tax_query = array();

			$type = isset($_REQUEST['type']) ? (array) $_REQUEST['type'] : array();

			if ($type) {
				$tax_query[] = array(
					'taxonomy' => 'post-type',
					'terms' => array_map('intval', $type)
				);
			}

			$tag = isset($_REQUEST['post_tag']) ? (array) $_REQUEST['post_tag'] : array();

			if ($tag) {
				$tax_query[] = array(
					'taxonomy' => 'post_tag',
					'terms' => array_map('intval', $tag)
				);
			}

			if ($tax_query) {
				$query->set('tax_query', $tax_query);
			}
		}
	}

	public function filter_posts_results($posts, $wp_query) {
		global $total_results;

		if ($wp_query->is_main_query()) {
			$total_results = (int) $wp_query->found_posts;
		}

		return $posts;
	}

	public function filter_terms_clauses($pieces, $taxonomies, $args) {
		if (!isset($args['exclude_parents']) || $args['exclude_parents'] !== 1) {
			return $pieces;
		}

		$pieces['where'] .= ' AND tt.parent > 0';

		return $pieces;
	}

	public function filter_parse_query($query) {
		if (!is_admin()) {
			return $query;
		}

		if (isset($_GET['affiliate']) && !empty($_GET['affiliate'])) {
			$query->set('meta_key', '_is_affiliate');

			if ($_GET['affiliate'] == '-1') {
				$query->set('meta_compare', 'NOT EXISTS');
			} else {
				$query->set('meta_value', '1');
				$query->set('meta_compare', '=');
			}
		}

		if (isset($_GET['commission']) && !empty($_GET['commission'])) {
			$query->set('meta_key', '_commission');

			if ($_GET['commission'] == '-1') {
				$query->set('meta_compare', 'NOT EXISTS');
			} else {
				$query->set('meta_value', array(''));
				$query->set('meta_compare', 'NOT IN');
			}
		}
	}

	public function action_restrict_manage_posts($post_type) {
		$this->post_type = $post_type;

		$options = get_object_taxonomies($post_type, 'objects');
		$options = array_filter($options, array($this, 'is_filterable'));

		array_walk($options, array($this, 'output_filter_for'));

		$affiliate = isset($_GET['affiliate']) ? $_GET['affiliate'] : '';
		$commission = isset($_GET['commission']) ? $_GET['commission'] : '';

		echo '
			<select name="affiliate" class="postform">
				<option value="">' . __('Is Affiliate', 'wb') . '</option>
				<option value="-1" ' . (($affiliate == '-1') ? 'selected' : '') . '>' . __('No', 'wb') . '</option>
				<option value="1" ' . (($affiliate == '1') ? 'selected' : '') . '>' . __('Yes', 'wb') . '</option>
			</select>
		';

		echo '
			<select name="commission" class="postform">
				<option value="">' . __('Has Commission', 'wb') . '</option>
				<option value="-1" ' . (($commission == '-1') ? 'selected' : '') . '>' . __('No', 'wb') . '</option>
				<option value="1" ' . (($commission == '1') ? 'selected' : '') . '>' . __('Yes', 'wb') . '</option>
			</select>
		';
	}

	protected function is_filterable($taxonomy) {
		if ($this->post_type == 'post' && $taxonomy->name == 'category') {
			return false;
		}

		return true;
	}

	protected function output_filter_for($taxonomy) {
		wp_dropdown_categories(array(
			'show_option_all' => sprintf(__('All %s', 'wb'), $taxonomy->label),
			'orderby'         => 'name',
			'order'           => 'ASC',
			'hide_empty'      => false,
			'hide_if_empty'   => true,
			'selected'        => filter_input(INPUT_GET, $taxonomy->query_var, FILTER_SANITIZE_STRING),
			'hierarchical'    => true,
			'name'            => $taxonomy->query_var,
			'taxonomy'        => $taxonomy->name,
			'value_field'     => 'slug'
		));
	}

	public static function get_prices($array) {
		sort($array);

		$array = array_filter($array, 'is_numeric');

		$rangeLimits = array(0, 25, 50, 100, 200, 500);

		$ranges = array();

		for ($i = 0; $i < count($rangeLimits); $i++) {
			if ($i == count($rangeLimits) - 1) {
				break;
			}

			$lowLimit = $rangeLimits[$i];
			$highLimit = $rangeLimits[$i + 1];

			$ranges[$i]['ranges']['min'] = $lowLimit;
			$ranges[$i]['ranges']['max'] = $highLimit;

			foreach ($array as $perPrice) {
				if ($perPrice >= $lowLimit && $perPrice < $highLimit) {
					$ranges[$i]['values'][] = $perPrice;
				}
			}

			if (!isset($ranges[$i]['values'])) {
				unset($ranges[$i]);
			}
		}

		return array(
			'max' => max($array),
			'list' => $ranges
		);
	}

}
