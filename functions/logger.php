<?php
/**
 * This file is responsible for logging all REST Requests based on the user
 * settings.
 *
 * @package Functions / Logger
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Log an REST Request
 *
 * @param WP_HTTP_Response $response  Result to send to the client. Usually a WP_REST_Response.
 * @param WP_REST_Server   $server    Server instance.
 * @param WP_REST_Request  $request   Request used to generate the response.
 */
function rlg_rest_post_dispatch( $response, $server, $request ) {

	if ( false === ( $settings = get_option( 'rlg_settings' ) ) ) {
		$settings = array();
	}

	/**
	 * The settings used when checking whether or not to run the logger.
	 *
	 * @var array $settings The settings.
	 */
	$settings = apply_filters( 'rlg_run_logger_settings', $settings );

	$run = false;
	$status = $response->get_status();

	if ( isset( $settings['log_informational_requests'] ) && $settings['log_informational_requests'] && 100 >= $status && 200 > $status ) {
		$run = true;
	}

	if ( isset( $settings['log_success_requests'] ) && $settings['log_success_requests'] && 200 >= $status && 300 > $status ) {
		$run = true;
	}

	if ( isset( $settings['log_redirection_requests'] ) && $settings['log_redirection_requests'] && 300 >= $status && 400 > $status ) {
		$run = true;
	}

	if ( isset( $settings['log_client_error_requests'] ) && $settings['log_client_error_requests'] && 400 >= $status && 500 > $status ) {
		$run = true;
	}

	if ( isset( $settings['log_server_error_requests'] ) && $settings['log_server_error_requests'] && 500 >= $status && 600 > $status ) {
		$run = true;
	}

	/**
	 * Determine whether or not to run the logger action hook.
	 *
	 * @var bool $run The bool variable on whether or not to run the logger.
	 */
	$run = apply_filters( 'rlg_run_logger', $run );

	if ( $run ) {
		/**
		 * The action fired when a REST Request is dispatched
		 *
		 * @param array $data The response, server, and request objects.
		 */
		do_action( 'rlg_log_request', array(
			'response' => $response,
			'server'   => $server,
			'request'  => $request,
		) );
	}

	return $response;
}
add_action( 'rest_post_dispatch', 'rlg_rest_post_dispatch', 10, 3 );
