<?php
/**
 * This file is responsible for showing the data on the Settings Page.
 *
 * @package Views / Settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( 'header.php' ); ?>

<h1><?php esc_attr_e( 'Settings', 'rlg' ); ?></h1>

<form method="post">
	<table class="form-table">
		<h3><?php esc_attr_e( 'Log a Request with a Status of:', 'rlg' ); ?></h3>
		<tbody>

			<tr>
				<th scope="row"><?php esc_attr_e( 'Informational', 'rlg' ); ?></th>
				<td>
					<fieldset>
						<label for="rlg-log-informational-requests">
							<input type="checkbox" id="rlg-log-informational-requests" name="rlg-log-informational-requests" <?php echo esc_attr( isset( $settings['log_informational_requests'] ) && $settings['log_informational_requests'] ? 'checked="checked"' : '' ); ?>>
							<?php esc_attr_e( 'Log informational requests (i.e. status code of 1xx).', 'rlg' ); ?>
						</label>
					</fieldset>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php esc_attr_e( 'Success', 'rlg' ); ?></th>
				<td>
					<fieldset>
						<label for="rlg-log-success-requests">
							<input type="checkbox" id="rlg-log-success-requests" name="rlg-log-success-requests" <?php echo esc_attr( isset( $settings['log_success_requests'] ) && $settings['log_success_requests'] ? 'checked="checked"' : '' ); ?>>
							<?php esc_attr_e( 'Log success requests (i.e. status code of 2xx).', 'rlg' ); ?>
						</label>
					</fieldset>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php esc_attr_e( 'Redirection', 'rlg' ); ?></th>
				<td>
					<fieldset>
						<label for="rlg-log-redirection-requests">
							<input type="checkbox" id="rlg-log-redirection-requests" name="rlg-log-redirection-requests" <?php echo esc_attr( isset( $settings['log_redirection_requests'] ) && $settings['log_redirection_requests'] ? 'checked="checked"' : '' ); ?>>
							<?php esc_attr_e( 'Log redirection requests (i.e. status code of 3xx).', 'rlg' ); ?>
						</label>
					</fieldset>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php esc_attr_e( 'Client Error', 'rlg' ); ?></th>
				<td>
					<fieldset>
						<label for="rlg-log-client-error-requests">
							<input type="checkbox" id="rlg-log-client-error-requests" name="rlg-log-client-error-requests" <?php echo esc_attr( isset( $settings['log_client_error_requests'] ) && $settings['log_client_error_requests'] ? 'checked="checked"' : '' ); ?>>
							<?php esc_attr_e( 'Log error requests (i.e. status code of 4xx).', 'rlg' ); ?>
						</label>
					</fieldset>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php esc_attr_e( 'Server Error', 'rlg' ); ?></th>
				<td>
					<fieldset>
						<label for="rlg-log-server-error-requests">
							<input type="checkbox" id="rlg-log-server-error-requests" name="rlg-log-server-error-requests" <?php echo esc_attr( isset( $settings['log_server_error_requests'] ) && $settings['log_server_error_requests'] ? 'checked="checked"' : '' ); ?>>
							<?php esc_attr_e( 'Log error requests (i.e. status code of 5xx).', 'rlg' ); ?>
						</label>
					</fieldset>
				</td>
			</tr>

		</tbody>
	</table>

	<table class="form-table">
		<h3><?php esc_attr_e( 'Storage', 'rlg' ); ?></h3>
		<tbody>

			<tr>
				<th scope="row"><?php esc_attr_e( 'Save log data to:', 'rlg' ); ?></th>
				<td>
					<fieldset>
						<label for="rlg-log-storage-type"></label>
						<select type="checkbox" id="rlg-log-storage-type" name="rlg-log-storage-type">
							<?php foreach ( RLG_Requests_Model::get_datasources() as $key => $datasource ) { ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $settings['storage_type'], $key, true ); ?>><?php echo esc_html( $datasource ); ?></option>
							<?php } ?>
						</select>
					</fieldset>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php esc_attr_e( 'Limit Logged Requests', 'rlg' ); ?></th>
				<td>
					<fieldset>
						<label for="rlg-log-request-limit"></label>
						<select type="checkbox" id="rlg-log-request-limit" name="rlg-log-request-limit">
							<option value="25" <?php selected( $settings['request_limit'], $key, true ); ?>><?php esc_html_e( '25', 'rlg' ); ?></option>
							<option value="50" <?php selected( $settings['request_limit'], $key, true ); ?>><?php esc_html_e( '50', 'rlg' ); ?></option>
							<option value="100" <?php selected( $settings['request_limit'], $key, true ); ?>><?php esc_html_e( '100', 'rlg' ); ?></option>
						</select>
						<span><?php esc_html_e( 'requests', 'rlg' ); ?></span>
					</fieldset>
				</td>
			</tr>

		</tbody>
	</table>

	<?php wp_nonce_field( 'rlg-save-settings' ); ?>

	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Changes', 'rlg' ); ?>">
	<input type="submit" name="clear-all-data" id="clear-all-data" class="button button-default" value="<?php esc_attr_e( 'Clear All Data', 'rlg' ); ?>">
</form>

<?php require_once( 'footer.php' );
