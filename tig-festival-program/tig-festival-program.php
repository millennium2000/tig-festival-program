<?php
/**
 * Plugin Name: TIG Festival Program
 * Description: Festival and family day program schedule shortcode.
 * Version: 0.7.5
 * Author: TIG
 * Text Domain: tig-festival-program
 */

if (!defined('ABSPATH')) {
    exit;
}

define('TIG_FESTIVAL_PROGRAM_VERSION', '0.7.5');
define('TIG_FESTIVAL_PROGRAM_PATH', plugin_dir_path(__FILE__));
define('TIG_FESTIVAL_PROGRAM_URL', plugin_dir_url(__FILE__));
define('TIG_FESTIVAL_PROGRAM_OPTION', 'tig_festival_program_schedule');
define('TIG_FESTIVAL_PROGRAM_VENUES_OPTION', 'tig_festival_program_venues');
define('TIG_FESTIVAL_PROGRAM_TAGS_OPTION', 'tig_festival_program_tags');
define('TIG_FESTIVAL_PROGRAM_DAYS_OPTION', 'tig_festival_program_days');

function tig_festival_program_get_default_venues(): array
{
    return [
        ['id' => 'main_stage',   'label' => 'FÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂszÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­npad',    'color' => '#2b72b8', 'text_color' => '#ffffff'],
        ['id' => 'side_stage',   'label' => 'MellÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©kszÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­npad','color' => '#5f96cf', 'text_color' => '#ffffff'],
        ['id' => 'workshop',     'label' => 'Workshop',     'color' => '#f2e799', 'text_color' => '#0f2133'],
        ['id' => 'kids_zone',    'label' => 'GyerekzÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ³na',   'color' => '#ffd200', 'text_color' => '#0f2133'],
        ['id' => 'food_court',   'label' => 'ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂtkezÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©si tÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©r', 'color' => '#c9d7e6', 'text_color' => '#0f2133'],
    ];
}
function tig_festival_program_get_venues(): array
{
    $venues = get_option(TIG_FESTIVAL_PROGRAM_VENUES_OPTION);

    if (!is_array($venues) || empty($venues)) {
        $venues = tig_festival_program_get_default_venues();
    }

    $indexed = [];

    foreach ($venues as $venue) {
        if (!is_array($venue) || empty($venue['id']) || empty($venue['label'])) {
            continue;
        }

        $id = sanitize_key($venue['id']);
        $indexed[$id] = [
            'id' => $id,
            'label' => sanitize_text_field($venue['label']),
            'color' => sanitize_hex_color($venue['color'] ?? '') ?: '#eef3f7',
            'text_color' => sanitize_hex_color($venue['text_color'] ?? '') ?: '#0f2133',
        ];
    }

    if (empty($indexed)) {
        foreach (tig_festival_program_get_default_venues() as $venue) {
            $indexed[$venue['id']] = $venue;
        }
    }

    return $indexed;
}

function tig_festival_program_get_default_program_types(): array
{
    return [
        ['id' => 'main',        'label' => 'FÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ program'],
        ['id' => 'workshop',    'label' => 'Workshop'],
        ['id' => 'continuous',  'label' => 'Folyamatos program'],
    ];
}
function tig_festival_program_get_default_days(): array
{
    return [
        [
            'date'     => '',
            'label'    => '1. nap',
            'schedule' => tig_festival_program_get_default_schedule(),
        ],
    ];
}

function tig_festival_program_get_days(): array
{
    $days = get_option(TIG_FESTIVAL_PROGRAM_DAYS_OPTION);

    if (!is_array($days) || empty($days)) {
        $legacy = get_option(TIG_FESTIVAL_PROGRAM_OPTION);
        if (is_array($legacy) && !empty($legacy)) {
            $days = [['date' => '', 'label' => '1. nap', 'schedule' => $legacy]];
            update_option(TIG_FESTIVAL_PROGRAM_DAYS_OPTION, $days);
        } else {
            return tig_festival_program_get_default_days();
        }
    }

    return $days;
}

function tig_festival_program_get_program_types(): array
{
    $types = get_option(TIG_FESTIVAL_PROGRAM_TAGS_OPTION, false);

    if ($types === false) {
        $types = tig_festival_program_get_default_program_types();
    }

    $indexed = [];

    foreach ($types as $type) {
        if (!is_array($type) || empty($type['id']) || empty($type['label'])) {
            continue;
        }

        $id = sanitize_key($type['id']);
        $indexed[$id] = [
            'id' => $id,
            'label' => sanitize_text_field($type['label']),
        ];
    }

    return $indexed;
}

function tig_festival_program_get_default_schedule(): array
{
    return [
        ['time' => '09:00', 'note' => 'KapunyitÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡s', 'events' => []],
        ['time' => '09:30', 'note' => '', 'events' => [
            ['venue' => 'kids_zone',  'title' => 'Gyermekprogramok kezdete', 'type' => 'continuous'],
            ['venue' => 'food_court', 'title' => 'Reggeli bÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼fÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ© nyitÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡s',      'type' => 'continuous'],
        ]],
        ['time' => '10:00', 'note' => '', 'events' => [
            ['venue' => 'main_stage', 'title' => 'MegnyitÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ³ ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼nnepsÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©g',        'type' => 'main'],
            ['venue' => 'workshop',   'title' => 'Workshop A ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¢ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ BemutatkozÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡s','type' => 'workshop'],
        ]],
        ['time' => '11:00', 'note' => '', 'events' => [
            ['venue' => 'main_stage', 'title' => 'VendÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©gelÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂadÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ³ I.',           'type' => 'main'],
            ['venue' => 'side_stage', 'title' => 'Akusztikus koncert',        'type' => 'main'],
            ['venue' => 'workshop',   'title' => 'Workshop B ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¢ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ CsapatjÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡tÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©k', 'type' => 'workshop'],
        ]],
        ['time' => '12:30', 'note' => 'EbÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©dszÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼net', 'events' => [
            ['venue' => 'food_court', 'title' => 'EbÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©d', 'type' => 'continuous'],
        ]],
        ['time' => '14:00', 'note' => '', 'events' => [
            ['venue' => 'main_stage', 'title' => 'FÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ program ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¢ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ Csapatverseny',  'type' => 'main'],
            ['venue' => 'side_stage', 'title' => 'InteraktÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­v bemutatÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ³',         'type' => 'main'],
            ['venue' => 'kids_zone',  'title' => 'KÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©zmÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ±ves foglalkozÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡s',        'type' => 'workshop'],
        ]],
        ['time' => '16:00', 'note' => '', 'events' => [
            ['venue' => 'main_stage', 'title' => 'DÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­jÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡tadÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ³ ceremÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ³nia',          'type' => 'main'],
        ]],
        ['time' => '17:00', 'note' => '', 'events' => [
            ['venue' => 'main_stage', 'title' => 'ZÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡rÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ³ koncert',                'type' => 'main'],
            ['venue' => 'food_court', 'title' => 'Esti bÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼fÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©',                   'type' => 'continuous'],
        ]],
        ['time' => '19:00', 'note' => 'RendezvÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©ny vÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©ge', 'events' => []],
    ];
}
function tig_festival_program_get_schedule(): array
{
    $schedule = get_option(TIG_FESTIVAL_PROGRAM_OPTION);

    if (!is_array($schedule) || empty($schedule)) {
        return tig_festival_program_get_default_schedule();
    }

    return $schedule;
}

