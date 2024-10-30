<?php
namespace Innercircle\admin\Util;

use Innercircle\Core\Assets_Interface;

class CSS_Loader implements Assets_Interface {


    /**
     * CSS_Loader constructor.
     */
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [ $this, 'enqueue' ]);
    }

    public function enqueue() {
        wp_enqueue_style(
            'innercircle',
            plugins_url( 'assets/css/ic-admin.css', dirname( __FILE__ ) ),
            array(),
            filemtime( plugin_dir_path( dirname( __FILE__ ) ) . 'assets/css/ic-admin.css' )
        );


    }
}
