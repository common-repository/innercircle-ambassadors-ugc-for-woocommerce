<?php

namespace Innercircle\admin;


use Innercircle\admin\Util\CSS_Loader;

class IC_Admin
{
    const REDIRECT_TRANSIENT = 'innercircle_redirect_after_activation';
    const INNERCIRCLE_AUTH_TRANSIENT = 'innercircle_auth_transient';

    public function __construct()
    {
        add_action('admin_init', array($this, 'redirect_after_activation'));
        add_action('admin_menu', array($this, 'build_menu'), 70);
        $this->enqueue_scripts();
        register_activation_hook(INNERCIRCLE_BASE_PATH, array($this, 'do_activate_action'));
        /**
         * The following hooks relate to plugin activation.
         */
        add_action('innercircle_redirect', array($this, 'set_redirect_transient'));
        add_action('innercircle_activate', array($this, 'do_redirect_action'), 100);
    }


    private function enqueue_scripts()
    {
        new CSS_Loader();
    }

    public function set_redirect_transient()
    {
        set_transient(self::REDIRECT_TRANSIENT, true, 300);
    }

    public function do_redirect_action()
    {
        \do_action('innercircle_redirect');
    }

    public function redirect_after_activation()
    {
        if (get_transient(self::REDIRECT_TRANSIENT)) {
            delete_transient(self::REDIRECT_TRANSIENT);
            wp_safe_redirect(admin_url('admin.php?page=innercircle'));
            exit;
        }
    }

    public function build_menu()
    {
        $menu_title = sprintf(__('Innercircle', 'ic'));
        add_menu_page(__('Innercircle integration', 'ic'), $menu_title, 'manage_woocommerce', 'innercircle', "",
            "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDIwMDEwOTA0Ly9FTiIKICJodHRwOi8vd3d3LnczLm9yZy9UUi8yMDAxL1JFQy1TVkctMjAwMTA5MDQvRFREL3N2ZzEwLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4wIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiB3aWR0aD0iMzI3LjAwMDAwMHB0IiBoZWlnaHQ9IjM3NS4wMDAwMDBwdCIgdmlld0JveD0iMCAwIDMyNy4wMDAwMDAgMzc1LjAwMDAwMCIKIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIG1lZXQiPgoKPGcgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMC4wMDAwMDAsMzc1LjAwMDAwMCkgc2NhbGUoMC4xMDAwMDAsLTAuMTAwMDAwKSIKZmlsbD0iIzAwMDAwMCIgc3Ryb2tlPSJub25lIj4KPHBhdGggZD0iTTE1NjAgMzMyMCBjLTIyOSAtMzIgLTQ3MyAtMTMwIC02NzAgLTI2OSAtMTM1IC05NSAtMzAyIC0yNzQgLTM5NwotNDI2IC00OCAtNzUgLTEzMyAtMjU3IC0xMzMgLTI4MyAwIC01OCA1NiAtMTEyIDExOCAtMTEyIDQ3IDAgODAgMzUgMTI3IDEzNwoxNzQgMzcyIDUwMCA2MzEgOTA1IDcxOSAxMDQgMjMgMzkxIDI2IDQ4NSA2IDE5NyAtNDMgMzY5IC0xMTggNTIwIC0yMjcgNzYKLTU1IDk2IC02NSAxMzIgLTY1IDkxIDAgMTM3IDEwOCA3NyAxNzkgLTYxIDczIC0zMjMgMjIzIC00NzkgMjc1IC0xNjMgNTMKLTI0OCA2NyAtNDQwIDcxIC05OSAyIC0yMDkgMCAtMjQ1IC01eiIvPgo8cGF0aCBkPSJNMTU5NSAyNTg1IGMtMzU4IC03OCAtNjEzIC00MTggLTU5MiAtNzkwIDMgLTU1IDEzIC0xMjcgMjIgLTE2MCA1NgotMjA1IDIxMSAtMzkzIDQwMCAtNDg1IDEyNiAtNjEgMTg5IC03NSAzNDUgLTc0IDEyNiAwIDE0MSAyIDIzMCAzMiA5OSAzNCAyMTcKMTAxIDI2MCAxNDcgMzcgMzkgMzYgMTE0IC0zIDE1MiAtMjIgMjMgLTM2IDI4IC03NSAyNyAtMzkgMCAtNTggLTggLTExNCAtNDYKLTE2NSAtMTEzIC0zNjggLTEyNyAtNTQ4IC0zNyAtMTQxIDcxIC0yNTYgMjIxIC0yODkgMzc3IC01OCAyNzcgMTIzIDU2NSAzOTkKNjM3IDQ4IDEzIDkwIDE2IDE2MCAxMiAxMTUgLTYgMTkwIC0zMSAyODAgLTkxIDgwIC01NSAxMDcgLTYyIDE1MyAtNDMgNDEgMTcKNjcgNjAgNjcgMTA4IDAgNzEgLTE3MiAxODYgLTM0NCAyMjkgLTkzIDIzIC0yNTcgMjYgLTM1MSA1eiIvPgo8cGF0aCBkPSJNNDEyIDE0MzQgYy0zMyAtMjMgLTU1IC03MiAtNDggLTEwNyA5IC00NSA4OCAtMjA2IDE0MSAtMjg5IDIyNQotMzQ2IDU2NyAtNTc3IDk3OCAtNjYwIDkyIC0xOSAxNDQgLTIzIDI5MiAtMjIgMTk1IDAgMzAwIDE2IDQ2MSA3MCAxNjIgNTQKMzgzIDE3OCA0NzcgMjY2IDMyIDMxIDM3IDQxIDM3IDgwIDAgNTUgLTI2IDkyIC03NSAxMDggLTQyIDE0IC02OCA3IC0xMTUgLTI5Ci0yNDAgLTE4NyAtNDkyIC0yNzQgLTc5NSAtMjc1IC0xNjEgLTEgLTI0NSAxMiAtMzk5IDYxIC0yNTMgODAgLTUwNSAyNjkgLTY1NAo0OTEgLTU2IDg1IC0xMzIgMjI4IC0xMzIgMjUxIDAgNSAtMTQgMjMgLTMxIDQwIC0yNCAyNSAtMzkgMzEgLTczIDMxIC0yMyAwCi01MiAtNyAtNjQgLTE2eiIvPgo8L2c+Cjwvc3ZnPgo="
        );
        add_submenu_page("innercircle", "Innercircle", "Innercircle", 'manage_woocommerce', "innercircle", [$this, 'build_app']);
    }

    public function do_activate_action()
    {
        \do_action('innercircle_activate');
    }

    public function build_app()
    {
        $shop = get_site_url();
        $icCode = substr(md5(openssl_random_pseudo_bytes(20)), -32);
        set_transient(self::INNERCIRCLE_AUTH_TRANSIENT, $icCode, 604800);
        echo "<p>Click here to open your Innercircle admin panel 
                <!--
                <a target='_blank' href='" . INNERCIRCLE_PORTAL_URL . "/startnow?referrer=woocommerce&shop=" . $this->encodeURIComponent($shop) . "&code=" . $icCode . "'>Open Dashboard</a>
                //-->
                <a target='_blank' href='https://inner-circle.io/'>Open Dashboard</a>
            </p>";
    }

    private function encodeURIComponent($str)
    {
        $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
        return strtr(rawurlencode($str), $revert);
    }
}