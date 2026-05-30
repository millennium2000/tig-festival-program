<?php
/**
 * Plugin Name: TIG Festival Program
 * Plugin URI: https://example.com/tig-festival
 * Description: Complete festival program management with schedule, artists, and stages.
 * Version: 2.0.0
 * Author: TIG Festival
 * Text Domain: tig-festival
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'TIG_VERSION', '2.0.0' );
define( 'TIG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TIG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

register_activation_hook( __FILE__, 'tig_activate' );
register_deactivation_hook( __FILE__, 'tig_deactivate' );

function tig_activate() {
    tig_create_tables();
    tig_insert_sample_data();
    flush_rewrite_rules();
}
function tig_deactivate() { flush_rewrite_rules(); }

function tig_create_tables() {
    global $wpdb;
    $charset = $wpdb->get_charset_collate();
    $stages = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tig_stages (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(200) NOT NULL,
        location VARCHAR(200) DEFAULT '',
        capacity INT DEFAULT 0,
        color VARCHAR(7) DEFAULT '#e63946',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset;";
    $artists = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tig_artists (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(200) NOT NULL,
        genre VARCHAR(100) DEFAULT '',
        bio TEXT,
        image_url VARCHAR(500) DEFAULT '',
        website VARCHAR(500) DEFAULT '',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset;";
    $events = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tig_events (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        title VARCHAR(300) NOT NULL,
        artist_id BIGINT(20) UNSIGNED DEFAULT NULL,
        stage_id BIGINT(20) UNSIGNED DEFAULT NULL,
        event_date DATE NOT NULL,
        start_time TIME NOT NULL,
        end_time TIME NOT NULL,
        description TEXT,
        status ENUM('published','draft','cancelled') DEFAULT 'published',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY artist_id (artist_id),
        KEY stage_id (stage_id),
        KEY event_date (event_date)
    ) $charset;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $stages ); dbDelta( $artists ); dbDelta( $events );
}

function tig_insert_sample_data() {
    global $wpdb;
    if ( $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}tig_stages" ) > 0 ) { return; }
    foreach ( array(
        array( 'name' => 'Main Stage',    'location' => 'Central Area', 'capacity' => 5000, 'color' => '#e63946' ),
        array( 'name' => 'Club Stage',    'location' => 'East Wing',    'capacity' => 1200, 'color' => '#f4a261' ),
        array( 'name' => 'Acoustic Tent', 'location' => 'North Garden', 'capacity' => 400,  'color' => '#2a9d8f' ),
    ) as $r ) { $wpdb->insert( "{$wpdb->prefix}tig_stages", $r ); }
    foreach ( array(
        array( 'name' => 'The Midnight',         'genre' => 'Synthwave',    'bio' => 'Nostalgic electronic duo from Los Angeles.' ),
        array( 'name' => 'Jungle',               'genre' => 'Funk / Soul',  'bio' => 'UK collective bringing dance-floor heat.' ),
        array( 'name' => 'Cigarettes After Sex', 'genre' => 'Dream Pop',    'bio' => 'Hazy, romantic ambient pop.' ),
        array( 'name' => 'Parcels',              'genre' => 'Disco / Funk', 'bio' => 'Australian five-piece disco revivalists.' ),
        array( 'name' => 'Glass Animals',        'genre' => 'Indie Pop',    'bio' => 'Oxford quartet fusing pop and psychedelia.' ),
    ) as $r ) { $wpdb->insert( "{$wpdb->prefix}tig_artists", $r ); }
    foreach ( array(
        array( 'title' => 'The Midnight',         'artist_id' => 1, 'stage_id' => 1, 'event_date' => '2025-07-18', 'start_time' => '21:00:00', 'end_time' => '22:30:00', 'status' => 'published' ),
        array( 'title' => 'Jungle',               'artist_id' => 2, 'stage_id' => 1, 'event_date' => '2025-07-19', 'start_time' => '20:00:00', 'end_time' => '21:30:00', 'status' => 'published' ),
        array( 'title' => 'Cigarettes After Sex', 'artist_id' => 3, 'stage_id' => 3, 'event_date' => '2025-07-18', 'start_time' => '19:00:00', 'end_time' => '20:00:00', 'status' => 'published' ),
        array( 'title' => 'Parcels',              'artist_id' => 4, 'stage_id' => 2, 'event_date' => '2025-07-19', 'start_time' => '22:00:00', 'end_time' => '23:30:00', 'status' => 'published' ),
        array( 'title' => 'Glass Animals',        'artist_id' => 5, 'stage_id' => 1, 'event_date' => '2025-07-20', 'start_time' => '21:30:00', 'end_time' => '23:00:00', 'status' => 'published' ),
    ) as $r ) { $wpdb->insert( "{$wpdb->prefix}tig_events", $r ); }
}

add_action( 'admin_menu', 'tig_admin_menu' );
function tig_admin_menu() {
    add_menu_page( __( 'TIG Festival', 'tig-festival' ), __( 'TIG Festival', 'tig-festival' ), 'manage_options', 'tig-festival', 'tig_admin_dashboard', 'dashicons-calendar-alt', 30 );
    add_submenu_page( 'tig-festival', 'Dashboard', 'Dashboard', 'manage_options', 'tig-festival',         'tig_admin_dashboard' );
    add_submenu_page( 'tig-festival', 'Events',    'Events',    'manage_options', 'tig-festival-events',   'tig_admin_events' );
    add_submenu_page( 'tig-festival', 'Artists',   'Artists',   'manage_options', 'tig-festival-artists',  'tig_admin_artists' );
    add_submenu_page( 'tig-festival', 'Stages',    'Stages',    'manage_options', 'tig-festival-stages',   'tig_admin_stages' );
    add_submenu_page( 'tig-festival', 'Settings',  'Settings',  'manage_options', 'tig-festival-settings', 'tig_admin_settings' );
}

add_action( 'admin_enqueue_scripts', 'tig_admin_assets' );
function tig_admin_assets( $hook ) {
    if ( strpos( $hook, 'tig-festival' ) === false ) { return; }
    wp_enqueue_style( 'tig-admin-css', TIG_PLUGIN_URL . 'assets/admin.css', array(), TIG_VERSION );
    wp_enqueue_script( 'tig-admin-js', TIG_PLUGIN_URL . 'assets/admin.js', array( 'jquery' ), TIG_VERSION, true );
    wp_localize_script( 'tig-admin-js', 'tigData', array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'tig_nonce' ) ) );
}

function tig_admin_dashboard() {
    global $wpdb;
    $total_events  = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}tig_events" );
    $total_artists = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}tig_artists" );
    $total_stages  = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}tig_stages" );
    $upcoming      = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}tig_events WHERE event_date >= %s AND status = 'published'", current_time( 'Y-m-d' ) ) );
    ?>
    <div class="tig-wrap">
        <div class="tig-header"><h1>&#127929; TIG Festival Program</h1><p class="tig-subtitle">Festival Management Dashboard v2.0</p></div>
        <div class="tig-stats-grid">
            <div class="tig-stat-card tig-stat-events"><div class="tig-stat-icon">&#127926;</div><div class="tig-stat-number"><?php echo $total_events; ?></div><div class="tig-stat-label">Total Events</div></div>
            <div class="tig-stat-card tig-stat-artists"><div class="tig-stat-icon">&#127932;</div><div class="tig-stat-number"><?php echo $total_artists; ?></div><div class="tig-stat-label">Artists</div></div>
            <div class="tig-stat-card tig-stat-stages"><div class="tig-stat-icon">&#127914;</div><div class="tig-stat-number"><?php echo $total_stages; ?></div><div class="tig-stat-label">Stages</div></div>
            <div class="tig-stat-card tig-stat-upcoming"><div class="tig-stat-icon">&#128197;</div><div class="tig-stat-number"><?php echo $upcoming; ?></div><div class="tig-stat-label">Upcoming</div></div>
        </div>
        <div class="tig-quick-actions"><h2>Quick Actions</h2><div class="tig-actions-row">
            <a href="<?php echo admin_url('admin.php?page=tig-festival-events&action=add'); ?>" class="tig-btn tig-btn-primary">+ Add Event</a>
            <a href="<?php echo admin_url('admin.php?page=tig-festival-artists&action=add'); ?>" class="tig-btn tig-btn-secondary">+ Add Artist</a>
            <a href="<?php echo admin_url('admin.php?page=tig-festival-stages&action=add'); ?>" class="tig-btn tig-btn-secondary">+ Add Stage</a>
        </div></div>
        <div class="tig-shortcodes-box"><h2>Shortcodes</h2><table class="tig-table"><thead><tr><th>Shortcode</th><th>Description</th></tr></thead><tbody>
            <tr><td><code>[tig_schedule]</code></td><td>Full festival schedule</td></tr>
            <tr><td><code>[tig_artists]</code></td><td>Artist lineup cards</td></tr>
            <tr><td><code>[tig_stages]</code></td><td>Stage information</td></tr>
        </tbody></table></div>
    </div>
    <?php
}

function tig_admin_events() {
    global $wpdb;
    $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
    $id     = isset($_GET['id'])     ? absint($_GET['id']) : 0;
    if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['tig_nonce']) ) {
        if (!wp_verify_nonce($_POST['tig_nonce'],'tig_event_save')) { wp_die('Security check failed.'); }
        $data = array(
            'title'       => sanitize_text_field($_POST['title']),
            'artist_id'   => absint($_POST['artist_id']),
            'stage_id'    => absint($_POST['stage_id']),
            'event_date'  => sanitize_text_field($_POST['event_date']),
            'start_time'  => sanitize_text_field($_POST['start_time']),
            'end_time'    => sanitize_text_field($_POST['end_time']),
            'description' => sanitize_textarea_field($_POST['description']),
            'status'      => sanitize_text_field($_POST['status']),
        );
        if ($id > 0) { $wpdb->update("{$wpdb->prefix}tig_events",$data,array('id'=>$id)); echo '<div class="notice notice-success"><p>Event updated.</p></div>'; }
        else { $wpdb->insert("{$wpdb->prefix}tig_events",$data); echo '<div class="notice notice-success"><p>Event added.</p></div>'; }
        $action = 'list';
    }
    if ('delete'===$action && $id>0) { check_admin_referer('tig_delete_event_'.$id); $wpdb->delete("{$wpdb->prefix}tig_events",array('id'=>$id)); $action='list'; }
    $stages  = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tig_stages ORDER BY name");
    $artists = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tig_artists ORDER BY name");
    if ('add'===$action||'edit'===$action) {
        $event = ('edit'===$action&&$id) ? $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}tig_events WHERE id=%d",$id)) : null;
        $eid = $event ? $event->id : 0;
        ?><div class="tig-wrap"><div class="tig-page-header"><h1><?php echo $eid?'Edit Event':'Add New Event'; ?></h1><a href="<?php echo admin_url('admin.php?page=tig-festival-events'); ?>" class="tig-btn tig-btn-secondary">&larr; Back</a></div>
        <form method="post" class="tig-form"><?php wp_nonce_field('tig_event_save','tig_nonce'); ?><input type="hidden" name="id" value="<?php echo $eid; ?>">
        <div class="tig-form-row"><label>Event Title *</label><input type="text" name="title" value="<?php echo esc_attr($event->title??''); ?>" required class="tig-input"></div>
        <div class="tig-form-row tig-form-cols">
            <div><label>Artist</label><select name="artist_id" class="tig-input"><option value="">No Artist</option><?php foreach($artists as $a) echo '<option value="'.$a->id.'"'.selected(($event->artist_id??0),$a->id,false).'>'.esc_html($a->name).'</option>'; ?></select></div>
            <div><label>Stage</label><select name="stage_id" class="tig-input"><option value="">No Stage</option><?php foreach($stages as $s) echo '<option value="'.$s->id.'"'.selected(($event->stage_id??0),$s->id,false).'>'.esc_html($s->name).'</option>'; ?></select></div>
        </div>
        <div class="tig-form-row tig-form-cols">
            <div><label>Date *</label><input type="date" name="event_date" value="<?php echo esc_attr($event->event_date??''); ?>" required class="tig-input"></div>
            <div><label>Start Time *</label><input type="time" name="start_time" value="<?php echo esc_attr(substr($event->start_time??'',0,5)); ?>" required class="tig-input"></div>
            <div><label>End Time *</label><input type="time" name="end_time" value="<?php echo esc_attr(substr($event->end_time??'',0,5)); ?>" required class="tig-input"></div>
        </div>
        <div class="tig-form-row"><label>Description</label><textarea name="description" rows="4" class="tig-input"><?php echo esc_textarea($event->description??''); ?></textarea></div>
        <div class="tig-form-row"><label>Status</label><select name="status" class="tig-input"><?php foreach(array('published','draft','cancelled') as $sv) echo '<option value="'.$sv.'"'.selected(($event->status??'published'),$sv,false).'>'.ucfirst($sv).'</option>'; ?></select></div>
        <div class="tig-form-actions"><button type="submit" class="tig-btn tig-btn-primary"><?php echo $eid?'Update Event':'Add Event'; ?></button></div>
        </form></div><?php return;
    }
    $events = $wpdb->get_results("SELECT e.*,a.name AS artist_name,s.name AS stage_name,s.color AS stage_color FROM {$wpdb->prefix}tig_events e LEFT JOIN {$wpdb->prefix}tig_artists a ON e.artist_id=a.id LEFT JOIN {$wpdb->prefix}tig_stages s ON e.stage_id=s.id ORDER BY e.event_date,e.start_time");
    ?><div class="tig-wrap"><div class="tig-page-header"><h1>Events</h1><a href="<?php echo admin_url('admin.php?page=tig-festival-events&action=add'); ?>" class="tig-btn tig-btn-primary">+ Add Event</a></div>
    <table class="tig-table tig-table-full"><thead><tr><th>Title</th><th>Artist</th><th>Stage</th><th>Date</th><th>Time</th><th>Status</th><th>Actions</th></tr></thead><tbody>
    <?php foreach($events as $ev): ?><tr>
        <td><strong><?php echo esc_html($ev->title); ?></strong></td>
        <td><?php echo esc_html($ev->artist_name??''); ?></td>
        <td><span class="tig-stage-badge" style="background:<?php echo esc_attr($ev->stage_color); ?>"><?php echo esc_html($ev->stage_name??''); ?></span></td>
        <td><?php echo esc_html($ev->event_date); ?></td>
        <td><?php echo esc_html(substr($ev->start_time,0,5).' - '.substr($ev->end_time,0,5)); ?></td>
        <td><span class="tig-status tig-status-<?php echo esc_attr($ev->status); ?>"><?php echo esc_html($ev->status); ?></span></td>
        <td><a href="<?php echo admin_url('admin.php?page=tig-festival-events&action=edit&id='.$ev->id); ?>" class="tig-link-edit">Edit</a> | <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=tig-festival-events&action=delete&id='.$ev->id),'tig_delete_event_'.$ev->id); ?>" class="tig-link-delete" onclick="return confirm('Delete?')">Delete</a></td>
    </tr><?php endforeach; ?>
    </tbody></table></div><?php
}

function tig_admin_artists() {
    global $wpdb;
    $action = isset($_GET['action'])?sanitize_text_field($_GET['action']):'list';
    $id = isset($_GET['id'])?absint($_GET['id']):0;
    if ('POST'===$_SERVER['REQUEST_METHOD']&&isset($_POST['tig_nonce'])) {
        if (!wp_verify_nonce($_POST['tig_nonce'],'tig_artist_save')) { wp_die('Security check failed.'); }
        $data = array('name'=>sanitize_text_field($_POST['name']),'genre'=>sanitize_text_field($_POST['genre']),'bio'=>sanitize_textarea_field($_POST['bio']),'image_url'=>esc_url_raw($_POST['image_url']),'website'=>esc_url_raw($_POST['website']));
        if ($id>0) { $wpdb->update("{$wpdb->prefix}tig_artists",$data,array('id'=>$id)); } else { $wpdb->insert("{$wpdb->prefix}tig_artists",$data); }
        $action='list';
    }
    if ('delete'===$action&&$id>0) { check_admin_referer('tig_delete_artist_'.$id); $wpdb->delete("{$wpdb->prefix}tig_artists",array('id'=>$id)); $action='list'; }
    if ('add'===$action||'edit'===$action) {
        $artist = ('edit'===$action&&$id)?$wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}tig_artists WHERE id=%d",$id)):null;
        ?><div class="tig-wrap"><div class="tig-page-header"><h1><?php echo $id?'Edit Artist':'Add Artist'; ?></h1><a href="<?php echo admin_url('admin.php?page=tig-festival-artists'); ?>" class="tig-btn tig-btn-secondary">&larr; Back</a></div>
        <form method="post" class="tig-form"><?php wp_nonce_field('tig_artist_save','tig_nonce'); ?>
        <div class="tig-form-row"><label>Name *</label><input type="text" name="name" value="<?php echo esc_attr($artist->name??''); ?>" required class="tig-input"></div>
        <div class="tig-form-row"><label>Genre</label><input type="text" name="genre" value="<?php echo esc_attr($artist->genre??''); ?>" class="tig-input"></div>
        <div class="tig-form-row"><label>Bio</label><textarea name="bio" rows="4" class="tig-input"><?php echo esc_textarea($artist->bio??''); ?></textarea></div>
        <div class="tig-form-row"><label>Image URL</label><input type="url" name="image_url" value="<?php echo esc_attr($artist->image_url??''); ?>" class="tig-input"></div>
        <div class="tig-form-row"><label>Website</label><input type="url" name="website" value="<?php echo esc_attr($artist->website??''); ?>" class="tig-input"></div>
        <div class="tig-form-actions"><button type="submit" class="tig-btn tig-btn-primary"><?php echo $id?'Update':'Add Artist'; ?></button></div>
        </form></div><?php return;
    }
    $artists=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}tig_artists ORDER BY name");
    ?><div class="tig-wrap"><div class="tig-page-header"><h1>Artists</h1><a href="<?php echo admin_url('admin.php?page=tig-festival-artists&action=add'); ?>" class="tig-btn tig-btn-primary">+ Add Artist</a></div>
    <table class="tig-table tig-table-full"><thead><tr><th>Name</th><th>Genre</th><th>Website</th><th>Actions</th></tr></thead><tbody>
    <?php foreach($artists as $a): ?><tr><td><strong><?php echo esc_html($a->name); ?></strong></td><td><?php echo esc_html($a->genre); ?></td>
    <td><?php if($a->website) echo '<a href="'.esc_url($a->website).'" target="_blank">'.esc_html($a->website).'</a>'; ?></td>
    <td><a href="<?php echo admin_url('admin.php?page=tig-festival-artists&action=edit&id='.$a->id); ?>" class="tig-link-edit">Edit</a> | <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=tig-festival-artists&action=delete&id='.$a->id),'tig_delete_artist_'.$a->id); ?>" class="tig-link-delete" onclick="return confirm('Delete?')">Delete</a></td>
    </tr><?php endforeach; ?></tbody></table></div><?php
}

function tig_admin_stages() {
    global $wpdb;
    $action=isset($_GET['action'])?sanitize_text_field($_GET['action']):'list';
    $id=isset($_GET['id'])?absint($_GET['id']):0;
    if ('POST'===$_SERVER['REQUEST_METHOD']&&isset($_POST['tig_nonce'])) {
        if (!wp_verify_nonce($_POST['tig_nonce'],'tig_stage_save')) { wp_die('Security check failed.'); }
        $data=array('name'=>sanitize_text_field($_POST['name']),'location'=>sanitize_text_field($_POST['location']),'capacity'=>absint($_POST['capacity']),'color'=>sanitize_hex_color($_POST['color']));
        if ($id>0) { $wpdb->update("{$wpdb->prefix}tig_stages",$data,array('id'=>$id)); } else { $wpdb->insert("{$wpdb->prefix}tig_stages",$data); }
        $action='list';
    }
    if ('delete'===$action&&$id>0) { check_admin_referer('tig_delete_stage_'.$id); $wpdb->delete("{$wpdb->prefix}tig_stages",array('id'=>$id)); $action='list'; }
    if ('add'===$action||'edit'===$action) {
        $stage=('edit'===$action&&$id)?$wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}tig_stages WHERE id=%d",$id)):null;
        ?><div class="tig-wrap"><div class="tig-page-header"><h1><?php echo $id?'Edit Stage':'Add Stage'; ?></h1><a href="<?php echo admin_url('admin.php?page=tig-festival-stages'); ?>" class="tig-btn tig-btn-secondary">&larr; Back</a></div>
        <form method="post" class="tig-form"><?php wp_nonce_field('tig_stage_save','tig_nonce'); ?>
        <div class="tig-form-row"><label>Stage Name *</label><input type="text" name="name" value="<?php echo esc_attr($stage->name??''); ?>" required class="tig-input"></div>
        <div class="tig-form-row tig-form-cols">
            <div><label>Location</label><input type="text" name="location" value="<?php echo esc_attr($stage->location??''); ?>" class="tig-input"></div>
            <div><label>Capacity</label><input type="number" name="capacity" value="<?php echo esc_attr($stage->capacity??0); ?>" class="tig-input"></div>
            <div><label>Color</label><input type="color" name="color" value="<?php echo esc_attr($stage->color??'#e63946'); ?>" class="tig-input"></div>
        </div>
        <div class="tig-form-actions"><button type="submit" class="tig-btn tig-btn-primary">Save Stage</button></div>
        </form></div><?php return;
    }
    $stages=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}tig_stages ORDER BY name");
    ?><div class="tig-wrap"><div class="tig-page-header"><h1>Stages</h1><a href="<?php echo admin_url('admin.php?page=tig-festival-stages&action=add'); ?>" class="tig-btn tig-btn-primary">+ Add Stage</a></div>
    <table class="tig-table tig-table-full"><thead><tr><th>Name</th><th>Location</th><th>Capacity</th><th>Color</th><th>Actions</th></tr></thead><tbody>
    <?php foreach($stages as $s): ?><tr>
        <td><strong><?php echo esc_html($s->name); ?></strong></td><td><?php echo esc_html($s->location); ?></td>
        <td><?php echo number_format($s->capacity); ?></td>
        <td><span class="tig-color-dot" style="background:<?php echo esc_attr($s->color); ?>"></span> <?php echo esc_html($s->color); ?></td>
        <td><a href="<?php echo admin_url('admin.php?page=tig-festival-stages&action=edit&id='.$s->id); ?>" class="tig-link-edit">Edit</a> | <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=tig-festival-stages&action=delete&id='.$s->id),'tig_delete_stage_'.$s->id); ?>" class="tig-link-delete" onclick="return confirm('Delete?')">Delete</a></td>
    </tr><?php endforeach; ?></tbody></table></div><?php
}

function tig_admin_settings() {
    if ('POST'===$_SERVER['REQUEST_METHOD']&&isset($_POST['tig_settings_nonce'])) {
        if (!wp_verify_nonce($_POST['tig_settings_nonce'],'tig_settings_save')) { wp_die('Security check failed.'); }
        update_option('tig_festival_name',sanitize_text_field($_POST['festival_name']));
        update_option('tig_festival_dates',sanitize_text_field($_POST['festival_dates']));
        update_option('tig_festival_location',sanitize_text_field($_POST['festival_location']));
        echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
    }
    ?><div class="tig-wrap"><div class="tig-page-header"><h1>Settings</h1></div>
    <form method="post" class="tig-form"><?php wp_nonce_field('tig_settings_save','tig_settings_nonce'); ?>
    <div class="tig-form-row"><label>Festival Name</label><input type="text" name="festival_name" value="<?php echo esc_attr(get_option('tig_festival_name','TIG Festival 2025')); ?>" class="tig-input"></div>
    <div class="tig-form-row"><label>Festival Dates</label><input type="text" name="festival_dates" value="<?php echo esc_attr(get_option('tig_festival_dates','July 18-20, 2025')); ?>" class="tig-input"></div>
    <div class="tig-form-row"><label>Location</label><input type="text" name="festival_location" value="<?php echo esc_attr(get_option('tig_festival_location','Budapest, Hungary')); ?>" class="tig-input"></div>
    <div class="tig-form-actions"><button type="submit" class="tig-btn tig-btn-primary">Save Settings</button></div>
    </form></div><?php
}

add_shortcode('tig_schedule','tig_shortcode_schedule');
function tig_shortcode_schedule($atts) {
    global $wpdb;
    $atts  = shortcode_atts(array('date'=>''),$atts,'tig_schedule');
    $where = "WHERE e.status='published'";
    if (!empty($atts['date'])) { $where .= $wpdb->prepare(" AND e.event_date=%s",$atts['date']); }
    $events = $wpdb->get_results("SELECT e.*,a.name AS artist_name,s.name AS stage_name,s.color AS stage_color FROM {$wpdb->prefix}tig_events e LEFT JOIN {$wpdb->prefix}tig_artists a ON e.artist_id=a.id LEFT JOIN {$wpdb->prefix}tig_stages s ON e.stage_id=s.id $where ORDER BY e.event_date,e.start_time");
    if (empty($events)) return '<p class="tig-no-events">No events found.</p>';
    $grouped=array(); foreach($events as $ev) { $grouped[$ev->event_date][]=$ev; }
    ob_start();
    echo '<div class="tig-schedule-wrap">';
    foreach ($grouped as $date=>$day) {
        echo '<div class="tig-day-block"><h2 class="tig-day-title">'.esc_html(date('l, F j, Y',strtotime($date))).'</h2><div class="tig-events-list">';
        foreach ($day as $ev) {
            $c=esc_attr($ev->stage_color??'#555');
            echo '<div class="tig-event-card" style="border-left-color:'.$c.'">';
            echo '<div class="tig-event-time">'.esc_html(substr($ev->start_time,0,5).' - '.substr($ev->end_time,0,5)).'</div>';
            echo '<div class="tig-event-title">'.esc_html($ev->title).'</div>';
            if ($ev->stage_name) echo '<div class="tig-event-stage" style="color:'.$c.'">&#9654; '.esc_html($ev->stage_name).'</div>';
            echo '</div>';
        }
        echo '</div></div>';
    }
    echo '</div>';
    return ob_get_clean();
}

add_shortcode('tig_artists','tig_shortcode_artists');
function tig_shortcode_artists($atts) {
    global $wpdb;
    $artists=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}tig_artists ORDER BY name");
    if (empty($artists)) return '<p>No artists found.</p>';
    ob_start(); echo '<div class="tig-artists-grid">';
    foreach ($artists as $a) {
        echo '<div class="tig-artist-card">';
        if ($a->image_url) echo '<img src="'.esc_url($a->image_url).'" alt="'.esc_attr($a->name).'" class="tig-artist-img">'; else echo '<div class="tig-artist-placeholder">&#127932;</div>';
        echo '<h3 class="tig-artist-name">'.esc_html($a->name).'</h3>';
        if ($a->genre) echo '<p class="tig-artist-genre">'.esc_html($a->genre).'</p>';
        if ($a->bio)   echo '<p class="tig-artist-bio">'.esc_html($a->bio).'</p>';
        echo '</div>';
    }
    echo '</div>'; return ob_get_clean();
}

add_shortcode('tig_stages','tig_shortcode_stages');
function tig_shortcode_stages($atts) {
    global $wpdb;
    $stages=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}tig_stages ORDER BY name");
    if (empty($stages)) return '<p>No stages found.</p>';
    ob_start(); echo '<div class="tig-stages-grid">';
    foreach ($stages as $s) {
        echo '<div class="tig-stage-card" style="border-top-color:'.esc_attr($s->color).'">';
        echo '<h3 style="color:'.esc_attr($s->color).'">'.esc_html($s->name).'</h3>';
        if ($s->location) echo '<p>&#128205; '.esc_html($s->location).'</p>';
        if ($s->capacity) echo '<p>&#128101; Capacity: '.number_format($s->capacity).'</p>';
        echo '</div>';
    }
    echo '</div>'; return ob_get_clean();
}

add_action('wp_enqueue_scripts','tig_frontend_styles');
function tig_frontend_styles() {
    wp_enqueue_style('tig-frontend',TIG_PLUGIN_URL.'assets/frontend.css',array(),TIG_VERSION);
}
