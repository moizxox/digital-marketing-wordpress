<?php

function wb_content($content, $limit, $after = '...') {
	$words = explode(' ', strip_tags(strip_shortcodes($content)));

	if (count($words) >= $limit) {
		return implode(' ', array_splice($words, 0, $limit)) . $after;
	}

	return $content;
}

function wb_image($image_url, $width, $height) {
	if (empty($image_url)) {
		return '';
	}

	$image_path = parse_url($image_url);
	$image_path = $_SERVER['DOCUMENT_ROOT'] . $image_path['path'];

	if (!file_exists($image_path)) {
		return $image_url;
	}

	$image_size = getimagesize($image_path);

	$image_info = pathinfo($image_path);
	$cropped_image_path = $image_info['dirname'] . '/' . $image_info['filename'] . '-' . $width . 'x' . $height . '.' . $image_info['extension'];

	if ($image_size[1] > $width || $image_size[2] > $height) {
		if (file_exists($cropped_image_path)) {
			return str_replace(basename($image_url), basename($cropped_image_path), $image_url);
		}

		if (function_exists('wp_get_image_editor')) {
			if (!is_wp_error($image = wp_get_image_editor($image_path))) {
				$image->resize($width, $height, true);
				$image->save($cropped_image_path);

				$image_path = $cropped_image_path;
			}
		} else {
			$image_path = image_resize($image_path, $width, $height, true);
		}

		return str_replace(basename($image_url), basename($image_path), $image_url);
	}

	return $image_url;
}
