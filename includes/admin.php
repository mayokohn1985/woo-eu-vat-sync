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

    if (!current_user_can('manage_woocommerce')) {
        return;
    }

    if (isset($_POST['wevs_run'])) {
        check_admin_referer('wevs_run');

        wevs_update_all_vat_rates();

        wp_redirect(admin_url('admin.php?page=wevs&updated=1'));
        exit;
    }

    if (isset($_POST['wevs_save'])) {
        check_admin_referer('wevs_settings');

        $enabled  = isset($_POST['wevs_enabled']) ? 1 : 0;
        $interval = isset($_POST['wevs_interval']) ? sanitize_text_field($_POST['wevs_interval']) : 'weekly';

        if (!in_array($interval, ['daily', 'weekly', 'monthly'], true)) {
            $interval = 'weekly';
        }

        update_option('wevs_enabled', $enabled);
        update_option('wevs_interval', $interval);

        wevs_clear_event();

        if ($enabled) {
            wevs_schedule_event();
        }

        wp_redirect(admin_url('admin.php?page=wevs&saved=1'));
        exit;
    }

    if (isset($_GET['updated'])) {
        echo '<div class="updated"><p>VAT updated.</p></div>';
    }

    if (isset($_GET['saved'])) {
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $last_run = get_option('wevs_last_run');
    $count    = get_option('wevs_last_count');
    $enabled  = (int) get_option('wevs_enabled', 1);
    $interval = get_option('wevs_interval', 'weekly');

    ?>
    <div class="wrap">
        <h1>Woo EU VAT Sync</h1>

        <h2>Status</h2>

        <p><strong>Last run:</strong> <?php echo esc_html($last_run ?: 'Never'); ?></p>
        <p><strong>Updated rates:</strong> <?php echo esc_html($count ?: 0); ?></p>
        <p><strong>Automatic updates:</strong> <?php echo $enabled ? 'Enabled' : 'Disabled'; ?></p>
        <p><strong>Interval:</strong> <?php echo esc_html($interval); ?></p>

        <hr>

        <h2>Manual update</h2>

        <form method="post">
            <?php wp_nonce_field('wevs_run'); ?>
            <p>
                <button class="button button-primary" name="wevs_run">
                    Update VAT Rates
                </button>
            </p>
        </form>

        <hr>

        <h2>Automatic updates</h2>

        <form method="post">
            <?php wp_nonce_field('wevs_settings'); ?>

            <p>
                <label>
                    <input type="checkbox" name="wevs_enabled" value="1" <?php checked($enabled, 1); ?>>
                    Enable automatic updates
                </label>
            </p>

            <p>
                <label for="wevs_interval">Update interval:</label><br>
                <select name="wevs_interval" id="wevs_interval">
                    <option value="daily" <?php selected($interval, 'daily'); ?>>Daily</option>
                    <option value="weekly" <?php selected($interval, 'weekly'); ?>>Weekly</option>
                    <option value="monthly" <?php selected($interval, 'monthly'); ?>>Monthly</option>
                </select>
            </p>

            <p>
                <button class="button button-secondary" name="wevs_save">
                    Save settings
                </button>
            </p>
        </form>
    </div>
    <?php
}
