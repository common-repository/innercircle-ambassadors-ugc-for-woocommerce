<?php


namespace Innercircle\admin;

require_once INNERCIRCLE_BASE_PATH . "./../includes/InnerCircle_HTTP.php";

class IC_Webhooks
{
    private $ic_order_webhook;
    private $ic_deactivate_webhook;

    const IC_WEBHOOK_OPTION_NAME = "innercircle_webhook";
    const IC_ORDER_WEBHOOK_OPTION_NAME = "innercircle_webhook_order.updated";
    const IC_DEACTIVATE_WEBHOOK_OPTION_NAME = "innercircle_webhook_account.deactivate";

    /**
     * IC_Webhooks constructor.
     */
    public function __construct()
    {
        add_action('woocommerce_init', [$this, 'get_ic_webhooks']);
        add_action('woocommerce_order_status_changed', [$this, 'trigger_ic_webhook']);
    }

    public function get_ic_webhooks()
    {
        $icOrderWebhook = get_option(self::IC_ORDER_WEBHOOK_OPTION_NAME, null);
        if (!is_null($icOrderWebhook))
            $this->ic_order_webhook = json_decode($icOrderWebhook);

        $icDeactivateWebhook = get_option(self::IC_DEACTIVATE_WEBHOOK_OPTION_NAME, null);
        if (!is_null($icDeactivateWebhook))
            $this->ic_deactivate_webhook = json_decode($icDeactivateWebhook);
    }

    public function saveWebhook($webhookToSave)
    {
        try {
            update_option(self::IC_WEBHOOK_OPTION_NAME . "_" . $webhookToSave["topic"], json_encode($webhookToSave));
        } catch (\Exception $e) {
            throw new \Exception("innercircle webhook was not saved in settings");
        }

    }

    public function deleteWebhook()
    {
        $savedWebhook = get_option(self::IC_ORDER_WEBHOOK_OPTION_NAME, null);
        if (is_null($savedWebhook) && empty($this->ic_order_webhook))
            return;

        $deleted = delete_option(self::IC_ORDER_WEBHOOK_OPTION_NAME);
        if (!$deleted)
            throw new \Exception("innercircle webhook could not be deleted from settings");

        $this->ic_order_webhook = null;
    }

    public function trigger_ic_webhook($order_id)
    {
        if (empty($this->ic_order_webhook))
            return;
        $order = wc_get_order($order_id);
       foreach( $order->get_used_coupons() as $coupon)
        {
        	$order->update_meta_data( 'icCoupon', $coupon );
		}
        $icHttp = new \InnerCircle_HTTP();
        $icHttp->makePostRequest($order->get_data(), $this->ic_order_webhook->secret, $this->ic_order_webhook->deliveryUrl, $this->ic_order_webhook->topic);
    }


    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
}