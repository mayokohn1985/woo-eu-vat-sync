<?php
/**
 * Plugin Name: Woo EU VAT Sync
 * Description: Simple EU VAT rate updater for WooCommerce
 * Version: 0.2.0
 * Author: Marián Kohn
 * Author URI: https://mayokohn.sk
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/vat-data.php';
require_once plugin_dir_path(__FILE__) . 'includes/updater.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/cron.php';

register_activation_hook(__FILE__, function () {
    add_option('wevs_enabled', 1);
    add_option('wevs_interval', 'weekly');
    wevs_schedule_event();
});

register_deactivation_hook(__FILE__, function () {
    wevs_clear_event();
});
