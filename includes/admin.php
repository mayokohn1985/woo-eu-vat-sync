<?php

add_action('admin_menu', function () {
    add_submenu_page(
        'woocommerce',
        'EU VAT Sync',
        'EU VAT Sync',
        'manage_woocommerce',
        'wevs',
        'wevs_admin_page'
    );
});

function wevs_admin_page() {

    if (isset($_POST['wevs_run'])) {
        check_admin_referer('wevs_run');

        wevs_update_all_vat_rates();

        echo '<div class="updated"><p>VAT updated.</p></div>';
    }

    $last_run = get_option('wevs_last_run');
    $count = get_option('wevs_last_count');

    ?>
    <div class="wrap">
        <h1>Woo EU VAT Sync</h1>

        <p><strong>Last run:</strong> <?= esc_html($last_run ?: 'Never'); ?></p>
        <p><strong>Updated rates:</strong> <?= esc_html($count ?: 0); ?></p>

        <form method="post">
            <?php wp_nonce_field('wevs_run'); ?>
            <button class="button button-primary" name="wevs_run">
                Update VAT Rates
            </button>
        </form>
    </div>
    <?php
}
