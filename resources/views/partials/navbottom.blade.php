<!-- Floating Bottom Navigation -->
<nav class="navbottom">
    <div class="navbottom-container">
        <div class="navbottom-wrapper">
            <!-- Dashboard -->
            <div class="navbottom-item {{ request()->routeIs('admins.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admins.dashboard') }}" class="navbottom-link">
                    <div class="navbottom-icon-wrapper">
                        <i class="fas fa-home navbottom-icon"></i>
                    </div>
                </a>
            </div>
            
            <!-- Manajemen Keuangan -->
            <div class="navbottom-item">
                <a href="#" class="navbottom-link">
                    <div class="navbottom-icon-wrapper">
                        <i class="fas fa-money-bill-wave navbottom-icon"></i>
                    </div>
                </a>
            </div>
            
            <!-- Manajemen Laporan -->
            <div class="navbottom-item">
                <a href="#" class="navbottom-link">
                    <div class="navbottom-icon-wrapper">
                        <i class="fas fa-file-alt navbottom-icon"></i>
                    </div>
                </a>
            </div>
            
            <!-- Profile -->
            <div class="navbottom-item">
                <a href="#" class="navbottom-link">
                    <div class="navbottom-icon-wrapper">
                        <i class="fas fa-user navbottom-icon"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Floating Bottom Navigation */
    .navbottom {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1030;
        width: calc(100% - 32px);
        max-width: 480px;
        padding: 0;
    }
    
    .navbottom-container {
        background: #FFFFFF;
        border-radius: 28px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 12px 16px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    .navbottom-wrapper {
        display: flex;
        justify-content: space-around;
        align-items: center;
        gap: 8px;
    }
    
    .navbottom-item {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .navbottom-link {
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        width: 100%;
        height: 100%;
        padding: 4px;
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
    
    .navbottom-icon {
        font-size: 20px;
        color: #8E8E93;
        transition: all 0.3s ease;
    }
    
    /* Active State */
    .navbottom-item.active .navbottom-icon-wrapper {
        background: linear-gradient(135deg, rgba(29, 138, 78, 0.15) 0%, rgba(46, 204, 113, 0.15) 100%);
    }
    
    .navbottom-item.active .navbottom-icon {
        color: var(--primary-color);
        transform: scale(1.1);
    }
    
    /* Hover Effect */
    .navbottom-item:not(.active):hover .navbottom-icon-wrapper {
        background: rgba(142, 142, 147, 0.08);
        transform: scale(1.05);
    }
    
    .navbottom-item:not(.active):hover .navbottom-icon {
        color: var(--primary-color);
    }
    
    /* Mobile Extra Small (max-width: 360px) */
    @media (max-width: 360px) {
        .navbottom {
            bottom: 16px;
            width: calc(100% - 24px);
        }
        
        .navbottom-container {
            padding: 10px 12px;
            border-radius: 24px;
        }
        
        .navbottom-wrapper {
            gap: 4px;
        }
        
        .navbottom-icon-wrapper {
            width: 42px;
            height: 42px;
        }
        
        .navbottom-icon {
            font-size: 18px;
        }
    }
    
    /* Mobile Small (361px - 375px) */
    @media (min-width: 361px) and (max-width: 375px) {
        .navbottom {
            bottom: 18px;
            width: calc(100% - 28px);
        }
        
        .navbottom-container {
            padding: 11px 14px;
            border-radius: 26px;
        }
        
        .navbottom-wrapper {
            gap: 6px;
        }
        
        .navbottom-icon-wrapper {
            width: 44px;
            height: 44px;
        }
        
        .navbottom-icon {
            font-size: 19px;
        }
    }
    
    /* Mobile Medium (376px - 575px) */
    @media (min-width: 376px) and (max-width: 575px) {
        .navbottom {
            bottom: 20px;
            width: calc(100% - 32px);
            max-width: 500px;
        }
        
        .navbottom-container {
            padding: 12px 16px;
            border-radius: 28px;
        }
        
        .navbottom-wrapper {
            gap: 8px;
        }
        
        .navbottom-icon-wrapper {
            width: 48px;
            height: 48px;
        }
        
        .navbottom-icon {
            font-size: 20px;
        }
    }
    
    /* Tablet Small (576px - 767px) */
    @media (min-width: 576px) and (max-width: 767px) {
        .navbottom {
            bottom: 24px;
            width: calc(100% - 40px);
            max-width: 540px;
        }
        
        .navbottom-container {
            padding: 14px 20px;
            border-radius: 30px;
        }
        
        .navbottom-wrapper {
            gap: 12px;
        }
        
        .navbottom-icon-wrapper {
            width: 52px;
            height: 52px;
        }
        
        .navbottom-icon {
            font-size: 22px;
        }
    }
    
    /* Tablet Medium (768px - 991px) */
    @media (min-width: 768px) and (max-width: 991px) {
        .navbottom {
            bottom: 28px;
            width: auto;
            max-width: 580px;
        }
        
        .navbottom-container {
            padding: 15px 24px;
            border-radius: 32px;
        }
        
        .navbottom-wrapper {
            gap: 16px;
        }
        
        .navbottom-icon-wrapper {
            width: 54px;
            height: 54px;
        }
        
        .navbottom-icon {
            font-size: 23px;
        }
    }
    
    /* Desktop Small (992px - 1199px) */
    @media (min-width: 992px) and (max-width: 1199px) {
        .navbottom {
            bottom: 30px;
            max-width: 600px;
        }
        
        .navbottom-container {
            padding: 16px 28px;
            border-radius: 34px;
        }
        
        .navbottom-wrapper {
            gap: 20px;
        }
        
        .navbottom-icon-wrapper {
            width: 56px;
            height: 56px;
        }
        
        .navbottom-icon {
            font-size: 24px;
        }
    }
    
    /* Desktop Medium (1200px - 1399px) */
    @media (min-width: 1200px) and (max-width: 1399px) {
        .navbottom {
            bottom: 32px;
            max-width: 620px;
        }
        
        .navbottom-container {
            padding: 17px 30px;
            border-radius: 35px;
        }
        
        .navbottom-wrapper {
            gap: 24px;
        }
        
        .navbottom-icon-wrapper {
            width: 58px;
            height: 58px;
        }
        
        .navbottom-icon {
            font-size: 25px;
        }
    }
    
    /* Desktop Large (1400px+) */
    @media (min-width: 1400px) {
        .navbottom {
            bottom: 35px;
            max-width: 640px;
        }
        
        .navbottom-container {
            padding: 18px 32px;
            border-radius: 36px;
        }
        
        .navbottom-wrapper {
            gap: 28px;
        }
        
        .navbottom-icon-wrapper {
            width: 60px;
            height: 60px;
        }
        
        .navbottom-icon {
            font-size: 26px;
        }
    }
    
    /* Landscape Mode for Mobile */
    @media (max-height: 500px) and (orientation: landscape) {
        .navbottom {
            bottom: 12px;
        }
        
        .navbottom-container {
            padding: 8px 14px;
            border-radius: 22px;
        }
        
        .navbottom-wrapper {
            gap: 6px;
        }
        
        .navbottom-icon-wrapper {
            width: 40px;
            height: 40px;
        }
        
        .navbottom-icon {
            font-size: 18px;
        }
    }
    
    /* Landscape Mode for Tablet */
    @media (min-height: 501px) and (max-height: 700px) and (orientation: landscape) {
        .navbottom {
            bottom: 16px;
        }
        
        .navbottom-container {
            padding: 10px 18px;
            border-radius: 26px;
        }
        
        .navbottom-wrapper {
            gap: 10px;
        }
        
        .navbottom-icon-wrapper {
            width: 44px;
            height: 44px;
        }
        
        .navbottom-icon {
            font-size: 20px;
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
    
    .navbottom-icon-wrapper::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: var(--primary-color);
        opacity: 0;
        pointer-events: none;
    }
</style>