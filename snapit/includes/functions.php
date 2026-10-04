<?php
if (session_status() === PHP_SESSION_NONE) {
    // Only accept session ids this server issued, and keep the cookie away from JavaScript.
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

// Never let the browser keep a copy of a page. After logout, the Back button has to
// ask the server again, and the server will find no logged-in session.
if (!headers_sent()) {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
}

// Completely end the current login: wipe every session value (user, role, booth session,
// CSRF token), destroy the session on the server, then start a new empty session with a
// brand-new id so a flash message can still be shown on the login page.
function destroy_login_session() {
    $_SESSION = [];
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    session_id(session_create_id());
    session_start();
}

function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function set_flash($message, $type = 'warning') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

function has_flash() {
    return isset($_SESSION['flash_message']);
}

function get_flash_message() {
    if (has_flash()) {
        $msg = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'warning';
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        return ['message' => $msg, 'type' => $type];
    }
    return null;
}

function is_loggedin() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function is_customer() {
    return is_loggedin() && ($_SESSION['role'] ?? '') === 'customer';
}

function is_staff() {
    return is_loggedin() && in_array(($_SESSION['role'] ?? ''), ['staff', 'admin']);
}

function is_admin() {
    return is_loggedin() && ($_SESSION['role'] ?? '') === 'admin';
}

function require_login() {
    if (!is_loggedin()) {
        set_flash('Please log in to access this page.', 'warning');
        redirect('../users/login.php');
    }
}

function require_staff() {
    require_login();
    if (!is_staff()) {
        set_flash('Staff access required.', 'danger');
        redirect('../index.php');
    }
}

function require_admin() {
    require_login();
    if (!is_admin()) {
        set_flash('Administrator access required.', 'danger');
        redirect('../index.php');
    }
}

function valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function status_badge_class($status) {
    $map = [
        'pending' => 'bg-warning text-dark',
        'confirmed' => 'bg-info text-dark',
        'paid' => 'bg-success',
        'completed' => 'bg-secondary',
        'cancelled' => 'bg-danger',
        'active' => 'bg-primary',
        'inactive' => 'bg-secondary',
        'printed' => 'bg-success',
        'error' => 'bg-danger',
    ];
    return $map[strtolower($status)] ?? 'bg-secondary';
}

function format_money($amount) {
    return 'PHP ' . number_format((float)$amount, 2);
}

function format_date($dateStr, $withTime = false) {
    if (empty($dateStr)) return '-';
    $ts = strtotime($dateStr);
    if ($withTime) return date('M j, Y h:i A', $ts);
    return date('M j, Y', $ts);
}

function is_date_past($dateStr) {
    $today = date('Y-m-d');
    return $dateStr < $today;
}

function time_to_minutes($timeStr) {
    list($h, $m) = explode(':', substr($timeStr, 0, 5));
    return ((int)$h * 60) + (int)$m;
}

function booking_has_conflict($conn, $event_date, $start_time, $duration_hours, $exclude_booking_id = null) {
    $start_min = time_to_minutes($start_time);
    $end_min = $start_min + ((int)$duration_hours * 60);

    $sql = "SELECT booking_id, start_time, duration_hours FROM bookings 
            WHERE event_date = ? AND booking_type = 'event' AND status NOT IN ('cancelled')";
    $params = [$event_date];
    $types = 's';
    if ($exclude_booking_id) {
        $sql .= " AND booking_id <> ?";
        $params[] = $exclude_booking_id;
        $types .= 'i';
    }
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $exist_start = time_to_minutes($row['start_time']);
        $exist_end = $exist_start + ((int)$row['duration_hours'] * 60);
        if ($start_min < $exist_end && $end_min > $exist_start) {
            return true;
        }
    }
    return false;
}

function in_business_hours($start_time, $duration_hours) {
    $open = 8 * 60;
    $close = 22 * 60;
    $start_min = time_to_minutes($start_time);
    $end_min = $start_min + ((int)$duration_hours * 60);
    return $start_min >= $open && $end_min <= $close;
}

function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf($token) {
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function site_base() {
    static $base = null;
    if ($base !== null) return $base;
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    $needle = '/snapit/';
    $pos = strpos($script, $needle);
    if ($pos === false) {
        $base = rtrim(dirname($script), '/');
    } else {
        $base = substr($script, 0, $pos) . '/snapit';
    }
    return $base;
}

function site_url($path = '') {
    $b = site_base();
    if ($path === '') return $b . '/';
    $path = ltrim($path, '/');
    return $b . '/' . $path;
}

function current_booth_session_id($conn) {
    if (empty($_SESSION['active_session_id'])) return null;
    $stmt = mysqli_prepare($conn, "SELECT session_id FROM guest_sessions WHERE session_id = ? AND status = 'active' LIMIT 1");
    $sid = $_SESSION['active_session_id'];
    mysqli_stmt_bind_param($stmt, 'i', $sid);
    mysqli_stmt_execute($stmt);
    $r = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($r) > 0) return $sid;
    unset($_SESSION['active_session_id']);
    return null;
}

