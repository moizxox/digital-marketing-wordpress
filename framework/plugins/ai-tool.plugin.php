<?php

class AI_Tool extends WB_Plugin {

	private $tools = array();

	public function __construct() {
		parent::__construct();
		$this->register_default_tools();
		add_action('init', array($this, 'action_init'));
		add_action('manage_ai-tool_posts_columns', array($this, 'filter_manage_ai_tool_posts_columns'));
		add_action('manage_ai-tool_posts_custom_column', array($this, 'action_manage_ai_tool_posts_custom_column'), 10, 2);
		add_action('save_post', array($this, 'action_save_post'));
	}

	public function action_init() {
		add_action('admin_menu', array($this, 'add_admin_menu'));
		add_action('wp_ajax_ai_tool_execute', array($this, 'execute_tool'));

		register_post_type('ai-tool', array(
			'labels' => array(
				'name'               => _x('AI Tools', 'post type general name', 'wb'),
				'singular_name'      => _x('AI Tool', 'post type singular name', 'wb'),
				'menu_name'          => _x('AI Tools', 'admin menu', 'wb'),
				'name_admin_bar'     => _x('AI Tool', 'add new on admin bar', 'wb'),
				'add_new'            => _x('Add New', 'ai-tool', 'wb'),
				'add_new_item'       => __('Add New AI Tool', 'wb'),
				'new_item'           => __('New AI Tool', 'wb'),
				'edit_item'          => __('Edit AI Tool', 'wb'),
				'view_item'          => __('View AI Tool', 'wb'),
				'all_items'          => __('All AI Tools', 'wb'),
				'search_items'       => __('Search AI Tools', 'wb'),
				'parent_item_colon'  => __('Parent AI Tools:', 'wb'),
				'not_found'          => __('No AI tools found.', 'wb'),
				'not_found_in_trash' => __('No AI tools found in Trash.', 'wb')
			),
			'public'               => true,
			'menu_position'        => 30,
			'menu_icon'            => 'dashicons-admin-generic',
			'supports'             => array('title', 'editor', 'excerpt', 'thumbnail')
		));

		register_taxonomy('ai-tool-category', 'ai-tool', array(
			'labels' => array(
				'name'              => _x('Categories', 'taxonomy general name', 'wb'),
				'singular_name'     => _x('Category', 'taxonomy singular name', 'wb'),
				'menu_name'         => __('Categories', 'wb'),
				'parent_item'       => __('Parent Category', 'wb'),
				'parent_item_colon' => __('Parent Category:', 'wb'),
				'edit_item'         => __('Edit Category', 'wb'),
				'update_item'       => __('Update Category', 'wb'),
				'add_new_item'      => __('Add New Category', 'wb'),
				'new_item_name'     => __('New Category Name', 'wb'),
				'all_items'         => __('All Categories', 'wb'),
				'search_items'      => __('Search Categories', 'wb')
			),
			'public' => true,
			'show_admin_column' => true,
			'hierarchical' => true
		));

		register_taxonomy('ai-tool-pricing-option', 'ai-tool', array(
			'labels' => array(
				'name'              => _x('Pricing Options', 'taxonomy general name', 'wb'),
				'singular_name'     => _x('Pricing Option', 'taxonomy singular name', 'wb'),
				'menu_name'         => __('Pricing Options', 'wb'),
				'parent_item'       => __('Parent Pricing Option', 'wb'),
				'parent_item_colon' => __('Parent Pricing Option:', 'wb'),
				'edit_item'         => __('Edit Pricing Option', 'wb'),
				'update_item'       => __('Update Pricing Option', 'wb'),
				'add_new_item'      => __('Add New Pricing Option', 'wb'),
				'new_item_name'     => __('New Pricing Option Name', 'wb'),
				'all_items'         => __('All Pricing Options', 'wb'),
				'search_items'      => __('Search Pricing Options', 'wb')
			),
			'public' => true,
			'publicly_queryable' => false,
			'show_admin_column' => true,
			'hierarchical' => true
		));
	}

	private function register_default_tools() {
		$this->tools = array(
			'text_summarizer' => array(
				'name' => __('Text Summarizer', 'wb'),
				'description' => __('Summarizes long text into key points', 'wb'),
				'callback' => array($this, 'summarize_text')
			),
			'content_generator' => array(
				'name' => __('Content Generator', 'wb'),
				'description' => __('Generates content based on given topic and parameters', 'wb'),
				'callback' => array($this, 'generate_content')
			),
			'seo_optimizer' => array(
				'name' => __('SEO Optimizer', 'wb'),
				'description' => __('Suggests SEO improvements for content', 'wb'),
				'callback' => array($this, 'optimize_seo')
			),
			'code_analyzer' => array(
				'name' => __('Code Analyzer', 'wb'),
				'description' => __('Analyzes code for improvements and potential issues', 'wb'),
				'callback' => array($this, 'analyze_code')
			)
		);
	}

	public function add_admin_menu() {
		add_menu_page(
			__('AI Tools', 'wb'),
			__('AI Tools', 'wb'),
			'manage_options',
			'ai-tools',
			array($this, 'tools_page'),
			'dashicons-admin-tools',
			31
		);
	}

