@extends('layouts.auth')

@section('title', 'Lupa PIN - Sistem Keuangan Masjid')

@section('content')
    <div class="login-container fade-in">
        <div class="login-card">
            <div class="login-card-header">
                <h2><i class="fas fa-key"></i> Lupa PIN</h2>
                <p>Masukkan nomor telepon terdaftar</p>
            </div>

            <div class="login-card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form id="forgotPinForm" method="POST" action="{{ route('auth.verify-phone') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label text-center mb-2">
                            <i class="fas fa-phone me-1"></i> Nomor Telepon
                        </label>
                        
                        <div class="phone-input-container">
                            <input type="text" 
                                   class="form-control phone-input" 
                                   name="nomor_telfon" 
                                   id="phoneInput"
                                   placeholder="Contoh: 081234567890"
                                   value="{{ old('nomor_telfon') }}"
                                   autocomplete="tel"
                                   required>
                            @error('nomor_telfon')
                                <div class="phone-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <p class="pin-hint">
                            <i class="fas fa-info-circle"></i> Masukkan nomor telepon yang terdaftar di sistem
                        </p>
                    </div>

                    <button type="submit" class="btn-login" id="verifyButton">
                        <i class="fas fa-arrow-right"></i>
                        <span>Verifikasi & Lanjutkan</span>
                    </button>

                    <div class="login-options">
                        <a class="back-options" href="{{ route('auth.login') }}">
                            <i class="fas fa-arrow-left"></i> Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="login-footer">
            <p>&copy; {{ date('Y') }} Sistem Keuangan Masjid Jami' Al-Muttaqiin</p>
        </div>
    </div>

    @push('styles')
        <style>
            .alert {
                border-radius: 10px;
                border: none;
                padding: 15px;
                margin-bottom: 20px;
                animation: slideDown 0.3s ease;
            }

            .alert-danger {
                background-color: #fff5f5;
                color: #e74c3c;
                border-left: 4px solid #e74c3c;
            }

            .btn-close {
                padding: 1rem;
                margin-top: -0.375rem;
            }

            @keyframes slideDown {
                from {
                    transform: translateY(-10px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .phone-input-container {
                margin-bottom: 15px;
            }

            .phone-input {
                width: 100%;
                padding: 15px;
                font-size: 1.1rem;
                border: 2px solid #e1e8ed;
                border-radius: 12px;
                background-color: white;
                color: var(--text-dark);
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                text-align: center;
                font-weight: 500;
            }

            .phone-input:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(29, 138, 78, 0.1);
                background-color: #f8fff8;
            }

            .phone-input.error {
                border-color: #e74c3c;
                background-color: #fff5f5;
            }

            .phone-error {
                color: #e74c3c;
                font-size: 0.9rem;
                margin-top: 8px;
                text-align: center;
            }

            .pin-hint {
                color: var(--text-light);
                font-size: 0.9rem;
                text-align: center;
                margin-top: 10px;
                margin-bottom: 0;
            }

            .pin-hint i {
                color: var(--primary-color);
                margin-right: 5px;
            }

            .btn-login.loading {
                opacity: 0.8;
                cursor: not-allowed;
            }

            @media (max-width: 768px) {
                .phone-input {
                    font-size: 1rem;
                    padding: 12px;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const forgotPinForm = document.getElementById('forgotPinForm');
                const verifyButton = document.getElementById('verifyButton');
                const phoneInput = document.getElementById('phoneInput');

                // Auto-format phone number
                phoneInput.addEventListener('input', function() {
                    this.classList.remove('error');
                    
                    // Only allow numbers, +, -, and spaces
                    this.value = this.value.replace(/[^0-9+\-\s]/g, '');
                });

                // Form submission
                forgotPinForm.addEventListener('submit', function(e) {
                    const phone = phoneInput.value.trim();

                    if (!phone) {
                        phoneInput.classList.add('error');
                        phoneInput.focus();
                        e.preventDefault();
                        return;
                    }

                    // Show loading state
                    verifyButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Memverifikasi...</span>';
                    verifyButton.classList.add('loading');
                    verifyButton.disabled = true;
                });
            });
        </script>
    @endpush
@endsection