// ---- Real-camera pictures shown on the camera preset cards ----
// Maps a preset name to a Wikimedia Commons file (served through Special:FilePath,
// so no hashed upload paths are needed). Presets not listed fall back to an icon.
function camera_photo_url($preset_name, $width = 240) {
    static $files = [
        'Kodak Gold 200'        => 'Kodak_Gold_200_Film.jpg',
        'Kodak Portra 400'      => 'Kodak_Portra_400_120_film.jpg',
        'Fuji X Classic Chrome' => 'Fujifilm_X100V_9_feb_2020a.jpg',
        'Fuji Superia 400'      => 'Fujifilm_SUPERIA_200.jpg',
        'Canon G7X II'          => 'Canon_PowerShot_G7_X_Mark_II.jpg',
        'iPhone 5S'             => 'IPhone_5s_(model_A1457)-3319.jpg',
        'iPhone 4S'             => 'Black_iPhone_4s.jpg',
        'DV Camcorder'          => 'Sony_Handycam_MiniDV_DCR-VX2000_camcorder.jpg',
        'Disposable Flash'      => 'Kodak_FunSaver_35_(3332226374).jpg',
        'Polaroid Instant'      => 'Polaroid_636_Close_Up_instant_camera.jpg',
        'Y2K Digicam'           => 'Sony_Cyber-shot_DSC-W1.jpg',
        'B&W Film'              => 'ILFORD_HP5.jpg',
    ];
    if (!isset($files[$preset_name])) return null;
    return 'https://commons.wikimedia.org/wiki/Special:FilePath/' . rawurlencode($files[$preset_name]) . '?width=' . (int)$width;
}

// ---- Photo strip rendering (print_size "WxH" inches, e.g. 2x6 / 4x6) ----
function strip_cfg($r, $caption = '') {
    return [
        'cols' => (int)($r['grid_cols'] ?? 1), 'rows' => (int)($r['grid_rows'] ?? 1),
        'count' => (int)($r['photo_count'] ?? 1), 'print' => $r['print_size'] ?? '',
        'bc' => $r['border_color'] ?? '#6f42c1', 'bw' => (int)($r['border_width'] ?? 6),
        'bg' => $r['bg_color'] ?? '#ffffff', 'fg' => $r['text_color'] ?? '#6f42c1',
        'gap' => (int)($r['photo_gap'] ?? 10), 'rad' => (int)($r['photo_radius'] ?? 8),
        'pattern' => $r['pattern'] ?? '', 'filter' => $r['css_filter'] ?? 'none', 'caption' => $caption,
    ];
}
function strip_html($photos, $c, $id = '') {
    $w = 520; $ar = '';
    if (preg_match('/^(\d+)x(\d+)$/', $c['print'] ?? '', $m)) { $ar = "aspect-ratio:{$m[1]}/{$m[2]};"; $w = $m[1] <= 2 ? 270 : 400; }
    $bgimg = ($c['pattern'] ?? '') === 'dots' ? 'background-image:radial-gradient(rgba(128,128,128,.25) 1px,transparent 1.6px);background-size:14px 14px;' : '';
    $o = '<div ' . ($id ? 'id="' . e($id) . '" ' : '') . 'class="snap-strip" style="max-width:' . $w . 'px;' . $ar . $bgimg
       . '--bg:' . e($c['bg']) . ';--bc:' . e($c['bc']) . ';--bw:' . (int)$c['bw'] . 'px;--gap:' . (int)$c['gap'] . 'px;--rad:' . (int)$c['rad'] . 'px;--fg:' . e($c['fg']) . ';">';
    $o .= '<div class="snap-grid" style="grid-template-columns:repeat(' . (int)$c['cols'] . ',1fr);grid-template-rows:repeat(' . (int)$c['rows'] . ',1fr);">';
    $n = max((int)$c['count'], count($photos));
    for ($i = 0; $i < $n; $i++) {
        $o .= isset($photos[$i]) ? '<img src="' . e($photos[$i]) . '" alt="Photo ' . ($i+1) . '" crossorigin="anonymous" style="filter:' . e($c['filter']) . '">'
                                 : '<div class="snap-slot"><i class="fa-regular fa-image fa-2x"></i></div>';
    }
    return $o . '</div><div class="snap-foot">' . e($c['caption']) . '</div></div>';
}
