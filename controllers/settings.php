<?php
/**
 * This file is responsible for the Settings Page
 *
 * @package Controllers / Log
 */

if ( false === ( $settings = get_option( 'rlg_settings' ) ) ) {
	$settings = array();
}

if ( isset( $_POST['submit'] ) && check_admin_referer( 'rlg-save-settings' ) ) {

	$settings['log_informational_requests'] = isset( $_POST['rlg-log-informational-requests'] ) && $_POST['rlg-log-informational-requests'] ? true : false;
	$settings['log_success_requests'] = isset( $_POST['rlg-log-success-requests'] ) && $_POST['rlg-log-success-requests'] ? true : false;
	$settings['log_redirection_requests'] = isset( $_POST['rlg-log-redirection-requests'] ) && $_POST['rlg-log-redirection-requests'] ? true : false;
	$settings['log_client_error_requests'] = isset( $_POST['rlg-log-client-error-requests'] ) && $_POST['rlg-log-client-error-requests'] ? true : false;
	$settings['log_server_error_requests'] = isset( $_POST['rlg-log-server-error-requests'] ) && $_POST['rlg-log-server-error-requests'] ? true : false;

	update_option( 'rlg_settings', $settings );
}

include_once( REST_LOGGER_PLUGIN_DIR . 'views/settings.php' );