function tig_festival_program_time_to_minutes(string $time): ?int
{
    if (!preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $time, $matches)) {
        return null;
    }

    return ((int) $matches[1] * 60) + (int) $matches[2];
}

function tig_festival_program_sanitize_venues(array $raw_venues): array
{
    $venues = [];

    foreach ($raw_venues as $raw_venue) {
        if (!is_array($raw_venue)) {
            continue;
        }

        $label = isset($raw_venue['label']) ? sanitize_text_field(wp_unslash($raw_venue['label'])) : '';

        if ($label === '') {
            continue;
        }

        $id = isset($raw_venue['id']) ? sanitize_key(wp_unslash($raw_venue['id'])) : '';
        $id = $id !== '' ? $id : sanitize_title($label);
        $id = $id !== '' ? $id : 'venue-' . wp_generate_password(6, false, false);

        $original_id = $id;
        $suffix = 2;

        while (isset($venues[$id])) {
            $id = $original_id . '-' . $suffix;
            $suffix++;
        }

        $venues[$id] = [
            'id' => $id,
            'label' => $label,
            'color' => sanitize_hex_color($raw_venue['color'] ?? '') ?: '#eef3f7',
            'text_color' => sanitize_hex_color($raw_venue['text_color'] ?? '') ?: '#0f2133',
        ];
    }

    return array_values($venues);
}

function tig_festival_program_sanitize_program_types(array $raw_types): array
{
    $types = [];

    foreach ($raw_types as $raw_type) {
        if (!is_array($raw_type)) {
            continue;
        }

        $label = isset($raw_type['label']) ? sanitize_text_field(wp_unslash($raw_type['label'])) : '';

        if ($label === '') {
            continue;
        }

        $id = isset($raw_type['id']) ? sanitize_key(wp_unslash($raw_type['id'])) : '';
        $id = $id !== '' ? $id : sanitize_title($label);
        $id = $id !== '' ? $id : 'tag-' . wp_generate_password(6, false, false);

        $original_id = $id;
        $suffix = 2;

        while (isset($types[$id])) {
            $id = $original_id . '-' . $suffix;
            $suffix++;
        }

        $types[$id] = [
            'id' => $id,
            'label' => $label,
        ];
    }

    return array_values($types);
}

function tig_festival_program_sanitize_schedule(array $raw_schedule, array $venues, array $types): array
{
    $allowed_venues = array_keys($venues);
    $allowed_types = array_keys($types);
    $schedule = [];

    foreach ($raw_schedule as $raw_row) {
        if (!is_array($raw_row)) {
            continue;
        }

        $time = isset($raw_row['time']) ? sanitize_text_field(wp_unslash($raw_row['time'])) : '';
        $note = isset($raw_row['note']) ? sanitize_text_field(wp_unslash($raw_row['note'])) : '';

        if ($time === '' && $note === '' && empty($raw_row['events'])) {
            continue;
        }

        $events = [];

        if (!empty($raw_row['events']) && is_array($raw_row['events'])) {
            foreach ($raw_row['events'] as $raw_event) {
                if (!is_array($raw_event)) {
                    continue;
                }

                $venue = isset($raw_event['venue']) ? sanitize_key(wp_unslash($raw_event['venue'])) : '';
                $title = isset($raw_event['title']) ? sanitize_text_field(wp_unslash($raw_event['title'])) : '';
                $type = isset($raw_event['type']) ? sanitize_key(wp_unslash($raw_event['type'])) : '';
                $end_time = isset($raw_event['end_time']) ? sanitize_text_field(wp_unslash($raw_event['end_time'])) : '';
            $description = isset($raw_event['description']) ? sanitize_textarea_field(wp_unslash($raw_event['description'])) : '';
            $link        = isset($raw_event['link'])        ? esc_url_raw(wp_unslash($raw_event['link']))        : '';
            $image_url   = isset($raw_event['image_url'])   ? esc_url_raw(wp_unslash($raw_event['image_url']))   : '';

                if ($title === '' || !in_array($venue, $allowed_venues, true)) {
                    continue;
                }

                if ($type !== '' && !in_array($type, $allowed_types, true)) {
                    $type = '';
                }

                $start_minutes = tig_festival_program_time_to_minutes($time);
                $end_minutes = tig_festival_program_time_to_minutes($end_time);

                if ($start_minutes === null || $end_minutes === null || $end_minutes <= $start_minutes) {
                    $end_time = '';
                }

                $events[] = [
                    'venue' => $venue,
                    'title' => $title,
                    'type' => $type,
                    'end_time' => $end_time,
                    'description' => $description,
                    'link'        => $link,
                    'image_url'   => $image_url,
                ];
            }
        }

        $schedule[] = [
            'time' => $time,
            'note' => $note,
            'events' => $events,
        ];
    }

    usort($schedule, static function (array $a, array $b): int {
        return strcmp($a['time'], $b['time']);
    });

    return $schedule;
}

function tig_festival_program_enqueue_assets(): void
{
    // Ha mÃ¡r be van tÃ¶ltve, nem hÃ­vjuk meg Ãºjra
    if (wp_style_is('tig-festival-program', 'enqueued')) {
        return;
    }

    wp_enqueue_style(
        'tig-festival-program',
        TIG_FESTIVAL_PROGRAM_URL . 'assets/css/tig-festival-program.css',
        [],
        TIG_FESTIVAL_PROGRAM_VERSION
    );

    wp_enqueue_script(
        'tig-festival-program',
        TIG_FESTIVAL_PROGRAM_URL . 'assets/js/tig-festival-program.js',
        [],
        TIG_FESTIVAL_PROGRAM_VERSION,
        true
    );
}

