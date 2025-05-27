<?php

class View extends WB_Plugin {

	public function action_template_redirect() {
		if (is_tax(array('category', 'tool-category', 'tool-tag', 'course-category', 'course-tag', 'service-category', 'service-tag'))) {
			$term_id = get_queried_object()->term_id;

			$views = get_term_meta($term_id, '_views', true);

			if ($views === '') {
				$views = 0;
			}

			update_term_meta($term_id, '_views', $views + 1);
		}

		if (is_singular(array('post', 'tool', 'course', 'service'))) {
			global $post;

			$views = get_post_meta($post->ID, '_views', true);

			if ($views === '') {
				$views = 0;
			}

			update_post_meta($post->ID, '_views', $views + 1);
		}
	}

}
