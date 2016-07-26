<?php
/**
 * This file is responsible for the Settings Page
 *
 * @package Controllers / Log
 */

if ( false === ( $settings = get_option( 'rlg_settings' ) ) ) {
	$settings = array();
}

// Save options.
if ( isset( $_POST['submit'] ) && check_admin_referer( 'rlg-save-settings' ) ) {

	$settings['log_informational_requests'] = isset( $_POST['rlg-log-informational-requests'] ) && $_POST['rlg-log-informational-requests'] ? true : false;
	$settings['log_success_requests'] = isset( $_POST['rlg-log-success-requests'] ) && $_POST['rlg-log-success-requests'] ? true : false;
	$settings['log_redirection_requests'] = isset( $_POST['rlg-log-redirection-requests'] ) && $_POST['rlg-log-redirection-requests'] ? true : false;
	$settings['log_client_error_requests'] = isset( $_POST['rlg-log-client-error-requests'] ) && $_POST['rlg-log-client-error-requests'] ? true : false;
	$settings['log_server_error_requests'] = isset( $_POST['rlg-log-server-error-requests'] ) && $_POST['rlg-log-server-error-requests'] ? true : false;
	$settings['storage_type'] = isset( $_POST['rlg-log-storage-type'] ) ? sanitize_text_field( $_POST['rlg-log-storage-type'] ) : 'option';
	$settings['request_limit'] = isset( $_POST['rlg-log-request-limit'] ) ? sanitize_text_field( $_POST['rlg-log-request-limit'] ) : '50';

	update_option( 'rlg_settings', $settings );
}

// Clear all data.
if ( isset( $_POST['clear-all-data'] ) && check_admin_referer( 'rlg-save-settings' ) ) {
	RLG_Requests_Model::get_instance()->logger->delete_data();
}

include_once( REST_LOGGER_PLUGIN_DIR . 'views/settings.php' );
