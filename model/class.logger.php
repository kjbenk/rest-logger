<?php
/**
 * Manages the logging of all data.
 *
 * @package Model / Logger
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RLG_Requests_Model' ) ) :

	/**
	 * The REST Requests model class
	 */
	class RLG_Requests_Model implements RLG_Requests_Interface {

		/**
		 * Data format.
		 *
		 * @var string
		 */
		public $date_format = 'Y-m-d H:i:s';

		/**
		 * The default request
		 *
		 * @return array A default request.
		 */
		function default_data() {
			return array(
				'status' => '0',
				'date'   => '',
				'route'  => '',
				'method' => 'GET',
			);
		}

		/**
		 * Create the new model class and determine which storage system to use.
		 */
		function setup() {
			$storage_type = $this->get_storage_type();
			require_once( REST_LOGGER_PLUGIN_DIR . 'model/class.logger-' . $storage_type . '.php' );

			$class_name = 'RLG_Requests_Model_' . ucfirst( $storage_type );
			$this->logger = new $class_name();
		}

		/**
		 * Get the storage type, and if there is none set then default to `option`.
		 *
		 * @return string The storage type.
		 */
		function get_storage_type() {
			if ( false === ( $settings = get_option( 'rlg_settings' ) ) ) {
				$settings = array();
			}

			return ! empty( $settings['storage_type'] ) ? $settings['storage_type'] : 'option';
		}

		/**
		 * Get the data from the request.
		 *
		 * @param  array $data The request data.
		 * @return array       The obtained request data.
		 */
		function obtain_data( $data ) {
			return array(
				'status' => (int) $data['response']->get_status(),
				'date'   => date( $this->date_format, time() ),
				'route'  => $data['request']->get_route(),
				'method' => $data['request']->get_method(),
				'ip'     => $_SERVER['REMOTE_ADDR'],
			);
		}

		/**
		 * Get requests
		 *
		 * @param array $args      The args to filter requests.
		 */
		function get_data( $args = array() ) {}

		/**
		 * Add a request to the table.
		 *
		 * @param array $data The request data.
		 */
		function add_data( $data ) {}

		/**
		 * Delete the oldest entry if we have reached out limit.
		 */
		function delete_oldest_entry() {}

		/**
		 * Validate that the data is in the correct format
		 *
		 * @access public
		 * @param mixed $data  The audit data to validate.
		 */
		function validate_data( $data ) {}

		/**
		 * Parse the returned data
		 *
		 * @param mixed $data  The request data to be parsed.
		 */
		function parse_data( $data ) {}
	}

endif;
