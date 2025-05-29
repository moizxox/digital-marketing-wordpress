<?php

class Compare extends WB_Plugin {

	public function filter_query_vars($vars) {
		$vars[] = 'compare';

		return $vars;
	}

	public function action_rewrite_rules_array($rules) {
		$new_rules = array();
		$new_rules['(tool|course|service)/(.*)/add/?$']  = 'index.php?$matches[1]=$matches[2]&compare=add';
		$new_rules['(tool|course|service)/(.*)/remove/?$']  = 'index.php?$matches[1]=$matches[2]&compare=remove';

		return $new_rules + $rules;
	}

	public function action_template_redirect() {
		$compare = get_query_var('compare');

		if ($compare) {
			global $post;

			if ($post) {
				if ($compare == 'add') {
					$cookie_name = sprintf('_%s_l', $post->post_type);

					if (!isset($_COOKIE[$cookie_name])) {
						setcookie($cookie_name, $post->ID, time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);

						$_COOKIE[$cookie_name] = $post->ID;

						wp_send_json(array(
							'status' => 'success',
							'count' => count(self::get_ids())
						));
					} else {
						if (!$this->in_list($post)) {
							$ids = self::get_ids($post->post_type);

							$ids[] = $post->ID;

							setcookie($cookie_name, implode(',', $ids), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);

							$_COOKIE[$cookie_name] = implode(',', $ids);

							wp_send_json(array(
								'status' => 'success',
								'count' => count(self::get_ids())
							));
						}
					}
				}

				if ($compare == 'remove') {
					if ($this->in_list($post)) {
						$cookie_name = sprintf('_%s_l', $post->post_type);

						$ids = self::get_ids($post->post_type);

						if (($key = array_search($post->ID, $ids)) !== false) {
							unset($ids[$key]);

							setcookie($cookie_name, implode(',', $ids), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);

							$_COOKIE[$cookie_name] = implode(',', $ids);

							wp_send_json(array(
								'status' => 'success',
								'count' => count(self::get_ids())
							));
						}
					}
				}
			}

			wp_send_json(array(
				'status' => 'error'
			));
		}
	}

	public static function get_ids($type = null) {
		$types = array('tool', 'course', 'service');

		if ($type) {
			$cookie_name = sprintf('_%s_l', $type);

			if (isset($_COOKIE[$cookie_name])) {
				return explode(',', $_COOKIE[$cookie_name]);
			}
		} else {
			$ids = array();

			foreach ($types as $type) {
				$cookie_name = sprintf('_%s_l', $type);

				if (isset($_COOKIE[$cookie_name])) {
					$ids[] = explode(',', $_COOKIE[$cookie_name]);
				}
			}

			if (empty($ids)) {
				return $ids;
			}

			return call_user_func_array('array_merge', $ids);
		}

		return array();
	}

	public static function in_list($post) {
		$cookie_name = sprintf('_%s_l', $post->post_type);

		if (isset($_COOKIE[$cookie_name])) {
			$ids = explode(',', $_COOKIE[$cookie_name]);

			if (in_array($post->ID, $ids)) {
				return true;
			}
		}

		return false;
	}

}
