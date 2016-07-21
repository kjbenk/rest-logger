<?php
/**
 * Manages all the data within a JSON file.
 *
 * @package Model / JSON Logger
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RLG_Requests_Model_JSON' ) ) :

	/**
	 * The REST Requests model class
	 */
	class RLG_Requests_Model_JSON extends RLG_Requests_Model {

		/**
		 * The file name.
		 *
		 * @var string
		 */
		public static $filename = 'rlg-requests.json';

		/**
		 * Get requests
		 *
		 * @param array $args      The args to filter requests.
		 * @return array $requests The filtered requests.
		 */
		function get_data( $args = array() ) {
			$data = file_get_contents( REST_LOGGER_PLUGIN_DIR . 'data/' . self::$filename );

			if ( false === $data ) {
				$data = wp_json_encode( array() );
			}

			return json_decode( (string) $data, true );
		}

		/**
		 * Add a request to the option.
		 *
		 * @param array $data The request data.
		 */
		function add_data( $data ) {
			$all_data = $this->get_data();
			$all_data[] = $this->validate_data( $this->obtain_data( $data ) );

			$file = fopen( REST_LOGGER_PLUGIN_DIR . 'data/' . self::$filename, 'w+' );
			fwrite( $file, wp_json_encode( $all_data ) );
			fclose( $file );
		}

		/**
		 * Delete the oldest entry if we have reached out limit.
		 */
		function delete_oldest_entry() {}

		/**
		 * Delete all of the data.
		 */
		static function delete_data() {
			$file = fopen( REST_LOGGER_PLUGIN_DIR . 'data/' . self::$filename, 'w+' );
			fclose( $file );
		}

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
