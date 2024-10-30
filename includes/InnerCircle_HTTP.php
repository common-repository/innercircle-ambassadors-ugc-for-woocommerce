<?php

class InnerCircle_HTTP
{
    public function makePostRequest($body, $secret, $deliveryUrl, $headerTopic){
        $signature = base64_encode(hash_hmac("sha256", json_encode($body, JSON_UNESCAPED_UNICODE), wp_specialchars_decode($secret, ENT_QUOTES), true));
        $headers = array(
            "x-ic-webhook-topic" => $headerTopic,
            "x-ic-webhook-signature" => $signature,
            "Content-Type" => "application/json",
        );

        wp_remote_post( "$deliveryUrl", array(
                'method'      => 'POST',
                'timeout'     => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => $headers,
                'body'        => json_encode($body, JSON_UNESCAPED_UNICODE),
                'cookies'     => array()
            )
        );
    }
}