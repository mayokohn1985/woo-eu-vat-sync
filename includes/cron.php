<?php

add_filter('cron_schedules', function ($schedules) {
    $schedules['weekly'] = [
        'interval' => 7 * DAY_IN_SECONDS,
        'display'  => 'Once Weekly'
    ];

    $schedules['monthly'] = [
        'interval' => 30 * DAY_IN_SECONDS,
        'display'  => 'Once Monthly'
    ];

    return $schedules;
});

function wevs_schedule_event() {
    if (!get_option('wevs_enabled')) {
        return;
    }

    $interval = get_option('wevs_interval', 'weekly');

    if (!wp_next_scheduled('wevs_cron_event')) {
        wp_schedule_event(time(), $interval, 'wevs_cron_event');
    }
}

function wevs_clear_event() {
    $timestamp = wp_next_scheduled('wevs_cron_event');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'wevs_cron_event');
    }
}

add_action('wevs_cron_event', function () {
    wevs_update_all_vat_rates();
});
