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
            --primary-color: #01747B;
            --primary-dark: #005a60;
            --secondary-color: #00ABB5;
            --accent-color: #00D2DF;
            --light-color: #B5FBFF;
            --dark-color: #004F54;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --card-bg: rgba(255, 255, 255, 0.95);
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
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
            z-index: -2;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, var(--primary-color) 10%, rgba(1, 116, 123, 0) 70%);
            z-index: -1;
        }

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
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            animation: float 15s infinite ease-in-out;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 70%;
            left: 80%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 20%;
            left: 85%;
            animation-delay: 4s;
        }

        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            top: 80%;
            left: 15%;
            animation-delay: 6s;
        }

        .shape:nth-child(5) {
            width: 50px;
            height: 50px;
            top: 60%;
            left: 5%;
            animation-delay: 8s;
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
            min-height: 80vh;
            text-align: center;
            padding: 40px 0;
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
            font-weight: 700;
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
            background: #ffff;
            border-radius: 2px;
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.3rem;
            margin-bottom: 40px;
            font-weight: 400;
            max-width: 600px;
            text-align: center;
        }

        .financial-summary {
            width: 100%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }

        .financial-summary h3 {
            color: white;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
            font-size: 1.8rem;
            position: relative;
            padding-bottom: 14px;
        }

        .total-balance {
            background: linear-gradient(135deg, var(--success-color), #20c997);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(32, 201, 151, 0.3);
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
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .financial-card.income {
            border-top: 4px solid var(--success-color);
        }

        .financial-card.expense {
            border-top: 4px solid #dc3545;
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
            color: #dc3545;
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
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            margin-top: 20px;
            font-size: 0.9rem;
            backdrop-filter: blur(10px);
        }

        .transparency-badge i {
            color: var(--accent-color);
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
            color: white;
            border-color: white;
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
            opacity: 0.8;
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
        }
    </style>
</head>

<body>
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