function tig_festival_program_shortcode(array $atts = []): string
{
    $atts = shortcode_atts([
        'title'    => '',
        'subtitle' => '',
        'date'         => '',
        'display_date' => '',
    ], $atts, 'tig_program');

    tig_festival_program_enqueue_assets();

    $template_candidates = [
        TIG_FESTIVAL_PROGRAM_PATH . 'templates/program.php',
        __DIR__ . '/templates/program.php',
        dirname(__FILE__) . '/templates/program.php',
    ];

    $template = '';

    foreach ($template_candidates as $template_candidate) {
        if (file_exists($template_candidate)) {
            $template = $template_candidate;
            break;
        }
    }

    if ($template === '') {
        return tig_festival_program_render_program_inline((string) $atts['title'], (string) $atts['subtitle'], (string) $atts['date'], (string) $atts['display_date']);
    }

    ob_start();
        // Shortcode attribÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂºtumok ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡tadÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡sa a template-nek
include $template;
    $html = trim((string) ob_get_clean());

    if ($html === '' && current_user_can('manage_options')) {
        return '<div class="tig-program tig-program-error">DG Program: a shortcode lefutott, de nem kÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©szÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼lt megjelenÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­thetÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ HTML.</div>';
    }

    return $html;
}

add_shortcode('dg_program', 'tig_festival_program_shortcode');
add_shortcode('tig_festival_program', 'tig_festival_program_shortcode');
add_shortcode('festival_program', 'tig_festival_program_shortcode');

function tig_festival_program_render_program_inline(string $title = '', string $subtitle = '', string $filter_date = '', string $display_date = ''): string
{
    $venues   = tig_festival_program_get_venues();
    $all_days = tig_festival_program_get_days();

    if ($filter_date !== '') {
        $all_days = array_values(array_filter($all_days, function (array $d) use ($filter_date): bool {
            return ($d['date'] ?? '') === $filter_date;
        }));
    }

    $schedule = !empty($all_days) ? ($all_days[0]['schedule'] ?? []) : [];
    $program_types = tig_festival_program_get_program_types();
    $schedule_times = array_map(static function (array $row): string {
        return (string) ($row['time'] ?? '');
    }, $schedule);

    $get_event_rowspan = static function (array $event, int $row_index) use ($schedule_times): int {
        $start_time = $schedule_times[$row_index] ?? '';
        $end_time = (string) ($event['end_time'] ?? '');
        $start_minutes = tig_festival_program_time_to_minutes($start_time);
        $end_minutes = tig_festival_program_time_to_minutes($end_time);

        if ($start_minutes === null || $end_minutes === null || $end_minutes <= $start_minutes) {
            return 1;
        }

        $span = 1;
        $row_count = count($schedule_times);

        for ($next_index = $row_index + 1; $next_index < $row_count; $next_index++) {
            $next_minutes = tig_festival_program_time_to_minutes($schedule_times[$next_index]);

            if ($next_minutes === null || $next_minutes >= $end_minutes) {
                break;
            }

            $span++;
        }

        return $span;
    };

        $days = $all_days;
    // FejlÃÂÃÂÃÂÃÂ©c dÃÂÃÂÃÂÃÂ¡tum
    if ($display_date === '' && $filter_date !== '') { $display_date = $filter_date; }
    if ($display_date === '' && !empty($all_days)) { $display_date = (string) ($all_days[0]['date'] ?? ''); }
    $formatted_date = '';
    if ($display_date !== '') {
        $ts = strtotime($display_date);
        if ($ts !== false) { $formatted_date = date_i18n('Y. F j., l', $ts); }
    }
        ob_start();
    ?>
    <div class="tig-program">
      <div class="tig-program-head">
        <div class="tig-program-title"><?php echo esc_html($title); ?></div>
        <div class="tig-program-subtitle"><?php echo esc_html($subtitle); ?></div>
      </div>

      <div class="tig-program-legend">
        <?php foreach ($venues as $venue) : ?>
          <div
            class="tig-legend-item tig-venue-chip tig-has-venue-tooltip"
            style="--tig-venue-bg: <?php echo esc_attr($venue['color']); ?>; --tig-venue-fg: <?php echo esc_attr($venue['text_color']); ?>;"
            data-venue-tooltip="<?php echo esc_attr($venue['label']); ?>"
            tabindex="0"
          >
            <?php echo esc_html($venue['label']); ?>
          </div>
        <?php endforeach; ?>
      </div>

      <?php if (!empty($program_types)) : ?>
        <div class="tig-program-type-legend">
          <?php foreach ($program_types as $type_key => $type) : ?>
            <span class="tig-program-type tig-program-type-<?php echo esc_attr($type_key); ?>">
              <?php echo esc_html($type['label']); ?>
            </span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <div class="tig-desktop-view">
        <div class="tig-program-table-wrap">
          <table class="tig-program-table tig-program-table-dynamic">
            <thead>
              <tr>
                <th class="tig-program-time-head">IdÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂpont</th>
                <?php foreach ($venues as $venue) : ?>
                  <th><?php echo esc_html($venue['label']); ?></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php $rowspan_blocks = []; ?>
              <?php foreach ($schedule as $row_index => $row) : ?>
                <?php
                $events_by_venue = [];

                foreach (($row['events'] ?? []) as $event) {
                    if (empty($event['venue']) || empty($venues[$event['venue']])) {
                        continue;
                    }

                    $events_by_venue[$event['venue']][] = $event;
                }
                ?>
                <tr>
                  <td class="time">
                    <?php echo esc_html($row['time'] ?? ''); ?>
                    <?php if (!empty($row['note'])) : ?>
                      <span class="tig-time-note"><?php echo esc_html($row['note']); ?></span>
                    <?php endif; ?>
                  </td>

                  <?php foreach ($venues as $venue_key => $venue) : ?>
                    <?php if (!empty($rowspan_blocks[$venue_key])) : ?>
                      <?php $rowspan_blocks[$venue_key]--; ?>
                      <?php continue; ?>
                    <?php endif; ?>
                    <?php $venue_events = $events_by_venue[$venue_key] ?? []; ?>
                    <?php
                    $rowspan = 1;

                    foreach ($venue_events as $event) {
                        $rowspan = max($rowspan, $get_event_rowspan($event, (int) $row_index));
                    }

                    if ($rowspan > 1) {
                        $rowspan_blocks[$venue_key] = $rowspan - 1;
                    }
                    ?>
                    <td
                      class="<?php echo esc_attr(!empty($venue_events) ? 'tig-venue-cell tig-has-venue-tooltip' : 'muted empty'); ?>"
                      <?php if ($rowspan > 1) : ?>
                        rowspan="<?php echo esc_attr((string) $rowspan); ?>"
                      <?php endif; ?>
                      <?php if (!empty($venue_events)) : ?>
                        style="--tig-venue-bg: <?php echo esc_attr($venue['color']); ?>; --tig-venue-fg: <?php echo esc_attr($venue['text_color']); ?>;"
                        data-venue-tooltip="<?php echo esc_attr($venue['label']); ?>"
                        tabindex="0"
                      <?php endif; ?>
                    >
                      <?php foreach ($venue_events as $event) : ?>
                        <?php $type = $event['type'] ?? ''; ?>
                        <div class="tig-program-event">
                          <span class="tig-program-event-title"><?php echo esc_html($event['title']); ?></span>
                          <?php if (!empty($event['end_time'])) : ?>
                            <span class="tig-program-time-range"><?php echo esc_html(($row['time'] ?? '') . 'ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¢ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ' . $event['end_time']); ?></span>
                          <?php endif; ?>
                          <?php if ($type !== '' && !empty($program_types[$type])) : ?>
                            <span class="tig-program-type tig-program-type-<?php echo esc_attr($type); ?>">
                              <?php echo esc_html($program_types[$type]['label']); ?>
                            </span>
                          <?php endif; ?>
                        </div>
                      <?php endforeach; ?>
                    </td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="tig-mobile-view">
        <?php foreach ($schedule as $row) : ?>
          <?php
          $events_by_venue = [];

          foreach (($row['events'] ?? []) as $event) {
              if (empty($event['venue']) || empty($venues[$event['venue']])) {
                  continue;
              }

              $events_by_venue[$event['venue']][] = $event;
          }
          ?>
          <div class="tig-mobile-card" data-time="<?php echo esc_attr($row['time'] ?? ''); ?>">
            <div class="tig-mobile-time">
              <?php echo esc_html($row['time'] ?? ''); ?>
              <?php if (!empty($row['note'])) : ?>
                <span class="tig-mobile-time-note"><?php echo esc_html($row['note']); ?></span>
              <?php endif; ?>
            </div>

            <?php foreach ($venues as $venue_key => $venue) : ?>
              <?php if (empty($events_by_venue[$venue_key])) : ?>
                <?php continue; ?>
              <?php endif; ?>

              <div class="tig-mobile-section">
                <div class="tig-mobile-label"><?php echo esc_html($venue['label']); ?></div>
                <div class="tig-tag-row">
                  <?php foreach ($events_by_venue[$venue_key] as $event) : ?>
                    <?php $type = $event['type'] ?? ''; ?>
                    <div
                      class="tig-tag tig-venue-chip"
                      style="--tig-venue-bg: <?php echo esc_attr($venue['color']); ?>; --tig-venue-fg: <?php echo esc_attr($venue['text_color']); ?>;"
                    >
                      <span class="tig-program-event-title"><?php echo esc_html($event['title']); ?></span>
                      <?php if (!empty($event['end_time'])) : ?>
                        <span class="tig-program-time-range"><?php echo esc_html(($row['time'] ?? '') . 'ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¢ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ' . $event['end_time']); ?></span>
                      <?php endif; ?>
                      <?php if ($type !== '' && !empty($program_types[$type])) : ?>
                        <span class="tig-program-type tig-program-type-<?php echo esc_attr($type); ?>">
                          <?php echo esc_html($program_types[$type]['label']); ?>
                        </span>
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php
    return trim((string) ob_get_clean());
}