	public function tools_page() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<div class="ai-tools-container">
				<?php foreach ($this->tools as $tool_id => $tool) : ?>
					<div class="ai-tool-card">
						<h3><?php echo esc_html($tool['name']); ?></h3>
						<p><?php echo esc_html($tool['description']); ?></p>
						<button class="button button-primary" 
								onclick="executeTool('<?php echo esc_attr($tool_id); ?>')"
								data-tool="<?php echo esc_attr($tool_id); ?>">
							<?php _e('Use Tool', 'wb'); ?>
						</button>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<script type="text/javascript">
		function executeTool(toolId) {
			// Implementation will vary based on tool
			console.log('Executing tool:', toolId);
			
			// Example AJAX call
			jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'ai_tool_execute',
					tool: toolId,
					nonce: '<?php echo wp_create_nonce('ai_tool_nonce'); ?>'
				},
				success: function(response) {
					if (response.success) {
						alert(response.data.message);
					} else {
						alert('Error: ' + response.data.message);
					}
				}
			});
		}
		</script>

		<style>
		.ai-tools-container {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
			gap: 20px;
			padding: 20px 0;
		}
		.ai-tool-card {
			background: #fff;
			border: 1px solid #ddd;
			padding: 20px;
			border-radius: 5px;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		}
		.ai-tool-card h3 {
			margin-top: 0;
		}
		</style>
		<?php
	}

	public function execute_tool() {
		check_ajax_referer('ai_tool_nonce', 'nonce');

		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('message' => __('Insufficient permissions', 'wb')));
		}

		$tool = isset($_POST['tool']) ? sanitize_text_field($_POST['tool']) : '';

		if (!isset($this->tools[$tool])) {
			wp_send_json_error(array('message' => __('Invalid tool', 'wb')));
		}

		try {
			$result = call_user_func($this->tools[$tool]['callback']);
			wp_send_json_success(array('message' => $result));
		} catch (Exception $e) {
			wp_send_json_error(array('message' => $e->getMessage()));
		}
	}

	// Tool Implementation Methods

	public function summarize_text($text = '') {
		// Get text from post content if not provided
		if (empty($text)) {
			$text = get_the_content();
		}

		// Call AI Agent to summarize
		$ai_agent = new AI_Agent();
		$prompt = "Please summarize the following text:\n\n" . $text;
		
		return $ai_agent->generate_response($prompt);
	}

	public function generate_content($topic = '', $parameters = array()) {
		if (empty($topic)) {
			return __('Topic is required', 'wb');
		}

		$ai_agent = new AI_Agent();
		$prompt = "Generate content about: " . $topic;
		
		if (!empty($parameters)) {
			$prompt .= "\nParameters:\n";
			foreach ($parameters as $key => $value) {
				$prompt .= "- $key: $value\n";
			}
		}

		return $ai_agent->generate_response($prompt);
	}

	public function optimize_seo($content = '', $keywords = array()) {
		if (empty($content)) {
			$content = get_the_content();
		}

		$ai_agent = new AI_Agent();
		$prompt = "Analyze the following content for SEO improvements:\n\n" . $content;
		
		if (!empty($keywords)) {
			$prompt .= "\nTarget keywords: " . implode(', ', $keywords);
		}

		return $ai_agent->generate_response($prompt);
	}

	public function analyze_code($code = '') {
		if (empty($code)) {
			return __('No code provided for analysis', 'wb');
		}

		$ai_agent = new AI_Agent();
		$prompt = "Analyze the following code for improvements and potential issues:\n\n```\n" . $code . "\n```";

		return $ai_agent->generate_response($prompt);
	}

	public function filter_manage_ai_tool_posts_columns($columns) {
		$columns['affiliate_commission'] = __('Is Affiliate / Commission', 'wb');
		return $columns;
	}

	public function action_manage_ai_tool_posts_custom_column($column, $post_id) {
		if ($column == 'affiliate_commission') {
			$affiliate = get_post_meta($post_id, '_is_affiliate', true);
			$commission = get_post_meta($post_id, '_commission', true);

			if ($affiliate) {
				_e('Yes', 'wb');
			} else {
				_e('No', 'wb');
			}

			if ($commission) {
				echo ' / <strong>' . $commission . '</strong>';
			}
		}
	}

	public function action_save_post($post_id) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		if (isset($_POST['ai_tool_details_meta_box_nonce']) && wp_verify_nonce($_POST['ai_tool_details_meta_box_nonce'], 'ai_tool_details_meta_box')) {
			update_post_meta($post_id, '_logo', esc_url($_POST['logo']));
			update_post_meta($post_id, '_website_url', esc_url($_POST['website_url']));
			update_post_meta($post_id, '_amount', esc_attr($_POST['amount']));
			update_post_meta($post_id, '_currency', esc_attr($_POST['currency']));
			update_post_meta($post_id, '_is_affiliate', isset($_POST['affiliate']));
			update_post_meta($post_id, '_commission', esc_attr($_POST['commission']));
		}
	}
}
