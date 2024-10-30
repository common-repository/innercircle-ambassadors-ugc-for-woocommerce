<?php
/**
 * Innercircle Uninstall
 *
 * Uninstalling Innercircle deletes pages, and options.
 *
 */


defined( 'WP_UNINSTALL_PLUGIN' ) || exit;
if ( ! defined( 'INNERCIRCLE_BASE_PATH' ) ) {
    define( 'INNERCIRCLE_BASE_PATH', __FILE__ );
}
require_once INNERCIRCLE_BASE_PATH . "./../includes/InnerCircle_Uninstall.php";
$uninstallHandler = new InnerCircle_Uninstall();
$uninstallHandler->uninstall();