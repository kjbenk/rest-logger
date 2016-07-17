<?php
/**
 * Manages all the data within the option row.
 *
 * @package Model / Option Logger
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RLG_Requests_Model_Option' ) ) :

	/**
	 * The REST Requests model class
	 */
	class RLG_Requests_Model_Option extends RLG_Requests_Model {

		/**
		 * The option name.
		 *
		 * @var string
		 */
		public $option_name = 'rlg_requests';

		/**
		 * Get requests
		 *
		 * @param array $args      The args to filter requests.
		 * @return array $requests The filtered requests.
		 */
		function get_data( $args = array() ) {
			$data = get_option( $this->option_name );

			if ( false === $data ) {
				$data = array();
			}

			return $data;
		}

		/**
		 * Add a request to the option.
		 *
		 * @param array $data The request data.
		 */
		function add_data( $data ) {
			$all_data = $this->get_data();
			$all_data[] = $this->validate_data( $this->obtain_data( $data ) );

			update_option( $this->option_name, $all_data );
		}

		/**
		 * Delete the oldest entry if we have reached out limit.
		 */
		function delete_oldest_entry() {}

		/**
		 * Validate that the data is in the correct format
		 *
		 * @access public
		 * @param mixed $data  The audit data to validate.
		 * @return mixed $data The validated data.
		 */
		function validate_data( $data ) {
			$validated_data = array_merge( $this->default_data(), $data );
			return $validated_data;
		}

		/**
		 * Parse the returned data
		 *
		 * @param mixed $data  The request data to be parsed.
		 * @return mixed $data The parsed request data.
		 */
		function parse_data( $data ) {
			return $data;
		}
	}

endif;
