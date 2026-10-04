<?php
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@300;400;500;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="<?= e(site_url('includes/style/style.css')) ?>" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="<?= e(site_url('includes/strip.js')) ?>"></script>
    <script src="<?= e(site_url('includes/camfx.js')) ?>"></script>
    <meta name="theme-color" content="#120d09">
    <title>Snap It &mdash; Luxury Photo Booth Experiences</title>
    <?php if (is_loggedin()): ?>
    <script>
        // If the browser restores this page from its back/forward cache (for example after
        // logging out and pressing Back), reload it so the server can check the login again.
        window.addEventListener('pageshow', function (ev) {
            var nav = (performance.getEntriesByType('navigation')[0] || {}).type;
            if (ev.persisted || nav === 'back_forward') { window.location.reload(); }
        });
    </script>
    <?php endif; ?>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-snapit">
    <div class="container-fluid container-lg">
        <a class="navbar-brand" href="<?= e(site_url()) ?>" aria-label="Snap It — home">
            <svg class="brand-mark" viewBox="0 0 26 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1.5 16.5 9 3l5.5 9.5M11 16.5 17 6l7.5 10.5"/></svg>
            <span>Snap It</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#snapitNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="snapitNavbar">
            <ul class="navbar-nav mx-lg-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= e(site_url()) ?>">Home</a></li>
                <?php if (!is_loggedin()): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= e(site_url('index.php')) ?>#experience">Experience</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(site_url('index.php')) ?>#packages">Packages</a></li>
                <?php endif; ?>
                <?php if (is_loggedin()): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= e(site_url('booking/index.php')) ?>">Bookings</a></li>
                    <?php if (is_customer()): ?><li class="nav-item"><a class="nav-link" href="<?= e(site_url('walkin/index.php')) ?>">Walk-in</a></li><?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="<?= e(site_url('gallery/index.php')) ?>">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(site_url('booth/index.php')) ?>">Studio</a></li>
                <?php endif; ?>
                <?php if (is_staff()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Admin</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= e(site_url('admin/index.php')) ?>"><i class="fa-solid fa-gauge me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?= e(site_url('booking/index.php')) ?>"><i class="fa-regular fa-calendar me-2"></i>Bookings</a></li>
                            <li><a class="dropdown-item" href="<?= e(site_url('inventory/index.php')) ?>"><i class="fa-solid fa-boxes-stacked me-2"></i>Inventory</a></li>
                            <li><a class="dropdown-item" href="<?= e(site_url('inventory/print_queue.php')) ?>"><i class="fa-solid fa-print me-2"></i>Print Queue</a></li>
                            <li><a class="dropdown-item" href="<?= e(site_url('payment/index.php')) ?>"><i class="fa-solid fa-money-bill-wave me-2"></i>Payments</a></li>
                            <?php if (is_admin()): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= e(site_url('admin/customers.php')) ?>"><i class="fa-solid fa-users me-2"></i>Customers</a></li>
                                <li><a class="dropdown-item" href="<?= e(site_url('admin/staff.php')) ?>"><i class="fa-solid fa-user-tie me-2"></i>Staff</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <?php if (is_loggedin()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= e(site_url('users/profile.php')) ?>">
                            <i class="fa-regular fa-circle-user me-1"></i><?= e($_SESSION['name'] ?? 'Account') ?>
                            <span class="badge badge-snapit ms-1"><?= e(ucfirst($_SESSION['role'] ?? 'customer')) ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= e(site_url('users/logout.php')) ?>">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= e(site_url('users/login.php')) ?>">Login</a></li>
                    <li class="nav-item ms-lg-1"><a class="nav-link nav-cta" href="<?= e(site_url('users/register.php')) ?>">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
