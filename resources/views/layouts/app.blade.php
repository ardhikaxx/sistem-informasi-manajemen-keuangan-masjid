<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard Admin - Keuangan Masjid')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #1D8A4E;
            --primary-dark: #146C43;
            --secondary-color: #28A745;
            --accent-color: #2ECC71;
            --light-color: #E8F5E9;
            --dark-color: #0F5132;
            --success-color: #27AE60;
            --warning-color: #F39C12;
            --card-bg: rgba(255, 255, 255, 0.98);
            --text-dark: #1C2833;
            --text-light: #566573;
            --shadow: 0 10px 30px rgba(29, 138, 78, 0.15);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --navbar-height: 70px;
            --navbottom-height: 85px;
            --sidebar-width: 280px;
            --sidebar-compact-width: 90px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            height: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f9f7;
            background-image:
                radial-gradient(circle at 10% 20%, rgba(232, 245, 233, 0.3) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(29, 138, 78, 0.1) 0%, transparent 20%);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            padding-bottom: var(--navbottom-height);
            transition: var(--transition);
        }

        /* Desktop Layout */
        @media (min-width: 992px) {
            body {
                padding-left: var(--sidebar-width);
                padding-bottom: 0;
            }

            body.sidebar-compact {
                padding-left: var(--sidebar-compact-width);
            }
        }

        .glass-effect {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            box-shadow: var(--shadow);
        }

        /* Navbar Top */
        .navbar {
            height: var(--navbar-height);
        }

        .navbar-brand {
            color: var(--primary-dark) !important;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .navbar-brand i {
            color: var(--primary-color);
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - var(--navbar-height) - var(--navbottom-height));
            padding: 20px 15px;
            transition: var(--transition);
        }

        @media (min-width: 992px) {
            .main-content {
                min-height: calc(100vh - var(--navbar-height));
                padding: 25px 30px;
                margin-left: 0;
            }

            body.sidebar-compact .main-content {
                padding-left: 30px;
            }
        }

        .page-title {
            color: var(--dark-color);
            font-weight: 700;
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(to right, var(--primary-color), var(--accent-color));
            border-radius: 2px;
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-text {
                display: none !important;
            }

            .profile-icon {
                width: 36px !important;
                height: 36px !important;
                font-size: 0.9rem;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }
        }

        @media (min-width: 992px) {
            .profile-text {
                display: block !important;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1.1rem;
            }

            .navbar-brand span {
                display: inline-block;
                max-width: 150px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            :root {
                --navbottom-height: 80px;
            }
        }

        @media (max-width: 360px) {
            :root {
                --navbottom-height: 75px;
            }
        }

        /* Desktop Sidebar Styles */
        .desktop-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: white;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.08);
            z-index: 1040;
            transition: var(--transition);
            overflow-y: auto;
            overflow-x: hidden;
            display: none;
        }

        @media (min-width: 992px) {
            .desktop-sidebar {
                display: block;
            }
        }

        /* Compact Sidebar */
        body.sidebar-compact .desktop-sidebar {
            width: var(--sidebar-compact-width);
        }

        /* Mobile Sidebar Toggle */
        .navbar-toggler {
            border: none;
            background: transparent;
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        @media (min-width: 992px) {
            .navbar-toggler {
                display: none;
            }
        }

        /* Mobile sidebar show (for tablet) */
        @media (max-width: 991px) {
            .desktop-sidebar {
                display: block;
                transform: translateX(-100%);
                width: 280px;
            }

            .desktop-sidebar.mobile-show {
                transform: translateX(0);
            }

            .desktop-sidebar.mobile-show+.navbar {
                padding-left: 280px;
                transition: padding-left 0.3s ease;
            }

            .desktop-sidebar.mobile-show~.main-content {
                padding-left: 280px;
                transition: padding-left 0.3s ease;
            }
        }

        /* Sidebar Container */
        .sidebar-container {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 20px 0;
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 0 25px 25px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            color: var(--primary-dark);
            font-weight: 700;
            font-size: 1.3rem;
            position: relative;
        }

        .sidebar-logo i {
            font-size: 1.8rem;
            margin-right: 12px;
            color: var(--primary-color);
        }

        .sidebar-logo-text {
            transition: var(--transition);
        }

        body.sidebar-compact .sidebar-logo-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar-toggle {
            position: absolute;
            right: -10px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1px solid rgba(0, 0, 0, 0.1);
            background: white;
            color: var(--text-light);
            font-size: 0.8rem;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-toggle:hover {
            background: var(--light-color);
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        body.sidebar-compact .sidebar-toggle {
            right: 10px;
        }

        /* Sidebar Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 0 15px;
        }

        .sidebar-item {
            margin-bottom: 8px;
            position: relative;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            text-decoration: none;
            border-radius: 12px;
            color: var(--text-light);
            transition: var(--transition);
            position: relative;
        }

        .sidebar-link:hover {
            background: rgba(29, 138, 78, 0.05);
            color: var(--primary-color);
        }

        .sidebar-item.active .sidebar-link {
            background: linear-gradient(135deg, rgba(29, 138, 78, 0.1) 0%, rgba(46, 204, 113, 0.1) 100%);
            color: var(--primary-color);
            font-weight: 600;
        }

        .sidebar-icon-wrapper {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.02);
            transition: var(--transition);
        }

        .sidebar-item.active .sidebar-icon-wrapper {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        }

        .sidebar-icon {
            font-size: 1.2rem;
            transition: var(--transition);
        }

        .sidebar-item.active .sidebar-icon {
            color: white;
        }

        .sidebar-label {
            margin-left: 15px;
            font-size: 0.95rem;
            transition: var(--transition);
            white-space: nowrap;
        }

        body.sidebar-compact .sidebar-label {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        /* Sidebar Tooltip for Compact Mode */
        .sidebar-tooltip {
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: var(--dark-color);
            color: white;
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 0.85rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            pointer-events: none;
            z-index: 9999;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar-tooltip::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border-width: 6px;
            border-style: solid;
            border-color: transparent var(--dark-color) transparent transparent;
        }

        body.sidebar-compact .sidebar-item:hover .sidebar-tooltip {
            opacity: 1;
            visibility: visible;
            left: calc(100% + 15px);
        }

        /* Logout Item */
        .logout-item {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Sidebar Scrollbar */
        .desktop-sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .desktop-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .desktop-sidebar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .desktop-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.2);
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Desktop Sidebar (only visible on desktop) -->
    <aside class="desktop-sidebar">
        <div class="sidebar-container">
            <!-- Logo Section -->
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-mosque"></i>
                    <span class="sidebar-logo-text">Keuangan Masjid</span>
                </div>
            </div>

            <!-- Navigation Items -->
            <nav class="sidebar-nav">
                <!-- Dashboard -->
                <div
                    class="sidebar-item {{ request()->routeIs('admins.dashboard') || request()->is('/') ? 'active' : '' }}">
                    <a href="{{ route('admins.dashboard') }}" class="sidebar-link">
                        <div class="sidebar-icon-wrapper">
                            <i class="fas fa-home sidebar-icon"></i>
                        </div>
                        <span class="sidebar-label">Dashboard</span>
                        <span class="sidebar-tooltip">Dashboard</span>
                    </a>
                </div>

                <!-- Manajemen Keuangan -->
                <div class="sidebar-item {{ request()->routeIs('admins.manajemen-keuangan') || request()->is('/') ? 'active' : '' }}">
                    <a href="{{ route('admins.manajemen-keuangan') }}" class="sidebar-link">
                        <div class="sidebar-icon-wrapper">
                            <i class="fas fa-sack-dollar sidebar-icon"></i>
                        </div>
                        <span class="sidebar-label"> Manajemen Keuangan</span>
                        <span class="sidebar-tooltip">Manajemen Keuangan</span>
                    </a>
                </div>

                <!-- Manajemen Laporan -->
                <div class="sidebar-item {{ request()->routeIs('admins.manajemen-laporan') || request()->is('/') ? 'active' : '' }}">
                    <a href="{{ route('admins.manajemen-laporan') }}" class="sidebar-link">
                        <div class="sidebar-icon-wrapper">
                            <i class="fas fa-receipt sidebar-icon"></i>
                        </div>
                        <span class="sidebar-label">Manajemen Laporan</span>
                        <span class="sidebar-tooltip">Manajemen Laporan</span>
                    </a>
                </div>

                <!-- Pengaturan Profil -->
                <div class="sidebar-item {{ request()->routeIs('admins.pengaturan-profil') || request()->is('/') ? 'active' : '' }}">
                    <a href="{{ route('admins.pengaturan-profil') }}" class="sidebar-link">
                        <div class="sidebar-icon-wrapper">
                            <i class="fas fa-user sidebar-icon"></i>
                        </div>
                        <span class="sidebar-label">Pengaturan Profil</span>
                        <span class="sidebar-tooltip">Pengaturan Profil</span>
                    </a>
                </div>

                <!-- Logout -->
                <div class="sidebar-item logout-item">
                    <a href="#" class="sidebar-link">
                        <div class="sidebar-icon-wrapper">
                            <i class="fas fa-sign-out-alt sidebar-icon"></i>
                        </div>
                        <span class="sidebar-label">Keluar</span>
                        <span class="sidebar-tooltip">Keluar</span>
                    </a>
                </div>
            </nav>
        </div>
    </aside>

    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container-fluid d-flex justify-content-between justify-content-lg-end">
            <a class="navbar-brand d-flex align-items-center d-md-none d-block" href="#">
                <i class="fas fa-mosque me-2"></i>
                <span>Keuangan Masjid</span>
            </a>

            <div class="d-flex align-items-center">
                <!-- Profile -->
                <div class="dropdown">
                    <button class="btn border-0 d-flex align-items-center" type="button" id="profileDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <!-- Profile text - hanya tampil di desktop -->
                        <div class="profile-text me-2 d-none d-md-block">
                            <div class="fw-bold text-dark">Admin Masjid</div>
                            <small class="text-muted">Administrator</small>
                        </div>
                        <!-- Profile icon -->
                        <div class="rounded-circle d-flex align-items-center justify-content-center profile-icon"
                            style="width: 40px; height: 40px; background: var(--primary-color);">
                            <i class="fas fa-user text-white"></i>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                        <li>
                            <h6 class="dropdown-header">Akun</h6>
                        </li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profil Saya</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt me-2"></i>
                                Keluar</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content container-fluid">
        @yield('content')
    </main>

    <!-- Mobile/Tablet Bottom Navigation -->
    @include('partials.navbottom')

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Update active state based on current page
            updateActiveNavItem();

            // Function to update active nav item
            function updateActiveNavItem() {
                const currentPath = window.location.pathname;

                // Update sidebar items
                const sidebarItems = document.querySelectorAll('.sidebar-item');
                sidebarItems.forEach(item => {
                    item.classList.remove('active');
                    const link = item.querySelector('a');
                    if (link) {
                        const href = link.getAttribute('href');
                        if (href && (currentPath.includes(href) || href.includes(currentPath))) {
                            item.classList.add('active');
                        }
                    }
                });

                // Update bottom nav items
                const navItems = document.querySelectorAll('.navbottom-item');

                // Remove active class from all items first
                navItems.forEach(item => {
                    item.classList.remove('active');
                    const label = item.querySelector('.navbottom-label');
                    if (label) {
                        label.style.display = 'none';
                        label.style.opacity = '0';
                        label.style.width = '0';
                    }
                });

                // Add active class to current item
                navItems.forEach(item => {
                    const link = item.querySelector('a');
                    if (link) {
                        // Check if href matches current path
                        const href = link.getAttribute('href');
                        if (href && (currentPath.includes(href) || href.includes(currentPath))) {
                            item.classList.add('active');

                            // Show label for active item
                            const label = item.querySelector('.navbottom-label');
                            if (label) {
                                label.style.display = 'block';
                                setTimeout(() => {
                                    label.style.opacity = '1';
                                    label.style.width = 'auto';
                                }, 10);
                            }

                            // Adjust other items
                            adjustOtherItems();
                        }
                    }
                });
            }

            // Function to adjust other non-active items
            function adjustOtherItems() {
                const activeItem = document.querySelector('.navbottom-item.active');
                const allItems = document.querySelectorAll('.navbottom-item:not(.active)');

                if (activeItem) {
                    // Add compact class to non-active items
                    allItems.forEach(item => {
                        item.classList.add('compact');
                    });
                } else {
                    // Remove compact class if no active item
                    allItems.forEach(item => {
                        item.classList.remove('compact');
                    });
                }
            }

            // Update on page load
            updateActiveNavItem();
        });
    </script>

    @stack('scripts')
</body>

</html>
