<?php
if (!defined('ABSPATH')) { exit; }

$day_count = count($days);
$show_tabs = $day_count > 1;
$uid       = 'tig-' . substr(md5(uniqid('', true)), 0, 8);
?>
<div class="tig-program" id="<?php echo esc_attr($uid); ?>">

    <div class="tig-program-head">
        <div class="tig-program-title"><?php echo esc_html($title); ?></div>
        <?php if ($subtitle !== '') : ?>
        <div class="tig-program-subtitle"><?php echo esc_html($subtitle); ?></div>
        <?php endif; ?>
        <?php if (!empty($formatted_date)) : ?>
        <div class="tig-program-date"><?php echo esc_html($formatted_date); ?></div>
        <?php endif; ?>
    </div>

    <?php if ($show_tabs) : ?>
    <div class="tig-day-tabs" role="tablist" aria-label="<?php esc_attr_e('Napok', 'tig-festival-program'); ?>">
        <?php foreach ($days as $day_idx => $day) : ?>
        <button
            class="tig-day-tab<?php echo $day_idx === 0 ? ' tig-day-tab--active' : ''; ?>"
            role="tab"
            aria-selected="<?php echo $day_idx === 0 ? 'true' : 'false'; ?>"
            aria-controls="<?php echo esc_attr($uid . '-day-' . $day_idx); ?>"
            data-tig-tab="<?php echo esc_attr($uid . '-day-' . $day_idx); ?>"
        >
            <?php echo esc_html($day['label'] ?: ('Nap ' . ($day_idx + 1))); ?>
            <?php if (!empty($day['date'])) : ?>
                <span class="tig-day-tab-date"><?php echo esc_html(date_i18n('M j.', strtotime($day['date']))); ?></span>
            <?php endif; ?>
        </button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php foreach ($days as $day_idx => $day) :
        $schedule = $day['schedule'] ?? [];
        $schedule_times = array_map(static function (array $row): string {
            return (string) ($row['time'] ?? '');
        }, $schedule);

        $get_event_rowspan = static function (array $event, int $row_index) use ($schedule_times): int {
            $start_time  = $schedule_times[$row_index] ?? '';
            $end_time    = (string) ($event['end_time'] ?? '');
            $start_min   = tig_festival_program_time_to_minutes($start_time);
            $end_min     = tig_festival_program_time_to_minutes($end_time);
            if ($start_min === null || $end_min === null || $end_min <= $start_min) { return 1; }
            $span = 1;
            for ($i = $row_index + 1; $i < count($schedule_times); $i++) {
                $next_min = tig_festival_program_time_to_minutes($schedule_times[$i]);
                if ($next_min === null || $next_min >= $end_min) { break; }
                $span++;
            }
            return max(1, $span);
        };
    ?>

    <div
        class="tig-program-day<?php echo $day_idx === 0 ? ' tig-program-day--active' : ''; ?>"
        id="<?php echo esc_attr($uid . '-day-' . $day_idx); ?>"
        role="tabpanel"
        <?php if ($show_tabs) : ?>
        aria-labelledby="<?php echo esc_attr($uid . '-tab-' . $day_idx); ?>"
        <?php endif; ?>
    >
        <div class="tig-program-toolbar">
            <button type="button" class="tig-jump-now-btn" aria-label="<?php esc_attr_e('Ugrás az aktuális időponthoz', 'tig-festival-program'); ?>">&#9654; Most</button>
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

        <?php if (empty($schedule)) : ?>
            <p class="tig-program-empty"><?php esc_html_e('Erre a napra mÃÂ©g nincs program megadva.', 'tig-festival-program'); ?></p>
        <?php else : ?>

        <div class="tig-program-desktop">
            <table class="tig-program-table" role="grid">
                <thead>
                    <tr>
                        <th class="tig-time-head" scope="col"><?php esc_html_e('IdÃÂpont', 'tig-festival-program'); ?></th>
                        <?php foreach ($venues as $venue) : ?>
                        <th scope="col"
                            style="--tig-venue-bg: <?php echo esc_attr($venue['color']); ?>; --tig-venue-fg: <?php echo esc_attr($venue['text_color']); ?>;"
                            class="tig-venue-head">
                            <?php echo esc_html($venue['label']); ?>
                        </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $skip = [];
                    foreach ($schedule as $row_idx => $row) :
                        $events_by_venue = [];
                        foreach (($row['events'] ?? []) as $event) {
                            $events_by_venue[$event['venue'] ?? ''] = $event;
                        }
                    ?>
                    <tr>
                        <td class="tig-time">
                            <span><?php echo esc_html($row['time'] ?? ''); ?></span>
                            <?php if (!empty($row['note'])) : ?>
                            <span class="tig-time-note"><?php echo esc_html($row['note']); ?></span>
                            <?php endif; ?>
                        </td>
                        <?php foreach ($venues as $venue) :
                            $vid = $venue['id'];
                            if (in_array($row_idx . '|' . $vid, $skip, true)) continue;
                            $event   = $events_by_venue[$vid] ?? null;
                            $rowspan = $event ? $get_event_rowspan($event, $row_idx) : 1;
                            if ($event && $rowspan > 1) {
                                for ($s = $row_idx + 1; $s < $row_idx + $rowspan; $s++) {
                                    $skip[] = $s . '|' . $vid;
                                }
                            }
                        ?>
                        <td
                            <?php if ($rowspan > 1) echo 'rowspan="' . esc_attr((string)$rowspan) . '"'; ?>
                            <?php if ($event) : ?>
                            class="tig-event-cell"
                            style="--tig-venue-bg: <?php echo esc_attr($venue['color']); ?>; --tig-venue-fg: <?php echo esc_attr($venue['text_color']); ?>;"
                            <?php endif; ?>
                        >
                            <?php if ($event) : ?>
                            <span class="tig-event-title"><?php echo esc_html($event['title'] ?? ''); ?></span>
                            <?php if (!empty($event['end_time'])) : ?>
                            <span class="tig-event-time"><?php echo esc_html($row['time'] ?? ''); ?>Ã¢ÂÂ<?php echo esc_html($event['end_time']); ?></span>
                            <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="tig-program-mobile">
            <?php foreach ($schedule as $row) :
                if (empty($row['events']) && empty($row['note'])) continue;
            ?>
            <div class="tig-mobile-row">
                <div class="tig-mobile-time">
                    <?php echo esc_html($row['time'] ?? ''); ?>
                    <?php if (!empty($row['note'])) : ?>
                    <span class="tig-time-note"><?php echo esc_html($row['note']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="tig-mobile-events">
                    <?php foreach (($row['events'] ?? []) as $event) :
                        $venue = $venues[$event['venue'] ?? ''] ?? null;
                    ?>
                    <div class="tig-mobile-event<?php echo $venue ? ' tig-venue-chip' : ''; ?>"
                        <?php if ($venue) : ?>
                        style="--tig-venue-bg: <?php echo esc_attr($venue['color']); ?>; --tig-venue-fg: <?php echo esc_attr($venue['text_color']); ?>;"
                        <?php endif; ?>>
                        <?php if ($venue) : ?>
                        <span class="tig-mobile-venue-label"><?php echo esc_html($venue['label']); ?></span>
                        <?php endif; ?>
                        <span class="tig-mobile-event-title"><?php echo esc_html($event['title'] ?? ''); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php endif; // empty schedule ?>

    </div><!-- .tig-program-day -->

    <?php endforeach; // days ?>

</div><!-- .tig-program -->
