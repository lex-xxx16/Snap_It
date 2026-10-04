<footer class="footer-lux">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <span class="footer-brand">
                    <svg class="brand-mark" viewBox="0 0 26 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1.5 16.5 9 3l5.5 9.5M11 16.5 17 6l7.5 10.5"/></svg>
                    Snap It
                </span>
                <p>Photo customization booth and rental, composed for the occasions you&rsquo;ll want to remember &mdash; capture, customize, celebrate.</p>
            </div>
            <div class="col-6 col-lg-3 offset-lg-1">
                <h6>Explore</h6>
                <ul>
                    <li><a href="<?= e(site_url()) ?>">Home</a></li>
                    <li><a href="<?= e(site_url('booking/create.php')) ?>">Reserve an event</a></li>
                    <?php if (is_loggedin()): ?>
                        <li><a href="<?= e(site_url('gallery/index.php')) ?>">Gallery</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-6 col-lg-3">
                <h6>Account</h6>
                <ul>
                    <?php if (is_loggedin()): ?>
                        <li><a href="<?= e(site_url('users/profile.php')) ?>">Profile</a></li>
                        <li><a href="<?= e(site_url('users/logout.php')) ?>">Sign out</a></li>
                    <?php else: ?>
                        <li><a href="<?= e(site_url('users/login.php')) ?>">Sign in</a></li>
                        <li><a href="<?= e(site_url('users/register.php')) ?>">Create account</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="footer-base">
            <span>&copy; <?= date('Y') ?> Snap It. All rights reserved.</span>
            <span>Photo Customization Booth &amp; Rental System</span>
        </div>
    </div>
</footer>
</body>
</html>
