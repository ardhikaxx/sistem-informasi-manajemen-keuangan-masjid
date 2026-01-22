<!-- Bottom Navigation -->
<nav class="navbottom">
    <div class="container-fluid">
        <div class="d-flex justify-content-around align-items-center navbottom-wrapper">
            <!-- Dashboard -->
            <div class="navbottom-item text-center {{ request()->routeIs('admins.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admins.dashboard') }}" class="text-decoration-none d-flex flex-column align-items-center">
                    <div class="navbottom-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <span class="navbottom-label mt-1">Dashboard</span>
                </a>
            </div>
            
            <!-- Manajemen Keuangan -->
            <div class="navbottom-item text-center">
                <a href="#" class="text-decoration-none d-flex flex-column align-items-center">
                    <div class="navbottom-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <span class="navbottom-label mt-1">Keuangan</span>
                </a>
            </div>
            
            <!-- Manajemen Laporan -->
            <div class="navbottom-item text-center">
                <a href="#" class="text-decoration-none d-flex flex-column align-items-center">
                    <div class="navbottom-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="fas fa-file"></i>
                    </div>
                    <span class="navbottom-label mt-1">Laporan</span>
                </a>
            </div>
            
            <!-- Profile -->
            <div class="navbottom-item text-center">
                <a href="#" class="text-decoration-none d-flex flex-column align-items-center">
                    <div class="navbottom-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="navbottom-label mt-1">Profil</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
    .navbottom {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        background: white;
        z-index: 1030;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        transition: var(--transition);
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08);
        padding: 10px 0;
        /* Menggunakan safe-area-inset untuk iOS/Android dengan notch */
        padding-bottom: max(10px, env(safe-area-inset-bottom));
    }

    .navbottom-wrapper {
        padding: 0 15px;
        max-width: 100%;
    }

    .navbottom-item {
        flex: 1;
        padding: 5px 0;
        transition: var(--transition);
        min-width: 0;
        max-width: 100px;
    }

    .navbottom-item a {
        color: inherit;
        padding: 5px;
    }

    .navbottom-item.active .navbottom-icon {
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        color: white;
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(29, 138, 78, 0.3);
    }

    .navbottom-item.active .navbottom-label {
        color: var(--primary-color);
        font-weight: 600;
        transform: translateY(-5px);
    }

    .navbottom-icon {
        width: 42px;
        height: 42px;
        background-color: var(--light-color);
        color: var(--primary-dark);
        font-size: 1rem;
        transition: var(--transition);
        margin: 0 auto;
    }

    .navbottom-item:not(.active):hover .navbottom-icon {
        background-color: rgba(29, 138, 78, 0.1);
        transform: translateY(-2px);
    }

    .navbottom-label {
        font-size: 0.65rem;
        color: var(--text-light);
        transition: var(--transition);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        display: block;
        margin-top: 4px;
    }

    /* Mobile Small (320px - 575px) */
    @media (max-width: 575px) {
        .navbottom {
            padding: 8px 0;
            padding-bottom: max(8px, env(safe-area-inset-bottom));
        }

        .navbottom-wrapper {
            padding: 0 10px;
        }

        .navbottom-icon {
            width: 40px;
            height: 40px;
            font-size: 0.95rem;
        }

        .navbottom-label {
            font-size: 0.6rem;
        }

        .navbottom-item {
            max-width: 80px;
        }
    }

    /* Tablet (768px - 991px) */
    @media (min-width: 768px) {
        .navbottom {
            padding: 12px 0;
            padding-bottom: max(12px, env(safe-area-inset-bottom));
        }

        .navbottom-wrapper {
            padding: 0 20px;
        }

        .navbottom-icon {
            width: 48px;
            height: 48px;
            font-size: 1.1rem;
        }

        .navbottom-label {
            font-size: 0.7rem;
            margin-top: 5px;
        }

        .navbottom-item {
            max-width: 110px;
        }

        .navbottom-item.active .navbottom-icon {
            transform: translateY(-6px);
        }

        .navbottom-item.active .navbottom-label {
            transform: translateY(-6px);
        }
    }

    /* Desktop (992px+) */
    @media (min-width: 992px) {
        .navbottom {
            padding: 15px 0;
            padding-bottom: max(15px, env(safe-area-inset-bottom));
        }

        .navbottom-wrapper {
            padding: 0 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .navbottom-icon {
            width: 52px;
            height: 52px;
            font-size: 1.2rem;
        }

        .navbottom-label {
            font-size: 0.75rem;
            margin-top: 6px;
        }

        .navbottom-item {
            max-width: 120px;
        }

        .navbottom-item.active .navbottom-icon {
            transform: translateY(-8px);
        }

        .navbottom-item.active .navbottom-label {
            transform: translateY(-8px);
        }
    }

    /* Large Desktop (1200px+) */
    @media (min-width: 1200px) {
        .navbottom-wrapper {
            max-width: 1600px;
        }

        .navbottom-icon {
            width: 55px;
            height: 55px;
            font-size: 1.25rem;
        }

        .navbottom-label {
            font-size: 0.8rem;
        }
    }

    /* Support untuk landscape mode di mobile */
    @media (max-height: 500px) and (orientation: landscape) {
        .navbottom {
            padding: 6px 0;
            padding-bottom: max(6px, env(safe-area-inset-bottom));
        }

        .navbottom-icon {
            width: 38px;
            height: 38px;
            font-size: 0.9rem;
        }

        .navbottom-label {
            font-size: 0.6rem;
            margin-top: 3px;
        }
    }
</style>