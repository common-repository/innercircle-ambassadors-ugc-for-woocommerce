<?php


namespace Innercircle\admin;


class IC_Page
{
    const IC_PAGE_OPTION_NAME = "innercircle_page_id";
    const IC_COMPANY_SLUG_OPTION_NAME = "innercircle_company_slug";

    public function getPage()
    {
        $savedPageId = get_option(self::IC_PAGE_OPTION_NAME, null);
        $page = null;
        if (isset($savedPageId)) {
            $page = get_post($savedPageId);
        }
        return [
            "siteUrl" => get_site_url(),
            "pageSlug" => $page ? $page->post_name : null
        ];
    }

    public function updatePage($pageSlug)
    {
        $savedPageId = get_option(self::IC_PAGE_OPTION_NAME, null);
        $page = get_post($savedPageId);
        $page->post_title = $pageSlug;
        $page->post_name = sanitize_title($pageSlug);
        $postId = wp_update_post($page);
        if (is_wp_error($postId))
            throw new \Exception("could not update innercircle page");
    }

    public function savePage($pageSlug, $companySlug)
    {
        global $wpdb;
        $slug = sanitize_title($pageSlug);
        if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = '{$slug}'", 'ARRAY_A')) {
            $current_user = wp_get_current_user();
            $page = array(
                'post_title' => $pageSlug,
                'post_status' => 'publish',
                'post_author' => $current_user->ID,
                'post_type' => 'page',
                'meta_input' => ['_wp_page_template' => 'page-innercircle-platform.php']
            );
            $pageId = wp_insert_post($page);
            if (is_wp_error($pageId))
                throw new \Exception("innercircle page could not be created");

            $pageSettingUpdated = update_option(self::IC_PAGE_OPTION_NAME, $pageId);
            if (!$pageSettingUpdated)
                throw new \Exception("innercircle page was not saved in settings");

            $companySlugSettingUpdated = update_option(self::IC_COMPANY_SLUG_OPTION_NAME, $companySlug);
            if (!$companySlugSettingUpdated)
                throw new \Exception("innercircle company slug was not saved in settings");
        }
    }

    public function deletePage()
    {
        $savedPageId = get_option(self::IC_PAGE_OPTION_NAME);
        if (!$savedPageId)
            return;

        wp_trash_post($savedPageId);
        delete_option(self::IC_PAGE_OPTION_NAME);
    }
}