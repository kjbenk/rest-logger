<?php
/**
 * This class manages the All Audits table using the WP_List_Table class.
 *
 * @package Classes / All Audit Table
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if ( ! class_exists( 'RLG_Requests_Table' ) ) :

	/**
	 * The All Audit Table class
	 */
	class RLG_Requests_Table extends WP_List_Table {

		/**
		 * Initialize the table object
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			parent::__construct( array(
				'singular' => __( 'Request', 'rlg' ),
				'plural'   => __( 'Requests', 'rlg' ),
				'ajax'     => false,
			) );
		}

		/**
		 * Display this message when there are no posts found
		 *
		 * @access public
		 * @return void
		 */
		public function no_items() {
			esc_attr_e( 'No Requests found.', 'rlg' );
		}

		/**
		 * Prepare all the items to be displayed
		 *
		 * @access public
		 * @return void
		 */
		public function prepare_items() {
			$columns = $this->get_columns();
			$sortable = $this->get_sortable_columns();
			$hidden = array();
			$this->_column_headers = array( $columns, $hidden, $sortable );
			$per_page     = 20;
			$current_page = $this->get_pagenum();

			$rest_logger_model = new RLG_Requests_Model();
			$this->items = $rest_logger_model->get_data();
			$total_items = count( $this->items );

			$this->set_pagination_args( array(
				'total_items' => $total_items, 	// We have to calculate the total number of items.
				'per_page'    => $per_page, 	// We have to determine how many items to show on a page.
			) );

			$this->items = array_slice( $this->items,( ( $current_page - 1 ) * $per_page ), $per_page );
			$this->items = apply_filters( 'rlg_log_table_items', $this->items );
		}

		/**
		 * Get all the columns that we want to display
		 *
		 * @access public
		 */
		function get_columns() {
			$columns['status'] = __( 'Status', 'rlg' );
			$columns['date']   = __( 'Date', 'rlg' );
			$columns['route']  = __( 'Route', 'rlg' );
			$columns['method'] = __( 'Method', 'rlg' );

			return apply_filters( 'rlg_log_table_columns', $columns );
		}

		/**
		 * Default Column Value
		 *
		 * @access public
		 * @param mixed $item        The REST Request.
		 * @param mixed $column_name The column that is being displayed.
		 */
		public function column_default( $item, $column_name ) {
			$output = '';
			switch ( $column_name ) {
				case 'date':
					$output = date( 'M j, H:i:s', strtotime( $item[ $column_name ] ) );
				break;
				default:
					$output = $item[ $column_name ];
				break;
			}

			return apply_filters( 'rlg_log_table_column_default', $output );
		}
	}

endif;