function tig_festival_program_admin_menu(): void
{
    add_menu_page(
        'DG Program',
        'DG Program',
        'edit_posts',
        'tig-festival-program',
        'tig_festival_program_render_admin_page',
        'dashicons-calendar-alt',
        26
    );
}


// CSS/JS csak akkor tÃ¶ltÅdik be, ha az oldalon van tig_program shortcode
add_action('wp_enqueue_scripts', 'tig_festival_program_maybe_enqueue_assets');

function tig_festival_program_maybe_enqueue_assets(): void
{
    global $post;

    $shortcodes = ['tig_program', 'dg_program', 'festival_program'];
    $needs_assets = false;

    if ($post instanceof WP_Post) {
        foreach ($shortcodes as $tag) {
            if (has_shortcode($post->post_content, $tag)) {
                $needs_assets = true;
                break;
            }
        }
    }

    // Widget terÃ¼leteken Ã©s Gutenberg blokkokban is ellenÅrzÃ¼nk
    if (!$needs_assets) {
        $needs_assets = (bool) apply_filters('tig_festival_program_force_enqueue', false);
    }

    if ($needs_assets) {
        tig_festival_program_enqueue_assets();
    }
}

add_action('admin_menu', 'tig_festival_program_admin_menu');

function tig_festival_program_handle_admin_save(): void
{
    if (!current_user_can('edit_posts')) {
        wp_die(esc_html__('Nincs jogosultsÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡god a program szerkesztÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©sÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©hez.', 'tig-festival-program'));
    }

    check_admin_referer('tig_festival_program_save', 'tig_festival_program_nonce');

    $raw_venues = isset($_POST['dg_venues']) && is_array($_POST['dg_venues']) ? $_POST['dg_venues'] : [];
    $sanitized_venues = tig_festival_program_sanitize_venues($raw_venues);

    if (empty($sanitized_venues)) {
        $sanitized_venues = tig_festival_program_get_default_venues();
    }

    update_option(TIG_FESTIVAL_PROGRAM_VENUES_OPTION, $sanitized_venues, false);

    $raw_types = isset($_POST['dg_program_types']) && is_array($_POST['dg_program_types']) ? $_POST['dg_program_types'] : [];
    $sanitized_types = tig_festival_program_sanitize_program_types($raw_types);

    update_option(TIG_FESTIVAL_PROGRAM_TAGS_OPTION, $sanitized_types, false);

    $venues = [];

    foreach ($sanitized_venues as $venue) {
        $venues[$venue['id']] = $venue;
    }

    $types = [];

    foreach ($sanitized_types as $type) {
        $types[$type['id']] = $type;
    }

    $raw_schedule = isset($_POST['dg_schedule']) && is_array($_POST['dg_schedule']) ? $_POST['dg_schedule'] : [];
    update_option(TIG_FESTIVAL_PROGRAM_OPTION, tig_festival_program_sanitize_schedule($raw_schedule, $venues, $types), false);

    // Napos struktÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂºra frissÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­tÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©se
    $raw_days_post = isset($_POST['tig_days']) && is_array($_POST['tig_days']) ? $_POST['tig_days'] : [];
    if (!empty($raw_days_post)) {
        $sanitized_days = [];
        $allowed_venues_keys = array_keys($sanitized_venues ?: tig_festival_program_get_venues());
        $allowed_types_keys  = array_keys($sanitized_types  ?: tig_festival_program_get_program_types());
        foreach ($raw_days_post as $raw_day) {
            if (!is_array($raw_day)) continue;
            $day_date  = isset($raw_day['date'])  ? sanitize_text_field(wp_unslash((string) $raw_day['date']))  : '';
            $day_label = isset($raw_day['label']) ? sanitize_text_field(wp_unslash((string) $raw_day['label'])) : '';
            $day_sched = isset($raw_day['schedule']) && is_array($raw_day['schedule'])
                ? tig_festival_program_sanitize_schedule($raw_day['schedule'], $allowed_venues_keys, $allowed_types_keys)
                : [];
            $sanitized_days[] = ['date' => $day_date, 'label' => $day_label, 'schedule' => $day_sched];
        }
        if (!empty($sanitized_days)) {
            update_option(TIG_FESTIVAL_PROGRAM_DAYS_OPTION, $sanitized_days, false);
        }
    }

    wp_safe_redirect(add_query_arg([
        'page' => 'tig-festival-program',
        'updated' => '1',
    ], admin_url('admin.php')));
    exit;
}

