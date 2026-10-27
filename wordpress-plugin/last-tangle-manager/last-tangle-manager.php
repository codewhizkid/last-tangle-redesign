<?php
/**
 * Plugin Name: Salon Website Manager
 * Description: Shared content management foundation for bespoke salon websites.
 * Version: 0.1.0
 * Requires at least: 6.4
 * Requires PHP: 8.0
 * Text Domain: salon-website-manager
 */
defined('ABSPATH') || exit;

final class Salon_Website_Manager {
    const OPTION = 'swm_content';
    const REST_NAMESPACE = 'salon-manager/v1';

    public static function init() {
        add_action('rest_api_init', [__CLASS__, 'register_routes']);
        add_action('admin_menu', [__CLASS__, 'admin_menu']);
    }

    public static function sections() {
        return [
            'homepage' => ['headline', 'welcome', 'hero_image_id'],
            'story' => ['story', 'story_image_id'],
            'services' => ['items'],
            'team' => ['items'],
            'gallery' => ['description', 'items'],
            'careers' => ['description'],
            'contact' => ['description', 'booking_url'],
        ];
    }

    public static function defaults() {
        return [
            'homepage' => ['headline' => '', 'welcome' => '', 'hero_image_id' => 0],
            'story' => ['story' => '', 'story_image_id' => 0],
            'services' => ['items' => []],
            'team' => ['items' => []],
            'gallery' => ['description' => '', 'items' => []],
            'careers' => ['description' => ''],
            'contact' => ['description' => '', 'booking_url' => ''],
        ];
    }

    public static function content() {
        $stored = get_option(self::OPTION, []);
        return array_replace_recursive(self::defaults(), is_array($stored) ? $stored : []);
    }

    public static function can_edit() {
        return current_user_can('edit_pages');
    }

    public static function register_routes() {
        register_rest_route(self::REST_NAMESPACE, '/content', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => function () { return rest_ensure_response(self::content()); },
                'permission_callback' => '__return_true',
            ],
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => [__CLASS__, 'save_content'],
                'permission_callback' => [__CLASS__, 'can_edit'],
                'args' => ['content' => ['required' => true, 'type' => 'object']],
            ],
        ]);
    }

    public static function save_content(WP_REST_Request $request) {
        $input = $request->get_param('content');
        if (!is_array($input)) {
            return new WP_Error('swm_invalid_content', 'Expected content object.', ['status' => 400]);
        }
        $clean = self::defaults();
        foreach (self::sections() as $section => $fields) {
            $incoming = isset($input[$section]) && is_array($input[$section]) ? $input[$section] : [];
            foreach ($fields as $field) {
                if (!array_key_exists($field, $incoming)) {
                    continue;
                }
                $value = $incoming[$field];
                if ($field === 'items') {
                    if (!is_array($value) || count($value) > 250) {
                        return new WP_Error('swm_invalid_items', 'Invalid section items.', ['status' => 400]);
                    }
                    $clean[$section][$field] = array_map([__CLASS__, 'sanitize_item'], array_values($value));
                } elseif (str_ends_with($field, '_image_id')) {
                    $clean[$section][$field] = absint($value);
                } elseif ($field === 'booking_url') {
                    $url = esc_url_raw(trim((string) $value), ['https']);
                    if ($value !== '' && (!$url || !str_starts_with($url, 'https://'))) {
                        return new WP_Error('swm_invalid_booking_url', 'Booking URL must use HTTPS.', ['status' => 400]);
                    }
                    $clean[$section][$field] = $url;
                } else {
                    $clean[$section][$field] = sanitize_textarea_field((string) $value);
                }
            }
        }
        update_option(self::OPTION, $clean, false);
        return rest_ensure_response($clean);
    }

    public static function sanitize_item($item) {
        if (!is_array($item)) {
            return [];
        }
        $allowed = ['id', 'name', 'description', 'role', 'bio', 'caption', 'image_id'];
        $clean = [];
        foreach ($allowed as $key) {
            if (!isset($item[$key]) || !array_key_exists($key, $item)) {
                continue;
            }
            $clean[$key] = $key === 'image_id' ? absint($item[$key]) : sanitize_textarea_field((string) $item[$key]);
        }
        return $clean;
    }

    public static function admin_menu() {
        add_menu_page('Salon Website Manager', 'Salon Website', 'edit_pages', 'salon-website-manager', [__CLASS__, 'admin_page'], 'dashicons-admin-customizer', 26);
    }

    public static function admin_page() {
        if (!self::can_edit()) {
            wp_die(esc_html__('You do not have permission to manage this site.', 'salon-website-manager'));
        }
        echo '<div class="wrap"><h1>Salon Website Manager</h1><p>Phase 2 foundation installed. Content API and permissions are ready for dashboard integration.</p>';
        echo '<p><strong>API:</strong> <code>' . esc_html(rest_url(self::REST_NAMESPACE . '/content')) . '</code></p>';
        echo '<p>The original prototype dashboard is not yet connected. Do not use this as a production editor.</p></div>';
    }
}
Salon_Website_Manager::init();
