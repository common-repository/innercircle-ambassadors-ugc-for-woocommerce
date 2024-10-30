<?php


namespace Innercircle\templates\Util;

use Innercircle\admin\IC_Page;
use Innercircle\Core\Assets_Interface;

class JS_Loader implements Assets_Interface
{
    const JS_BASE_PATH = 'assets/js/dist';
    public function __construct()
    {
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue'] );
    }

    public function enqueue()
    {
        if (!is_page_template('page-innercircle-platform.php'))
            return;

        wp_register_script(
            'ic-platform-url-generator',
            plugins_url(self::JS_BASE_PATH . '/ic-woo-user.min.js', dirname(__FILE__)),
            ['jquery'],
            filemtime(plugin_dir_path(dirname(__FILE__)) . self::JS_BASE_PATH . '/ic-woo-user.min.js')
        );
        wp_enqueue_script('ic-platform-url-generator');
        wp_localize_script('ic-platform-url-generator', 'INNERCIRCLE_PORTAL_URL', INNERCIRCLE_PORTAL_URL);
        wp_localize_script('ic-platform-url-generator', 'INNERCIRCLE_HOST', INNERCIRCLE_HOST);
        wp_localize_script('ic-platform-url-generator', 'IC_COMPANY_SLUG', get_option(IC_Page::IC_COMPANY_SLUG_OPTION_NAME, null));
    }
}