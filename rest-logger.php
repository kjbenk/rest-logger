<?php
/**
 * Plugin Name: REST Logger
 * Plugin URI: https://github.com/kjbenk/rest-logger
 * Description: A WordPress Plugin that logs WP-API REST Requests.
 * Author: Kyle Benk
 * Author URI: https://kylebenk.com
 * Version: 1.0.0
 * Text Domain: rlg
 * Domain Path: languages
 *
 * @package Main File
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'REST_Logger' ) ) :

	/**
	 * REST_Logger class.
	 */
	final class REST_Logger {

		/**
		 * Holds the REST_Logger object and is the only way to obtain it
		 *
		 * @var mixed
		 * @access private
		 * @static
		 */
		private static $instance;

		/**
		 * Creates or retrieves the REST_Logger instance
		 *
		 * @access public
		 * @static
		 */
		public static function instance() {
			// No object is created yet so lets create one.
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof REST_Logger ) ) {

				self::$instance = new REST_Logger;
				self::$instance->setup_constants();
				self::$instance->includes();

				add_action( 'plugins_loaded',   array( self::$instance, 'load_textdomain' ) );
				add_action( 'init',             array( self::$instance, 'initial_setup' ) );
			}

			// Return the REST_Logger object.
			return self::$instance;
		}

		/**
		 * Throw an error if this class is cloned
		 *
		 * @access public
		 * @return void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_attr__( 'You cannot __clone an instance of the REST_Logger class.', 'rest-logger' ), '1.6' );
		}

		/**
		 * Throw an error if this class is unserialized
		 *
		 * @access public
		 * @return void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_attr__( 'You cannot __wakeup an instance of the REST_Logger class.', 'rest-logger' ), '1.6' );
		}

		/**
		 * Sets up the constants we will use throughout the plugin
		 *
		 * @access private
		 * @return void
		 */
		private function setup_constants() {

			// Plugin prefix.
			if ( ! defined( 'REST_LOGGER_PREFIX' ) ) {
				define( 'REST_LOGGER_PREFIX', 'rlg-' );
			}

			// Plugin name.
			if ( ! defined( 'REST_LOGGER_NAME' ) ) {
				define( 'REST_LOGGER_NAME', 'Rest Logger' );
			}

			// Plugin version.
			if ( ! defined( 'REST_LOGGER_VERSION' ) ) {
				define( 'REST_LOGGER_VERSION', '1.0.0' );
			}

			// Plugin Folder Path.
			if ( ! defined( 'REST_LOGGER_PLUGIN_DIR' ) ) {
				define( 'REST_LOGGER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL.
			if ( ! defined( 'REST_LOGGER_PLUGIN_URL' ) ) {
				define( 'REST_LOGGER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File.
			if ( ! defined( 'REST_LOGGER_PLUGIN_FILE' ) ) {
				define( 'REST_LOGGER_PLUGIN_FILE', __FILE__ );
			}
		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @return void
		 */
		private function includes() {

			// Functions.
			require_once( REST_LOGGER_PLUGIN_DIR . 'functions/activation.php' );
			require_once( REST_LOGGER_PLUGIN_DIR . 'functions/admin-pages.php' );
			require_once( REST_LOGGER_PLUGIN_DIR . 'functions/logger.php' );

			// Model.
			require_once( REST_LOGGER_PLUGIN_DIR . 'model/requests.php' );

			// Classes.
			require_once( REST_LOGGER_PLUGIN_DIR . 'classes/class.requests-table.php' );

			$rlg_requests = new RLG_Requests_Model();
			add_action( 'rlg_log_request', array( $rlg_requests, 'add_data' ), 10, 1 );
		}

		/**
		 * Perform initial setup
		 */
		public function initial_setup() {}

		/**
		 * Load the text domain for translation
		 *
		 * @access public
		 * @return void
		 */
		public function load_textdomain() {
			load_textdomain( 'rest-logger' , dirname( plugin_basename( REST_LOGGER_PLUGIN_FILE ) ) . '/languages/' );
		}
	}

endif; // End if class_exists check.

/**
 * This is the function you will use in order to obtain an instance
 * of the REST_Logger class.
 *
 * Example: <?php $rest_Logger = rest_logger_instance(); ?>
 *
 * @access public
 */
function rest_logger_instance() {
	return REST_Logger::instance();
}

// Get the class loaded up and running.
rest_logger_instance();
