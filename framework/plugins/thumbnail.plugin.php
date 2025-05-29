<?php

class Thumbnail extends WB_Plugin {

	public function filter_get_post_metadata($value, $post_id, $meta_key, $single) {
		static $is_recursing = false;

		if (!$is_recursing && ($meta_key === '_thumbnail_id')) {
			$is_recursing = true;

			$value = get_post_thumbnail_id($post_id);

			$is_recursing = false;

			if (!$value) {
				$value = 26710;
			}

			if (!$single) {
				$value = array($value);
			}
		}

		return $value;
	}

}
