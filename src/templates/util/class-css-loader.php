<?php
namespace Innercircle\templates\Util;

use Innercircle\Core\Assets_Interface;

class CSS_Loader implements Assets_Interface {


    /**
     * CSS_Loader constructor.
     */
    public function __construct()
    {
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue'] );
    }

    public function enqueue() {
        if(!is_page_template('page-innercircle-platform.php'))
            return;

        wp_enqueue_style(
            'ic-platform-style',
            plugins_url( 'assets/css/ic-platform-style.css', dirname( __FILE__ ) ),
            [],
            filemtime( plugin_dir_path( dirname( __FILE__ ) ) . 'assets/css/ic-platform-style.css' )
        );

    }
}
