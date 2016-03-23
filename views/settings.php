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

<?php require_once( 'footer.php' );
