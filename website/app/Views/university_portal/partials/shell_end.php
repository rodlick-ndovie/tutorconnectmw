<?php $activeNav = (string) ($active_nav ?? 'home'); ?>
        </main>

        <nav class="bottom-nav">
            <a href="<?= site_url('university-portal/dashboard') ?>" class="nav-item <?= $activeNav === 'home' ? 'active' : '' ?>">
                <i class="fas fa-house"></i>
                <span>Home</span>
            </a>
            <a href="<?= site_url('university-portal/complete-profile') ?>" class="nav-item <?= $activeNav === 'profile' ? 'active' : '' ?>">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="<?= site_url('university-portal/subscription') ?>" class="nav-item <?= $activeNav === 'subscription' ? 'active' : '' ?>">
                <i class="fas fa-crown"></i>
                <span>Premium</span>
            </a>
            <a href="<?= site_url('logout') ?>" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </nav>
    </div>