add_action('admin_post_tig_festival_program_save', 'tig_festival_program_handle_admin_save');
add_action('admin_post_tig_festival_program_reset', 'tig_festival_program_handle_admin_reset');
add_action('wp_ajax_tig_festival_program_export', 'tig_festival_program_handle_export');
add_action('admin_post_tig_festival_program_import', 'tig_festival_program_handle_import');

add_action('rest_api_init', 'tig_festival_program_register_rest_routes');


function tig_festival_program_register_rest_routes(): void
{
    $ns = 'tig-festival/v1';

    register_rest_route($ns, '/program', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'tig_festival_program_rest_get_program',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route($ns, '/days', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'tig_festival_program_rest_get_days',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route($ns, '/venues', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'tig_festival_program_rest_get_venues',
        'permission_callback' => '__return_true',
    ]);
}

function tig_festival_program_rest_get_program(): WP_REST_Response
{
    $data = [
        'version'   => TIG_FESTIVAL_PROGRAM_VERSION,
        'generated' => gmdate('c'),
        'days'      => tig_festival_program_get_days(),
        'venues'    => array_values(tig_festival_program_get_venues()),
        'types'     => array_values(tig_festival_program_get_program_types()),
    ];
    $response = new WP_REST_Response($data, 200);
    $response->header('Cache-Control', 'public, max-age=60');
    return $response;
}

function tig_festival_program_rest_get_days(WP_REST_Request $request): WP_REST_Response
{
    $filter = sanitize_text_field((string) $request->get_param('date'));
    $days = tig_festival_program_get_days();
    if ($filter !== '') {
        $days = array_values(array_filter($days, function (array $d) use ($filter): bool {
            return ($d['date'] ?? '') === $filter;
        }));
    }
    $response = new WP_REST_Response(['days' => $days], 200);
    $response->header('Cache-Control', 'public, max-age=60');
    return $response;
}

function tig_festival_program_rest_get_venues(): WP_REST_Response
{
    $venues = array_values(tig_festival_program_get_venues());
    return new WP_REST_Response(['venues' => $venues], 200);
}

function tig_festival_program_handle_admin_reset(): void
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Nincs jogosultsÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡god ehhez a mÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ±velethez.', 'tig-festival-program'));
    }

    check_admin_referer('tig_festival_program_reset', 'tig_festival_program_reset_nonce');

    delete_option(TIG_FESTIVAL_PROGRAM_OPTION);
    delete_option(TIG_FESTIVAL_PROGRAM_DAYS_OPTION);
    delete_option(TIG_FESTIVAL_PROGRAM_VENUES_OPTION);
    delete_option(TIG_FESTIVAL_PROGRAM_TAGS_OPTION);

    wp_safe_redirect(add_query_arg([
        'page'    => 'tig-program',
        'updated' => '1',
        'reset'   => '1',
    ], admin_url('admin.php')));
    exit;
}
function tig_festival_program_handle_export(): void
{
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Nincs jogosultsÃÂÃÂ¡god.'], 403);
    }

    check_ajax_referer('tig_export_nonce', 'nonce');

    $export = [
        'version'  => TIG_FESTIVAL_PROGRAM_VERSION,
        'exported' => date('Y-m-d H:i:s'),
        'days'     => tig_festival_program_get_days(),
        'venues'   => tig_festival_program_get_venues(),
        'types'    => tig_festival_program_get_program_types(),
    ];

    wp_send_json_success($export);
}

function tig_festival_program_handle_import(): void
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Nincs jogosultsÃÂÃÂ¡god.', 'tig-festival-program'));
    }

    check_admin_referer('tig_festival_program_import', 'tig_festival_program_import_nonce');

    $raw = isset($_POST['tig_import_json']) ? wp_unslash((string) $_POST['tig_import_json']) : '';

    if (empty($raw)) {
        wp_safe_redirect(add_query_arg(['page' => 'tig-program', 'import_error' => 'empty'], admin_url('admin.php')));
        exit;
    }

    $data = json_decode($raw, true);

    if (!is_array($data)) {
        wp_safe_redirect(add_query_arg(['page' => 'tig-program', 'import_error' => 'invalid_json'], admin_url('admin.php')));
        exit;
    }

    // Napok importÃÂÃÂ¡lÃÂÃÂ¡sa
    if (!empty($data['days']) && is_array($data['days'])) {
        $allowed_venues = array_keys(tig_festival_program_get_venues());
        $allowed_types  = array_keys(tig_festival_program_get_program_types());
        $sanitized_days = [];
        foreach ($data['days'] as $day) {
            if (!is_array($day)) continue;
            $day_sched = isset($day['schedule']) && is_array($day['schedule'])
                ? tig_festival_program_sanitize_schedule($day['schedule'], $allowed_venues, $allowed_types)
                : [];
            $sanitized_days[] = [
                'date'     => sanitize_text_field((string) ($day['date']  ?? '')),
                'label'    => sanitize_text_field((string) ($day['label'] ?? '')),
                'schedule' => $day_sched,
            ];
        }
        update_option(TIG_FESTIVAL_PROGRAM_DAYS_OPTION, $sanitized_days, false);
    }

    // HelyszÃÂÃÂ­nek importÃÂÃÂ¡lÃÂÃÂ¡sa
    if (!empty($data['venues']) && is_array($data['venues'])) {
        update_option(TIG_FESTIVAL_PROGRAM_VENUES_OPTION, tig_festival_program_sanitize_venues($data['venues']), false);
    }

    // TÃÂÃÂ­pusok importÃÂÃÂ¡lÃÂÃÂ¡sa
    if (!empty($data['types']) && is_array($data['types'])) {
        update_option(TIG_FESTIVAL_PROGRAM_TAGS_OPTION, $data['types'], false);
    }

    wp_safe_redirect(add_query_arg(['page' => 'tig-program', 'updated' => '1', 'imported' => '1'], admin_url('admin.php')));
    exit;
}

