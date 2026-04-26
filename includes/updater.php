<?php

function wevs_update_all_vat_rates() {

    $rates = wevs_get_vat_rates();
    global $wpdb;

    $table = $wpdb->prefix . 'woocommerce_tax_rates';

    $updated = 0;

    foreach ($rates as $country => $rate) {

        $result = $wpdb->update(
            $table,
            ['tax_rate' => $rate],
            [
                'tax_rate_country' => $country,
                'tax_rate_name' => 'VAT'
            ],
            ['%f'],
            ['%s', '%s']
        );

        if ($result !== false) {
            $updated++;
        }
    }

    update_option('wevs_last_run', current_time('mysql'));
    update_option('wevs_last_count', $updated);
}
