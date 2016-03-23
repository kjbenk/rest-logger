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

	return $response;
}
add_action( 'rest_post_dispatch', 'rlg_rest_post_dispatch', 10, 3 );