function tig_festival_program_render_admin_page(): void
{
    if (!current_user_can('edit_posts')) {
        wp_die(esc_html__('Nincs jogosultsÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡god a program szerkesztÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©sÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©hez.', 'tig-festival-program'));
    }

    $venues = tig_festival_program_get_venues();
    $schedule = tig_festival_program_get_schedule();
    $types = tig_festival_program_get_program_types();
    ?>
    <div class="wrap tig-admin-wrap">
        <h1>DG Program szerkesztÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ</h1>

        <?php if (isset($_GET['reset']) && $_GET['reset'] === '1') : ?>
                <div class="notice notice-warning is-dismissible">
                    <p>ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂsszes adat tÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¶rÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¶lve. Az alapÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©rtelmezett adatok visszaÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡llnak elsÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ betÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¶ltÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©skor.</p>
                </div>
                <?php endif; ?>
                <?php if (isset($_GET['updated']) && $_GET['updated'] === '1' && !isset($_GET['reset'])) : ?>
            <div class="notice notice-success is-dismissible">
                <p>Program mentve.</p>
            </div>
        <?php endif; ?>
                <?php endif; ?>
                <?php if (isset($_GET['imported']) && $_GET['imported'] === '1') : ?>
                <div class="notice notice-success is-dismissible">
                    <p>Program sikeresen importÃÂÃÂ¡lva.</p>
                </div>
                <?php endif; ?>
                <?php if (isset($_GET['import_error'])) : ?>
                <div class="notice notice-error is-dismissible">
                    <p>Import hiba: <?php echo esc_html($_GET['import_error'] === 'invalid_json' ? 'ÃÂÃÂrvÃÂÃÂ©nytelen JSON fÃÂÃÂ¡jl.' : 'ÃÂÃÂres fÃÂÃÂ¡jl.'); ?></p>
                </div>
                <?php endif; ?>
                

        <p>Az elsÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ oszlop az idÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂpont, utÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡na a lent megadott helyszÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­nek jelennek meg oszlopkÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©nt. A tag-ek opcionÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡lisak, bÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂvÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­thetÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂk ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©s ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡tÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­rhatÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ³k. TÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¶bb idÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂsÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡von ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡t tartÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ³ programnÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡l add meg a vÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©ge idÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂpontot.</p>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="tig_festival_program_save">
            <?php wp_nonce_field('tig_festival_program_save', 'tig_festival_program_nonce'); ?>

            <div class="tig-admin-toolbar">
                <button type="button" class="button button-secondary" data-tig-add-venue>ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂj helyszÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­n</button>
                <button type="button" class="button button-secondary" data-tig-add-type>ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂj tag</button>
                <button type="button" class="button button-secondary" data-tig-add-row>ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂj idÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂpont</button>
                <button type="submit" class="button button-primary">Program mentÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©se</button>
            

                <?php wp_nonce_field('tig_festival_program_reset', 'tig_festival_program_reset_nonce'); ?>
                <button
                    type="button"
                    class="button button-link-delete tig-admin-reset-btn"
                    data-confirm="<?php echo esc_attr('Biztosan tÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¶rlÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¶d az ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¶sszes helyszÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­nt, programot ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©s beÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡llÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­tÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡st? Ez a mÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ±velet nem vonhatÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ³ vissza.'); ?>"
                    data-action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                    data-nonce-name="tig_festival_program_reset_nonce"
                    data-form-action="tig_festival_program_reset"
                >ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂsszes adat tÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¶rlÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©se</button>

                <?php
                // Export nonce
                $export_nonce = wp_create_nonce('tig_export_nonce');
                ?>

                <button
                    type="button"
                    class="button button-secondary tig-export-btn"
                    data-nonce="<?php echo esc_attr($export_nonce); ?>"
                    data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
                >&#8595; ExportÃÂÃÂ¡lÃÂÃÂ¡s (JSON)</button>

                <label class="button button-secondary tig-import-label" title="JSON fÃÂÃÂ¡jl importÃÂÃÂ¡lÃÂÃÂ¡sa">
                    &#8593; ImportÃÂÃÂ¡lÃÂÃÂ¡s (JSON)
                    <input type="file" class="tig-import-file" accept=".json" style="display:none;">
                </label>
                <?php wp_nonce_field('tig_festival_program_import', 'tig_festival_program_import_nonce'); ?>
                <input type="hidden" name="tig_import_json" class="tig-import-json-hidden" value="">
                <input type="hidden" name="import_trigger" class="tig-import-trigger" value="0"></div>

            <section class="tig-admin-panel">
                <h2>HelyszÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­nek</h2>
                <div class="tig-admin-venues" data-tig-venues>
                    <?php foreach ($venues as $venue_index => $venue) : ?>
                        <?php tig_festival_program_render_admin_venue((string) $venue_index, $venue); ?>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="tig-admin-panel">
                <h2>Tag-ek</h2>
                <div class="tig-admin-types" data-tig-types>
                    <?php foreach ($types as $type_index => $type) : ?>
                        <?php tig_festival_program_render_admin_type((string) $type_index, $type); ?>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="tig-admin-panel">
                <h2>IdÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂpontok ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©s programok</h2>
                <div class="tig-admin-schedule" data-tig-schedule>
                    <?php foreach ($schedule as $row_index => $row) : ?>
                        <?php tig_festival_program_render_admin_row((int) $row_index, $row, $venues, $types); ?>
                    <?php endforeach; ?>
                </div>
            </section>

            <div class="tig-admin-toolbar tig-admin-toolbar-bottom">
                <button type="button" class="button button-secondary" data-tig-add-venue>ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂj helyszÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­n</button>
                <button type="button" class="button button-secondary" data-tig-add-type>ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂj tag</button>
                <button type="button" class="button button-secondary" data-tig-add-row>ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂj idÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂpont</button>
                <button type="submit" class="button button-primary">Program mentÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©se</button>
            </div>
        </form>
    </div>

    <template id="tig-program-venue-template">
        <?php tig_festival_program_render_admin_venue('__venue_index__', ['id' => '', 'label' => '', 'color' => '#eef3f7', 'text_color' => '#0f2133']); ?>
    </template>

    <template id="tig-program-type-template">
        <?php tig_festival_program_render_admin_type('__type_index__', ['id' => '', 'label' => '']); ?>
    </template>

    <template id="tig-program-row-template">
        <?php tig_festival_program_render_admin_row('__row_index__', ['time' => '', 'note' => '', 'events' => []], $venues, $types); ?>
    </template>

    <template id="tig-program-event-template">
        <?php tig_festival_program_render_admin_event('__row_index__', '__event_index__', ['venue' => array_key_first($venues), 'title' => '', 'type' => '', 'end_time' => ''], $venues, $types); ?>
    </template>

    <style>
        .tig-admin-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            margin: 18px 0;
        }

        .tig-admin-toolbar-bottom {
            margin-top: 20px;
        }

        .tig-admin-panel {
            max-width: 1180px;
            margin: 0 0 18px;
            padding: 16px;
            border: 1px solid #dcdcde;
            background: #fff;
        }

        .tig-admin-panel h2 {
            margin-top: 0;
        }

        .tig-admin-venue,
        .tig-admin-type,
        .tig-admin-event {
            display: grid;
            grid-template-columns: minmax(180px, 1fr) 120px 120px auto;
            gap: 10px;
            align-items: end;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #dcdcde;
            background: #fbfbfb;
        }

        .tig-admin-row {
            margin: 0 0 16px;
            border: 1px solid #dcdcde;
            background: #fff;
        }

        .tig-admin-row-head {
            display: grid;
            grid-template-columns: 150px minmax(180px, 1fr) auto;
            gap: 12px;
            align-items: end;
            padding: 14px;
            border-bottom: 1px solid #dcdcde;
            background: #f6f7f7;
        }

        .tig-admin-field label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .tig-admin-field input[type="text"],
        .tig-admin-field input[type="time"],
        .tig-admin-field select {
            width: 100%;
            max-width: 100%;
        }

        .tig-admin-events {
            padding: 14px;
        }

        .tig-admin-event {
            grid-template-columns: 170px minmax(220px, 1fr) 220px 150px auto;
        }

        .tig-admin-actions,
        .tig-admin-event-actions,
        .tig-admin-row-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        @media (max-width: 900px) {
            .tig-admin-venue,
            .tig-admin-type,
            .tig-admin-row-head,
            .tig-admin-event {
                grid-template-columns: 1fr;
            }

            .tig-admin-actions,
            .tig-admin-row-actions,
            .tig-admin-event-actions {
                justify-content: flex-start;
            }
        }
    </style>

    <script>
        (function () {
            const venues = document.querySelector("[data-tig-venues]");
            const types = document.querySelector("[data-tig-types]");
            const schedule = document.querySelector("[data-tig-schedule]");
            const venueTemplate = document.getElementById("tig-program-venue-template");
            const typeTemplate = document.getElementById("tig-program-type-template");
            const rowTemplate = document.getElementById("tig-program-row-template");
            const eventTemplate = document.getElementById("tig-program-event-template");

            if (!venues || !types || !schedule || !venueTemplate || !typeTemplate || !rowTemplate || !eventTemplate) {
                return;
            }

            function nextIndex() {
                return Date.now().toString() + Math.floor(Math.random() * 1000).toString();
            }

            function bindVenue(venue) {
                venue.querySelectorAll("[data-tig-remove-venue]").forEach(function (button) {
                    button.addEventListener("click", function () {
                        venue.remove();
                    });
                });
            }

            function bindType(type) {
                type.querySelectorAll("[data-tig-remove-type]").forEach(function (button) {
                    button.addEventListener("click", function () {
                        type.remove();
                    });
                });
            }

            function bindRow(row) {
                row.querySelectorAll("[data-tig-add-event]").forEach(function (button) {
                    button.addEventListener("click", function () {
                        const rowIndex = row.getAttribute("data-row-index");
                        const html = eventTemplate.innerHTML
                            .replaceAll("__row_index__", rowIndex)
                            .replaceAll("__event_index__", nextIndex());

                        row.querySelector("[data-tig-events]").insertAdjacentHTML("beforeend", html);
                        bindEvent(row.querySelector("[data-tig-events] [data-tig-event]:last-child"));
                    });
                });

                row.querySelectorAll("[data-tig-remove-row]").forEach(function (button) {
                    button.addEventListener("click", function () {
                        row.remove();
                    });
                });

                row.querySelectorAll("[data-tig-event]").forEach(bindEvent);
            }

            function bindEvent(event) {
                event.querySelectorAll("[data-tig-remove-event]").forEach(function (button) {
                    button.addEventListener("click", function () {
                        event.remove();
                    });
                });
            }

            document.querySelectorAll("[data-tig-add-venue]").forEach(function (button) {
                button.addEventListener("click", function () {
                    const html = venueTemplate.innerHTML.replaceAll("__venue_index__", nextIndex());
                    venues.insertAdjacentHTML("beforeend", html);
                    bindVenue(venues.querySelector("[data-tig-venue]:last-child"));
                });
            });

            document.querySelectorAll("[data-tig-add-type]").forEach(function (button) {
                button.addEventListener("click", function () {
                    const html = typeTemplate.innerHTML.replaceAll("__type_index__", nextIndex());
                    types.insertAdjacentHTML("beforeend", html);
                    bindType(types.querySelector("[data-tig-type]:last-child"));
                });
            });

            document.querySelectorAll("[data-tig-add-row]").forEach(function (button) {
                button.addEventListener("click", function () {
                    const rowIndex = nextIndex();
                    const html = rowTemplate.innerHTML.replaceAll("__row_index__", rowIndex);
                    schedule.insertAdjacentHTML("beforeend", html);
                    bindRow(schedule.querySelector("[data-tig-row]:last-child"));
                });
            });

            venues.querySelectorAll("[data-tig-venue]").forEach(bindVenue);
            types.querySelectorAll("[data-tig-type]").forEach(bindType);
            schedule.querySelectorAll("[data-tig-row]").forEach(bindRow);
        })();
    

            // Reset gomb kezelÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ
            (function () {
                var resetBtn = document.querySelector('.tig-admin-reset-btn');
                if (!resetBtn) return;
                resetBtn.addEventListener('click', function () {
                    if (!window.confirm(resetBtn.getAttribute('data-confirm'))) return;
                    var form = document.createElement('form');
                    form.method = 'post';
                    form.action = resetBtn.getAttribute('data-action');
                    var actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = resetBtn.getAttribute('data-form-action');
                    form.appendChild(actionInput);
                    var nonceInput = document.querySelector('input[name="' + resetBtn.getAttribute('data-nonce-name') + '"]');
                    if (nonceInput) form.appendChild(nonceInput.cloneNode());
                    document.body.appendChild(form);
                    form.submit();
                });
            })();</script>
    <?php
}

