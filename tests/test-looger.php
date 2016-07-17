<?php
/**
 * This is the test class for all logger functions
 *
 * @package Tests / Logger
 */

/**
 * Class RLG_Logger_Test
 */
class RLG_Logger_Test extends WP_Test_REST_Controller_Testcase {

	/**
	 * The setup fuction.
	 */
	function setUp() {
		parent::setup();

		$this->editor_id = $this->factory->user->create( array(
			'role' => 'editor',
		) );

		// Create the logger database table.
		$rest_logger_model = new RLG_Requests_Model();
		$rest_logger_model->setup();
		$this->logger_object = $rest_logger_model;
	}

	/**
	 * Test Information Status
	 */
	function test_information_status_code() {
		$this->markTestSkipped( 'TODO: Create a custom endpoint that returns a 100 status.' );

		$request = new WP_REST_Request( 'OPTIONS', '/wp/v2/posts' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 100, $response->get_status() );
	}

	/**
	 * Test Success Status
	 */
	function test_success_status_code() {
		add_filter( 'rlg_run_logger_settings', array( 'RLG_Logger_Test', 'force_settings_true' ), 10, 1 );
		$request = new WP_REST_Request( 'OPTIONS', '/wp/v2/posts' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 200, $response->get_status() );

		$logger_data = $this->logger_object->logger->get_data();
		$this->assertEquals( 1, count( $logger_data ) );
		$this->assertEquals( 200, (int) $logger_data[0]['status'] );

		add_filter( 'rlg_run_logger_settings', array( 'RLG_Logger_Test', 'force_settings_false' ), 10, 1 );
		$request = new WP_REST_Request( 'OPTIONS', '/wp/v2/posts' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 200, $response->get_status() );

		$logger_data = $this->logger_object->logger->get_data();
		$this->assertEquals( 1, count( $logger_data ) );
	}

	/**
	 * Test Client Error Status
	 */
	function test_client_error_status_code() {
		add_filter( 'rlg_run_logger_settings', array( 'RLG_Logger_Test', 'force_settings_true' ), 10, 1 );
		$request = new WP_REST_Request( 'GET', 'zzzzzzzzzzzzzzzzzzzzzzzz' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 404, $response->get_status() );

		$logger_data = $this->logger_object->logger->get_data();
		$this->assertEquals( 1, count( $logger_data ) );
		$this->assertEquals( 404, (int) $logger_data[0]['status'] );

		add_filter( 'rlg_run_logger_settings', array( 'RLG_Logger_Test', 'force_settings_false' ), 10, 1 );
		$request = new WP_REST_Request( 'GET', 'zzzzzzzzzzzzzzzzzzzzzzzz' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 404, $response->get_status() );

		$logger_data = $this->logger_object->logger->get_data();
		$this->assertEquals( 1, count( $logger_data ) );
	}

	/**
	 * Test Server Error Status
	 */
	function test_server_error_status_code() {
		$this->markTestSkipped( 'TODO: Create a custom endpoint that returns a 500 status.' );
		wp_set_current_user( $this->editor_id );

		$request = new WP_REST_Request( 'POST', '/wp/v2/posts' );
		$params  = $this->set_post_data( array() );
		$request->set_body_params( $params );

		/**
		 * Disable showing error as the below is going to intentionally
		 * trigger a DB error.
		 */
		global $wpdb;
		$wpdb->suppress_errors = true;
		add_filter( 'query', array( $this, 'error_insert_query' ) );

		$response = $this->server->dispatch( $request );
		remove_filter( 'query', array( $this, 'error_insert_query' ) );
		$wpdb->show_errors = true;

		$this->assertEquals( 500, $response->get_status() );
	}

	/**
	 * Force the settings to change in order to test different use cases.
	 *
	 * @param  array $settings The logger setings.
	 * @return array $settings The filtered logger setings.
	 */
	static function force_settings_true( $settings ) {

		if ( false === ( $settings = get_option( 'rlg_settings' ) ) ) {
			$settings = array();
		}

		$settings['log_informational_requests'] = true;
		$settings['log_success_requests'] = true;
		$settings['log_redirection_requests'] = true;
		$settings['log_client_error_requests'] = true;
		$settings['log_server_error_requests'] = true;

		return $settings;
	}

	/**
	 * Force the settings to change in order to test different use cases.
	 *
	 * @param  array $settings The logger setings.
	 * @return array $settings The filtered logger setings.
	 */
	static function force_settings_false( $settings ) {

		if ( false === ( $settings = get_option( 'rlg_settings' ) ) ) {
			$settings = array();
		}

		$settings['log_informational_requests'] = false;
		$settings['log_success_requests'] = false;
		$settings['log_redirection_requests'] = false;
		$settings['log_client_error_requests'] = false;
		$settings['log_server_error_requests'] = false;

		return $settings;
	}

	/**
	 * Force the logger to run.
	 *
	 * @return bool true Should the logger run?
	 */
	static function force_log_run() {
		return true;
	}

	/**
	 * Internal function used to disable an insert query which
	 * will trigger a wpdb error for testing purposes.
	 *
	 * @param mixed $query The SQL query.
	 */
	public function error_insert_query( $query ) {
		if ( strpos( $query, 'INSERT' ) === 0 ) {
			$query = '],';
		}
		return $query;
	}

	/**
	 * Setup the post data.
	 *
	 * @param array $args The post arguments.
	 */
	public function set_post_data( $args = array() ) {
		$defaults = array(
			'title'   => rand_str(),
			'content' => rand_str(),
			'excerpt' => rand_str(),
			'name'    => 'test',
			'status'  => 'publish',
			'author'  => get_current_user_id(),
			'type'    => 'post',
		);
		return wp_parse_args( $args, $defaults );
	}

	/**
	 * Test register routes
	 */
	public function test_register_routes() {}

	/**
	 * Test context param
	 */
	public function test_context_param() {}

	/**
	 * Test get items
	 */
	public function test_get_items() {}

	/**
	 * Test get item
	 */
	public function test_get_item() {}

	/**
	 * Test create item
	 */
	public function test_create_item() {}

	/**
	 * Test update item
	 */
	public function test_update_item() {}

	/**
	 * Test delete item
	 */
	public function test_delete_item() {}

	/**
	 * Test prepare item
	 */
	public function test_prepare_item() {}

	/**
	 * Test get item schema
	 */
	public function test_get_item_schema() {}
}
