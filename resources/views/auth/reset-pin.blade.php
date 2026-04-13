@extends('layouts.auth')

@section('title', 'Reset PIN - Sistem Keuangan Masjid')

@section('content')
    <div class="login-container fade-in">
        <div class="login-card">
            <div class="login-card-header">
                <h2><i class="fas fa-lock"></i> Reset PIN</h2>
                <p>Buat PIN baru 4 digit</p>
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

                <form id="resetPinForm" method="POST" action="{{ route('auth.reset-pin.post') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label text-center mb-2">
                            <i class="fas fa-phone me-1"></i> Nomor Terdaftar: <strong>{{ $phone }}</strong>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="form-label text-center mb-2">
                            <i class="fas fa-key me-1"></i> PIN Baru 4 Digit
                        </label>

                        <div class="pin-input-container">
                            <div class="pin-input-wrapper">
                                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="pin-digit pin-new"
                                    data-index="0" autocomplete="off" autofocus required>
                                <div class="pin-dash">-</div>
                                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="pin-digit pin-new"
                                    data-index="1" autocomplete="off" required>
                                <div class="pin-dash">-</div>
                                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="pin-digit pin-new"
                                    data-index="2" autocomplete="off" required>
                                <div class="pin-dash">-</div>
                                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="pin-digit pin-new"
                                    data-index="3" autocomplete="off" required>
                            </div>
                            <input type="hidden" id="new_pin" name="new_pin" value="">
                            @error('new_pin')
                                <div class="pin-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <label class="form-label text-center mb-2">
                            <i class="fas fa-check-circle me-1"></i> Konfirmasi PIN Baru
                        </label>

                        <div class="pin-input-container">
                            <div class="pin-input-wrapper">
                                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="pin-digit pin-confirm"
                                    data-index="0" autocomplete="off" required>
                                <div class="pin-dash">-</div>
                                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="pin-digit pin-confirm"
                                    data-index="1" autocomplete="off" required>
                                <div class="pin-dash">-</div>
                                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="pin-digit pin-confirm"
                                    data-index="2" autocomplete="off" required>
                                <div class="pin-dash">-</div>
                                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="pin-digit pin-confirm"
                                    data-index="3" autocomplete="off" required>
                            </div>
                            <input type="hidden" id="confirm_pin" name="confirm_pin" value="">
                            @error('confirm_pin')
                                <div class="pin-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <p class="pin-hint">
                            <i class="fas fa-info-circle"></i> Masukkan ulang PIN yang sama
                        </p>
                    </div>

                    <button type="submit" class="btn-login" id="resetButton">
                        <i class="fas fa-save"></i>
                        <span>Simpan PIN Baru</span>
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

            .alert-success {
                background-color: #f0fff4;
                color: #28a745;
                border-left: 4px solid #28a745;
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

            .pin-input-container {
                text-align: center;
                margin-bottom: 15px;
            }

            .pin-input-wrapper {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 5px;
                margin-bottom: 10px;
            }

            .pin-digit {
                width: 60px;
                height: 60px;
                text-align: center;
                font-size: 1.8rem;
                font-weight: 700;
                border: 2px solid #e1e8ed;
                border-radius: 12px;
                background-color: white;
                color: var(--text-dark);
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                caret-color: transparent;
            }

            .pin-digit:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(29, 138, 78, 0.1);
                background-color: #f8fff8;
                transform: translateY(-2px);
            }

            .pin-digit.active {
                border-color: var(--primary-color);
                background-color: #f8fff8;
            }

            .pin-digit.error {
                border-color: #e74c3c;
                background-color: #fff5f5;
                animation: shake 0.5s ease;
            }

            .pin-digit.match {
                border-color: #28a745;
                background-color: #f0fff4;
            }

            .pin-dash {
                font-size: 1.5rem;
                font-weight: 600;
                color: var(--text-light);
                margin: 0 5px;
                user-select: none;
            }

            .pin-error {
                color: #e74c3c;
                font-size: 0.9rem;
                margin-top: 8px;
                min-height: 20px;
                text-align: center;
                animation: fadeIn 0.3s ease;
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

            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            @media (max-width: 768px) {
                .pin-digit {
                    width: 50px;
                    height: 50px;
                    font-size: 1.5rem;
                }

                .pin-dash {
                    font-size: 1.3rem;
                }
            }

            @media (max-width: 480px) {
                .pin-digit {
                    width: 45px;
                    height: 45px;
                    font-size: 1.3rem;
                }

                .pin-dash {
                    font-size: 1.1rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const resetPinForm = document.getElementById('resetPinForm');
                const resetButton = document.getElementById('resetButton');
                const newPinInputs = document.querySelectorAll('.pin-new');
                const confirmPinInputs = document.querySelectorAll('.pin-confirm');
                const hiddenNewPin = document.getElementById('new_pin');
                const hiddenConfirmPin = document.getElementById('confirm_pin');

                function getPin(inputs) {
                    return Array.from(inputs).map(input => input.value).join('');
                }

                function clearErrors() {
                    document.querySelectorAll('.pin-digit').forEach(input => {
                        input.classList.remove('error', 'match');
                    });
                    document.querySelectorAll('.pin-error').forEach(error => {
                        error.textContent = '';
                    });
                }

                // New PIN input handling
                newPinInputs.forEach((input, index) => {
                    input.addEventListener('input', function() {
                        clearErrors();
                        this.value = this.value.replace(/[^0-9]/g, '');
                        
                        if (this.value.length === 1 && index < 3) {
                            newPinInputs[index + 1].focus();
                        }
                        
                        hiddenNewPin.value = getPin(newPinInputs);
                    });

                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'Backspace') {
                            if (this.value === '' && index > 0) {
                                newPinInputs[index - 1].focus();
                            }
                        }
                    });
                });

                // Confirm PIN input handling
                confirmPinInputs.forEach((input, index) => {
                    input.addEventListener('input', function() {
                        clearErrors();
                        this.value = this.value.replace(/[^0-9]/g, '');
                        
                        if (this.value.length === 1 && index < 3) {
                            confirmPinInputs[index + 1].focus();
                        }
                        
                        hiddenConfirmPin.value = getPin(confirmPinInputs);
                        
                        // Check if matches new PIN
                        const newPin = hiddenNewPin.value;
                        const confirmPin = hiddenConfirmPin.value;
                        
                        if (newPin.length === 4 && confirmPin.length === 4) {
                            if (newPin === confirmPin) {
                                confirmPinInputs.forEach(inp => inp.classList.add('match'));
                            } else {
                                confirmPinInputs.forEach(inp => inp.classList.add('error'));
                            }
                        }
                    });

                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'Backspace') {
                            if (this.value === '' && index > 0) {
                                confirmPinInputs[index - 1].focus();
                            }
                        }
                    });
                });

                // Form submission
                resetPinForm.addEventListener('submit', function(e) {
                    const newPin = getPin(newPinInputs);
                    const confirmPin = getPin(confirmPinInputs);

                    if (newPin.length !== 4) {
                        newPinInputs[0].focus();
                        e.preventDefault();
                        return;
                    }

                    if (confirmPin.length !== 4) {
                        confirmPinInputs[0].focus();
                        e.preventDefault();
                        return;
                    }

                    if (newPin !== confirmPin) {
                        confirmPinInputs.forEach(input => input.classList.add('error'));
                        e.preventDefault();
                        return;
                    }

                    resetButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Menyimpan...</span>';
                    resetButton.classList.add('loading');
                    resetButton.disabled = true;
                });
            });
        </script>
    @endpush
@endsection
