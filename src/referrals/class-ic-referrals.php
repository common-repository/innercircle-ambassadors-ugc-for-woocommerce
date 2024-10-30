<?php


namespace Innercircle\referrals;


class IC_Referrals
{
    /**
     * Refferals constructor.
     */
    public function __construct()
    {
        add_action( 'init',  [$this, 'set_check_content_cookie'], 0 );
        add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'custom_checkout_field_update_order_meta' ], 0 );
    }

    public function set_check_content_cookie() {
        if ( is_admin() ) {
            return;
        }

        if ( isset( $_GET['icrc'] )){
            setcookie( 'icrc', sanitize_text_field($_GET['icrc']), time()+2592000, COOKIEPATH, COOKIE_DOMAIN );
        }
    }

    public function custom_checkout_field_update_order_meta($order_id) {
        if (isset($_COOKIE['icrc']))
            update_post_meta($order_id, 'icrc', sanitize_text_field($_COOKIE['icrc']));
    }
}