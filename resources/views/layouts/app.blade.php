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
            padding-bottom: 90px; /* Default untuk mobile */
        }

        @media (min-width: 768px) {
            body {
                padding-bottom: 95px; /* Tablet */
            }
        }

        @media (min-width: 992px) {
            body {
                padding-bottom: 100px; /* Desktop */
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
            min-height: calc(100vh - var(--navbar-height) - 90px);
            padding: 20px 15px;
        }

        @media (min-width: 768px) {
            .main-content {
                min-height: calc(100vh - var(--navbar-height) - 95px);
                padding: 30px 20px;
            }
        }

        @media (min-width: 992px) {
            .main-content {
                min-height: calc(100vh - var(--navbar-height) - 100px);
                padding: 40px 30px;
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
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
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
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center profile-icon"
                            style="width: 40px; height: 40px;">
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

    <!-- Bottom Navigation -->
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

            // Add active class to current nav item
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.navbottom-item');

            navItems.forEach(item => {
                const link = item.querySelector('a');
                if (link && link.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>