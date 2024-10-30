<?php


namespace Innercircle;

use Innercircle\admin\IC_Admin;
use Innercircle\admin\IC_Coupons;
use Innercircle\admin\IC_Deactivate;
use Innercircle\admin\IC_Webhooks;
use Innercircle\admin\IC_Page;
use Innercircle\rest\IC_RestApi;
use Innercircle\templates\IC_PlatformPage;
use Innercircle\referrals\IC_Referrals;
use Innercircle\templates\Util\CSS_Loader;
use Innercircle\templates\Util\JS_Loader;

class Innercircle
{
    public function __construct() {
        new IC_PlatformPage();
        new IC_Referrals();
        new CSS_Loader();
        new JS_Loader();
        $webhooks = new IC_Webhooks();
        $coupons = new IC_Coupons();
        $icPage = new IC_Page();
        new IC_RestApi($webhooks, $icPage, $coupons);
        if ( is_admin() ) {
            new IC_Admin();
            new IC_Deactivate();
        }
    }
}