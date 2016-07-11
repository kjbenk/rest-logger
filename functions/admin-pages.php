<?php
/**
 * This file is responsible for creating the admin pages, and there are hooks for
 * extensibility
 *
 * @package Functions / Admin Pages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hooks into the 'admin_menu' hook to show the settings page
 *
 * @access public
 * @static
 * @return void
 */
function rlg_menu() {

	// Log.
	add_menu_page(
		__( REST_LOGGER_NAME, 'rest-logger' ),
		__( REST_LOGGER_NAME, 'rest-logger' ),
		'edit_posts',
		'rlg-log',
		'rlg_log',
		'dashicons-archive'
	);

	// Log.
	$log_page_load = add_submenu_page(
		'rlg-log',
		__( 'Log', 'rest-logger' ),
		__( 'Log', 'rest-logger' ),
		'edit_posts',
		'rlg-log',
		'rlg_log'
	);
	add_action( "admin_print_scripts-$log_page_load", 'rlg_log_scripts' );

	/**
	 * Allows other developers the ability to add thier own pages
	 */
	do_action( 'rlg_before_admin_pages', 'rlg-log' );

	// Settings.
	$settings_page_load = add_submenu_page(
		'rlg-log',
		__( 'Settings', 'rest-logger' ),
		__( 'Settings', 'rest-logger' ),
		'manage_options',
		'rlg-settings',
		'rlg_settings'
	);
	add_action( "admin_print_scripts-$settings_page_load" , 'rlg_settings_scripts' );

	/**
	 * Allows other developers the ability to add thier own pages
	 */
	do_action( 'rlg_after_admin_pages', 'rlg-log' );
}
add_action( 'admin_menu', 'rlg_menu' );

/**
 * Hooks into the 'admin_print_scripts-$page' to inlcude the scripts for the log page
 *
 * @access public
 * @static
 * @return void
 */
function rlg_log_scripts() {}

/**
 * Hooks into the 'admin_print_scripts-$page' to inlcude the scripts for the settings page
 *
 * @access public
 * @static
 * @return void
 */
function rlg_settings_scripts() {}

/**
 * This is the main function for the log page
 *
 * @access public
 * @static
 * @return void
 */
function rlg_log() {
	require_once( REST_LOGGER_PLUGIN_DIR . 'controllers/log.php' );
}

/**
 * This is the main function for the settings page
 *
 * @access public
 * @static
 * @return void
 */
function rlg_settings() {
	require_once( REST_LOGGER_PLUGIN_DIR . 'controllers/settings.php' );
}
