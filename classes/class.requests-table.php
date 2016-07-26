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
				'singular' => __( 'Request', 'rest-logger' ),
				'plural'   => __( 'Requests', 'rest-logger' ),
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
			esc_attr_e( 'No Requests found.', 'rest-logger' );
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
			$per_page = 25;
			$current_page = $this->get_pagenum();

			$this->items = RLG_Requests_Model::get_instance()->logger->get_data();
			$total_items = count( $this->items );

			if ( is_array( $this->items ) && ! empty( $this->items ) ) {
				usort( $this->items, array( $this, 'sort_items' ) );

				$this->set_pagination_args( array(
					'total_items' => $total_items, 	// We have to calculate the total number of items.
					'per_page'    => $per_page, 	// We have to determine how many items to show on a page.
				) );

				$this->items = array_slice( $this->items,( ( $current_page - 1 ) * $per_page ), $per_page );
				$this->items = apply_filters( 'rlg_log_table_items', $this->items );
			}
		}

		/**
		 * Get all the columns that we want to display
		 *
		 * @access public
		 */
		function get_columns() {
			$columns['status'] = __( 'Status', 'rest-logger' );
			$columns['date']   = __( 'Date', 'rest-logger' );
			$columns['route']  = __( 'Route', 'rest-logger' );
			$columns['method'] = __( 'Method', 'rest-logger' );
			$columns['ip'] = __( 'IP', 'rest-logger' );

			return apply_filters( 'rlg_log_table_columns', $columns );
		}

		/**
		 * Get all the sortable columns.
		 *
		 * @access public
		 */
		function get_sortable_columns() {
			$sortable_columns['status'] = array( 'status', true );
			$sortable_columns['date']   = array( 'date', true );
			$sortable_columns['route']  = array( 'route', true );
			$sortable_columns['method'] = array( 'method', true );
			$sortable_columns['ip'] = array( 'ip', true );

			return apply_filters( 'rlg_log_table_sortable_columns', $sortable_columns );
		}

		/**
		 * Compare two values for sorting.
		 *
		 * @param  mixed $a First value.
		 * @param  mixed $b Second value.
		 * @return bool     The comparision.
		 */
		function sort_items( $a, $b ) {
			if ( $a[ $this->get_orderby() ] === $b[ $this->get_orderby() ] ) {
				return 0;
			}

			$a_val = $a[ $this->get_orderby() ];
			$b_val = $b[ $this->get_orderby() ];

			if ( 'date' === $this->get_orderby() ) {
				$a_val = strtotime( $a_val );
				$b_val = strtotime( $b_val );
			}

			$comparison = $a_val < $b_val;

			if ( 'asc' === $this->get_order() ) {
				$comparison = $a_val > $b_val;
			}

			return $comparison ? -1 : 1;
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
					$output = date( 'M j Y, H:i:s', strtotime( $item[ $column_name ] ) );
				break;
				default:
					$output = $item[ $column_name ];
				break;
			}

			return apply_filters( 'rlg_log_table_column_default', $output );
		}

		/**
		 * Get the orderby param.
		 *
		 * @return string $orderby The orderby param.
		 */
		private function get_orderby() {
			if ( ! empty( $_POST['orderby'] ) ) {
				$orderby = wp_unslash( $_POST['orderby'] );
			} else if ( ! empty( $_GET['orderby'] ) ) {
				$orderby = wp_unslash( $_GET['orderby'] );
			}

			return ! empty( $orderby ) ? wp_unslash( $orderby ) : 'date';
		}

		/**
		 * Get the order param.
		 *
		 * @return string $order The order param.
		 */
		private function get_order() {
			if ( ! empty( $_POST['order'] ) ) {
				$order = wp_unslash( $_POST['order'] );
			} else if ( ! empty( $_GET['order'] ) ) {
				$order = wp_unslash( $_GET['order'] );
			}

			return ! empty( $order ) ? wp_unslash( $order ) : 'asc';
		}
	}

endif;
