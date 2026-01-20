<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistem Keuangan Transparan Masjid Jami' Al-Muttaqiin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/logo-side.png') }}" type="image/x-icon">
    <style>
        :root {
            --primary-color: #1D8A4E; /* Hijau terang islami */
            --primary-dark: #146C43;
            --secondary-color: #28A745; /* Hijau cerah */
            --accent-color: #2ECC71; /* Hijau mint */
            --light-color: #E8F5E9; /* Hijau sangat muda */
            --dark-color: #0F5132; /* Hijau tua */
            --success-color: #27AE60; /* Hijau sukses */
            --warning-color: #F39C12; /* Oranye */
            --card-bg: rgba(255, 255, 255, 0.98);
            --text-dark: #1C2833;
            --text-light: #566573;
            --shadow: 0 10px 30px rgba(29, 138, 78, 0.15);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100svh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            padding-top: 80px;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("{{ asset('assets/bg-image.jpeg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(5px) brightness(0.9);
            z-index: -2;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(29, 138, 78, 0.9) 10%, rgba(29, 138, 78, 0.4) 70%);
            z-index: -1;
        }

        /* =================== MENU BAR STYLES =================== */
        .menu-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .menu-toggle {
            width: 50px;
            height: 50px;
            background-color: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            z-index: 1001;
        }

        .menu-toggle:hover {
            background-color: var(--primary-dark);
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }

        .menu-toggle.active {
            background-color: var(--primary-color);
            transform: rotate(90deg);
        }

        .menu-toggle i {
            color: white;
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }

        .menu-items {
            position: absolute;
            top: 60px;
            right: 0;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 10px;
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px) scale(0.95);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 1000;
        }

        .menu-items.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .menu-items::before {
            content: '';
            position: absolute;
            top: -8px;
            right: 20px;
            width: 16px;
            height: 16px;
            background-color: white;
            transform: rotate(45deg);
            border-radius: 3px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            margin: 5px 0;
            border-radius: 50px;
            text-decoration: none;
            color: var(--text-dark);
            transition: all 0.3s ease;
            background-color: white;
            border: 1px solid transparent;
        }

        .menu-item i {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 1rem;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .menu-item:hover i {
            transform: scale(1.2);
        }

        .menu-item span {
            font-weight: 500;
            font-size: 0.95rem;
            flex-grow: 1;
        }

        .menu-item .badge {
            background-color: var(--light-color);
            color: var(--primary-color);
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 600;
        }

        /* Animasi untuk item menu */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .menu-item:nth-child(1) {
            animation: slideInRight 0.3s ease 0.1s both;
        }

        .menu-item:nth-child(2) {
            animation: slideInRight 0.3s ease 0.2s both;
        }

        /* Overlay untuk menutup menu saat klik di luar */
        .menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0);
            z-index: 999;
            visibility: hidden;
            transition: background-color 0.3s ease;
        }

        .menu-overlay.active {
            background-color: rgba(0, 0, 0, 0.1);
            visibility: visible;
        }

        /* =================== END MENU BAR STYLES =================== */

        .background-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .shape {
            position: absolute;
            opacity: 0.5;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            animation: float 15s infinite ease-in-out;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
            background: rgba(40, 167, 69, 0.3);
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 70%;
            left: 80%;
            animation-delay: 2s;
            background: rgba(232, 245, 233, 0.3);
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 20%;
            left: 85%;
            animation-delay: 4s;
            background: rgba(29, 138, 78, 0.3);
        }

        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            top: 80%;
            left: 15%;
            animation-delay: 6s;
            background: rgba(46, 204, 113, 0.3);
        }

        .shape:nth-child(5) {
            width: 50px;
            height: 50px;
            top: 60%;
            left: 5%;
            animation-delay: 8s;
            background: rgba(39, 174, 96, 0.3);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(10deg);
            }
        }

        .container-main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .hero-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .logo-container {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .logo-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 25px;
        }

        .logo-image {
            max-height: 150px;
            width: auto;
        }

        @media (max-width: 576px) {
            .logo-image {
                max-height: 80px;
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .title {
            color: white;
            font-weight: 800;
            margin-bottom: 15px;
            font-size: 2.8rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            position: relative;
            display: inline-block;
        }

        .title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 350px;
            height: 4px;
            background: white;
            border-radius: 2px;
        }

        .subtitle {
            color: #fafafa;
            font-size: 1.3rem;
            margin-bottom: 40px;
            font-weight: 600;
            max-width: 600px;
            text-align: center;
        }

        .financial-summary {
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            box-shadow: var(--shadow);
        }

        .financial-summary h3 {
            color: var(--primary-dark);
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
            font-size: 1.8rem;
            position: relative;
            padding-bottom: 14px;
        }

        .financial-summary h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }

        .total-balance {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(29, 138, 78, 0.3);
        }

        .total-balance h4 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            font-weight: 500;
            opacity: 0.9;
        }

        .total-balance .amount {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .financial-details {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: row;
            margin-top: 20px;
        }

        .financial-card {
            width: 100%;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: var(--transition);
        }

        .financial-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .financial-card.income {
            border-top: 4px solid var(--success-color);
        }

        .financial-card.expense {
            border-top: 4px solid #E74C3C;
        }

        .financial-card .label {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .financial-card .value {
            color: var(--text-dark);
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .financial-card .value.income {
            color: var(--success-color);
        }

        .financial-card .value.expense {
            color: #E74C3C;
        }

        .financial-card .subtext {
            color: var(--text-light);
            font-size: 0.8rem;
            margin-top: 5px;
            font-weight: 500;
        }

        .transparency-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            margin-top: 20px;
            font-size: 0.9rem;
            backdrop-filter: blur(10px);
        }

        .transparency-badge i {
            color: var(--light-color);
        }

        .access-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn-access {
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-admin {
            background: var(--dark-color);
            color: white;
            border: 2px solid var(--dark-color);
        }

        .btn-admin:hover {
            background: transparent;
            color: var(--dark-color);
            border-color: var(--dark-color);
        }

        .btn-public {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-public:hover {
            background: white;
            color: var(--dark-color);
        }

        /* Update existing styles */
        .footer {
            margin-top: 50px;
            color: white;
            text-align: center;
            font-size: 0.9rem;
            opacity: 0.9;
            padding: 20px 0;
        }

        .fade-in {
            animation: fadeIn 1s ease-out;
        }

        .slide-up {
            animation: slideUp 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .title {
                font-size: 1.5rem;
            }

            .subtitle {
                font-size: 1.2rem;
            }

            .financial-summary {
                padding: 20px;
                margin: 20px 15px;
            }

            .total-balance .amount {
                font-size: 2rem;
            }

            .financial-details {
                grid-template-columns: 1fr;
            }

            .access-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-access {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
            
            /* Responsive menu */
            .menu-container {
                top: 15px;
                right: 15px;
            }
            
            .menu-items {
                min-width: 200px;
            }
        }

        @media (max-width: 576px) {
            .title {
                font-size: 1.8rem;
            }

            .title::after {
                width: 200px;
            }

            .financial-summary h3 {
                font-size: 1.5rem;
            }

            .total-balance {
                padding: 20px;
            }
            
            .menu-toggle {
                width: 45px;
                height: 45px;
            }
            
            .menu-items {
                top: 55px;
                right: -5px;
            }
        }
    </style>
</head>

<body>
    <!-- Menu Bar Container -->
    <div class="menu-container">
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="menu-items" id="menuItems">
            <a href="#" class="menu-item" id="loginAdmin">
                <i class="fas fa-user-shield"></i>
                <span>Login Admin</span>
            </a>
            
            <a href="#" class="menu-item" id="qrcodeAmal">
                <i class="fas fa-qrcode"></i>
                <span>Amal QR Code</span>
            </a>
        </div>
        
        <div class="menu-overlay" id="menuOverlay"></div>
    </div>

    <div class="background-animation">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
    </div>

    <div class="container-main">
        <section class="hero-section fade-in">
            <div class="logo-container">
                <h4 class="fw-medium text-white">Sistem Keuangan</h4>
                <h1 class="title fw-bolder text-uppercase">Masjid Jami' Al-Muttaqiin</h1>
                <p class="subtitle slide-up">Jl. MT. Haryono No.46, Sumber Beringin, Karangrejo, Kec. Sumbersari,
                    Kabupaten Jember, Jawa Timur 68124</p>
            </div>

            <div class="financial-summary slide-up">
                <h3>Total Keuangan Masjid</h3>
                
                <div class="total-balance">
                    <h4>Saldo Saat Ini</h4>
                    <div class="amount">Rp 125.450.750</div>
                    <div class="subtext mt-2">Terakhir diperbarui: {{ date('d F Y') }}</div>
                </div>

                <div class="financial-details">
                    <div class="financial-card income">
                        <div class="label">Total Pemasukan</div>
                        <div class="value income">Rp 189.250.500</div>
                        <div class="subtext">Tahun {{ date('Y') }}</div>
                    </div>
                    
                    <div class="financial-card expense">
                        <div class="label">Total Pengeluaran</div>
                        <div class="value expense">Rp 63.799.750</div>
                        <div class="subtext">Tahun {{ date('Y') }}</div>
                    </div>
                </div>

            </div>
        </section>

        <div class="footer fade-in">
            <p>&copy; {{ date('Y') }} Masjid Jami' Al-Muttaqiin. All rights reserved.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Format number to Indonesian currency
        function formatCurrency(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        // Menu Toggle Functionality
        const menuToggle = document.getElementById('menuToggle');
        const menuItems = document.getElementById('menuItems');
        const menuOverlay = document.getElementById('menuOverlay');
        const loginAdmin = document.getElementById('loginAdmin');
        const qrcodeAmal = document.getElementById('qrcodeAmal');

        // Toggle menu open/close
        function toggleMenu() {
            const isOpen = menuItems.classList.contains('show');
            
            if (!isOpen) {
                menuToggle.classList.add('active');
                menuItems.classList.add('show');
                menuOverlay.classList.add('active');
                menuToggle.innerHTML = '<i class="fas fa-times"></i>';
            } else {
                menuToggle.classList.remove('active');
                menuItems.classList.remove('show');
                menuOverlay.classList.remove('active');
                menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            }
        }

        // Close menu when clicking outside
        function closeMenu() {
            menuToggle.classList.remove('active');
            menuItems.classList.remove('show');
            menuOverlay.classList.remove('active');
            menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
        }

        // Event listeners for menu
        menuToggle.addEventListener('click', toggleMenu);
        menuOverlay.addEventListener('click', closeMenu);

        // Menu item click handlers
        loginAdmin.addEventListener('click', function(e) {
            e.preventDefault();
            alert('Fitur Login Admin akan diarahkan ke halaman login');
            closeMenu();
            
            // Simulate redirect (in real app, use window.location.href)
            console.log('Redirect to admin login page');
        });

        qrcodeAmal.addEventListener('click', function(e) {
            e.preventDefault();
            alert('Fitur Amal QR Code akan membuka QR code untuk donasi');
            closeMenu();
            
            // Simulate QR code display (in real app, show modal with QR)
            console.log('Show QR code for donations');
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMenu();
            }
        });

        // Update totals with animation
        document.addEventListener('DOMContentLoaded', function() {
            const balanceElement = document.querySelector('.total-balance .amount');
            const incomeElement = document.querySelector('.financial-card.income .value');
            const expenseElement = document.querySelector('.financial-card.expense .value');
            
            // These values should come from your backend
            const totals = {
                balance: 125450750,
                income: 189250500,
                expense: 63799750
            };

            // Animate numbers (optional - can be removed if not needed)
            [balanceElement, incomeElement, expenseElement].forEach((el, index) => {
                const value = Object.values(totals)[index];
                el.textContent = formatCurrency(value);
            });

            // Update date
            const now = new Date();
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            const dateElements = document.querySelectorAll('.subtext');
            if (dateElements.length > 1) {
                dateElements[0].textContent = `Terakhir diperbarui: ${now.toLocaleDateString('id-ID', options)}`;
            }
        });
    </script>
</body>

</html>