function tig_festival_program_render_admin_venue($venue_index, array $venue): void
{
    ?>
    <div class="tig-admin-venue" data-tig-venue>
        <input type="hidden" name="dg_venues[<?php echo esc_attr((string) $venue_index); ?>][id]" value="<?php echo esc_attr($venue['id'] ?? ''); ?>">
        <div class="tig-admin-field">
            <label>HelyszÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­n neve</label>
            <input type="text" name="dg_venues[<?php echo esc_attr((string) $venue_index); ?>][label]" value="<?php echo esc_attr($venue['label'] ?? ''); ?>" placeholder="Pl. NagyszÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­npad">
        </div>
        <div class="tig-admin-field">
            <label>HÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡ttÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©rszÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­n</label>
            <input type="color" name="dg_venues[<?php echo esc_attr((string) $venue_index); ?>][color]" value="<?php echo esc_attr($venue['color'] ?? '#eef3f7'); ?>">
        </div>
        <div class="tig-admin-field">
            <label>SzÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¶vegszÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­n</label>
            <input type="color" name="dg_venues[<?php echo esc_attr((string) $venue_index); ?>][text_color]" value="<?php echo esc_attr($venue['text_color'] ?? '#0f2133'); ?>">
        </div>
        <div class="tig-admin-actions">
            <button type="button" class="button button-link-delete" data-tig-remove-venue>HelyszÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­n tÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¶rlÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©se</button>
        </div>
    </div>
    <?php
}

