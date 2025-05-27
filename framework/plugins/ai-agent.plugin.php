<?php

class AI_Agent extends WB_Plugin {

	private $api_key;
	private $model;
	private $temperature;
	private $max_tokens;

	public function __construct() {
		$this->api_key = get_option('ai_agent_api_key', '');
		$this->model = get_option('ai_agent_model', 'gpt-3.5-turbo');
		$this->temperature = get_option('ai_agent_temperature', 0.7);
		$this->max_tokens = get_option('ai_agent_max_tokens', 2048);
	}

	public function action_init() {
		add_action('admin_menu', array($this, 'add_admin_menu'));
		add_action('admin_init', array($this, 'register_settings'));
	}

	public function add_admin_menu() {
		add_menu_page(
			__('AI Agent Settings', 'wb'),
			__('AI Agent', 'wb'),
			'manage_options',
			'ai-agent-settings',
			array($this, 'settings_page'),
			'dashicons-admin-generic',
			30
		);
	}

	public function register_settings() {
		register_setting('ai_agent_settings', 'ai_agent_api_key');
		register_setting('ai_agent_settings', 'ai_agent_model');
		register_setting('ai_agent_settings', 'ai_agent_temperature');
		register_setting('ai_agent_settings', 'ai_agent_max_tokens');
	}

	public function settings_page() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields('ai_agent_settings');
				do_settings_sections('ai_agent_settings');
				?>
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="ai_agent_api_key"><?php _e('API Key', 'wb'); ?></label>
						</th>
						<td>
							<input type="password" 
								   id="ai_agent_api_key" 
								   name="ai_agent_api_key" 
								   value="<?php echo esc_attr($this->api_key); ?>" 
								   class="regular-text">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="ai_agent_model"><?php _e('Model', 'wb'); ?></label>
						</th>
						<td>
							<select id="ai_agent_model" name="ai_agent_model">
								<option value="gpt-3.5-turbo" <?php selected($this->model, 'gpt-3.5-turbo'); ?>>GPT-3.5 Turbo</option>
								<option value="gpt-4" <?php selected($this->model, 'gpt-4'); ?>>GPT-4</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="ai_agent_temperature"><?php _e('Temperature', 'wb'); ?></label>
						</th>
						<td>
							<input type="number" 
								   id="ai_agent_temperature" 
								   name="ai_agent_temperature" 
								   value="<?php echo esc_attr($this->temperature); ?>" 
								   min="0" 
								   max="2" 
								   step="0.1">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="ai_agent_max_tokens"><?php _e('Max Tokens', 'wb'); ?></label>
						</th>
						<td>
							<input type="number" 
								   id="ai_agent_max_tokens" 
								   name="ai_agent_max_tokens" 
								   value="<?php echo esc_attr($this->max_tokens); ?>" 
								   min="1" 
								   max="4096">
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	public function generate_response($prompt, $context = array()) {
		if (empty($this->api_key)) {
			return new WP_Error('no_api_key', __('API key is not configured', 'wb'));
		}

		$headers = array(
			'Authorization' => 'Bearer ' . $this->api_key,
			'Content-Type' => 'application/json',
		);

		$messages = array();
		if (!empty($context)) {
			$messages = array_merge($messages, $context);
		}
		$messages[] = array('role' => 'user', 'content' => $prompt);

		$body = array(
			'model' => $this->model,
			'messages' => $messages,
			'temperature' => floatval($this->temperature),
			'max_tokens' => intval($this->max_tokens),
		);

		$response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
			'headers' => $headers,
			'body' => json_encode($body),
			'timeout' => 30,
		));

		if (is_wp_error($response)) {
			return $response;
		}

		$body = json_decode(wp_remote_retrieve_body($response), true);

		if (isset($body['error'])) {
			return new WP_Error('api_error', $body['error']['message']);
		}

		return $body['choices'][0]['message']['content'];
	}

	public function action_wp_ajax_ai_generate() {
		check_ajax_referer('ai_agent_nonce', 'nonce');

		if (!current_user_can('edit_posts')) {
			wp_send_json_error('Insufficient permissions');
		}

		$prompt = isset($_POST['prompt']) ? sanitize_textarea_field($_POST['prompt']) : '';
		
		if (empty($prompt)) {
			wp_send_json_error('Prompt is required');
		}

		$context = isset($_POST['context']) ? json_decode(stripslashes($_POST['context']), true) : array();
		$response = $this->generate_response($prompt, $context);

		if (is_wp_error($response)) {
			wp_send_json_error($response->get_error_message());
		}

		wp_send_json_success($response);
	}
}
