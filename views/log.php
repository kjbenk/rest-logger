<?php
/**
 * This file is responsible for showing the data on the Log Page.
 *
 * @package Views / Log
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( 'header.php' ); ?>

<h1><?php esc_attr_e( 'Log', 'rlg' ); ?></h1>

<form method="post" class="rlg-log-form">
	<input type="hidden" name="page" value="<?php echo ( isset( $_REQUEST['page'] ) ? esc_attr__( sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) ) : '' ); // Input var okay. ?>" />
	<?php
	$rest_logger_table = new RLG_Requests_Table();
	$rest_logger_table->prepare_items();
	$rest_logger_table->display();
	wp_nonce_field( 'rlg-log-table' ); ?>
</form>

<?php require_once( 'footer.php' );
