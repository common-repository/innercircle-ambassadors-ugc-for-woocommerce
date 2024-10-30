<?php
/*
Plugin Name: Innercircle: Ambassadors & UGC for WooCommerce
Description: Innercircle: Turn Your Customers Into Brand Ambassadors. Generate authentic user content, reviews, engagement on social media, and collect genuine feedback.
Author: Innercircle
Author URI: https://inner-circle.io/
Version: 2.2

                Copyright: ©️ 2021 Innercircle
                License: GNU General Public License v3.0
                License URI: https://portal.inner-circle.io/terms-of-use
*/

use Innercircle\Innercircle;
const INNERCIRCLE_PORTAL_URL = "https://portal.inner-circle.io";
const INNERCIRCLE_HOST = "portal.inner-circle.io";
const INNERCIRCLE_BASE_API = "https://apq.inner-circle.io";

const INNERCIRCLE_SETTINGS = "innercircle_settings";
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!defined('WPINC')) {
    die;
}
if ( ! defined( 'INNERCIRCLE_BASE_PATH' ) ) {
    define( 'INNERCIRCLE_BASE_PATH', __FILE__ );
}

/**
 * Check if WooCommerce is active
 */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    require_once 'vendor/autoload.php';
    new Innercircle();
}
