<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/config.php';

$packages_stmt = mysqli_prepare($conn, "SELECT * FROM packages WHERE is_active = 1 AND package_type = 'event' ORDER BY base_price ASC");
mysqli_stmt_execute($packages_stmt);
$packages = mysqli_stmt_get_result($packages_stmt);

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/alert.php';
?>

<section class="hero-lux">
    <div class="hero-light" aria-hidden="true"></div>
    <div class="hero-curtain" aria-hidden="true"></div>
    <div class="hero-floor" aria-hidden="true"></div>

    <div class="hero-copy">
        <h1>We are artisans<br>elevating moments</h1>
        <p class="hero-sub">Capture. Customize. Celebrate. An all-in-one photo customization booth and rental experience, tailored to make every event unforgettable.</p>
        <div class="hero-actions">
        <?php if (!is_loggedin()): ?>
            <a href="<?= e(site_url('booking/create.php')) ?>" class="btn btn-snapit">Book a Consultation</a>
            <a href="<?= e(site_url('users/register.php')) ?>" class="btn btn-ghost">Create Account</a>
        <?php else: ?>
            <a href="<?= e(site_url('booking/create.php')) ?>" class="btn btn-snapit">Book a Consultation</a>
            <a href="<?= e(site_url('walkin/index.php')) ?>" class="btn btn-ghost">Walk-in Photo</a>
        <?php endif; ?>
        </div>
    </div>

    <div class="hero-chair">
<svg viewBox="0 0 320 300" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Cream lounge armchair with a butter-yellow cushion">
  <defs>
    <linearGradient id="cr" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#f8f2e6"/><stop offset="1" stop-color="#d8cbb4"/></linearGradient>
    <linearGradient id="crTop" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fffaf0"/><stop offset="1" stop-color="#e3d6bf"/></linearGradient>
    <linearGradient id="crSide" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#d9ccb5"/><stop offset="1" stop-color="#b9aa90"/></linearGradient>
    <linearGradient id="bt" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#f5e1a0"/><stop offset="1" stop-color="#d3b160"/></linearGradient>
    <linearGradient id="wd" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#4a2f1a"/><stop offset=".45" stop-color="#7a5232"/><stop offset="1" stop-color="#3a2315"/></linearGradient>
    <linearGradient id="wdDark" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#2c1b0f"/><stop offset="1" stop-color="#3d2616"/></linearGradient>
    <filter id="blur" x="-20%" y="-200%" width="140%" height="500%"><feGaussianBlur stdDeviation="7"/></filter>
  </defs>
  <ellipse cx="162" cy="284" rx="138" ry="13" fill="#000" opacity=".55" filter="url(#blur)"/>
  <!-- rear legs & back posts -->
  <rect x="88" y="206" width="9" height="70" rx="4" fill="url(#wdDark)"/>
  <rect x="226" y="206" width="9" height="70" rx="4" fill="url(#wdDark)"/>
  <rect x="82" y="26" width="9" height="184" rx="4.5" fill="url(#wdDark)"/>
  <rect x="232" y="30" width="9" height="180" rx="4.5" fill="url(#wdDark)"/>
  <!-- back cushion -->
  <g transform="rotate(-3 160 90)">
    <rect x="86" y="34" width="150" height="116" rx="20" fill="url(#cr)"/>
    <rect x="86" y="34" width="150" height="116" rx="20" fill="none" stroke="#fff" stroke-opacity=".35"/>
    <!-- butter pillow -->
    <g transform="rotate(-9 124 104)">
      <rect x="88" y="64" width="76" height="74" rx="15" fill="url(#bt)"/>
      <path d="M96 72c20 4 44 4 62-2" stroke="#fff" stroke-opacity=".35" fill="none" stroke-linecap="round"/>
    </g>
  </g>
  <!-- seat -->
  <path d="M54 172 Q162 148 270 172 L272 198 Q162 218 52 198 Z" fill="url(#crTop)"/>
  <path d="M52 198 Q162 218 272 198 L270 218 Q162 238 54 218 Z" fill="url(#crSide)"/>
  <path d="M52 198 Q162 218 272 198" stroke="#fff" stroke-opacity=".45" fill="none"/>
  <!-- seat rail -->
  <path d="M54 224 Q162 244 270 224" stroke="url(#wd)" stroke-width="8" stroke-linecap="round" fill="none"/>
  <!-- arms -->
  <rect x="38" y="112" width="74" height="10" rx="5" fill="url(#wd)"/>
  <rect x="214" y="108" width="74" height="10" rx="5" fill="url(#wd)"/>
  <rect x="46" y="118" width="10" height="112" rx="5" fill="url(#wd)"/>
  <rect x="268" y="114" width="10" height="116" rx="5" fill="url(#wd)"/>
  <!-- front legs -->
  <g transform="rotate(4 58 230)"><rect x="52" y="226" width="11" height="54" rx="5" fill="url(#wd)"/></g>
  <g transform="rotate(-4 272 230)"><rect x="264" y="226" width="11" height="54" rx="5" fill="url(#wd)"/></g>
</svg>
    </div>

    <div class="hero-stats">
        <div><div class="stat-num">10</div><div class="stat-label">Recipients per Delivery</div></div>
        <div><div class="stat-num">PNG</div><div class="stat-label">Instant Downloads</div></div>
        <div><div class="stat-num">0</div><div class="stat-label">Double Bookings</div></div>
    </div>
