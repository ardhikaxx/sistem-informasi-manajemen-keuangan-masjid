<!-- Floating Bottom Navigation -->
<nav class="navbottom">
    <div class="navbottom-container">
        <div class="navbottom-wrapper">
            <!-- Dashboard -->
            <div class="navbottom-item {{ request()->routeIs('admins.dashboard') || request()->is('/') ? 'active' : '' }}"
                data-label="Dashboard">
                <a href="{{ route('admins.dashboard') }}" class="navbottom-link">
                    <div class="navbottom-icon-wrapper">
                        <i class="fas fa-home navbottom-icon"></i>
                    </div>
                    <span class="navbottom-label">Dashboard</span>
                </a>
            </div>

            <!-- Manajemen Keuangan -->
            <div class="navbottom-item {{ request()->routeIs('admins.manajemen-keuangan') || request()->is('/') ? 'active' : '' }}" data-label="Keuangan">
                <a href="{{ route('admins.manajemen-keuangan') }}" class="navbottom-link">
                    <div class="navbottom-icon-wrapper">
                        <i class="fas fa-sack-dollar navbottom-icon"></i>
                    </div>
                    <span class="navbottom-label">Keuangan</span>
                </a>
            </div>

            <!-- Manajemen Laporan -->
            <div class="navbottom-item {{ request()->routeIs('admins.manajemen-laporan') || request()->is('/') ? 'active' : '' }}" data-label="Laporan">
                <a href="{{ route('admins.manajemen-laporan') }}" class="navbottom-link">
                    <div class="navbottom-icon-wrapper">
                        <i class="fas fa-receipt navbottom-icon"></i>
                    </div>
                    <span class="navbottom-label">Laporan</span>
                </a>
            </div>

            <!-- Pengaturan Profil -->
            <div class="navbottom-item {{ request()->routeIs('admins.pengaturan-profil') || request()->is('/') ? 'active' : '' }}" data-label="Profil">
                <a href="{{ route('admins.pengaturan-profil') }}" class="navbottom-link">
                    <div class="navbottom-icon-wrapper">
                        <i class="fas fa-user navbottom-icon"></i>
                    </div>
                    <span class="navbottom-label">Profil</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Hide bottom navigation on desktop */
    @media (min-width: 992px) {
        .navbottom {
            display: none !important;
        }
    }

    /* Floating Bottom Navigation */
    .navbottom {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1030;
        width: calc(100% - 32px);
        max-width: 520px;
        padding: 0;
        transition: var(--transition);
    }

    .navbottom-container {
        background: #FFFFFF;
        border-radius: 28px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 12px 16px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: var(--transition);
    }

    .navbottom-wrapper {
        display: flex;
        justify-content: space-around;
        align-items: center;
        gap: 4px;
        transition: var(--transition);
    }

    .navbottom-item {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        min-width: 60px;
        max-width: 100%;
    }

    .navbottom-item.compact {
        flex: 0.8;
        min-width: 50px;
    }

    .navbottom-item.active {
        flex: 1;
        min-width: 50px;
    }

    .navbottom-link {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        width: 100%;
        height: 100%;
        padding: 6px 8px;
        position: relative;
        transition: var(--transition);
    }

    .navbottom-icon-wrapper {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
        background: transparent;
    }

    .navbottom-item.compact .navbottom-icon-wrapper {
        width: 42px;
        height: 42px;
    }

    .navbottom-item.active .navbottom-icon-wrapper {
        width: 52px;
        height: 52px;
    }

    .navbottom-icon {
        font-size: 20px;
        color: #8E8E93;
        transition: all 0.3s ease;
    }

    .navbottom-label {
        font-size: 12px;
        font-weight: 600;
        color: #8E8E93;
        margin-top: 4px;
        opacity: 0;
        width: 0;
        height: 0;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        text-align: center;
        white-space: nowrap;
    }

    /* Active State */
    .navbottom-item.active .navbottom-icon-wrapper {
        background: linear-gradient(135deg, rgba(29, 138, 78, 0.15) 0%, rgba(46, 204, 113, 0.15) 100%);
        transform: translateY(-4px);
    }

    .navbottom-item.active .navbottom-icon {
        color: var(--primary-color);
        transform: scale(1.1);
    }

    .navbottom-item.active .navbottom-label {
        color: var(--primary-color);
        opacity: 1;
        width: auto;
        height: auto;
        display: block;
        transform: translateY(0);
    }

    /* Hover Effect */
    .navbottom-item:not(.active):hover .navbottom-icon-wrapper {
        background: rgba(142, 142, 147, 0.08);
        transform: scale(1.05);
    }

    .navbottom-item:not(.active):hover .navbottom-icon {
        color: var(--primary-color);
    }

    .navbottom-item:not(.active):hover .navbottom-label {
        opacity: 1;
        width: auto;
        height: auto;
        color: var(--text-light);
    }

    /* Mobile Extra Small (max-width: 360px) */
    @media (max-width: 360px) {
        .navbottom {
            bottom: 16px;
            width: calc(100% - 24px);
            max-width: 320px;
        }

        .navbottom-container {
            padding: 10px 12px;
            border-radius: 24px;
        }

        .navbottom-wrapper {
            gap: 2px;
        }

        .navbottom-icon-wrapper {
            width: 40px;
            height: 40px;
        }

        .navbottom-item.compact .navbottom-icon-wrapper {
            width: 36px;
            height: 36px;
        }

        .navbottom-item.active .navbottom-icon-wrapper {
            width: 44px;
            height: 44px;
        }

        .navbottom-icon {
            font-size: 18px;
        }

        .navbottom-label {
            font-size: 10px;
        }
    }

    /* Mobile Small (361px - 375px) */
    @media (min-width: 361px) and (max-width: 375px) {
        .navbottom {
            bottom: 18px;
            width: calc(100% - 28px);
            max-width: 340px;
        }

        .navbottom-container {
            padding: 11px 14px;
            border-radius: 26px;
        }

        .navbottom-wrapper {
            gap: 3px;
        }

        .navbottom-icon-wrapper {
            width: 42px;
            height: 42px;
        }

        .navbottom-item.compact .navbottom-icon-wrapper {
            width: 38px;
            height: 38px;
        }

        .navbottom-item.active .navbottom-icon-wrapper {
            width: 46px;
            height: 46px;
        }

        .navbottom-icon {
            font-size: 19px;
        }

        .navbottom-label {
            font-size: 11px;
        }
    }

    /* Mobile Medium (376px - 575px) */
    @media (min-width: 376px) and (max-width: 575px) {
        .navbottom {
            bottom: 20px;
            width: calc(100% - 32px);
            max-width: 400px;
        }

        .navbottom-container {
            padding: 12px 16px;
            border-radius: 28px;
        }

        .navbottom-wrapper {
            gap: 4px;
        }

        .navbottom-icon-wrapper {
            width: 46px;
            height: 46px;
        }

        .navbottom-item.compact .navbottom-icon-wrapper {
            width: 40px;
            height: 40px;
        }

        .navbottom-item.active .navbottom-icon-wrapper {
            width: 50px;
            height: 50px;
        }

        .navbottom-icon {
            font-size: 20px;
        }

        .navbottom-label {
            font-size: 12px;
        }
    }

    /* Tablet Small (576px - 767px) */
    @media (min-width: 576px) and (max-width: 767px) {
        .navbottom {
            bottom: 24px;
            width: calc(100% - 40px);
            max-width: 480px;
        }

        .navbottom-container {
            padding: 14px 20px;
            border-radius: 30px;
        }

        .navbottom-wrapper {
            gap: 6px;
        }

        .navbottom-icon-wrapper {
            width: 50px;
            height: 50px;
        }

        .navbottom-item.compact .navbottom-icon-wrapper {
            width: 44px;
            height: 44px;
        }

        .navbottom-item.active .navbottom-icon-wrapper {
            width: 56px;
            height: 56px;
        }

        .navbottom-icon {
            font-size: 22px;
        }

        .navbottom-label {
            font-size: 13px;
        }
    }

    /* Tablet Medium (768px - 991px) */
    @media (min-width: 768px) and (max-width: 991px) {
        .navbottom {
            bottom: 28px;
            width: auto;
            max-width: 520px;
        }

        .navbottom-container {
            padding: 15px 24px;
            border-radius: 32px;
        }

        .navbottom-wrapper {
            gap: 8px;
        }

        .navbottom-icon-wrapper {
            width: 52px;
            height: 52px;
        }

        .navbottom-item.compact .navbottom-icon-wrapper {
            width: 46px;
            height: 46px;
        }

        .navbottom-item.active .navbottom-icon-wrapper {
            width: 58px;
            height: 58px;
        }

        .navbottom-icon {
            font-size: 23px;
        }

        .navbottom-label {
            font-size: 14px;
        }
    }

    /* Desktop Small (992px - 1199px) */
    @media (min-width: 992px) and (max-width: 1199px) {
        .navbottom {
            display: none;
        }
    }

    /* Desktop Medium (1200px+) */
    @media (min-width: 1200px) {
        .navbottom {
            display: none;
        }
    }

    /* Landscape Mode for Mobile */
    @media (max-height: 500px) and (orientation: landscape) {
        .navbottom {
            bottom: 12px;
            max-width: 500px;
        }

        .navbottom-container {
            padding: 8px 16px;
            border-radius: 24px;
        }

        .navbottom-wrapper {
            gap: 4px;
        }

        .navbottom-icon-wrapper {
            width: 40px;
            height: 40px;
        }

        .navbottom-item.compact .navbottom-icon-wrapper {
            width: 36px;
            height: 36px;
        }

        .navbottom-item.active .navbottom-icon-wrapper {
            width: 44px;
            height: 44px;
        }

        .navbottom-icon {
            font-size: 18px;
        }

        .navbottom-label {
            font-size: 11px;
        }
    }

    /* Animation untuk smooth appearance */
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    }

    .navbottom {
        animation: slideUp 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    /* Smooth transition for active state */
    .navbottom-item {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .navbottom-link {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
</style>

<!-- JavaScript for navbottom (same as before) -->
<script>
    // JavaScript untuk mengatur status aktif dan animasi
    document.addEventListener('DOMContentLoaded', function() {
        const navItems = document.querySelectorAll('.navbottom-item');
        let activeItem = null;

        // Set initial active item
        navItems.forEach(item => {
            if (item.classList.contains('active')) {
                activeItem = item;
            }
        });

        // Apply compact class to non-active items
        updateNavItems();

        // Click handler for nav items
        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // If this is already active, do nothing
                if (this.classList.contains('active')) {
                    return;
                }

                // Remove active class from all items
                navItems.forEach(navItem => {
                    navItem.classList.remove('active');
                    navItem.classList.add('compact');
                });

                // Add active class to clicked item
                this.classList.add('active');
                this.classList.remove('compact');

                // Update active item reference
                activeItem = this;

                // Update other items
                updateNavItems();
            });
        });

        function updateNavItems() {
            navItems.forEach(item => {
                if (!item.classList.contains('active')) {
                    item.classList.add('compact');
                } else {
                    item.classList.remove('compact');
                }
            });
        }

        // Hover effect for non-active items
        navItems.forEach(item => {
            if (!item.classList.contains('active')) {
                item.addEventListener('mouseenter', function() {
                    const label = this.querySelector('.navbottom-label');
                    if (label) {
                        label.style.opacity = '1';
                        label.style.width = 'auto';
                        label.style.height = 'auto';
                    }
                });

                item.addEventListener('mouseleave', function() {
                    const label = this.querySelector('.navbottom-label');
                    if (label && !this.classList.contains('active')) {
                        label.style.opacity = '0';
                        label.style.width = '0';
                        label.style.height = '0';
                    }
                });
            }
        });
    });
</script>
