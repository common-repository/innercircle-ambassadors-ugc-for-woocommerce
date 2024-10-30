<?php

namespace Innercircle\rest;

use Innercircle\admin\IC_Admin;
use Innercircle\admin\IC_Coupons;
use Innercircle\admin\IC_Webhooks;
use Innercircle\admin\IC_Page;
use \Firebase\JWT\JWT;

class IC_RestApi
{
    const IC_REST_NAMESPACE = 'innercircle/v1';
    private $icPublicKey;
    private $webhookService;
    private $pageService;
    private $couponsService;

    public function __construct(IC_Webhooks $webhookService, IC_Page $pageService, IC_Coupons $couponsService)
    {
        add_action('plugins_loaded', [$this, 'get_ic_public_key']);
        add_filter('https_ssl_verify', '__return_false');
        $this->webhookService = $webhookService;
        $this->pageService = $pageService;
        $this->couponsService = $couponsService;
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function get_ic_public_key()
    {
        $response = wp_remote_get(INNERCIRCLE_BASE_API . '/integrations/app/public-key');
        $this->icPublicKey = wp_remote_retrieve_body($response);
    }

    public function register_routes()
    {
        register_rest_route(
            IC_RestApi::IC_REST_NAMESPACE,
            '/verify-account', array(
            'methods' => 'POST',
            'callback' => [$this, 'verifyInnercircleAccount'],
            'permission_callback' => [$this, 'permissionsValidator']
        ));

        register_rest_route(
            IC_RestApi::IC_REST_NAMESPACE,
            '/webhook', array(
            'methods' => ['POST', 'PUT'],
            'callback' => [$this, 'handleSaveWebhook'],
            'permission_callback' => [$this, 'permissionsValidator']
        ));

        register_rest_route(
            IC_RestApi::IC_REST_NAMESPACE,
            '/webhook', array(
            'methods' => ['DELETE'],
            'callback' => [$this, 'deleteWebhook'],
            'permission_callback' => [$this, 'permissionsValidator']
        ));

        register_rest_route(
            IC_RestApi::IC_REST_NAMESPACE,
            '/page', array(
            'methods' => ['GET'],
            'callback' => [$this, 'getPage'],
            'permission_callback' => [$this, 'permissionsValidator']
        ));
        register_rest_route(
            IC_RestApi::IC_REST_NAMESPACE,
            '/page', array(
            'methods' => ['PUT'],
            'callback' => [$this, 'updatePage'],
            'permission_callback' => [$this, 'permissionsValidator']
        ));
        register_rest_route(
            IC_RestApi::IC_REST_NAMESPACE,
            '/page', array(
            'methods' => ['POST'],
            'callback' => [$this, 'addPage'],
            'permission_callback' => [$this, 'permissionsValidator']
        ));
        register_rest_route(
            IC_RestApi::IC_REST_NAMESPACE,
            '/coupons', array(
            'methods' => ['POST'],
            'callback' => [$this, 'addCoupons'],
            'permission_callback' => [$this, 'permissionsValidator']
        ));
    }

    public function verifyInnercircleAccount(\WP_REST_Request $request)
    {
        $code = $request->get_json_params()["code"];
        $savedCode = get_transient(IC_Admin::INNERCIRCLE_AUTH_TRANSIENT);
        $response = new \WP_REST_Response();
        $response->set_status($savedCode === $code ? 200 : 403);
        delete_transient(IC_Admin::INNERCIRCLE_AUTH_TRANSIENT);
        return $response;
    }

    public function handleSaveWebhook(\WP_REST_Request $request)
    {
        $webhookToSave = $request->get_json_params();
        $response = new \WP_REST_Response();
        try {
            $this->webhookService->saveWebhook($webhookToSave);
            $response->set_status(201);
        } catch (\Exception $e) {
            $response->set_status(500);
            $response->set_data($e);
        }
        return $response;
    }

    public function addCoupons(\WP_REST_Request $request)
    {
        $response = new \WP_REST_Response();
        $couponData = $request->get_json_params();
        try {
            $this->couponsService->addCoupons($couponData);
            $response->set_status(201);
        } catch (\Exception $e) {
            $response->set_status(500);
            $response->set_data($e);
        }
        return $response;
    }

    public function deleteWebhook(\WP_REST_Request $request)
    {
        $response = new \WP_REST_Response();
        try {
            $this->webhookService->deleteWebhook();
            $response->set_status(201);
        } catch (\Exception $e) {
            $response->set_status(500);
            $response->set_data($e);
        }

        return $response;
    }

    public function getPage(\WP_REST_Request $request)
    {
        $response = new \WP_REST_Response();
        $response->set_status(200);
        $response->set_data($this->pageService->getPage());
        return $response;
    }

    public function updatePage(\WP_REST_Request $request)
    {
        $pageSlug = $request->get_json_params();
        $response = new \WP_REST_Response();
        try {
            $this->pageService->updatePage($pageSlug["pageSlug"]);
            $response->set_status(201);
            $response->set_data($this->pageService->getPage());
        } catch (\Exception $e) {
            $response->set_status(400);
            $response->set_data($e);
        }

        return $response;
    }

    public function addPage(\WP_REST_Request $request)
    {
        $pageData = $request->get_json_params();
        $response = new \WP_REST_Response();
        try {
            $this->pageService->savePage($pageData["pageSlug"], $pageData["companySlug"]);
            $response->set_status(201);
            $response->set_data($this->pageService->getPage());
        } catch (\Exception $e) {
            $response->set_status(500);
            $response->set_data($e);
        }

        return $response;
    }

    public function permissionsValidator(\WP_REST_Request $request)
    {
        $icToken = $request->get_header("x-ic-signature");
        try {
            JWT::decode($icToken, $this->icPublicKey, array('RS256'));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}