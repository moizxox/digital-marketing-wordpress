<?php

class Filter extends WB_Plugin {

	private $post_type;

	public function action_pre_get_posts($query) {
		if (is_admin()) {
			return;
		}

		if (!$query->is_main_query()) {
			return;
		}

		if (isset($query->query_vars['search_page'])) {
			$type = isset($_GET['type']) ? $_GET['type'] : 'tool';
			$query->set('post_type', $type);
			$query->set('post_status', 'publish');

			if (isset($_GET['s']) && !empty($_GET['s'])) {
				$query->set('s', $_GET['s']);
			}

			$per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 12;
			if ($per_page > 0) {
				$query->set('posts_per_page', $per_page);
			}

			if (isset($_GET['sort'])) {
				switch ($_GET['sort']) {
					case 'alphabetical':
						$query->set('orderby', 'title');
						$query->set('order', 'ASC');
						break;
					case 'popularity':
						$query->set('orderby', 'meta_value_num');
						$query->set('meta_key', '_popularity');
						$query->set('order', 'DESC');
						break;
					case 'price_high':
						$query->set('orderby', 'meta_value_num');
						$query->set('meta_key', '_amount');
						$query->set('order', 'DESC');
						break;
					case 'price_low':
						$query->set('orderby', 'meta_value_num');
						$query->set('meta_key', '_amount');
						$query->set('order', 'ASC');
						break;
				}
			}

			$tax_query = array();
			$meta_query = array();

			if (isset($_GET['category']) && !empty($_GET['category'])) {
				$tax_query[] = array(
					'taxonomy' => $type . '-category',
					'field' => 'slug',
					'terms' => $_GET['category']
				);
			}

			if (isset($_GET['tag']) && !empty($_GET['tag'])) {
				$tax_query[] = array(
					'taxonomy' => $type . '-tag',
					'field' => 'slug',
					'terms' => $_GET['tag']
				);
			}

			if (in_array($type, array('tool', 'course', 'service'))) {
				if (isset($_GET['country']) && !empty($_GET['country'])) {
					$tax_query[] = array(
						'taxonomy' => $type . '-location',
						'field' => 'slug',
						'terms' => $_GET['country']
					);
				}

				if (isset($_GET['city']) && !empty($_GET['city'])) {
					$tax_query[] = array(
						'taxonomy' => $type . '-location',
						'field' => 'slug',
						'terms' => $_GET['city']
					);
				}
			}

			if (isset($_GET['pricing_option']) && !empty($_GET['pricing_option'])) {
				$tax_query[] = array(
					'taxonomy' => $type . '-pricing-option',
					'field' => 'slug',
					'terms' => $_GET['pricing_option']
				);
			}

			if (isset($_GET['price_min']) && !empty($_GET['price_min'])) {
				$meta_query[] = array(
					'key' => '_amount',
					'value' => floatval($_GET['price_min']),
					'type' => 'NUMERIC',
					'compare' => '>='
				);
			}

			if (isset($_GET['price_max']) && !empty($_GET['price_max'])) {
				$meta_query[] = array(
					'key' => '_amount',
					'value' => floatval($_GET['price_max']),
					'type' => 'NUMERIC',
					'compare' => '<='
				);
			}

			if (!empty($tax_query)) {
				$query->set('tax_query', $tax_query);
			}

			if (!empty($meta_query)) {
				$query->set('meta_query', $meta_query);
			}
		} else if ($query->is_tax()) {
			$term = get_queried_object();
			$type = str_replace(array('-category', '-tag'), '', $term->taxonomy);
			$query->set('post_type', $type);
			$query->set('post_status', 'publish');
		} else if ($query->is_home() || $query->is_page()) {
			return;
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
