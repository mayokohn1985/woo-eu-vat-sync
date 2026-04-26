<?php
/**
 * Plugin Name: Woo EU VAT Sync
 * Description: Simple EU VAT rate updater for WooCommerce
 * Version: 0.1.0
 * Author: Marián Kohn
 * Author URI: https://mayokohn.com
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/vat-data.php';
require_once plugin_dir_path(__FILE__) . 'includes/updater.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin.php';
