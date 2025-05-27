<?php

function wb_breadcrumb($templates = array(), $strings = array()) {
	$templates = wp_parse_args($templates, array(
		'before' => '<ol class="breadcrumb">',
		'after' => '</ol>',
		'standart' => '<li><a href="%s">%s</a></li>',
		'current' => '<li class="active">%s</li>'
	));

	$strings = wp_parse_args($strings, array(
		'home' => __('Home', 'wb'),
		'search' => __('%s results found for query <em>%s</em>', 'wb'),
		'paged' => __('Page %d', 'wb'),
		'page_not_found' => __('Page not found', 'wb')
	));

	$post_type = get_post_type();
	$queried_object = get_queried_object();

	$breadcrumb['home'] = sprintf($templates['current'], $strings['home']);

	if (get_option('show_on_front') == 'posts') {
		if (!is_home() || is_paged()) {
			$breadcrumb['home'] = sprintf($templates['standart'], home_url('/'), $strings['home']);
		}
	} else {
		if (!is_front_page()) {
			$breadcrumb['home'] = sprintf($templates['standart'], home_url('/'), $strings['home']);
		}

		$page_for_posts = get_option('page_for_posts');

		if (is_home() && !is_paged()) {
			$breadcrumb['blog'] = sprintf($templates['current'], get_the_title($page_for_posts));
		}

		if (($post_type == 'post' && !is_search() && !is_home()) || ($post_type == 'post' && is_paged())) {
			$breadcrumb['blog'] = sprintf($templates['standart'], get_permalink($page_for_posts), get_the_title($page_for_posts));
		}
	}

	if (is_singular() || (is_archive() && !is_post_type_archive()) || is_search() || is_paged()) {
		if ($post_type_link = get_post_type_archive_link($post_type)) {
			$post_type_label = get_post_type_object($post_type)->labels->name;

			$breadcrumb["archive_{$post_type}"] = sprintf($templates['standart'], $post_type_link, $post_type_label);
		}
	}

	if (is_singular()) {
		if (!is_front_page()) {
			$_id = $queried_object->ID;
			$_post_type = $post_type;

			if (is_attachment()) {
				$_id = $queried_object->post_parent;
				$_post_type = get_post_type($_id);
			}

			$taxonomies = array_values(wp_list_filter(get_object_taxonomies($_post_type, 'objects'), array(
				'hierarchical' => true
			)));

			if (!empty($taxonomies)) {
				$taxonomy = $taxonomies[0]->name;
				$terms = get_the_terms($_id, $taxonomy);

				if (!empty($terms)) {
					$terms = array_values($terms);
					$term = $terms[0];

					if ($term->parent != 0) {
						$parent_ids = array_reverse(get_ancestors($term->term_id, $taxonomy));

						foreach ($parent_ids as $parent_id) {
							$term = get_term($parent_id, $taxonomy);

							$breadcrumb["archive_{$taxonomy}_{$parent_id}"] = sprintf($templates['standart'], get_term_link($term->slug, $taxonomy), $term->name);
						}
					}

					$breadcrumb["archive_{$taxonomy}"] = sprintf($templates['standart'], get_term_link($term->slug, $taxonomy), $term->name);
				}
			}

			if ($queried_object->post_parent != 0) {
				$parents = array_reverse(get_post_ancestors($queried_object->ID));

				foreach ($parents as $parent) {
					$breadcrumb["archive_{$post_type}_{$parent}"] = sprintf($templates['standart'], get_permalink($parent), get_the_title($parent));
				}
			}

			$breadcrumb["single_{$post_type}"] = sprintf($templates['current'], get_the_title());
		}
	} else if (is_search()) {
		$total = $GLOBALS['wp_query']->found_posts;
		$text = sprintf($strings['search'], $total, get_search_query());

		$breadcrumb['search'] = sprintf($templates['current'], $text);

		if (is_paged()) {
			$breadcrumb['search'] = sprintf($templates['standart'], home_url('?s=' . urlencode(get_search_query(false))), $text);
		}
	} else if (is_archive()) {
		if (is_category() || is_tag() || is_tax()) {
			$taxonomy = $queried_object->taxonomy;

			if ($queried_object->parent != 0 && is_taxonomy_hierarchical($taxonomy)) {
				$parent_ids = array_reverse(get_ancestors($queried_object->term_id, $taxonomy));

				foreach ($parent_ids as $parent_id) {
					$term = get_term($parent_id, $taxonomy);

					$breadcrumb["archive_{$taxonomy}_{$parent_id}"] = sprintf($templates['standart'], get_term_link($term->slug, $taxonomy), $term->name);
				}
			}

			$breadcrumb["archive_{$taxonomy}"] = sprintf($templates['current'], $queried_object->name);

			if (is_paged()) {
				$breadcrumb["archive_{$taxonomy}"] = sprintf($templates['standart'], get_term_link($queried_object->slug, $taxonomy), $queried_object->name);
			}
		} else if (is_date()) {
			if (is_year()) {
				$breadcrumb['archive_year'] = sprintf($templates['current'], get_the_date('Y'));

				if (is_paged()) {
					$breadcrumb['archive_year'] = sprintf($templates['standart'], get_year_link(get_query_var('year')), get_the_date('Y'));
				}
			} else if (is_month()) {
				$breadcrumb['archive_year'] = sprintf($templates['standart'], get_year_link(get_query_var('year')), get_the_date('Y'));

				$breadcrumb['archive_month'] = sprintf($templates['current'], get_the_date('F'));

				if (is_paged()) {
					$breadcrumb['archive_month'] = sprintf($templates['standart'], get_month_link(get_query_var('year'), get_query_var('monthnum')), get_the_date('F'));
				}
			} else if (is_day()) {
				$breadcrumb['archive_year'] = sprintf($templates['standart'], get_year_link(get_query_var('year')), get_the_date('Y'));

				$breadcrumb['archive_month'] = sprintf($templates['standart'], get_month_link(get_query_var('year'), get_query_var('monthnum')), get_the_date('F'));

				$breadcrumb['archive_day'] = printf($templates['current'], get_the_date('j'));

				if (is_paged()) {
					$breadcrumb['archive_day'] = sprintf($templates['standart'], get_month_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day')), get_the_date('F'));
				}
			}
		} else if (is_post_type_archive() && !is_paged()) {
			$post_type_label = get_post_type_object($post_type)->labels->name;

			$breadcrumb["archive_{$post_type}"] = sprintf($templates['current'], $post_type_label);
		} else if (is_author()) {
			$breadcrumb['archive_author'] = sprintf($templates['current'], $queried_object->display_name); 
		}
	} else if (is_404()) {
		$breadcrumb['page_not_found'] = sprintf($templates['current'], $strings['page_not_found']);
	}

	if (is_paged()) {
		$breadcrumb['paged'] = sprintf($templates['current'], sprintf($strings['paged'], get_query_var('paged')));
	}

	echo $templates['before'] . implode('', $breadcrumb) . $templates['after'];
}

function wb_pagination($templates = array(), $range = 2) {
	global $wp_query, $paged;

	$templates = wp_parse_args($templates, array(
		'before' => '<ul class="pagination">',
		'after' => '</ul>',
		'standart' => '<li><a href="%s">%s</a></li>',
		'current' => '<li class="active">%s</li>'
	));

	$show_items = ($range * 2) + 1;

	if (empty($paged)) {
		$paged = 1;
	}

	if (empty($pages)) {
		$pages = $wp_query->max_num_pages ? $wp_query->max_num_pages : 1;
	}

	if ($pages != 1) {
		echo $templates['before'];

		if ($paged > 2 && $paged > $range + 1 && $show_items < $pages)  {
			echo sprintf($templates['standart'], get_pagenum_link(1), '&laquo;');
		}

		if ($paged > 1 && $show_items < $pages) {
			echo sprintf($templates['standart'], get_pagenum_link($paged - 1), '&lsaquo;');
		}

		for ($i = 1; $i <= $pages; $i++) {
			if ($pages != 1 && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $show_items)) {
				if ($paged == $i) {
					echo sprintf($templates['current'], $i);
				} else {
					echo sprintf($templates['standart'], get_pagenum_link($i), $i);
				}
			}
		}

		if ($paged < $pages && $show_items < $pages) {
			echo sprintf($templates['standart'], get_pagenum_link($paged + 1), '&rsaquo;');
		}

		if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $show_items < $pages) {
			echo sprintf($templates['standart'], get_pagenum_link($pages), '&raquo;');
		}

		echo $templates['after'];
	}

}
