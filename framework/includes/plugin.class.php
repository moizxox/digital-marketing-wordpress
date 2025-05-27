<?php

class WB_Plugin {

	protected $path;

	public function __construct() {
		$reflection_class = new ReflectionClass($this);

		$this->path = dirname($reflection_class->getFileName());

		foreach ($reflection_class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
			if (strpos($property->name, 'data_') === 0) {
				$option = substr($property->name, 5);

				$default_data = method_exists($this, 'default_data') ? $this->default_data() : '';
			} else {
				$doc_comment = $property->getDocComment();

				if (empty($doc_comment) || strpos($doc_comment, '@Data') === false) {
					continue;
				}
				if (preg_match('#^\s+\*\s*@Data\s+([\w-]+)\s+(\w+)?#im', $doc_comment, $matches)) {
					$option = $matches[1];

					$default_data = (isset($matches[2]) && method_exists($this, $matches[2])) ? $this->{$matches[2]}() : '';
				}
			}

			if (isset($option)) {
				$this->{$property->name} = wp_parse_args(get_option($option), $default_data);
			}
		}

		foreach ($reflection_class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
			if (strpos($method->name, 'action_') === 0) {
				$tag = substr($method->name, 7);

				add_action($tag, array($this, $method->name), 10, $method->getNumberOfParameters());
			} else if (strpos($method->name, 'filter_') === 0) {
				$tag = substr($method->name, 7);

				add_filter($tag, array($this, $method->name), 10, $method->getNumberOfParameters());
			} else if (strpos($method->name, 'shortcode_') === 0) {
				$tag = substr($method->name, 10);

				add_shortcode($tag, array($this, $method->name));
			} else {
				$doc_comment = $method->getDocComment();

				if (empty($doc_comment) || (strpos($doc_comment, '@OptionsPage') === false && strpos($doc_comment, '@Action') === false && strpos($doc_comment, '@Filter') === false && strpos($doc_comment, '@Shortcode') === false)) {
					continue;
				}

				if (is_admin() && current_user_can('edit_theme_options') && preg_match('#^\s+\*\s*@OptionsPage\s\'(.*)\'\s?(\d+)?\s?(.*)?#im', $doc_comment, $matches)) {
					$priority = isset($matches[2]) ? intval($matches[2]) : 1;

					WB_Options_Page::instance()->add_page(array(
						'icon' => $matches[3] ? $matches[3] : 'cog',
						'title' => __($matches[1], 'wb'),
						'name' => sanitize_title($matches[1]),
						'content' => array($this, $method->name),
						'priority' => $priority
					));
				} else if (preg_match_all('#^\s+\*\s*@(Action|Filter)\s+([\w-]+)(\s*\d+)?#im', $doc_comment, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$type = $match[1];
						$tag = $match[2];
						$priority = 10;

						if (!empty($match[3])) {
							$priority = intval($match[3]);
						}

						if ($type == 'Action') {
							add_action($tag, array($this, $method->name), $priority, $method->getNumberOfParameters());
						} else {
							add_filter($tag, array($this, $method->name), $priority, $method->getNumberOfParameters());
						}
					}
				} else if (preg_match_all('#^\s+\*\s*@Shortcode\s+([\w-]+)?#im', $doc_comment, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						add_shortcode($match[1], array($this, $method->name));
					}
				}
			}
		}
	}

}
