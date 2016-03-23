<?php
/**
 * This file handles all operations upon plugin activation.
 *
 * @package Functions / Activation
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The plugin is activated
 *
 * @access public
 * @return void
 */
function rlg_activation() {

	// Add the version number to the database.
	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		update_site_option( 'rlg_version', REST_LOGGER_VERSION );
	} else {
		update_option( 'rlg_version', REST_LOGGER_VERSION );
	}

	// Create the logger database table.
	$rest_logger_model = new RLG_Requests_Model();
	$rest_logger_model->create_table();

	// Add the transient to redirect.
	set_transient( '_rlg_activation_redirect', true, 30 );

	// Add Upgraded From Option.
	update_option( 'rlg_version_upgraded_from', REST_LOGGER_VERSION );
	delete_transient( 'rlg_running_audit' );
}
register_activation_hook( REST_LOGGER_PLUGIN_FILE, 'rlg_activation' );
