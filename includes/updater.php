<?php

function wevs_log($message) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[WEVS] ' . $message);
    }
}

function wevs_update_all_vat_rates() {

    $rates = wevs_get_vat_rates();
    global $wpdb;

    $table = $wpdb->prefix . 'woocommerce_tax_rates';

    $updated = 0;

    foreach ($rates as $country => $rate) {

$existing = $wpdb->get_var($wpdb->prepare(
    "SELECT tax_rate_id FROM $table 
     WHERE tax_rate_country = %s 
     AND tax_rate_name = %s",
    $country,
    'VAT'
));

if ($existing) {

    $wpdb->update(
        $table,
        ['tax_rate' => $rate],
        ['tax_rate_id' => $existing],
        ['%f'],
        ['%d']
    );

    wevs_log("UPDATED $country → $rate");

} else {

    $wpdb->insert(
        $table,
        [
            'tax_rate_country'  => $country,
            'tax_rate'          => $rate,
            'tax_rate_name'     => 'VAT',
            'tax_rate_priority' => 1,
            'tax_rate_compound' => 0,
            'tax_rate_shipping' => 1,
            'tax_rate_order'    => 0,
            'tax_rate_class'    => '',
        ]
    );

    wevs_log("INSERTED $country → $rate");
}

    }

    update_option('wevs_last_run', current_time('mysql'));
    update_option('wevs_last_count', $updated);
}
