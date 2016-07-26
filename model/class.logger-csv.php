<?php
/**
 * Manages all the data within a CSV file.
 *
 * @package Model / CSV Logger
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RLG_Requests_Model_CSV' ) ) :

	/**
	 * The REST Requests model class
	 */
	class RLG_Requests_Model_CSV extends RLG_Requests_Model {

		/**
		 * The file name.
		 *
		 * @var string
		 */
		public static $filename = 'rlg-requests.csv';

		/**
		 * Get requests
		 *
		 * @param array $args      The args to filter requests.
		 * @return array $requests The filtered requests.
		 */
		function get_data( $args = array() ) {
			$data = array_map( 'str_getcsv', file( REST_LOGGER_PLUGIN_DIR . 'data/' . self::$filename ) );

			if ( false === $data ) {
				$data = array();
			}

			$parsed_data = array();
			foreach ( $data as $item ) {
				$parsed_data_item = array();
				$count = 0;
				foreach ( $this->default_data() as $key => $value ) {
					$parsed_data_item[ $key ] = $item[ $count ];
					$count++;
				}
				$parsed_data[] = $parsed_data_item;
			}

			return $parsed_data;
		}

		/**
		 * Add a request to the option.
		 *
		 * @param array $data The request data.
		 */
		function add_data( $data ) {
			parent::add_data();
			$all_data = $this->get_data();
			$all_data[] = $this->validate_data( $this->obtain_data( $data ) );

			$file = fopen( REST_LOGGER_PLUGIN_DIR . 'data/' . self::$filename, 'w+' );

			foreach ( $all_data as $data_item ) {
				fputcsv( $file, array_values( $data_item ) );
			}

			fclose( $file );
		}

		/**
		 * Delete the oldest entry if we have reached out limit.
		 */
		function delete_oldest_entry() {
			$all_data = $this->get_data();
			unset( $all_data[0] );

			$file = fopen( REST_LOGGER_PLUGIN_DIR . 'data/' . self::$filename, 'w+' );

			foreach ( $all_data as $data_item ) {
				fputcsv( $file, array_values( $data_item ) );
			}

			fclose( $file );
		}

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