function tig_festival_program_render_admin_type($type_index, array $type): void
{
    ?>
    <div class="tig-admin-type" data-tig-type>
        <input type="hidden" name="dg_program_types[<?php echo esc_attr((string) $type_index); ?>][id]" value="<?php echo esc_attr($type['id'] ?? ''); ?>">
        <div class="tig-admin-field">
            <label>Tag neve</label>
            <input type="text" name="dg_program_types[<?php echo esc_attr((string) $type_index); ?>][label]" value="<?php echo esc_attr($type['label'] ?? ''); ?>" placeholder="Pl. FÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ program">
        </div>
        <div></div>
        <div></div>
        <div class="tig-admin-actions">
            <button type="button" class="button button-link-delete" data-tig-remove-type>Tag tÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¶rlÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©se</button>
        </div>
    </div>
    <?php
}

function tig_festival_program_render_admin_row($row_index, array $row, array $venues, array $types): void
{
    $events = isset($row['events']) && is_array($row['events']) ? $row['events'] : [];
    ?>
    <section class="tig-admin-row" data-tig-row data-row-index="<?php echo esc_attr((string) $row_index); ?>">
        <div class="tig-admin-row-head">
            <div class="tig-admin-field">
                <label>IdÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂpont</label>
                <input type="time" name="dg_schedule[<?php echo esc_attr((string) $row_index); ?>][time]" value="<?php echo esc_attr($row['time'] ?? ''); ?>">
            </div>
            <div class="tig-admin-field">
                <label>IdÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂpont megjegyzÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©s</label>
                <input type="text" name="dg_schedule[<?php echo esc_attr((string) $row_index); ?>][note]" value="<?php echo esc_attr($row['note'] ?? ''); ?>" placeholder="Pl. KapunyitÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡s">
            </div>
            <div class="tig-admin-row-actions">
                <button type="button" class="button" data-tig-add-event>Program hozzÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡adÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡sa</button>
                <button type="button" class="button button-link-delete" data-tig-remove-row>IdÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂpont tÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¶rlÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©se</button>
            </div>
        </div>
        <div class="tig-admin-events" data-tig-events>
            <?php foreach ($events as $event_index => $event) : ?>
                <?php tig_festival_program_render_admin_event($row_index, (int) $event_index, $event, $venues, $types); ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

function tig_festival_program_render_admin_event($row_index, $event_index, array $event, array $venues, array $types): void
{
    $venue = $event['venue'] ?? array_key_first($venues);
    $type = $event['type'] ?? '';
    ?>
    <div class="tig-admin-event" data-tig-event>
        <div class="tig-admin-field">
            <label>HelyszÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­n</label>
            <select name="dg_schedule[<?php echo esc_attr((string) $row_index); ?>][events][<?php echo esc_attr((string) $event_index); ?>][venue]">
                <?php foreach ($venues as $venue_key => $venue_data) : ?>
                    <option value="<?php echo esc_attr($venue_key); ?>" <?php selected($venue, $venue_key); ?>>
                        <?php echo esc_html($venue_data['label']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="tig-admin-field">
            <label>Program cÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ­me</label>
            <input type="text" name="dg_schedule[<?php echo esc_attr((string) $row_index); ?>][events][<?php echo esc_attr((string) $event_index); ?>][title]" value="<?php echo esc_attr($event['title'] ?? ''); ?>">
        </div>
        <div class="tig-admin-field">
            <label>Tag</label>
            <select name="dg_schedule[<?php echo esc_attr((string) $row_index); ?>][events][<?php echo esc_attr((string) $event_index); ?>][type]">
                <option value="" <?php selected($type, ''); ?>>Nincs tag</option>
                <?php foreach ($types as $type_key => $type_label) : ?>
                    <option value="<?php echo esc_attr($type_key); ?>" <?php selected($type, $type_key); ?>>
                        <?php echo esc_html($type_label['label']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="tig-admin-field">
            <label>VÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©ge (opcionÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¡lis)</label>
            <input type="time" name="dg_schedule[<?php echo esc_attr((string) $row_index); ?>][events][<?php echo esc_attr((string) $event_index); ?>][end_time]" value="<?php echo esc_attr($event['end_time'] ?? ''); ?>">
        </div>
        <div class="tig-admin-event-actions">
            
                    <div class="tig-admin-field tig-admin-field--full">
                        <label>LeÃÂ­rÃÂ¡s (opcionÃÂ¡lis)</label>
                        <textarea name="dg_schedule[<?php echo esc_attr((string) $row_index); ?>][events][<?php echo esc_attr((string) $event_index); ?>][description]" rows="2" placeholder="RÃÂ¶vid leÃÂ­rÃÂ¡s a programpontrÃÂ³l..."><?php echo esc_textarea($event['description'] ?? ''); ?></textarea>
                    </div>
                    <div class="tig-admin-field">
                        <label>Link (opcionÃÂ¡lis)</label>
                        <input type="url" name="dg_schedule[<?php echo esc_attr((string) $row_index); ?>][events][<?php echo esc_attr((string) $event_index); ?>][link]" value="<?php echo esc_attr($event['link'] ?? ''); ?>" placeholder="https://...">
                    </div>
                    <div class="tig-admin-field">
                        <label>KÃÂ©p URL (opcionÃÂ¡lis)</label>
                        <input type="url" name="dg_schedule[<?php echo esc_attr((string) $row_index); ?>][events][<?php echo esc_attr((string) $event_index); ?>][image_url]" value="<?php echo esc_attr($event['image_url'] ?? ''); ?>" placeholder="https://...">
                    </div>
                    <button type="button" class="button button-link-delete" data-tig-remove-event>Program tÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¶rlÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ©se</button>
        </div>
    </div>
    <?php
}
