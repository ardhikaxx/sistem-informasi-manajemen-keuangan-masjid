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

        /* =================== MODAL QRIS STYLES - IMPROVED =================== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 81, 50, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s ease;
            backdrop-filter: blur(8px);
            padding: 20px;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-container {
            background: white;
            border-radius: 25px;
            width: 100%;
            max-width: 500px;
            max-height: 95vh;
            overflow: hidden;
            transform: scale(0.8) translateY(30px);
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
        }

        .modal-overlay.active .modal-container {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 25px 30px;
            text-align: center;
            color: white;
            position: relative;
            flex-shrink: 0;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.2;
        }

        .modal-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
            position: relative;
            z-index: 1;
        }

        .modal-header p {
            font-size: 1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-content {
            padding: 30px;
            overflow-y: auto;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .modal-content::-webkit-scrollbar {
            width: 6px;
        }

        .modal-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .modal-content::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 3px;
        }

        .modal-content::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        .qris-image-container {
            margin: 0 auto 25px;
            padding: 15px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 2px dashed var(--light-color);
            max-width: 300px;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .qris-image-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 40%, rgba(232, 245, 233, 0.3) 50%, transparent 60%);
            background-size: 200% 200%;
            animation: shine 3s infinite linear;
        }

        @keyframes shine {
            0% {
                background-position: -100% -100%;
            }

            100% {
                background-position: 200% 200%;
            }
        }

        .qris-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
            display: block;
            position: relative;
            z-index: 1;
            transition: transform 0.5s ease;
            max-height: 380px;
            object-fit: contain;
        }

        .qris-image:hover {
            transform: scale(1.02);
        }

        .qris-instructions {
            background: var(--light-color);
            border-radius: 15px;
            padding: 20px;
            margin-top: 10px;
            text-align: left;
            flex-shrink: 0;
        }

        .qris-instructions h4 {
            color: var(--primary-dark);
            font-size: 1.2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qris-instructions h4 i {
            color: var(--primary-color);
        }

        .instructions-list {
            list-style: none;
            padding-left: 0;
        }

        .instructions-list li {
            padding: 8px 0;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: var(--text-light);
        }

        .instructions-list li i {
            color: var(--success-color);
            margin-top: 3px;
            flex-shrink: 0;
        }

        .modal-footer {
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #eee;
            background: white;
        }

        .donation-info {
            display: flex;
            justify-content: start;
            align-items: start;
            margin-bottom: 15px;
        }

        .donation-item {
            background: var(--light-color);
            padding: 10px 18px;
            border-radius: 50px;
            font-weight: 500;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .donation-item i {
            color: var(--primary-color);
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .modal-btn {
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .modal-btn.download {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .modal-btn.download:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(29, 138, 78, 0.3);
        }

        .modal-btn.close {
            background: transparent;
            color: var(--text-light);
            border: 2px solid #ddd;
        }

        .modal-btn.close:hover {
            background: #f8f9fa;
            color: var(--text-dark);
        }

        /* Animation for QRIS loading */
        @keyframes pulseQR {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(29, 138, 78, 0.4);
            }

            50% {
                box-shadow: 0 0 0 15px rgba(29, 138, 78, 0);
            }
        }

        .qris-loading {
            animation: pulseQR 2s infinite;
        }

        /* =================== END MODAL QRIS STYLES =================== */

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
            width: 100%;
        }

        .hero-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100%;
            margin-bottom: 50px;
        }

        .logo-container {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
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
            line-height: 1.5;
        }

        .financial-summary {
            width: 100%;
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            color: white;
        }

        .financial-summary h3 {
            color: var(--light-color);
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
            font-size: 2.2rem;
            position: relative;
        }

        .total-balance {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 10px;
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
            margin-top: 20px;
            width: 100%;
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

        /* =================== SECTION TENTANG MASJID =================== */
        .about-section {
            width: 100%;
            margin-top: 20px;
            margin-bottom: 50px;
            padding: 0 15px;
        }

        .section-title {
            text-align: center;
            color: white;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 4px;
            background: white;
            border-radius: 2px;
        }

        .glassmorphism-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            padding: 30px;
            color: white;
            transition: var(--transition);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            z-index: 1;
            min-height: 320px;
            display: flex;
            flex-direction: column;
        }

        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            z-index: -1;
            border-radius: 20px;
        }

        .glass-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .card-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
            transition: var(--transition);
        }

        .glass-card:hover .card-icon {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.1) rotate(5deg);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: white;
        }

        .card-content {
            font-size: 1rem;
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .card-highlight {
            background: rgba(255, 255, 255, 0.1);
            padding: 12px 15px;
            border-radius: 10px;
            font-size: 0.9rem;
            margin-top: 15px;
            border-left: 4px solid var(--accent-color);
            color: white;
        }

        /* Masjid History Section */
        .history-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            margin-top: 30px;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .history-title {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .history-content {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.8;
            font-size: 1.1rem;
        }

        .history-features {
            color: white;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 25px;
        }

        .history-feature {
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 15px;
            border-radius: 50px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* =================== END SECTION TENTANG MASJID =================== */

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

        /* =================== RESPONSIVE STYLES =================== */

        /* Desktop Large (≥1200px) */
        @media (min-width: 1200px) {
            .container-main {
                padding: 30px;
            }

            .title {
                font-size: 3.2rem;
            }

            .subtitle {
                font-size: 1.5rem;
                max-width: 800px;
            }

            .financial-summary {
                padding: 35px;
            }

            .total-balance .amount {
                font-size: 3rem;
            }

            .section-title {
                font-size: 2.5rem;
            }

            .glassmorphism-cards {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Desktop (≥992px) */
        @media (min-width: 992px) {
            .financial-details {
                flex-direction: row;
            }

            .financial-card {
                flex: 1;
                min-width: 0;
            }
        }

        /* Tablet (768px - 991px) */
        @media (max-width: 991px) and (min-width: 768px) {
            .title {
                font-size: 2.5rem;
            }

            .title::after {
                width: 280px;
            }

            .subtitle {
                font-size: 1.2rem;
                max-width: 550px;
                margin-bottom: 30px;
            }

            .financial-summary {
                padding: 25px 20px;
            }

            .financial-summary h3 {
                font-size: 1.6rem;
            }

            .total-balance .amount {
                font-size: 2.2rem;
            }

            .financial-details {
                flex-direction: row;
                gap: 15px;
            }

            .financial-card {
                padding: 18px;
            }

            .financial-card .value {
                font-size: 1.3rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .glassmorphism-cards {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .glass-card {
                padding: 25px;
            }

            .history-section {
                padding: 30px;
            }

            .menu-container {
                top: 15px;
                right: 15px;
            }

            .modal-container {
                max-width: 90%;
            }

            .modal-header h2 {
                font-size: 1.6rem;
            }

            .modal-content {
                padding: 25px 20px;
            }

            .modal-actions {
                gap: 10px;
            }

            .modal-btn {
                padding: 10px 20px;
            }
        }

        /* Mobile Large (576px - 767px) */
        @media (max-width: 767px) and (min-width: 576px) {
            body {
                padding-top: 70px;
            }

            .title {
                font-size: 2rem;
            }

            .title::after {
                width: 220px;
                bottom: -8px;
                height: 3px;
            }

            .subtitle {
                font-size: 1.1rem;
                padding: 0 15px;
                margin-bottom: 25px;
            }

            .container-main {
                padding: 15px;
            }

            .financial-summary {
                padding: 20px 15px;
                border-radius: 15px;
            }

            .financial-summary h3 {
                font-size: 1.5rem;
                padding-bottom: 10px;
            }

            .financial-summary h3::after {
                width: 80px;
            }

            .total-balance {
                padding: 20px 15px;
                margin-bottom: 20px;
                border-radius: 12px;
            }

            .total-balance h4 {
                font-size: 1.1rem;
            }

            .total-balance .amount {
                font-size: 2rem;
            }

            .financial-details {
                flex-direction: column;
                gap: 15px;
                margin-top: 15px;
            }

            .financial-card {
                padding: 18px 15px;
            }

            .financial-card .value {
                font-size: 1.4rem;
            }

            /* Tentang Masjid Responsive */
            .about-section {
                margin-bottom: 30px;
            }

            .section-title {
                font-size: 1.8rem;
                margin-bottom: 25px;
            }

            .section-title::after {
                width: 120px;
                height: 3px;
            }

            .glassmorphism-cards {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .glass-card {
                padding: 25px;
                min-height: 280px;
            }

            .card-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .card-title {
                font-size: 1.3rem;
            }

            .card-content {
                font-size: 0.95rem;
            }

            .history-section {
                padding: 25px 20px;
                margin-top: 20px;
            }

            .history-title {
                font-size: 1.5rem;
            }

            .history-content {
                font-size: 1rem;
            }

            .history-features {
                gap: 10px;
            }

            .menu-container {
                top: 15px;
                right: 15px;
            }

            .modal-container {
                max-width: 95%;
                max-height: 90vh;
            }

            .modal-header {
                padding: 20px 15px;
            }

            .modal-header h2 {
                font-size: 1.4rem;
            }

            .modal-header p {
                font-size: 0.9rem;
            }

            .modal-content {
                padding: 20px 15px;
            }

            .qris-image-container {
                padding: 12px;
                margin-bottom: 20px;
            }

            .qris-instructions {
                padding: 15px;
            }

            .qris-instructions h4 {
                font-size: 1.1rem;
            }

            .instructions-list li {
                font-size: 0.9rem;
            }

            .modal-footer {
                padding: 15px 20px;
            }

            .donation-info {
                justify-content: center;
                margin-bottom: 12px;
            }

            .donation-item {
                padding: 8px 15px;
                font-size: 0.85rem;
            }

            .modal-actions {
                flex-direction: row;
                gap: 10px;
            }

            .modal-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }

        /* Mobile Small (<576px) */
        @media (max-width: 575px) {
            body {
                padding-top: 60px;
                padding-bottom: 20px;
            }

            .title {
                font-size: 1.8rem;
                line-height: 1.2;
            }

            .title::after {
                width: 180px;
                bottom: -6px;
                height: 3px;
            }

            .subtitle {
                font-size: 1rem;
                padding: 0 10px;
                margin-bottom: 20px;
                line-height: 1.4;
            }

            .container-main {
                padding: 10px;
            }

            .financial-summary {
                padding: 18px 12px;
                border-radius: 12px;
            }

            .financial-summary h3 {
                font-size: 1.3rem;
                padding-bottom: 8px;
            }

            .financial-summary h3::after {
                width: 70px;
                height: 2px;
            }

            .total-balance {
                padding: 18px 12px;
                margin-bottom: 18px;
                border-radius: 10px;
            }

            .total-balance h4 {
                font-size: 1rem;
            }

            .total-balance .amount {
                font-size: 1.8rem;
            }

            .total-balance .subtext {
                font-size: 0.8rem;
            }

            .financial-details {
                flex-direction: column;
                gap: 12px;
                margin-top: 12px;
            }

            .financial-card {
                padding: 16px 12px;
                border-radius: 12px;
            }

            .financial-card .label {
                font-size: 0.8rem;
            }

            .financial-card .value {
                font-size: 1.3rem;
            }

            .financial-card .subtext {
                font-size: 0.75rem;
            }

            /* Tentang Masjid Responsive */
            .about-section {
                margin-bottom: 25px;
                padding: 0 10px;
            }

            .section-title {
                font-size: 1.6rem;
                margin-bottom: 20px;
                padding-bottom: 10px;
            }

            .section-title::after {
                width: 100px;
                height: 3px;
            }

            .glassmorphism-cards {
                grid-template-columns: 1fr;
                gap: 15px;
                margin-bottom: 25px;
            }

            .glass-card {
                padding: 20px;
                min-height: 260px;
                border-radius: 15px;
            }

            .card-icon {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
                margin-bottom: 15px;
            }

            .card-title {
                font-size: 1.2rem;
                margin-bottom: 12px;
            }

            .card-content {
                font-size: 0.9rem;
                line-height: 1.5;
            }

            .card-highlight {
                padding: 10px 12px;
                font-size: 0.85rem;
            }

            .history-section {
                padding: 20px 15px;
                border-radius: 15px;
                margin-top: 15px;
            }

            .history-title {
                font-size: 1.3rem;
                margin-bottom: 15px;
            }

            .history-content {
                font-size: 0.95rem;
                line-height: 1.6;
            }

            .history-features {
                gap: 8px;
                margin-top: 15px;
            }

            .history-feature {
                padding: 8px 12px;
                font-size: 0.8rem;
            }

            .access-buttons {
                flex-direction: column;
                gap: 12px;
                margin-top: 20px;
            }

            .btn-access {
                width: 100%;
                max-width: 280px;
                justify-content: center;
                padding: 12px 20px;
            }

            .footer {
                margin-top: 30px;
                padding: 15px 0;
                font-size: 0.8rem;
            }

            .menu-container {
                top: 10px;
                right: 10px;
            }

            .menu-toggle {
                width: 40px;
                height: 40px;
            }

            .menu-toggle i {
                font-size: 1.2rem;
            }

            .menu-items {
                top: 50px;
                min-width: 180px;
                right: -5px;
            }

            .menu-item {
                padding: 10px 14px;
            }

            .menu-item i {
                width: 25px;
                height: 25px;
                font-size: 0.9rem;
                margin-right: 10px;
            }

            .menu-item span {
                font-size: 0.9rem;
            }

            .modal-overlay {
                padding: 10px;
            }

            .modal-container {
                max-width: 100%;
                max-height: 95vh;
                border-radius: 20px;
            }

            .modal-header {
                padding: 15px 12px;
            }

            .modal-header h2 {
                font-size: 1.3rem;
            }

            .modal-header p {
                font-size: 0.85rem;
                width: 90% !important;
            }

            .modal-close {
                top: 12px;
                right: 12px;
                width: 35px;
                height: 35px;
            }

            .modal-content {
                padding: 15px 12px;
            }

            .qris-image-container {
                padding: 10px;
                margin-bottom: 15px;
            }

            .qris-instructions {
                padding: 12px;
            }

            .qris-instructions h4 {
                font-size: 1rem;
                margin-bottom: 12px;
            }

            .instructions-list li {
                font-size: 0.85rem;
                padding: 6px 0;
            }

            .instructions-list li i {
                margin-top: 2px;
            }

            .modal-footer {
                padding: 12px 15px;
            }

            .donation-info {
                justify-content: center;
                margin-bottom: 10px;
            }

            .donation-item {
                padding: 7px 12px;
                font-size: 0.8rem;
            }

            .modal-actions {
                flex-direction: column;
                gap: 8px;
            }

            .modal-btn {
                width: 100%;
                padding: 10px 15px;
                font-size: 0.9rem;
                justify-content: center;
            }

            .shape:nth-child(2),
            .shape:nth-child(3),
            .shape:nth-child(4),
            .shape:nth-child(5) {
                display: none; /* Hide some shapes on mobile for better performance */
            }
        }

        /* Very Small Mobile (≤360px) */
        @media (max-width: 360px) {
            .title {
                font-size: 1.6rem;
            }

            .title::after {
                width: 150px;
            }

            .subtitle {
                font-size: 0.95rem;
            }

            .financial-summary h3 {
                font-size: 1.2rem;
            }

            .total-balance .amount {
                font-size: 1.6rem;
            }

            .financial-card .value {
                font-size: 1.2rem;
            }

            .section-title {
                font-size: 1.4rem;
            }

            .glass-card {
                padding: 18px;
                min-height: 240px;
            }

            .menu-items {
                min-width: 160px;
            }

            .modal-header h2 {
                font-size: 1.2rem;
            }
        }

        /* Landscape Mode for Mobile */
        @media (max-height: 600px) and (orientation: landscape) {
            body {
                padding-top: 60px;
                padding-bottom: 10px;
            }

            .hero-section {
                padding: 10px 0;
                margin-bottom: 20px;
            }

            .title {
                font-size: 1.8rem;
                margin-bottom: 10px;
            }

            .subtitle {
                margin-bottom: 15px;
                font-size: 1rem;
            }

            .financial-summary {
                padding: 15px;
            }

            .total-balance {
                padding: 15px;
                margin-bottom: 15px;
            }

            .total-balance .amount {
                font-size: 1.8rem;
            }

            .financial-details {
                margin-top: 15px;
                flex-direction: row;
                gap: 10px;
            }

            .financial-card {
                padding: 12px;
            }

            /* Tentang Masjid Landscape */
            .about-section {
                margin-bottom: 20px;
            }

            .glassmorphism-cards {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .glass-card {
                min-height: 220px;
                padding: 15px;
            }

            .history-section {
                padding: 20px 15px;
            }

            .footer {
                margin-top: 20px;
                padding: 10px 0;
            }

            .modal-container {
                max-height: 85vh;
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
            <a href="{{ route('auth.login') }}" class="menu-item">
                <i class="fas fa-user-shield"></i>
                <span>Login Admin</span>
            </a>

            <button class="menu-item" id="qrcodeAmal">
                <i class="fas fa-qrcode"></i>
                <span>Amal QR Code</span>
            </button>
        </div>

        <div class="menu-overlay" id="menuOverlay"></div>
    </div>

    <!-- QRIS Modal -->
    <div class="modal-overlay" id="qrisModal">
        <div class="modal-container">
            <div class="modal-header">
                <button class="modal-close" id="modalClose">
                    <i class="fas fa-times"></i>
                </button>
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <h2><i class="fas fa-donate"></i> Donasi via QRIS</h2>
                    <p class="w-75">Scan QR Code dibawah berikut untuk berdonasi ke Masjid Jami' Al-Muttaqiin</p>
                </div>
            </div>

            <div class="modal-content">
                <div class="qris-image-container qris-loading">
                    <img src="{{ asset('assets/qris.jpg') }}" alt="QRIS Donation" class="qris-image" id="qrisImage">
                </div>

                <div class="qris-instructions">
                    <h4><i class="fas fa-info-circle"></i> Cara Donasi:</h4>
                    <ul class="instructions-list">
                        <li>
                            <i class="fas fa-mobile-alt"></i>
                            <span>Buka aplikasi e-wallet atau mobile banking yang mendukung QRIS</span>
                        </li>
                        <li>
                            <i class="fas fa-camera"></i>
                            <span>Pindai QR Code di atas menggunakan fitur scan di aplikasi Anda</span>
                        </li>
                        <li>
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Masukkan nominal donasi yang ingin Anda berikan</span>
                        </li>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <span>Konfirmasi pembayaran dan donasi Anda akan langsung tercatat</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="modal-footer">
                <div class="donation-info">
                    <div class="donation-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Transaksi Aman & Terjamin</span>
                    </div>
                </div>

                <div class="modal-actions">
                    <button class="modal-btn download" id="downloadQR">
                        <i class="fas fa-download"></i>
                        <span>Download QR</span>
                    </button>
                    <button class="modal-btn close" id="closeModalBtn">
                        <i class="fas fa-times"></i>
                        <span>Tutup</span>
                    </button>
                </div>
            </div>
        </div>
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

            <div class="financial-summary glass-card slide-up">
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

        <!-- =================== SECTION TENTANG MASJID =================== -->
        <section class="about-section fade-in">
            <h2 class="section-title">Tentang Masjid Jami' Al-Muttaqiin</h2>

            <div class="glassmorphism-cards">
                <div class="glass-card slide-up">
                    <div class="card-icon">
                        <i class="fas fa-mosque"></i>
                    </div>
                    <h3 class="card-title">Sejarah Masjid</h3>
                    <p class="card-content">
                        Masjid Jami' Al-Muttaqiin didirikan pada tahun 1985 sebagai pusat ibadah dan aktivitas keislaman di wilayah Jember. 
                        Sejak berdiri, masjid ini telah menjadi simbol persatuan umat dan pusat pembangunan karakter masyarakat muslim.
                    </p>
                    <div class="card-highlight">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Didirikan: 15 Agustus 1985</span>
                    </div>
                </div>

                <div class="glass-card slide-up" style="animation-delay: 0.2s">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="card-title">Fasilitas & Kegiatan</h3>
                    <p class="card-content">
                        Masjid ini dilengkapi dengan berbagai fasilitas modern termasuk tempat wudhu yang nyaman, ruang perkantoran, 
                        perpustakaan islami, dan aula serbaguna. Setiap harinya diadakan berbagai kegiatan keagamaan dan sosial.
                    </p>
                    <div class="card-highlight">
                        <i class="fas fa-check-circle"></i>
                        <span>Kapasitas: 1,500 jamaah</span>
                    </div>
                </div>

                <div class="glass-card slide-up" style="animation-delay: 0.4s">
                    <div class="card-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3 class="card-title">Program & Layanan</h3>
                    <p class="card-content">
                        Masjid menyelenggarakan berbagai program termasuk TPA, pengajian rutin, bantuan sosial, pembinaan remaja masjid, 
                        dan layanan konsultasi keagamaan. Kami juga aktif dalam kegiatan kemasyarakatan dan pembangunan umat.
                    </p>
                    <div class="card-highlight">
                        <i class="fas fa-star"></i>
                        <span>TPA: 150 anak aktif</span>
                    </div>
                </div>
            </div>

            <div class="history-section slide-up" style="animation-delay: 0.6s">
                <h3 class="history-title"><i class="fas fa-mosque"></i> Visi & Misi Masjid</h3>
                <div class="history-content">
                    <p><strong>Visi:</strong> Menjadi masjid yang mandiri, modern, dan menjadi pusat peradaban Islam yang berkontribusi positif bagi pembangunan umat dan bangsa.</p>
                    <p><strong>Misi:</strong></p>
                    <ul>
                        <li>Menyelenggarakan ibadah yang khusyuk dan bermakna</li>
                        <li>Mengembangkan pendidikan Islam yang berkualitas</li>
                        <li>Memberdayakan ekonomi umat melalui program produktif</li>
                        <li>Menjadi tempat silaturahmi dan pemersatu umat</li>
                        <li>Melaksanakan pengelolaan keuangan yang transparan dan akuntabel</li>
                    </ul>
                </div>
                <div class="history-features">
                    <div class="history-feature">
                        <i class="fas fa-clock"></i>
                        <span>5 Waktu Sholat Berjamaah</span>
                    </div>
                    <div class="history-feature">
                        <i class="fas fa-book-open"></i>
                        <span>Pengajian Rutin Harian</span>
                    </div>
                    <div class="history-feature">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Program Pendidikan</span>
                    </div>
                    <div class="history-feature">
                        <i class="fas fa-hands"></i>
                        <span>Layanan Sosial</span>
                    </div>
                </div>
            </div>
        </section>
        <!-- =================== END SECTION TENTANG MASJID =================== -->

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
        const qrcodeAmal = document.getElementById('qrcodeAmal');

        // QRIS Modal Elements
        const qrisModal = document.getElementById('qrisModal');
        const modalClose = document.getElementById('modalClose');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const downloadQR = document.getElementById('downloadQR');
        const qrisImage = document.getElementById('qrisImage');

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

        // Open QRIS Modal
        function openQRISModal() {
            qrisModal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }

        // Close QRIS Modal
        function closeQRISModal() {
            qrisModal.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        }

        // Download QR Image
        function downloadQRImage() {
            const imageUrl = qrisImage.src;
            const link = document.createElement('a');
            link.href = imageUrl;
            link.download = 'QRIS_Donasi_Masjid_Jami_Al-Muttaqiin.jpg';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Show success message
            alert('QR Code berhasil diunduh!');
        }

        // Event listeners for menu
        menuToggle.addEventListener('click', toggleMenu);
        menuOverlay.addEventListener('click', closeMenu);

        qrcodeAmal.addEventListener('click', function(e) {
            e.preventDefault();
            openQRISModal();
            closeMenu();
        });

        // QRIS Modal event listeners
        modalClose.addEventListener('click', closeQRISModal);
        closeModalBtn.addEventListener('click', closeQRISModal);
        downloadQR.addEventListener('click', downloadQRImage);

        // Close modal when clicking outside
        qrisModal.addEventListener('click', function(e) {
            if (e.target === qrisModal) {
                closeQRISModal();
            }
        });

        // Close menu and modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMenu();
                closeQRISModal();
            }
        });

        // Handle image loading and error
        qrisImage.addEventListener('load', function() {
            console.log('QRIS image loaded successfully');
            // Remove loading animation after image loads
            setTimeout(() => {
                document.querySelector('.qris-image-container').classList.remove('qris-loading');
            }, 500);
        });

        qrisImage.addEventListener('error', function() {
            console.error('Failed to load QRIS image');
            // Show placeholder if image fails to load
            qrisImage.src = 'https://via.placeholder.com/300x300/1D8A4E/FFFFFF?text=QRIS+Donasi';
            qrisImage.alt = 'QRIS placeholder - image not found';

            // Show error message
            const content = document.querySelector('.modal-content');
            const errorMsg = document.createElement('div');
            errorMsg.style.background = '#FFE5E5';
            errorMsg.style.color = '#D32F2F';
            errorMsg.style.padding = '10px';
            errorMsg.style.borderRadius = '5px';
            errorMsg.style.marginTop = '10px';
            errorMsg.innerHTML =
                '<i class="fas fa-exclamation-triangle"></i> Gambar QRIS tidak ditemukan. Pastikan file qris.jpg ada di folder assets.';
            content.appendChild(errorMsg);
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
            const options = {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };
            const dateElements = document.querySelectorAll('.subtext');
            if (dateElements.length > 1) {
                dateElements[0].textContent = `Terakhir diperbarui: ${now.toLocaleDateString('id-ID', options)}`;
            }

            // Add animation to glass cards
            const glassCards = document.querySelectorAll('.glass-card');
            glassCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.2}s`;
            });
        });

        // Handle responsive adjustments
        function handleResponsiveAdjustments() {
            const isMobile = window.innerWidth <= 767;
            const financialDetails = document.querySelector('.financial-details');
            
            if (isMobile) {
                // Adjust layout for mobile
                if (financialDetails) {
                    financialDetails.style.flexDirection = 'column';
                }
            } else {
                // Adjust layout for tablet/desktop
                if (financialDetails) {
                    financialDetails.style.flexDirection = 'row';
                }
            }
        }

        // Initial call
        handleResponsiveAdjustments();

        // Listen for window resize
        window.addEventListener('resize', handleResponsiveAdjustments);
    </script>
</body>

</html>