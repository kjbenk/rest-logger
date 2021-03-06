<?php
/**
 * Manages all the data within the {$wpdb->prefix}rlg_requests table
 *
 * @package Model / Table Logger
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RLG_Requests_Model_Table' ) ) :

	/**
	 * The REST Requests model class
	 */
	class RLG_Requests_Model_Table extends RLG_Requests_Model {

		/**
		 * Table name
		 *
		 * (default value: 'rlg_requests')
		 *
		 * @var string
		 * @access public
		 */
		public $table_name = 'rlg_requests';

		/**
		 * Make sure the table exists.
		 */
		function __construct() {
			$this->create_table();
		}

		/**
		 * Create a table for the requests
		 *
		 * @access public
		 * @return void
		 */
		function create_table() {
			global $wpdb;

			$sql = 'CREATE TABLE IF NOT EXISTS ' . $this->get_table_name() . " (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`status` int(3) NOT NULL DEFAULT '0',
				`date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				`route` varchar(256) NOT NULL DEFAULT '',
				`method` varchar(8) NOT NULL DEFAULT 'GET',
				`user` int(12) NOT NULL DEFAULT '0',
				`ip` varchar(39) NOT NULL DEFAULT '127.0.0.1',
				PRIMARY KEY (`id`)
			) " . $wpdb->get_charset_collate();

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		/**
		 * Get requests
		 *
		 * @param array $args      The args to filter requests.
		 * @return array $requests The filtered requests.
		 */
		function get_data( $args = array() ) {
			global $wpdb;
			$data = $wpdb->get_results( 'SELECT * FROM `' . $this->get_table_name() . '` ORDER BY `id` DESC;', 'ARRAY_A' ); // WPCS: unprepared SQL ok. // WPCS: cache ok. // WPCS: db call ok.

			return $this->parse_data( $data );
		}

		/**
		 * Add a request to the table.
		 *
		 * @param array $data The request data.
		 */
		function add_data( $data ) {
			parent::add_data();
			$data = $this->validate_data( $data );
			global $wpdb;

			$wpdb->insert(
				$this->get_table_name(),
				$this->obtain_data( $data )
			); // WPCS: db call ok.

			return $wpdb->insert_id;
		}

		/**
		 * Delete the log table.
		 */
		function delete_data() {
			global $wpdb;
			$sql = 'DELETE FROM ' . $this->get_table_name();
			$wpdb->query( $sql ); // WPCS: db call ok. // WPCS: cache ok. // WPCS: unprepared SQL ok.
		}

		/**
		 * Delete the oldest entry if we have reached out limit.
		 */
		function delete_oldest_entry() {
			global $wpdb;
			$sql = 'DELETE FROM `' . $this->get_table_name() . '` WHERE date IS NOT NULL order by date DESC LIMIT 1';
			$wpdb->query( $sql ); // WPCS: db call ok. // WPCS: cache ok. // WPCS: unprepared SQL ok.
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
			$parsed_data = array();
			foreach ( $data as $key => $row ) {
				$request = array();

				foreach ( $row as $column => $value ) {

					if ( is_serialized( $row[ $column ], true ) ) {
						$row[ $column ] = unserialize( $row[ $column ] );
					}

					$request[ $column ] = $row[ $column ];
				}

				$parsed_data[] = $request;
			}

			return $parsed_data;
		}

		/**
		 * Returns the proper table name for Multisies
		 *
		 * @return string $table_name The name of the database table.
		 */
		function get_table_name() {
			global $wpdb;
			return $wpdb->prefix . $this->table_name;
		}
	}

endif;
