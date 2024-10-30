<?php

namespace Innercircle\admin;

require_once INNERCIRCLE_BASE_PATH . "./../includes/InnerCircle_Uninstall.php";
class IC_Deactivate
{

    public function __construct()
    {
        register_deactivation_hook( INNERCIRCLE_BASE_PATH, array($this, 'do_deactivate_action') );
    }

    public function do_deactivate_action(){
        $uninstallHandler = new \InnerCircle_Uninstall();
        $uninstallHandler->uninstall();
    }
}