</section>

<section class="container section-lux" id="experience">
    <div class="section-title">
        <span class="eyebrow">The Experience</span>
        <h2>Crafted down to every frame</h2>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card feature-card h-100">
                <span class="feature-no">01</span>
                <i class="fa-solid fa-camera feature-icon"></i>
                <h5 class="mb-3">The Live Capture Suite</h5>
                <p class="text-muted mb-0">Filters, presets, layouts &mdash; your guests shoot, retake, and keep every shot with a graceful countdown.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card feature-card h-100">
                <span class="feature-no">02</span>
                <i class="fa-regular fa-calendar-check feature-icon"></i>
                <h5 class="mb-3">Seamless Reservations</h5>
                <p class="text-muted mb-0">Prevent double-bookings with conflict checks and choose the package that fits the scale of your event.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card feature-card h-100">
                <span class="feature-no">03</span>
                <i class="fa-solid fa-envelopes-bulk feature-icon"></i>
                <h5 class="mb-3">Instant Photo Delivery</h5>
                <p class="text-muted mb-0">Email up to 10 recipients with the finished photo set, or download directly as PNG.</p>
            </div>
        </div>
    </div>
</section>

<section class="container section-lux" id="packages">
    <div class="section-title">
        <span class="eyebrow">Our Packages</span>
        <h2>Choose your collection</h2>
    </div>
    <div class="row g-4">
        <?php while ($pkg = mysqli_fetch_assoc($packages)): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card card-package h-100">
                    <div class="card-header py-3 text-center">
                        <h4 class="mb-0"><?= e($pkg['name']) ?></h4>
                        <div class="small text-muted mt-2"><?= (int)$pkg['duration_hours'] ?> hours &bull;
                            <?= (int)$pkg['softcopy_count'] ?> soft / <?= (int)$pkg['hardcopy_count'] ?> hard copies
                        </div>
                    </div>
                    <div class="card-body p-4 pt-3">
                        <p class="text-muted small mb-3"><?= e($pkg['description'] ?? '') ?></p>
                        <div class="mb-3">
                            <span class="badge badge-snapit me-2"><i class="fa-regular fa-file-image me-1"></i><?= (int)$pkg['softcopy_count'] ?> Soft Copies</span>
                            <span class="badge bg-secondary"><i class="fa-solid fa-print me-1"></i><?= (int)$pkg['hardcopy_count'] ?> Hard Copies</span>
                        </div>
                        <ul class="list-unstyled mb-4 small">
                            <li class="mb-2"><i class="fa-solid fa-check me-2 text-primary"></i>Live countdown capture</li>
                            <li class="mb-2"><i class="fa-solid fa-check me-2 text-primary"></i>Filters, layouts &amp; frame designs</li>
                            <li class="mb-2"><i class="fa-solid fa-check me-2 text-primary"></i>Conflict-free date booking</li>
                            <?php if ($pkg['has_softcopy_addon']): ?>
                                <li class="mb-2"><i class="fa-solid fa-plus me-2 text-primary"></i>Softcopy add-on available</li>
                            <?php endif; ?>
                        </ul>
                        <div class="d-flex align-items-end justify-content-between pt-3 border-top">
                            <div>
                                <div class="small text-muted">Starting at</div>
                                <div class="price-tag"><?= e(format_money($pkg['base_price'])) ?></div>
                                <?php if ($pkg['has_softcopy_addon'] && $pkg['softcopy_addon_price'] > 0): ?>
                                    <div class="small text-muted mt-1">+ <?= e(format_money($pkg['softcopy_addon_price'])) ?> softcopy add-on</div>
                                <?php endif; ?>
                            </div>
                            <a href="<?= e(site_url('booking/create.php?package=' . (int)$pkg['package_id'])) ?>" class="btn btn-snapit">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php
$cfg_path = __DIR__ . '/includes/config.php';
$db_ready = false;
if (file_exists($cfg_path)) {
    $cfg_include = @include $cfg_path;
    if (isset($conn) && $conn instanceof mysqli && !mysqli_connect_error()) {
        $tbl = @mysqli_query($conn, "SHOW TABLES LIKE 'packages'");
        if ($tbl && mysqli_num_rows($tbl) > 0) {
            $db_ready = true;
        }
    }
}
?>

<section class="container section-lux pb-0">
    <div class="card cta-panel">
        <span class="eyebrow mb-3">Begin</span>
        <h3>Ready to elevate your event?</h3>
        <p>From debuts to corporate launches, we have a collection for every celebration.</p>
        <div>
        <?php if (!$db_ready): ?>
            <a href="<?= e(site_url('install.php')) ?>" class="btn btn-snapit btn-lg px-5">
                Run the Installer to get started
            </a>
        <?php else: ?>
            <?php if (!is_loggedin()): ?>
                <a href="<?= e(site_url('users/register.php')) ?>" class="btn btn-snapit btn-lg px-5 me-2">
                    Create Your Account
                </a>
                <a href="<?= e(site_url('booking/create.php')) ?>" class="btn btn-ghost btn-lg px-5">
                    Book Now
                </a>
            <?php else: ?>
                <a href="<?= e(site_url('booking/create.php')) ?>" class="btn btn-snapit btn-lg px-5">
                    Book Your Next Event
                </a>
            <?php endif; ?>
        <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
