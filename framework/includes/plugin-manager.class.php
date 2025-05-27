<?php

final class WB_Plugin_Manager {

	public function load_plugins() {
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(WB_FRAMEWORK_DIR . '/plugins'));
		$iterator->setMaxDepth(2);

		foreach ($iterator as $file) {
			if (!$file->isFile()) {
				continue;
			}

			if (!(substr($file, -strlen('.plugin.php')) == '.plugin.php') && !(substr($file, -strlen('.widget.php')) == '.widget.php')) {
				continue;
			}

			$this->add_plugin($file);
		}
	}

	private function add_plugin($file) {
		include_once $file->getRealpath();

		$class_name = preg_replace('/\.plugin.php|.widget.php$/', '', $file->getFileName());
		$class_name = str_replace(' ', '_', ucwords(str_replace('-', ' ', $class_name)));

		if (class_exists($class_name)) {
			if (is_subclass_of($class_name, 'WP_Widget')) {
				add_action('widgets_init', function () use ($class_name) {
					register_widget($class_name);
				});
			} else {
				new $class_name;
			}
		}
	}

}
