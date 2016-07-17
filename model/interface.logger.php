<?php
/**
 * Interfaces the classes for the loggers.
 *
 * @package Model / Interface Logger
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'RLG_Requests_Interface' ) ) :

	/**
	 * The REST Requests interface
	 */
	interface RLG_Requests_Interface {

		/**
		 * Get requests
		 *
		 * @param array $args      The args to filter requests.
		 * @return array $requests The filtered requests.
		 */
		function get_data( $args = array() );

		/**
		 * Add a request to the table.
		 *
		 * @param array $data The request data.
		 */
		function add_data( $data );

		/**
		 * Delete the oldest entry if we have reached out limit.
		 */
		function delete_oldest_entry();

		/**
		 * Validate that the data is in the correct format
		 *
		 * @access public
		 * @param mixed $data  The audit data to validate.
		 * @return mixed $data The validated data.
		 */
		function validate_data( $data );

		/**
		 * Parse the returned data
		 *
		 * @param mixed $data  The request data to be parsed.
		 * @return mixed $data The parsed request data.
		 */
		function parse_data( $data );
	}

endif;
