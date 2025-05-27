<?php

class AI_Tool extends WB_Plugin {

	private $tools = array();

	public function __construct() {
		$this->register_default_tools();
	}

	public function action_init() {
		add_action('admin_menu', array($this, 'add_admin_menu'));
		add_action('wp_ajax_ai_tool_execute', array($this, 'execute_tool'));
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
}
