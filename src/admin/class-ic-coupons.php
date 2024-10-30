<?php

namespace Innercircle\admin;

class IC_Coupons
{
    /**
     * IC_Coupons constructor.
     */
    public function __construct()
    {
    }

    public function addCoupons($couponData)
    {
        foreach ($couponData["discount_codes"] as $coupon) {
            $coupon = array(
                'post_title' => $coupon["code"],
                'post_content' => '',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => 'shop_coupon');
            $new_coupon_id = wp_insert_post($coupon);
            // Add meta
            update_post_meta($new_coupon_id, 'discount_type', $couponData["type"]);
            update_post_meta($new_coupon_id, 'coupon_amount', $couponData["value"]);
            update_post_meta($new_coupon_id, 'individual_use', 'no');
            update_post_meta($new_coupon_id, 'product_ids', '');
            update_post_meta($new_coupon_id, 'exclude_product_ids', '');
            update_post_meta($new_coupon_id, 'usage_limit', '1');
            update_post_meta($new_coupon_id, 'expiry_date', '');
            update_post_meta($new_coupon_id, 'apply_before_tax', 'yes');
            update_post_meta($new_coupon_id, 'free_shipping', 'no');
            update_post_meta($new_coupon_id, 'ic_coupon', true);
        }


    }
}