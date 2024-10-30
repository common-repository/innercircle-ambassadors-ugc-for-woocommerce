<?php

require_once INNERCIRCLE_BASE_PATH . "./../includes/InnerCircle_HTTP.php";

class InnerCircle_Uninstall
{
    const IC_PAGE_OPTION_NAME = "innercircle_page_id";
    const IC_DEACTIVATE_WEBHOOK_OPTION_NAME = "innercircle_webhook_account.deactivate";
    const IC_ORDER_WEBHOOK_OPTION_NAME = "innercircle_webhook_order.updated";
    const INNERCIRCLE_AUTH_TRANSIENT = 'innercircle_auth_transient';
    const IC_COMPANY_SLUG_OPTION_NAME = "innercircle_company_slug";

    public function uninstall(){
        $this->notifyIc();
        $this->deletePage();
        $this->deleteWebhook();
        $this->deleteTransient();
    }

    private function notifyIc(){
        $icDeactivateWebhook = get_option(self::IC_DEACTIVATE_WEBHOOK_OPTION_NAME, null);
        if (is_null($icDeactivateWebhook))
            return;

        $webhook = json_decode($icDeactivateWebhook);

        $icHttp = new InnerCircle_HTTP();
        $deactivation = array(
            "deactivate" => true
        );
        $icHttp->makePostRequest($deactivation, $webhook->secret, $webhook->deliveryUrl, $webhook->topic);
    }

    private function deletePage()
    {
        $savedPageId = get_option(self::IC_PAGE_OPTION_NAME);

        if (!$savedPageId)
            return;

        wp_trash_post($savedPageId);
        delete_option(self::IC_PAGE_OPTION_NAME);
        delete_option(self::IC_COMPANY_SLUG_OPTION_NAME);
    }

    private function deleteWebhook()
    {
        $icOrderWebhook = get_option(self::IC_ORDER_WEBHOOK_OPTION_NAME, null);
        if (!is_null($icOrderWebhook))
            delete_option(self::IC_ORDER_WEBHOOK_OPTION_NAME);

        $icDeactivateWebhook = get_option(self::IC_DEACTIVATE_WEBHOOK_OPTION_NAME, null);
        if (!is_null($icDeactivateWebhook))
            delete_option(self::IC_DEACTIVATE_WEBHOOK_OPTION_NAME);
    }

    private function deleteTransient(){
        delete_transient(self::INNERCIRCLE_AUTH_TRANSIENT);
    }
}