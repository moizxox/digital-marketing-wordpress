<?php

class Search extends WB_Plugin {

	public function action_template_redirect() {
		if (isset($_GET['type']) && !isset($_GET['category']) && ($_GET['type'] == 'content')) {
			$query = esc_attr($_GET['query']);

			wp_redirect(add_query_arg('s', $query, home_url('/')));

			exit;
		}
	}

}
